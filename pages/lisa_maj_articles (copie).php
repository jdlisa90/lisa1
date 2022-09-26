<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

include("../include/types1.inc");
$sql="SELECT histo.num AS numh,histo.lien AS llien, date_maj,illustration, lien_ent, illustrations.* FROM histo,illustrations WHERE illustrations.id=histo.illustration AND sect=1
	AND illustration<>0 AND info='illustrations pour entités' ORDER BY date_maj DESC LIMIT 1";
try {$id_maj=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l_maj=$id_maj->fetch(PDO::FETCH_ASSOC);

$sql="SELECT type_arch,ss.lib, pref, an_dep,suff, arch_serie.lib AS serie, arch_dep.lib AS dep FROM arch_ssserie AS ss,arch_dep,arch_serie
    WHERE ss.id=".$l_maj["llien"]." AND ss.dep=arch_dep.id AND ss.serie=arch_serie.id"; // lien représente l'id de arch_ssserie
try {$id_arch=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l_arch=$id_arch->fetch(PDO::FETCH_ASSOC);     // récupère le parametre de l'entité à afficher dans les pages entity1, commune1 ou paroisse1
                                            //, son type et son libellé + le dépot et la cote de l'archive
$lien="entity1";

setlocale (LC_TIME, 'fr_FR','fra');
date_default_timezone_set("Europe/Paris");
mb_internal_encoding("UTF-8");
                                                             // AFFICHAGES
$content="<div id='maj' class='row mt-3 pb-3'> <!-- BLOC des mises à jour -->
    <div class='col-6 ps-0'>
        <div class='row pb-2'>
            <div class='fs-4'>DERNIÈRE MISE À JOUR : <a href='accueil1-sources_archive-arch-".$l_maj["llien"].".html'>"
            .$l_arch["lib"]."&nbsp;".$l_arch["dep"]." ".$l_arch["pref"]." ".$l_arch["serie"]." ".$l_arch["suff"]."</span>
            &nbsp;-&nbsp;".$type_arch[$l_arch["type_arch"]]."</a></div>";
if($l_arch["an_dep"]<>"") $content.="<div>Dépouillement : ".$l_arch["an_dep"]."<div>";							// partie dépouillée (le cas échéant)
if($l_maj["lien_ent"]!="0"){																									// modification pour accepter les liens entités multiples, séparés par /
    $ent=$l_maj["lien_ent"];
    $ent_=explode("/", $ent);																									// tableau contenant les divers liens
    $ent_txt="";
    foreach ($ent_ as $ent){
       $sql="SELECT type_ent, lib, titre FROM entity WHERE id=".$ent;
	    try {$ide=$bdd->query($sql);}
		catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
	    $le=$ide->fetch(PDO::FETCH_ASSOC);
	    switch($l_arch["type_arch"]){
        case 1:
                $lien="paroisse";
                break;
        case 2:
                $lien="commune";
                $ent-=100;
                break;
        default:
                $lien="entite";
    	}           // détermination de la page et de l'id de l'entité géographique à lier
	    $ent_txt.="<a href='accueil1-entites_".$lien."-ent-".$ent.".html'>".$le["lib"];
	    if($le["titre"]!="") $ent_txt.=" (".rtrim($le["titre"]).")";
	    $ent_txt.="</a> / ";
    }
    $ent_txt=substr($ent_txt,0,-3);
    if(strpos($ent_txt, " / ")) $content.="<div>Entités concernées : ".$ent_txt;
    else $content.="<div>Entité concernée : ".$ent_txt;
    $content.="</div>";
}
///if($l_maj["comment_aff"]!="") $content.= "<div>".$l_maj["comment_aff"]."</div>";
$content.="<div>En ligne depuis le ".strftime('%d-%m-%Y',strtotime($l_maj["date_maj"]));
$content.="&nbsp;&nbsp; |  &nbsp;&nbsp;<a href='accueil1-lisa_historique.html'><b>Voir toutes les mises à jour</b></a></div>";
$content.="</div>
        <div class='row'>
            <div class='col'>";
$chemin="../docs".$dossier_illustr[$l_maj["info"]]."/".$l_maj["img"];
$content.="<img src='$chemin' id='image_last_maj' style='max-width:90%;max-height:200px;width: auto;'></div></div>";    // image dernière mise en ligne
if(!empty($l_maj["lien"])) $content.="<a href='".$l_maj["lien"]."' target='_blank'><span class='small fst-italic'>".$l_maj["legende"]."</span></a>";
else $content.="<span class='small fst-italic'>".$l_maj["legende"]."</span>";
$content.="</div>";


//	création de listes formées des 2 catégories d'articles, par date décroissante de dernière mise en ligne
// temp2 : tous articles, par année début (per1) croissante -> select : un champ ordre reprend le champ ordre de pages_supp et le champ per1 de articles -> liste déroulante
// temp_y : nouveaux articles, avec années, à convertir en période(s) pour temp
// temp : tous articles, par date décroissante de dernière mise en ligne -> Derniers articles
// temp1 : copie de temp, dont les 5 premiers articles sont éliminés -> listes par périodes

$per_y=array(1=>array(0,1324), 5=>array(1325,1648), 10=>array(1649,1792), 15=>array(1793,1871), 20=>array(1871,2100)); // pour conversion années->catégories (nécessaire pour adaptation articles anciens)
// création de temp et temp1 et temp_y
try {$bdd->query("create temporary table temp(id int not null, maj date, cat tinyint, periode tinyint)");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
try {$bdd->query("create temporary table temp1 LIKE temp");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
try {$bdd->query("create temporary table temp_y(id int not null, maj date, y1 smallint, y2 smallint)");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

// création et remplissage de temp2
try {$bdd->query("create temporary table temp2(`id` INT NOT NULL , `cat` BOOLEAN NOT NULL , `titre` TEXT NOT NULL , `an1` SMALLINT NOT NULL , `an2` SMALLINT NOT NULL , `ordre` FLOAT(5,1) NOT NULL)");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$sql="INSERT INTO temp2(id, cat, titre, an1, an2, ordre) SELECT num, 0, titre_fr, per1, per2, ordre FROM pages_supp WHERE pub=1"; // tous les nouveaux articles validés
try {$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$sql="INSERT INTO temp2(id, cat, titre, an1, an2, ordre) SELECT id, 1, titre, per1, per2, per1 FROM article WHERE pub=1"; // tous les anciens articles validés
try {$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

// remplissage de temp_y avec les nouveaux articles (années)
$sql="INSERT INTO temp_y(id, maj, y1, y2) SELECT id, modif, per1, per2 FROM article WHERE";  // per1 et per2 des nouveaux articles sont des années !
if (!isset($_SESSION["authentification"]) OR $_SESSION['statut']<7) $sql.=" pub=1";
else $sql.=" pub>0";		// les admin peuvent voir les articles avec pub=1 ou 2 (à l'essai)
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
// remplissage de temp pour les nouveaux articles, par conversion des années de temp_y en périodes
foreach($per_y as $per=>$y){	// filtrage par années pour les 5 périodes (un même article peut entrer dans plusieurs catégories)
	$sql="INSERT INTO temp(id, maj, cat, periode) SELECT id, maj, 1, $per FROM temp_y WHERE (y1>=".$y[0]." AND y1<=".$y[1]." OR y1<=".$y[0]." AND y2>=".$y[1]." OR y2>=".$y[0]." AND y2<=".$y[1].")";
	try {$req=$bdd->query($sql);}
	catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
}
// on complète temp avec les anciens articles, en utilisant la table article-periode
$sql="INSERT INTO temp(id, maj, cat, periode) SELECT pages_supp.num, date_maj as maj, 0,periode from pages_supp,`article-periode` WHERE `article-periode`.article=pages_supp.num AND new=0 AND pub=1";
try {$req=$bdd->query($sql);}		//anciens articles
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$sql="SELECT id FROM temp ORDER BY maj desc limit 5"; // 5 DERNIERs ARTICLEs
try {$req=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$l=$req->fetch(PDO::FETCH_ASSOC);

                                                       
//                  ******  Dernier article
$sql="SELECT `id`,`titre`,`abstract`,`image`,`ref_image`,`id_arch`,`lib_arch`,mel, sign_courte, modif, year(`mel`) AS y0, year(`modif`) AS y1 FROM article WHERE id=".$l["id"];
try {$id_art=$bdd->query($sql);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
$l_art=$id_art->fetch(PDO::FETCH_ASSOC);
$last_article=$l["id"];

$content.="<div class='col-6  ps-4 pb-0' style='border-left: 1px solid var(--light-bg-color);'>
<div class='row pb-2'><div class='fs-4'>DERNIER ARTICLE : ";

$content.="<a href='accueil1-article_nouveau-article-".$l_art["id"].".html' target='_blank'>".strip_tags($l_art["titre"])."</a></div>";    // titre
if(!empty($l_art["abstract"]))$content.="<div>".strip_tags($l_art["abstract"]);
if(!empty($l_art["sign_courte"])) $content.="<br>Par<i> ".strip_tags($l_art["sign_courte"])."</i>";
if($l_art["y0"]!="0000" OR $l_art["y1"]!="0000") {
    $content.= " <span style='font-size: 90%;'>(";
    if($l_art["y0"]!="0000") $content.= "éd. ".$l_art["y0"];
    if($l_art["y0"]!="0000" AND $l_art["y1"]!="0000" AND $l_art["modif"]>$l_art["mel"]) $content.= " - ";
    if($l_art["y1"]!="0000" AND $l_art["modif"]>$l_art["mel"]) $content.= "màj ".$l_art["y1"];
    $content.=")";
}
$content.="</div></div>";
if(!empty($l_art["image"])){
    //$size=getimagesize("../docs/".$l_art["image"]);
    $content.="<div><img src='".$l_art["image"]."' style='max-width:100%;max-height:200px;width: auto;'>";               // image dernièr article : HAUTEUR MAX 300 px
    if(!empty($l_art["ref_image"]))$content.="<br><span class='small fst-italic'>".strip_tags($l_art["ref_image"],array("<a>","<b>"));
    if($l_art["id_arch"]!=0){
        $content.="&nbsp;/&nbsp Archive : ";
        if($l_art["lib_arch"]!="") {
                $content.="<a href='accueil1-sources_archive-arch-".$l_art["id_arch"].".html' target='_blank'>".$l_art["lib_arch"]."</a>";	//envoi de la variable archive en mode POST
        }
        else $content.= $l_art["lib_arch"];
    }
    $content.="</span>";
}
$content.="
</div></div></div>";
                                                /* Bloc INFO */
$sql="SELECT mess_court FROM messages ORDER BY maj DESC LIMIT 1";
try {$id_mess=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
if($l_mess=$id_mess->fetch(PDO::FETCH_ASSOC)){
	if($l_mess["mess_court"]<>"") {
         $content.="
        <div class='row ps-0 mt-0 mb-3'><div id='information' class='col ps-2'>
        INFORMATION : &nbsp;".$l_mess["mess_court"]."</div></div>";
	}
}
//$content.="</div>";

//                                                          ****************   BLOC   ARTICLES     *********
$content.="<div class='row mt-1' id='row_forms_&_articles'> 
    <div class='col ps-0 pe-0' id='col_forms_&_articles'>"; // ouverture d'un row et une col pour contenir toute la suite

$content.="<div class='mt-2 pt-2 pb-2 border-top border-secondary border-1 d-flex justify-content-between'>";
$content.="<div class='fs-3'>Articles précédents</div>";
$content.="<div><form style='display:inline;' action='accueil1-article_recherche_texte.html' method='post' onsubmit='return valid_search(this)'>
<input name='chaine' type='text' id='chaine' style='width:300px;'
    title='Saisir un mot ou une chaîne de caractères à rechercher dans tous les articles de LISA' placeholder='Recherche textuelle dans les articles'>&nbsp;
    <input type='image' name='submit' src='../img/search.png' style='vertical-align:sub;'></form></div>";       // formulaire de recherche
$content.="</div>";

$tabs=array(1=>"Période ancienne",5=>"Période autrichienne",10=>"Ancien régime",15=>"Révolution à 1871",20=>"Période moderne");
$infos=array(1=>"Période ancienne (<div class='info over'>avant 1324<div>1324 : mariage d'Albrecht II de Habsbourg avec Jeanne de Ferrette.</div></div>)",
5=>"Période autrichienne (<div class='info over'>1324<div>1324 : mariage d'Albrecht II de Habsbourg avec Jeanne de Ferrette.</div></div>-<div class='info over'>1648<div>1648 : traités de Westphalie, l'Alsace entre dans le royaume de France, et le comté de Belfort est inféodé à Mazarin et ses 
    héritiers.</div></div>)",
10=>"Période française d'ancien régime (<div class='info over'>1648<div>1648 : traités de Westphalie, l'Alsace entre dans le royaume de France, et le comté de Belfort est inféodé à Mazarin et ses 
héritiers.</div></div>-1792)",
15=>"De la révolution à <div class='info over'>1871<div>De la révolution à 1871, la région de Belfort est une partie du département du Haut-Rhin ; en 1871, le traité de Francfort la maintient
dans la république française, alors que le reste du Haut-Rhin est annexé par l'Allemagne.</div></div>",
20=>"Période moderne, après <div class='info over'>1871<div>Le territoire devient un département en 1922.</div></div>");

$content.="<nav>
<div class='nav nav-tabs' id='nav-tab' role='tablist'>";

foreach($tabs as $key=>$val){
    $content.="<button id='nav_$key' class='nav-link text-body fs-5 fw-bold' id='nav-$key-tab' data-bs-toggle='tab' data-bs-target='#nav-$key' type='button' 
        role='tab' aria-controls='nav-1' aria-selected='true'>$val</button>";   // tous les onglets inactifs au départ, pour pb d'affichage du padding
}
$content.="</div>
</nav>";

//                           ****            ONGLETS DE TOUS LES AUTRES ARTICLES ***

$sql1="INSERT INTO temp1 SELECT * FROM temp WHERE id!=$last_article ORDER BY maj desc limit 500 OFFSET 4";		// temp1 contient tous les articles, sauf les 5 plus récents -> affichage après
try {$bdd->query($sql1);}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}

$content.="<div class='tab-content' id='nav-tabContent'>";
foreach($tabs as $key=>$val){
    $sql="SELECT id, cat FROM temp1 WHERE periode=$key ORDER BY maj desc";
    try {$req=$bdd->query($sql);}
    catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
    $content.="<div class='tab-pane fade mt-3 pb-3 ps-0 pe-0 me-0' id='nav-$key' role='tabpanel' aria-labelledby='nav-$key-tab'>"; // début d'un bloc tab-pane
    $content.="<div class='row' style='padding-right:0.73rem;'><h4>".$infos[$key]."</h4>";
    while($l_ser=$req->fetch(PDO::FETCH_ASSOC)){        // suite de colonnes wrapées
        $content.="<div class='col-2 pe-0 mb-3' style='min-height: 170px;'>";
        if($l_ser["cat"]==0){				// anciens articles
            $sql="SELECT `num`,`titre_fr` as titre,`image`,`ref_image`,`insert_img`,`resume`,`ref`,`arch_link`,`ordre`,`epoque`, `auteurs`, date_m_l, date_maj, year(`date_m_l`) AS y0, year(`date_maj`) 
                AS y1 FROM pages_supp WHERE num=".$l_ser["id"];
            try {$id_art=$bdd->query($sql);}
            catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
            $l_art=$id_art->fetch(PDO::FETCH_ASSOC);
            $content.="<a href='accueil1-article_ancien-article-".$l_art["num"].".html' target='_blank' class='fs-5' title=\"".strip_tags($l_art["resume"])."\">";
        }
        else {		// nouveaux
            $sql="SELECT `id`,`titre`,`auteur`,`abstract`,`image`,`ref_image`,`insert_img`,`id_arch`,`lib_arch`,mel, modif, per1, per2, year(`mel`) AS y0, 
            year(`modif`) AS y1 FROM article WHERE id=".$l_ser["id"];
            try {$id_art=$bdd->query($sql);}
            catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));}
            $l_art=$id_art->fetch(PDO::FETCH_ASSOC);
            $content.="<a href='accueil1-article_nouveau-article-".$l_art["id"].".html' target='_blank' class='fs-5' title=\"".strip_tags($l_art["abstract"])."\">";
        }
        if($l_art["image"]!=""){
            $path_parts = pathinfo($l_art["image"]);
            $img="../img_art_m/".$path_parts['filename']."§s.".$path_parts['extension'];  // utilisation de xxx§s pour chargement plus rapide
            $content.="<img src='$img' style='display: block;max-width:100%;height:auto;max-height:200px;width: auto;'>";
        }
        else {
/*             $content.="<div style=\"float:left;margin:5px;background:url('../img/first_logo1.jpg');width:50px;height:50px;background-size:100% 100%;border-radius:50%;shape-outside:circle();\">
            </div>"; */
            $content.="<div style=\"float:left;margin:7px;\"><i class='bi bi-journal-text fs-1'></i>
            </div>";
            $content.="<div style='height:10px;'></div>";
        }
        if(strlen(htmlspecialchars_decode($l_art["titre"]))<80) $cart="titre-a";
        else $cart="titre-c";
        $content.="<span class='$cart'>".strip_tags($l_art["titre"])."</span>";
        $content.="</a></div>";
    }
    $content.="</div></div>"; // fermeture du bloc d'articles
}
$content.="</div>"; // fermeture du bloc nav-tabContent
$content.="</div></div>"; // fermeture des deux blocs contenant les articles antérieurs
$res=1;
?>
<style>
.over:hover div{
    width : 200px;
}
</style>