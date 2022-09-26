<?php
session_start();
header('Content-type: text/html; charset=UTF-8');// Inutile depuis php 5.6

header("Content-Type: application/json");
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

$ref=$_REQUEST['ref'];	
$sql="SELECT * FROM `glossaire_articles` WHERE id='$ref'";

try{$req=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
$l=$req->fetch(PDO::FETCH_ASSOC);
if($l["image"]!="") {
	$image="<img src='../glossaire_images/".$l["image"]."' style='max-width:100%;' alt=''><br>".$l["source_image"];
	if($l["text_image"]!="") $image.=" <i>(".$l["text_image"]."</i>)";
}
echo json_encode(array("result"=>"
<div onclick=window.open('../pages1/glossaire_MF.html#".$ref."')>
<div class='row pb-2'><div class='col'>GLOSSAIRE : <b>".$l["lib"]."</b> (".$l["type"].")</div></div>
<div class='row'><div class='me-0'><div class='col-7 float-end ps-2 mb-1'>".$image."</div>".strip_tags($l["comment"])."</div>
</div>","res"=>1));
?>