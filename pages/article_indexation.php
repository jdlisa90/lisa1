<?php
session_start(); // On relaye la session
?>
<html>
<head>
<title>Indexation d'un article</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<!-- <LINK rel='stylesheet' href='../css/style1.css'/> -->
<link rel='stylesheet' href='../css/style-popup.css'/>
<link rel='stylesheet' href='../css/tooltips.css'/>
<link href="../css/style-bootstrap.css" rel="stylesheet">
<link href='../css/bootstrap.css' rel='stylesheet'/>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/UploadAjaxABCI.js"></script>
<script>
function update_index(mode,index){
	var fd = new FormData();
    fd.append('mode',mode);
    fd.append('article',$("#article").val());
    fd.append('new_old',$("#new_old").val());
    //alert($("#termes").val());
    if(mode=="delete"){
        fd.append('index',index);
    }
    else{
        if($("#termes").val()==null){
            alert("Sélectionner un terme !");
            return(false);
        }
        else{
            fd.append('terme',$("#termes").val());
        }
    }
	//alert(content);
	$.ajax({
		url:"ajax_article_index.php",
		type:'post',
		data:fd,
		dataType:'json',
		contentType: false,
		processData: false,
		success:function(response){
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$("#indexes-article").html(response.result);
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

function update_termes(mode,niveau){
	var fd = new FormData();
    fd.append('mode',mode);
    fd.append('niveau',niveau);
    //alert($("#termes").val());
    //alert(niveau);
    if(mode=="delete"){
        if($("#termes").val()==null){
            alert("Sélectionner un terme !");
            return(false);
        }
        else{
            fd.append('terme',$("#termes").val());
        }
    }
    else{
        if(niveau=='1'){
            if($("#terme_lib1").val()==""){
                alert("Saisir une expression !");
                return(false);
            }
            else{
                fd.append('terme_lib',$("#terme_lib1").val());
            }
        }
        else{
            if($("#terme_lib2").val()=="" || $("#terme1").val()==null){
                alert("Saisies incomplètes !");
                return(false);
            }
            else{
                fd.append('terme_lib',$("#terme_lib2").val());
                fd.append('terme1',$("#terme1").val());
            }
        }
    }
	$.ajax({
		url:"ajax_article_terme.php",
		type:'post',
		data:fd,
		dataType:'json',
		contentType: false,
		processData: false,
		success:function(response){
			$("#terme_lib1").val("");
			$("#terme_lib2").val("");
            if(mode=="delete" || niveau=='2') {
                $("#btn_close").get(0).click();   // on ne ferme pas si on vient d'enregistrer un niveau 1
                $("#terme1_inserted").text("");
            }
            else{
                $("#terme1_inserted").text("terme ajouté");
            }
			if(response != ""){
				//alert(response.res);
				if(response.res==1){
					$("#termes").html(response.result);
                    $("#terme1").html(response.result1);
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
</script>
<!-- 								FIN DES SCRIPTS -->
</head>
<body class="bg-primary">
<?php
extract($_GET,EXTR_OVERWRITE);
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
if($new_old=="new") $sql="SELECT titre FROM article WHERE id=:article";
else $sql="SELECT  titre_fr AS titre FROM pages_supp WHERE num=:article";
try {$rek=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('article', $article, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$rek->fetch(PDO::FETCH_ASSOC);
$titre=strip_tags($l["titre"]);
//                                  TERMES
$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2, article_terme_indexation_2.id FROM article_terme_indexation_1,article_terme_indexation_2
    WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 ORDER BY index1, index2";
try {$rek=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('article', $article, PDO::PARAM_INT);
$rek->bindValue('new_old', $new_old, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

echo"<div class='container'><div class='border border-secondary border-3 rounded mt-3 p-3'>";
echo"<div class='row'>
<div class='col-12'>";
echo"<select id='termes'><option value='0' selected='selected' disabled='disabled'>&raquo; Termes enregistrés</option>";
$index1="";
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
    if($index1!=$l["index1"]) {
        echo "<optgroup label=\"".$l["index1"]."\">";
        $index1=$l["index1"];
    }
    echo"<option value='".$l["id"]."'>".$l["index2"]."</option>";
}
echo"</select>&nbsp;&nbsp;<button class='btn btn-outline-danger' onClick='update_termes(\"delete\")' title='Supprimer le terme sélectionné' style='min-width:105px;'>
<i class='bi bi-bookmark-dash-fill fs-2 text-danger' style='vertical-align:sub;'></i> Supprimer</button>
</div></div>
<div class='mt-1'><button class='btn btn-outline-success' data-bs-toggle='modal' data-bs-target='#terme_saisie' title='Ajouter un terme de niveau 1 ou 2' style='min-width:105px;'>
<i class='bi bi-bookmark-plus-fill fs-2 text-success' style='vertical-align:sub;'></i> Ajouter</button>";
echo"</div></div>";

//                                  INDEXES DE L'ARTICLE 
$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2, article_terme_indexation_2.id,`article-indexes`.id FROM article_terme_indexation_1,article_terme_indexation_2,
    `article-indexes` WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 AND article_terme_indexation_2.id=`article-indexes`.terme 
    AND `article-indexes`.article=:article AND `article-indexes`.new_old=:new_old ORDER BY index1, index2";
try {$rek=$bdd->prepare($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('article', $article, PDO::PARAM_INT);
$rek->bindValue('new_old', $new_old, PDO::PARAM_STR);
try{$rek->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
echo"<input type='hidden' id='article' value='$article'><input type='hidden' id='new_old' value='$new_old'>
<div class='border border-secondary border-3 rounded mt-3 p-3'><div class='fw-bold'>Index inscrits pour l'article \"$titre\"";
echo"<br><button class='btn btn-outline-success' onClick='update_index(\"insert\",\"\")' title='Inscrire le terme sélectionné comme index pour cet article' style='min-width:105px;'>
    <i class='bi bi-box-arrow-down fs-2 text-success' style='vertical-align:sub;'></i> Indexer</button></div>
<div class='row mt-2'>
<div id='indexes-article' class='col-12'>";
$index1="";
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
    if($index1!=$l["index1"]) {
        echo "<div><b>&bull; ".$l["index1"]."</b></div>";
        $index1=$l["index1"];
    }
    echo"<div class='ms-4'>  <a href='#' onClick='update_index(\"delete\",".$l["id"].")' title='Supprimer cet index'>
        <i class='bi bi-clipboard-minus fs-1 text-danger' style='vertical-align:sub;'></i></a>&nbsp;".$l["index2"]."</div>";    // effacement du terme en mode id (une occurrence supprimée)
}
echo"</div></div>";

echo"</div></div>";
?>

    <!--                                     FENETRE MODALE DE SAISIE DES TERMES -->
<div class="modal fade modal_bg" tabindex="-1" id="terme_saisie">
	<div class="modal-dialog">
	<div class="modal-content bg-primary">
		<div class="modal-header">
			<h5 class="modal-title"><b>Saisie d'un terme pour l'indexation</b></h5>
			<button type="button" class="btn-close" id='btn_close' data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col col-2 justify-content-end">
					</div>
					<div class="col col-10">
                    <b>Niveau 1</b>
					</div>
				</div>
                <div class="row justify-content-center">
					<div class="col col-2 justify-content-end">
						<p class="text-end">Terme :</p>
					</div>
					<div class="col col-10">
						<input type='text' id='terme_lib1' size='45'>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col col-2">
					</div>
					<div class="col col-10">
                    <button type="button" class="btn btn-secondary" id="btnGo" onClick="update_termes('insert','1')">Go</button>
                    </div>
				</div>
                <div class="row justify-content-center mt-4">
					<div class="col col-2 justify-content-end">
					</div>
					<div class="col col-10">
                    <b>Niveau 2</b>
					</div>
				</div>
                <div class="row justify-content-center">
					<div class="col col-2 justify-content-end">
						<p class="text-end">Terme :</p>
					</div>
					<div class="col col-10">
						<input type='text' id='terme_lib2' size='45'>
					</div>
				</div>
                <div class="row justify-content-center">
					<div class="col col-2 justify-content-end">
						<p class="text-end">Terme de niveau 1</p>
					</div>
					<div class="col col-10">
						<select id='terme1'><option value='0' selected='selected' disabled='disabled'>&raquo; Termes enregistrés</option>
                        <?php
                        $sql="SELECT * FROM article_terme_indexation_1 ORDER BY lib";
                        try {$req=$bdd->query($sql);}
                        catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
                        while($l=$req->fetch(PDO::FETCH_ASSOC)){
                            echo"<option value='".$l["id"]."'>".$l["lib"]."</option>";
                        }
                        ?>
                        </select>&nbsp;<span id='terme1_inserted' class='text-success'></span>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col col-2">
					</div>
					<div class="col col-10">
                        <button type="button" class="btn btn-secondary" id="btnGo" onClick="update_termes('insert','2')">Go</button>
                    </div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</body>
</html>