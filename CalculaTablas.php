<?php

include_once 'lib/tablas.php';
include_once 'lib/mail.php';

	set_time_limit(0);
	
	//Recojo las variables de la pagina
	$email = $_POST["email"];	
	$position = $_POST["posicion"];
	$alphaDL = $_POST["alpha"];
	
	//compruebo si existe el directorio
	if(!file_exists ("users/".$email))
	{
		mkdir("users/".$email);
	}
	
	if($_FILES["fichero"]["error"] != 4)
	{
		
		
		$nomF = $_FILES["fichero"]["name"];
		
		$fichero = "users/".$email."/".$nomF;
		
		move_uploaded_file($_FILES["fichero"]["tmp_name"],$fichero);
	}
	
	
	if($_FILES["abreviaturas"]["error"] != 4)
	{
		
		
		$nomA = $_FILES["abreviaturas"]["name"];
		
		$fAbrev = "users/".$email."/".$nomA;
		
		move_uploaded_file($_FILES["abreviaturas"]["tmp_name"],$fAbrev );
	}
	

	
	if(isset($fAbrev))
		$abreviaturas = cargarAbreviaturas($fAbrev);
	
	$cadenasOri = cargarDiccionarios($fichero, $abreviaturas,$position);
	$cadenas = $cadenasOri;
	usort($cadenas, "compareDiccionario");
	$arrayobject = new ArrayObject($cadenas);
	$iterator = $arrayobject->getIterator();
	
	$name = explode(".", $nomF);
	
	$newName = $name[0].".dist";
	
	$file = fopen("users/".$email."/".$newName,"w");
	
	fputs($file, $email."\n");
	fputs($file, $alphaDL."\n");
	fputs($file, count($cadenas)."\n");
	
	
	while($iterator->valid())
	{
		fputs($file, $iterator->current()->getCsv());
		fputs($file, $iterator->current()->getC()."\n");
		fputs($file, $iterator->current()->getF()."\n");
		fputs($file,  $iterator->current()->getL()."\n");
		fputs($file, $iterator->current()->getLDit()."\n");
		fputs($file, $iterator->current()->getIndice()."\n");
		fputs($file, $iterator->current()->getPos()."\n");
		$iterator->next();
	}
	
	clusteringTabla($file,$cadenas);
	fclose($file);

	$msg = "Para acceder al resultado del analisis acceda al siguiente link:</br>";
	$msg.= "<a href=\"localhost/datacleansing/Clustering.php\">Acceso al contenido</a>";


	sendMail($email,$msg);

?>