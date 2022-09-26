<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'><div class='col pt-4 pb-4 flex-grow-0'><img src='../img/logo_arbre.png'>
<input type='hidden' id='requete' value='$form'><input type='hidden' id='nom' value='$nom'><input type='hidden' id='prenom' value='$prenom'>
<input type='hidden' id='a0' value='$a0'><input type='hidden' id='a1' value='$a1'><input type='hidden' id='arch' value='$arch'>";

$content.="</div></div>"; // fermeture row contenant
?>