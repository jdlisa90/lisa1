<?php
session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
include("../include/types1.inc");
define("NB_LN_PPAGE",30);
define("NB_LN",NB_LN_PPAGE*10000); // nombre maximal de lignes recherchée (10000 pages)
$arch=$_REQUEST['arch'];
$a0=$_REQUEST['a0'];
$a1=$_REQUEST['a1'];
$lieu=$_REQUEST['lieu'];
$page=$_REQUEST['page'];

$sql="SELECT arch_ssserie.lib as libarch, type_arch,id from arch_ssserie where arch_ssserie.id1 = $arch";
try {$rek=$bdd->prepare($sql);}		//1ère requête pour le nom de l'archive
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('arch', $arch, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$l=$rek->fetch(PDO::FETCH_ASSOC);
if($l["type_arch"]<20) $lib_arch=$type_arch[$l["type_arch"]]." : ";

$result="";
if($lieu!=""){
    $result.=" ; lieu=";
    $result.="*".$lieu."*";
}
if($a0!=""){
    if($a1!="") $result.=" ; entre $a0 et $a1";
    else $result.=" ; après $a0";
}
else{
    if($a1!="") $result.=" ; avant $a1";
}
if ($_SESSION['statut']>1 OR $_SESSION['editeur']==1){
    $lieu=strtr($lieu,"*","%");
    $lieu=strtr($lieu,"?","_");
}
$sql="SELECT count(*) as nb FROM arch_ssection WHERE (complet=0 OR arch_totale=0) AND urls=0 AND arch_id1=$arch";
try {$rek=$bdd->prepare($sql);}		//1ère requête pour le nom de l'archive
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('arch', $arch, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
if($rek->rowCount()>0) $incomplet=" (Cette archive n'est pas entièrement dépouillée.)";

$sql="";
if($lieu<>""){
    $sql.=" AND lieux_regex LIKE :lieu";
}

$result=str_replace("**","*",$result);    
$result="<div class='row mt-3 pb-3' style='border-bottom:none;'><div class='col pe-0'>
    <p><a href='#' data-bs-toggle='modal' data-bs-target='#patros' title='Éditer la recherche'><b>Recherche de patronymes par archives</b></a> : 
    <a href='accueil1-sources_archive-arch-".$l["id"].".html' title='Page de l&#39;archive'>$lib_arch ".$l["libarch"]."</a>".$result.$incomplet."</p>"; // ligne de résumé de la recherche

if($a0<>"")	$sql.=" AND individus.an>=:a0";  							/* élimination des actes non-datés (an=0)	*/
if($a1<>"") $sql.=" AND individus.an>0 AND individus.an<=:a1";  /* dans les deux cas 							*/

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

$sql="SELECT DISTINCT nom from individus WHERE arch=:arch AND nom<>'' AND m_e_lCNIL<=".date("Y").$sql." AND pub=1 ORDER BY nom" ;
try {$rek=$bdd->prepare($sql);}		//1ère requête pour LE dénombrement et le CLASSEMENT
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('arch', $arch, PDO::PARAM_INT);
if($lieu<>"") $rek->bindValue('lieu', "%".preg_replace('#[^[:alpha:]%_]#u', '', $lieu)."%", PDO::PARAM_STR);
if($a0<>"") $rek->bindValue('a0', $a0, PDO::PARAM_INT);
if($a1<>"") $rek->bindValue('a1', $a1, PDO::PARAM_INT);
try{$rek->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
if ($rek->rowCount()>0){
    $result.="<div class='row'>";
    while ($l=$rek->fetch(PDO::FETCH_ASSOC)){
        $result.="<div class='col-2'>".$l["nom"]."</div>";
    }
    $result.="</div>";
}
else $result.="Aucun enregistrement ne répond à la recherche.";
$result.="</div></div>";
$res=1;
echo json_encode(array("result"=>$result,"res"=>$res));
?>