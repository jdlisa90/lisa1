<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content">, qui applique une bordure fine en bas de ses enfants directs 'row' (sauf la dernière) ou 'col'

$tabl="img_ext90_1"; // alterner les tables mel_gnn et mel_gnn1 pour les mises à jour
$id=1;
function sel_gnn($ent) {
	global $bdd;
	global $tabl;
	global $id;
    $sql="SELECT * FROM $tabl WHERE entity=$ent ORDER BY ordre, serie, init_serie, `ordre_ss-serie`, lib, init_lot, an0, num_suite, num_suite_complement";
	try {$req=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$txt="<div style='margin-bottom:4px;'>";
	$serie="";
	$lib="";
	$rem_lot="";
    
	while ($l=$req->fetch(PDO::FETCH_ASSOC)){
		if($l["serie"]!=$serie){		// nouvelle table, à chaque nouvelle série
			if($serie!=""){
				$txt.="</div>";	// 	fermeture dernière table de la série précédente
				$txt.="</div>";	// 	fermeture div série précédente (sauf la 1ère)
				$txt.="</div>";
			}
			// Écriture de la ligne de SÉRIE
            $txt.="<div class='row series pt-2 pb-2' id='k$id'>
				<span><a data-bs-toggle='collapse' href='#n$id' role='button' aria-expanded='false' aria-controls='n$id' class='toggle'><span onClick='tog(this)'>▶</span></a> ".$l["serie"];
			if($l["archive"]<>"0") $txt.=" <a href='accueil1.php?disp=sources_archive&arch=".$l["archive"]."' target='_blank' title='Description'>
			<i class='bi bi-card-text' style='font-size:1rem;'></i></a>";
			if($l["rem_serie"]<>"") $txt.="<br>".$l["rem_serie"];
            $txt.="</span><div class='collapse ms-4' id='n$id'>";
            $id++;
			$lib="";
			$rem_lot="";
			$serie=$l["serie"];
			$ordre=$l["ordre"];
			$init_lot=$l["init_lot"];
			$init_serie=$l["init_serie"];
		}
		if ($l["lib"]<>$lib OR $l["rem_lot"]<>$rem_lot){ // pour définir un "paragraphes" pour chaque lot, s'il y a changement de titre ou de remarque du lot
			if($lib!=""){
                $txt.="</div>";	//fermeture table précédente de la même série (sauf la 1ère)
			}
			$lib=$l["lib"];			// lib est le titre du lot de cotes (en référence à son contenu)
			$rem_lot=$l["rem_lot"];
			$ordre_gr=$l["ordre_ss-serie"];	// pour un classement des cotes différent de l'année
			if($l["init_serie"]>0){
                $txt.="<a data-bs-toggle='collapse' href='#n$id' role='button' aria-expanded='false' aria-controls='n$id' class='toggle'>
				<span onClick='tog(this)'>▶</span></a> ".$lib;
                if($l["rem_lot"]<>"") {
					if(substr($l["rem_lot"],0,1)=="(") $txt.=" ".$l["rem_lot"];
					else $txt.=" (".$l["rem_lot"].")";
				}
				$txt.="<br>";
                $txt.="<div class='row collapse ms-3' id='n$id'>";
                $id++;
			}
		}
		$init_lot=$l["init_lot"];
		if($init_lot>0){			// sinon, il s'agit d'une ligne créée artificiellement par l'interface de gestion, qui ne doit pas être affichée
            if($l["url"]=="") $txt.="<div class='col-3'>".$l["serie"]." ".$l["num_suite"].$l["num_suite_complement"]."</div><div class='col-7'>"; // pas de lien si dep_lisa
			else {
                $txt.="<div class='col-3'><a href='".$l["url"]."'";
				if($l["num_suite"]==0) $num_suite="";
				else $num_suite=$l["num_suite"];
                $txt.=" target='_blank'>".$l["serie"]." ".$num_suite." ".$l["num_suite_complement"]."</a></div><div class='col-7'>";
			}
			$txt.=$l["rem_cote"];
			if($l["dep_lisa"]<>""){
				$txt.=" <b><em>dépouillement LISA ";
			}
			$txt.="</div><div class='col-2'>".$l["an0"];
			if($l["an1"]<>"" AND $l["an1"]<>$l["an0"]) $txt.="-".$l["an1"];
			
			if ($_SESSION['statut']>7) $txt.=" (".$l["id"].")";
            $txt.="</div>";
		}
	/* 	$groupe=$l["id"];			// dans tous les cas
		$groupe_lib=$l["lib"];	//
		if($l["archive"]<>"0") $groupe_lib.=" <a href='accueil1.php?disp=sources_archive&arch=".$l["archive"]."' target='_blank'><i class='bi bi-card-text' style='font-size:1rem;'></i></a>";
 */		$ordre_gr=$l["ordre_ss-serie"];	
	}
    $txt.="</div></div>";		//fermeture de la dernière table et de la dernière div
	$txt.="</div>";
	$txt.="</div>";				// fin div globale
	//if($ent==1250) $content.="<script>alert(".$txt.");</script>";
	return $txt;
}
$txt_ensisheim="<div style='margin-bottom:20px;'>Liste des cotes de la série AD68 1C (régence d'Ensisheim) concernant ";
/*                                                                   AFFICHAGE                                       */
$content="<div class='row justify-content-center mt-3'><div class='col-8'>
<p><span class='fs-4'>Liste des sources en ligne concernant le Territoire-de-Belfort</span>, principalement anciennes, sur des sites <b>autres que ceux des services archivistiques de ce département
</b>(archives départementales ou municipales).</p>";
$sql="SELECT SUM(`nb_reg`) AS nb FROM ".$tabl." WHERE `url`<>''";
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$req->fetch(PDO::FETCH_ASSOC);
$content.="<p>Actuellement, <b>".$l["nb"]."</b> cotes sont disponibles.</p>";

$content.="<p>La majorité de ces sources <b>n'a pas encore été dépouillée par LISA</b>.<br>
Tout usager inscrit à la possibilité de participer à ces dépouillements. Veuillez à cette fin
<script type=text/javascript>
var name = '".strrev(str_rot13("09nfvy"))."' ;
var domain = '".str_rot13("benatr.se")."' ;
var subject = 'Dépouillements';
document.write('<a href=\"ma'+'ilto:' + name + '@' + domain + ' ?subject=' + subject + '\">');
document.write('nous contacter</a>.');
</script></p>";
/*                                                  SOMMAIRE                    */
$content.="
<div class='border border-2 border-secondary ps-3 pe-3 pt-2 mb-3' style='width:520px'>
<h5>Archives anciennes</h5>
<ul>
<li><a href='#a1'>Comté, magistrat et prévôté de <b>Belfort</b> et seigneurie du <b>Rosemont</b></a></li>
<li><a href='#a10'>Seigneurie de <b>Courcelles</b></a></li>
<li><a href='#a8'>Seigneurie de <b>Delle</b></a></li>
<li><a href='#a14'>Seigneurie d'<b>Essert</b></a></li>
<li><a href='#a2'>Seigneurie de <b>Florimont</b></a></li>
<li><a href='#a9'>Seigneurie de <b>Fontaine</b></a></li>
<li><a href='#a3'>Seigneurie de <b>Froidefontaine</b></a></li>
<li><a href='#a12'>Seigneuries de <b>Grandvillars</b>, <b>Morvillars</b> et <b>Thiancourt</b></a></li>
<li><a href='#a4'>Seigneuries de <b>Montreux</b> et <b>Foussemagne</b></a></li>
<li><a href='#a11'>Seigneurie de <b>Lachapelle-ss-Rougemont</b></a></li>
<li><a href='#a5'>Seigneurie de <b>Rougemont</b></a></li>
<li><a href='#a13'>Principauté de <b>Montbéliard</b> et seigneurie de <b>Blamont</b> (Beaucourt)</a></li>
</ul>
</div>
<div class='border border-2 border-secondary ps-3 pe-3 pt-2 mb-3' style='width:520px'>
<h5>Archives Modernes</h5>
<ul>
<li><a href='#b1'>Arrondissement de <b>Belfort</b> (1792-1871)</a></li>
<li><a href='#b2'><b>Territoire de Belfort</b> (après 1871)</a></li>
</ul>
</div>";

$content.="<h3 class='fw-bold'>Archives anciennes</h3><h4 id='a1'>Comté, magistrat et prévôté de Belfort et seigneurie du Rosemont</h4>
<a href='#' data-bs-toggle='modal' data-bs-target='#image_modale' data-bs-image='../img/cartes/Bel.png' data-bs-title='Comté de Belfort'>
<img src='../img/cartes/Bel_thumbnail.jpeg' style='cursor: zoom-in;'></a>";
$content.=sel_gnn(1229);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Belfort.pdf' target='_blank'>le comté de Belfort</a> ; 
 <a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Rosemont-mines.pdf' target='_blank'>les mines et la seigneurie du Rosemont</a>.</div>";

$content.="<h4 id='a10'>Seigneurie de Courcelles</h4>
<img src='../img/cartes/Crc.png'>";
$content.=sel_gnn(1250);

$content.="<h4 id='a8'>Seigneurie de Delle</h4>
<a href='#' data-bs-toggle='modal' data-bs-target='#image_modale' data-bs-image='../img/cartes/Del.png' data-bs-title='Seigneurie de Delle'>
<img src='../img/cartes/Del_thumbnail.jpeg' style='cursor: zoom-in;'></a>";
$content.=sel_gnn(302);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Delle.pdf' target='_blank'>la seigneurie de Delle</a>.</div>";

$content.="<h4 id='a14'>Seigneurie d'Essert</h4>
<img src='../img/cartes/Ess.png'>";
$content.=sel_gnn(354);

$content.="<h4 id='a2'>Seigneurie de Florimont</h4>
<img src='../img/cartes/Flo.png'>";
$content.=sel_gnn(312);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Florimont.pdf' target='_blank'>la seigneurie de Florimont</a>.</div>";

$content.="<h4 id='a9'>Seigneurie de Fontaine</h4>
<img src='../img/cartes/Fnt.png'>";
$content.=sel_gnn(351);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Fontaine.pdf' target='_blank'>la seigneurie de Fontaine</a>.</div>";

$content.="<h4 id='a3'>Seigneurie de Froidefontaine</h4>
<img src='../img/cartes/Fdf.png'>";
$content.=sel_gnn(345);

$content.="<h4 id='a12'>Seigneurie de Grandvillars, Morvillars et Thiancourt</h4>
<img src='../img/cartes/Grv.png'>";
$content.=sel_gnn(347);

$content.="<h4 id='a4'>Seigneurie de Montreux et Foussemagne</h4>
<img src='../img/cartes/Montreux.png'>";
$content.=sel_gnn(301);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Montreux.pdf' target='_blank'>les seigneuries de Montreux</a>.</div>";

$content.="<h4 id='a11'>Seigneurie de Lachapelle-sous-Rougemont</h4>Lachapelle et quelques habitants des villages alentours";
$content.=sel_gnn(355);

$content.="<h4 id='a5'>Seigneurie de Rougemont</h4>
<a href='#' data-bs-toggle='modal' data-bs-target='#image_modale' data-bs-image='../img/cartes/Rgt.png' data-bs-title='Seigneurie de Rougemont'>
<img src='../img/cartes/Rgt_thumbnail.jpeg' width='348' height='500'></a>";
$content.=sel_gnn(361);
$content.=$txt_ensisheim."<a href='http://img.lisa90.free.fr/archives/ad068/inventaires_pdf/Rougemont.pdf' target='_blank'>la seigneurie de Rougemont</a>.</div>";

$content.="<h4 id='a13'>Principauté de Montbéliard et seigneurie de Blamont</h4>
<b>La seigneurie de Blamont</b> concerne la majeure partie des habitants de Beaucourt ; quelques familles de <b>Châtenois</b> sont sujets de la PM.
<br>Deux collections ont été numérisées : 4 cotes de la série K (Ppté de Montbéliard) des Archives Nationales, par le groupe \"familles parisiennes\", et, d'autre
part, 5 cotes de la série EPM des AD25.";
$content.=sel_gnn(1359);

$content.="<div class='row series'></div>
<h3 class='pt-4 fw-bold'>Archives modernes</h3><h4 id='b1'>Arrondissement de Belfort (1792-1871)</h4>";
$content.=sel_gnn(1500);

$content.="<h4 id='b2'>Territoire de Belfort (après 1871)</h4>";
$content.=sel_gnn(1600);

$content.="</div></div>";

?>
										<!-- FENÊTRE MODALE D'AFFICHAGE DES IMAGES -->
<div class="modal fade modal_bg" tabindex="-2" id="image_modale">
	<div class="modal-dialog modal-lg h-100 mt-0">
	<div class="modal-content text-center">
		<div class="modal-header border-0 p-0 bg-secondary">
			<div class="col text-center">
			<span class='fs-4' id="modal-title">Titre</span>
			</div>
		</div>
			<div id='modal-image'>
		</div>
		<div class="modal-footer border-0 p-0 bg-secondary">
		<button type="button" class="btn-close" id='btn_close' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
	</div>
	</div>
</div>
													<!-- SCRIPTS -->
<script>
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

	modalTitle.textContent = imageTitle
	imageDiv.innerHTML = "<img class='mw-100 mh-100' src='" + imageSrc + "'>"
})
</script>