<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col-9 pt-4 pb-4'>";
$content.="<div class='mb-4'>";

if($page = file_get_contents("../census/".$arch.".html")){
    $debut_code = strpos($page,'<body') + 3;
    $debut_code = strpos($page,'>',$debut_code)+1;
    $fin_code = strpos($page,'</body>');
    if($fin_code === FALSE) $code = substr($page,$debut_code);
    else $code = substr($page,$debut_code,$fin_code - $debut_code);
    $code=str_replace("<a href='javascript:history.go(-1)'>Retour</a>","",$code); // suppression du lien "back"
    $code=str_replace('<img src="','<img src="../census/',$code);
    $content.=utf8_encode($code);
}
else $content.="Fichier manquant.";
//$content.="<h3>Sources archivistiques anciennes et modernes</h3>"; // row contenant et colonne principale

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>