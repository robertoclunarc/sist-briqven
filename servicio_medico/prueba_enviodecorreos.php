<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$usuario,$actividad){
require_once("phpmail/class.phpmailer.php");
require_once("phpmail/class.smtp.php");

require("include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
$query="select email from tbl_envio_correo where actividad = '".$actividad."'";
$listado = pg_query($cn,$query);


$enviado='';

$mail = new PHPMailer(); // create a new object
$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
$mail->IsSMTP(); // enable SMTP
$mail->Host = "10.0.3.20";
$mail->SMTPAuth = true; // enable 
$mail->Username = "brismd@briqven.com.ve"; //from@@domainname.com
$mail->Password = "brismd.123";
$mail->SMTPSecure = false;
$mail->SMTPAutoTLS = false;
$mail->Port = 25; 


//Content Mail
$mail->IsHTML(true);
$mail->FromName = "Sist. Servicio Medico";
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

$mail->SMTPOptions = array(
	'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	)
);

if(!$mail->Send())
    {
  $msj="Mailer Error";
  $enviado="*funcion php mail->Errorinfo"." - ".$msj;              	  
    }
else
    {    		      
   	  $enviado="Correo Enviado!";      	
    } 
 
return $enviado;
}


 $result=ENVIAR_CORREO('<p>&nbsp;</p>Sr(a). Esto Es Una Prueba','PRUEBA','','matzem@briqven.com.ve',2);
 echo $result; 

//pg_free_result($listado);
//exit;

?>
