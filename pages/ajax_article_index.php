<?php
session_start();
if (!isset($_SESSION["authentification"])) print ("<script language = \"JavaScript\">location.href = '../pages1/accueil1.php?frd=login1&erreur=intru';</script>");
header("Content-Type: application/json");
$article=$_REQUEST['article'];
$new_old=$_REQUEST['new_old'];
$mode=$_REQUEST['mode'];
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
if($mode=="insert"){
    $terme=$_REQUEST['terme'];
    $sql="INSERT INTO `article-indexes`(`article`, `new_old`, `terme`) VALUES ($article,'$new_old',$terme)";
}
else{
    $index=$_REQUEST['index'];
    $sql="DELETE FROM `article-indexes` WHERE id=$index";
}
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
///exit(json_encode(array("result"=>$sql,"res"=>0)));
// reconstitution du html
$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2, article_terme_indexation_2.id,`article-indexes`.id FROM article_terme_indexation_1,article_terme_indexation_2,
    `article-indexes` WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 AND article_terme_indexation_2.id=`article-indexes`.terme 
    AND `article-indexes`.article=:article AND `article-indexes`.new_old=:new_old ORDER BY index1, index2";
try {$rek=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('article', $article, PDO::PARAM_INT);
$rek->bindValue('new_old', $new_old, PDO::PARAM_STR);
try{$rek->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$index1="";
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
    if($index1!=$l["index1"]) {
        $result.="<div><b>&bull; ".$l["index1"]."</b></div>";
        $index1=$l["index1"];
    }
    $result.="<div class='ms-4'>  <a href='#' onClick='update_index(\"delete\",".$l["id"].")' title='Supprimer cet index'>
        <i class='bi bi-clipboard-minus fs-1 text-danger'></i></a>&nbsp;".$l["index2"]."</div>";    // effacement du terme en mode id (une occurrence supprimée)
}
echo json_encode(array("result"=>$result,"res"=>1));
?>