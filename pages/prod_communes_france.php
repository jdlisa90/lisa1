<?php
extract($_POST);
$sql="SELECT DISTINCT dep, depart.lib FROM communes_france1,depart WHERE depart.id=communes_france1.dep ORDER BY dep";
try{$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'><div class='mb-4'>
<h3>Recherche d'une commune française, avec caractères de contrôle</h3></div>"; // row contenant et colonne principale
$content.="<div class='row mb-3 d-flex'><div class='col flex-grow-0'>";
$content.="<form method='post' action='accueil1.php?disp=prod_communes_france'>
	<select name=depts[] multiple class='form-select bg-secondary' style='width:200px;height:460px;'><option value='0'";
if(!isset($depts)) $content.=" selected";
else if(in_array("0",$_POST["depts"])) $content.= " selected";
$content.=">tous départements</option>";
while($dept=$req->fetch(PDO::FETCH_ASSOC)) {
	$content.= "<option value='".$dept["dep"]."'";
	if(isset($depts)){
		if(in_array($dept["dep"], $depts)) $content.= " selected";
	}
	$content.=">".$dept["dep"]." ".$dept["lib"]."</option>";
}
$content.="</select></div>";
$content.="<div class='col d-flex align-items-center'>";
$content.="<div><strong><</strong> Ci-contre, on peut sélectionner <strong>plusieurs départements</strong> en utilisant les touches Maj et Ctrl.
<br><br><br>
<strong>Commune</strong> : 
<br>Saisir le nom <b>sans l'article</b> éventuel. 
<br>On pourra utiliser les caractères * (tout groupe de caractères) et ? (1 caractère)<br>
<input type='text' name='commune' style='margin-top:10px;'";
if(isset($commune)) $content.="value=\"$commune\"";
$content.=">
<br><br><input type='submit' value='Envoyer'>
</form></div></div></div>";

if(isset($commune)){
	$commune=strtr($commune,"*","%");
	$commune=strtr($commune,"?","_");
	$sql="SELECT type,insee, article, commune FROM communes_france1 WHERE commune LIKE :commune";
	if(!in_array("0", $_POST["depts"])) {
		$nb_dep=count($_POST["depts"]);
		$sql.=" AND (";
		for($k=0;$k<$nb_dep;$k++){				// preparation de $nb_dep variables à lier
			$sql.="dep=:dep".$k." OR ";
		}
		$sql.="0)";
	}
	$sql.=" ORDER BY insee LIMIT 200";
	try {$req=$bdd->prepare($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$req->bindValue('commune', $commune, PDO::PARAM_STR);
	if(!in_array("0", $_POST["depts"])) {
		$k=0;
		foreach($_POST["depts"] as $value) {
			$req->bindValue('dep'.$k, $value, PDO::PARAM_STR);
			$k++;
		}
	}
	try{$req->execute();}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	$result=$req->rowCount();
	$content.="<div class='mb-2'>";
	if($result==0) $content.= "Aucun résultat.</div>";
	else {									
		$content.="<b>".$result." résultat";
		if($result>1)$content.="s";
		$content.="</b></div><div class='row mb-4'>"; // début de l'écriture de la liste
		while($l=$req->fetch(PDO::FETCH_ASSOC)){
			$content.="<div class='col-4'>";
			if($l["type"]==1){				// anciennes communes (90)
				$is_old=1;
				$content.= "*";
			}
			$content.= $l["article"];
			if($l["article"]!="L'" AND $l["article"]!="") $content.= " ";
			$content.= $l["commune"]." ".$l["insee"]."</div>";
		}
		$content.="</div>";
	}
	if($result==200) $content.= "<div>La recherche est limitée à <b>200 résultats</b> : pour des réponses plus pertinentes, affiner les critères de recherche.</div>";
	if($is_old==1) $content.= "<div>(* ancienne commune)</div>"; // au moins une ancienne commune
	$content.= "<div><i>Données extraites du <a href='https://www.insee.fr/fr/information/3720946#titre-bloc-15' target='_blank'>Code officiel géographique de l'Insee 2019</a></i>.</div>";

}

$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant
?>