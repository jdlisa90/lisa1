<?php
session_start();
if (!isset($_SESSION["authentification"])) print ("<script language = \"JavaScript\">location.href = '../pages1/accueil1.php?frd=login1&erreur=intru';</script>");
//if ($_SESSION['statut']==10){
	header("Content-Type: application/json");
	$article=$_REQUEST['article'];
	$email=$_REQUEST['email'];
	$chercheur=$_REQUEST['chercheur'];
	$contact=$_REQUEST['contact'];
	try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
	catch(Exception $e) {die('Erreur : '.$e->getMessage());}
	// recherche des administrateurs
	$sql="SELECT email_ch FROM chercheurs WHERE statut=10";
	try{$req=$bdd->query($sql);}
	catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
	$to="";
	while($l=$req->fetch(PDO::FETCH_ASSOC)) {
		$to.=$l["email_ch"].", ";
	}
	$to=substr($to, 0, strlen($to)-2);
	header("Content-Type: application/json");
/*		$to="Association_LISA <jdlisa.";		// à supprimer
		$to.="90@gmai";							//
		$to.="l.com>";								//*/

	$from=$email;
	$sujet="Rapport concernant un article en ligne par ".$chercheur;
	$entetedate = date("D, j M Y H:i:s -0600"); // avec offset horaire
	$entetemail = "From: $from \n";
	$entetemail .= "Cc: \n";
	$entetemail .= "Reply-To: $from\n"; // Adresse de retour
	$entetemail .= "X-Mailer: PHP/" . phpversion() . "\n";
	$entetemail .= "Date: $entetedate\n";
	$entetemail .= "MIME-Version: 1.0\n";
	$entetemail .= "Content-type: text/html; charset= \"utf-8\" \n";
	$entetemail .= "Content-Transfer-Encoding: 8bit";
		$corps="<!DOCTYPE -//w3c//dtd 4.01 html http://www.w3c.org/TR/1999/REC-html401-1=9991224/loose.dtd public transitional//en>
		    <HTML>
		    <HEAD>
		    <TITLE></TITLE>
		    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		    </head>
		    <body style='background-color: rgb(232, 236, 200); color: rgb(0, 0, 0);'>";
	   $corps.="<p>Message concernant l'article <a href='http://www.lisa90.org/lisa1/work/article_new-".$article.".html'>".$article."</a> :<br><em>".$contact.
	   "</em><br><br><span style='font-size: 80%;font-style:italic'>Ce mail vous est adressé en temps qu'administrateur de LISA.</span><br>".$admin;
	//  echo json_encode(array("result"=>"Article"));

   if(mail($to, $sujet, $corps, $entetemail)) {$result.=" Le message a été envoyé.";$res=1;}
	else {$result.=" Echec envoi message.";$res=0;}
   echo json_encode(array("result"=>$result,"res"=>$res));
   //echo json_encode(array("result"=>$to." ".$chercheur,"res"=>0));
//}
?>