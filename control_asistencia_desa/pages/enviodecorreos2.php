<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$usuario, $actividad){
	require_once("phpmail/class.phpmailer.php");
	require_once("phpmail/class.smtp.php");

	require("include_conex.php");
	$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
	$query="select email from tbl_envio_correo where actividad = '".$actividad."'";
	$listado = pg_query($cn,$query);


	$enviado='';
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
	$mail->Host = "10.0.3.20";
	$mail->Port = 25; 
	$mail->SMTPAutoTLS = false;
	//$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true; // enable 
	$mail->IsHTML(true);
	$mail->FromName = "Sist. Servicio Medico";
	$mail->Username = "brismd@briqven.com.ve"; //from@@domainname.com
	$mail->Password = "matesi.11";
	$mail->SetFrom("brismd@briqven.com.ve");

	$mail->Subject = $asunto;
	if ($attach!="")
	  $mail->AddAttachment($attach);
	//$mail->Body    = 'Sr(a). <strong>'.$direccion.'</strong><p>'.$cuerpo.'</p>';
	$mail->Body    = $cuerpo;
	$mail->AltBody = '';
	if ($actividad!="PRUEBA")
		$mail->AddAddress($usuario);
	while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))
	  $mail->AddAddress($reg['email']);
	pg_free_result($listado);
	if(!$mail->Send()){
	  $msj="Mailer Error";
	  $enviado="*funcion php mail->Errorinfo"." - ".$msj;              	  
    	}else {    		      
   	  $enviado="Correo Enviado!";      	
    	} 
 
	return $enviado;
}

/*
 $result=ENVIAR_CORREO('<p>&nbsp;</p>Sr(a). Esto Es Una Prueba','Prueba','ATTACHMENT/mataln-LAME012018.PDF','matlux@sidor.com;',2);
 echo $result; 
*/
//pg_free_result($listado);
//exit;

?>
