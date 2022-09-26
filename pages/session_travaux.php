<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'>";
$l2="Site web";
$l3="Sujet";
$l4="Patronymes";
$mod="Modifier";
$sup="Supprimer";

$rek_ch="select travaux.* FROM `travaux` WHERE travaux.idch_tr=".$_SESSION['_id']." ORDER BY id_tr";
try {$i_ch=$bdd->query($rek_ch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content.="<div class='mb-4'>";
$content.="<h3>Travaux indexés par \"".$_SESSION['pseudo']."\"</h3>"; // row contenant et colonne principale
$content.="</div>"; // mb pour quasiment tous les "paragraphes"

$content.="<div class='mb-4'>";
if($i_ch->rowCount()>0){
    $content.="<table class='table table-sm'>
    <thead>
    <tr>
        <th scope='col'></th>
        <th scope='col'></th>
        <th scope='col'>Commune</th>
        <th scope='col'>Sujet</th>
        <th scope='col'>Patronymes</th>
    </tr>
    </thead>
    <tbody>";
    while ($lc=$i_ch->fetch(PDO::FETCH_ASSOC)){
        $content.="<tr>
               <td class='mt-0 pt-0'><a href='accueil1.php?disp=session_travail_edit&num_tr=".$lc["id_tr"]."' title='Éditer cette référence'><img src='../img/button_edit.png'></a>";
                    // on envoie le n° du travail
        $content.="<td class='mt-0 pt-0'><a href='accueil1.php?disp=session_travail_delete&num_tr=".$lc["id_tr"]."' title='Supprimer cette référence'
               onclick='return confirmLink(this, &#039supprimer cette référence ?&#039)'><img src='../img/button_drop.png'></a><td>";
        $sql="SELECT DISTINCT ID_LOC, LIB_LOC from locality,trav_com WHERE locality.ID_LOC=trav_com.comm AND trav_com.trav=".$lc["id_tr"];
        try {$id_com=$bdd->query($sql);}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $communes="";
        while($l_com=$id_com->fetch(PDO::FETCH_ASSOC)){
            $communes.="<a href='accueil1.php?disp=entites_commune&ent=".$l_com["ID_LOC"]."'>".$l_com["LIB_LOC"]."</a>, ";
        }
        $content.= substr($communes,0,-2)."<td>".stripslashes($lc["tit_tr"])."<td>";
                                               /*Affichage des familles*/
        $rek_f="select famil_trav.* from `famil_trav` where famil_trav.idtr_ft=".$lc["id_tr"];
        try {$id_f=$bdd->query($rek_f);}
            catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $nb_f= $id_f->rowCount();
        for ($f=0;$f<$nb_f-1;$f++) {
                $lf=$id_f->fetch(PDO::FETCH_ASSOC);
                $content.= $lf["famil_ft"].", ";
            }
        $lf=$id_f->fetch(PDO::FETCH_ASSOC);
        $content.= $lf["famil_ft"];
    }
    $content.="</table>";
    $autre=" nouveau";
    $content.="</tbody></table>";
}
else $content.="Vous n'avez indexé aucun travail.";
$content.="</div>";
$content.="<div><a type='button' class='btn btn-secondary fs-5' href='accueil1.php?disp=session_travail_edit'>Indexer un".$autre." travail</a></div>";
$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>