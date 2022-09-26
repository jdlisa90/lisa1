<?php session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
?><!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
<meta charset='utf-8'/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LISA</title>
<!-- <link rel="shortcut icon" type="image/x-icon" href="../img/logo_arbre.ico" /> -->
<link rel="shortcut icon" type="image/x-icon" href="../img/lisaicon.ico" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- utilité pour les onglets ?? mais pas pour le loader -->
<script src="../js/UploadAjaxABCI.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script>
function content(content){
	var fd = new FormData();
	fd.append('content',content);
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_content.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					window.history.pushState({pageID: 'about'}, '', 'https://www.lisa90.org/lisa1/work_bootstrap/accueil1.php?disp='+content);
					//alert(response.result);
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax
}
function requeteI(page){
	setLoader();
	if(page=="select"){
		page=$("#select_page").val();
	}
 	var fd = new FormData();
	fd.append('nom',$('#nomI').val());
	fd.append('nom_exact',$('input[type=radio][name=nom_exactI]:checked').attr('value'));	//y ou n
	fd.append('prenom',$('#prenomI').val());
	fd.append('prenom_exact',$('input[type=radio][name=prenom_exactI]:checked').attr('value'));
	fd.append('lieu',$('#lieuI').val());
	fd.append('a0',$('#a0I').val());
	fd.append('a1',$('#a1I').val());
	fd.append('sit',$('#sitI').val());
	fd.append('page',page);	
	fd.append('div',$('#divI').val());
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteI.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeI").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche d'individus");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}
function requeteC(page){
	setLoader();
	if(page=="select"){
		page=$("#select_page").val();
	}
 	var fd = new FormData();
	fd.append('nomA',$('#nomAC').val());
	fd.append('nom_exactA',$('input[type=radio][name=nom_exactAC]:checked').attr('value'));	//y ou n
	fd.append('prenomA',$('#prenomAC').val());
	fd.append('prenom_exactA',$('input[type=radio][name=prenom_exactAC]:checked').attr('value'));
	fd.append('nomB',$('#nomBC').val());
	fd.append('nom_exactB',$('input[type=radio][name=nom_exactBC]:checked').attr('value'));	//y ou n
	fd.append('prenomB',$('#prenomBC').val());
	fd.append('prenom_exactB',$('input[type=radio][name=prenom_exactBC]:checked').attr('value'));
	fd.append('lieu',$('#lieuC').val());
	fd.append('a0',$('#a0C').val());
	fd.append('a1',$('#a1C').val());
	fd.append('page',page);
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteC.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeC").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche de couples");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}
function requeteA(page){
	setLoader();
	if(page=="select"){
		page=$("#select_page").val();
	}
 	var fd = new FormData();
	fd.append('nom',$('#nomA').val());
	fd.append('arch',$('#archA').val());
	fd.append('nom_exact',$('input[type=radio][name=nom_exactA]:checked').attr('value'));	//y ou n
	fd.append('prenom',$('#prenomA').val());
	fd.append('prenom_exact',$('input[type=radio][name=prenom_exactA]:checked').attr('value'));
	fd.append('lieu',$('#lieuA').val());
	fd.append('a0',$('#a0A').val());
	fd.append('a1',$('#a1A').val());
	fd.append('sit',$('#sitA').val());
	fd.append('comp',$('#compA').val());
	fd.append('div',$('#divA').val());
	fd.append('page',page);
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteA.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeA").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche par archive");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}
function requeteP(page){
	setLoader();
	if(page=="select"){
		page=$("#select_page").val();
	}
 	var fd = new FormData();
	fd.append('arch',$('#archP').val());
	fd.append('a0',$('#a0P').val());
	fd.append('a1',$('#a1P').val());
	fd.append('lieu',$('#lieuP').val());
	fd.append('page',page);
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteP.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeP").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.sql);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche de patronymes");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}

function requeteIx(page){
	setLoader();
	if(page=="select"){
		page=$("#select_page").val();
	}
 	var fd = new FormData();
	fd.append('ix',$('#ix').val());
	fd.append('a0',$('#a0Ix').val());
	fd.append('a1',$('#a1Ix').val());
	fd.append('page',page);
	//alert(content);
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteIx.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeIx").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.sql);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche d'index");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}

function requeteS(){
	//alert($('#nomS').val());
	setLoader();
 	var fd = new FormData();
	fd.append('nom',$('#nomS').val());
	fd.append('prenom',$('#prenomS').val());
	fd.append('a0',$('#a0S').val());
	fd.append('a1',$('#a1S').val());
	fd.append('occurrences',$('#occurrences').is(":checked"));	// true ou false
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_requeteS.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeS").get(0).click();
			$(".loader_container").css("display","none");
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$(document).prop('title', "LISA - Recherche de source");
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
}

function setLoader() {
	var elem = document.createElement('div');
	var elem1 = document.createElement('div');
	elem.className = 'loader_container';			// le cadre pour masquer la page
	elem1.className = 'loader';						// l'animation dans le cadre	
	document.body.appendChild(elem);
	elem.appendChild(elem1);
	return true;
};

function deconnexion(){
	var fd = new FormData();
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:'ajax_deconnexion.php',		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$(".perm_mbr").html(" (? * <span class='text-danger info' type='warning'>interdits<span style='display:none;'>L'utilisation des caractères de contrôle est réservée aux membres de LISA</span></span>)"); // tous les éléments de classe perm_mbr 
					$("#colConnexion").html("<button type='button' class='btn btn-outline-menu fs-5 fw-bold' id='menuConnexion' data-bs-toggle='modal' data-bs-target='#connexion'>Connexion</button>");
					if($("#display").val()=='article_nouveau' || $("#display").val()=='article_ancien'){
						$("#ligne_comment0").removeClass("d-none");
						$("#ligne_comment0").addClass("d-block");
						$("#ligne_comment1").addClass("d-none");
						$("#ligne_comment1").removeClass("d-block"); // permutation de visibilité
					}
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax
}

function mot_de_passe(){
	var fd = new FormData();
	fd.append('login',$("#login_mdp").val());
	fd.append('email',$("#email_mdp").val());
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:'ajax_mdp.php',		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeM").get(0).click();
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					alert(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax
}

function valid_login_pass(login,pass){
	if(login=="" || pass==""){
		alert("L\'identifiant ou le mot de passe ne sont pas renseignés.");
		return false;
	}
	return true;
}
function valid_search(form){
/* 	if(login=="" || pass==""){
		alert("L\'identifiant ou le mot de passe ne sont pas renseignés.");
		return false;
	}
	return true; */
	if(form.chaine.value==""){
		alert("Chaîne vide !");
		return false;
	}
	return true;
}

function connexion(){
	var login=$("#login").val();
	var pass=$("#pass").val();
	if(valid_login_pass(login, pass)==true){
		var fd = new FormData();
		fd.append('login',login);
		fd.append('pass',pass);
		$.ajax({		// instancie implicitement un objet XmlHttpRequest
			url:'ajax_connexion.php',		// La ressource ciblée:
			type:'post',
			data:fd,					// l'objet FormData
			dataType:'json',
			contentType: false,	// pour empêcher jQuery d'ajouter un header
			processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
			success:function(response){
				$("#btn_closeK").get(0).click();
				if(response != ""){
					if(response.res==1){
						//alert(response.statut);
						if(response.statut>=1){		// toujours le cas, en principe
							$("#menuConnexion").addClass("text-secondary"); // couleur du texte (light)
							$("#menuConnexion").attr("data-bs-toggle", "dropdown");
							$("#menuConnexion").attr("aria-expanded", "false");
							$("#menuConnexion").removeAttr("data-bs-target");
							if(response.statut>=2){		// membres
								$("#menuConnexion").addClass("member");
								$("#menuConnexion").text(response.login); // laisser la place pour le logo
							}
							else{
								$("#menuConnexion").addClass("not_member");
								$("#menuConnexion").text(response.login);
							}
							
							var menu="<ul id='menuConnexion_drop' class='dropdown-menu dropdown-menu-end' aria-labelledby='menuConnexion'>";
							if(response.statut==10) {
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../admin/menu_admin.php?lang=fr' target='_blank'>Administration</a></li>";
							}
							else{
								if(response.editeur==1) {
									menu=menu+"<li class='text-end'><a class='dropdown-item' href='../admin/menu_admin.php?lang=fr' target='_blank'>Menu intervenant</a></li>";
								}
							}
							if(response.statut==10) {
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='CAvirtuel.php' target='_blank'>CA virtuel</a></li>";
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../work_bootstrap/accueil1.php'>Évolution du site</a></li>";
							}
							if(response.editeur==1) {
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../edition/acte_select.php?lang=fr' target='_blank'>Édition d'actes</a></li>";
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../edition/url_edit_select.php' target='_blank'>Édition des urls</a></li>";
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../edition/glossaire_XVI.php' target='_blank'>Édition du glossaire M. F.</a></li>";
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../edition_article/articles_liste.php' target='_blank'>Rédaction d'articles</a></li>";
							}
							if(response.lecteur==1) {
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../lecture_work/liste_articles.php' target='_blank'>Lecture d'articles</a></li>";
							}
							if(response.statut==10) {
								menu=menu+"<li class='text-end'><a class='dropdown-item' href='../admin/demandes_num.php' target='_blank'>Denandes de numérisations</a></li>";
							}
							menu=menu+"<li class='text-end'><a class='dropdown-item' href='accueil1-session_espace_perso.html'>Espace perso</a></li>";
							menu=menu+"<li class='text-end'><a class='dropdown-item' href='#' onClick='deconnexion()'>Fermer la session</a></li>";
							menu=menu+"<li class='text-end'><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#connexion''>Se reconnecter</a></li></ul>";
							//alert(menu);
							$("#menuConnexion").after(menu);
							if(response.statut>=2 || response.editeur==1) {
								$(".perm_mbr").html(" (? * <span class='text-success'>permis</span>)"); // tous les éléments de classe txmbr (colG1.php)
							}
							if($("#display").val()=='article_nouveau' || $("#display").val()=='article_ancien'){
								$("#ligne_comment1").removeClass("d-none");
								$("#ligne_comment1").addClass("d-block");
								$("#ligne_comment0").addClass("d-none");
								$("#ligne_comment0").removeClass("d-block"); // permutation de visibilité
							}
						}
					}
					else{
						alert(response.result);
					}
				}
				else{
					alert('Nada');
				}
			}		// fin de success
		});		// fin de $.ajax
	}
}

function session_connexion(){		// gestion de la déconnexion accidentelle INUTILISÉ
	var login=$("#login").val();
	var pass=$("#pass").val();
	if(valid_login_pass(login, pass)==true){
		var fd = new FormData();
		fd.append('login',login);
		fd.append('pass',pass);
		$.ajax({		// instancie implicitement un objet XmlHttpRequest
			url:'ajax_session_connexion.php',
			type:'post',
			data:fd,			
			dataType:'json',
			contentType: false,
			processData: false,
			success:function(response){
				$("#btn_closeK").get(0).click();
				if(response != ""){
					if(response.res==1){
						//alert(response.statut);
						if(response.statut>=1){		// toujours le cas, en principe
                            window.location.href=$('#from').text();
						}
					}
					else{
						alert(response.result);
					}
				}
				else{
					alert('Nada');
				}
			}		// fin de success
		});		// fin de $.ajax
	}
}

/* function affichage_acte(elt){		// non utilisé
	$(elt).attr("src",'../img/book_open.gif');
	var fd = new FormData();
	fd.append('arch',$(elt).parent().attr("data-bs-arch"));
	fd.append('numacte',$(elt).parent().attr("data-bs-numacte"));
	fd.append('ordre',$(elt).parent().attr("data-bs-ordre"));
	$.ajax({		// instancie implicitement un objet XmlHttpRequest
		url:"ajax_acte.php",		// La ressource ciblée:
		type:'post',
		data:fd,					// l'objet FormData
		dataType:'json',
		contentType: false,	// pour empêcher jQuery d'ajouter un header
		processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
		success:function(response){
			$("#btn_closeS").get(0).click();
			$(".loader").css("display","none");
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					//alert(response.result);
					$("#content").html(response.result);
				}
				else{
					alert(response.result);
				}
			}
			else{
				alert('Nada');
			}
		}		// fin de success
	});		// fin de $.ajax 
	var content=$("#acte-content");
	content.html(numacte);
} */

function getRandomIntInclusive(min, max) {
	min = Math.ceil(min);
	max = Math.floor(max);
	return Math.floor(Math.random() * (max - min +1)) + min;
}
$(document).ready(function () {
	console.log("document is ready");
	$(document).prop('title', "LISA");
	var titre={
		"article_ancien" : "article",
		"article_nouveau" : "article",
		"article_recherche_texte" : "recherche dans les articles",
		"article_select_theme" : "articles par thème",
		"entites_communes_carte" : "carte des communes",
		"entites_communes_liste" : "liste des communes",
		"entites_commune" : "commune",
		"entites_entite" : "entité",
		"entites_paroisse" : "paroisse",
		"entites_paroisses_carte" : "carte des paroisses",
		"entites_select" : "entités",
		"indexation" : "indexation d'actes",
		"lisa_assoc" : "association",
		"lisa_contact" : "contact",
		"lisa_historique" : "historique",
		"lisa_maj_articles" : "",
		"prod_communes_france" : "recherche d'une commune",
		"prod_glossaire" : "glossaire",
		"prod_prenoms" : "prénoms anciens",
		"prod_stats_bapt" : "statistiques des baptêmes",
		"prod_transcriptions" : "transcriptions",
		"prod_travaux_chercheur" : "travaux par chercheur",
		"prod_travaux_indiv" : "travaux par individus",
		"prod_travaux" : "travaux",
		"recensement" : "recensement",
		"recherche" : "accueil",
		"session_commentaires" : "",
		"session_espace_perso" : "espace personnel",
		"session_travail_edit" : "édition d'un travail",
		"session_travaux" : "travaux personnels",
		"sources_archive" : "archive",
		"sources_dep_partiels" : "dépouillements récents",
		"sources_dep_progression" : "progression des dépouillements",
		"sources_depouillees" : "sources dépouillées",
		"sources_hors_tdb" : "sources en ligne (hors 90)",
		"sources_paroissiaux" : "registres paroissiaux",
		"sources_recensement" : "recensement",
		"sources_select" : "sources"
	};
	if (titre[$("#display").val()]!="") $(document).prop('title', "LISA - " + titre[$("#display").val()]);
	if($("#display").val()=="sources_dep_progression"){
		google.charts.load('current', {'packages':['corechart']});
		var fd = new FormData();
		var tab;
		$.ajax({
			url:'chart_actes.php',
			type:'post',
			data:fd,
			dataType:'json',
			contentType: false,
			processData: false,
			success:function(response){
				if(response != ""){
					var monTableau = Object.keys(response).map(function(cle) {
							return [cle, response[cle]];
					});
					console.log(monTableau);
					var data = google.visualization.arrayToDataTable(monTableau);
					var options = {
						fontSize: 10,
						chartArea:{left:60,top:30,width:'90%',height:'75%'},
						colors: ['#cccc99'],
						backgroundColor: "#e8ecc8",
						legend: {position: 'bottom'},
						lineWidth: 3,
						vAxis: {format: 'decimal'},
						hAxis: {slantedTextAngle: -45}
					};
					var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
					chart.draw(data, options);
				}
				else{
					alert('Nada');
				}
			}		// fin de success
		});		// fin de $.ajax */
	}
	
	$("#form_comm").click(function(){		// à voir
		$("#bloc_form_comm").slideToggle("slow");
    });
    
	$("body").on("mouseover",".info",function(){ 	// affichage des tooltips de la classe .info
		var ref=$(this).attr("ref");
		var cont=$('span:first', this);	// le premier span enfant de this
		var ww=$(window).width();
		var left=Math.round($(this).offset().left); // la largeur du tooltip est 450
		var w=Math.round($(this).width());
		var milieu=left+w/2;
		//var top=$(this).offset().top;
		
		if(milieu<(ww/2)){	// le milieu du tooltip est à gauche du milieu de la fenetre
			cont.css('left','0');	// à la droite du bord gauche (mot du côté gauche)
			//alert("1 " + milieu + "  " + ww);
		}
		else {
			cont.css('right','0');	//à la gauche du bord droit (mot du côté droit)
			//alert("2 " + milieu + "  " + ww);
		}
		$(this).css('cursor','pointer');
		switch ($(this).attr("type")){
			case "glossaire":
				var fd = new FormData();
				fd.append('ref',ref);
				php='ajax_gloss_query.php';
				$.ajax({
					url:php,
					type:'post',
					data:fd,
					dataType:'json',
					contentType: false,
					processData: false,
					success:function(response){	
						if(response != ""){
							if(response.res==1){
								cont.html(response.result);
							}
							else{
								alert(response.result);
							}
						}
						else{
							alert('Nada');
						}
						$(close_but).get(0).click();
					}		// fin de success
				});		// fin de $.ajax*/
				break;
			case "glo_ref":
				var fd = new FormData();
				fd.append('ref',ref);
				php='ajax_glo_ref_query.php';
				cont.css('width','200');
				cont.css('right','-100px');
				$(this).css('cursor','help');
				$.ajax({
					url:php,
					type:'post',
					data:fd,
					dataType:'json',
					contentType: false,
					processData: false,
					success:function(response){
						if(response != ""){
							//alert(response.result);
							if(response.res==1){
								//alert(response.result);
								cont.html(response.result);
							}
						else{
							alert(response.result);
							}
						}
						else{
							alert('Nada');
						}
						$(close_but).get(0).click();
					}		// fin de success
				});		// fin de $.ajax*/*/
				break;
			case "warning":
				cont.css('display','block');
				break;
			default:
		}
	});		// fin de jquery

	$("body").on("mouseout",".info",function(){
		var cont=$('span:first', this);
		switch ($(this).attr("type")){
			case "warning":
				cont.css('display','none');
				break;
			default:
		}
	});
   
   	$("body").on("click",".info",function(){
   		if($(this).attr("type")=="glossaire"){
			window.open("accueil1-prod_glossaire.html#"+$(this).attr("ref"));
		}
	});

	$(".year").keypress(function() {
		//alert(event.which);
		if (event.which > 31 && (event.which < 48 || event.which > 57))	// autorise les codes inférieurs à 31 ou compris entre 48 et 57
			return false;
		return true;
	});

	if($("#display").val()=="recherche"){
		if($("#requete").val()=="archive"){
			$('#archives #nomA').val($('#nom').val());
			$('#archives #prenomA').val($('#prenom').val());
			$('#archives #a0A').val($('#a0').val());
			$('#archives #a1A').val($('#a1').val());
			$('#archives #archA').val($('#arch').val());
			$("#req_archives").get(0).click();
		}
	}

	let nav=[1,5,10,15,20];		// click sur un des onglets de navigation, inactifs au départ pour des raisons d'affichage // NE FONCTIONNE QU'AVEC lisa_maj_articles LAISSSER EN DERNIER !!
	var nav_active=nav[getRandomIntInclusive(0,4)];
	$("#nav_"+nav_active).get(0).click();
});

function valide_compte(){
	valid=true;
	if($("#email").val()==""){
		alert("Email svp!");
		valid=false;
	}
	else if(!$("#email").val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)){
		alert("Email valide svp");
		valid=false;
	}
	if($('#form_compte').attr("name")=='insert' || ($('#form_compte').attr("name")=='update' && $("#passnew").val()!="")){	// changement de mot de passe pour usagers anciens OU pass d'inscription
		if($("#passnew").val().length<8 || $("#passnew").val().length>20){
			alert("Mot de passe à la bonne longueur svp");
			valid=false;
		}
		else if($("#passnew").val()!=$("#pass2").val()){
			alert("Les mots de passe ne sont pas identiques");
			valid=false;
		}
		//alert($("#pass").val() + $("#pass2").val());
	}
	return valid;
}

function delete_compte(){
	return confirm("Attention, cette suppression est définitive.\nLa confirmez-vous ?");
}

function valide_travail(frm){//erreur si une variable contient autre chose que des lettres, 
    //sauf une apostrophe, un tiret ou des espaces. Il faut également que le tiret ou l'apostrophe ne soit pas placés en premier.
    var c=0;
    var titre=frm.elements['titre'].value.trim();
    if(titre!="") c++;
    for (i = 0; i < 9; i++) {
        var exp=new RegExp("^[a-zéèàùûêâôë]{1}[a-zéèàùûêâôë \'-]*[a-zéèàùûêâôë]$","gi");
        var txt=frm.elements['fam'+i].value;
        txt=txt.trim();
        if(txt!=""){
            if (! exp.test(txt)){	
                alert ("Un caractère du nom "+txt+" n'est pas valide !");
                txt=""; 
                return false;
            }
        c++;
        }
    }
    if(c==0){
        alert("Rien n'a été saisi !");
        return false;
    }
    return true;
};

function hgt(){
	var D = document;
	return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    )-30;
}
function affiche_acte(arch,numA,ord)
{
	arch=arch.trim();
	var height=hgt()-10;
//	var newwindow=window.open("../affichage_work/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,width=725,height=screenHeight,scrollbars=yes,resizable=yes,location=no, menubar=no");
	var newwindow=window.open("../affichage_actes/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,height="+height+",width=700,scrollbars=yes");
	var newwindow=window.open("../affichage_work/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,height="+height+",width=700,scrollbars=yes");
	if (window.focus) {newwindow.focus()}
}
function indexation(article,new_old)
{
	var newwindow=window.open("indexation-"+article+"-"+new_old+".html", "Indexation de l'article","top=10,left=10,width=600,height=700,scrollbars=yes,resizable=yes,location=no, menubar=no");
	if (window.focus) {newwindow.focus()}
}

function to_art(elt){		// affichage d'un article de la liste déroulante
	var dest = elt.value.split('/');
	if (dest[1]=='1') {
		$('#to_art').attr('action', 'accueil1-article_nouveau-article-'+dest[0]+'.html');
	}
	else {
		$('#to_art').attr('action', 'accueil1-article_ancien-article-'+dest[0]+'.html');
	}
	$('#to_art').submit();
}

function submit_contact() {
	valid=true;
	//alert("jj");
	if($("#adresse_contact").val()==""){
		$("#adresse_contact").next(".error-message").fadeIn().text("Email svp");
		valid=false;
	}
	else if(!$("#adresse_contact").val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)){
		$("#adresse_contact").next(".error-message").fadeIn().text("Email valide svp");
		valid=false;
	}		
	else {
		$("#adresse_contact").next(".error-message").fadeOut();
	}

	if ($("#message_contact").val().length<5) {
		valid=false;
		$("#message_erreur").fadeIn().text("Veuillez compléter le champ message");
	}
	else {
		$("#message_erreur").fadeOut();
	}
	if(valid==true){
		var fd = new FormData();
		fd.append('auteur',$("#auteur_contact").val());
		fd.append('email',$("#adresse_contact").val());
		fd.append('titre',$("#titre_contact").val());
		fd.append('message',$("#message_contact").val());
		fd.append('captcha',$("#captcha").val());
		//alert($("#message_contact").val()+" "+$("#adresse_contact").val()+" "+$("#captcha").val());
		$.ajax({		// instancie implicitement un objet XmlHttpRequest
			url:'ajax_contact_valid.php',		// La ressource ciblée:
			type:'post',
			data:fd,					// l'objet FormData
			dataType:'json',
			contentType: false,	// pour empêcher jQuery d'ajouter un header
			processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
			success:function(response){		// callback function à exécuter when Ajax request succeeds ; response est la donnée retournée par la page url
				if(response != ""){
						//alert(response.res);
					if(response.res==1){		// l'élément $(this) n'est plus disponible à cet endroit
						//alert(response.res);
						alert(response.result);
					}
					else{
						alert(response.result);
					}
				}
				else{
					alert('Nada');
				}
			}		// fin de success
		});		// fin de $.ajax
	}
	return false;
}

function reload_captcha() {
	$('#captcha_txt').html("<img src='captcha.php' alt='CAPTCHA'>");
}
/* $(window).scroll(function(){
	var scroll = $(window).scrollTop();
	if (scroll > 80 ) {		// lorsque le header est réduit, la valeur de scrolTop aussi, ce qui provoque des clognotement pour des valeurs inférieures à 80
		$('#logo').addClass("d-none");
		$('#logo_s').removeClass("d-none");
	}
	else {
		$('#logo_s').addClass("d-none");
		$('#logo').removeClass("d-none");
	}
}); */
</script>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
 -->
<!-- police kreon -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kreon:wght@300&display=swap" rel="stylesheet">

<link href="../css/style-bootstrap.css" rel="stylesheet">
<link href="../css/bootstrap_article.css" rel="stylesheet">
<link href='../css/bootstrap1.css' rel='stylesheet'/>
</head>

											<!-- -----------------  BODY --------------- -->
<body class="bg-primary">
<?php include("../include/types1.inc");
include("../include/images.inc");?>		<!-- pour le vignettage -->
<main>
<!-- <div class="container pe-0" style="min-height:90vh;"> -->	<!-- pour repousser le #foot vers le bas de la page -->
	<nav id='navbar' class="row bg-primary sticky-top border-bottom border-secondary"> <!-- couleur bg-secondary = light-bg -->
		<div class="col ms-2" style="flex: 0;">
			<a href='accueil1-lisa_maj_articles.html'><img src="../img/logo_gauche.jpg" class='me-4 mb-1 mt-1' title='Accueil - articles'></a>
		
<!-- 			<a href='accueil1-lisa_maj_articles.html'><img id='logo' src="../img/logoC.png" class='me-2 mb-1 mt-1' title='Accueil'></a>
			<a href='accueil1-lisa_maj_articles.html'><img id='logo_s' src="../img/logoC_s.png" class='me-2 mb-1 mt-1 d-none' title='Accueil'></a> -->
		</div>
		<div class="col menubar fw-bold flex-grow-1">
			<div class="row align-items-center border-bottom border-secondary">
				<div class="col-10">
					<div class="row d-flex align-items-center">
						<div class="col-2 dropdown nav-item" style='width:14%;'>
							<a id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" href='#'>LISA</a>
							<ul class="dropdown-menu fade-down" aria-labelledby="dropdownMenuButton1">
								<li><a class="dropdown-item" href="accueil1-lisa_maj_articles.html">Accueil - articles</a></li>
								<li><a class="dropdown-item" href="accueil1-lisa_assoc.html">Association</a></li>
								<li><a class="dropdown-item" href="accueil1-lisa_historique.html">Historique des m. à j.</a></li>
								<li><a class="dropdown-item" href="accueil1-lisa_contact.html">Contact</a></li>
							</ul>
						</div>
						<div class="col-2 dropdown nav-item" style='width:14%;'>
							<a id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Entités</a>
							<ul class="dropdown-menu fade-down" aria-labelledby="dropdownMenuButton2">
								<!-- <li><a class="dropdown-item" href="accueil1-entites_communes_liste.html">Communes</a></li> -->
								<li><a class="dropdown-item" href="accueil1-entites_communes_carte.html">Carte des communes</a></li>
								<li><a class="dropdown-item" href="accueil1-entites_paroisses_carte.html">Carte des paroisses</a></li>
								<li><a class="dropdown-item" href="accueil1-entites_select.html">Entités par catégories</a></li>
							</ul>
						</div>
						<div class="col-2 dropdown nav-item" style='width:14%;'>
							<a id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Archives</a>
							<ul class="dropdown-menu fade-down" aria-labelledby="dropdownMenuButton3">
								<li><a class="dropdown-item" href="accueil1-sources_select.html">Archives par types</a></li>
								<li><a class="dropdown-item" href="accueil1-sources_hors_tdb.html">Images d'archives en ligne</a></li>
								<li><a class="dropdown-item" href="accueil1-sources_depouillees.html">Archives dépouillées</a></li>
								<li><a class="dropdown-item" href="accueil1-sources_dep_progression.html">Progressions des dépouillements</a></li>
								<li><a class="dropdown-item" href="accueil1-sources_dep_partiels.html">Dépouillements en cours</a></li>
								<li><a class="dropdown-item" href="accueil1-sources_paroissiaux.html">Registres paroissiaux</a></li>
							</ul>
						</div>
						<div class="col-2 dropdown nav-item" style='width:14%;'>
							<a id="dropdownMenuButton5" aria-expanded="false" href='accueil1-indexation.html'>Indexation</a>
						</div>
						<div class="col-2 dropdown nav-item" style='width:14%;'>
							<a id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Productions</a>
							<ul class="dropdown-menu fade-down" aria-labelledby="dropdownMenuButton4">
								<li><a class="dropdown-item" href="accueil1-prod_glossaire.html">Glossaire du moyen français</a></li>
								<li><a class="dropdown-item" href="accueil1-prod_prenoms.html">Prénoms anciens</a></li>
								<li><a class="dropdown-item" href="accueil1-prod_transcriptions.html">Transcriptions d'actes anciens</a></li>
								<li><a class="dropdown-item" href="accueil1-prod_stats_bapt.html">Statistiques baptêmes</a></li>
								<li><a class="dropdown-item" href="accueil1-prod_travaux.html">Travaux indexés par les usagers</a></li>
								<li><a class="dropdown-item" href="accueil1-prod_communes_france.html">Recherche d'une commune en France</a></li>
							</ul>
						</div>
						<div class="col-2"></div>
						<div class="col-2 text-end">
						<!-- <img src="../img/ukraine.png" style="height:40px;vertical-align:middle;" class='me-4 mb-1 mt-1' title='Solidarité avec le peuple ukrainien'> -->
						</div>
					</div>
				</div>
			<div class='col-2 text-end' id='colConnexion'>
<?php
$disp="lisa_maj_articles";
extract($_GET,EXTR_OVERWRITE);
if($_SESSION['statut']>0) {
	if ($_SESSION['statut']>1) $classe="member";
	else $classe="not_member";
	echo"<button type='button' class='btn btn-outline-menu fs-5 fw-bold text-secondary $classe' id='menuConnexion' data-bs-toggle='dropdown' 
	aria-expanded='false'>".$_SESSION["pseudo"]."</button>";
	// menu_dropdown (qui sera ajouté ou retiré par les opérations de connexion-déconnexion)
	echo"<ul id='menuConnexion_drop' class='dropdown-menu dropdown-menu-end' aria-labelledby='menuConnexion'>";
	if($_SESSION['statut']==10) echo"<li class='text-end'><a class='dropdown-item' href='../admin/menu_admin.php?lang=fr' target='_blank'>Administration</a></li>";
	elseif($_SESSION['editeur']==1) echo"<li class='text-end'><a class='dropdown-item' href='../admin/menu_admin.php?lang=fr' target='_blank'>Menu intervenant</a></li>";
 	if($_SESSION['statut']==10) echo"<li class='text-end'><a class='dropdown-item' href='CAvirtuel.php' target='_blank'>CA virtuel</a></li>
							 <li class='text-end'><a class='dropdown-item' href='../work_bootstrap/accueil1.php'>Évolution du site</a></li>";
	if($_SESSION['editeur']==1) echo"<li class='text-end'><a class='dropdown-item' href='../edition/acte_select.php?lang=fr' target='_blank'>Édition d'actes</a></li>
				<li class='text-end'><a class='dropdown-item' href='../edition/url_edit_select.php' target='_blank'>Édition des urls</a></li>
				<li class='text-end'><a class='dropdown-item' href='../edition/glossaire_XVI.php' target='_blank'>Édition du glossaire M. F.</a></li>
				<li class='text-end'><a class='dropdown-item' href='../edition_article/articles_liste.php' target='_blank'>Rédaction d'articles</a></li>";
	if($_SESSION['lecteur']==1) echo"<li class='text-end'><a class='dropdown-item' href='../lecture_work/liste_articles.php' target='_blank'>Lecture d'articles</a></li>";
	if($_SESSION['statut']==10) echo"<li class='text-end'><a class='dropdown-item' href='../admin/demandes_num.php' target='_blank'>Denandes de numérisations</a></li>";
	echo"<li class='text-end'><a class='dropdown-item' href='accueil1-session_espace_perso.html'>Espace perso</a></li>
	 	<li class='text-end'><a class='dropdown-item' href='#' onClick='deconnexion()'>Fermer la session</a></li>
		<li class='text-end'><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#connexion'>Se reconnecter</a></li>
 	</ul>";
}
else echo"<button type='button' class='btn btn-outline-menu fs-5 fw-bold' id='menuConnexion' data-bs-toggle='modal' data-bs-target='#connexion'>Connexion</button>";
?>				
</div>
			</div>
			<!-- 2ème ligne -->
			<div class="row align-items-center">

				<div class="col-10">
					<div class="row" style="flex-wrap:nowrap;">
						<div class="col-2" style='width:14%;'>
							Recherches&nbsp;: 
						</div>						
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#individus" id="req_individus">Individus</a>
						</div>
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#couples" id="req_couples">Couples</a>
						</div>
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#archives" id="req_archives">par Archive</a>
						</div>
<!-- 						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal" id="req_initiale">par Initiale</a>
						</div> -->
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#sources" id="req_sources">Sources</a>
						</div>
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#patros" id="req_patros">Patronymes</a>
						</div>
						<div class="col-2" style='width:14%;'>
							<a href="#" data-bs-toggle="modal" data-bs-target="#indexes" id="req_indexes">Index</a>
						</div>
					</div>
				</div>
				<div class="col-2 text-end">
					<button type="button" class="btn btn-outline-menu fs-5 fw-bold" data-bs-toggle="modal" data-bs-target="#aide_recherche">Aide</button>
				</div>
			</div>
		</div>
   	</nav>
	<!-- <div style='height:88px;'></div> -->		<!-- espace vertical réservé au bloc fixe de navigation -->
	<aside></aside> <!-- colonne gauche vide -->
	<div class="col" id="content" style="min-height:83vh;">

									<!-- ******************* BLOC des CONTENUS ******************* -->
<?php
echo"<input type='hidden' id='display' value='$disp'>";	// stockage d'une variable pour utilisation dans le javascript de connexion
if($disp=="entites_communes_carte") include("index.html");
else{
	include($disp.".php");
	echo $content;
}
?>

	</div> 										<!--	FIN DU BLOC CONTENT -->
<!-- </div> 	 -->									<!-- FIN DU CONTAINER (navigation et content) -->
<aside></aside> <!-- colonne droite vide -->
<footer class="pt-2 border-top border-secondary" id='foot'> <!-- Pied de page -->
	<p class="text-center">
<?php
echo"<a href='https://fr.geneawiki.com/index.php/Annuaire_des_relev%C3%A9s_collaboratifs_libres_et_gratuits' target='_blank'>
<img src='../img/400px-Logo_libres&gratuits.png' style='vertical-align:middle;border:0px;height:40px' class='btn-inflate'></a>&nbsp;&nbsp;
Partenaires :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
try {$rek=$bdd->query("SELECT id, img, lien FROM partner ORDER BY ord");}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
	echo"<a href='".$l["lien"]."' target='_blank'><img src='../img/".$l["img"]."' class='btn-inflate'
            style='vertical-align:middle;border:0px;height:40px'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
echo"<br><a id='copy' href='#copy'>&copy; Lisa90 2001-".date("Y")."</a>
    <div id='bloc_copy' title='Copyright LISA' style='text-align: justify;background: #e8ecc8;display:none;'>
    &copy; LISA90 2001-".date("Y")."<p>Toute publication, reproduction, ou rediffusion de tout ou partie du contenu 
      du site Internet Lisa90.org est interdite, que ce soit sous forme de rédactionnel, 
      de photos ou de dessins, sans l'autorisation expresse des auteurs.";
?>
	</p></div>	
</footer>
</main>
</body>
<!-- 											FIN DE LA PAGE -->

<!-- 															MODALS 					-->

										<!-- FENÊTRE MODALE D'AFFICHAGE DES ACTES -->
<div class="modal fade modal_bg" tabindex="-2" id="acte_modal">
	<div class="modal-dialog modal-lg h-100 mt-0">
	<div class="modal-content text-center">
		<div class="modal-header border-0 p-0 bg-secondary">
			<div class="col text-center">
			<span class='fs-4' id="modal-title">Titre</span>
			</div>
		</div>
			<div id='acte-content'>
		</div>
		<div class="modal-footer border-0 p-0 bg-secondary">
		<button type="button" class="btn-close" id='btn_close' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
	</div>
	</div>
</div>
									<!-- CONNEXION -->
<div class="modal fade modal_bg" tabindex="-1" id="connexion">
	<div class="modal-dialog modal-sm">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h5 class="modal-title">Connexion</h5>
			<button type="button" class="btn-close" id='btn_closeK' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col col-6 justify-content-end">
						<p class="text-end">Identifiant :</p>
					</div>
					<div class="col col-6">
						<input type='text' id='login' size='12'>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col col-6">
						<p class="text-end">Mot de passe :</p>
					</div>
					<div class="col col-6">
						<input type='password' id='pass' size='12'>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="accueil1-session_compte-new-new.html">S'incrire</a> | <a href="#" data-bs-toggle='modal' data-bs-dismiss="modal" data-bs-target='#mdp'>Mot de passe oublié</a> &nbsp;&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn btn-secondary" id="btnGo" onClick="connexion()">Go</button>
		</div></form>
	</div>
	</div>
</div>

<div class="modal fade modal_bg" tabindex="-2" id="aide_recherche">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Formulaires de recherche</h4>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="crit_l">
Dans les formulaires, pour plusieurs critères, il est possible d'opter, soit pour une recherche 
des occurrences <strong>contenant la chaîne saisie</strong> ("est dans"), soit spécifiquement <strong>égale</strong> à la chaîne saisie ("exact"). 
Le premier mode est sélectionné par défaut.
<br>Ce mode "<b>est dans</b>" vise essentiellement, pour les noms et prénoms, à sélectionner les résultats où la forme recherchée a été saisi comme variante ; 
par exemple : "COURVOISIER / CREVOISIER".
<br>Pour les prénoms, il aura aussi comme avantage de retourner les prénoms composés, comme "Marie Catherine", pour la saisie de "Catherine".
<br>Pour <u>les membres de LISA</u>, notons qu'aucun des modes ne limite l'utilisation des caractères de contrôle (* et ?).
</div>
<br>

<p><b>FORMULAIRE "Recherche par archives"</b></p>
<div class="crit_l">
Champs "<strong>Lieu </strong>contient", "<strong>Compl. ident.</strong> contient", "<strong>Divers</strong> contient" :
<br>
Ces 3 champs de saisie permettent d'affiner les recherches.
Ils portent sur des champs de données qui ne sont pas toujours renseignés (voir jamais pour les
relevés du type "indexation"), les utiliser peut donc restreindre fortement les résultats.<br>
- "Lieu contient" : s&eacute;lection des individus dont la r&eacute;sidence ou l'origine (lue ou interpr&eacute;t&eacute;e &agrave; la saisie) contient la 
cha&icirc;ne inscrite.<br>
- "Compl(ément) (d')ident(ité) contient" : les termes saisis sont recherchées dans les champs de données "civilité" et "surnom".<br>
- "Divers contient" : les termes saisis sont recherchés dans un champ qui peut contenir diverses informations concernant un individu, comme sa profession.<br>
</div>
<br>

<p><b>FORMULAIRE "Recherche de sources"</b></p>
<div class="crit_l">Ce formulaire permet de lister les sources indexant au moins un individu répondant aux champs renseignés.
<br>Pour exploiter les résutats, chaque ligne fournit un lien lançant la recherche des individus pour l'archive correspondante, dans une nouvelle fenêtre/onglet.
</div>
<br>

<p><b>CHAMPS (tous formulaires)</b></p>
<div class="crit_l">Les champs <strong>vides</strong> ne sont pas pris en compte dans la requête, à l'exception du champ "<strong>nom</strong>".
<br>
Si celui-ci est laissé vide, la requ&egrave;te retournera les enregistrements <strong>o&ugrave; le nom n'est pas mentionn&eacute;</strong>.
</div>
<br>

<p><b>CARACTERES DE CONTROLE (réservé aux membres de l'association LISA)</b></p>
<div class="crit_l">L'utilisation des caract&egrave;res ? (un caract&egrave;re quelconque) ou * (groupe quelconque de caract&egrave;res) dans un
des champs (nom, pr&eacute;nom, etc...) permet d'élargir la recherche et de tenir compte de la variation des noms de personnes.<br>
Par exemple, le patronyme ROSS&Eacute; pouvant s'écrire aussi ROSSEL, ROSSELZ, ROSSET ou encore ROSSELZ, le texte "ross*" donnera de bons 
résultats.
</div>
		</div>
	</div>
	</div>
</div>
  																		<!-- FENÊTRES MODALES DE RECHERCHE -->
<?php
if ($_SESSION['statut']>1 OR $_SESSION['editeur']==1){
		$txt_mbr= " (? * <span class='text-success'>permis</span>)";
}
else $txt_mbr=" (? * <span class='text-danger info' type='warning'>interdits<span style='display:none;'>L'utilisation des caractères de contrôle est réservée aux membres de LISA</span></span>)";

$situ=array("Baptisé"=>"Enfant (bap./naiss.)","Epoux"=>"Epoux (mar.)","Epouse"=>"Epouse (mar.)","Défunt"=>"Défunt (sep./déc.)","1"=>"Indiv. central (ts. actes)",
	"Père"=>"Père (EC)","Mère"=>"Mère (EC)","2"=>"Indiv. central + parents","7"=>"Tous individus, hors état-civil","10"=>"Tous individus");
?>

<div class="modal fade modal_bg" tabindex="-2" id="individus">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Recherche d'individus</h5>
			<button type="button" class="btn-close" id='btn_closeI' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
                <div class="col-3">
                    Nom
                </div>
                <div class="col-2 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-4">
				<input type='text' id='nomI' maxlength='20' size='20' value='<?php $nom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans <input type='radio' name='nom_exactI' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='nom_exactI' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-3">
                    Prénom
                </div>
                <div class="col-2 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-4">
				<input type='text' name='prenom2' id='prenomI' maxlength='20' size='20' value='<?php $prenom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans  <input type='radio' name='prenom_exactI' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='prenom_exactI' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-5">
				Commune (de l'acte ou de la personne) contient 
                </div>
                <div class="col-4">
				<input type='text' id='lieuI' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
            <div class="row">
                <div class="col-5">
				Année entre
                </div>
                <div class="col-4">
                	<input type='text' id='a0I' maxlength='4' size='8' class='align_middle year' value='<?php $a0 ?>'> et 
					<input type='text' id='a1I' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
            <div class="row">
                <div class="col-5">
				Situation dans l'acte
                </div>
                <div class="col-4">
					<select id='sitI' class='align_middle'><?php
					foreach ($situ as $key => $value) {
						echo"<option value='".$key."' ";
							if($key=="1") echo "selected";
							echo">".$value."</option>";
					}?></select>
                </div>
                <div class="col-3">
                </div>
			</div>
			<div class="row">
                <div class="col-5">
				Divers contient 
                </div>
                <div class="col-4">
				<input type='text' id='divI' maxlength='20' size='20' value='<?php $div ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" onClick='requeteI(0)'>Go</button>
		</div></form>
	</div>
	</div>
</div>
<!-- 										COUPLES -->
<div class="modal fade modal_bg" tabindex="-2" id="couples">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Recherche de couples</h5>
			<button type="button" class="btn-close" id='btn_closeC' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
                <div class="col-3">
                    Nom A
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-3">
				<input type='text' id='nomAC' maxlength='20' size='20' value='<?php $nom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans  <input type='radio' name='nom_exactAC' value='n' checked></label>
				&nbsp;&nbsp;<label>exact <input type='radio' name='nom_exactAC' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-3">
                    Prénom A
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-3">
				<input type='text' name='prenom2' id='prenomAC' maxlength='20' size='20' value='<?php $prenom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans  <input type='radio' name='prenom_exactAC' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='prenom_exactAC' value='y'></label>
                </div>
			</div>
			<div class="row">
                <div class="col-3">
                    Nom B
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-3">
				<input type='text' id='nomBC' maxlength='20' size='20' value='<?php $nom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans  <input type='radio' name='nom_exactBC' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='nom_exactBC' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-3">
                    Prénom B
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-3">
				<input type='text' id='prenomBC' maxlength='20' size='20' value='<?php $prenom2 ?>'>
                </div>
                <div class="col-3">
				<label>est dans  <input type='radio' name='prenom_exactBC' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='prenom_exactBC' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-6">
				Commune (de l'acte ou de la personne) contient 
                </div>
                <div class="col-3">
				<input type='text' id='lieuC' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
            <div class="row">
                <div class="col-6">
				Année entre
                </div>
                <div class="col-3">
                	<input type='text' id='a0C' maxlength='4' size='8' class='align_middle year' value=''> et 
					<input type='text' id='a1C' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" onClick='requeteC(0)'>Go</button>
		</div></form>
	</div>
	</div>
</div>

					<!-- PAR ARCHIVES -->
<?php 
//constitution de la liste des archives dépouillées
try {$id_rk1=$bdd->query("select SQL_SMALL_RESULT DISTINCT individus.arch FROM individus");}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
// sélection de toutes les arch (id1) pour lesquelles existe un dépouillement
$nb_rep1 = $id_rk1->rowCount();
$typ=0;

for($e=0;$e<$nb_rep1;$e++){
	$l1=$id_rk1->fetch(PDO::FETCH_ASSOC);
	if($l1["arch"]<>'' and $l1["arch"]<>0){
		$rek="select lib,lib_abrg, depll,type_arch, an_dep, linked_ad, cot_dep FROM arch_ssserie WHERE id1=".$l1["arch"];
		try {$id_rk2=$bdd->query($rek);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
		$l2=$id_rk2->fetch(PDO::FETCH_ASSOC);
		// recherche du libellé, du type et autres éléments de chacune des arch
		$type_a_d[]=$l2["type_arch"];
		if($l2["lib_abrg"]<>"") $lib=$l2["lib_abrg"];
		else $lib=html_entity_decode($l2["lib"]);
		$part="";

		if($l2["an_dep"]<>"") $part=" (".$l2["an_dep"].")";
		if($l2["linked_ad"]>0) $part.=" [P]";
		// répartition des informations sur plusieurs tableaux (un pour chaque type d'arch)
		${"arch".$l2["type_arch"]}[$e]=array('id'=>$l1["arch"],'lib'=>$lib.$part);	//chaque ${"arch".$l2["type_arch"]} est un tableau à 2 dimension,
		                      //  chaque ${"arch".$l2["type_arch"]}[$e]= (id, lib) de l'archive ; et id=individu.arch (id1)
		$id_rk2->closeCursor();
	}
}

$id_rk1->closeCursor();
$type_a_d=array_unique($type_a_d);
asort($type_a_d);
?>
<div class="modal fade modal_bg" tabindex="-2" id="archives">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Recherche d'individus par archives</h5>
			<button type="button" class="btn-close" id='btn_closeA' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
				<div class="col-2">
					Source
				</div>
				<div class="col-2 perm_mbr">
				(obligatoire)
				</div>
				<div class="col-8">
					<select id='archA'><option value='0' selected='selected' disabled='disabled'>&raquo; Sélectionner la source</option>
					<?php 
					foreach($type_a_d as $val){
						if($val<>''){
							echo "<optgroup label=\"".mb_strtoupper($type_arch[$val], 'UTF-8')."\">";
							$lib = array_column(${"arch".$val}, 'lib');
							array_multisort($lib, SORT_ASC, ${"arch".$val});
							foreach(${"arch".$val} as $value){
								echo "<option value='".$value["id"]."'";
								if($value["id"]==$arch) echo "selected";
								echo">".$value["lib"]."</option>";
							}
							if($type_arch[$val]=="EC ancien") echo"<option value='_par'";
								if("_par"==$arch) echo "selected";
								echo">**".$allpar."</option>";
							if($type_arch[$val]=="EC moderne") echo"<option value='_com'";
								if("_com"==$arch) echo "selected";
								echo">**".$allcomm."</option>";
							echo "</optgroup>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<div class="row">
                <div class="col-2">
                    Nom
                </div>
                <div class="col-2 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-4">
				<input type='text' id='nomA' maxlength='20' size='20' value='<?php $nom2 ?>'>
                </div>
                <div class="col-4">
				<label>est dans  <input type='radio' name='nom_exactA' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='nom_exactA' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-2">
                    Prénom
                </div>
                <div class="col-2 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-4">
				<input type='text' id='prenomA' maxlength='20' size='20' value='<?php $prenom2 ?>'>
                </div>
                <div class="col-4">
				<label>est dans  <input type='radio' name='prenom_exactA' value='n' checked></label>
					&nbsp;&nbsp;<label>exact <input type='radio' name='prenom_exactA' value='y'></label>
                </div>
			</div>
            <div class="row">
                <div class="col-4">
				Année entre
                </div>
                <div class="col-5">
                	<input type='text' id='a0A' maxlength='4' size='8' class='align_middle year' value='<?php $a0 ?>'> et 
					<input type='text' id='a1A' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
            <div class="row">
                <div class="col-4">
				Situation dans l'acte
                </div>
                <div class="col-5">
					<select id='sitA' class='align_middle'><?php
					foreach ($situ as $key => $value) {
						echo"<option value='".$key."' ";
							if($key=="1") echo "selected='selected'";
							echo">".$value."</option>";
					}?></select>
                </div>
                <div class="col-3">
                </div>
			</div>
			<div class="row">
                <div class="col-4">
				Lieu contient 
                </div>
                <div class="col-5">
				<input type='text' id='lieuA' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
			<div class="row">
                <div class="col-4">
				Complément d'identité contient
                </div>
                <div class="col-5">
				<input type='text' id='compA' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
			<div class="row">
                <div class="col-4">
				Divers contient 
                </div>
                <div class="col-5">
				<input type='text' id='divA' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" onClick='requeteA(0)'>Go</button>
		</div></form>
	</div>
	</div>
</div>
										<!-- DE SOURCES -->
<div class="modal fade modal_bg" tabindex="-2" id="sources">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Recherche de sources</h5>
			<button type="button" class="btn-close" id='btn_closeS' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
                <div class="col-4">
                    Nom
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-5">
				<input type='text' id='nomS' maxlength='20' size='20' value='<?php $nom2 ?>'>
                </div>
			</div>
            <div class="row">
                <div class="col-4">
                    Prénom
                </div>
                <div class="col-3 perm_mbr">
				<?php echo $txt_mbr; ?>
                </div>
                <div class="col-5">
				<input type='text' id='prenomS' maxlength='20' size='20' value='<?php $prenom2 ?>'>
                </div>
			</div>
            <div class="row">
                <div class="col-7">
				Année entre
                </div>
                <div class="col-5">
                	<input type='text' id='a0S' maxlength='4' size='8' class='align_middle year' value='<?php $a0 ?>'> et 
					<input type='text' id='a1S' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
				</div>
			</div>
            <div class="row">
                <div class="col-7">
				Avec nombre d'occurrences (plus lent) &nbsp; <input type="checkbox" id="occurrences">
                </div>
                <div class="col-5">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" onClick='requeteS()'>Go</button>
		</div></form>
	</div>
	</div>
</div>
<div class='modal fade modal_bg' tabindex='-2' id='mdp'>	
	<div class='modal-dialog'>
	<div class='modal-content bg-primary'>
		<div class='modal-header'>
			<h4 class='modal-title'>Mot de passe oublié</h5>
			<button type='button' class='btn-close' id='btn_closeM' data-bs-dismiss='modal' aria-label='Close'></button>
		</div>
		<div class='modal-body'><form onsubmit="return false">
			<div class='row'>
                <div class='col-6'>
                Identifiant
                </div>
                <div class='col-6'>
				<input type='text' id='login_mdp' maxlength='20' value=''>
                </div>
			</div>
			<div class='row'>
                <div class='col-6'>
                E-MAIL
                </div>
                <div class='col-6'>
				<input type='text' id='email_mdp' size='20' required>
                </div>
			</div>
		</div>
		<div class='modal-footer'>
			<button type='button' class='btn btn-secondary' onClick='mot_de_passe()'>Go</button>
		</div>
	</div>
	</div>
</div>

<?php 	//						**** DES PATRONYMES ***
//constitution de la liste des archives dépouillées
try {$rk1=$bdd->query("select SQL_SMALL_RESULT DISTINCT individus.arch FROM individus");}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
// sélection de toutes les arch (id1) pour lesquelles existe un dépouillement
$nb_rep1 = $rk1->rowCount();
$typ=0;
for($e=0;$e<$nb_rep1;$e++){
	$l1=$rk1->fetch(PDO::FETCH_ASSOC);
	if($l1["arch"]<>'' and $l1["arch"]<>0){
		$rek="select lib,lib_abrg, depll,type_arch, an_dep, linked_ad, cot_dep FROM arch_ssserie WHERE id1=".$l1["arch"];
		try {$rk2=$bdd->query($rek);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
		$l2=$rk2->fetch(PDO::FETCH_ASSOC);
		// recherche du libellé, du type et autres éléments de chacune des arch
		$type_a_d[]=$l2["type_arch"];
		if($l2["lib_abrg"]<>"") $lib=$l2["lib_abrg"];
		else $lib=html_entity_decode($l2["lib"]);
		$part="";

		if($l2["an_dep"]<>"") $part=" (".$l2["an_dep"].")";
		if($l2["linked_ad"]>0) $part.=" [P]";
		// répartition des informations sur plusieurs tableaux (un pour chaque type d'arch)
		${"arc".$l2["type_arch"]}[$e]=array('id'=>$l1["arch"],'lib'=>$lib.$part);	//chaque ${"arch".$l2["type_arch"]} est un tableau à 2 dimension,
		                      //  chaque ${"arch".$l2["type_arch"]}[$e]= (id, lib) de l'archive ; et id=individu.arch (id1)
		$rk2->closeCursor();
	}
}
$rk1->closeCursor();
$type_a_d=array_unique($type_a_d);
asort($type_a_d);
?>
<div class="modal fade modal_bg" tabindex="-2" id="patros">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Liste de patronymes par archives</h5>
			<button type="button" class="btn-close" id='btn_closeP' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
				<div class="col-2">
					Source
				</div>
				<div class="col-2 perm_mbr">
				(obligatoire)
				</div>
				<div class="col-8">
					<select id='archP'><option value='0' selected='selected' disabled='disabled'>&raquo; Sélectionner la source</option>
					<?php 
					foreach($type_a_d as $val){
						if($val<>''){
							echo "<optgroup label=\"".mb_strtoupper($type_arch[$val], 'UTF-8')."\">";
							$lib = array_column(${"arc".$val}, 'lib');
							array_multisort($lib, SORT_ASC, ${"arc".$val});
							foreach(${"arc".$val} as $value){
								echo "<option value='".$value["id"]."'";
								if($value["id"]==$arch) echo "selected";
								echo">".$value["lib"]."</option>";
							}
							if($type_arch[$val]=="EC ancien") echo"<option value='_par'";
								if("_par"==$arch) echo "selected";
								echo">**".$allpar."</option>";
							if($type_arch[$val]=="EC moderne") echo"<option value='_com'";
								if("_com"==$arch) echo "selected";
								echo">**".$allcomm."</option>";
							echo "</optgroup>";
						}
					}
					?>
					</select>
				</div>
			</div>
            <div class="row">
                <div class="col-4">
				Année entre
                </div>
                <div class="col-5">
                	<input type='text' id='a0P' maxlength='4' size='8' class='align_middle year' value='<?php $a0 ?>'> et 
					<input type='text' id='a1P' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
			<div class="row">
                <div class="col-4">
				Lieu contient 
                </div>
                <div class="col-5">
				<input type='text' id='lieuP' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" onClick='requeteP(0)'>Go</button>
		</div></form>
	</div>
	</div>
</div>

<?php 	//						**** DES INDEXES ***
//constitution de la liste des archives dépouillées
try {$req=$bdd->query("select * FROM `index_actes` ORDER BY item");}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
?>
<div class="modal fade modal_bg" tabindex="-2" id="indexes">
	<div class="modal-dialog modal-lg">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h4 class="modal-title">Liste de patronymes par archives</h5>
			<button type="button" class="btn-close" id='btn_closeIx' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body"><form onsubmit="return false">
			<div class="row">
				<div class="col-2">
					Index
				</div>
				<div class="col-2 perm_mbr">
				(obligatoire)
				</div>
				<div class="col-8">
					<select id='ix'><option value='0' selected='selected' disabled='disabled'>&raquo; Sélectionner un index</option>
					<?php 
					while($l=$req->fetch(PDO::FETCH_ASSOC)){
						echo"<option value='".$l[id]."'>".$l[item]."</option>";
					}
					?>
					</select>
				</div>
			</div>
            <div class="row">
                <div class="col-4">
				Année entre
                </div>
                <div class="col-5">
                	<input type='text' id='a0Ix' maxlength='4' size='8' class='align_middle year' value='<?php $a0 ?>'> et 
					<input type='text' id='a1Ix' maxlength='4' size='8' class='align_middle year' value='<?php $a1 ?>'>
                </div>
                <div class="col-3">
                </div>
			</div>
<!-- 			<div class="row">
                <div class="col-4">
				Lieu contient 
                </div>
                <div class="col-5">
				<input type='text' id='lieuP' maxlength='20' size='20' value='<?php $lieu ?>'>
                </div>
                <div class="col-3">
                </div>
			</div> -->
		</div>
		<div class="modal-footer">
			Attention : les index ne concernent actuellement qu'un petit corpus d'actes, essentiellement des comptes &nbsp;&nbsp; 
			<button type="submit" class="btn btn-secondary" onClick='requeteIx(0)'>Go</button>
		</div></form>
	</div>
	</div>
</div>
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
		<button type="submit" class="btn-close" id='btn_close' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
	</div>
	</div>
</div>