<?php
session_start();
header('Content-type: text/html; charset=UTF-8');// Inutile depuis php 5.6

header("Content-Type: application/json");
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

$ref=$_REQUEST['ref'];	
$sql="SELECT * FROM glo_refer WHERE id='$ref'";

try{$req=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
$l=$req->fetch(PDO::FETCH_ASSOC);

echo json_encode(array("result"=>$l["lib"],"res"=>1));
?>