<?php session_start();
extract($_POST);
if(isset($_SESSION['captcha_'])){
   // echo $_SESSION['captcha_']."<br/>".$_POST['captcha']."<br/>";
   // On compare le code entre par l'utilisateur avec celui de l'image(qui avait ete stoque dans les sessions)
	if(strtolower($captcha)==strtolower($_SESSION['captcha_'])){
		//unset($_SESSION['captcha_']);
		if (eregi("MIME-Version:|Content-Type:|Content-Transfer-Encoding:|bcc:|cc:|href|\[url\|http://|https://|{texte}]", $adresse.$message)) {
			$result=("Tentative d'intrusion détectée");
			$res=0;
		}
		else {
	      if($auteur<>"")$comment=$auteur." a écrit :<br><br>";
	      $comment.= "\n--------------------------------------------------------------\n<br>";
	      $comment.= $message;	
	      $comment.= "\n\n<br>-----------------------------------------------------------\n";
	      $from = $email;
	
	      $to="Association LISA <li";
	      $to.="sa.90@ora";
	      $to.="nge.fr>";
	      // $to.=", olivier.billot@cg90.fr";
	
	      $entetedate = date("D, j M Y H:i:s -0600"); // avec offset horaire
	      $entetemail = "From: $from \n";
	      $entetemail .= "Cc: \n";
	      $entetemail .= "Reply-To: $from\n"; // Adresse de retour
	      $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n";
	      $entetemail .= "Date: $entetedate\n";
	      $entetemail .= "MIME-Version: 1.0\n";
	      $entetemail .= "Content-type: text/html; charset= \"utf-8\" \n";
	      $entetemail .= "Content-Transfer-Encoding: 8bit";
	
	      $corps.="<!DOCTYPE -//w3c//dtd 4.01 html http://www.w3c.org/TR/1999/REC-html401-1=9991224/loose.dtd public transitional//en>
	          <HTML>
	          <HEAD>
	          <TITLE></TITLE>
	          <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	          </head>
	          <body style='background-color: rgb(232, 236, 200); color: rgb(0, 0, 0);'>";
	      $corps.="<tr><td>&nbsp;<tr><td>".trim($comment);
	      $corps.="</table></html>"."\n\n";

	      if(mail($to, $titre, $corps, $entetemail)){     //message de la boite de dialogue en cas d'envoi réussi
	      	$res=1;
	         $result="Vous venez d'envoyer le message suivant :
	         \nsujet : ".$titre."\ntexte : ".$message."
	         
LISA vous remercie et vous répondra dés que possible.";
	      }
	      else {
	      	$res=0;
	         echo"Erreur mail";
			}
		}	// pas d'intrusion
	}	// bon captcha
	else{
		$result='Le code que vous avez entré est mauvais, veuillez réessayer.';
		$res=0;
	}	// mauvais captcha
}
else {
	$result= 'Session expirée ; veuillez renouveler votre envoi.';
	$res=0;
}	// pas de session captcha
echo json_encode(array("result"=>$result,"res"=>$res));
?>