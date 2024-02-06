<?PHP
//session_start();
require_once('libs/conexion_2.php');
$cnx_oracle= Conectarse_oracle();

$asunto = isset($_POST["asunto"])?$_POST["asunto"]:"NULL";    //
$cboTnomina= isset($_POST["cboTnomina"])?$_POST["cboTnomina"]:"";         //
$cboCnomina= isset($_POST["cboCnomina"])?$_POST["cboCnomina"]:"NULL";   // 
$cboMes= isset($_POST["cboMes"])?$_POST["cboMes"]:"";               // 
$cboAnho= isset($_POST["cboAnho"])?$_POST["cboAnho"]:"NULL";   // 
$txtdestino= isset($_POST["txtdestino"])?$_POST["txtdestino"]:"";
$cboTrabajador= isset($_POST["cboTrabajador"])?$_POST["cboTrabajador"]:"NULL";     // 
$txtTrabajador= isset($_POST["txtTrabajador"])?$_POST["txtTrabajador"]:"NULL";   //
$cboRlaboral= isset($_POST["cboRlaboral"])?$_POST["cboRlaboral"]:"NULL";   //
$txtexcepcion= isset($_POST["txtexcepcion"])?$_POST["txtexcepcion"]:"NULL";   //
$cboCcosto= isset($_POST["cboCcosto"])?$_POST["cboCcosto"]:"NULL";   //
$entregar= isset($_POST["entrega"])?$_POST["entrega"]:"NULL";
$clave= isset($_POST["clave"])?$_POST["clave"]:2;
$tienecorreo = isset($_POST["correo"])?$_POST["correo"]:3;

$TB_ENC=$cboTnomina.$cboCnomina.$cboMes.$cboAnho;

//$TB_ENC="LMME122017";

    $query = "SELECT DISTINCT";
    $query = $query . "   E.TRABAJADOR TRABAJADOR,";  
    $query = $query . "   RTRIM(LTRIM(SUBSTR(E.NOMBRE,1,22))) NOMBRE,";
    $query = $query . "   E.CENTRO_COSTO CENTRO_COSTO,";
    $query = $query . "   E.RELACION_LABORAL RELACION_LABORAL,";
    $query = $query . "   E.TIPO_NOMINA TIPO_NOMINA,";
    $query = $query . "   E.UBICGEO UBICGEO,";
    $query = $query . "   TO_CHAR(E.RATA_HORA, '99999999999.99') RATA_HORA,";
    $query = $query . "   TO_CHAR(E.SUELDO_MENSUAL,'9999999999.99') SUELDO_MENSUAL,";
    $query = $query . "   E.INSTITUCION_DEPOSITO INSTITUCION_DEPOSITO,";
    $query = $query . "   E.CUENTA_DEPOSITO CUENTA_DEPOSITO,";
    $query = $query . "   E.PERIODO ,";
    $query = $query . "   E.FECHA,";
    $query = $query . "   E.NRO_CORRELATIVO,";
    $query = $query . "   E.DIRECCION,";
    $query = $query . "   E.GERENCIA,";
    $query = $query . "   E.COD_GER,";
    $query = $query . "   E.COD_SUP,";
    $query = $query . "   E.COD_GER_GRAL,";
    $query = $query . "   E.OFICINA,";
    $query = $query . "   T.E_MAIL, ";
    $query = $query . "   E.TIPO_NOMINA || ' ' || TO_CHAR(E.PERIODO,'00') || ' ' || TO_CHAR(FECHA, 'YYYY') || '-' || E.RELACION_LABORAL  REFERENCIA,";
    $query = $query . "   'ATTACHMENT/' || LTRIM(RTRIM(SUBSTR(T.E_MAIL, 1, INSTR(T.E_MAIL,'@', 1) -1))) || '-" . strtoupper(TRIM($TB_ENC)) . ".PDF' PDF,";
    $query = $query . "   LTRIM(RTRIM(SUBSTR(T.E_MAIL, 1, INSTR(T.E_MAIL,'@', 1) -1))) SIGLADO";
    $query = $query . "   ,PWD.DATO AS PWD";
    $query = $query . "   ,TO_CHAR(SYSDATE, 'DDMMYYYYHH24MISS') FECHA_HOY";
    $query = $query . "   ,G.FECHA_INGRESO";
    $query = $query . " FROM ";
    $query = $query . "     ENC_" . $TB_ENC . "  E ";
    $query = $query . "   , TRABAJADORES   T";
    $query = $query . "   , DET_" . $TB_ENC . " D";
    $query = $query . "   , TP_COMP_CORREO_SID PWD";
    $query = $query . "   , TRABAJADORES_GRALES G";
    $query = $query . " WHERE ";  
    $query = $query . " T.TRABAJADOR = E.TRABAJADOR ";
    $query = $query . "  AND E.TRABAJADOR = D.TRABAJADOR ";
    $query = $query . "  AND E.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND T.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = T.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = T.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = G.TRABAJADOR ";
   // $query = $query . "  AND T.E_MAIL like '%briqven%' ";
  // $query = $query . "  AND D.TRABAJADOR IN ('  16395343', '  10574863', '  17110005' )";
    
    // filtrar listado excluyendo algunos especificados en el campo de excepcion
    if (isset($_POST['chkexcepcion']))
        $query = $query . "   and TO_NUMBER(T.TRABAJADOR) NOT IN (" . $txtexcepcion . ")";    

    if ($tienecorreo < 3)
        if ($tienecorreo == 2){
       //para imprimir los comprobantes de trabajadores sin correos excluyendo a los q si tienen
           $query = $query . "  and (instr(ltrim(rtrim(t.e_mail)),'.com') = 0 ";
           $query = $query . "  and instr(ltrim(rtrim(t.e_mail)),'briqven') = 0 )";

        }   
        else {
       //para enviar imprimir los comprobantes de trabajadores con correos excluyendo a los que no tienen
           $query = $query . "  and (instr(ltrim(rtrim(t.e_mail)),'.com') <> 0 ";
           $query = $query . "  or instr(ltrim(rtrim(t.e_mail)),'briqven') <> 0 )";
       }
    
    //filtrar por centro de costo
    if (isset($_POST['chkCcosto']))
    {
      $query = $query . " and E.CENTRO_COSTO = " . $cboCcosto;
     
    } 

    //filtrar por trabajadores
    if (isset($_POST['chkTrabajador']))    
    {
        $query = $query . "  and TO_NUMBER(t.trabajador)  IN (" . $txtTrabajador . ")"; 
        
    }    
    
    if (isset($_POST['chkRlaboral']))   
    {
      $query = $query . "  and e.relacion_laboral = '" . $cboRlaboral . "'";
      
    }
    $query = $query . " ORDER BY E.CENTRO_COSTO";
$trabajador='';
$totalregistros=0;
$stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
oci_execute($stid);
while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false) {  
  $totalregistros++;
  $trabajador=$trabajador.'#'.$fila['NOMBRE'];
}
echo $query.'#'.$totalregistros.$trabajador;
?>