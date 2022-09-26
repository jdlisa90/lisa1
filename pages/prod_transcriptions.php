<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
function conv_date($str) {
	$mois=array( 0=>"",1 => 'janvier',
     2 => 'février',
     3 => 'mars',
     4 => 'avril',
     5 => 'mai',
     6 => 'juin',
     7 => 'juillet',
     8 => 'août',
     9=> 'septembre',
    10=> 'octobre',
    11 => 'novembre',
    12 => 'décembre');
	$chars = preg_split('#\.|/|-#', $str, -1);
	//print_r($chars);
	$nb_int=0;
	$str1="";
	if(count($chars)==3){
		foreach($chars as $ch){
			if($ch=="") $ch="0";
			if(!preg_match ("/[^0-9]/", $ch)) {
				if($nb_int==1) $str1.=$mois[(int)$ch]." ";
				else $str1.=(int)$ch." ";
				$nb_int++ ;
			}
			else return $str;
		}
		if($nb_int==3) {
			return $str1;
		}
		else return $str;
	}
	else return $str;
}

$sql="create temporary table temp1(arch smallint(6) not null)";
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

if($_SESSION["lecteur"]==1) $sql="INSERT INTO temp1(arch) SELECT DISTINCT acts.arch FROM acts WHERE acts.transcription!='' AND pub!=3";
else $sql="INSERT INTO temp1(arch) SELECT DISTINCT acts.arch FROM acts WHERE acts.transcription!='' AND pub=1 AND pub_transcription=1";
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$sql="create temporary table temp2(arch smallint(6) not null, dep_lib varchar(25) null, 
		pref varchar(10) null, serie_lib tinytext null, suff1 varchar(10) null, suff varchar(10) null,lib tinytext null)";
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$sql="SELECT temp1.arch, arch_dep.lib AS dep_lib,ss.pref,arch_serie.lib AS serie_lib,ss.suff1,ss.suff, ss.lib, ss.id 
	FROM temp1, arch_ssserie AS ss, arch_dep, arch_serie WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND ss.id1=temp1.arch 
	ORDER BY dep_lib, serie_lib,pref,suff1";
try {$req1=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

// 		AFFICHAGE
$allowable_tags=array("<b>","</b>","<br>","<i>","</i>","<em>","</em>");

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>
<h3>Transcriptions d'actes</h3>
<div class='mb-3'>Transcriptions complètes ou partielles d'actes anciens. Consulter si nécessaire le <a href='glossaire_MF.html' target='_blank'>glossaire du moyen français</a>.
<br><span class='bg-secondary fst-italic'>Placer le curseur sur le résumé pour afficher la transcription complète.</span></div>"; // row contenant et colonne principale
$id=1;	// numéro des toggle pour la target du lien
while($l1=$req1->fetch(PDO::FETCH_ASSOC)) {									// listes par archive
	if($_SESSION["lecteur"]==1) $sql="SELECT NUM,DATE_ACT,DIVERS, RIGHT(DATE_ACT,4) AS AN, TYPE_ACT, img, vue, nom_ch, transcription, ordre_transcription, pub, pub_transcription FROM 
	acts LEFT JOIN chercheurs ON chercheurs.id_ch=acts.auteur WHERE arch=".$l1["arch"]." AND transcription!='' AND pub!=3 ORDER BY AN, ordre_transcription";
	else $sql="SELECT NUM,DATE_ACT,DIVERS, RIGHT(DATE_ACT,4) AS AN, TYPE_ACT, img, vue, nom_ch, transcription, ordre_transcription FROM acts LEFT JOIN chercheurs ON chercheurs.id_ch=acts.auteur 
	WHERE arch=".$l1["arch"]." AND transcription!='' AND pub=1 AND pub_transcription=1 ORDER BY AN, ordre_transcription";
	try {$req2=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$text="";
	$nb_trans=0;
	while($l2=$req2->fetch(PDO::FETCH_ASSOC)) {		// constitution du texte formant les transcriptions pour l'archive
		if($_SESSION["lecteur"]==1) {
			$text.="<input type='checkbox' ";
			if($l2["pub"]==0 OR $l2["pub_transcription"]==0) $text.="unchecked title='non publiée' ";
			else $text.="checked title='publiée' ";
			$text.="onclick='return false'> ";		// checkbox non cliquable
		}
		$dat=conv_date($l2["DATE_ACT"]);		// convertir la date de la manière la plus naturelle
		if(substr($dat, 0, 2)=="? ")$dat=substr($dat, 2);
		if(substr($dat, 0, 2)=="? ")$dat=substr($dat, 2);
		if(substr($dat, 0, 2)=="0 ")$dat=substr($dat, 2);
		if($dat=="") $dat="non daté";
		$text.="<b>$dat; ".$l2["TYPE_ACT"]."</b> :";
		if($l2["DIVERS"]!="") {
			$text.="<br>";
			$description=strip_tags($l2["DIVERS"],"<b></b><br><i></i><em></em>"); // en 2020, php version 7.0.33, seule la version 7.4 accepte allowable_tags sous forme de tableau
			if(strlen($description)<100) $text.=$description;
			else $text.=substr($description, 0, 100)."...";
		}
		$text.="<div class='sujet bg-secondary fst-italic p-1'>".nl2br(strip_tags(substr($l2["transcription"], 0, 100)))."..."; // résumé
		$text.="<div class='complement' style='width:700px;font-style: italic;'>".$l2["transcription"]."</div></div>";
		if($l2["nom_ch"]) $text.="par ".$l2["nom_ch"]." | ";
		$text.="<a href='".$l2["img"]."' onclick='window.open(this.href);return false'>image</a>";
		if($l2["vue"]!="") $text.= " (<b>".$l2["vue"]."</b>)";
		if($_SESSION["lecteur"]==1 AND $l2["ordre_transcription"]!=0) $text.= " | repère : ".(int)$l2["ordre_transcription"];
		if($_SESSION["lecteur"]==1) $text.=" | <a href='../edition/acte_edit.php?num=".$l2["NUM"]."' target='_blank'>édition</a>";
		else $text.= " | <a href='../affichage_actes/act-".$l2["NUM"]."-1-".$l1["arch"].".html' target='_blank'>synthèse de l'acte</a>";
		$text.="<br><br>";
		$nb_trans++;
	}

	$content.="<div class='row series'><div class='col pt-2 pb-2'>
	<span class='fs-4'><a data-bs-toggle='collapse' href='#n$id' role='button' aria-expanded='false' aria-controls='n$id' class='toggle'>▶</a> "; // début de la ligne de titre et support du toggle
	$content.=$l1["dep_lib"]." ".$l1["pref"].$l1["serie_lib"]." ".$l1["suff1"]." ".$l1["suff"]." ".$l1["lib"]."</span>"; // intitulé de la série

	$content.="<span> <a href='archive-".$l1["id"].".html' target='_blank' title=\"présentation de l'archive\"><img src='../img/book.png' width='20' height='20' style='vertical-align:middle;'></a>";
	if($_SESSION['statut']==10) $content.="&nbsp;<a href='transcript_export.php?arch=".$l1["arch"]."' target='_blank'>Exporter</a>";
	$content.="  | <b>$nb_trans</b> transcription";
	if($nb_trans>1)$content.="s";
	$content.="</span>";	// lien vers la présentation de l'archive et nb de transcriptions
	$content.="</div>";		//fermeture de la ligne
		// contenu du toggle
	$content.="<div class='collapse ms-4' id='n$id'>";
	$content.=$text."</div></div>";		// fermeture de la 
	$id++;
}

$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant
?>
<script>
$(document).ready(function (){
	$("body").on("click",".toggle",function(){
        if($(this).text()=="▶"){
            $(this).text("▼");
        }
        else{
            $(this).text("▶");
        }
    });
});

function toggle(elt) {
    if($(this).text()=="▶"){
        $(elt).text("▼");
    }
}
</script>
<style>
.sujet {
  position: relative;
  display: table-cell;
  background-color: var(--light-bg-color);
}
.sujet:hover .complement {
  visibility: visible;
  opacity: 1;
  transform: translate3d(0px, 0px, 0px);
}
.sujet p{
	margin-top: 0px;
	margin-bottom: 0px;
}
.sujet .complement {
  visibility: hidden;
  background-color: var(--vlight-bg-color);
  font-style: italic;
  text-align: left;
  border-radius: 6px;
  padding: 10px;
  position: absolute;
  z-index: 1;
  /*bottom: -140px;*/
  /*left: -80px;*/
  /*margin-left: -60px;*/
  opacity: 0;
  transition: .3s;
  transform: translate3d(0px, 20px, 0px);
}
</style>