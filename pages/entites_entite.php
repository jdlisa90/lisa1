<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

extract($_POST,EXTR_OVERWRITE);

$info_illustr=array("ad90"=>"ad90", "armorial d'Hozier"=>"Armorial d'Hozier (1690) ; source : BnF", "wik"=>"Wikipedia", "gallica"=>"BnF");
$lien_illustr=array("gallica"=>"http://gallica2.bnf.fr/", "ad90"=>"http://www.archives.cg90.fr/?id=documents_figures");  
$dossier_illustr=array("armorial d'Hozier"=>"/Armorial_dHozier","illustrations pour entités"=>"/illustrations-entites","revue Alsace 1850"=>"/rev_A_1850");

$rek="SELECT DISTINCT * FROM entity WHERE id=$ent";
try {$idf=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$lf=$idf->fetch(PDO::FETCH_ASSOC);
$idf->closeCursor();

if($lf["souverainete"]!=0){
    $sql="SELECT titre, lib FROM entity WHERE id=".$lf["souverainete"];
    try {$req=$bdd->query($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $ls=$req->fetch(PDO::FETCH_ASSOC);
    $link_souv="<a href='accueil1-entites_entite-ent-".$lf["souverainete"].".html' class='fs-3'>".$ls["titre"]." ".$ls["lib"]."</a> > ";
}

$sql="SELECT illustrations.* FROM illustrations,`illustr-page` WHERE illustrations.id=`illustr-page`.illustr AND `illustr-page`.id_page=".$ent;
try {$req_illustr=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$rek="SELECT ss.*, arch_dep.lib AS dep_lib, arch_dep.type AS dep_typ,arch_serie.lib AS serie_lib 
		FROM arch_ssserie AS ss,arch_dep,arch_serie,`arch-ent` 
		WHERE ss.dep=arch_dep.id AND ss.serie=arch_serie.id AND ss.id=`arch-ent`.arch AND `arch-ent`.ent=".$lf["id"].
		" ORDER BY type_arch";
try {$ida=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}   // archives

$content="<div class='row justify-content-center'>
<div class='col-10 pt-4 pb-4'>";
$content.="<div class='mb-4'>";
$content.="<h3>$link_souv".$lf["titre"].$lf["lib"]."</h3>";
$content.="</div>";

if($req_illustr->rowCount()>0){
    $content.="<figure class='ps-2 float-end text-center' style='max-width:55%;'>"; // <figure> ne sert pas à grand chose
    WHILE($l_ill=$req_illustr->fetch(PDO::FETCH_ASSOC)){
        if(isset ($lien_illustr[$l_ill["lien"]])) $content.="<a href='".$lien_illustr[$l_ill["lien"]]."' target='blank'>            
            <img src='../docs".$dossier_illustr[$l_ill["info"]]."/".$l_ill["img"]."'></a>"; // lien externe sur l'image
        else $content.="<img src='../docs".$dossier_illustr[$l_ill["info"]]."/".$l_ill["img"]."' style='max-width:100%;'>"; // image seule
        $content.="<figcaption class='fst-italic small  mb-3'>".$info_illustr[$l_ill["info"]]."
        </figcaption>";
    }// "info" contient à la fois la key du dossier et de l'info
    $content.="</figure>";
}  

if(trim($lf["descr"])<>'') $content.= "<div class='mb-4'>".$lf["descr"]."</div>";
if(trim($lf["comment"])<>'') $content.= "<div class='mb-4'>".$lf["comment"]."</div>"; // à faire : modifier les liens dans certaines descriptions (en part.
// comté Belfort)

if($type_ent_list_comm[$lf["type_ent"]]>0){ // pour certaines entités ne pas afficher la liste des communes
    $rek="SELECT entity.lib,entity.id,`ent-comm`.comment FROM entity,`ent-comm` WHERE type_ent=22 AND entity.id=`ent-comm`.comm AND 
            	`ent-comm`.ent=".$lf["id"]." ORDER BY lib";
    try {$idc=$bdd->query($rek);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} 
    if($idc->rowCount()>0){
        $content.= "<div class='mb-4'><b>Communes</b> relevant du territoire de cette juridiction :<ul class='ps-3'>";
        while ($lc=$idc->fetch(PDO::FETCH_ASSOC)){
            $content.= "<li><a href='accueil1.php?disp=entites_commune&ent=".($lc["id"]-100)."'>".
            html_entity_decode($lc["lib"])."</a>";
            if(trim($lc["comment"])<>'') $content.=" (".$lc["comment"].")";
            $content.="</li>";
        }
        $idc->closeCursor();
        $content.="</ul>";
        $content.="</div>";
    }    
}

/*   Archives */	
if($ida->rowCount()>0){
    $content.="<h4>Archives relevant de cette juridiction:</h4>";
    $content.="<ul class='ps-3'>";
    while ($la=$ida->fetch(PDO::FETCH_ASSOC)){
        if($lib_arch==$la["lib"]AND $la["lib"]<>"") $aff_lib="id.";  // on conserve une présentation en liste à cause des grosses images pouvant être affichées à droite
        else{
                $aff_lib=$la["lib"];
                $lib_arch=$la["lib"];
        }
        $content.= "<li>".$type_arch[$la["type_arch"]]." : <br>";
        $content.= "<a href='accueil1.php?disp=sources_archive&arch=".$la["id"]."'>".$aff_lib."</a>";
        $content.=" (".$la["dep_lib"].") ".$la["pref"]." ".$la["serie_lib"]." ".$la["suff"];	
        if($la["dates"]<>'')$content.= " : ".$la["dates"];
        $content.="<br>";
        if ($la["cot_dep"]<>''){
                $content.="<b><i>Dépouillements réalisés :</i></b> ";
                $content.= $la["cot_dep"];				
        }
    }
    $ida->closeCursor();
}

// les titulatures et charges ne sont pas affichées
$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>