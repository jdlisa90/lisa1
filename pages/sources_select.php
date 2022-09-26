
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'>
<h4>Sources archivistiques anciennes et modernes</h4>
<h5>Liste (non exhaustive) des sources d'archives primaires et de synthèses d'archives étudiées par LISA.</h5>";

foreach($type_arch as $key=>$val){
    $rek="SELECT id, lib, dates FROM arch_ssserie WHERE arch_ssserie.type_arch =".$key." AND `private`= 0 ORDER BY hors_secteur, lib";
    try {$id=$bdd->query($rek);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    if($id->rowCount()>0){
        $content.="<b>".$val."</b>";
        $content.="<div class='pb-2'><form method='post' id='form".$key."' action='accueil1-sources_archive.html'>";
        $content.="<select name='arch' id='".$key."' onChange='this.form.submit()' >
            <option value='0'  disabled='disabled' selected>&raquo; ".$val."</option>";
        while($l=$id->fetch(PDO::FETCH_ASSOC)){
            $content.="<option value='".$l["id"]."'>".$l["lib"];
            if($l["dates"]<>"") $content.=" (".$l["dates"].")";
            $content.="</option>";
        }
        $content.="</select></form>";
        if($key==1)$content.="Voir sur ce sujet les articles <b><a href='accueil1-article_ancien-article-41.html'>
            Etat-civil ancien ; collections conservées</a></b> et <b><a href='accueil1-article_ancien-article-56.html'>
            Paroisses et diocèses du Territoire-de-Belfort avant la Révolution</a></b>";
        $content.="</div>";
    }
}
$content.="Voir également la page <a href='https://fr.geneawiki.com/index.php/Au_del%C3%A0_de_l%27Etat-Civil_-_90' target='_blank'>Geneawiki : Au_delà_de_l'Etat-Civil - 90
</a>";

$content.="</div>
<div class='col pt-4 pb-4 flex-grow-0'>
<img src='../img/logo_arbre.png'>
</div>";
$content.="
</div>";
?> 
