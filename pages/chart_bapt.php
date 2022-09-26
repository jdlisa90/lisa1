<?php
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_bar.php");
$colors=array("moccasin","darkgoldenrod1","gold3","khaki2","wheat1","olivedrab1","plum1","thistle3","vieuxrose", "blanchedalmond","lemonchiffon3",
"AntiqueWhite3", "slategray2","tan");
extract($_GET,EXTR_OVERWRITE);
setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$id=mysql_connect("localhost","lisa90","jlpmjg90") or die ("<p>Impossible de se connecter");
mysql_select_db("lisa90") or die ("<p>Impossible d'accéder à la base de données");

$nb_colr=count($colors);
$rek="select * from stats_bap where arch=".$arch." ORDER BY id";
$id_rk=mysql_query($rek)or die("Echec ".$rek);
$nb_plot = mysql_num_rows($id_rk);
/*srand ((double) microtime() * 10000000);
shuffle ($colors);*/
//echo $nb_plot;
//die();

for($e=1;$e<=$nb_plot;$e++){
	$l=mysql_fetch_array($id_rk);
	${"data".$e."y"}=explode(",",$l["data"]);	
	if($e==1){
		$n_dat=count($data1y);
		$ampl=$l["ampl"];
        $year=$l["year"];
		for($f=0;$f<$n_dat;$f++){
        	$xax=$year."\n-".($year+=$ampl);
        	if($pos==0) $xax="\n\n".$xax;
        	$datax[]=$xax;
        	$year++;
        	$pos=1-$pos;
        }	
	}
	
	${"b".$e."plot"} = new BarPlot(${"data".$e."y"});
	if($e==1)${"b".$e."plot"}->SetFillColor($colors[1]);
	else {
		if ($e==$nb_plot)${"b".$e."plot"}->SetFillColor($colors[$nb_colr-1]);
		else ${"b".$e."plot"}->SetFillColor($colors[($e)]);
	}
    ${"b".$e."plot"}->SetLegend($l["lib"]);

	$bplots[]=${"b".$e."plot"};
}

for($f=0;$f<$n_dat;$f++){
	for($e=1;$e<=$nb_plot;$e++){
		$btot[$f]+=${"data".$e."y"}[$f];
	}
}
rsort($btot);
//echo $btot[0];
$graph = new Graph(130+$n_dat*20,50+$btot[0],"auto");
/*$graph = new Graph(max(40+$n_dat*20,100),230,"auto");*/
$graph->SetScale("textlin");

/*$graph->SetShadow();*/
$graph->img->SetMargin(120,10,10,40);	
$graph->SetColor("fondclair");
$graph->SetMarginColor("fondsombre"); 

$graph->xaxis->SetTickLabels($datax);

$gbplot = new AccBarPlot($bplots);
$gbplot->SetWidth(0.6);

// ...and add it to the graPH
$graph->Add($gbplot);
$graph->yaxis->SetFont(FF_FONT0);
$graph->xaxis->SetFont(FF_FONT0);

/*$graph->legend->SetAbsPos(30,10,'left','top')*/
$graph->legend->Pos(0.01,0.6,"left");
$graph->legend->SetShadow('gray',0);
$graph->legend->SetFillColor("fondsombre");
$graph->legend->SetFont(FF_FONT0);
/*$graph->SetBackgroundImage("../img/graph_bkgr1.jpg",BGIMG_FILLPLOT,"auto");*/
/*$graph->AdjBackgroundImage(0.3,0.3);*/
// Display the graph
$graph->Stroke();
?>