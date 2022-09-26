<?php
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$presentation="<div class='mt-3'>Nous avons répertorié les prénoms, aujourd'hui oubliés, rencontrés à l'occasion de nos dépouillements.<br>
<br>
On a relevé 2 types de prénoms :
<ul class='noindent'>
<li>Les pr&eacute;noms \"vrais\", associ&eacute;s &agrave; un saint, ou
&agrave; une tradition attest&eacute;e, plus ou moins locaux,
oubli&eacute;s de nos jours ; ex : Valbert, Deile... Aussi quelques prénoms germaniques peu connus, port&eacute;s par des familles
d'origine allemande ou pas ; exemple : Peterman, Fridolin...<br>
</li>
<li>Les formes anciennes de pr&eacute;noms actuels. Elles apparaissent en caractères italiques.
</ul>
Dans ce dernier cas, nous avons recherch&eacute;
particuli&egrave;rement les formes authentiquement port&eacute;es \"dans
la vie courante\".<br>
Il importe de comprendre à ce propos qu'aucune référence écrite (ni l'acte de baptême, ni document d'identité) n'établit
à l'époque l'identité précise d'un individu) ; donc la seule référence authentique était <b>orale</b>.
<br>Ces formes authentiques nous sont néanmoins accessibles :
<ul class='noindent'>
<li>id&eacute;alement, dans les signatures des actes d'état-civil, &agrave; condition
&eacute;videmment que les individus soient capables de signer
(essentiellement les hommes),</li>
<li>dans les actes rédigés en fran&ccedil;ais, &agrave;
condition toutefois qu'elles n'aient pas &eacute;t&eacute;
d&eacute;form&eacute;es par les tabellions soucieux de normalisation,</li>
<li>dans les textes (paroissiaux) latins, &agrave; condition
l&agrave; aussi que le cur&eacute; n'ai pas su ou pas voulu trouver le
pr&eacute;nom latin \"standart\" correspondant ; parfois, il choisit une 
forme \"latinisante\", intermédiaire, difficile à traduire.</li>
</ul>
On constate que ces formes traditionnelles disparaissent assez rapidement des actes au cours de la p&eacute;riode
&eacute;tudi&eacute;e.<br>
La raison de cette disparition tient à l'évolution des sociétés, accélérée ici par la volont&eacute; de l'&eacute;tat de contr&ocirc;ler et de normaliser
les provinces r&eacute;cemment incorpor&eacute;es au royaume
(Franche-Comt&eacute; et Alsace), volont&eacute; progressivement
couronn&eacute;e de succ&egrave;s.<br>
<br>
Au milieu du 18&egrave;me si&egrave;cle, la gamme des
pr&eacute;noms est nettement r&eacute;duite ; peu apr&egrave;s,
toutefois, cette disparition des formes \"rurales\" est largement
compens&eacute;e par la recherche de patronymes beaucoup plus
vari&eacute;s, d'origines diverses.<br>
Ce ph&eacute;nom&egrave;ne conna&icirc;tra une v&eacute;ritable
explosion à l'échelle nationale au 19&egrave;me si&egrave;cle, en particulier avec une grande
mode des pr&eacute;noms compos&eacute;s.</div>";

$content="<div class='row justify-content-center'>
<div class='col-9 pt-3 pb-4'>
<div class='ms-1'>"; // row contenant et colonne principale
if(!isset($init)) $content.="<h4>Prénoms anciens</h4>";
else $content.="<h4><a href='accueil1.php?disp=prod_prenoms'>Prénoms anciens</a></h4>";

$rek="SELECT DISTINCT init FROM pren WHERE init>64 ORDER BY init";
try {$id=$bdd->query($rek);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$l=$id->fetch(PDO::FETCH_ASSOC);
$alphab="";
for($e=65;$e<91;$e++){
    $alphab.="<span ";
    $size="";
    if($e==$init)$size="class='big'";
    if($l["init"]==$e){
        $alphab.= $size."><a href='accueil1.php?disp=prod_prenoms&init=".$e."'>".chr($e)."</a> ";
        $l=$id->fetch(PDO::FETCH_ASSOC);
    } 
    else $alphab.= ">".chr($e)." ";
    $alphab.="</span>";
}
$content.=$alphab;
$content.="</div>";
if(!isset($init)) $content.=$presentation;
else{
    $rek="SELECT * FROM pren WHERE init=".$init." ORDER BY SUBSTR(lib,2)";// pour incorporer certains noms dont l'initiale est un caractère spécial
    try {$id=$bdd->query($rek);}
	catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

    $typ=array("","Prénom ancien", "Forme ancienne", "Forme locale", "Prénom germanique", "Forme germanisante", "");
    if($id->rowCount()){
        $content.="<table class='table table-sm mt-3'>";
        while($l=$id->fetch(PDO::FETCH_ASSOC)){
            if($l["type"]>1 and $l["type"]<>4){
                $ital="<em>"; $ital1="</em>";
            }
            else 
            {
                $ital=""; $ital1="";
            }
            $content.="<tr>";
            $content.="<td valign='top' width='17%'><span id='".$l["id"]."'></span><b>".$ital.$l["lib"].$ital1."</b> (".$l["sex"].")"; // avec ancre
            if($l["lib_alt"]<>"")$content.="<br>var: ".$l["lib_alt"];
            if($l["lib_lat"]<>"")$content.="<br>lat: ".$l["lib_lat"];
            $content.="<td valign='top'  width='53%' style='padding: 5px;'>";
            $rek="SELECT * FROM pren_illus WHERE id_pren=".$l["id"]." ORDER BY ordre";
            try {$id_c=$bdd->query($rek);}
				catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
            $c=0;
            while($li=$id_c->fetch(PDO::FETCH_ASSOC)){ 
                if ($c>0)$content.="<br>";
                if($li["link"]<>"") $content.="<a href='".$li["link"]."' target='_blank'><img src='../img/pren/".$li["illus"].".jpg'></a>";
                else $content.="<img src='../img/pren/".$li["illus"].".jpg'>";
                if($li["comment"]<>"") $content.="<br>(".$li["comment"].")";
                $c++;
            }
            $content.="<td valign='top'>";
            if($l["type"]<>"")$content.=$typ[$l["type"]];
            if($l["type"]<>"" and $l["comment"]<>"")$content.="<br>";
            $content.=$l["comment"];
        }
        $content.="</table>";
        $content.=$alphab;
    }
}

$content.="</div>";            // fermeture colonne principale + colonne de droite et logo
$content.="</div>"; // fermeture row contenant
?>