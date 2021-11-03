<?php
try{
    $db = new PDO('mysql:host=localhost;dbname=fakebook','root','');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e){
	exit();
}
?>