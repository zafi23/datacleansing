<?php

	include_once("lib/funciones.inc");
	include_once 'lib/mail.php';


	$modificaciones = json_decode($_POST["modificaciones"]);
	$tipo = $_POST["tipo"];
	
	//llamar a funcion de hacer las modificaciones
	aplicarMods($modificaciones);
	
	$cads = unserialize($_SESSION["cads"]);
	$listaC = unserialize($_SESSION["clusters"]);
	$nomF = $_SESSION["nomF"];
	$ruta = "users/".trim($_SESSION["email"])."/";
	$email = trim($_SESSION["email"]);
	
	$fichero = $ruta."res.csv";

	$file = fopen($fichero,"w");
	
	//Se realiza un tipo de guardado distinto acorde a la opcion escogida por el usuario
	switch($tipo)
	{
		case 0:
			saveFich($file,$cads);
			break;
		case 1:
			saveFichC($file,$cads,$listaC);
			break;
		case 2:
			saveFichB($file,$cads,$listaC);
			break;
	}

	
	fclose($file);

	//enviar correo
		
	$msg = "Gracias por usar el servicio de Datacleansing, te adjuntamos el fichero generado.";
	$subject = "Fichero resultante";
	sendMail($email,$msg,$subject,$fichero);
	
	//Se borran las variables de session y se elimina la session
	unset($_SESSION["cads"]);
	unset($_SESSION["clusters"]);
	unset($_SESSION["email"]);
	unset($_SESSION["nomF"]);
	
	session_destroy();
	
?>