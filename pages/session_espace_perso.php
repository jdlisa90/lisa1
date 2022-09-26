<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
if ($_SESSION['statut']<1) {
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'session_connexion.php?from=".urlencode($url)."';</script>");
}

function dat($dat,$l){
	$pieces=explode("-",$dat);
	return date("d/m/y",mktime(0,0,0,$pieces[1],$pieces[2],$pieces[0]));
}

$content="<div class='row justify-content-center'>
<div class='col-7 pt-4 pb-4'>";
$content.="<div class='row mb-3 align-items-center'><div class='col-9'>";
$content.="<h3>Espace personnel de ".$_SESSION['pseudo']; // row contenant et colonne principale

if($_SESSION['statut']>7) $content.=" (administrateur)";
else{
    if($_SESSION['statut']==1) $content.=" (abonné)";
    if($_SESSION['statut']>=2) {
        if (isset($_SESSION['member'])){
            $content.=" (membre)";
        }
    }
    //if($_SESSION['statut']==3) echo" (intervenant)";
    if($_SESSION['statut']==5) $content.=" (trésorier)";
}
$content.="</h3></div><div class='col-3 text-center'><img src='../img/logo_arbre.png'></div>";
$content.="</div>";

$content.="<div class='row mt-5 mb-3'>";
$content.="<div class='col-9'>Éditer mes paramètres personnels</div>
    <div class='col-3 text-center'><a type='button' href='accueil1-session_compte.html' class='btn btn-secondary'>Go</a></div>";
$content.="</div>";
$content.="<div class='row mt-4 mb-3'>";
$content.="<div class='col-9'>Indexer un travail</div>
    <div class='col-3 text-center'><a type='button' href='accueil1-session_travail_edit.html' class='btn btn-secondary'>Go</a></div>";
$content.="</div>";
$content.="<div class='row mt-4 mb-3'>";
$content.="<div class='col-9'>Afficher la liste de mes travaux indexés</div>
    <div class='col-3 text-center'><a type='button' href='accueil1-session_travaux.html' class='btn btn-secondary'>Go</a></div>";
$content.="</div>";
$content.="<div class='row mt-4 mb-3'>";
$content.="<div class='col-9'>Afficher la liste de mes commentaires d'actes</div>
    <div class='col-3 text-center'><a type='button' href='accueil1-session_commentaires.html' class='btn btn-secondary'>Go</a></div>";
$content.="</div>";
/* 
if($_SESSION['statut']==3 OR $_SESSION['statut']>=10){
    $content.="<div class='row mt-4 mb-3'>";
    $content.="<div class='col-9'>$interv</div><div class='col-3 text-center'><a type='button' href='../admin/menu_intervenant.php?lang=fr.html' target='_blank' class='btn btn-secondary'>Go</a></div>";
    $content.="</div>";
} */
if($_SESSION['statut']==5){
    $content.="<div class='row mt-4 mb-3'>";
    $content.="<div class='col-9'>Ouvrir la \"page financière\"</div><div class='col-3 text-center'><a type='button' href='../admin/compta.php?lang=fr.html' target='_blank' class='btn btn-secondary'>Go</a></div>";
    $content.="</div>";
}
/* 
$content.="<div class='row mt-4 mb-3'>";
$content.="<div class='col-9'>Consentement (ou non) à la réception des courriels d'informations de LISA</div>
<div class='col-3 text-center'><a type='button' href='#' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#courriels' >Go</a></div>";   // objet dans page accueil1
$content.="</div>"; */

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>