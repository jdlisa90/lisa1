<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$avert="Il s'agit ici d'une INDEXATION de travaux réalisés par différents chercheurs.
  LISA n'assure pas la présentation du contenu de ces travaux. Pour en prendre connaissance, il vous faudra prendre contact avec le chercheur, par notre intermédiaire.";
extract($_POST,EXTR_OVERWRITE);

$sql="SELECT DISTINCT chercheurs.id_ch, chercheurs.nom_ch,travaux.tit_tr,travaux.id_tr
FROM `famil_trav` LEFT JOIN `travaux` ON famil_trav.idtr_ft=travaux.id_tr LEFT JOIN `chercheurs` ON travaux.idch_tr=chercheurs.id_ch
WHERE famil_trav.famil_ft='".$nom."' OR famil_trav.famil_ft='".htmlspecialchars_decode(htmlentities($nom, ENT_NOQUOTES, "UTF-8"))."'";
/*pour tenir compte de l'écriture des caractères accentués dans la base*/
$sql.=" order by travaux.id_tr DESC";

/*echo $rek_c_pat;*/
try {$i_c_pat=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>";
$content.="<div class='mb-4 fs-4'>Travaux portant sur le patronyme $nom"; // row contenant et colonne principale
$content.="</div>"; // mb pour quasiment tous les "paragraphes"
$content.="<div class='mb-4'>Travaux portant sur le patronyme $avert</div>"; // mb pour quasiment tous les "paragraphes"
$content.="<table class='table'>
<thead>
<tr>
    <th scope='col' class='align-text-top'>Commune</th>
    <th scope='col' class='align-text-top'>Par</th>
    <th scope='col' class='align-text-top'>Sujet</th>
    <th scope='col' class='align-text-top'>Patronymes</th>
</tr>
</thead>
<tbody>";
$anon=0;
while ($lcp=$i_c_pat->fetch(PDO::FETCH_ASSOC)) {
    $sql="SELECT DISTINCT ID_LOC, LIB_LOC from locality,trav_com WHERE locality.ID_LOC=trav_com.comm AND trav_com.trav=".$lcp["id_tr"];
    //die($sql);
    try {$id_com=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $content.="<tr><td>";
    $communes="";
    while($l_com=$id_com->fetch(PDO::FETCH_ASSOC)){
        $communes.="<a href='accueil1-entites_commune-ent-".$l_com["ID_LOC"].".html#patros'>".$l_com["LIB_LOC"]."</a>, ";
    }
    $content.= substr($communes,0,-2)."<td>";
    if($lcp["nom_ch"]!="") $content.=$lcp["nom_ch"];
    else {
        $content.="*";
        $anon=1;
    }
    $content.="<td>".$lcp["tit_tr"]."<td>";
                                            /*Affichage des familles*/
    $rek_f="select famil_ft  from `famil_trav` where famil_trav.idtr_ft=".$lcp["id_tr"];
    try {$id_f=$bdd->query($rek_f);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $nb_f= $id_f->rowCount();
    for ($f=0;$f<$nb_f-1;$f++) {
        $lf=$id_f->fetch(PDO::FETCH_ASSOC);
        if (trim($lf["famil_ft"])==trim($param)) $content.="<b>";
            $content.= $lf["famil_ft"]."</b>, ";
        }
    $lf=$id_f->fetch(PDO::FETCH_ASSOC);
    $content.= $lf["famil_ft"];
}
$content.="</table>";
if($anon==1) $content.="* <i>Le compte du chercheur n'a pas été mis à jour.</i>";
$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>