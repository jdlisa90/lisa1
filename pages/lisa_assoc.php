<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)
$content="<div class='row'><div class='col mt-3'>
<link rel='stylesheet' href='../css/style_articles2.css'/>
<img src='../img/logo_arbre.png' class='float-end'>
<h3>Association LISA</h3>
<p>LISA est formée de chercheurs amateurs dont le but est la <b>mise en valeur des sources archivistiques
relatives &agrave; la R&eacute;gion de Belfort</b>.
<br />Le produit de ses activit&eacute;s est destin&eacute &agrave; tous, <b>librement
et gratuitement</b>.&nbsp;<br><br>
<div class='rectangle_light' style='width:250px;'><b>Sommaire :</b>
    <ul class='sommaire'>
        <li>1. <a href='#1'>Historique</a>
        <li>2. <a href='#2'>Site Internet</a>
        <li>3. <a href='#3'>Adhésion et activités</a>
    </ul>
</div>
<br>
<h4 id='1'>1. Historique</h4>
<div class='mb-2'>Une plateforme de travail a &eacute;t&eacute; constitu&eacute;e en 1991.<br />Pour assurer la p&eacute;rennit&eacute; de ses travaux et l'esprit de service public qui l'anime, 
l'&eacute;quipe LISA &agrave; choisi de d&eacute;velopper son activit&eacute; en liaison &eacute;troite avec les Archives d&eacute;partementales du Territoire de Belfort ainsi qu'avec 
d'autres institutions patrimoniales int&eacute;ressant l'histoire de la r&eacute;gion de Belfort.<br>
Fin 2001, afin d'inscrire son action dans la dur&eacute;e, l'&eacute;quipe LISA a choisi de se constituer en <b>association</b> &agrave; but non lucratif.</div>";

$content.="
<div class='mb-2'><a href='#' data-bs-toggle='modal' data-bs-target='#equipe'>Administrateurs</a></div>
<div class='mb-3'>En 2002, LISA a sign&eacute; une convention de partenariat avec le Conseil G&eacute;n&eacute;ral du Territoire-de-Belfort afin d'officialiser ses 
liens avec les Archives de ce d&eacute;partement.<br>
Ult&eacute;rieurement, des partenariats ont &eacute;t&eacute; formalis&eacute;s avec le Conseil G&eacute;n&eacute;ral du Haut-Rhin, le Mus&eacute;e de 
la R&eacute;sistance et de la D&eacute;portation de Besan&ccedil;on, avec la Ville de Belfort et les Archives Municipales et avec la Biblioth&egrave;que Nationale de France.</div>

<h4 id='2'>2. Site Internet</h4>
<div class='mb-3'>Ce site Internet est l'interface publique de nos activit&eacute;s.<br>
Son acc&egrave;s est public et gratuit.<br />Toutefois, la cr&eacute;ation (gratuite) d'un <b>compte</b> (menu Connexion &gt; <b>S'inscrire</b>) est conseill&eacute;e pour 
b&eacute;n&eacute;ficier d'une interactivit&eacute; compl&egrave;te.<br>
Par ailleurs, certaines facilit&eacute;s dans les formulaires de recherche sont r&eacute;serv&eacute;es aux membres (en particulier l'usage de caract&egrave;res de 
contr&ocirc;le).</div>

<h4 id='3'>3. Activités et adhésion</h4>";
if ($_SESSION['statut']>1) $content.=" (Vous ètes adhérent)";
$content.="<div class='mb-3'>L'essentiel des activit&eacute;s de LISA se centre sur le <strong>d&eacute;pouillement</strong> d'archives.<br>
En 2020, la totalit&eacute; des <a href='accueil1-entites_paroisses_carte.html' target='_blank'>&eacute;tat-civils anciens</a> et environ la moiti&eacute; des 
<a href='accueil1-entites_communes_liste.html'>&eacute;tats-civils modernes</a>, ainsi qu'une partie des archives notariales et juridictionnelles anciennes sont d&eacute;pouill&eacute;es.
<br />Toutes les archives n'étant pas digitalis&eacute;es, une activit&eacute; de <strong>num&eacute;risation</strong> reste utile, si les conditions mat&eacute;rielles le permettent.<br>
Depuis quelques temps, LISA a d&eacute;velopp&eacute; des <strong>interfaces de saisie en ligne</strong> originales qui conf&egrave;rent une totale autonomie 
aux b&eacute;n&eacute;voles. Des activit&eacute;s p&eacute;riph&eacute;riques, comme la transcriptions d'actes anciens, la r&eacute;daction d'articles historiques et 
celle d'un glossaire du moyen français sont &eacute;galement support&eacute;es par ces interfaces.<br>
L'<b>adh&eacute;sion</b> &agrave; l'association est gratuite (pas de cotisation), mais s'obtient comme contrepartie de la r&eacute;alisation effective d'activit&eacute;s 
au b&eacute;n&eacute;fice de l'association. Elle est prononc&eacute;e par son Conseil d'Administration.</div>";

$content.="<div class='mb-3'><script language='JavaScript' type='text/javascript'>
var part1 = 'admin';
var part2 = Math.pow(2,6);
var part3 = String.fromCharCode(part2);
var part4 = 'lisa90.org';
var part5 = part1 + String.fromCharCode(part2) + part4;
var part6='Statuts\u00a0association';
var part7='Veuillez\u00a0me\u00a0faire\u00a0parvenir\u00a0un\u00a0exemplaire\u00a0des\u00a0statuts\u00a0de\u00a0l\u0027association\u00a0LISA.\u00a0Merci.';
document.write('<a href=' + 'mai' + 'lto' + ':' + part5 + '?subject=' + part6 + '&body=' + part7 + '>' + 'Demander un exemplaire des statuts de l\'Association' + '</a>.');
</script></div>";

if($_SESSION["statut"]<2) {
    $content.="<div class='mb-3'><a href='static/form_adh1.php' target='blank'>J'ai pris connaissance des statuts et je souhaite adhérer</a></div>";
}
$content.="<div class='mt-3 mb-3'>
<b>Remerciements de LISA :</b>
<ul class='ps-3'>
<li>aux b&eacute;n&eacute;voles qui, dans le respect de son &eacute;thique, s'investissent dans ses activit&eacute;s,</li>
<li>au Conseil G&eacute;n&eacute;ral du Territoire de Belfort,</li>
<li>&agrave; la Ville de Belfort,</li>
<li>au Conseil g&eacute;n&eacute;ral du Haut-Rhin,</li>
<li>aux Archives D&eacute;partementales du Territoire de Belfort,</li>
<li>aux Archives Municipales de Belfort,</li>
<li>aux Archives D&eacute;partementales du Haut-Rhin,</li>
<li>&agrave; la Biblioth&egrave;que Nationale de France,</li>
<li>au Mus&eacute;e de la R&eacute;sistance et de la D&eacute;portation de Besan&ccedil;on,</li>
<li>au personnel des Archives pour leur compr&eacute;hension, leur aide et leur soutien.</li>
</div></div></div>";
$res=1;
?>
<div class="modal fade modal_bg" tabindex="-2" id="equipe">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-body">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-5 text-end">
                <h2>Association LISA</h2><br>
                <h3>Conseil d'administration</h3>
                <h5>Pr&eacute;sident : Jean-Dominique Pellegrini<br>
                    Vice pr&eacute;sident : Olivier Billot<br>
                    Vice pr&eacute;sident : Patrick Bornier<br>
                    Secr&eacute;taire : Bernard Cuquemelle<br>
                    Secr&eacute;taire-adjoint : Denis Dahy<br>
                    Trésorier : Herv&eacute; Ross&eacute;<br>
                <br><br>
                Aur&eacute;lia Benayas<br>
                Robert Billerey<br>
                &dagger; Pierre Braun<br>
                Christian Gindre<br>
                G&eacute;rard Jacquot</h5>
                <h3><br>
                    Site Internet</h3>
                <h5>
                    Jean-Dominique Pellegrini<br>
                </h5>
                </div>
                <div class="col-5 text-center align-self-center">
                <img SRC="../img/logo_arbre.png" ALT="logo lisa">
                </div>
            </div>
            </div>
		</div>
	</div>
	</div>
</div>