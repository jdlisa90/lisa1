<?php
extract($_POST,EXTR_OVERWRITE);

$content="<div class='row justify-content-center'>
<div class='col pt-4 pb-4'>"; 

try {$bdd->query("create temporary table temp(id int not null, titre text, page text, type tinyint(1))");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));} // recherche insensible aux accents

$sql="INSERT INTO temp(id, titre, page, type) SELECT id,titre,intro, 1 FROM article WHERE (intro LIKE '%".str_replace("'", "''", $chaine)."%' OR 
intro LIKE '%".htmlentities(str_replace("'", "''", $chaine),ENT_QUOTES,'UTF-8')."%') AND pub=1";
$ida=$bdd->prepare($sql);
$ida->bindValue('chaine', "%".$chaine."%", PDO::PARAM_STR);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}  // dans l'intro 'un artile nouveau

$sql="INSERT INTO temp(id, titre, page, type) SELECT article,article.titre,content, 1 FROM article_chapitre,article WHERE article_chapitre.article=article.id AND
(content LIKE '%".str_replace("'", "''", $chaine)."%' OR content LIKE '%".htmlentities(str_replace("'", "''", $chaine),ENT_QUOTES,'UTF-8')."%') AND pub=1";
$ida=$bdd->prepare($sql);
$ida->bindValue('chaine', "%".$chaine."%", PDO::PARAM_STR);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}  // dans un chapitre d'un artile nouveau

$sql="INSERT INTO temp(id, titre, page, type) SELECT num,titre_fr,page, 0 FROM pages_supp WHERE page LIKE '%".str_replace("'", "''", $chaine)."%' OR 
    page LIKE '%".htmlentities(str_replace("'", "''", $chaine),ENT_QUOTES,'UTF-8')."%'  AND pub=1";
$ida=$bdd->prepare($sql);
$ida->bindValue('chaine', "%".$chaine."%", PDO::PARAM_STR);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());} // dans un artile ancien

$sql="SELECT * FROM temp ORDER BY type DESC, id DESC";
$id=$bdd->prepare($sql);
$id->bindValue('chaine', "%".$chaine."%", PDO::PARAM_STR);
try {$id->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}   // extraction de la table temporaire

//$ida=mysql_query($sql)or die("La requête n'a pu aboutir ; essayez de modifier votre saisie. Merci.");
if($id->rowCount()>0){
    $article=0;
    $content.="<div class='fs-3 mb-4'>Articles contenant le terme \"<span class='fs-3 fst-italic'>".$chaine."</i>\" : </span></div>";
    while($la=$id->fetch(PDO::FETCH_ASSOC)){
        if($la["id"]!=$article){
            $tx=html_entity_decode(strip_tags($la["page"]),ENT_QUOTES,'UTF-8');   // page sans les codes html
            $p1= stripos($tx,$chaine);
            if($p1!="false" AND $p1>0 AND $p1!=""){
                if($la["type"]==1) $content.="<b><a href='accueil1-article_nouveau-article-".$la["id"].".html' target='_blank'>".strip_tags($la["titre"])."</a></b>";
                else $content.="<b><a href='accueil1-article_ancien-article-".$la["id"].".html' target='_blank'>".strip_tags($la["titre"])."</a></b>";
                       // la sensibilité aux accents diffère entre les recherches mysql et php : on élimine les cas où cette dernière ne retourne rien
                $content.=" :<br>...".substr(          // retourne un segment de texte
                str_ireplace(
                    $chaine,
                    "<b><i>".substr($tx,$p1,strlen($chaine))."</i></b>",
                    $tx),               //  page dans laquelle la chaîne a été remplacée par elle-même, en italiques et gras
                max(0,$p1-100),         // offset : le plus grand de début et de position de la chaine -100   
                200+strlen($chaine)    // longueur
                )."...";
                $content.="</b><br><br>";
            }
            $article=$la["id"];
        }
    }    
}
else $content.="<br><br>Aucun article ne contient le terme \"<i>".$chaine."</i>\".<br><br>";
$content.="<div><form style='display:inline;' action='accueil1-article_recherche_texte.html' method='post' onsubmit='return valid_search(this)'>
<input name='chaine' type='text' id='chaine' style='vertical-align:top;'
    title='Saisir un mot ou une chaîne de caractères à rechercher dans tous les articles de LISA' placeholder='Autre recherche'>&nbsp;
    <input type='image' name='submit' src='../img/search.png'></form></div>";       // formulaire de recherche
/* $content.="<form style='display:inline;' action='accueil1.php?disp=article_recherche_texte' method='post' name='sel_art' onsubmit='return valid(this,&#39;chaine&#39;,2)'>
Recherche d'autres <strong>articles</strong> &nbsp;<input name='chaine' type='text' id='chaine' title='Saisir un mot ou une chaîne de caractères à rechercher dans tous les articles de LISA'
> &nbsp;
<button type='submit' class='btn btn-secondary'>Go</button>
</form></p>"; */

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo

$content.="</div>"; // fermeture row contenant

?>
