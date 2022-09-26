<?php session_start();
extract($_POST,EXTR_OVERWRITE);
extract($_GET,EXTR_OVERWRITE);
$sujet_mail="Création d'un compte sur le site LISA";
$texte1_mail=", pour terminer la création de votre compte sur le site LISA, il ne vous reste plus qu'à ouvrir 
le mail que nous venons de vous adresser et à suivre la procédure qui y est indiquée.<br><br>
A très bientôt.";
//echo $_SESSION['captcha_'];
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
if ($new=="new"){	/*inscription d'un nouveau chercheur*/
    if(isset($_POST['captcha'],$_SESSION['captcha_'])){
        
        if(strtolower($_POST['captcha'])==strtolower($_SESSION['captcha_'])){
            unset($_SESSION['captcha_']);
            //echo '<strong style="color:#00bb00;">Le code que vous avez entr&eacute; est bon.</strong>';
            $form = false;

            $code=strtolower($login);
            $rek_s="select * from `chercheurs` where nom_ch='".$login."'";
            try {$id_s=$bdd->query($rek_s);}
            catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
            if($id_s->rowCount()==0){
                include "../ftp/includes/email_mime.class.php";
					$sql="INSERT INTO `chercheurs` SET nom_ch= :login,code_ch= :pass ,email_ch=:mail ,site_ch=:site,nb_trav=0,send_letter=:courriels,lang='".$lang."'";
					try {$id_i_c=$bdd->prepare($sql);}
					catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
					$id_i_c->bindValue('login', $login, PDO::PARAM_STR);
					$id_i_c->bindValue('pass', md5($pass), PDO::PARAM_STR);
					$id_i_c->bindValue('mail', $mail, PDO::PARAM_STR);
					$id_i_c->bindValue('site', $wsite, PDO::PARAM_STR);
                    if($courriels=='y')$id_i_c->bindValue('courriels', "y", PDO::PARAM_STR);
                    else $id_i_c->bindValue('courriels', "n", PDO::PARAM_STR);
					try{$id_i_c->execute();}
					catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
            
                $id_ch=$bdd->lastInsertId();
                $mailto=$mail;
                $txt_mail="<!DOCTYPE -//w3c//dtd 4.01 html http://www.w3c.org/TR/1999/REC-html401-1=9991224/loose.dtd public transitional//en>
                <HTML>
                    <HEAD>
                    <TITLE></TITLE>
                    <META http-equiv=\"Content-type\" content=\"text/html; charset=iso-8859-15\" />
                    </head>
                <body style='background-color: rgb(232, 236, 200); color: rgb(0, 0, 0);'>
                <table border='0' width='665'>
                <tr><td>Veuillez confirmer votre inscription  
                <a href=\"http://www.lisa90.org/lisa1/pages/account_confirm.php?vid=".md5($id_ch).md5($login).md5($pass)."\" target='_blank'>en cliquant ici</a> ou, 
                en recopiant cette url dans votre barre de navigation : <br>
                http://www.lisa90.org/lisa1/pages/account_confirm.php?vid=".md5($id_ch).md5($login).md5($pass)."
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
                //$entetemail .= "Reply-To: $exp \n"; // Adresse de retour
                $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n" ;
                $entetemail .= "Date: $entetedate\n";
                $entetemail .= "MIME-Version: 1.0\n";
                $entetemail .= "Content-type: text/html; charset= \"iso-8859-1\" \n";
                $entetemail .= "Content-Transfer-Encoding: 8bit";
                $m=mail($mailto, "Votre inscription sur le site LISA", $corps,$entetemail);
    //echo"mail".$mailto;
                if($m){
                    echo "<div id='bloc_mess' title='Votre inscription' style='text-align: justify;display:none;'>
                        \"".$login."\"".$texte1_mail."</div>";
    ?>
                    <script src='http://code.jquery.com/jquery.min.js'></script>
                    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js'></script> 
                    <script>$(function () {
                        $('#bloc_mess').dialog();
                        return false;
                    });
                    </script>
    <?php
                    $form=false;
                }
                else{   //echec mail
                    echo"<script>alert('erreur mailing');</script>";
                    $form = true;
                }
            }
            else{   // login déjà utilisé
                echo "<script>alert('Ce login/pseudo est d\u00e9j\u00e0 utilis\u00e9 ; veuillez en choisir un autre.');</script>";
                $form = true;
            }
        }  
        else{      // mauvaise saisie code captcha
            echo "<script>alert('Le code que vous avez entr\u00e9 est mauvais, veuillez r\u00e9essayer.');</script>";
            $form = true;
        }
    }
    else{       // pas de code ou échec session
        echo "<script>alert('Saisies incompl\u00e8tes.');</script>";// pas vraiment utile
        $form = true;
    }
    if($form) echo"<meta http-equiv='refresh' content='0; url=accueil1-session_compte-new-new.html' />";
    else {
        echo"<script>alert('Un mail vous a \u00e9t\u00e9 adress\u00e9. Veuillez le compl\u00e9ter pour finaliser votre inscription');</script>";
        echo"<meta http-equiv='refresh' content='0; url=accueil1-lisa_maj_articles.html' />";
    }
}	

else{                   // modification des données perso
    $id_ch=$_SESSION['_id'];
    $passold = md5($_POST['passold']);
    $sql=sprintf("SELECT * FROM chercheurs WHERE id_ch='$id_ch' AND code_ch='$passold' AND statut>0"); // requête sur la base administrateurs
    try {$verif=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $utilisateur = $verif->rowCount();
    if ($utilisateur) {	// On teste s'il y a un utilisateur correspondant
        if($send_letter<>'y') $send_letter='n';
        $sql="UPDATE `chercheurs` SET ";
        //if($pass<>"") $rek_i_c.="code_ch=md5('".$pass."'), ";    // on ne change le pass que si qqch a été saisi (et validé par javascript)
        //$rek_i_c.="email_ch='".$mail."',site_ch='".$wsite."',send_letter='".$send_letter."',lang='".$lang."' WHERE id_ch=".$id_ch;
        
        if($pass<>"") $sql.="code_ch=:pass, ";    // on ne change le pass que si qqch a été saisi (et validé par javascript)
        $sql.="email_ch=:mail, site_ch=:site, send_letter=:courriels, lang='".$lang."' WHERE id_ch=".$id_ch;
        //echo $sql;
			try {$id_i_c=$bdd->prepare($sql);}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
			if($pass<>"") $id_i_c->bindValue('pass', md5($pass), PDO::PARAM_STR);
			$id_i_c->bindValue('mail', $mail, PDO::PARAM_STR);
			$id_i_c->bindValue('site', $wsite, PDO::PARAM_STR);
            if($courriels=='y')$id_i_c->bindValue('courriels', "y", PDO::PARAM_STR);
            else $id_i_c->bindValue('courriels', "n", PDO::PARAM_STR);
			try{$id_i_c->execute();}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
			
        // modification des données (email) de member le cas échéant
        if($change_mail_mbr=='y'){
            $sql="UPDATE member SET EMAIL_MBR='".$mail."' WHERE num_mbr=".$_SESSION['member'];
            try {$bdd->query($sql);}
				catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        }
        $_SESSION['email'] = $mail; 
        $_SESSION['site']=$wsite;
        $modif=1;
        session_unset();
        echo "<script>alert('Modification enregistr\u00e9e ; vous devrez vous reconnecter.');</script>";
        echo"<meta http-equiv='refresh' content='0; url=accueil1-lisa_maj_articles.html' />"; //déconnection à revoir
    }
    else{
        $url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL   REVOIR
        print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
    }
}
?>