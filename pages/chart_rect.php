<?php
extract($_GET,EXTR_OVERWRITE);
$l_wr=40;
$larg-=10;
if ($write=='') $scale=$larg/$max;
else $scale=($larg-$l_wr)/$max;

$pct=imagecreate($larg,$h);
$back_col=imagecolorallocate($pct, 204,204,153);
$front_col = imagecolorallocate($pct, 232,236,200);
$black = imagecolorallocate($pct, 0,0,0);

imagefilledrectangle($pct,0,0,$val*$scale,$h,$front_col);
imagestring($pct,1,$val*$scale+5,0,$val,$black);
imagepng($pct);
imagedestroy($pct);
?>