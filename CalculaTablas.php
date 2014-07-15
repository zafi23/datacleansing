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
	
	//Si la variable de fichero existe la copio al servidor
	if($_FILES["fichero"]["error"] != 4)
	{
		
		
		$nomF = $_FILES["fichero"]["name"];
		
		$fichero = "users/".$email."/".$nomF;
		
		move_uploaded_file($_FILES["fichero"]["tmp_name"],$fichero);
	}
	
	//Si la variable de abreviaturas existe la copio al servidor
	if($_FILES["abreviaturas"]["error"] != 4)
	{
		
		
		$nomA = $_FILES["abreviaturas"]["name"];
		
		$fAbrev = "users/".$email."/".$nomA;
		
		move_uploaded_file($_FILES["abreviaturas"]["tmp_name"],$fAbrev );
	}
	

	//Se cargan las abreviaturas si existe el fichero de abreviaturas
	if(isset($fAbrev))
		$abreviaturas = cargarAbreviaturas($fAbrev);
	
	//Cargo los diccionarios 
	$cadenasOri = cargarDiccionarios($fichero, $abreviaturas,$position);
	$cadenas = $cadenasOri;
	
	//Los ordeno
	usort($cadenas, "compareDiccionario");
	$arrayobject = new ArrayObject($cadenas);
	$iterator = $arrayobject->getIterator();
	
	$name = explode(".", $nomF);
	
	$newName = $name[0].".dist";
	
	//Guardo la informacion de las cadenas en el fichero
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
	
	//Calculo las distancias de las cadenas
	clusteringTabla($file,$cadenas);
	fclose($file);
	 
	//Se envia el mensaje con el link para acceder a los resultados
	$query_string = 'e=' . urlencode($email) . '&f=' . urlencode($newName);
	$msg = "Para acceder al resultado del analisis acceda al siguiente link:</br>";
	$msg.= "<a href=\"localhost/datacleansing/Resultados.php?".$query_string."\">Acceso al contenido</a>";
	$subject = "Aceso a resultados";

	sendMail($email,$msg,$subject);

?>