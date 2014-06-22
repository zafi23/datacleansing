<?php
    
    include_once 'lib/cluster.php';
    //include_once 'index2.php';
    include_once 'lib/mail.php';
	
	$name = $_POST["lname"];
	
	
    $fich = "ua.dist";
	$cads = array(); 
	$listaCluster = array();
	
	$dist = leerCadenas($fich,$cads,$numero);

	clustering($listaCluster,$dist,$numero,0.002);
	
	saveFichB($cads,$listaCluster);
	saveFichC($cads,$listaCluster);
	
	
	
	sendMail('zafi23@hotmail.com',$name);
	
?>    
