<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$usuario, $actividad){
if (!class_exists("PHPMailer"))
{
  require_once 'PHPMailer/src/Exception.php';
  require_once 'PHPMailer/src/PHPMailer.php';
  require_once 'PHPMailer/src/SMTP.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer(); // create a new object
}

require("include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
$query="select email from tbl_envio_correo where actividad = '".$actividad."'";
//print $query;
$listado = pg_query($cn,$query);

$enviado='';

//$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
$mail->IsSMTP(); // enable SMTP
$mail->Host = "10.0.3.20";
$mail->SMTPAuth = true; // enable 
$mail->Username = "brismd@briqven.com.ve"; //from@@domainname.com
$mail->Password = "brismd.123";
//$mail->Username = "brismd@briqven.com.ve"; //from@@domainname.com
//$mail->Password = "brismd.123";
//$mail->Password = "matesi.11";
$mail->SMTPSecure = "tls";
$mail->SMTPAutoTLS = false;
$mail->Port = 587;

//Content Mail Quien Envia
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
//  $mail->AddAddress('matzem@briqven.com.ve');

pg_free_result($listado);

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
}
else
{    		      
  $enviado="Correo Enviado!";      	
} 
 
return $enviado;
}

/*
 $result=ENVIAR_CORREO('<p>&nbsp;</p>Sr(a). Esto Es Una Prueba','Prueba','','matlux@briqven.com.ve.com','PRUEBA');
 echo $result; 
*/
//pg_free_result($listado);
//exit;

?>
