<?php session_start();
?><!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
<meta charset='utf-8'/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<link href="../css/style-bootstrap.css" rel="stylesheet">
<link href='../css/bootstrap.css' rel='stylesheet'/>
</head>
<body class="bg-primary">
<div class="container">
   	<div class="row border-bottom border-inf">
		<div class="col" style="flex: 0;border-bottom: 1px solid var(--light-bg-color);">
			<img src="../img/logo_gauche.jpg">
		</div>
		<div class="col menubar">
			<div class="row align-items-center">
				<div class="col" style="flex: 1;">
					<div class="row" style="flex-wrap:nowrap;">
						<div class="col dropdown">
							<a id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" href='#'>LISA</a>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</div>
						<div class="col dropdown">
							<a id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Entités</a>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</div>
						<div class="col dropdown">
							<a id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Archives</a>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</div>
						<div class="col dropdown">
							<a id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" href='#'>Productions</a>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col" style="flex: 0;">
					<button type="button" class="btn btn-secondary fw-bold"  data-bs-toggle="modal" data-bs-target="#connexion">Connexion</button>
				</div>
			</div>
			<!-- 2ème ligne -->
			<div class="row align-items-center">

				<div class="col" style="flex: 1;">
					<div class="row" style="flex-wrap:nowrap;">
						<div class="col">
							Recherches : 
						</div>						
						<div class="col">
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal">Individus</a>
						</div>
						<div class="col">
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal">Couples</a>
						</div>
						<div class="col">
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal">par Archive</a>
						</div>
						<div class="col">
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal">par Initiale</a>
						</div>
						<div class="col">
							<a href="#" data-bs-toggle="modal" data-bs-target="#testModal">de Sources</a>
						</div>
					</div>
				</div>
				<div class="col" style="flex: 0;">
					<button type="button" class="btn btn-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#aide_recherche">Aide</button>
				</div>
			</div>
		</div>
   	</div>

	<div class="row" id="content"> <!-- BLOC PRINCIPAL -->
		<div id="maj" class="row pe-0 mt-3 pb-3" > <!-- BLOC des mises à jour -->
			<div class="col-3" id="info">
			<p class="fs-4">INFO :</p>
			Dans son numéro du 16 septembre, l'Est Républicain, rubrique "Culture-Loisirs", publie un article consacré aux mises en ligne de documents par les AD90.
			 LISA a le plaisir d'y voir citée
			 <p>
			 <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-image="../img/cartes/Bel.png" data-bs-title="Belfort">Belfort</a><br>
			 <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-image="../img/cartes/Del.png" data-bs-title="Delle">Delle</a>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
		<div class='modal-image'>
		</div>
    </div>
  </div>
</div>
<script>
	var exampleModal = document.getElementById('exampleModal')
	exampleModal.addEventListener('show.bs.modal', function (event) {
	// Button that triggered the modal
	var target = event.relatedTarget
	// Extract info from data-bs-* attributes
	var imageTitle = target.getAttribute('data-bs-title')
	var imageSrc = target.getAttribute('data-bs-image')

	// Update the modal's content.
	var modalTitle = exampleModal.querySelector('.modal-title')
	var imageDiv = exampleModal.querySelector('.modal-image')

	modalTitle.textContent = imageTitle
	imageDiv.innerHTML = "<img src='" + imageSrc + "'>"
})
</script>
			</div>
			<div class="col-9">
				<div class="row">
					<div id="maj_l" class="col">
					<p class="fs-4">DERNIÈRES MISE À JOUR :</p>
					Mise en ligne de l'ensemble des montres d'armes des années 1580-1600 :
		- la montre d'arme du comté de Belfort de 1604, déjà transcrite par Robert Billerey (AD90 4B 201),
		- celle, restreinte à la ville de Belfort, de 1589 (AMB II 3/3), revue en 1610,
		- celle, de même, de 1605 (AMB II 3/4).
		Cette dernière est suivie, dans la cote II 3/4, d'une enquête diligentée par les autorités autrichiennes en 1633, après la reprise de la ville aux suédois. 
		Nous l'avons synthétisée dans un acte.
					</div>
					<div id="maj_c" class="col-md-auto">
						<img src="../docs/illustrations-entites/illustr4.jpg" id="image_last_maj">
					</div>
					<div id="maj_r" class="col-md-auto pe-0">
						<img src="../docs/illustrations-entites/illustr4.jpg" id="image_last_maj" width="120px">
					</div>
				</div>
			</div>
		</div>

		<div class="row pe-0 mt-3 pb-3 pe-0" id="articles_new"> <!-- BLOC des articles RÉCENTS -->
			<div class="col-6 ps-0 pe-0">
				<p class="fs-3">
				Foussemagne Un château seigneurial
				</p>
				<p>
				Notre attention a été attirée sur un document du tabellioné du comté de Belfort concernant un patrimoine ancien, dont nous avons tenté de suivre l'historique.

Il s'agit de la description et de l'évaluation du château seigneurial de Foussemagne, après le décès de la comtesse douairière, en 1757 (cote AD90 2E1 227)
				</p>
				<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
			</div>
			<div id="art_c" class="col-3 ps-3">
				<div class="row mt-3">
				<img src="../upload/45/notaire.jpg" style="max-width:100%;">
				<p class="fs-4">
				Difficultés de datation
					</p>
				</div>
				<div class="row mt-3 mb-3">
				<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fs-4">
					Foussemagne Un château seigneurial
					</p>
				</div>
			</div>
			<div id="art_r" class="col-3 ps-4 pe-0">
			<div class="row mt-3">
				<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fs-4">
					Foussemagne Un château seigneurial
					</p>
				</div>
				<div class="row mt-3 mb-3">
				<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fs-4">
					Foussemagne Un château seigneurial
					</p>
				</div>
			</div>
		</div>
		<div class="row mt-3 pb-3 ps-0 pe-0 me-0" id="articles_new" > <!-- BLOC des articles ANCIENS -->
			<div class="col-2 pe-0">
			<img src="../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../upload/33/chateau_gallica.png" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
			<div class="col-2 pe-0">
			<img src="../docs/../upload/44/Foussemagne_Chapelle_Sainte-Anne.jpg" style="max-width:100%;">
				<p class="fr-5">
					Foussemagne Un château seigneurial
				</p>	
			</div>
		</div>
	</div>
	<div class="row" id="foot"> <!-- Pied de page -->

	</div>	
</div>

<!-- Modal -->
<div class="modal fade modal_bg" tabindex="-1" id="connexion">
	<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Connexion</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
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
			S'incrire | Adhérer | Mot de passe oublié &nbsp;
			<button type="button" class="btn btn-secondary" id="btnSave">Go</button>
		</div>
	</div>
	</div>
</div>

<div class="modal fade modal_bg" tabindex="-2" id="aide_recherche">
	<div class="modal-dialog modal-dialog-scrollable">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Modal title</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<p>Modal body text goes here.</p>
			<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="btnSave">Save</button>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" tabindex="-2" id="testModal">
	<div class="modal-dialog modal-dialog-centered modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Modal title</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<p>Modal body text goes here.</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="btnSave">Save</button>
		</div>
	</div>
	</div>
</div>
<script>
	const container = document.getElementById("testModal");
	const modal = new bootstrap.Modal(container);
	document.getElementById("btnSave").addEventListener("click", function () {
		modal.hide();
	});
</script>