<?php
$content="<div class='row justify-content-center'>
<div class='col-7 pt-3 pb-4'>
<h3>Registres paroissiaux</h3>"; // row contenant et colonne principale

$rek="select id, ID_PAR, LIB_PAR,SIG_PAR,REM_REG FROM arch_ssserie, parish WHERE arch_ssserie.id1=parish.ID_PAR ORDER BY LIB_PAR";
try {$id_rk=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content.="<br> (Dépouillés : <img src='../img/rect_dep.png'> , non dépouillés : <img src='../img/rect_n_dep.png'>)";

while($p=$id_rk->fetch(PDO::FETCH_ASSOC)){
$content.="<br><br><a name='".$p["SIG_PAR"]."'></a><b><a href='accueil1.php?disp=sources_archive?arch=".$p["id"]."'>".$p["LIB_PAR"]."</a></b>";
	if ($p["REM_REG"]<>"") $content.="<br> (".$p["REM_REG"].")";
	$content.="<br><img src='chart_paroissiaux.php?par=".$p["ID_PAR"]."'>";
}

$content.="</div>";            // fermeture colonne principale

$content.="</div>"; // fermeture row contenant
?>