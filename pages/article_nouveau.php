
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$sql="SELECT * FROM article WHERE id=:article";
$req=$bdd->prepare($sql);
$req->bindValue('article', $article, PDO::PARAM_INT);
try{$req->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$req->fetch(PDO::FETCH_ASSOC);

$sql="SELECT * FROM article_chapitre WHERE article=:article AND n1=0";		// il peut y avoir, par erreur, plusieurs intros
$reqI=$bdd->prepare($sql);
$reqI->setFetchMode(PDO::FETCH_ASSOC);
$reqI->bindValue('article', $article, PDO::PARAM_INT);
try{$reqI->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$intros=$reqI->fetchAll();

$sql="SELECT * FROM article_chapitre WHERE article=:article AND n1>0 ORDER BY n1,n2,n3,n4,n5,n6";
$reqC=$bdd->prepare($sql);
$reqC->setFetchMode(PDO::FETCH_ASSOC);
$reqC->bindValue('article', $article, PDO::PARAM_INT);
try{$reqC->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$paragraphes=$reqC->fetchAll();

$sql="SELECT * FROM article_ref WHERE article=:article AND type=1 AND pub=1 ORDER BY num"; // maintenu pour les premiers articles convertis
$reqR=$bdd->prepare($sql);
$reqR->setFetchMode(PDO::FETCH_ASSOC);
$reqR->bindValue('article', $article, PDO::PARAM_INT);
try{$reqR->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$references=$reqR->fetchAll();

$sql="SELECT * FROM article_ref WHERE article=:article AND type=2 AND pub=1 ORDER BY num";
$reqN=$bdd->prepare($sql);
$reqN->setFetchMode(PDO::FETCH_ASSOC);
$reqN->bindValue('article', $article, PDO::PARAM_INT);
try{$reqN->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$notes=$reqN->fetchAll();

if(isset($_SESSION["authentification"])) {
	$sql="SELECT COUNT(*) as nb FROM article_auteur WHERE article=:article AND auteur=".$_SESSION['_id'];
	$reqH=$bdd->prepare($sql);
	$reqH->bindValue('article',  $article, PDO::PARAM_INT);
	try{$reqH->execute();}
	catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$lH=$reqH->fetch(PDO::FETCH_ASSOC);
}

$content="<div class='row justify-content-center'>
<div class='col-10 pt-4 pb-4'>"; // row contenant et colonne principale
//$content.=$id." ".$_SESSION['_id']." ".$lH["nb"]." ".$_SESSION['lecteur']." ".$l["pub"]." "; !!! ($_SESSION['lecteur'] ne semble pas défini)

if($lH["nb"]==0 AND $_SESSION['lecteur']<1 AND $l["pub"]!=1) $content.="Cet article n'est pas public."; // condition : article public OU lecteur = oui OU chercheur est l'un des auteurs
else {
//						INTRO(s)
	$sql="SELECT id FROM `article-indexes` WHERE new_old='new' AND article=$article";
	try {$req=$bdd->query($sql);}
	catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	if($req->rowCount()>0) {
		$icon="-check";
		$txt="compléter";
	}
	else {
		$icon="";
		$txt="démarrer";
	}
	$content.="<div class='titre_article'>";
	if($_SESSION['statut']==10){
		$content.="<a href='javascript:indexation($article,\"new\")' title=\"$txt l'indexation de l'article\"><i class='bi bi-clipboard$icon fs-1'></i></a> &nbsp;";
	}
	if(($lH["nb"]>0 OR $_SESSION['lecteur']>0) AND !isset($pdf)){
		$content.="<a href='article_pdf.php?article=$article' target='_blank' title=\"Voir l'article en mode compatible PDF\"><i onclick='return pdf()' class='bi bi-file-earmark-pdf fs-1'></i></a> &nbsp;";
	}
	$titre=preg_replace("#^<p>#", "", $l["titre"]);
	$titre=preg_replace("#^<strong>#", "", $titre);
	$content.=$titre."</div>";	// Titre
    //if(!empty($l["abstract"])) $content.="<div style='font-weight:bold;'>".$l["abstract"]."</div>";
    if(!empty($l["image"])) {
        $content.="<div class='row'><div class='col flex-grow-0 small fst-italic legende'><img src='".$l["image"]."'>"; // image
        if(!empty($l["ref_image"]))$content.="<br>".preg_replace("#^<p>#", "", $l["ref_image"]);
        $content.="</div></div>";
	}
	$content.="<div class='fst-italic pb-3'>Par ".nl2br($l["auteur"])."</div>";

	if($l["lib_arch"]!=""){
		$content.="<div class='pb-3'>";
		if($l["id_arch"]!="") {
				$content.="Archive : <a href='accueil1-sources_archive-arch-".$l["id_arch"].".html' target='_blank'>".$l["lib_arch"]."</a>";	//envoi de la variable archive en mode POST
		}
		else $content.=$l_art["ref"];
		$content.="</div>";
	}

    //$content.="<div class='article_content'>";
	if($reqI->rowCount()>0) {
		foreach($intros as $li){
			$intro=preg_replace("#^<p>#", "", $li["content"]);
			$intro=trim(preg_replace("#</p>$#", "", $intro));
			$intro1=trim(preg_replace("#^<h2>#", "", $intro));
			$intro1=trim(preg_replace("#</h2>$#", "", $intro1));
			$intro1=trim(preg_replace("#&nbsp;#", "", $intro1));
			if ($intro1!="") $content.="<div class='pb-3'>$intro</div>";
		}
	}

	//						PLAN
	if($reqC->rowCount()>0) {
		$content.="<div class='rectangle_light' style='max-width:450px;'><b>Sommaire :</b><ul class='sommaire'>";
		$parent="";
		$niveau=1;
		foreach ($paragraphes as $lC) {
			$titre="";
			$rang=$lC["n1"];
			for($n=2;$n<7;$n++) {
				if($lC["n".$n]==0) break;		// détermination du dernier niveau non nul
				else $rang.="-".$lC["n".$n];
			}	
			$niv=$n-1;
			$titre.=$rang.". <a href='#$rang'>".$lC["titre"]."</a>";
			if($niv==$niveau) {
				$content.="<li>".$titre;
			}
			else{
				$diff=$niv-$niveau;		// nouveau-ancien
				if($diff>0) {
					for($d=0;$d<$diff;$d++) {		// normalement, $diff<=1
						$content.="<ul><li>";	// si le nouveau niveau est supérieur au précédent, décalages vers la droite
					}
					$content.=$titre;
				}
				else {
					for($d=0;$d>$diff;$d--){
						$content.="</li></ul>";	// si le nouveau niveau est inférieur au précédent, décalages vers la gauche
					}
					$content.="<li>".$titre;		// nouvel élément
				}
			$niveau=$niv;	
			}
		}
		for($d=0;$d<($niv-1);$d++){
			$content.="</li></ul>";
		}
		if($reqR->rowCount()>0)	$content.="<li><a href='#references'>Références</a></li>";
		if($reqN->rowCount()>0)	$content.="<li><a href='#notes'>Notes</a></li>";
		$content.="</ul></div>";
	}

//						PARAGRAPHES
	//$increment=40;
	$increment=0;       // pas d'intentations ; structure conservée pour évolution
	$before=8;
	foreach($paragraphes as $lC){
		$rang=$lC["n1"];
		for($n=2;$n<7;$n++) {
			if($lC["n".$n]==0) break;		// reconstitution du numéro
			else $rang.="-".$lC["n".$n];
		}	
		$content.="<div style='margin-left:".(($n-2)*$increment)."px;margin-top:".((6-$n)*$before)."px'>
            <div class='titre".($n-1)."' id='$rang'>".$rang.". ".preg_replace("#^<p>#", "", $lC["titre"])."
		</div>";
		if($lC["content"]=="") $content.="";
		//else $content.=preg_replace("#^<p>#", "", $lC["content"]);
		else $content.=$lC["content"];
		$content.="</div>";
	}

//						REFERENCES		conservé pour les quelques articles convertis au début.
	if($reqR->rowCount()>0) {
		$content.="<div class='titre1' id='references'>Références</div>";
		$lib=preg_replace("#^<p>#", "", $lR["lib"]);
		$lib=preg_replace("#</p>$#", "", $lib);		//	suppression des marques de paragraphes en début et fin de chaîne		
		foreach($references as $lR){
			$content.="<div class='reference' id='ref".$lR["num"]."'> ".$lR["num"].". ".$lib."</div>";
		}
	}
	
//						NOTES (avec lien retour SUPPRIMÉ)
	if($reqN->rowCount()>0) {
		$content.="<br><div class='titre1' id='notes'>Notes</div>";
		foreach($notes as $lN){
			$content.="<div class='reference' id='note".$lN["num"]."'> ".$lN["num"].". ";
			//if($lN["used"]==1) $content.="<a href='#rev-note".$lN["num"]."'>&uarr;</a> ";		
			$content.=$lN["lib"]."</div>";
		}
	}
	// Ouverture d'overlays pour message ou rapport
	$content.="<b><br><i>Cet article est publié par LISA sous la seule responsabilité de son auteur.</i></b><br>";
	if(!isset($pdf)){
		if (isset($_SESSION["_id"])){		// rapport de message réservé aux sessions ouvertes - modifié par javascript de connexion
			$content.="<div id='ligne_comment1' class='d-block'><a href='#' data-bs-toggle='modal' data-bs-target='#commentaire_article' data-article='$article' data-email='".$_SESSION['email']."' 
			data-chercheur='".$_SESSION["pseudo"]."'>Envoyer un commentaire concernant cet article à LISA, ou rapporter son contenu à un modérateur.</a></div>";
			$content.="<div id='ligne_comment0' class='d-none'>Pour envoyer un message ou un rapport concernant cet article, veuillez vous connecter.</div>";
		}
		else {
			$content.="<div id='ligne_comment1' class='d-none'><a href='#' data-bs-toggle='modal' data-bs-target='#commentaire_article' data-article='$article' data-email='".$_SESSION['email']."' 
			data-chercheur='".$_SESSION["pseudo"]."'>Envoyer un commentaire concernant cet article à LISA, ou rapporter son contenu à un modérateur.</a></div>";
			$content.="<div id='ligne_comment0' class='d-block'>Pour envoyer un message ou un rapport concernant cet article, veuillez vous connecter.</div>";
		}
	}
}

$content.="</div></div>
<div class='modal fade modal_bg' tabindex='-2' id='commentaire_article'>
	<div class='modal-dialog'>
	<div class='modal-content bg-primary'>
		<div class='modal-header'>
			<h4 class='modal-title'>Envoyer un commentaire concernant cet article</h5>
			<button type='button' class='btn-close' id='btn_closeComment' data-bs-dismiss='modal' aria-label='Close'></button>
		</div>
		<div class='modal-body'>
			<div class='row'>
                <div class='col'>
				Utiliser ce formulaire pour poser une question, apporter des éléments concernant cet article, ou rapporter son contenu à un modérateur (en précisant la 
				raison).
                </div>
			</div>
            <div class='row'>
                <div class='col'>
                    <textarea id='modal_message' article='$article' email='".$_SESSION['email']."' chercheur='".$_SESSION['pseudo']."' 
					placeholder='Saisir ici le message' rows='5'></textarea>
                </div>
			</div>
		</div>
		<div class='modal-footer'>
			<button type='button' class='btn btn-secondary' onClick='requeteComment()'>Go</button>
		</div>
	</div>
	</div>
</div>";
?>
<div class="modal fade modal_bg" tabindex="-2" id="image_modale">
	<div id="dialog" class="modal-dialog modal-xl h-100 mt-0">
	<div class="modal-content text-center">
		<div class="modal-header border-0 p-0 bg-secondary">
			<div class="col text-center">
			<!-- <span class='fs-4' id="modal-title">Titre</span> -->
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

<script type="text/javascript">
function requeteComment(){
	var fd = new FormData();	//L'objet FormData permet de créer un ensemble de paires clef-valeur pour un envoi via XMLHttpRequest. 
											//Cet objet est destiné avant tout à l'envoi de données de formulaire
 	var article=$('#modal_message').attr('article');
	var email=$('#modal_message').attr('email');
	var chercheur=$('#modal_message').attr('chercheur');
	var contact=$('#modal_message').val();
	fd.append('article',article);
	fd.append('email',email);
	fd.append('chercheur',chercheur);
	fd.append('contact',contact);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:'ajax_article_comment.php',		// La ressource ciblée
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header 
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){		// callback function à exécuter when Ajax request succeeds ; response est la donnée retournée par la page url
			$("#btn_closeComment").get(0).click();
			if(response != "") {
			alert(response.result);
			if(response.res=="1") $('#ligne_comment').html("<b>>> Vous venez d'envoyer un message concernant le contenu de cet article.</b>");
			}
				else alert("Dysfonctionnement interne.");
		}		// fin de success
	});		// fin de $.ajax
}

var Modal_Image = document.getElementById('image_modale')
Modal_Image.addEventListener('show.bs.modal', function (event) {
	// Button that triggered the modal
	var target = event.relatedTarget
	// Extract info from data-bs-* attributes
	var imageLink = target.getAttribute('data-bs-link')
	var imageSrc = target.getAttribute('data-bs-image')
	var width = target.getAttribute('data-bs-width')

	// Update the modal's content.
	var modalTitle = Modal_Image.querySelector('#modal-title')
	var imageDiv = Modal_Image.querySelector('#modal-image')
	var modalDialog = Modal_Image.querySelector('#dialog')

	//largeur de la fenêtre modale
	if(width==null){
		width="120vh";
	}
	modalDialog.setAttribute('style', 'max-width:'+ width);
	
	//modalTitle.innerHTML = imageTitle
	if(imageLink==null){
		imageDiv.innerHTML = "<img class='mw-100 mh-100' src='" + imageSrc + "'>"
	}
	else {
		imageDiv.innerHTML = "<a href='" + imageLink + "' target='_blank' title='Cliquer pour accéder à la source'><img class='mw-100 mh-100' src='" + imageSrc + "'></a>"
	}
})
</script>