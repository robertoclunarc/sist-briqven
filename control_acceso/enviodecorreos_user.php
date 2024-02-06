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
       	  $msj="ENVIADO a ".$mails;
        }                
return $msj;
}
//echo ENVIAR_CORREO('prueba','probando','','matlux@sidor.com','robertoclunarg@gmail.com');
function migrar_claves(){
require_once('libs/conexion.php');
$cn1=  Conectarse(); 
$conn = oci_connect('matlux', 'lux1705', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$sql1 = "select cedula, login from usuarios where estatus='ACTIVO' and login <>'sistem'";
$res1 = pg_query($cn1,$sql1);
$i=0;

while ($row1 = pg_fetch_array($res1)){
   
   $stid = oci_parse($conn, "select dato from tp_comp_correo_sid where to_number(trabajador) = ".$row1['cedula']);
oci_execute($stid);
$row = oci_fetch_array($stid, OCI_BOTH);

$sql = "INSERT INTO passw_user(login, cedula, passw, fecha_modificacion) VALUES ('".$row1['login']."', '".$row1['cedula']."', '".$row['DATO']."',now());";
    $res = pg_query($cn1,$sql);
    $i++;
}

pg_close($cn1);
return  "Finalizado Con Exito! Cantidad Migrados: ".$i;

}

function enviar_passw(){
require_once('libs/conexion.php');
$cn1=  Conectarse();
$sql1 = "SELECT 
  usuarios.nombres, 
  usuarios.nivel, 
  passw_user.passw, 
  usuarios.email, 
  usuarios.login
FROM 
  passw_user, 
  usuarios
WHERE 
  passw_user.login = usuarios.login AND
  usuarios.estatus = 'ACTIVO' AND usuarios.nivel<=6 and usuarios.login='matpec'";
$res1 = pg_query($cn1,$sql1);
while ($row1 = pg_fetch_array($res1)){
  switch ($row1['nivel']) {
    case 1:
        $oper = "AUTORIZACION DE ENTRADA/SALIDA";
        break;
    case 2:
        $oper = "CONFIRMACION DE ENTRADA/SALIDA";
        break;
    case 3:
        $oper = "SOLICITUD DE ENTRADA/SALIDA";
        break;
    case 4:
        $oper = "VALIDACION DE ENTRADA/SALIDA";
        break;
    case 5:
        $oper = "REGISTRAR ENTRADA/SALIDA";
        break;
    case 6:
        $oper = "CONSULTA ENTRADA/SALIDA DE PERSONAL";
        break;           
}
  /*$cuerpo="Estimado Sr(a). ".$row1['nombres']."<br>Se le a creado un usuario para el acceso al Sistema Control de Acceso, Modulo Entrada/Salida de Materiales y Suministros.<br>Los datos para acceder debe recordarlos y mantenerlos en total CONFIDENCIALIDAD.<br>Usuario: ".$row1['login']."<br>Passw: ".$row1['passw']."<br>debe respectar las mayusculas y minusculas tal como se le esta suministrando.<br>La operacion segun su nivel de permisologia en el  sistema es: ".$oper.".<br>Para ingresar al sistema pueder hacer Click aqui <A HREF='http://10.50.188.48/control_acceso/index.php'>Sistema Control de Acceso</A> o puede ingresar desde la pagina de Intranet <A HREF='http://10.50.188.48'>Briqven al Dia</A>.<br>Para informacion y soporte puede comunicarse via e-mail con el Dpto. TI: Roberto Lunar matlux@briqven.com.ve y/o Mervin Zerpa matzem@briqven.com.ve<br><br>Saludos.";*/

  $cuerpo="Estimado Sr(a). ".$row1['nombres']."<br>Se le a creado un usuario para el acceso al Sistema Control de Acceso, Modulo Entrada/Salida de Materiales y Suministros.<br>Los datos para acceder debe recordarlos y mantenerlos en total CONFIDENCIALIDAD.<br>Usuario: ".$row1['login']."<br>Passw: ".$row1['passw']."<br>debe respectar las mayusculas y minusculas tal como se le esta suministrando.<br>La operacion segun su nivel de permisologia en el  sistema es: ".$oper.".<br>Para ingresar al sistema pueder hacer Click aqui <A HREF='http://10.50.188.48/control_acceso/index.php'>Sistema Control de Acceso</A> o puede ingresar desde la pagina de Intranet <A HREF='http://10.50.188.48'>Briqven al Dia</A>.<br>Para informacion y soporte puede comunicarse via e-mail con el Dpto. TI: Roberto Lunar matlux@briqven.com.ve y/o Mervin Zerpa matzem@briqven.com.ve<br><br>Saludos.";

  $asunto="Contro de Acceso y Modulo Entrada/Salida de Materiales";
  $cc='matlux@briqven.com.ve';
  $attach=dirname(__FILE__)."/manual control de acceso.pdf"; 
   echo ENVIAR_CORREO($cuerpo,$asunto,$attach,$row1['email'],$cc)."<br>";
}

} 
enviar_passw();
?>