
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)
 
// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$time_0=@explode(' ',@microtime());
$nb_min=3;
$date0="2019-00-00";
$sql="SELECT arch, section, lib_section, complet, arch_ssection.comment, min(maj), max(maj), COUNT(*) FROM `acts`,`arch_ssection` 
WHERE `arch_ssection`.id=acts.section AND (arch_totale=0 OR complet=0) GROUP BY section HAVING max(maj)>'$date0' AND COUNT(*)>=".$nb_min." ORDER BY max(maj) DESC, min(maj) DESC";
  							
// liste de tous les actes dépouillés (ou corrigés) après $date0, formant un groupe auteur-section supérieur à $nb_min (pour éliminer les corrections ponctuelles)
// dont, soit le dépouillement est inachevé, soit la section ne correspond pas à la totalité de l'archive.
try{$req0=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}

$content="<div>
<div class='pt-3'>
<div class='fs-4'>Archives en cours de dépouillement</div>"; // row contenant et colonne principale

$content.="<table class='table table-sm'>
<thead>
<tr>
    <th scope='col' class='align-text-top'>Archive (section de dépouillement)</th>
    <th scope='col' class='align-text-top' style='min-width:105px;max-width:180px;'>Dépouillement achevé (pour la section)</th>
    <th scope='col' class='align-text-top'>Cotes dépouillées ou en cours</th>
    <th scope='col' class='align-text-top'>Actes relevés</th>
    <th scope='col' class='align-text-top' style='min-width:190px;'>Saisie ou mise en ligne</th>
</tr>
</thead>
<tbody>";

while($l0=$req0->fetch(PDO::FETCH_ASSOC)){
    //	$time_0=@explode(' ',@microtime());
        // pour chaque groupe, recherche des éléments
    if($l0["arch"]<100) $sql="SELECT id AS num_arch, CONCAT('Registres paroissiaux de ',lib) AS lib_arch FROM arch_ssserie WHERE id1=".$l0["arch"]." LIMIT 1";
    elseif ($l0["arch"]<221) {
        $sql="SELECT id AS num_arch, CONCAT('État-civil de ',lib) AS lib_arch FROM arch_ssserie WHERE id1=".($l0["arch"])." LIMIT 1";// code...
    }
    else {
        $sql="SELECT id AS num_arch, lib_abrg AS lib_arch, type_arch FROM arch_ssserie WHERE id1=".$l0["arch"]." LIMIT 1";// code...
    }
    try{$reqA=$bdd->query($sql);}
    catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
    $lA=$reqA->fetch(PDO::FETCH_ASSOC);
      
    $cotes_t="";
    if ($l0["complet"]==0) { // on n'affiche pas les cotes si le dépouillement est complet !
        $sql="SELECT cote FROM acts WHERE cote<>'' AND arch=".$l0["arch"]." AND section=".$l0["section"]." GROUP BY cote ORDER BY cote";
        try{$reqCotes=$bdd->query($sql);}
        catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage()."<br>".$sql);}
        $cotes="";
        while($lCotes=$reqCotes->fetch(PDO::FETCH_ASSOC)){
                $cotes.=$lCotes["cote"].", ";
        }
        $cotes_t=rtrim($cotes,", ");
    }

    $arch="";
    if($l0["arch"]>=250 AND isset($lA["type_arch"])) $arch=$type_arch[$lA["type_arch"]]." : ";
    $arch.=" <b><a href='accueil1.php?disp=sources_archive&arch=".$lA["num_arch"]."' target='_blank'>";
    $arch.=$lA["lib_arch"];
    $arch.="</a></b>";
    if ($l0["lib_section"]!="" OR $l0["comment"]!=""){
        if ($l0["lib_section"]!="" AND $l0["comment"]!="") $arch.=" (".$l0["lib_section"]." : ".$l0["comment"].")";
        elseif($l0["lib_section"]!="") $arch.=" (".$l0["lib_section"].")";
        else $arch.=" (".$l0["comment"].")";
    }
    
    if($l0["min(maj)"]!="0000-00-00" AND $l0["min(maj)"]!=$l0["max(maj)"]) $date="du ".date('d M. Y', strtotime($l0["min(maj)"]))." au ".date('d M. Y', strtotime($l0["max(maj)"]));
    else {
        $date=date('d M. Y', strtotime($l0["max(maj)"]));
    }
    $content.="<tr><td>$arch</td>";
    $content.="<td style='text-align:center;'>";
    if($l0["lib_section"]!=""){
/*         $content.="<input type='checkbox' ";
        if($l0["complet"]==1) $content.="checked ";
        else $content.="unchecked "; 
        $content.="onclick='return false'>"; */
        if($l0["complet"]==1) $content.="<i class='bi bi-check-lg fs-3'></i>";
    }
    $content.="<td>$cotes_t</td><td class='text-end pe-3'><b>".$l0["COUNT(*)"]."</b></td>";
    $content.="<td>$date</td></tr>";
}
$content.="</tbody></table>
</div></div>";
?> 
