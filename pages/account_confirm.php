<?php
session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

extract($_GET,EXTR_OVERWRITE);
list($id,$pseudo,$pass)=explode("_",chunk_split($vid,32,"_"));

$sql="SELECT * FROM chercheurs WHERE MD5(id_ch)=:id AND MD5(nom_ch)=:pseudo AND code_ch=:pass"; //dans le lien du mail, TOUT a été codé en MD5
try {$req=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$req->bindValue('id', $id, PDO::PARAM_STR);
$req->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
$req->bindValue('pass', $pass, PDO::PARAM_STR);
try{$req->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
   
if ($req->rowCount()>0){
    $l_verif=$req->fetch(PDO::FETCH_ASSOC);
    $lang=$l_verif["lang"];
    $sql="UPDATE chercheurs SET statut=1 WHERE id_ch=".$l_verif["id_ch"];
    try {$req=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $_SESSION["authentification"]="true"; // enregistrement de la session (champ utilisé dans certains scripts)
    // déclaration des variables de session
    $_SESSION['_id']=$l_verif['id_ch'];
    $_SESSION['statut'] = 1; // le privilège de l'utilisateur (permet de définir des niveaux d'utilisateur)
    $_SESSION['pseudo'] = $l_verif['nom_ch']; // Son nom
    $_SESSION['email'] = $l_verif['email_ch'];
    $_SESSION['nb_trav'] = $l_verif['nb_trav'];
    $_SESSION['site']=$l_verif['site_ch'];
    $_SESSION['courriels']=$l_verif['send_letter'];
    echo "<script>alert('Votre inscription sur ce site est \u00e0 pr\u00e9sent valid\u00e9e.'+'\\n'+'Votre session est ouverte.'+'\\n'+
    'Vous pourrez toujours modifier votre consentement \u00e0 la r\u00e9ception de courriels d\u0027information en vous rendant dans votre espace personnel.');</script>";
}
else echo "<script>alert('Votre acc\u00e8s à cette page constitue une anomalie. Aucune suite ne peut \u00eatre donn\u00e9e à votre recherche.');</script>";
echo"<meta http-equiv='refresh' content='0; url=accueil1-lisa_maj_articles.html' />";
?>