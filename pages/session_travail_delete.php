<?php
if ($_SESSION['statut']<1){
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
}

$sql="DELETE FROM `travaux` WHERE id_tr=".$num_tr;
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$sql="DELETE FROM `famil_trav` WHERE idtr_ft=".$num_tr;
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$sql="DELETE from `trav_com` WHERE trav=".$num_tr;
try {$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}// effacement des anciennes communes

print("<script language = \"JavaScript\"> location.href = 'accueil1.php?disp=session_travaux'; </script>");
?>