<?php
session_start();
session_unset();
echo json_encode(array("res"=>1));
?>