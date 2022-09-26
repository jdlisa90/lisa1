<?php
session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
include("../include/types1.inc");
define("NB_LN_PPAGE",30);
define("NB_LN",NB_LN_PPAGE*10000); // nombre maximal de lignes recherchée (10000 pages)
$nom=$_REQUEST['nom'];
$prenom=$_REQUEST['prenom'];
$a0=$_REQUEST['a0'];
$a1=$_REQUEST['a1'];
$occurrences=$_REQUEST['occurrences'];
//exit(json_encode(array("res"=>0,"result"=>$occurrences.$nb_rep." ".$sql." ".$nom.$prenom.$a0.$a1)));
$result="nom=";
if($nom==""){
    $result.="*";
}
else{
    $result.=$nom;   
}
if($prenom!=""){
    $result.=" ; prénom=".$prenom;
}
if($a0!=""){
    if($a1!="") $result.=" ; entre $a0 et $a1";
    else $result.=" ; après $a0";
}
else{
    if($a1!="") $result.=" ; avant $a1";
}
$result=str_replace("**","*",$result);
$result="<div class='row mt-3 pb-3' style='border-bottom:none;'><div class='col pe-0'>
    <p><b><a href='#' data-bs-toggle='modal' data-bs-target='#sources'><b>Recherche de sources</b></a></b> : ".$result;
//$result.=" (les actes pourront être listés en utilisant <a href='#' data-bs-toggle='modal' data-bs-target='#archives'>la recherche par Archive</a>).</p>";
//$result.=" (les actes pourront être listés facilement en utilisant <a href='accueil1.php?disp=recherche&form=archive&nom=$nom&prenom=$prenom&a1=$a1&a0=$a0' target='_blank'>la recherche par Archive (dans une nouvelle fenêtre)</a>).</p>";
$result.=" (chaque lien ouvre une recherche par <b>archive</b> dans une nouvelle fenêtre)</a>).</p>";

if ($_SESSION['statut']>1 OR $_SESSION['editeur']==1){
    $nom=strtr($nom,"*","%");
    $nom=strtr($nom,"?","_");
    $prenom=strtr($prenom,"*","%");
    $prenom=strtr($prenom,"?","_");
}
date("Y");

$sql="SELECT DISTINCT id1, type_arch, lib_abrg, lib FROM arch_ssserie,individus WHERE arch_ssserie.id1=individus.arch AND ";
$sql1="individus.nom LIKE :nom";
if($prenom<>"")	$sql1=$sql1." and individus.prenom LIKE :prenom";
if($a0<>"")	$sql1=$sql1." and individus.an>=:a0";  	
if($a1<>"") $sql1=$sql1." and individus.an>0 and individus.an<=:a1";
$sql.=$sql1." ORDER BY type_arch, lib";

$loadresult = @exec('uptime');
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs);
if(isset($avgs[1])) {
	if($avgs[1] > $overloadmax) {
		exit(json_encode(array("result"=>"Le serveur est trop chargé pour permettre une recherche - merci de revenir plus tard \n")));
	}
	if(strpos($nom,"%")<4 and strstr($nom,"%")<>FALSE and $avgs[1] > $overloadmin) {
		exit(json_encode(array("result"=> "Le serveur est trop chargé pour permettre une recherche avec jokers -
         merci de revenir plus tard ou d'effectuer une recherche plus précise\n")));
	}
	if($nom=="" and $avgs[1] > $overloadmin) {
		exit(json_encode(array("result"=>"Le serveur est trop chargé pour permettre une recherche - merci de revenir plus tard \n")));
	}
}
	//Fin de la constitution de la requète principale
   
try {$id=$bdd->prepare($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$id->bindValue('nom', $nom, PDO::PARAM_STR);
if($prenom<>"") $id->bindValue('prenom', $prenom, PDO::PARAM_STR);
if($a0<>"") $id->bindValue('a0', $a0, PDO::PARAM_STR);
if($a1<>"") $id->bindValue('a1', $a1, PDO::PARAM_STR);
try{$id->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$nb_rep = $id->rowCount();
//exit(json_encode(array("res"=>0,"result"=>$nb_rep." ".$sql." ".$nom.$prenom.$a0.$a1)));
$t_arch=0;
$nb_l=0;            // nb de lignes effectivement écrites 
if ($nb_rep>0){
    $result.="<table class='table table-sm table-borderless'>
    <thead>
    <tr>
        <th scope='col'>Type de la source</th>
        <th scope='col'>Libellé";
        if($occurrences==="true") $result.="(occurrences)";
    $result.="</th>
    </tr>
    </thead>
    <tbody>";
    while ($l=$id->fetch(PDO::FETCH_ASSOC)){
        if($occurrences==="true"){
            $sql="SELECT COUNT(num_ind) as countid FROM individus WHERE ".$sql1." AND pub=1 and arch=".$l["id1"];	//nombre d'occurrences
            try {$rek=$bdd->prepare($sql);}
            catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
            $rek->bindValue('nom', $nom, PDO::PARAM_STR);
            if($prenom<>"") $rek->bindValue('prenom', $prenom, PDO::PARAM_STR);
            if($a0<>"") $rek->bindValue('a0', $a0, PDO::PARAM_STR);
            if($a1<>"") $rek->bindValue('a1', $a1, PDO::PARAM_STR);
            try{$rek->execute();}
            catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
            $nbligne = $rek->fetch(PDO::FETCH_ASSOC);
            $nb_occ=" (".$nbligne["countid"].")";
        }
//		 	$rek->closeCursor();
        if($t_arch==$l["type_arch"]) $type_ar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"";// pour ne pas répéter le type_arch 
        else{
            $type_ar=$type_arch[$l["type_arch"]];
            $t_arch=$l["type_arch"];
        }
        $result.= "<tr><td>".$type_ar."<td>";
        if($l["lib_abrg"]<>"") $lib_arch=$l["lib_abrg"];
        else $lib_arch=$l["lib"];
        //$result.=$lib_arch.$nb_occ;
        $result.="<a href='accueil1.php?disp=recherche&form=archive&nom=".$_REQUEST['nom']."&prenom=$prenom&a1=$a1&a0=$a0&arch=".$l["id1"]."' target='_blank'>".$lib_arch."</a>".$nb_occ;      // libellé de la source avec lien toggle vers le formulaire d'archives
    }
    $result.="</tbody></table>";
    $result.="</div></div>";
}
$res=1;
echo json_encode(array("result"=>$result,"res"=>$res));
?>