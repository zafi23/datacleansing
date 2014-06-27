<?php
    
    include_once 'lib/cluster.php';
    //include_once 'index2.php';
    include_once 'lib/mail.php';
	
	//$name = $_POST["lname"];
	
	
	
	$email = "";
	$alpha = "";
    $fich = "ua.dist";
	$cads = array(); 
	$listaCluster = array();
	
	$dist = leerCadenas($fich,$cads,$numero,$email,$alpha);


	var_dump($cads);

	clustering($listaCluster,$dist,$numero,0.002);
	
	//saveFichB($cads,$listaCluster);
	//saveFichC($cads,$listaCluster);
	
	
	
	//sendMail('zafi23@hotmail.com',$name);
	
?>    
