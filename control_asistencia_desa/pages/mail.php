<?php

require_once("class.phpmailer.php");
require_once("class.smtp.php");


$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
$mail->Host = "canaima.fundacite-bolivar.gob.ve";
$mail->SMTPAutoTLS = false;
$mail->SMTPSecure = false;
$mail->SMTPAuth = true; // enable 
$mail->IsHTML(true);
$mail->Username = "evieira@fundacite-bolivar.gob.ve"; //from@@domainname.com
$mail->Password = "21e12m1985";
$mail->SetFrom("evieira@fundacite-bolivar.gob.ve");
$mail->Subject = 'Here is the subject';
//$mail->AddAttachment("CT0416MATLUX.pdf");
$mail->Body    = 'This is the HTML message body <strong>in bold!</strong>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->AddAddress("evieira@fundacite-bolivar.gob.ve");
$mail->SMTPOptions = array(
        'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
        )
);

 if(!$mail->Send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    echo "Message has been sent";
    }
