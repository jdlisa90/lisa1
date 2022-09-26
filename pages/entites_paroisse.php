<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$l2="Paroisse";
$l3="";
$l4s="";
$l4m=" dont relèvent les communes de : ";
$fam="Familles : ";
$back="Retour";	
$listarch="Archives relevant de cette entité:";
$charges="Titulatures et charges dans cette paroisse:";
extract($_POST,EXTR_OVERWRITE);
$rek="select * from parish where ID_PAR ='".$ent."'";                   // PAROISSE
try {$id_rk=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$p=$id_rk->fetch(PDO::FETCH_ASSOC);

$sql="SELECT illustrations.* FROM illustrations,`illustr-page` WHERE illustrations.id=`illustr-page`.illustr AND `illustr-page`.cat_page='paroisse' 
	AND `illustr-page`.id_page=".$ent;
try {$req_illustr=$bdd->query($sql);}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} // ILLUSTRATIONS 

$rek="SELECT entity.id,entity.lib,`ent-comm`.comment FROM entity,`ent-comm` WHERE entity.id=`ent-comm`.comm AND `ent-comm`.ent='".$ent."' ORDER BY lib";
try {$idc=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$nbc = $idc->rowCount();                                                    // COMMUNES
if($idc->rowCount()>1) $comm="relèvent les <b>communes</b>";
else $comm="relève la <b>commune</b>";

$rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib 
		FROM arch_ssserie AS ss,arch_dep,arch_serie,`arch-ent` 
		WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND ss.id=`arch-ent`.arch AND `arch-ent`.ent=".$ent." ORDER BY type_arch, ss.id";
try {$ida=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} //ARCHIVES

$sql="SELECT fiefs_charges.* FROM fiefs_charges,entity,`arch-ent`,arch_ssserie WHERE fiefs_charges.ent=entity.id AND `arch-ent`.arch=arch_ssserie.id 
		and arch_ssserie.id1=".$ent;                                    //TITULAIRES DE CHARGES ET OFFICES
$sql="SELECT fiefs_charges.* FROM fiefs_charges WHERE fiefs_charges.ent=".$ent;     // laquelle des 2 requêtes ???
try {$idch=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-8 pt-4 pb-4'>"; // row contenant et colonne principale
$content.="<div class='mb-4'>";
$content.="<h3>Paroisse : ".$p["LIB_PAR"]."</h3>";

if($req_illustr->rowCount()>0){     // comme pour toutes les entités
    WHILE($l_ill=$req_illustr->fetch(PDO::FETCH_ASSOC)) {
        // actuellement il n'y a pas d'illustration pour les paroisses
    }
}
/*GENERALITES PAROISSE*/
if ($p["DIOC_PAR"]<>'') $content.= "<b>Diocèse : </b>".$p["DIOC_PAR"];
$content.="</div>";

$content.="<div class='mb-4'>";
if(strlen($p["VOC_PAR"])>1) $content.= "<b>Paroisse </b> sous le vocable ".$p["VOC_PAR"]." dont $comm de : ";
else $content.= "<b>Paroisse </b> dont $comm de : ";
$content.="<ul class='ps-3'>";                            // communes
while ($lc=$idc->fetch(PDO::FETCH_ASSOC)){
    $content.= "<li><a href='accueil1.php?disp=entites_commune&ent=".($lc["id"]-100)."'>".html_entity_decode($lc["lib"])."</a>";
    if(strlen($lc["comment"])>1) $content.=" : ".html_entity_decode($lc["comment"]);
}
$content.="</ul>";
$content.=$p["COMMENT_PAR"];
$content.="</div>"; 

				/************	Titulatures et charges	********/
/* if($idch->rowCount()>0){
    $content.= "<h2>".$charges."</h2>";
    $content.= "<ul class='noindent'>";
    while ($lt=$idch->fetch(PDO::FETCH_ASSOC)){
        $sql="SELECT DISTINCT nom FROM fiefs_titul,`fiefs_charges-titul` 
            WHERE `fiefs_charges-titul`.fief_charge=".$lt["id"]."
            AND `fiefs_charges-titul`.titul=fiefs_titul.id ORDER BY ordre";
        try {$req_i=$bdd->query($sql);}
        catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $nb_i = $req_i->rowCount();	
        $ch_noms="";
        for($g=0;$g<$nb_i;$g++) {
            $lI=$req_i->fetch(PDO::FETCH_ASSOC);
            $ch_noms.=$lI["nom"]." ; ";
        }		
        $content.= "<li>";
        if ($ch_noms<>"") $content.= "<b><a href='titulaires_charge-".$lt["id"]."-".$ent.".html'>".ucfirst($lt["lib"])."</a><b>";
        else $content.= ucfirst($lt["lib"]);

        if ($ch_noms<>"" or $lt["full"]<1) $content.= " (";
        if($lt["cat"]==1 and $ch_noms<>"")$content.= $fam;
        $content.= substr($ch_noms, 0, -3);
        if($lt["full"]<1)$content.= " ---".$en_constr2."---";
        if ($ch_noms<>"" or $lt["full"]<1) $content.=")";
        
        if($lt["rem"]<>"") $content.= " (".$lt["rem"].")";
    }
    $content.="</ul>";
} */

if($ida->rowCount()>0){
    $content.="<div class='mb-4'><h4><br>Archives relevant de cette paroisse :</h4>
    <table class='table table-sm'>
    <col width='250'>
    <col>
    <col width='100'>";
    while ($la1=$ida->fetch(PDO::FETCH_ASSOC)){
	    $content.="<tr><td>".$la1["dep_lib"]."</span>: ".$la1["pref"]." ".$la1["serie_lib"]." ".$la1["suff"]
            ."<td>";
        $descr="";
        if($la1["type_arch"]<20)$content.=$type_arch[$la1["type_arch"]]."</span>: ";
        if($la1["lib"]=="")$descr.=$la1["descr"];
        else $descr.=$la1["lib"];
        $content.= "<a href='accueil1.php?disp=sources_archive&arch=".$la1["id"]."'>".$descr."</a><td>".$la1["dates"];
    }
    $content.="</table></div>";
}

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant
?>