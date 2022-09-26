<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)
function dat($dat,$l){
	$pieces=explode("-",$dat);
	return date("d/m/y",mktime(0,0,0,$pieces[1],$pieces[2],$pieces[0]));
}

$sect=array(0=>"Structure du site",1=>"Ajout d'une archive dans la base de données", 2=>"Nouveaux articles", 3=>"Prénoms anciens", 4=>"Histoire",	6=>"Recensement", 9=>"Administration");

$content= "<div class='row justify-content-center'><div class='col-9 pt-4 pb-4'><div class='mb-3'><span class='fs-3'>Historique des mises à jour </span> depuis 2010</div>";
$sql="SELECT histo.*,UNIX_TIMESTAMP(date_maj) AS u, illustrations.img, illustrations.info AS info, illustrations.legende AS legende, illustrations.lien AS lien_ill 
	FROM histo LEFT JOIN illustrations ON illustrations.id=histo.illustration WHERE date_maj>'2010-00-00' ORDER BY date_maj DESC";
try {$id=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$n=0; // numérotation des commentaires des toggle
while($l=$id->fetch(PDO::FETCH_ASSOC)){
	$content.="<div class='row pb-2 pt-2'>";
    if ($l["sect"]==1 and $l["lien"]>0){	//mise en ligne d'archive référencée
        if($l["u"]<mktime(0,0,0,1,18,2007)) $sql1="SELECT type_arch,lib FROM arch_ssserie WHERE id1=".$l["lien"];
		else $sql1="SELECT type_arch,lib FROM arch_ssserie WHERE id=".$l["lien"];
		try {$id1=$bdd->query($sql1);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
		$l1=$id1->fetch(PDO::FETCH_ASSOC);
		if($l["comment"]!="") {
			$n++;
			$lib="<div><a data-bs-toggle='collapse' href='#n$n' role='button' aria-expanded='false' aria-controls='n$n' class='toggle'><span  onClick='tog(this)'>▶("
			.$type_arch[$l1["type_arch"]].") ".$l1["lib"]."</span></a></div>
				<div class='collapse' id='n$n'>".nl2br($l["comment"])."</div>";
		}
		else $lib="<b>"."(".$type_arch[$l1["type_arch"]].") ".$l1["lib"]."</b>";
    }
	else $lib="";
	$content.="<div class='col-1 d-flex align-items-center'>".dat($l["date_maj"],$lang)."</div><div class='col-2'>";
	if($l["img"]!=""){
		$image="../docs".$dossier_illustr[$l["info"]]."/".$l["img"];
		if(!empty($l["lien_ill"])) $legende="<a href='".$l["lien_ill"]."' target='_blank'>".$l["legende"]."</a>";
		else $legende=$l["legende"];
		$content.="<div class='d-flex align-items-center'><a href='#' data-bs-toggle='modal' data-bs-target='#image_modale' data-bs-image='$image' data-bs-title=\"".$legende."\">
		<img src='".vignette($image, 120, 100)."'></img></a></div>";
	}
	$content.="</div><div class='col-9 d-flex align-items-center'>";
	if($l["sect"]!=1) $content.="<i>".$sect[$l["sect"]]."</i>&nbsp; : &nbsp;";
	$content.="<div>$lib</div>";
	if($l["sect"]!=1 AND $l["titre"]<>"") $content.= trim(nl2br($l["titre"]));
	$content.="</div></div>";
}
$content.="</div></div>";
?>
										<!-- FENÊTRE MODALE D'AFFICHAGE DES IMAGES -->
										<div class="modal fade modal_bg" tabindex="-2" id="image_modale">
	<div class="modal-dialog modal-lg h-100 mt-0">
	<div class="modal-content text-center">
		<div class="modal-header border-0 p-0 bg-secondary">
			<div class="col text-center">
			<span id="modal-title"></span>
			</div>
		</div>
			<div class="bg-secondary" id='modal-image'>
		</div>
		<div class="modal-footer border-0 p-0 bg-secondary">
		<button type="button" class="btn-close" id='btn_close' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
	</div>
	</div>
</div>
													<!-- SCRIPTS -->
<script>
var Modal_Image = document.getElementById('image_modale')
Modal_Image.addEventListener('show.bs.modal', function (event) {
	// Button that triggered the modal
	var target = event.relatedTarget
	// Extract info from data-bs-* attributes
	var imageTitle = target.getAttribute('data-bs-title')
	var imageSrc = target.getAttribute('data-bs-image')

	// Update the modal's content.
	var modalTitle = Modal_Image.querySelector('#modal-title')
	var imageDiv = Modal_Image.querySelector('#modal-image')

	modalTitle.innerHTML = imageTitle
	imageDiv.innerHTML = "<img class='mw-100 mh-100' src='" + imageSrc + "'>"
})
function tog(elt) {
	var texte=elt.innerText;
 	if(texte[0]=="▶"){
		texte=texte.replace("▶", "▼");
		elt.innerText=texte;
	}
	else{
		texte=texte.replace("▼", "▶");
		elt.innerText=texte;
	}
}
</script>