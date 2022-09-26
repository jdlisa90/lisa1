
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)
$img=array(0=>"0",1=>"1",2=>"td",3=>"0",4=>"4");
$content="<div class='row pt-4 pb-4'>
<div class='text-center pb-4'><b>Dépouillement de l'état-civil moderne</b> : &nbsp;&nbsp;&nbsp;<img src='../img/".$img[1].".png' style='border:1px solid #d9d9d9;'> : réalisé &nbsp;&nbsp;&nbsp;
<img src='../img/".$img[2].".png' style='border:1px solid #d9d9d9;'> : tables décennales &nbsp;&nbsp;&nbsp;
<img src='../img/".$img[4].".png' style='border:1px solid #d9d9d9;'> : Belfort : td mar. (LISA) + n-d (site AM) &nbsp;&nbsp;&nbsp;
<img src='../img/".$img[0].".png' style='border:1px solid #d9d9d9;'> : non réalisé ou partiel</div>";
$sql="select LIB_LOC,ID_LOC,ZIPCODE_LOC,depll from locality,`arch-comm`,arch_ssserie WHERE locality.ID_LOC=`arch-comm`.comm AND `arch-comm`.arch=arch_ssserie.id 
	AND arch_ssserie.TYPE_ARCH=2 order by LIB_LOC";
try {$id=$bdd->query($sql);}
catch(Exception $e) {exit('<b>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
while($l=$id->fetch(PDO::FETCH_ASSOC)){
    $content.="
    <div class='col-3 pt-2 pb-2'>
        <div class='row ps-4 pe-4'>
            <div class='col-8 pe-1'>
                <a href='accueil1-entites_commune-ent-".$l["ID_LOC"].".html'>".$l["LIB_LOC"]."</a>
            </div>
            <div class='col-2'>
                ".$l["ZIPCODE_LOC"]."
            </div>
            <div class='col-2'>
                <img src='../img/".$img[$l["depll"]].".png' style='border:1px solid #d9d9d9;' width='37'>
            </div>
        </div>
    </div>";
}
$content.="</div>";
?> 
