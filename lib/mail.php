<?php

	if (!class_exists("phpmailer")) {
	require_once('phpmailer/class.phpmailer.php');
	}
	if (!class_exists("smtp")) {
	require_once('phpmailer/class.smtp.php');
	}

	
function sendMail($email,$msg)
{
	
	$mail = new PHPMailer();

	$body = $msg;
	
	$mail->IsSMTP(); 

	$mail->Mailer = 'smtp';	 
	$mail->SMTPSecure = "ssl";
	$mail->Host = 'ssl://smtp.gmail.com';	 
	$mail->Port = 465;
	
	$mail->IsHTML(true);
	
	// dirección remitente, p. ej.: no-responder@miempresa.com
	$mail->From = "kurisublack@gmail.com";
	
	// nombre remitente, p. ej.: "Servicio de envío automático"
	$mail->FromName = "Nombre_remitente";
	
	// asunto y cuerpo alternativo del mensaje
	$mail->Subject = "Asunto";
	$mail->AltBody = "Cuerpo alternativo para cuando el visor no puede leer HTML en el cuerpo"; 
	
	$mail->addAttachment(realpath('ua.resB.csv')); 
	
	echo count($mail->getAttachments())."</br>";
	// si el cuerpo del mensaje es HTML
	$mail->Body= $body;
	
	// podemos hacer varios AddAdress
	$mail->AddAddress($email, "Nombre_destino");
	
	
	// si el SMTP necesita autenticación
	$mail->SMTPAuth = true;
	
	
	// credenciales usuario
	$mail->Username = "kurisublack@gmail.com";
	$mail->Password = "Kawaii07"; 


	if(!$mail->Send()) {
	echo "Error enviando: " . $mail->ErrorInfo;
	} else {
	echo "¡¡Enviado!!";
	}

		
}





?>