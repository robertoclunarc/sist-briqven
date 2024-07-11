<?php

require_once("class.phpmailer.php");
require_once("class.smtp.php");


$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only ,false = Disable 
$mail->Host = "10.0.3.20";
$mail->SMTPAutoTLS = false;
//$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true; // enable 
$mail->IsHTML(true);
$mail->Username = "bricdp@briqven.com.ve"; //from@@domainname.com
$mail->Password = "matesi.11";
$mail->SetFrom("bricdp@briqven.com.ve");
$mail->Subject = 'Here is the subject';
//$mail->AddAttachment("CT0416MATLUX.pdf");
$mail->Body    = 'This is the HTML message body <strong>in bold!</strong>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->AddAddress("matlux@sidor.com");
 if(!$mail->Send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    echo "Message has been sent";
    }
