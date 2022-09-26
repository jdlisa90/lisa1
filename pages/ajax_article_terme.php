<?php
session_start();
if (!isset($_SESSION["authentification"])) print ("<script language = \"JavaScript\">location.href = '../pages1/accueil1.php?frd=login1&erreur=intru';</script>");
header("Content-Type: application/json");
$mode=$_REQUEST['mode'];
$niveau=$_REQUEST['niveau'];
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

if($mode=="delete"){        // seulement niveau 2
    $terme=$_REQUEST['terme'];
    $sql="DELETE FROM `article_terme_indexation_2` WHERE id=$terme";
    try {$req=$bdd->query($sql);}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
}
else{
    $terme_lib=$_REQUEST['terme_lib']; 
    if($niveau==1){
        $sql="INSERT INTO article_terme_indexation_1 SET lib=:terme_lib";
        try {$req=$bdd->prepare($sql);}
        catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $req->bindValue('terme_lib', $terme_lib, PDO::PARAM_STR);
    }
    else{
        $terme1=$_REQUEST['terme1'];
        $sql="INSERT INTO `article_terme_indexation_2` SET niveau1=:terme1, lib=:terme_lib";
        try {$req=$bdd->prepare($sql);}
        catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $req->bindValue('terme_lib', $terme_lib, PDO::PARAM_STR);
        $req->bindValue('terme1', $terme1, PDO::PARAM_INT);
    }
    try {$req->execute();}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
    if($niveau==2) $terme2=$bdd->lastInsertId();
}
///exit(json_encode(array("result"=>$sql,"res"=>0)));

// reconstitution du html
// select de la page principale
$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2, article_terme_indexation_2.id FROM article_terme_indexation_1,article_terme_indexation_2
    WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 ORDER BY index1, index2";
try {$rek=$bdd->prepare($sql);}	
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('article', $article, PDO::PARAM_INT);
$rek->bindValue('new_old', $new_old, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$result="<option value='0' selected='selected' disabled='disabled'>&raquo; Termes enregistrés</option>";
$index1="";
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
    if($index1!=$l["index1"]) {
        $result.="<optgroup label=\"".$l["index1"]."\">";
        $index1=$l["index1"];
    }
    if($l["id"]==$terme2) $result.="<option value='".$l["id"]."' selected='selected'>".$l["index2"]."</option>";
    else $result.="<option value='".$l["id"]."'>".$l["index2"]."</option>";
}

// select de la fenêtre modale
$result1="<option value='0' selected='selected' disabled='disabled'>&raquo; Termes enregistrés</option>";
$sql="SELECT * FROM article_terme_indexation_1 ORDER BY lib";
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
while($l=$req->fetch(PDO::FETCH_ASSOC)){
    $result1.="<option value='".$l["id"]."'>".$l["lib"]."</option>";
}
$res=1;
echo json_encode(array("result"=>$result,"result1"=>$result1,"res"=>1));
?>