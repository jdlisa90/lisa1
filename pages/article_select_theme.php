<?php
extract($_POST,EXTR_OVERWRITE);

$content="<div class='row justify-content-center'>
<div class='col pt-4 pb-4'>"; 

$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2 FROM article_terme_indexation_1,article_terme_indexation_2
    WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 AND article_terme_indexation_2.id=:terme";
$ida=$bdd->prepare($sql);
$ida->bindValue('terme', $terme, PDO::PARAM_INT);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$la=$ida->fetch(PDO::FETCH_ASSOC);
$theme_lib=$la["index1"]." > ".$la["index2"];

try {$bdd->query("create temporary table temp(id int not null, titre text, abstract text, type tinyint(1))");}
catch(Exception $e) {exit(json_encode(array("result"=>$sql.' <b>Exception at line '.$e->getLine() .' :</b> '.$e->getMessage(),"res"=>0)));} // recherche insensible aux accents

$sql="INSERT INTO temp(id, titre, abstract, type) SELECT article.id,titre,abstract, 1 FROM article, `article-indexes` WHERE article.id=`article-indexes`.article 
AND `article-indexes`.terme=:terme AND `article-indexes`.new_old='new' AND pub=1";
$ida=$bdd->prepare($sql);
$ida->bindValue('terme', $terme, PDO::PARAM_INT);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}  // artiles nouveaux

$sql="INSERT INTO temp(id, titre, abstract, type) SELECT pages_supp.num,titre_fr,resume, 0 FROM pages_supp, `article-indexes` WHERE pages_supp.num=`article-indexes`.article 
AND `article-indexes`.terme=:terme AND `article-indexes`.new_old='old' AND pub=1";
$ida=$bdd->prepare($sql);
$ida->bindValue('terme', $terme, PDO::PARAM_INT);
try {$ida->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}  // artiles ANCIENS

$sql="SELECT * FROM temp ORDER BY type DESC, id DESC";
$req=$bdd->prepare($sql);
try {$req->execute();}
catch(Exception $e) {exit($sql.'<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}   // extraction de la table temporaire

if($req->rowCount()>0){
    $content.="<div class='fs-3 mb-3'>Articles concernant le thème \"$theme_lib\"</div><ul>";
    while($la=$req->fetch(PDO::FETCH_ASSOC)){
        if($la["type"]==1) $content.="<li class='mb-3'><b><a href='accueil1-article_nouveau-article-".$la["id"].".html' target='_blank'>".strip_tags($la["titre"])."</a></b>";
        else $content.="<li class='mb-3'><b><a href='accueil1-article_ancien-article-".$la["id"].".html' target='_blank'>".strip_tags($la["titre"])."</a></b>";
        if($la["abstract"]!="") $content.="<div>".strip_tags($la["abstract"])."</div>";
        $content.="</li>";
    }
    $content.="</ul></div>";
}

$sql="SELECT article_terme_indexation_1.lib AS index1,article_terme_indexation_2.lib AS index2, article_terme_indexation_2.id, COUNT(*) AS nb 
    FROM article_terme_indexation_1,article_terme_indexation_2,`article-indexes` WHERE article_terme_indexation_1.id=article_terme_indexation_2.niveau1 
    AND `article-indexes`.terme=article_terme_indexation_2.id GROUP BY `article-indexes`.terme ORDER BY index1, index2";
try {$rek=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$content.="<form class='mt-2' action='accueil1-article_select_theme.html' method='post'>
    <select name='terme' onChange='this.form.submit()'><option value='0' selected='selected' disabled='disabled'>&raquo; Autre thème</option>";
$index1="";
while($l=$rek->fetch(PDO::FETCH_ASSOC)){
    if($index1!=$l["index1"]) {
        $content.= "<optgroup label=\"".$l["index1"]."\">";
        $index1=$l["index1"];
    }
    $content.="<option value='".$l["id"]."'>".$l["index2"]." (".$l["nb"].")</option>";
}
$content.="</select></form>";

$content.="</div>"; // fermeture row contenant

?>
