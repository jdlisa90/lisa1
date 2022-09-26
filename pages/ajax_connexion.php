<?php
session_start();    // suite à la connexion "standart" de l'accueil, ou de la connexion "accidentelle" de session_connexion.php
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
$login=$_REQUEST['login'];
$pass=$_REQUEST['pass'];

$sql="SELECT * FROM chercheurs WHERE nom_ch=:login AND code_ch=:pass";
try {$verif=$bdd->prepare($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$verif->bindValue('login', $login, PDO::PARAM_STR);
$verif->bindValue('pass', md5($pass), PDO::PARAM_STR);
try{$verif->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$row_verif = $verif->fetch(PDO::FETCH_ASSOC);
$utilisateur = $verif->rowCount();
if ($utilisateur>0) {	// On teste s'il y a un utilisateur correspondant
    if($row_verif["statut"]>=1){
        $_SESSION['name'] ="authentification";
        $_SESSION['authentification'] ="authentification"; // pour ne pas provoquer d'échec dans l'ouverture des pages spécifiques
        $_SESSION['_id']=$row_verif['id_ch'];
        $_SESSION['pseudo'] = $login; // Son nom
        $_SESSION['email'] = $row_verif['email_ch'];
        $_SESSION['nb_trav'] = $row_verif['nb_trav'];
        $_SESSION['site']=$row_verif['site_ch'];
        $_SESSION['courriels']=$row_verif['send_letter'];
        $sql="SELECT DISTINCT commune FROM cp_anc";             //définition d'un tableau variable de session pour les communes illustrées par une cp
        try {$id_cp=$bdd->query($sql);}
        catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
        $_SESSION['comm_cp']=array();		// ??
        $comm_cp=array();					// ??
        $_SESSION['statut']=(int)$row_verif["statut"];		// le statut est un décimal (!!)
        $_SESSION['editeur'] =$row_verif["editeur"];
        $_SESSION['member'] =$row_verif["member"];
        $_SESSION['lecteur'] = $row_verif['lecteur'];	
        // print ("<script language = \"JavaScript\">location.href = 'accueil1.php?frd=wait1&url=".urlencode($url)."';</script>");
        // setcookie("lisa");
        $res=1;
    }
    else{
        $res=0;
        $result="Votre inscription n'est pas finalisée. \nVeuillez répondre au mail qui vous a été adressé. Merci";
    }
}
else {
    $res=0;
    $result="Erreur login";
}
//infos nécessaires pour la modification du menu perso
echo json_encode(array("result"=>$result,"res"=>$res,"login"=>$_SESSION['pseudo'],"statut"=>(int)$row_verif["statut"],"editeur"=>$row_verif["editeur"],"member"=>$row_verif["member"],"lecteur"=>$row_verif['lecteur']));
?>