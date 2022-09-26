
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

$sql="SELECT num, titre_fr, resume, page, note, ref, arch_link, epoque, auteurs, theme,ordre, image, insert_img, ref_image FROM pages_supp WHERE num=".$article;
try {$id_art=$bdd->query($sql);}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l_art=$id_art->fetch(PDO::FETCH_ASSOC);

$sql="SELECT id FROM `article-indexes` WHERE new_old='old' AND article=$article";
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

$content="<div class='row justify-content-center'>
<div class='col-10 pt-4 pb-4'>"; // row contenant et colonne principale
$content.="<div class='titre_article'>";
if($_SESSION['statut']==10){
	$content.="<a href='javascript:indexation($article,\"old\")' title=\"$txt l'indexation de l'article\"><i class='bi bi-clipboard$icon fs-1'></i></a> &nbsp;";
}
/* if(($lH["nb"]>0 OR $_SESSION['lecteur']>0) AND !isset($pdf)){
	$content.="<a href='article_pdf.php?article=$article' target='_blank' title=\"Voir l'article en mode compatible PDF\"><i onclick='return pdf()' class='bi bi-file-earmark-pdf fs-1'></i></a> &nbsp;";
} */
$content.=$l_art["titre_fr"]."</div>";

if(!empty($l_art["image"]) AND $l_art["insert_img"]!=0){
	$content.="<div class='row pb-3'><div class='col flex-grow-0 small fst-italic legende'><img src='"."../docs/".$l_art["image"]."'>"; // image
	if(!empty($l_art["ref_image"]))$content.="<br>".preg_replace("#^<p>#", "", $l_art["ref_image"]);
	$content.="</div></div>";
}

if(empty($l_art["auteurs"]))$auteurs="LISA";
else $auteurs=$l_art["auteurs"];
$content.="<div class='pb-3 fst-italic'>Par ".nl2br($auteurs)."</div>";

if(!empty($l_art["ref"])){
	$content.="<div class='pb-3'>";
	if($l_art["arch_link"]!="") {
			$content.="Archive : <a href='accueil1-sources_archive-arch-".$l_art["arch_link"].".html' target='_blank'>".$l_art["ref"]."</a>";	//envoi de la variable archive en mode POST
	}
	else $content.=$l_art["ref"];
	$content.="</div>";
}

$content.="<div>";
$content.=stripslashes($l_art["page"]);

if($l_art["note"]!="") $content.="<div class='rectangle_light' style='width:100%'>".$l_art["note"]."</div>";

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
</script>