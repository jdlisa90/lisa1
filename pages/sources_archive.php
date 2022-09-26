<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$de=" de ";
$comm="Communes entrant dans le ressort de cette juridiction :";
$listarch="Archives relevant de cette juridiction :";
$an="Années";	
$l1="<b>Bapt&ecirc;mes<td align='center' bgcolor='#CCCC99'><b>Mariages<td align='center' bgcolor='#CCCC99'><b>S&eacutepultures</td></tr>";
$l4="<tr><td><b>Dépouillements";
$l3="<b>Familles les plus fréquentes :</b>";
$l4="<p>";
$l5="Nom";
$l6="%age des actes";
$l7="de";
$l8="à";
$l9="<b>Description :</b>";
$l10="<b>Bénévoles ayant participé au travail de dépouillement :</b><br>";
$dep3="<b>Membre(s) de LISA</b> ayant travaillé sur cette archive : ";
$m_action2="numérisation";
$cot="Cotes";
$en_lign2s=" a relevé ";
$en_lign2p=" ont relevé ";
$en_lign3=" individus dans ";
$en_lign4=" actes.";
$part_online="";
$part="";
$en_constr1="En cours de rédaction";
$en_constr2="En construction";
$a_realise_s=" a réalisé ";
$a_realise_p=" ont réalisé ";
$images=" images";
$dont=", dont ";
$online=" en ligne.";

$depl=array(0=>"à faire",1=>"fait",2=>"partiel", 3=>"partiel", 4=>"partiel");
$depp="Dépouillement ";

/* $arch est le id de arch_ssserie  */

extract($_POST,EXTR_OVERWRITE);

$rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib FROM arch_ssserie AS ss
 ,arch_dep,arch_serie WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND ss.id=".$arch." ORDER BY lib"; // id "absolu", <> id1 "de dépouillement"
try {$id=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$id->fetch(PDO::FETCH_ASSOC);
$dep=$l["depll"];


$content="<div class='row justify-content-center'>
<div class='col-10 pt-4 pb-4'>";
$content.="<div class='mb-4'><h3>";
if($l["type_arch"]<20)$lib_arch=$type_arch[$l["type_arch"]]." : ";  // sauf pour les archives "autres"
if ($l["lib"]<>"")$content.= $lib_arch.$l["lib"];
if($l["dep_lib"]<>"")$content.=" (".$l["dep_lib"].") ";
$content.=$l["pref"]." ".$l["serie_lib"]." ".$l["suff"]."</h3>";
if ($_SESSION['statut']>7) 
$content.="<a href='../admin/ssserie_edit.php?gofrom=arch_list&limit=0&action=modif&id_fd=".$arch."&sel=".$l["type_arch"]."' target='_blank'>Admin : éditer archive ".$arch."</a>"; 
$content.="</div>";

if($l["dates"]<>"") $content.="<div><b>".$an."</b> : ".$l["dates"]."</div>";   // années

if(trim($l["descr"])<>"") {     //Description
	$content.="<div class='mb-4'>";
    $content.="<b>Description :</b><br>";
    if(strstr($l["descr"],"<")) $content.= $l["descr"];
    else $content.= nl2br($l["descr"]);
    $content.="</div>";
}

$rek="SELECT entity.* FROM entity,`arch-ent` WHERE entity.id=`arch-ent`.ent AND `arch-ent`.arch=".$arch;
try {$ide=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($ide->rowCount()>0){
	$content.="<div class='mb-3'><b>Entité(s) concernée(s) :</b><ul class='ps-3'>";
    while ($le=$ide->fetch(PDO::FETCH_ASSOC)){
        $content.="<li><a href='accueil1.php?disp=entites_";
        switch($le["type_ent"]){
                case 16:
                    $content.="paroisse&ent=".$le["id"];
                    break;
                case 22:
                    $content.="commune&ent=".($le["id"]-100);
                    break; 
                default:
                    $content.="entite&ent=".$le["id"];
        }  
        $content.= "'>";
        if($type_ent[$le["type_ent"]]!="") $content.= ucfirst($type_ent[$le["type_ent"]])." : ";
        $content.= $le["lib"]."</a></b>";
    }
    $content.="</ul></div>";
}

if($l["type_arch"]==1){		/* ****************** CAS DES REGISTRES PAROISSIAUX ************** */
    /**********************  tableau des registres dépouillés  ***************************/
    $content.="<div class='mb-4'><b>Registres conservés :</b> (dépouillés : <img src='../img/rect_dep.png'> , 
        partiellement dépouillés : <img src='../img/rect_p_dep.png'> , non dépouillés : <img src='../img/rect_n_dep.png'>)";
    $content.="<div class='text-center mt-2'><img src='chart_paroissiaux.php?t=p&par=".$l["id1"]."&dep=".$dep."'></div></div>";
}

/* PERSONNES AYANT PARTICIPE AU DEPOUILLEMENT */

$sql="SELECT `intervenant-arch`.*, chercheurs.nom_ch FROM `intervenant-arch`, chercheurs WHERE chercheurs.id_ch=`intervenant-arch`.intervenant AND
`intervenant-arch`.numerisation=0 AND `intervenant-arch`.arch=".$arch;
try{$req=$bdd->query($sql);}
catch(Exception $e) {exit($sql.'<br>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$ni=$req->rowCount();
$i=0;
if($ni>0){
	$content.="<div class='mb-4'><b>Dépouillement :</b> ";
	while($li=$req->fetch(PDO::FETCH_ASSOC)) {
		$content.= "<span style='font-family:monospace;font-weight:bold;'>".$li["nom_ch"]."</span>";
		$i++;
		if($i<$ni) $content.=", ";
		else $content.=" ";
	}
	if($l["depll"]==1) $part="réalisé le";
	else $part="participé au";
	if($ni>1) $content.= "ont ".$part." dépouillement de cette archive.";
	else $content.= "a ".$part." dépouillement de cette archive.";
	
	$sql="select COUNT(*) AS nb FROM individus WHERE `arch`=".$l["id1"]." AND pub=1";
	try {$req=$bdd->query($sql);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	
	$l2=$req->fetch(PDO::FETCH_ASSOC);
	if($l2["nb"]>0 AND $l["id1"]>0){
		$sql="select COUNT(*) AS nb FROM acts WHERE `arch`=".$l["id1"]." AND pub=1";
		$content.=" ";
		try {$req=$bdd->query($sql);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
		$l3=$req->fetch(PDO::FETCH_ASSOC);		// nombre d'actes
		$nb_r=sprintf("%u",$l2["nb"]); //*Ecriture du nombre d'actes
		$l_nb_r=strlen($nb_r);	
		for($e=0;$e<$l_nb_r;$e++) $content.="<img src='../img/c".substr($nb_r,$e,1).".gif' align='absmiddle'>";
		$content.=" individus ont été relevés dans ";
		$nb_r=sprintf("%u",$l3["nb"]); //*Ecriture du nombre d'actes
		$l_nb_r=strlen($nb_r);	
		for($e=0;$e<$l_nb_r;$e++) $content.="<img src='../img/c".substr($nb_r,$e,1).".gif' align='absmiddle'>";
		$content.=" actes.";
		$content.="<br><b>Ce dépouillement est en ligne</b> sous le titre \"";
		if ($l["lib_abx rg"]<>"") $content.= $l["lib_abrg"];
		else $content.= $l["lib"];
		$content.="\".";
	}
    $content.="</div>";
}

if($l["mel_gnn"]!="") $content.="<div class='mb-4'><strong>Certaines composantes de cette archive ont été mises en ligne à fin d'indexation</strong> ; 
	voir <a href='accueil1-sources_hors_tdb.html#".$l["mel_gnn"]."'>ici</a></div>";

$rek="SELECT * FROM ssserie_parts WHERE arch=".$arch." ORDER BY lib";
try {$id1=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($id1->rowCount()>0){		/* détails / composantes pour l'archive */
	$content.= "<div class='mb-4'><b>Détails :</b><br>";
	$id=1;
	while ($l_part=$id1->fetch(PDO::FETCH_ASSOC)) {
		$en_ligne="";
		if($l["depll"]==2 and $l_part["dep"]==1) $en_ligne=" (partie dépouillée et en ligne)";//cas où la partie (cd) est dépouillée alors que l'archive ne l'est pas totalement
		if(strstr($l_part["rem"],"<")) $texte= stripslashes($l_part["rem"]);
		else $texte= stripslashes(nl2br(trim($l_part["rem"])));
		$content.="<div><a data-bs-toggle='collapse' href='#n$id' role='button' aria-expanded='false' aria-controls='n$id' class='toggle'>▶</a> <span  class='fs-4'>"
            .$l_part["lib"]."</span>$en_ligne</div>
			<div class='collapse' style='margin-left:14px;' id='n$id'>".$texte."</div>";
		$id++;
    }
    $content.="</div>";
}

if($l["type_arch"]==1){		/* ****************** CAS DES REGISTRES PAROISSIAUX ************** */
    /**********************  tableau des familles  ***************************/
    $rek_nam="select * from `most_cur_par_names` where PAR_NAME=".$l["id1"]." order by PERCENT_NAME DESC";
    /*$content.= $rek_nam;*/
    try {$id_rn=$bdd->query($rek_nam);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $nb_nam = $id_rn->rowCount();
    if ($nb_nam>0){	
        $w_percent=125;
        $h=7;
        $content.= "<div class='mb-4'><b>Familles les plus fréquentes :</b>
        <table width='80%' border='0' cellspacing='10' align='center'><tr>
        <td width='49%' valign='top'>
        <table width='100%' border='0' cellspacing='0' bgcolor='#CCCC99'><tr>
        <td WIDTH='30%'><b>".$l5."<td width='".$w_percent."'><b>".$l6."<td><b>".$l7."<td><b>".$l8."</td></tr><tr>";

        for ($e=0;$e<$nb_nam/2;$e++) { 
            $l_nam=$id_rn->fetch(PDO::FETCH_ASSOC);
            $percent=$l_nam["PERCENT_NAME"]/100; 
            if($e==0) $max=$percent;
            $content.="<td>".$l_nam["LIB_NAME"]."<td>".
            "<img src='chart_rect.php?h=".$h."&larg=".$w_percent."&max=".$max."&val=".$percent."&write=p'>
            <td>".$l_nam["BEGIN_NAME"]."<td>".$l_nam["END_NAME"]."</td></tr>";
        } 
        $content.="
        </table>
        <td width='2%'>
        <td valign='top'>
        <table width='100%' border='0' cellspacing='0' bgcolor='#CCCC99'><tr>
        <td WIDTH='30%'><b>".$l5."<td width='".$w_percent."'><b>".$l6."<td><b>".$l7."<td><b>".$l8."</td></tr><tr>";
        for ($e=0;$e<$nb_nam/2;$e++) { 
            $l_nam=$id_rn->fetch(PDO::FETCH_ASSOC);
            $percent=$l_nam["PERCENT_NAME"]/100; 
            if($percent>0){
                $content.="<td>".$l_nam["LIB_NAME"]."<td>".
                "<img src='chart_rect.php?h=".$h."&larg=".$w_percent."&max=".$max."&val=".$percent."&write=p'>
                <td>".$l_nam["BEGIN_NAME"]."<td>".$l_nam["END_NAME"]."</td></tr>";
            }
        }
        $content.="</table></table>
        </div>";
    }

    /* STATS SUR LES BAPTEMES */
    $rek="select DISTINCT stats_bap.arch AS arch,stats_bap.ampl from stats_bap WHERE stats_bap.arch=".$l["id1"];
    try {$id_p=$bdd->query($rek);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    if($id_p->rowCount()>0){
        $lp=$id_p->fetch(PDO::FETCH_ASSOC);
        $content.= "<b>Effectifs des baptêmes</b> par tranches de ".($lp["ampl"]+1)." années, et par communauté de domicile des parents : 
        <p style='text-align:center'><img src='chart_bapt.php?arch=".$l["id1"]."'></p>";
    }
}

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>