<?php
session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}

define("NB_LN_PPAGE",30);
define("NB_LN",NB_LN_PPAGE*10000); // nombre maximal de lignes recherchée (10000 pages)
$nomA=$_REQUEST['nomA'];
$nom_exactA=$_REQUEST['nom_exactA'];
$prenomA=$_REQUEST['prenomA'];
$prenom_exactA=$_REQUEST['prenom_exactA'];
$nomB=$_REQUEST['nomB'];
$nom_exactB=$_REQUEST['nom_exactB'];
$prenomB=$_REQUEST['prenomB'];
$prenom_exactB=$_REQUEST['prenom_exactB'];
$a0=$_REQUEST['a0'];
$a1=$_REQUEST['a1'];
$lieu=$_REQUEST['lieu'];
$page=$_REQUEST['page'];
$l0=$page*NB_LN_PPAGE; 
$result="nomA=";
if($nomA==""){
    if($nom_exactA=='y') $result.="(vide)";
    else $result.="*";
}
else{
    if($nom_exactA=='y') $result.=$nomA;
    else $result.="*$nomA*";   
}
if($prenomA!=""){
    $result.=" ; prénomA=";
    if($prenom_exactA=='y') $result.=$prenomA;
    else $result.="*".$prenomA."*";
}
$result.=" ; nomB=";
if($nomB==""){
    if($nom_exactB=='y') $result.="(vide)";
    else $result.="*";
}
else{
    if($nom_exactB=='y') $result.=$nomB;
    else $result.="*$nomB*";   
}
if($prenomB!=""){
    $result.=" ; prénomB=";
    if($prenom_exactB=='y') $result.=$prenomB;
    else $result.="*".$prenomB."*";
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

if ($_SESSION['statut']>1 OR $_SESSION['editeur']==1){
    $nomA=strtr($nomA,"*","%");
    $nomA=strtr($nomA,"?","_");
    $prenomA=strtr($prenomA,"*","%");
    $prenomA=strtr($prenomA,"?","_");
    $nomB=strtr($nomB,"*","%");
    $nomB=strtr($nomB,"?","_");
    $prenomB=strtr($prenomB,"*","%");
    $prenomB=strtr($prenomB,"?","_");
    $lieu=strtr($lieu,"*","%");
    $lieu=strtr($lieu,"?","_");
}
else {				/* suppression des caractères de contrôle */						
$nomA=strtr($nomA,"%","");
$nomA=strtr($nomA,"_","");
$nomB=strtr($nomB,"%","");
$nomB=strtr($nomB,"_","");
}
if($nom_exactA<>'y') $nomA="%".$nomA."%";
$sql1="couples.nom1 LIKE :nomA";
$sql2="couples.nom2 LIKE :nomA";
if($prenomA<>"") {
	if($prenom_exactA<>'y') $prenomA="%".$prenomA."%";
	$sql1.=" AND couples.prenom1 LIKE :prenomA";
    $sql2.=" AND couples.prenom2 LIKE :prenomA";
}
if($nom_exactB<>'y') $nomB="%".$nomB."%";
$sql1.=" AND couples.nom2 LIKE :nomB";
$sql2.=" AND couples.nom1 LIKE :nomB";
if($prenomB<>"") {
	if($prenom_exactB<>'y') $prenomB="%".$prenomB."%";
	$sql1.=" AND couples.prenom2 LIKE :prenomB";
    $sql2.=" AND couples.prenom1 LIKE :prenomB";
}
$sql0=" ($sql1 OR $sql2)";// $sql0 contient tous les critères de sélection (servira 2 fois)
if($lieu<>"") $sql0.=" AND couples.lieu LIKE :lieu"; 
$result=str_replace("**","*",$result);
$result="<div class='row mt-3 pb-3' style='border-bottom:none;'><div class='col pe-0'>
<p><b><a href='#' data-bs-toggle='modal' data-bs-target='#couples'><b>Recherche par couples</b></a></b> : ".$result."</p>";
if($a0<>"")	$sql0.=" AND couples.an>=:a0";  							/* élimination des actes non-datés (an=0)	*/
if($a1<>"") $sql0.=" AND couples.an>0 AND couples.an<=:a1";  /* dans les deux cas */
$sql="SELECT couples.id FROM couples WHERE".$sql0;						
// table temporaire avec l'id des couples + tout ce qui est nécessaire dans les 2 autres tables
/* try {$bdd->query("create temporary table temp1(num_c int not null, type_acte varchar(50), arch smallint(6), libarch tinytext)");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));} */
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
//exit(json_encode(array("res"=>0,"result"=>$sql)));
try {$id=$bdd->prepare($sql);}		//1ère requête pour le nombre de résultats
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$id->bindValue('nomA', $nomA, PDO::PARAM_STR);
$id->bindValue('nomB', $nomB, PDO::PARAM_STR);
if($prenomA<>"") $id->bindValue('prenomA', $prenomA, PDO::PARAM_STR);
if($prenomB<>"") $id->bindValue('prenomB', $prenomB, PDO::PARAM_STR);
if($lieu<>"") $id->bindValue('lieu', "%".preg_replace('#[^[:alpha:]%_]#u', '', $lieu)."%", PDO::PARAM_STR);
if($a0<>"") $id->bindValue('a0', $a0, PDO::PARAM_STR);
if($a1<>"") $id->bindValue('a1', $a1, PDO::PARAM_STR);
try{$id->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$nb_repTot = $id->rowCount();

if($nb_repTot>0){
    $nb_page_total=ceil($nb_repTot/NB_LN_PPAGE);
    $sql="SELECT couples.*, acts.TYPE_ACT, acts.arch, arch_ssserie.lib as libarch FROM couples, acts, arch_ssserie
	WHERE acts.NUM=couples.acte AND arch_ssserie.id1=acts.arch";  // requête principale, avec jointures et totalité des données (mais petit nombre de résultats)
    $sql.=" AND".$sql0." ORDER BY couples.an LIMIT ".$l0.",".NB_LN_PPAGE; // on réutilise $sql0
    //exit(json_encode(array("res"=>0,"result"=>$nb_repTot." ".$sql." ".$nomA.$nomB)));
    try {$id=$bdd->prepare($sql);}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
    $id->bindValue('nomA', $nomA, PDO::PARAM_STR);
    $id->bindValue('nomB', $nomB, PDO::PARAM_STR);
    if($prenomA<>"") $id->bindValue('prenomA', $prenomA, PDO::PARAM_STR);
    if($prenomB<>"") $id->bindValue('prenomB', $prenomB, PDO::PARAM_STR);
    if($lieu<>"") $id->bindValue('lieu', "%".preg_replace('#[^[:alpha:]%_]#u', '', $lieu)."%", PDO::PARAM_STR);
    if($a0<>"") $id->bindValue('a0', $a0, PDO::PARAM_STR);
    if($a1<>"") $id->bindValue('a1', $a1, PDO::PARAM_STR);
    try{$id->execute();}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

    $result.="<table class='table table-sm'>
    <thead>
    <tr>
        <th scope='col'></th>
        <th scope='col'>NomA</th>
        <th scope='col'>PrénomA</th>
        <th scope='col'>NomB</th>
        <th scope='col'>PrénomB</th>
        <th scope='col'>Année</th>
        <th scope='col'>Acte</th>
        <th scope='col'>Source</th>
    </tr>
    </thead>
    <tbody>";
    while ($l=$id->fetch(PDO::FETCH_ASSOC)){
		if ($l["an"]>0) $an=$l["an"];
		else $an="";
		$source=$l["libarch"];
		if($l["arch"]<230) $source.=" (EC)";
		$nom1=$l["nom1"];
		$nomA_=strtr(utf8_decode($nomA), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		$nom1_=strtr(utf8_decode($nom1), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		// contrairement à mysql, preg_match est sensible aux caractères accentués
		$prenom1=$l["prenom1"];
		$nom2=$l["nom2"];
		$prenom2=$l["prenom2"];
		$ordre1=$l["ordre1"];
		$ret="";
		if(!preg_match("#".preg_replace(array("#%#","#_#"),array(".*","."),$nomA_)."#i",$nom1_)){
				//$nom1 est la chaîne (temporaire) à afficher, $nomA est le 1er nom demandé (pérenne, peut _ ou %) ; permutation éventuelle
			$nom1=$l["nom2"];
			$prenom1=$l["prenom2"];
			$nom2=$l["nom1"];
			$prenom2=$l["prenom1"];
			$ordre1=$l["ordre2"];
			//$ret="ret";
		}
        $chlnk="<a href='javascript:affiche_acte(&#039 ".$l["arch"]."&#039,".$l["acte"].",".$ordre1.")' title=\"synthèse de l'acte\">";
        $result.="<tr style='vertical-align:middle;'><td class='pb-0 pt-0'>".$chlnk."<button class='btn btn-inflate p-0' style='width:30px;height:30px;'>
        <i class='bi bi-eye-fill fs-2' onclick=\"this.className='bi bi-eye fs-2'\"></i></button></a>";
        $result.="<td class='pb-0 pt-0'>".$nom1."<td>".$prenom1."<td>".$nom2."</a><td>".$prenom2."<td>".$an;
        $result.="<td class='pb-0 pt-0'>".$l["TYPE_ACT"]."<td class='pb-0 pt-0'>".$source;
        $result.="</tr>";
        $nbl++;
    }
    $result.="</tbody>
    </table>";
    $ll=$l0+$nbl;
    $l0++;
    $result.="<p><b>Couples $l0 à $ll sur $nb_repTot";
    if($nb_page_total>1){
        $page0=$page-1;						// $page est le numéro de la page -1 (on commence avec $page=0)
        $page1=$page+1;
        if ($page>0){
            $result.="&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick='requeteC(0)' title='Première'><b><<</b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteC($page0)' title='Précédente'><b><</b></a>";
        }
        $result.=" &nbsp; <select name='p' id='select_page' onChange='requeteC(\"\select\")'>";
        for ($k=1;$k<=$nb_page_total;$k++) {
            $result.="<option value='".($k-1)."'";
            if($k==$page1) $result.=" selected";
            $result.=">$k</option>";
        }
        $result.="</select>&nbsp;";
        if($page<$nb_page_total-1){
            $result.="&nbsp;&nbsp;<a href='#' onClick='requeteC($page1)' title='Suivante'><b>></b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteC($nb_page_total-1)' title='Fin'><b>>></b></a>";
        }
    }
    $result.="</div></div>";
}
else $result.="Aucun enregistrement ne répond à la recherche.";
$res=1;
echo json_encode(array("result"=>$result,"res"=>$res));
?>