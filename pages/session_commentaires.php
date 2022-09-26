<?php
if ($_SESSION['statut']<1) {
	$url= $_SERVER['REQUEST_URI']; 		// Ajouter l'emplacement de la ressource demandée à l'URL
    print ("<script language = \"JavaScript\">location.href = 'accueil1.php?disp=session_connexion.php?from=".urlencode($url)."';</script>");
}
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'
$l3="Acte";
$l7="";
$wor="Rafraîchir l'affichage";
$ret="Retour";
$comment='Commentaire';
$mod="Modifier";
$sup="Supprimer";

$rek="select comment_act.*,chercheurs.* from `comment_act` LEFT JOIN `chercheurs` ON comment_act.aut=chercheurs.id_ch
	where chercheurs.id_ch=".$_SESSION['_id']." order by comment_act.dat";
try {$i_ch=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div class='row justify-content-center'>
<div class='col-9 pt-4 pb-4'>";
//$content.="<div class='mb-4 fs-3'><a hef='accueil1-session_espace_perso.html' class='fs-3'>Espace personnel</a> > commentaires d'actes</div>";
$content.="<div class='mb-4 fs-3'>Commentaires d'actes</div>";
if($i_ch->rowCount()>0){
    $content.="<table class='mb-4 table table-sm'>
    <thead>
    <tr>
        <th scope='col'>Acte</th>
        <th scope='col'>Commentaire</th>
        <th scope='col'>Date</th>
    </tr>
    </thead>
    <tbody>";
    while ($lc=$i_ch->fetch(PDO::FETCH_ASSOC)){
        $sql="SELECT arch FROM acts WHERE NUM=".$lc["act"];	//nécessaire dans l'appel de AffAct
        try {$rek_arch=$bdd->query($sql);}
			catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
        $l_arch=$rek_arch->fetch(PDO::FETCH_ASSOC);
        $content.="<tr>
            <td><a href='javascript:affiche_acte(&#039 ".$l_arch["arch"]."&#039 ,".$lc["act"].",0)' title='afficher cet acte'>
            <img src='../img/book_close.gif' align='left' onclick=\"this.src='../img/book_open.gif'\"></a>    
            <td>".$lc["txt"]."
            <td>".$lc["dat"];
    }
    $content.="</table>
    <div><em>
    Pour supprimer un commentaire, ouvrir l'acte qui le contient.<br>
    Pour le modifier, le supprimer et le rééditer.</em></div>";
}
else $content.="Vous n'avez encore déposé aucun commentaire sur des actes.";
$content.="</div>"; 


$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>