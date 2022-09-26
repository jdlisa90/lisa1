<?php
session_start(); // On relaye la session 
extract($_GET,EXTR_OVERWRITE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<link rel="shortcut icon" type="image/x-icon" href="../img/lisaicon.ico" />
<link rel='stylesheet' href='../css/style1.css'/>
<style type="text/css">
* {  
  font-size: 17px;
}
</style>
</head>
<body>
<?php
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {die('Erreur : '.$e->getMessage());}
$pdf=1;
Include("article_nouveau.php");
echo $content;
?>

</body>
</html>