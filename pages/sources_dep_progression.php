<?php
$content="<div class='row justify-content-center'>
<div class='col pt-3'>
<h4>Progression des dépouillements</h4>"; // row contenant et colonne principale
$width=(date("n")+(date("Y")-2019)*12+7)*23; // pour que la largeur soit porportiopnnelle au nombres de mois à afficher
// une fonction jquery dans accueil1 appelle la page chart_actes.php
$content.="<div id='curve_chart' style='width: ".$width."px; height: 500px'></div>
<p>Le graphique (dynamique) ci-dessus représente le cumul des actes dépouillés depuis la mise en œuvre de notre <b>interface de saisie en ligne</b>.
<br><br>Il affiche une croissance régulière, les quelques sauts (début 2019 ou avril 2020) correspondant à des mises en ligne en bloc d'anciens 
dépouillements réalisés sur des supports externes.
<br><br><b>Merci à nos courageux bénévoles</b>.";

$content.="</div>"; // fermeture row contenant
?>