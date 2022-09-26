<?php
include($_REQUEST['content'].".php");
echo json_encode(array("result"=>$content,"res"=>$res));
?>