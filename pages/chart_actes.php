<?php
header("Content-Type: application/json");
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}

$sql="SELECT left(maj,7) as mois, count(left(maj,7)) AS nb FROM `acts` GROUP BY mois ORDER by mois";
try{$req=$bdd->query($sql);}
catch(Exception $e) {exit('<b>$sql<br>Exception at line '. $e->getLine() .' :</b> '. $e->getMessage());}
$result=array();
$result["time"]="actes cumulés";
while($lf=$req->fetch(PDO::FETCH_ASSOC)) {
if($lf["mois"]<'2017-06')$nb0+=$lf["nb"];
	else {
		if($nb0>0) {
			$result["06 2017"]=$nb0;
			$nb=$nb0;
			$nb0=0;
		}
		else {
			$nb+=$lf["nb"];
			$mois=substr($lf["mois"], 5, 2)." ".substr($lf["mois"], 0, 4);
			$result[$mois]=$nb;
		}
	}
}

echo json_encode($result);
?>