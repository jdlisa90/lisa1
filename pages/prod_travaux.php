<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

if (isset($_SESSION["authentification"])) $regist=1;

$com2="";
$com3="";
$tit2="Indexer un travail ou gérer ses travaux indexés";
$com4="S'incrire et accéder à mon espace perso";
$com5="Accéder à son espace perso";

$rek_c="select DISTINCT locality.ID_LOC,locality.LIB_LOC from `locality`, travaux WHERE travaux.id_loc=locality.ID_LOC order by LIB_LOC";
try {$id_k=$bdd->query($rek_c);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$rek_ch="select DISTINCT id_ch,nom_ch from `chercheurs`, travaux WHERE travaux.idch_tr=chercheurs.id_ch order by nom_ch";
try {$id_c=$bdd->query($rek_ch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$rek_p="select DISTINCT famil_ft from `famil_trav` WHERE ASCII(UCASE(famil_ft)) BETWEEN 65 AND 90 order by famil_ft"; //éviter des chaines exotiques
try {$id_p=$bdd->query($rek_p);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'>
<h3>Travaux indexés</h3>"; // row contenant et colonne principale
$content.="Afin de favoriser les échanges, LISA offre la possibilité de présenter brièvement des travaux (histoire, généalogie) en rapport avec les communes du secteur étudié.<br>
Tous les usagers peuvent <b>consulter</b> les travaux référencés, mais <b>il est nécessaire de s'inscrire</b> pour présenter les siens.
<br><br><h4>Consulter les travaux indexés</h4>
Il s'agit ici d'une INDEXATION de travaux. Vous avez la possibilité de prendre connaissance de l'existence de recherches portant sur une commune,
  ou  un patronyme. Vous pouvez également connaître l'ensemble des travaux indexés par un chercheur donné.
  <br>TOUTEFOIS, LISA n'assure pas la présentation du contenu de ces travaux. Pour cela, il vous faudra prendre directement contact avec le chercheur, qui a
  déposé une adresse mail à cet effet.<br><br>";

$content.="<div class='row'><div class='col-8'><b>Par communes</b><br><form method='post' action='accueil1-entites_commune.html#patros'><select name='ent' onChange='this.form.submit()'>
<option value='0'  disabled='disabled' selected>&raquo; Communes</option>";
while($lc=$id_k->fetch(PDO::FETCH_ASSOC)){
    $content.="<option value='".$lc["ID_LOC"]."'";
    //if ($lc["ID_LOC"]==$com){$content.=" selected ";}
    $content.=">".$lc["LIB_LOC"]."</option>";
}
$content.="</select></form></div></div>";

$content.="<br><br><div class='row'><div class='col-8'><b>Par patronymes</b><br><form method='post' action='accueil1-prod_travaux_indiv.html'><select name='nom' onChange='this.form.submit()'>
<option value='0'  disabled='disabled' selected>&raquo; Patronymes</option>";
while($lp=$id_p->fetch(PDO::FETCH_ASSOC)){
    //if(ord(substr($lp["famil_ft"],0,1)))
    $content.="<option value='".$lp["famil_ft"]."'>".$lp["famil_ft"]."</option>";
}
$content.="</select></form></div></div>";

$content.="<br><br><div class='row'><div class='col-8'><b>Par chercheurs</b><form method='post' action='accueil1-prod_travaux_chercheur.html'><select name='chercheur' onChange='this.form.submit()'>
<option value='0'  disabled='disabled' selected>&raquo; Chercheurs</option>";
while($lch=$id_c->fetch(PDO::FETCH_ASSOC)){
    $content.="<option value='".$lch["id_ch"]."'>".$lch["nom_ch"]."</option>";
}
$content.="</select></form></div></div>";
$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>