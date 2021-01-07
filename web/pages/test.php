<?php
	header("Content-Type: text/html; charset=UTF-8");
	include_once("bd/db.php");
	include("../scripts/menu.php");
	if (isset($_SESSION['logged_user'])){
		echo("ЕСТЬ!!!"); 
	}; 
?>