<?PHP
//session_start();
require_once('libs/conexion_2.php');
require_once('enviodecorreos.php');
$cnx_oracle= Conectarse_oracle();

function valida_email ($correo)
{
$correo=strtolower($correo);
if ((strpos($correo, 'briqven')!== false) || (strpos($correo, 'sidor.com')!== false))
    if (strpos($correo, 'briqven') !== false)
        $mail=$correo.".com.ve";
    else 
        $mail=$correo;
else
      $mail="";  
return $mail;
}

$asunto = isset($_POST["asunto"])?$_POST["asunto"]:"NULL";    //
$cboTrabajador= isset($_POST["cboTrabajador"])?$_POST["cboTrabajador"]:"";     // 
$txtTrabajador= isset($_POST["txtTrabajador"])?$_POST["txtTrabajador"]:"";   //

    $query = "select a.TRABAJADOR, a.MENSAJE, substr(b.nombre,instr(b.nombre,'/',1,2)+1) as  NOMBRES, b.E_MAIL from TP_MENSAJES_COND_SID a, TRABAJADORES b where a.trabajador=b.trabajador ";    

    //filtrar por trabajadores
    if ($txtTrabajador!="")    
    {
        $query = $query . "  and TO_NUMBER(a.trabajador)  IN (" . $txtTrabajador . ")"; 
                       
    } 
    
$stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
oci_execute($stid);
$count_reg=0;
while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false) {   
   $count_reg++; // contador de registros 
   $cedtrabajador = $fila['TRABAJADOR'];
   $mensaje = $fila['MENSAJE'];
   $nombre = $fila['NOMBRES'];
   $mail= valida_email($fila['E_MAIL']);

   $cuerpo="<p>&nbsp;</p>Sr(a). ".$nombre." 
   Esta recibiendo el password para observar el contenido del recibo de pago. La confidencialidad de la informaci&oacute;n que por este medio reciba depende exclusivamente de Ud.  
   Si tiene alguna duda, problema, comentario o sugerencia, con gusto estaremos dispuestos para 
   atenderla, comunicandose con el Sr. Blas Gonzalez ext. 297."; 
   $cuerpo = $cuerpo."<p>&nbsp;</p>".$mensaje;
  
   $result=ENVIAR_CORREO($cuerpo,$asunto,"",$mail,1);  
}

echo $count_reg;
?>