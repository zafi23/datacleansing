<?php
    
	include_once("lib/funciones.inc");
	
	$cluster = $_POST["cluster"];
	
	$cadenas = cadenasCluster($cluster);
	
	$cads = unserialize($_SESSION["cads"]);
	$clusters = unserialize($_SESSION["clusters"]);

	$centro = $cads[$clusters[$cluster]->centro()]->getC();
	$indiceCentro = $clusters[$cluster]->centro();
	
	$datos = array($centro,$indiceCentro,$cadenas);

	echo json_encode($datos);
?>