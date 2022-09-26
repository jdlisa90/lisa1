<?php
if ($new<>"new" AND $_SESSION['statut']<1) {
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
}
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$reception_lettre="Je souhaite recevoir les informations LISA";	
$avert_close="<i>Cette action va également fermer la session en cours.</i>";
$del="Je souhaite <b><span class='text-danger'>supprimer définitivement</span></b> mon compte \"".$_SESSION["pseudo"]."\", avec toutes les données qui lui sont associées";
$del1="Je souhaite <b><span class='text-danger'>supprimer définitivement</span></b> mon compte \"".$_SESSION["pseudo"]."\", mais sans effacer les commentaires associés aux actes que j'ai enregistrés";
$del_av=" supprimer définitivement ce compte ?";
$pas3="";

$avert_perso="
UNE UTILISATION AUTORISÉE ET CONFORME À LA LOI
En tant que condition d'utilisation, vous acceptez ne pas utiliser les données mises à votre disposition par l'association L.I.S.A. à des fins contraires à la loi.
De même, vous ne pouvez pas utiliser les informations et interfaces LISA d'une manière susceptible d'endommager, de désactiver, de surcharger ou d'entraver le fonctionnement de ces services, ou d'interférer avec l'usage d'un autre parti ou d'empêcher la jouissance de ces services.
Vous ne pouvez pas essayer d'accéder à des systèmes informatiques ou à des réseaux associés aux Services LISA sans autorisation.

UTILISATION DES DONNEES PERSONNELLES QUE VOUS SOUMETTEZ
Vous êtes informés que toutes les données personnelles (paramètres d'inscription, informations diverses, etc...) que vous pouvez fournir sur ce site sont destinées à vous transmettre des informations déposées sur ce site par d'autres usagers, à être diffusées sur ce même site et/ou dans des courriers électroniques adressés à d'autres usagers de ce site.
L'association L.I.S.A. aura le droit de les retirer à tout moment.
L'association L.I.S.A. ne pourra être tenue pour responsable en cas de perte des données que vous aurez déposées sur ce site.
Vous pourrez modifier vos paramètres d'inscription à tout moment en vous rendant sur la page \"Espace perso\".
";

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>";
$info_mailing="Je souhaite recevoir les courriels d'information de LISA";
$star="";
$checked="";
if($new<>"new"){     // cas de modification
    $old_mail_mbr="";
    $new_change="change";
    if($_SESSION['courriels']=='y') {
        $info_mailing="Je souhaite continuer à recevoir les courriels d'information de LISA";
        $checked=" checked";
    }
    $texte_email="Si vous souhaitez que LISA ne conserve plus votre adresse mail, saisissez une chaîne vide
	ici :<br>(dans ce cas, vous ne pourrez plus recevoir les informations et les messages LISA)";
    $content.="<div class='mb-4 fs-3'>Mes paramètres</div>
        <form method='post' action='account.php?new=change' id='form_compte' name='update'>";
    $content.="<div class='row mb-1'><div class='col-9'>IDENTIFIANT</div>
        <div class='col-3 d-flex justify-content-end'><input type='text' name='login' maxlength='20' value='".$_SESSION['pseudo']."' readonly='true'></div></div>
    <div class='row mb-1'><div class='col-9'>Mot de passe actuel (ou provisoire)</div>
    	<div class='col-3 d-flex justify-content-end'>*&nbsp;<input type='password' name='passold' id='passold' maxlength='20' size='20' autocomplete='new-password' required></div></div>
    <div class='row mb-1'><div class='col-9'>Nouveau mot de passe (de 8 à 20 caractères, pas de caractères spéciaux)</div>
        <div class='col-3 d-flex justify-content-end'><input type='password' name='pass' maxlength='20' minlength='8' id='passnew' maxlength='20' size='20'></div></div>";
}
else{		//nouvelle inscription
    $star="*&nbsp;";
    $texte_email="Une fois la procédure d'inscription terminée, vous pourrez éviter la conservation de votre
    adresse mail en modifiant vos paramètres personnels (Espace perso)";
    $content.="<div class='mb-4 fs-3'>Ouverture d'un compte (gratuit)</div>
        <form method='post' id='form_compte' action='account.php?new=new' name='insert'>";                
    $content.="<div class='row mb-1'><div class='col-9'>IDENTIFIANT (au moins 3 caractères)</div>
    	<div class='col-3 d-flex justify-content-end'>$star<input type='text' name='login' id='loginnew' maxlength='20' value=''></div></div>
    <div class='row mb-1'><div class='col-9'>Mot de passe (de 8 à 20 caractères, pas de caractères spéciaux)</div>
        <div class='col-3 d-flex justify-content-end'>$star<input type='password' name='pass' id='passnew' maxlength='20' size='20'></div></div>";
}
$content.="<div class='row mb-1'><div class='col-9'>Répéter ce mot de passe</div>
    <div class='col-3 d-flex justify-content-end'>$star<input type='password' name='pass2' id='pass2' maxlength='20' size='20'></div></div>
<div class='row mb-1'><div class='col-9'><div class='info over'>E-MAIL<div style='color: red;width:250px;'> $texte_email</div></div></div>
    <div class='col-3 d-flex justify-content-end'>$star<input type='text' name='mail' id='email' maxlength='50' value='".$_SESSION['email']."'></div></div>
    <div class='row mb-3'><div class='col-9'>Site web (facultatif)</div>
    <div class='col-3 d-flex justify-content-end'><input type='text' name='site' maxlength='50' value='".$_SESSION['site']."'></div></div>
<div class='row mb-3'><div class='col-9'>$info_mailing</div>
    <div class='col-3 d-flex justify-content-end'><input type='checkbox' name='courriels' value='y'$checked></div></div>";

if($new=="new"){
    $content.="<div class='row mb-3'><div class='col-9'>Veuillez recopier le code suivant : &nbsp;&nbsp;&nbsp;
    <img src=\"captcha.php\" alt=\"CAPTCHA\" style='vertical-align:middle'/></div>
    <div class='col-3 d-flex justify-content-end d-flex align-items-center'>*&nbsp;<input type='text' name='captcha' maxlength='50' value=''></div></div>";    // captcha
    $content.="<div class='row mb-3'><div class='col-12'><textarea name='textarea' readonly cols='105' rows='10'>".$avert_perso."</textarea></div></div>";  // avertissement
}
if($new=="new"){
    $content.="<div class='row mb-5'><div class='col-12'><button type='submit' id='submit_compte' class='btn btn-secondary' onClick='return valide_compte()'>J&#039accepte</button>&nbsp;&nbsp;
        <a type='button' href='accueil1-lisa_maj_articles.html' class='btn btn-secondary'>Je n&#039accepte pas</a></div></div>";  //  2 boutons
    $content.="</form>";
}
else {     // cas de modification : suppression de compte
    $content.="<div class='row mb-5'><div class='col-12'><button type='submit' id='submit_compte' class='btn btn-success' onClick='return valide_compte()'>Go</button>&nbsp;&nbsp;
    </div></div>";  //  1 bouton
    $content.="</form>";
	$new="change";
	$content.="<div class='mb-4 fs-3'>Suppression de mon compte \"".$_SESSION["pseudo"]."\"</div><h2>
	<form method='post' action='account_delete.php' onsubmit='return confirmLink(this,&#039".$del_av."&#039)'>
	<div class='row mb-2'><div class='col-9'>Mot de passe (saisir à nouveau)</div>
	<div class='col-3 d-flex justify-content-end'>*&nbsp;<input type='password' name='pass3' id='pass3' maxlength='20' size='20' required></div></div>
    <div class='row mb-2'><div class='col-9'>".$del1."</div>
   	<div class='col-3 d-flex justify-content-end d-flex align-items-center'><input type='radio' name='keep' value='y' checked='checked'></div></div>
    <div class='row mb-3'><div class='col-9'>".$del."</div>
   	<div class='col-3 d-flex justify-content-end d-flex align-items-center'><input type='radio' name='keep' value='n'></div></div>
    <div class='row mb-3'><div class='col-12'>".$avert_close."</div></div>
	<div class='row mb-3'><div class='col-12'><button type='submit' id='submit_delete' class='btn btn-danger' onClick='return delete_compte()'>
	Supprimer mon compte</button></div></div></form>";
}
           // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>