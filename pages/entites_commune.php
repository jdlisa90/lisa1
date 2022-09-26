<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
extract($_POST,EXTR_OVERWRITE);
$l10="Sources disponibles pour le notariat";
$l11="Recensements à ";
$l23="Autres fonds";
$l30="Notabilités d'ancien régime à ";
$tit_charges="Titulatures et charges dans cette commune";
$fam="Familles : ";

$dep1=array(2=>"partiellement", 1=>"entièrement");

$rek="select * from locality where ID_LOC ='".$ent."'";             // COMMUNE
try {$id_rk=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$id_rk->fetch(PDO::FETCH_ASSOC);

$rek_par="select * from `locality_parish` where id_loc=".$ent;      // PAROISSES
try {$id_rp=$bdd->query($rek_par);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$nb_par = $id_rp->rowCount();	   

$rek_trch="select DISTINCT chercheurs.*,travaux.* from `chercheurs` LEFT JOIN `travaux` ON chercheurs.id_ch=travaux.idch_tr 
	LEFT JOIN trav_com ON trav_com.trav=travaux.id_tr WHERE trav_com.comm=".$l["ID_LOC"];
try {$id_trch=$bdd->query($rek_trch);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} // CHERCHEURS
$nb_trch = $id_trch->rowCount();	   

$rek_c="select census.fichier, census.titre from cens_loc, census where cens_loc.loc='".$ent."' AND census.NUM=cens_loc.census ORDER BY `index`"; //recensements anciens
try {$id_rc=$bdd->query($rek_c);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} 														

$sql="SELECT illustrations.* FROM illustrations,`illustr-page` WHERE illustrations.info='illustrations pour entités' 
AND illustrations.id=`illustr-page`.illustr AND `illustr-page`.id_page=".($ent+100)." LIMIT 1";
try {$req_illustr=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

// recherche de cartes avant-après pour la commune
$sql="SELECT `cp_anc`.fichier AS im1,`cp_illustr`.fichier AS im2 FROM `cp_anc`,`cp_illustr` WHERE `cp_illustr`.cp=`cp_anc`.id AND commune='".$l["INSEE_LOC"]."' LIMIT 1";
try {$id_cp=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib 
FROM arch_ssserie AS ss ,arch_dep,arch_serie  
WHERE arch_serie.id=ss.serie AND arch_dep.id=ss.dep AND ss.id1=".($ent +100)." 
ORDER BY arch_dep.id, serie, pref, suff, ss.id";
try {$id_arch=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} // ARCHIVES (les seules référencées dans arch_ssserie sous le n° de la commune)

/* sélection des AUTRES archives  (en dehors EC) */
$rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib FROM arch_ssserie AS ss
 ,arch_dep,arch_serie,`ent-comm`,`arch-ent` WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND 
 ss.id=`arch-ent`.arch AND `arch-ent`.ent=`ent-comm`.ent AND `ent-comm`.comm=".($ent +100)." ORDER BY ss.type_arch, ss.lib, ss.id";
try {$id_arch1=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$nb_arch1=$id_arch1->rowCount();

// sélection des entités pour la commune
$sql="SELECT lib, cat_ent,type_ent, `ent-comm`.comment, `ent-comm`.ent FROM entity, `ent-comm` WHERE entity.id=`ent-comm`.ent AND type_ent<>16 AND type_ent<>22
 AND type_ent<>7   AND `ent-comm`.comm=".($ent +100)." ORDER BY type_ent"; // exclus : paroisse / commune / mairie seigneuriale
try {$id_ent=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-12 pt-3 pb-4 no-wrap'>";
$content.="<h3>Commune : ".$l["LIB_LOC"]."</h3>";
$content.="<div class='row justify-content-between d-flex'>
<div class='col'>";
if($req_illustr->rowCount()>0){     // illustration principale
    $l_ill=$req_illustr->fetch(PDO::FETCH_ASSOC);
    $content.="<div class='mb-3'>";
    if($l_ill["lien"]<>"") $content.="<a href='".$l_ill["lien"]."' target='_blank'>            
            <img src='../docs".$dossier_illustr[$l_ill["info"]]."/".$l_ill["img"]."' style='max-width:550px;max-height:550px;'></a>";
    else $content.="<img src='../docs".$dossier_illustr[$l_ill["info"]]."/".$l_ill["img"]."' style='max-width:600px;max-height:600px;'>";
    if(!empty($l_ill["legende"])) $content.="<br><span class='small fst-italic'>".$l_ill["legende"]."</span>";
    $content.="</div>";
}

if($id_cp->rowCount()>0){   // 1 carte postale avant-après, s'il y a
	$l_cp=$id_cp->fetch(PDO::FETCH_ASSOC);
    $content.="<div class='mb-3'>";
	$content.="<b>Cartes postales anciennes \"avant-après\"</b> pour ".$l["LIB_LOC"]."<br>
	<a href='accueil1.php?disp=cartes-postales?id=".$l["INSEE_LOC"]."'>
    <img src='../cp_anc/".$l_cp["im1"].".jpg' style='height:50px;'>&nbsp;<img src='../cp_illustr/".$l_cp["im2"].".jpg' style='height:50px;'></a>";
    $content.="</div>";
}

$content.="<div class='mb-3' id='paroisses'>"; // paroisses
if ($nb_par>1)$content.="Commune dépendant des <b>paroisses</b> de :";
else $content.= "Commune dépendant de la <b>paroisse</b> de :";
$content.="<ul class='ps-3'>";
while ($lp=$id_rp->fetch(PDO::FETCH_ASSOC)){
    $content.="<li><a href='accueil1.php?disp=entites_paroisse&ent=".$lp["id_par"]."'>".$lp["PARISH_LIB_LOC_PAR"]."</a>";
    if (strlen($lp["COMMENT_LOC_PAR"])>1) $content.=" : ".$lp["COMMENT_LOC_PAR"];
}
$content.="</ul></div>";

//Autres juridictions
if($id_ent->rowCount()>0){
    $content.="<div class='mb-3'>Les habitants de ".$l["LIB_LOC"]." relèvent, ou ont relevé, des <b>juridictions</b> suivantes :<ul class='ps-3'>";
    while($l_ent=$id_ent->fetch(PDO::FETCH_ASSOC)){
        if ($type_ent_public[$l_ent["type_ent"]]>0){ // certaines entités ne doivent pas apparaitre, d'autres apparaissent, mais ne sont pas linkées
            if ($type_ent_linked[$l_ent["type_ent"]]>0) $content.="<li><a href='accueil1.php?disp=entites_entite&ent=".$l_ent["ent"]."'>
                ".$type_ent[$l_ent["type_ent"]]." : ".$l_ent["lib"]."</a>"; 
            else $content.="<li>".$type_ent[$l_ent["type_ent"]]." : ".$l_ent["lib"];
            if($l_ent["comment"]<>'')$content.= " (".$l_ent["comment"].")";
        }
    }
    $content.="</ul></div>";
}

$content.= "<div class='mb-3'>Code Insee : ".$l["INSEE_LOC"]."<br>";
if(strlen($l["ZIPCODE_LOC"])>1) $content.= "Code postal : ".$l["ZIPCODE_LOC"]."<BR>";
if(strlen($l["ORIGIN_LOC"])>1) $content.= "Première mention : ".$l["ORIGIN_LOC"]."<br>";
if(strlen($l["OTHER_NAME_LOC"])>1) $content.= "Autre(s) forme(s) : ".$l["OTHER_NAME_LOC"]."<br>";
if(strlen($l["INCLUDE_LOC"])>1) $content.= "Incluant  le(s) lieu(x)-dit ou communauté(s) de : ".$l["INCLUDE_LOC"];
if(strlen($l["commentfr_loc"])>1) $content.= "<br>".$l["commentfr_loc"];
$content.="</div>";


/* DEPOUILLEMENTS ET PERSONNES Y AYANT PARTICIPE */
$sql="SELECT arch_ssserie.depll, arch_ssserie.cot_dep, arch_ssserie.an_dep FROM arch_ssserie WHERE arch_ssserie.id1=".($ent +100);
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l_dep=$req->fetch(PDO::FETCH_ASSOC);
$req->closeCursor();
if($l_dep["depll"]>0){
    $content.="<div class='mb-3'>L'état-civil de cette commune est <span class='bold'>".$dep1[$l_dep["depll"]]." dépouillé.</span>";
    if($l_dep["depll"]==2) $content.=" Partie dépouillée : ".$l_dep["cot_dep"].".";
    $content.="<br>Membre(s) de LISA ayant participé à ce dépouillement : ";
    $sql="SELECT SURN_MBR, NAME_MBR,`memb-arch`.action, arch_ssserie.depll, arch_ssserie.cot_dep, arch_ssserie.an_dep 
    FROM member, `memb-arch`, arch_ssserie WHERE member.n=`memb-arch`.memb AND `memb-arch`.arch=arch_ssserie.id 
    AND arch_ssserie.id1=".($ent +100)." AND action <>2 ORDER BY action, SURN_MBR";
    try {$req=$bdd->query($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $nb_dep=$req->rowCount();
    $m_action=array(2=>" (numérisation)");
    for($e=0;$e<$nb_dep;$e++){
        $l_dep=$req->fetch(PDO::FETCH_ASSOC);
        $content.= $l_dep["NAME_MBR"]." ".$l_dep["SURN_MBR"].$m_action[$l_dep["action"]];
        if($e<$nb_dep-1) $content.=", ";
    }
$content.="</div>";
}
$content.="</div>";     // fin de la 1ère colonne
// Plan de finage en float right
if(file_exists("../finages/".$l["INSEE_LOC"].".jpg")){
    $content.="<div class='col ps-2 mb-1 fst-italic small flex-grow-0'><img src='../finages/".$l["INSEE_LOC"].".jpg'>";
    if(!empty($l["finage_loc"])) $content.= "<br>Plan de finage (ad68, ".$l["finage_loc"].", copie aux ad90)";
    $content.="</div>";
}

$content.="<h3 class='mt-3'>Archives relatives aux habitants de ".$l["LIB_LOC"]."</h3>";

// archives communales
$content.="<div class='mt-2 mb-1'><b>Fonds communaux :</b>
    <table class='table table-sm'>
    <col width='300'>
    <col>
    <col width='100'>";
while($la1=$id_arch->fetch(PDO::FETCH_ASSOC)){
    $content.="<tr><td>".$la1["dep_lib"]." ".$la1["pref"]." ".$la1["serie_lib"]." ".$la1["suff"].
            "<td>".$type_arch[$la1["type_arch"]]." : <a href='accueil1.php?disp=sources_archive&arch=".$la1["id"]."'>".$la1["lib"]."</a>
            <td>".$la1["dates"];
}
$content.="</table></div>";

// fonds paroissiaux
$content.="<div class='mt-1 mb-3'><b>Fonds paroissiaux :</b> voir la ou les paroisses <a href='#paroisses'>ci-dessus</a></div>";

// autres archives
if($nb_arch1>0){
    $content.="<div class='mt-1 mb-1'><b>Autres fonds :</b>
    <table class='table table-sm'>
    <col width='300'>
    <col>
    <col width='100'>";
    while($la1=$id_arch1->fetch(PDO::FETCH_ASSOC)){
	$content.="<tr><td><span class='bold'>".$la1["dep_lib"]."</span>: ".$la1["pref"]." ".$la1["serie_lib"]." ".$la1["suff"]
            ."<td>";
        $descr="";
        if($la1["type_arch"]<20)$content.=$type_arch[$la1["type_arch"]]."</span>: ";
        if($la1["lib"]=="")$descr.=$la1["descr"];
        else $descr.=$la1["lib"];
        $content.= "<a href='accueil1.php?disp=sources_archive&arch=".$la1["id"]."'>".$descr."</a><td>".$la1["dates"];
    }
    $content.="</table></div>";
}

$content.="<h3 class='mt-3'>Travaux personnels concernant les habitants de ".$l["LIB_LOC"]."</h3>

<div class='mt-2 mb-2' id='patros'>Il s'agit ici de l'INDEXATION de travaux réalisés par différents chercheurs.
  <br>LISA n'assure pas la présentation du contenu de ces travaux. Pour en prendre connaissance, il vous faudra prendre contact avec le chercheur, par notre intermédiaire.";
if ($nb_trch>0){
    $content.="<table class='table table-sm mt-3'>
    <col width='180'>
    <col width='380'>
    <col>";
    while ($lt=$id_trch->fetch(PDO::FETCH_ASSOC)){
    	$content.="<tr><td style='width:9em;'><a href='accueil1-prod_travaux_chercheur-chercheur-".$lt["id_ch"].".html'>".$lt["nom_ch"]."</a>
    		<td>".stripslashes($lt["tit_tr"])."<td>";
      $rek_f="select famil_trav.* from `famil_trav` where famil_trav.idtr_ft=".$lt["id_tr"];
      try {$id_f=$bdd->query($rek_f);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    	$nb_f= $id_f->rowCount();
    	for ($f=0;$f<$nb_f-1;$f++) {
            $lf=$id_f->fetch(PDO::FETCH_ASSOC);
            $content.= $lf["famil_ft"].", ";
      }
      $lf=$id_f->fetch(PDO::FETCH_ASSOC);
      $content.= $lf["famil_ft"];
    }
    $content.="</table>";
}
$content.="</div>";
if (isset($_SESSION["authentification"])) $content.="<div class='mt-2 mb-2'><a href='accueil1.php?disp=session_travaux&com=".$l["ID_LOC"]."'><b>"
."Indexer un travail concernant ".$l["LIB_LOC"]."</a></b>";
$content.="</div>";

$content.="</div></div>";            // fermeture colonne principale

$content.="</div>"; // fermeture row contenant
?>