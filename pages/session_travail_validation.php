<?php
if ($_SESSION['statut']<1){
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
}
extract($_POST);

if($id_tr!=""){  // on efface les familles et communes associées à l'enregistrement existant
    $sql="DELETE from `famil_trav` WHERE idtr_ft=:id_tr";
    try {$req=$bdd->prepare($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $req->bindValue('id_tr', $id_tr, PDO::PARAM_STR);
    try{$req->execute();}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    
    $sql="DELETE from `trav_com` WHERE trav=:id_tr";
    try {$req=$bdd->prepare($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $req->bindValue('id_tr', $id_tr, PDO::PARAM_STR);
    try{$req->execute();}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

    $sql="UPDATE `travaux` SET tit_tr=:titre,pub=0 WHERE id_tr=:id_tr"; // on modifie seulement le titre
    try {$req=$bdd->prepare($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $req->bindValue('id_tr', $id_tr, PDO::PARAM_STR);
}
else{
    $action="insert";
    $sql="INSERT INTO `travaux` SET idch_tr=".$_SESSION['_id'].", tit_tr=:titre, pub=0"; // pub=0 car la publicité n'en a pas été encore faite
    try {$req=$bdd->prepare($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
}
$req->bindValue('titre', $titre, PDO::PARAM_STR);
try{$req->execute();}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$content=$sql;
if($id_tr=="") $id_tr=$bdd->lastInsertId();     // cas d'insertion

for ($f=0;$f<12;$f++){       // écriture des nouvelles familles
    $fa="fam".$f;
    $$fa= trim($$fa);
    if ($$fa<>""){
        $sql="INSERT INTO `famil_trav` (idtr_ft,famil_ft) VALUES(".$id_tr.",:famille)";    // rien dans idloc_ft (inutile)
        try {$req_i_f=$bdd->prepare($sql);}
        catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $req_i_f->bindValue('famille', $$fa, PDO::PARAM_STR);
        try{$req_i_f->execute();}
        catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    }
}

foreach($_POST["communes"] as $value) {
    $sql="INSERT INTO `trav_com` (trav,comm) VALUES(".$id_tr.",".$value.")";
    try {$bdd->query($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
}

/* for ($f=0;$f<$nb_com;$f++){ // écriture des nouvelles communes
    $fa="com".$f;
    if ($$fa<>0){                 // champs "commune" d'origine ($com0, $com1, ...), éventuellement nuls -alors on ne les inscrit pas-
        $sql="INSERT INTO `trav_com` (trav,comm) VALUES(".$id_tr.",".$$fa.")";
        try {$bdd->query($sql);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    }
} */
                            // Le système de mailin n'a pas été implanté, vu le peu d'utilisations de cette partie
if ($more=="y") print("<script language = \"JavaScript\"> location.href = 'accueil1.php?disp=session_travail_edit'; </script>");
else print("<script language = \"JavaScript\"> location.href = 'accueil1.php?disp=session_travaux'; </script>");
?>