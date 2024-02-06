<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$usuario, $actividad, $destinatario1, $destinatario2){
	if (!class_exists("PHPMailer"))
	{
  		require_once 'PHPMailer/src/Exception.php';
  		require_once 'PHPMailer/src/PHPMailer.php';
  		require_once 'PHPMailer/src/SMTP.php';
  		$mail = new PHPMailer\PHPMailer\PHPMailer(); // create a new object
	}

	$listado=$destinatario1;
	$enviado='';
	$mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
	$mail->IsSMTP(); // enable SMTP
	$mail->Host = "10.0.3.20";
	$mail->SMTPAuth = true; // enable 
	$mail->Username = "brisca@briqven.com.ve";
	$mail->Password = "matesi112022";
	$mail->SMTPSecure = "tls";
	$mail->SMTPAutoTLS = false;
	$mail->Port = 587;

	//Content Mail Quien Envia
	$mail->IsHTML(true);
	$mail->FromName = "Sist. Control de asistencia";
	$mail->SetFrom("brisca@briqven.com.ve", "Sist. Control de Asistencia");
	//$mail->SetFrom("bripro@briqven.com.ve");
	
	$mail->Subject = $asunto;
	if ($attach!="")
	    $mail->AddAttachment($attach);
	    $mail->Body    = $cuerpo;
	    $mail->AltBody = '';
	    $mail->AddAddress($listado);
	    if ($destinatario2!="") $mail->AddCC($destinatario2);

		//SALTAR ERROR DE VERIFICACION DE CERTIFICADO 2018
		$mail->SMTPOptions = array(
        		'ssl' => array(
         	       		'verify_peer' => false,
                		'verify_peer_name' => false,
	                	'allow_self_signed' => true
        		)
		);
	if(!$mail->Send())
	{      
  		$enviado="Mailer Error: ".$mail->Errorinfo;              	  
	}else{    		      
		  $enviado="Correo Enviado!";      	
		  $mail->clearAddresses();
	}	 
 
	return $enviado;
}
?>
