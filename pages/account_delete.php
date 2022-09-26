<?php session_start();
extract($_POST,EXTR_OVERWRITE);
$nada="Il n\u0027y a rien \u00e0 supprimer, ou le mot de passe saisi n\u0027est pas correct.";
$done="Les suppressions requises ont \u00e9t\u00e9 effectu\u00e9es...";
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

$rek_ch="select id_ch FROM `chercheurs` WHERE nom_ch='".$_SESSION['pseudo']."' AND code_ch=:pass3";
try {$i_ch=$bdd->prepare($rek_ch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$i_ch->bindValue('pass3', md5($pass3), PDO::PARAM_STR);
try{$i_ch->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$nb_tr=0;
$nb_fam=0;

if($i_ch->rowCount()>0){
    $l_ch=$i_ch->fetch(PDO::FETCH_ASSOC);
    $rek_tr="select id_tr FROM `travaux` WHERE idch_tr=".$l_ch["id_ch"];
    try {$i_tr=$bdd->query($rek_tr);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $nb_tr=$i_tr->rowCount();
    while($l_tr=$i_tr->fetch(PDO::FETCH_ASSOC)){
        $rek_fam="delete FROM `famil_trav` WHERE idtr_ft=".$l_tr["id_tr"];
        /*echo $rek_fam;*/
        try {$bdd->query($rek_fam);}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    }
    if ($nb_tr>0){
		$rek_s_tr="delete FROM `travaux` WHERE idch_tr=".$l_ch["id_ch"];
		try {$bdd->query($rek_s_tr);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    }
    if($keep=="n"){		// pas de conservation des commentaires
        $sql="DELETE FROM comment_act WHERE aut=".$l_ch["id_ch"];
        try {$id_c=$bdd->query($sql);}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $comm=$id_c->rowCount();
    }
    $rek_s_ch="DELETE FROM `chercheurs` WHERE nom_ch='".$_SESSION['pseudo']."' AND code_ch=md5('".$pass3."')";
    //echo $rek_s_ch;
    try {$bdd->query($rek_s_ch);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

    session_unset();
    $stat=1;
    echo"<SCRIPT language=javascript>alert('".$done."');</SCRIPT>";
}
else {
    echo"<SCRIPT>alert('".$nada."');</SCRIPT>";
}
echo"<meta http-equiv='refresh' content='0; url=accueil1-lisa_maj_articles.html' />";
?>