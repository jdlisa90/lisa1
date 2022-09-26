<?php
header("Content-Type: application/json");
$login=$_REQUEST['login'];
$email=$_REQUEST['email'];
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
$sql="SELECT * FROM chercheurs WHERE nom_ch=:login AND email_ch=:email";
try {$req=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('login', $login, PDO::PARAM_STR);
try{$req->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$res=0;
if($req->rowCount()>0) {
	$pw= substr(md5(uniqid(rand(99, 999999))),0,10);
	$txt=$login.", votre mot de passe est à présent ";
	
	$sql="UPDATE chercheurs SET code_ch=md5('".$pw."') WHERE nom_ch=:login AND email_ch=:email";
	try {$req=$bdd->prepare($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$req->bindValue('email', $email, PDO::PARAM_STR);
	$req->bindValue('login', $login, PDO::PARAM_STR);
	try{$req->execute();}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	
	$mailto=$email;

    $txt_mail="<!DOCTYPE -//w3c//dtd 4.01 html http://www.w3c.org/TR/1999/REC-html401-1=9991224/loose.dtd public transitional//en>
    <HTML>
    <HEAD>
    <TITLE></TITLE>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    </head>
    <body style='background-color: rgb(232, 236, 200); color: rgb(0, 0, 0);'>
    <table border='0' width='665'>
    <tr><td>".$login.", votre mot de passe est &agrave; pr&eacute;sent ".$pw.". <br>
    Nous vous conseillons de le modifier rapidement, pour plus de s&eacute;curit&eacute;.
    </td>
    </tr>
    </table></html>";

    $corps=$txt_mail;
    $from = "admi";
    $from.="n@lisa9";
    $from.="0.org";
    $entetedate = date("D, j M Y H:i:s -0600"); // avec offset horaire

    $entetemail = "From: $from \n";
    $entetemail .= "Cc: \n";
    $entetemail .= "Bcc: \n"; // Copies cachées
    $entetemail .= "Reply-To: $exp \n"; // Adresse de retour
    $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n" ;
    $entetemail .= "Date: $entetedate\n";
    $entetemail .= "MIME-Version: 1.0\n";
    $entetemail .= "Content-type: text/html; charset= \"iso-8859-1\" \n";
    $entetemail .= "Content-Transfer-Encoding: 8bit";
    //echo "E-mail envoyé";
    //echo $mailto." ".$corps." ".$entetemail;
    mail($mailto, "Votre compte sur le site LISA", $corps,$entetemail);
    $result="E-mail envoyé";
    $res=1;
}   // FONCTIONNE CORRECTEMENT AVEC GMAIL
else $result="Les données saisies ne correspondent à aucun compte ouvert sur notre site";
echo json_encode(array("result"=>$result,"res"=>$res));
?>