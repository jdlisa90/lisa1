<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
extract($_POST,EXTR_OVERWRITE);
$rek_ch="select chercheurs.* from `chercheurs`WHERE chercheurs.id_ch=".$chercheur;
try {$i_ch=$bdd->query($rek_ch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$lc=$i_ch->fetch(PDO::FETCH_ASSOC);


$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'>";
$content.="<div class='mb-4 fs-3'>Travaux indexés par \"".$lc["nom_ch"]."\"</div>";
$content.="<div class='mb-4'>LISA n'assure pas la présentation du contenu de ces travaux.<br>Pour en prendre connaissance, il vous faudra prendre contact avec le chercheur, 
par notre intermédiaire.</div>";
$content.="<table class='table'>
<thead>
<tr>
    <th scope='col' class='align-text-top'>Commune</th>
    <th scope='col' class='align-text-top'>Sujet</th>
    <th scope='col' class='align-text-top'>Patronymes</th>
</tr>
</thead>
<tbody>";
$rek_ch="SELECT travaux.* FROM `travaux` WHERE travaux.idch_tr=".$chercheur." order by id_tr";
try {$i_ch=$bdd->query($rek_ch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
while ($lc=$i_ch->fetch(PDO::FETCH_ASSOC)) {
    $sql="SELECT DISTINCT ID_LOC, LIB_LOC FROM locality,trav_com WHERE locality.ID_LOC=trav_com.comm AND trav_com.trav=".$lc["id_tr"];
    try {$id_com=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
   $content.="<tr><td>";
   $communes="";
   while($l_com=$id_com->fetch(PDO::FETCH_ASSOC)){
       $communes.="<a href='accueil1-entites_commune-ent-".$l_com["ID_LOC"].".html#patros'>".$l_com["LIB_LOC"]."</a>, ";
   }
   $content.= substr($communes,0,-2)."<td>".stripslashes($lc["tit_tr"])."<td>";
                                           /*Affichage des familles*/
   $rek_f="SELECT famil_trav.* FROM `famil_trav` WHERE famil_trav.idtr_ft=".$lc["id_tr"];
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
$content.="</table></div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>