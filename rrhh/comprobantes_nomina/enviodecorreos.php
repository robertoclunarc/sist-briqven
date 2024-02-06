<?php
function ENVIAR_CORREO($cuerpo,$asunto,$attach,$mails,$entregar){
if (!class_exists("PHPMailer"))
{
  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';
}
require_once('libs/conexion_2.php');
$cn=  Conectarse_posgres();
if ($entregar==1){
   $direccion=trim($mails);
   $desbody=$cuerpo;
   $i=1;   
}
else{

  $mails = substr($mails, 0, -1);
  $attach = substr($attach, 0, -1);
  $cuerpo = substr($cuerpo, 0, -3);
  $asunto = substr($asunto, 0, -3);

  $mails=trim($mails);
  $attach=trim($attach);
  $cuerpo=trim($cuerpo);
  $asunto=trim($asunto);

   $array_mail=explode(";", $mails);
   $array_attach=explode(";", $attach);
   $array_cuerpo=explode("*#*", $cuerpo);
   $array_asunto=explode("*#*", $asunto);
   
   $desbody="<p>&nbsp;</p>Sr(a) Usuario.
   Esta recibiendo en el adjunto, comprobante(s) de pago de nomina solicitado por usted.
   La confidencialidad de la informaci&oacute;n que por este medio reciba depende exclusivamente de Ud.  
   Si tiene alguna duda, problema, comentario o sugerencia, con gusto estaremos dispuestos para 
   atenderla, comunicandose con el Sr. Blas Gonzalez ext. 297.";
   
   $i=count($array_mail);

}
$j=0;
$env=0;
$error="";
$clase= array();
while($j < $i){
    $clase[$j] = new PHPMailer\PHPMailer\PHPMailer();
    $mail=$clase[$j];
    ////////////////////////////////////////////////////////////////////////////////////
     $mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
    $mail->IsSMTP(); // enable SMTP

    $mail->Host = "10.0.3.20";
    $mail->SMTPAuth = true; // enable 
    $mail->Username = "bricdp@briqven.com.ve"; //from@@domainname.com
    $mail->Password = "matesi.11";
    $mail->SMTPSecure = "tls";
    $mail->SMTPAutoTLS = false;
    $mail->Port = 587;

    //Content Mail Quien Envia
    $mail->IsHTML(true);
    $mail->FromName = "Comprobante de Pago Cesta Ticket";
    $mail->SetFrom("bricdp@briqven.com.ve");
    ///////////////////////////////////////////////////////////////////////////////////
    if ($entregar==2)
        $direccion = trim($array_mail[$j]);
     // print_r($array_mail);
    if ($direccion!='') 
    {
        $mail->Subject = $array_asunto[$j];
        if ($array_attach[$j]!="")
          $mail->AddAttachment($array_attach[$j]);
        //$mail->Body    = 'Sr(a). <strong>'.$direccion.'</strong><p>'.$cuerpo.'</p>';
        $mail->Body    = $array_cuerpo[$j];
        $mail->AltBody = '';
        $mail->AddAddress($direccion);        

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
  	         $msj="Mailer Error";
  	         $error=$error."*funcion php mail->Errorinfo "." - ".$msj."-".$direccion;
             $auditoria = pg_query($cn,"INSERT INTO envios(login, aquien_envio, observacion, fecha) VALUES ('".$_SESSION['user_session']."', '".$direccion."','".$error."',NOW());");              	  
            }
        else
            {    		      
           	  $auditoria = pg_query($cn,"INSERT INTO envios(login, aquien_envio, observacion, fecha) VALUES ('".$_SESSION['user_session']."', '".$direccion."','ENVIO EXITOSO',NOW());");
              $env++; 	
            }                
    }
    $j++;    	 
 }
 //return $cuerpo.'*'.$asunto.'*'.$attach.'*'.$direccion.'*'.$entregar;
 
 if ($error=="")
 	return $env;
 else
 	return $error;
}

/*
 $result=ENVIAR_CORREO('<p>&nbsp;</p>Sr(a). Esto Es Una Prueba','Prueba','ATTACHMENT/mataln-LAME012018.PDF','matlux@sidor.com;',2);
 echo $result; 
*/
//pg_free_result($listado);
//exit;

?>