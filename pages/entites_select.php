<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col-5 pt-4 pb-4'>
<h4>Entités administratives anciennes et modernes</h4>";

$content.="<br><b><div>Entités féodales</div></b>";

$rek="SELECT DISTINCT id, lib FROM entity WHERE type_ent=3 ORDER BY lib";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form3' action='accueil1-entites_entite.html'>";
    $content.="<select name='ent' id='3'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; seigneurie, comté</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["id"]."'>".$lf["lib"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$content.="<br><b><div>Période autrichienne (1324-1648)</div></b>
<form id='sub0' action='accueil1-entites_entite.html' method='post'><input type='hidden' name='ent' value='400'/></form>
<div class='ps-5'><b><a href='#' onclick='document.getElementById(\"sub0\").submit()'>Gouvernement autrichien en Haute-Alsace (régence d'Ensisheim)</a></b></div>";

$content.="
<br><b><div>Circonscriptions administratives d'Ancien Régime (1648-1787)</div></b>
<form id='sub' action='accueil1-entites_entite.html' method='post'><input type='hidden' name='ent' value='330'></form>
<div class='ps-5'><b><a href='#' onclick='document.getElementById(\"sub\").submit()'>Subdélégation de Belfort</a></b></div>	";

$rek="SELECT DISTINCT id, lib FROM entity WHERE type_ent=4 ORDER BY lib";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form4' action='accueil1-entites_entite.html'>
    <select name='ent' id='4'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; bailliage royal</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["id"]."'>".$lf["lib"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$content.="<br><b><div>Période moderne</div></b>";
$rek="SELECT DISTINCT id, lib FROM entity WHERE type_ent=23 ORDER BY lib";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form23' action='accueil1-entites_entite.html'>";
    $content.="<select name='ent' id='23'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; canton</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["id"]."'>".$lf["lib"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$rek="select LIB_LOC,ID_LOC from locality order by LIB_LOC";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form22' action='accueil1-entites_commune.html'>";
    $content.="<select name='ent' id='22'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; commune</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["ID_LOC"]."'>".$lf["LIB_LOC"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$content.="<div class='ps-5'><b><a href='accueil1-entites_communes_carte.html'>cartes des communes</a></b></div>";

$content.="<br><b><div>Entités ecclésiastiques</div></b>";
$rek="SELECT DISTINCT id, lib FROM entity WHERE type_ent=19 ORDER BY lib";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form19' action='accueil1-entites_entite.html'>";
    $content.="<select name='ent' id='19'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; chapitre collégial</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["id"]."'>".$lf["lib"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$rek="select LIB_PAR,ID_PAR from parish order by LIB_PAR";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($idf->rowCount()>0){
    $content.="<div class='ps-5'><form method='post' id='form16' action='accueil1-entites_paroisse.html'>";
    $content.="<select name='ent' id='16'  onChange='this.form.submit()'>
        <option value='0'  disabled='disabled' selected>&raquo; paroisse</option>";
    while($lf=$idf->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lf["ID_PAR"]."'>".$lf["LIB_PAR"]."</option>";
    }
    $content.="</select></form></div>";                                        
}
$idf->closeCursor();
$content.="<div class='ps-5'><b><a href='accueil1-entites_paroisses_carte.html'>cartes des paroisses</a></b></div>";

$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";
$content.="
</div>";
?> 