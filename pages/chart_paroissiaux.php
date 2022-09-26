<?php
function echec(){       // image vide si échec
    $pct=imagecreate();
    imagepng($pct);
    imagedestroy($pct);
    die();
}
$id=mysql_connect("localhost","lisa90","jlpmjg90") or die ("<p>Impossible de se connecter");
mysql_select_db("lisa90");

extract($_GET);
$rekb="select * from parish_reg where arch=".$par." and typ='b' order by y1";
$id_rkb=mysql_query($rekb);
if(!$id_rkb) echec();
$pb=mysql_fetch_array($id_rkb);

$rekm="select * from parish_reg where arch=".$par." and typ='m' order by y1";
$id_rkm=mysql_query($rekm); 
if(!$id_rkm) echec();
$pm=mysql_fetch_array($id_rkm);

$reks="select * from parish_reg where arch=".$par." and typ='s' order by y1";
$id_rks=mysql_query($reks);
if(!$id_rks) echec();
$ps=mysql_fetch_array($id_rks);

$y2=1793;

if($t==p){
    $y1=min($pb["y1"],$pm["y1"],$ps["y1"]);
    if ($y1==0) $y1=1750;
    $y1=floor($y1/50)*50;
    
    switch($y1){
    	case 1750:
    		$k=12;
    		break;
    	case 1700:
    		$k=6;
    		break;
    	case 1650:
    		$k=4;
    		break;
    	case 1600:
    		$k=3;
    		break;
    	DEFAULT:
    		$y1=1570;
    		$k=3;
    		break;
    }
}
else{
	$y1=1570; 
	$k=3;
}

$mgg=10;
$mgh=3;

$h_rect=7;
$pct=imagecreate(($y2-$y1)*$k+$mgg+8,3*$h_rect+$mgh+15);
$fond=imagecolorallocate($pct, 204,204,153);
$lgray = imagecolorallocate($pct, 232,236,200);
$rgray=imagecolorallocate($pct, 218,218,177);
$coul=array("n"=>$fond,"d"=>$lgray,$rgray);
$black = imagecolorallocate($pct, 0,0,0);
$gray=$black;

imagestring($pct,1,$mgg+($pb["y1"]-$y1)*$k-6,$mgh,'b',$gray);
IF (mysql_num_rows($id_rkb)>0){
    for($e=0;$e<mysql_num_rows($id_rkb);$e++){
    	if($e>0) $pb=mysql_fetch_array($id_rkb);
        $yb1=$pb["y1"];
        $yb2=$pb["y2"];
		imagefilledrectangle($pct,$mgg+($yb1-$y1)*$k+1,$mgh+2,$mgg+(1+$yb2-$y1)*$k-2,$mgh+$h_rect-1,$coul[$pb["dep"]]);
        imagerectangle($pct,$mgg+($yb1-$y1)*$k,$mgh+1,$mgg+(1+$yb2-$y1)*$k-1,$mgh+$h_rect,$gray);
    }
}
imagestring($pct,1,$mgg+($pm["y1"]-$y1)*$k-6,$mgh+$h_rect,'m',$gray);
IF (mysql_num_rows($id_rkm)>0){
    for($e=0;$e<mysql_num_rows($id_rkm);$e++){
    	if($e>0) $pm=mysql_fetch_array($id_rkm);
    	$ym1=$pm["y1"];
    	$ym2=$pm["y2"];
    	imagefilledrectangle($pct,$mgg+($ym1-$y1)*$k+1,$mgh+$h_rect+2,$mgg+(1+$ym2-$y1)*$k-2,$mgh+2*$h_rect-1,$coul[$pm["dep"]]);
    	imagerectangle($pct,$mgg+($ym1-$y1)*$k,$mgh+$h_rect+1,$mgg+(1+$ym2-$y1)*$k-1,$mgh+2*$h_rect,$gray);
    }
}
imagestring($pct,1,$mgg+($ps["y1"]-$y1)*$k-6,$mgh+2*$h_rect-1,'s',$gray);
IF (mysql_num_rows($id_rks)>0){
    for($e=0;$e<mysql_num_rows($id_rks);$e++){
    	if($e>0) $ps=mysql_fetch_array($id_rks);
    	$ys1=$ps["y1"];
    	$ys2=$ps["y2"];
    	imagefilledrectangle($pct,$mgg+($ys1-$y1)*$k+1,$mgh+2*$h_rect+2,$mgg+(1+$ys2-$y1)*$k-2,$mgh+3*$h_rect-1,$coul[$ps["dep"]]);
    	imagerectangle($pct,$mgg+($ys1-$y1)*$k,$mgh+2*$h_rect+1,$mgg+(1+$ys2-$y1)*$k-1,$mgh+3*$h_rect,$gray);
    }
}
for($i=0;$i<=$y2-$y1;$i+=10){
	imageline($pct,$mgg+$i*$k,$mgh+3*$h_rect,$mgg+$i*$k,$mgh+3*$h_rect+3,$black);
	imagestring($pct,1,$mgg+$i*$k-9,$mgh+3*$h_rect+5,$y1+$i,$gray);
}
for($i=0;$i<=$y2-$y1;$i+=5) imageline($pct,$mgg+$i*$k,$mgh+3*$h_rect,$mgg+$i*$k,$mgh+3*$h_rect+2,$black);
for($i=0;$i<=$y2-$y1;$i++) imageline($pct,$mgg+$i*$k,$mgh+3*$h_rect,$mgg+$i*$k,$mgh+3*$h_rect+1,$black);
imageline($pct,$mgg,$mgh+3*$h_rect,$mgg+($y2-$y1)*$k,$mgh+3*$h_rect,$black);
imagepng($pct);
imagedestroy($pct);

?>