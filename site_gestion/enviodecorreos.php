<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$mails,$cc){

if (!class_exists("PHPMailer"))
{
  require_once 'PHPMailer/src/Exception.php';
  require_once 'PHPMailer/src/PHPMailer.php';
  require_once 'PHPMailer/src/SMTP.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer();
}
    
////////////////////////////////////////////////////////////////////////////////////
$mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
$mail->IsSMTP(); // enable SMTP
$mail->Host = "10.0.3.20";
$mail->SMTPAuth = true; // enable 
$mail->Username = "bripro@briqven.com.ve"; //from@@domainname.com
$mail->Password = "matesi.11";
$mail->SMTPSecure = "tls";
$mail->SMTPAutoTLS = false;
$mail->Port = 587;
//Content Mail Quien Envia
$mail->IsHTML(true);
$mail->FromName = "Sistema de Protección Patrimonial";
$mail->SetFrom("bripro@briqven.com.ve");
///////////////////////////////////////////////////////////////////////////////////    
    $mail->Subject = $asunto;       
    $mail->AddAttachment($attach);        
    $mail->Body    = $cuerpo;
    $mail->AltBody = '';
    $mail->AddAddress($mails);
    $mail->AddCC($cc);
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
         $msj="Mailer Error: ";
         $msj.=$mail->Errorinfo." - ".$mails;
                      	  
        }
    else
        {    		      
       	  $msj="ENVIADO";
        }                
return $msj;
}
//echo ENVIAR_CORREO('prueba','probando','','matlux@sidor.com','robertoclunarg@gmail.com');
?>