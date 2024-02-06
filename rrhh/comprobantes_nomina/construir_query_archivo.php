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
$txtMensaje= isset($_POST["txtMensaje"])?$_POST["txtMensaje"]:"";   //
$noError = true;
$target_path="";
if(!empty($_FILES['uploadedfile']['name'])){
    $target_path = "ATTACHMENT/";
    $target_path = $target_path . basename($_FILES['uploadedfile']['name']); 
    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
        $noError = true;
    else 
        $noError = false;           
}
else
   $target_path = "";

if ($noError)
{
    $query = "select b.TRABAJADOR, substr(b.nombre,instr(b.nombre,'/',1,2)+1) as  NOMBRES, b.E_MAIL from TRABAJADORES b ";
    //filtrar por trabajadores
    if (isset($_POST['chkTrabajador']))    
    {
        $query = $query . " order by b.nombre";
    } 
    else   
    {
        $query = $query . "  where TO_NUMBER(b.trabajador)  IN (" . $txtTrabajador . ")";
    }    
    $stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
    oci_execute($stid);
    $count_reg=0;
    while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false) {   
       $count_reg++; // contador de registros 
       $cedtrabajador = $fila['TRABAJADOR'];       
       $nombre = $fila['NOMBRES'];
       $mail= valida_email($fila['E_MAIL']);

       $cuerpo="<p>&nbsp;</p>Sr(a). ".$nombre.". "; 
       $cuerpo = $cuerpo.$txtMensaje;
      
       $result=ENVIAR_CORREO($cuerpo,$asunto,$target_path,$mail,1);  
    }
   echo $target_path; 
}
else
    echo 'El Archivo '.$target_path.' No Subio';
?>