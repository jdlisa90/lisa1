<?php
                
$content="<div class='row justify-content-center'>
<div class='col-9 pt-4 pb-4'>"; // row contenant et colonne principale
$content.="
<div class='border border-2 border-secondary ps-3 pe-3 pt-2 mb-3' style='width:520px'>
<ul>
<li><a href='#a1'>Sources dépouillées et intégrées à la base de données</a></li>
<li><a href='#a2'>Sources en ligne au format pdf</a></li>
<li><a href='#a3'>Recensements en ligne au format html</a></li>
</ul>
</div>";
$content.="<h4 id='a1'>Sources dépouillées et intégrées à la base de données</h4>";
$type_keys=array_keys($type_arch);
foreach($type_keys as $value){
    $rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib FROM arch_ssserie AS ss
     ,arch_dep,arch_serie WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND type_arch=".$value." AND depll IN (1,2,4) AND id1<>0 ORDER BY ss.type_arch, ss.lib";
	try {$id=$bdd->query($rek);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    if($id->rowCount()>0) {
        $cols="";
        $type_ent="";
        $content.="<div class='row pt-1 pb-1'><div class='col-4'><b>".$type_arch[$value]."</div>";
        if ($value==1) $type_ent="paroisse";
        if ($value==2) $type_ent="commune";
        if($type_ent!="") $content.="<div class='col'><a href='accueil1.php?disp=entites_".$type_ent."s_carte'>Carte des ".$type_ent."s</a></div>";
        $content.="</b></div>";
        $content.="<div class='row pb-2'>";
        if($id->rowCount()>20) $cols="class='col-4'";
        while($l=$id->fetch(PDO::FETCH_ASSOC)){
            $content.= "<div $cols><a href='accueil1.php?disp=sources_archive&arch=".$l["id"]."'>".$l["lib"]."</a>";
            if($l["depll"]>1 and $l["cot_dep"]<>'') {
                $content.=" (";
                if ($l["depll"]==2) { //cas des dépouillements partiels
                    $content.= $l["cot_dep"];
                }
                $content.=")";
            }
            $content.="</div>";
        }
        $content.="</div>";
    }
}

// ******************
$rek="SELECT * FROM dep_divers WHERE public=1 ORDER BY link";
try {$id_rk=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$content.="<br><h4 id='a2'>Sources en ligne au format pdf</h4>
Le lien conduit au dépouillement :";
$content.="<div class='row pb-2'>";
while($l=$id_rk->fetch(PDO::FETCH_ASSOC)) {
    $content.="<a href='".$l["link"]."' target='_blank'>".$l["lib"]."</a><br>";
}
$content.="</div>";

// ****************** Recensements au format html
$rek="select census.* from cens_loc, census where census.NUM=cens_loc.census AND public=1 ORDER BY cens_loc.loc,census.titre";
try {$id_rc=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

while($lc=$id_rc->fetch(PDO::FETCH_ASSOC)){ // nécessaire pour que chaque dépouillement soit affiché une seule fois
	$file[]=$lc["fichier"];			// noms des fichiers		
}
$nb_file=array_count_values($file);		// constitue un tableau contenant les  noms des fichiers comme clés et leur occurrence (1 ou plus) comme valeur.

$rek="select distinct census.* from cens_loc, census where census.NUM=cens_loc.census AND public=1 ORDER BY cens_loc.loc,census.titre";
try {$id_rc=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$content.="<br><h4 id='a3'>Recensements en ligne au format html</h4>";
$content.="<div class='row pb-2'>";
while($lc=$id_rc->fetch(PDO::FETCH_ASSOC)){
    $content.="<div>";
    if($nb_file[$lc["fichier"]]==1){				// cas où le fichier apparait une seule fois
		$rek="select LIB_LOC from locality,cens_loc where locality.ID_LOC=cens_loc.loc AND cens_loc.census=".$lc["NUM"];
		try {$id_l=$bdd->query($rek);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
		$ll=$id_l->fetch(PDO::FETCH_ASSOC);
		$content.="<a href='accueil1.php?disp=sources_recensement&arch=".$lc["fichier"]."'>".$lc["titre"]."</a> (".$ll["LIB_LOC"].")";
	}
	else {									// si plusieurs occurrences (on ne recherche pas le lieu)
		$content.="<a href='accueil1.php?disp=sources_recensement&arch=".$lc["fichier"]."'>".$lc["titre"]."</a>";
	}
    $content.="</div>";
}
$content.="</div>";

// ******************
$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant
?>