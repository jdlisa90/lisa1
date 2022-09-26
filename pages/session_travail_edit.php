<?php
if ($_SESSION['statut']<1){
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
}

function stripslashes_r($var){ // Fonction qui supprime l'effet des magic quotes
    if(is_array($var)){ // Si la variable passée en argument est un array, on appelle la fonction stripslashes_r dessus
        return array_map('stripslashes_r', $var);
    }
    else {// Sinon, un simple stripslashes suffit
        return stripslashes($var);
    }
}
 
if(get_magic_quotes_gpc()){ // Si les magic quotes sont activés, on les désactive avec notre super fonction ! ;)
   $_GET = stripslashes_r($_GET);
   $_POST = stripslashes_r($_POST);
   $_COOKIE = stripslashes_r($_COOKIE);
}

foreach(array("action","colm","l_tr") as $v) 	if( ! isset(${$v}) ) ${$v} = "";
extract($_POST);

$other="";

$avert_info="
DE L'UTILISATION DES INFORMATIONS QUE VOUS SOUMETTEZ

Lorsque vous soumettez des informations, comprenez que l'association LISA a besoin de toute liberté en ce qui concerne leur exploitation. Notamment, en soumettant des informations, vous garantissez et attestez détenir, ou contrôler,les droits nécessaires à cette soumission et vous donnez à l'association LISA et à ses partenaires l'autorisation d'utiliser, de modifier, de copier, de diffuser, de transmettre, d'afficher publiquement, de reproduire, de créer des études dérivatives, de transférer toute information, et accordez à des tiers le droit illimité d'exercer l'un des droits relatifs aux informations que vous soumettez.

Il s'agit notamment du droit d'exploiter des droits de propriété dans les informations, y compris les droits soumis aux droits d'auteur, marque commerciale, marque de service ou brevet dans toutes les juridictions appropriées, sous la condition inaliénable que cette exploitation ne soit pas de nature commerciale ou lucrative.

Aucun dédommagement ne sera payé pour l'utilisation, par l'association LISA, des supports contenus dans les informations soumises. L'association LISA ne sera pas tenue pour responsable de la publication ou de l'utilisation des informations que vous êtes susceptible de fournir, et se réserve le droit de les retirer à tout moment.

Vous avez toutefois la possibilité de les modifier ou, si vous ne souhaitez plus qu'elles apparaissent sur ce site, de les supprimer vous-même à tout moment.
";

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>";
$content.="<div class='mb-4 fs-3'>";
$content.="<img src='../img/logo_arbre.png' class='float-end'>";
$content.="<a href='accueil1.php?disp=session_travaux' class='fs-3'>Mes travaux</a> > "; // row contenant et colonne principale

$sujet="";
$nb_com=1;
if($num_tr!=""){
	$rek_tr="select * from `travaux` where id_tr=".$num_tr;
	try {$id_tr=$bdd->query($rek_tr);}
	catch(Exception $e) {exit($rek_tr.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$l_tr=$id_tr->fetch(PDO::FETCH_ASSOC);
	$sujet=$l_tr["tit_tr"];
	
	$rek_f="select * from `famil_trav` where idtr_ft=".$num_tr;
	try {$id_f=$bdd->query($rek_f);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	  
	$rek_com="select distinct trav_com.comm from trav_com where trav=".$num_tr; //par erreur, plusieurs fois la même commune enregistrée pour le même travail
	try {$id_com=$bdd->query($rek_com);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $communes=array();
    while($l=$id_com->fetch(PDO::FETCH_ASSOC)) $communes[]=$l["comm"];    // tableau contenant les id de communes concernant le travail
	//$nb_com=$id_com->rowCount();
	
	$content.="modification d'un travail indexé";
}
else $content.= "indexation d'un travail";
$content.="</div>";
$content.="<div class='mb-4'>";
$content.="Ce formulaire vous permet d'informer les usagers de ce site du fait que vous avez réalisé des recherches concernant des familles dans certaines
communes.<br>
Comme LISA n'assure pas la présentation de ces travaux, les usagers intéressés devront <b>pouvoir vous contacter</b> afin d'en prendre connaissance, et d'échanger 
avec vous.<br>
Vous voudrez donc bien veiller à ce que l'adresse mail que vous avez enregistrée reste valide.<br>
Merci.";
$content.="</div>";

$content.="<form method='post' action='accueil1.php?disp=session_travail_validation&id_tr=".$num_tr."' onsubmit='return valide_travail(this)'>";
$content.="<div class='row mb-2'><div class='col-3'>Sujet</div><div class='col-9'>
<input type='text' name='titre' class='w-100' value=\"$sujet\" placeholder='ex : Ancêtres de Louis MARTIN, de 1703 à 1850'></div></div>";

$content.="<div class='row mb-2'><div class='col-3'>Commune(s)<br>(sélection multiple possible avec la touche \"ctrl\")</div><div class='col-9'>";
                            // communes

$rek_c="select locality.ID_LOC,locality.LIB_LOC from `locality` order by LIB_LOC";
try {$id_c=$bdd->query($rek_c);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}               

$content.="<select name=communes[] multiple><option value='0'";
if(!isset($communes)) $content.=" selected";
$content.=">&raquo; sélectionner</option>";
while($lc=$id_c->fetch(PDO::FETCH_ASSOC)){
    $content.="<option value='".$lc["ID_LOC"]."'";
	if(isset($communes)){
		if(in_array($lc["ID_LOC"], $communes)) $content.=" selected";
	}
    $content.=">".$lc["LIB_LOC"]."</option>";
}
$content.="</select>";

/* for($k=0;$k<$nb_com;$k++){
    try {$id_c=$bdd->query($rek_c);}        
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    if($num_tr!="")$l_com=$id_com->fetch(PDO::FETCH_ASSOC);

    $content.="<select name='com".$k."'>
    <option value='0' selected>&raquo; sélectionner</option>";
    while($lc=$id_c->fetch(PDO::FETCH_ASSOC)){
        $content.="<option value='".$lc["ID_LOC"]."'";
        if($num_tr!="" AND $lc["ID_LOC"]==$l_com["comm"])$content.=" selected ";
        $content.=">".$lc["LIB_LOC"]."</option>";
    }
    $content.="</select>";
} */
$content.="</div></div><input type='hidden' name='nb_com' value='".$nb_com."'>"; // l'ajout de communes est normalement à prévoir (ou : sélection multiple)

$content.="<div class='row mb-3'><div class='col-3'>Patronymes (1 par case)</div><div class='col-9'>";
for($l=0;$l<12;$l++){
    $content.="<input type='text' name='fam".$l."' class='pe-1' style='width:33.3%;'";
    if($num_tr!=""){
        $l_f=$id_f->fetch(PDO::FETCH_ASSOC);  // parcours des travaux de la table famil_trav
        if($l_f) $content.=" value=\"".stripslashes($l_f["famil_ft"])."\"";    // retirer les \ devant les ' ou "
    }
    $content.=">";
}
$content.="</div></div>";
$content.="<div class='row mb-2'><div class='col-12'>";
$content.="<textarea name='textarea' readonly class='w-100 small' style='height:15em;'>".$avert_info."</textarea>";
$content.="</div></div>";

$content.="<div class='row mb-2 align-items-center'><div class='col-8'><input type='checkbox' name='more' value='y'> référencer un autre travail</div>
    <div class='col-4 d-flex justify-content-end'>";
$content.="<button type='submit' class='btn btn-secondary'>Go</button></form>";
$content.="</div></div>"; // mb pour quasiment tous les "paragraphes"

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>