<style type="text/css">
.menu_fixe{
/* 	position: fixed;
	left: 150px;
	top:40%;
	padding-left: 5px;
	padding-right: 5px;
	text-align:center;
	font-size:14px;
	opacity: 50%;
	transform: translate(0, -50%); */
	position: fixed;
	left: 4px;
	top:102px;
	width: 18px;
	text-align:center;
	line-height:14px;
	font-size:16px !important;
}
</style>
<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$sql="SELECT glossaire_articles.*, glossaire_citations.lib AS citation, glossaire_citations.link, glossaire_citations.url FROM glossaire_articles 
LEFT JOIN glossaire_citations ON glossaire_citations.id_art=glossaire_articles.id AND glossaire_citations.pub=1 WHERE glossaire_articles.pub=1
ORDER BY glossaire_articles.lib, glossaire_articles.id, glossaire_citations.rang";
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$n_entr=$req->rowCount();

$sql="SELECT * FROM glo_refer ORDER BY id";
try {$req1=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$sql="SELECT * FROM glo_note ORDER BY id";
try {$req2=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}


$content="<div class='row justify-content-center'>
<div class='col pt-3 pb-4' style='margin-left: 75px;'>
<span class='fs-4'>Glossaire des sources belfortaines</span> ($n_entr entrées)<br>
Ce glossaire contient les termes susceptibles de
poser des difficultés dans la compréhension de sources locales antérieures à 1600, et en particulier les \"Comptes des revenus de la Ville de 
Belfort\", d'où sont extraits
la plupart des exemples : <a href='https://archives.belfort.fr/ark:/50960/g0sp9b8jf62z/82b13fcf-f350-4c5b-908b-953399a05399' target='_blank'>AmB CC4</a>,
<a href='https://archives.belfort.fr/ark:/50960/6cm9q84p7jxs/58ce883e-cd39-486e-80ad-fe5418948ea2' target='_blank'>CC5</a>,
<a href='https://archives.belfort.fr/ark:/50960/2qksr0hdmzbw' target='_blank'>CC6</a> et 
<a href='https://archives.belfort.fr/ark:/50960/q869t4x7gflh' target='_blank'>CC9</a>.
<br>
Tous les termes n'ont malheureusement pas pu trouver une explication complète et précise ; toute proposition est la bienvenue.<br>
Pour les termes de la justice seigneuriale du XVIIème siècle, on pourra consulter ce <a href='accueil1.php?disp=article-ancien&article=74' target='_blank'>petit glossaire</a>.";

$content.="<div class='bg-secondary menu_fixe' style='font-size:16px !important;'>";
$txt="";// menu alphabétique vertical fixe
for($c=1;$c<25;$c++) {
	$txt.="<a href='#&#".(64+$c)."'>&#".(64+$c)."</a></br>";
/* 	if($c!=0 AND $c % 4==0)$txt.="</br>";
	else $txt.=" "; */
}	
$content.= substr($txt, 0);
$content.="</div>";
$content.="<table class='table table-sm border-top border-secondary border-1 mt-3'>
<tbody>";
$id="";
$citation="";
while ($l=$req->fetch(PDO::FETCH_ASSOC)) {
	if($id!=$l["id"]){	//écriture des champs de l'id précédent
		if($id!=""){
			$content.="<tr id=\"$id\"><td>";
			if($initiale!=$init){
				$content.="<a id='$init'></a>";
				$initiale=$init;
			}
			$content.="<strong>$lib</strong><td>$type<td>$image<td>$comment<br>$citation";
			if($actes!="") $content.=$txt_ref.$actes;
		}
		$id=$l["id"];
		$init=strtoupper(substr($l["lib"], 0, 1));
		$citation="";
	}
	$lib=$l["lib"];
	if (isset($_SESSION["authentification"]) AND ($_SESSION['editeur']==1))$lib="<a class='pointer' onclick='copyToClipboard(this,\"http://www.lisa90.org/lisa1/pages1/glossaire_MF.html#".$id."\")' 
	title='cliquer pour copier le lien dans le presse-papier'>".$l["lib"]."</a>";
	$type=$l["type"];
	$comment=nl2br($l["comment"]);
	if($l["image"]!=""){
		$image="<img src='../glossaire_images/".$l["image"]."' style='max-width:300px;vertical-align:middle;' alt=''>";
		if($l["text_image"]!="" OR $l["source_image"]!="") {
			$image.="<br>".$l["source_image"];
			if($l["text_image"]!="") $image.=" <em>(".$l["text_image"]."</em>)";
		 }
		//$image.="<br>";
	}
	else $image="";
	if($l["citation"]!="") $citation.="<div class='cit_glossaire'><em>".$l["citation"]."</em> (<a href='".$l["url"]."' target='_blank'>".$l["link"]."</a>)</div>";
		
			// CITATIONS dans des actes
	$sql="SELECT acte, an, arch FROM `glossaire-actes` WHERE entry='$id' ORDER BY an";
	try {$reqa=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage()."<br>".$sql);}
	$actes="";
	$list_actes=array();
	while ($la=$reqa->fetch(PDO::FETCH_ASSOC)) {
		$list_actes[$la["an"]]=array($la["acte"],$la["arch"]);	// pour n'inscrire qu'un acte par année (contrairement à la page éditeur)
	}
	$txt_ref="<br>référence(s) en : ";
	foreach($list_actes as $an=>$value) $actes.="<a href='../affichage_actes/act-".$value[0]."-1-".$value[1].".html' target='_blank'>".$an."</a> ";
}
$content.="<tr id='$id'><td><strong>$lib</strong><td>$type<td>$image<td>".$comment."<br>$citation";		// purge du dernier id
if($actes!="") $content.= $txt_ref.$actes;
$content.="</tbody></table>
<br>";
$content.="<h4>RÉFÉRENCES</h4>
<ol style='padding-left: 22px;'>";
while ($l1=$req1->fetch(PDO::FETCH_ASSOC)) {
	$content.="<li value='".$l1["id"]."'><div class='reference' id='ref".$l1["id"]."'>"." ".$l1["lib"]." </div></li>";
}


$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>