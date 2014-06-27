<?php

	if (!class_exists("phpmailer")) {
	require_once('phpmailer/class.phpmailer.php');
	}
	if (!class_exists("smtp")) {
	require_once('phpmailer/class.smtp.php');
	}

	
function sendMail($email,$msg,$subject,$attachment = "")
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
	$mail->From = "no.reply.datacleansing@gmail.com";
	
	// nombre remitente, p. ej.: "Servicio de envío automático"
	$mail->FromName = "Servicio de Datacleansing";
	
	// asunto y cuerpo alternativo del mensaje
	$mail->Subject = $subject;
	$mail->AltBody = "Cuerpo alternativo para cuando el visor no puede leer HTML en el cuerpo"; 
	
	if($attachment!="")
	{
		$mail->addAttachment(realpath($attachment));	
	}

	// si el cuerpo del mensaje es HTML
	$mail->Body= $body;
	
	// podemos hacer varios AddAdress
	$mail->AddAddress($email);
	
	
	// si el SMTP necesita autenticación
	$mail->SMTPAuth = true;
	
	
	// credenciales usuario
	$mail->Username = "no.reply.datacleansing@gmail.com";
	$mail->Password = "Datacleansing2014"; 
/*

	if(!$mail->Send()) {
	echo "Error enviando: " . $mail->ErrorInfo;
	} else {
	echo "¡¡Enviado!!";
	}
*/
		
}





?>