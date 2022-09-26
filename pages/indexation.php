<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)
$content="<div class='row'><div class='col mt-3 pb-3 mb-3'>
<link rel='stylesheet' href='../css/style_articles2.css'/>
<img src='../img/logo_arbre.png' class='float-end'>
<h3>Indexation d'actes</h3>
<div class='mb-4'>Un certain nombre d'archives (surtout les comptes) valent plus pour leur contenu historique que pour les informations généalogiques qu'elles peuvent fournir. Dans leur dépouillement, nous 
sommes même conduits à saisir des actes sans individus.<br>
Afin de les rendre visibles et de permettre une recherche sur ces contenus, nous avons entrepris (en 2022) d'indexer les actes de ce type à partir d'un certain nombre d'items (menu \"Recherches d'index\").
<br>Nous avons cherché à limiter le nombre d'items. Voici leur liste, avec l'explication correspondante et le nombre d'occurrences relevées. Pour l'instant, les archives
concernées sont essentiellement les comptes <a href='accueil1.php?disp=sources_archive&arch=510'><b>CC4</b></a> et le livre de la ville <a href='accueil1.php?disp=sources_archive&arch=14'><b>BB2</b></a>, conservés aux Archives Municipales de Belfort.
<br>Remarque : un même acte peut être associé à plusieurs items (jusqu'à 3).</div>";
$content.="<table class='table table-sm'>
<COLGROUP width='18%'><COLGROUP width='80%'><COLGROUP>";
$sql="SELECT * FROM index_actes ORDER BY item";
$req=$bdd->prepare($sql);
try{$req->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .'</b> '. $e->getMessage(),"res"=>0)));}
while($l=$req->fetch(PDO::FETCH_ASSOC)){
    $content.="<tr><td><b>".$l["item"]."</b><td>".$l["comment"]."<td style='text-align:right;'>";
    $sql="SELECT COUNT(*) AS nb FROM `acts-index` WHERE index_id=".$l["id"];
    $req1=$bdd->prepare($sql);
    try{$req1->execute();}
    catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .'</b> '. $e->getMessage(),"res"=>0)));}
    $l1=$req1->fetch(PDO::FETCH_ASSOC);
    $content.= $l1["nb"];
}
$content.="</table></div></div></div>";
?>
