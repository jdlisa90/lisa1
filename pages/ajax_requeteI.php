<?php
session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}

define("NB_LN_PPAGE",30);
define("NB_LN",NB_LN_PPAGE*10000); // nombre maximal de lignes recherchée (10000 pages)
$nom=$_REQUEST['nom'];
$nom_exact=$_REQUEST['nom_exact'];
$prenom=$_REQUEST['prenom'];
$prenom_exact=$_REQUEST['prenom_exact'];
$lieu=$_REQUEST['lieu'];
$a0=$_REQUEST['a0'];
$a1=$_REQUEST['a1'];
$sit=$_REQUEST['sit'];
$div=$_REQUEST['div'];
$page=$_REQUEST['page'];

$result="nom=";
if($nom==""){
    if($nom_exact=='y') $result.="(vide)";
    else $result.="*";
}
else{
    if($nom_exact=='y') $result.=$nom;
    else $result.="*$nom*";   
}
if($prenom!=""){
    $result.=" ; prénom=";
    if($prenom_exact=='y') $result.=$prenom;
    else $result.="*".$prenom."*";
}
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
if($div!=""){
    $result.=" ; divers=";
    $result.="*".$div."*";
}

if ($_SESSION['statut']>1 OR $_SESSION['editeur']==1){
    $nom=strtr($nom,"*","%");
    $nom=strtr($nom,"?","_");
    $prenom=strtr($prenom,"*","%");
    $prenom=strtr($prenom,"?","_");
    $lieu=strtr($lieu,"*","%");
    $lieu=strtr($lieu,"?","_");
    $div=strtr($div,"*","%");
    $div=strtr($div,"?","_");
}
else {										
    $nom=strtr($nom,"%","");
    $nom=strtr($nom,"_","");
}
if($nom_exact<>'y') $nom="%".$nom."%";
$sql="(individus.nom LIKE :nom)";
if($prenom<>"") {
	if($prenom_exact<>'y') $prenom="%".$prenom."%";
	$sql.=" and (individus.prenom LIKE :prenom)";
}

if($lieu<>""){
    $sql.=" and lieux_regex LIKE :lieu";
}
if($div<>""){
    $sql.=" and individus.infos LIKE :div";
}
switch ($sit) {
    case 1:
        $sql.=" and individus.niveau<2 ";	/*Individus centraux*/
        $situation="Individus centraux";
        break;
    case 2:
        $sql.=" and individus.niveau<3 ";	/*Individus centraux + parents*/
        $situation="Individus centraux + parents";
        break;
    case 7 :
        $sql.=" and individus.arch>229 ";	/*Tous individus, hors état-civil*/
        $situation="Tous individus, hors état-civil";
        break;
    case 10:												/*Tous*/
        $situation="Tous individus";
        break;
    case "Baptisé":
        $sql.=" and (individus.situation LIKE '".$sit."%' OR individus.situation LIKE 'Nv. né%')";
        $situation="Enfant (naissances)";
        break;
    default:
        $sql.=" and individus.situation LIKE '".$sit."%' ";
        $situation=$sit;
}
$result=str_replace("**","*",$result);
$result="<div class='row mt-3 pb-3' style='border-bottom:none;'><div class='col pe-0'>
<p><a href='#' data-bs-toggle='modal' data-bs-target='#individus'><b>Recherche par individus</b></a> : ".$situation." ; ".$result."</p>";
$sql.=" AND individus.m_e_lCNIL<=".date("Y");
if($a0<>"")	$sql.=" and individus.an>=:a0";  							/* élimination des actes non-datés (an=0)	*/
if($a1<>"") $sql.=" and individus.an>0 and individus.an<=:a1";  /* dans les deux cas 							*/


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
try {$bdd->query("create temporary table temp1(num_ind int not null, an int null, nom varchar(30) null, prenom varchar(30) null, niveau tinyint(3) null, 
        residence tinytext, pub tinyint)");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$sql="insert into temp1(num_ind, an, nom, prenom, niveau,pub) select num_ind, an, nom, prenom, niveau,pub from individus WHERE ".$sql." limit ".NB_LN;
try {$rek=$bdd->prepare($sql);}		//1ère requête pour LE dénombrement et le CLASSEMENT
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$rek->bindValue('nom', $nom, PDO::PARAM_STR);
if($prenom<>"") $rek->bindValue('prenom', $prenom, PDO::PARAM_STR);
if($lieu<>"") $rek->bindValue('lieu', "%".preg_replace('#[^[:alpha:]%_]#u', '', $lieu)."%", PDO::PARAM_STR);
if($div<>"") $rek->bindValue('div', "%".$div."%", PDO::PARAM_STR);
if($a0<>"") $rek->bindValue('a0', $a0, PDO::PARAM_STR);
if($a1<>"") $rek->bindValue('a1', $a1, PDO::PARAM_STR);
try{$rek->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$sql="select count(*) as nb from temp1 where pub=1";
try {$req=$bdd->query($sql);}	// décompte du nombre d'actes publics
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$l=$req->fetch(PDO::FETCH_ASSOC);
$nb_repTot = $l["nb"];
$nb_page_total=ceil($nb_repTot/NB_LN_PPAGE);

$sql="create temporary table temp2(num_ind int not null)";
try {$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$l0=$page*NB_LN_PPAGE;         //rang du 1er acte sélectionné (0 par défaut)

$sql="insert into temp2(num_ind) select num_ind from temp1 where pub=1 order by an, nom, prenom, niveau desc limit ".$l0.",".NB_LN_PPAGE;
try {$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

try {$bdd->query("drop table temp1");}
catch(Exception $e) {exit(json_encode(array("result"=>'drop <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$sql="select distinct individus.*, arch_ssserie.lib as libarch from temp2, individus, arch_ssserie where individus.num_ind = temp2.num_ind 
and arch_ssserie.id1 = individus.arch";
try {$id_rk=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$nb_rep = $id_rk->rowCount();
// ligne de résumé de la recherche 

if($prenom<>"") $rek->bindValue('prenom', $prenom, PDO::PARAM_STR);
if($lieu<>"") $rek->bindValue('lieu', "%".preg_replace('#[^[:alpha:]%_]#u', '', $lieu)."%", PDO::PARAM_STR);
if($a0<>"") $rek->bindValue('a0', $a0, PDO::PARAM_STR);
if($a1<>"") $rek->bindValue('a1', $a1, PDO::PARAM_STR);

$nb_l=0;            // nb de lignes effectivement écrites 
if ($nb_rep>0){
    $result.="<table class='table table-sm'>
    <thead>
    <tr>
        <th scope='col'></th>
        <th scope='col'>Nom</th>
        <th scope='col'>Prénom</th>
        <th scope='col'>Lieux</th>
        <th scope='col'>Année</th>
        <th scope='col'>Acte</th>
        <th scope='col'>Situation</th>
        <th scope='col'>Source</th>
    </tr>
    </thead>
    <tbody>";
    while ($l=$id_rk->fetch(PDO::FETCH_ASSOC)){
        if ($l["an"]>0) $an=$l["an"];
        else $an="n. d.";
        $lieu_aff=$l["lieu_arch"];
        if($l["residence"]<>$lieu_aff) $resid=$l["residence"];
            else $resid="";
        if($l["origine"]<>$lieu_aff) $orig=$l["origine"];
            else $orig="";
        if($resid<>"" or $orig<>"") {
            if($resid<>$orig){
                if($resid<>"" and $orig<>"") $lieu_aff.=" (".$resid.", ".$orig.")";
                else $lieu_aff.=" (".$resid.$orig.")";
            }
            else $lieu_aff.=" (".$resid.")";
        }
        $source=$l["libarch"];
		if($l["arch"]<230) $source.=" (EC)";
        $chlnk="<a href='javascript:affiche_acte(&#039 ".$l["arch"]."&#039,".$l["numacte"].",".$l["ordre"].")' title=\"synthèse de l'acte\">";
/*         $chlnk="<a href='#' data-bs-toggle='modal' data-bs-target='#acte_modal' 
            data-bs-arch='".$l["arch"]."' data-bs-numacte='".$l["numacte"]."' data-bs-ordre='".$l["ordre"]."'>"; SOLUTION MODALE NON UTILISÉE */
        //$result.="<tr><td>".$chlnk."<img src='../img/book_close.gif' align='left' onclick=\"this.src='../img/book_open.gif'\"></a><td>".$l["nom"];
        $result.="<tr style='vertical-align:middle;'><td class='pb-0 pt-0'>".$chlnk."<button class='btn btn-inflate p-0' style='width:30px;height:30px;'>
            <i class='bi bi-eye-fill fs-2' onclick=\"this.className='bi bi-eye fs-2'\"></i></button></a><td class='pb-0 pt-0'>".$l["nom"];
//        $result.="<tr><td>".$chlnk."<img src='../img/book_close.gif' align='left' onclick='affichage_acte(this)'></a><td>".$l["nom"]."</a>";
        $result.="<td class='pb-0 pt-0'>".$l["prenom"]."<td class='pb-0 pt-0'>".$lieu_aff."<td class='pb-0 pt-0'>".$an."<td class='pb-0 pt-0'>".$l["type_acte"]."<td class='pb-0 pt-0'>".$l["situation"]."<td>".$source;
        $result.="</tr>";
        $nbl++;
    }
    $result.="</tbody>
    </table>";
    $ll=$l0+$nbl;
    $l0++;
    $result.="<p><b>Individus $l0 à $ll sur $nb_repTot";
    if($nb_page_total>1){
        $page0=$page-1;						// $page est le numéro de la page -1 (on commence avec $page=0) 
        $page1=$page+1;
        if ($page>0){
            $result.="&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick='requeteI(0)' title='Première'><b><<</b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteI($page0)' title='Précédente'><b><</b></a>";
        }
        $result.=" &nbsp; <select name='p' id='select_page' onChange='requeteI(\"\select\")'>";
        for ($k=1;$k<=$nb_page_total;$k++) {
            $result.="<option value='".($k-1)."'";
            if($k==$page1) $result.=" selected";
            $result.=">$k</option>";
        }
        $result.="</select>&nbsp;";
        if($page<$nb_page_total-1){
            $result.="&nbsp;&nbsp;<a href='#' onClick='requeteI($page1)' title='Suivante'><b>></b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteI($nb_page_total-1)' title='Fin'><b>>></b></a>";
        }
    }
    $result.="</div></div>";
}
else $result.="Aucun enregistrement ne répond à la recherche.";
$res=1;
echo json_encode(array("result"=>$result,"res"=>$res));
?>