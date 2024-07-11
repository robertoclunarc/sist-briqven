<?php
function ENVIAR_CORREO($cuerpo,  $asunto, $attach,$usuario, $actividad, $destinatario1){
    //print 'destinatario1:'.$destinatario1;
    if (!class_exists("PHPMailer"))
    {
      require_once 'PHPMailer/src/Exception.php';
      require_once 'PHPMailer/src/PHPMailer.php';
      require_once 'PHPMailer/src/SMTP.php';
      $mail = new PHPMailer\PHPMailer\PHPMailer(); // create a new object
    }
     
    $link=Conex_Contancia_pgsql();
    $query="select email from envio_correo where actividad = '".$actividad."'";
    $listado = ejecutar_query($link,$query) or die("Error en la Consulta SQL: ".$query);
    $destinatario_session= $_SESSION['user_session_const']."@briqven.com.ve";
    $enviado='';
    //print $query;
//print $destinatario1;
    $mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
    $mail->IsSMTP(); // enable SMTP
    $mail->Host = "10.0.3.20";
    $mail->SMTPAuth = true; // enable 
    $mail->Username = "brisca@briqven.com.ve"; //from@@domainname.com
    $mail->Password = "matesi112022";
    $mail->SMTPSecure = "tls";
    $mail->SMTPAutoTLS = false;
    $mail->Port = 587;

    //Content Mail Quien Envia
    $mail->IsHTML(true);
    $mail->FromName = "Sist. Control de Asistencia";
    $mail->SetFrom("brisca@briqven.com.ve", "Sist. Control de Asistencia");

    $mail->Subject = $asunto;
    if ($attach!="")
        $mail->AddAttachment($attach);

    $mail->Body    = $cuerpo;
    $mail->AltBody = '';
    //if ($actividad!="PRUEBA") $mail->AddAddress($usuario);
    if ($destinatario1!="")   $mail->AddCC($destinatario1);
    $mail->AddBCC($destinatario_session);
    
    while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))
          $mail->AddAddress($reg['email']);

    //if ($destinatario1!="") $mail->AddCC($destinatario1);

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
      //$enviado="Correo Enviado!";
      $mail->clearAddresses();      	
    } 
     
    return $enviado;
}


function ENVIAR_CORREO_INDIVIDUAL($cuerpo,$asunto,$attach,$usuario, $actividad, $destinatario1, $destinatario2){
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
      //$enviado="Correo Enviado!";       
      $mail->clearAddresses();
  }  
 
  return $enviado;
}
?>
