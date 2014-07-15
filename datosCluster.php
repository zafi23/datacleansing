<?php
    
	include_once("lib/funciones.inc");
	
	//Se obtiene la informacion de un cluster y se envia a la parte cliente
	$cluster = $_POST["cluster"];
	
	$cadenas = cadenasCluster($cluster);
	
	$cads = unserialize($_SESSION["cads"]);
	$clusters = unserialize($_SESSION["clusters"]);

	$centro = $cads[$clusters[$cluster]->centro()]->getC();
	$indiceCentro = $clusters[$cluster]->centro();
	
	
	$datos = array($centro,$indiceCentro,$cadenas);

	echo json_encode($datos);
?>