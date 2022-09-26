<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$sql="select DISTINCT stats_bap.arch AS arch,stats_bap.ampl, parish.LIB_PAR, arch_ssserie.id from stats_bap,parish, arch_ssserie WHERE 
    parish.ID_PAR=stats_bap.arch AND arch_ssserie.id1=parish.ID_PAR ORDER by arch";
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>
<h3>Baptêmes</h3>"; // row contenant et colonne principale
$content.="Effectifs par tranches de 5 années (sauf exceptions), et par communauté de domicile des parents.</h5>
<div class='small'>Remarque : en début de période, le domicile des parents est souvent omis.</div>";
$content.="<div class='row'><div class='col text-end'>";
while($l=$req->fetch(PDO::FETCH_ASSOC)){
	$content.="<br><br><b><a href='accueil1.php?disp=sources_archive?arch=".$l["id"]."'>Registres paroissiaux ";
        $de="de ";
        if(in_array(substr($l["LIB_PAR"],0,1),array("A","E","I","U","O"))) $de="d'";
        $content.=$de.$l["LIB_PAR"]."</a></b>";
	if($l["arch"]===7){
		$content.="<br>(dépouillement partiel)";
	}
    $content.="<br><img src='chart_bapt.php?arch=".$l["arch"]."'>";
}
$content.="</div></div>";
$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant
?>