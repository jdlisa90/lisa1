<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<head>
<link href="../css/style-bootstrap.css" rel="stylesheet">
<link href='../css/bootstrap.css' rel='stylesheet'/>
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
	//alert("Votre session a été fermée. Votre dernière saisie peut ne pas avoir été enregistrée. Après reconnexion, veuillez la vérifier SVP.");
	$('#connexion').modal('show');
});

function valid_login_pass(login,pass){
	if(login=="" || pass==""){
		alert("L\'identifiant ou le mot de passe ne sont pas renseignés.");
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
 						if(response.statut>=1){		// toujours le cas, en principe
                            window.location.href=$('#from').text();
						}
					}
					else{
						alert(response.result);
						if(response.result="Erreur login"){
							window.location.href="accueil1-lisa_maj_articles.html";
						}
					}
				}
				else{
					alert('Nada');
				}
			}		// fin de success
		});		// fin de $.ajax
	}
}
</script>
</head>
<body>
<div id='from' style='display:none;'>
	<?php 
	extract($_GET);
	echo $from;
	?>
</div>
</body>
</html>
                                           <!-- FENÊTRE modale de CONNEXION -->
<div class="modal fade modal_bg bg-secondary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="connexion">
	<div class="modal-dialog modal-sm">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h5 class="modal-title">Connexion</h5>
			<button type="button" class="btn-close" id='btn_closeK' data-bs-dismiss="modal" aria-label="Close"></button>
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
			<button type="button" class="btn btn-secondary" id="btnGo" onClick="connexion()">Go</button>
		</div>
	</div>
	</div>
</div>