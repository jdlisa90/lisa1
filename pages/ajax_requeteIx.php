<?php
session_start();
// ---------------------------------------------------
// $texte : le texte formaté (avec des balises HTML)
// $nbreCar : le nombre de caractères texte à afficher (sans compter les balises HTML)
// $nbreCar (minimum) : pour ne pas couper un mot, le compte s'arrêtera à l'espace suivant
// ---------------------------------------------------
function couper_texte_html($texte, $nbreCar){
    $nbreCarTexte = strlen(strip_tags($texte));
    $texte=nl2br($texte);
 
    if(is_numeric($nbreCar) and $nbreCarTexte > $nbreCar)    {
        $nbreCarTexte = strlen($texte);
        $LongueurAvantSansHtml    = strlen(trim(strip_tags($texte)));
        $MasqueHtmlSplit        = '#</?([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?>#';
        $MasqueHtmlMatch        = '#<(?:/([a-zA-Z1-6]+)|([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?)>#';
        $texte                  .= ' ';
        $BoutsTexte             = preg_split($MasqueHtmlSplit, $texte, -1,  PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $NombreBouts            = count($BoutsTexte);
        if( $NombreBouts == 1 ){
            $texte                .= ' ';
            $LongueurAvant        = strlen($texte);
            $texte                 = substr($texte, 0, strpos($texte, ' ', $LongueurAvant > $nbreCar ? $nbreCar : $LongueurAvant));
            if ($PointSuspension!='' && $LongueurAvant > $nbreCar) {
                $texte            .= $PointSuspension;
            }
        } else {
            $longueur                = 0;
            $indexDernierBout        = $NombreBouts - 1;
            $position                = $BoutsTexte[$indexDernierBout][1] + strlen($BoutsTexte[$indexDernierBout][0]) - 1;
            $indexBout                = $indexDernierBout;
            $rechercheEspace        = true;
            foreach( $BoutsTexte as $index => $bout ){
                $longueur += strlen($bout[0]);
                if( $longueur >= $nbreCar ){
                     $position_fin_bout = $bout[1] + strlen($bout[0]) - 1;
                     $position = $position_fin_bout - ($longueur - $nbreCar);
                     if( ($positionEspace = strpos($bout[0], ' ', $position - $bout[1])) !== false  ){
                            $position    = $bout[1] + $positionEspace;
                            $rechercheEspace = false;
                     }
                     if( $index != $indexDernierBout )
                            $indexBout    = $index + 1;
                     break;
                }
            }
            if( $rechercheEspace === true ){
                for( $i=$indexBout; $i<=$indexDernierBout; $i++ ){
                     $position = $BoutsTexte[$i][1];
                     if( ($positionEspace = strpos($BoutsTexte[$i][0], ' ')) !== false )
                     {
                            $position += $positionEspace;
                            break;
                     }
                }
            }
            $texte                    = substr($texte, 0, $position);
            preg_match_all($MasqueHtmlMatch, $texte, $retour, PREG_OFFSET_CAPTURE);
            $BoutsTag                = array();
            // ---------------------
            foreach( $retour[0] as $index => $tag ){
                if( isset($retour[3][$index][0]) ){
                     continue;
                }
                if( $retour[0][$index][0][1] != '/' ){
                     array_unshift($BoutsTag, $retour[2][$index][0]);
                }
                else{
                     array_shift($BoutsTag);
                }
            }
            if( !empty($BoutsTag) ){
                foreach( $BoutsTag as $tag ){
                     $texte        .= '</'.$tag.'>';
                }
            }
        }
        if($nbreCarTexte>strlen($texte)) $texte.="...";
    }
    return $texte;
};
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));}
include("../include/types1.inc");
define("NB_LN_PPAGE",30);
define("NB_LN",NB_LN_PPAGE*10000); // nombre maximal de lignes recherchée (10000 pages)
$ix=$_REQUEST['ix'];
$a0=$_REQUEST['a0'];
$a1=$_REQUEST['a1'];
$page=$_REQUEST['page'];

$result="<div class='row mt-3 pb-3' style='border-bottom:none;'><div class='col pe-0'>
    <p><b><a href='#' data-bs-toggle='modal' data-bs-target='#indexes'><b>Actes indexés : </b></a></b>&#34;";
$sql="SELECT item, comment FROM index_actes WHERE id=".$ix;
$req=$bdd->prepare($sql);
try{$req->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .'</b> '. $e->getMessage(),"res"=>0)));}
$l=$req->fetch(PDO::FETCH_ASSOC);
$result.=$l["item"]."&#34;";
if($l["comment"]!="") $result.=" (".$l["comment"].")";

if($a0!=""){
    if($a1!="") $result.=" ; entre $a0 et $a1";
    else $result.=" ; après $a0";
}
else{
    if($a1!="") $result.=" ; avant $a1";
}
date("Y");

$sql="SELECT DISTINCT acts.NUM, TYPE_ACT, arch, SUBSTRING(DATE_ACT FROM (LENGTH(DATE_ACT)-LOCATE('.', REVERSE(DATE_ACT))+2)) as Y, DIVERS, arch_ssserie.lib_abrg as libarch 
    FROM `acts-index`, acts, arch_ssserie
    WHERE arch_ssserie.id1 = acts.arch AND acts.NUM=`acts-index`.acte AND `acts-index`.index_id=:ix";
if($a0<>"")	$sql.=" AND SUBSTRING(DATE_ACT FROM (LENGTH(DATE_ACT)-LOCATE('.', REVERSE(DATE_ACT))+2))>=:a0";  	
if($a1<>"") $sql.=" AND SUBSTRING(DATE_ACT FROM (LENGTH(DATE_ACT)-LOCATE('.', REVERSE(DATE_ACT))+2))<=:a1";
$sql.=" ORDER BY Y";

$loadresult = @exec('uptime');
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs);
if(isset($avgs[1])) {
	if($avgs[1] > $overloadmax) {
		exit(json_encode(array("result"=>"Le serveur est trop chargé pour permettre une recherche - merci de revenir plus tard \n")));
	}
}

try {$id=$bdd->prepare($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$id->bindValue('ix', $ix, PDO::PARAM_INT);
if($a0<>"") $id->bindValue('a0', $a0, PDO::PARAM_STR);
if($a1<>"") $id->bindValue('a1', $a1, PDO::PARAM_STR);
try{$id->execute();}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$nb_repTot = $id->rowCount();
if($nb_repTot>0){
    $nb_page_total=ceil($nb_repTot/NB_LN_PPAGE);
    $l0=$page*NB_LN_PPAGE;
    
    $sql.=" LIMIT ".$l0.",".NB_LN_PPAGE;
    try {$id=$bdd->prepare($sql);}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
    $id->bindValue('ix', $ix, PDO::PARAM_INT);
    if($a0<>"") $id->bindValue('a0', $a0, PDO::PARAM_STR);
    if($a1<>"") $id->bindValue('a1', $a1, PDO::PARAM_STR);
    try{$id->execute();}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
    
    $result.="<table class='table table-sm'>
    <thead>
    <tr>
        <th scope='col'></th>
        <th scope='col'>Description</th>
        <th scope='col'>Année</th>
        <th scope='col'>Type d'acte</th>
        <th scope='col' style='width:150px;'>Source</th>
    </tr>
    </thead>
    <tbody>";
    $nbl=0;
    while ($l=$id->fetch(PDO::FETCH_ASSOC)){
        $result.="<tr style='vertical-align:middle;'><td class='pb-0 pt-0'><a href='javascript:affiche_acte(&#039 ".$l["arch"]."&#039,".$l["NUM"].",1)' title=\"synthèse de l'acte\">
        <button class='btn btn-inflate p-0' style='width:30px;height:30px;'><i class='bi bi-eye-fill fs-2' onclick=\"this.className='bi bi-eye fs-2'\"></i></button></a>
        <td class='pb-0 pt-0'>".couper_texte_html($l["DIVERS"],160)."<td class='pb-0 pt-0'>".$l["Y"]."<td class='pb-0 pt-0'>".$l["TYPE_ACT"]."<td class='pb-0 pt-0'>".$l["libarch"];
        $nbl++;
    }
    $result.="</tbody></table>";
    $result.="</div></div>";
    $ll=$l0+$nbl;
    $l0++;
    $result.="<p><b>Actes $l0 à $ll sur $nb_repTot";
    if($nb_page_total>1){
        $page0=$page-1;						// $page est le numéro de la page -1 (on commence avec $page=0) 
        $page1=$page+1;
        if ($page>0){
            $result.="&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick='requeteIx(0)' title='Première'><b><<</b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteIx($page0)' title='Précédente'><b><</b></a>";
        }
        $result.=" &nbsp; <select name='p' id='select_page' onChange='requeteIx(\"\select\")'>";
        for ($k=1;$k<=$nb_page_total;$k++) {
            $result.="<option value='".($k-1)."'";
            if($k==$page1) $result.=" selected";
            $result.=">$k</option>";
        }
        $result.="</select>&nbsp;";
        if($page<$nb_page_total-1){
            $result.="&nbsp;&nbsp;<a href='#' onClick='requeteIx($page1)' title='Suivante'><b>></b></a>
            &nbsp;&nbsp;<a href='#' onClick='requeteIx($nb_page_total-1)' title='Fin'><b>>></b></a>";
        }
    }
    $result.="</div></div>";
}
else $result.="<div>Aucun enregistrement ne répond à la recherche.</div>";

$res=1;
echo json_encode(array("result"=>$result,"res"=>$res));
?>