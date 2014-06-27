<?php
    
	include_once("lib/funciones.inc");
	
	$cluster = $_POST["cluster"];
	
	$cadenas = cadenasCluster($cluster);
	
	$cads = unserialize($_SESSION["cads"]);
	$clusters = unserialize($_SESSION["clusters"]);

	$centro = $cads[$clusters[$cluster]->centro()]->getC();
	
	$datos = array($centro,$cadenas);

	echo json_encode($datos);
?>