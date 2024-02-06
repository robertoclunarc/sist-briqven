<?PHP
require("include_conex.php");
session_start();
$paciente = isset($_POST["txtId"])?$_POST["txtId"]:"-1";    //
//$fecha= isset($_POST["txtFecha"])?$_POST["txtFecha"]:"";         //
$motivo= isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";   // 
$sintomas= isset($_POST["txtSintomas"])?$_POST["txtSintomas"]:"";               // 
$medico= isset($_POST["cboMedico"])?$_POST["cboMedico"]:"NULL";   // 
$observaciones= isset($_POST["txtObservaciones"])?$_POST["txtObservaciones"]:"";     
$diagnostico= isset($_POST["txtDiagnostico"])?$_POST["txtDiagnostico"]:"";   //
$paramedico= isset($_POST["cboParaMedico"])?$_POST["cboParaMedico"]:"NULL";     // 
$area= isset($_POST["cboArea"])?$_POST["cboArea"]:"NULL";   //
$patologia= isset($_POST["cboPatologias"])?$_POST["cboPatologias"]:"NULL";   //
$remitido= isset($_POST["cboRemitido"])?$_POST["cboRemitido"]:"NULL";   //
$reposo= isset($_POST["cboReposo"])?$_POST["cboReposo"]:"NULL";   //
$turno= isset($_POST["txtTurno"])?$_POST["txtTurno"]:"NULL";    //
$observacionMed= isset($_POST["txtObservacionMed"])?$_POST["txtObservacionMed"]:"";  
$indicaciones= isset($_POST["txtIndicaciones"])?$_POST["txtIndicaciones"]:"";   //
$fechaProxCita= isset($_POST["txtFechaProxCita"])?$_POST["txtFechaProxCita"]:"";   //
$referencia= isset($_POST["txtreferencia"])?$_POST["txtreferencia"]:"";   //
$condicion= isset($_POST["cbocondicion"])?$_POST["cbocondicion"]:"";   //

$fresp= isset($_POST["txtfresp"])?$_POST["txtfresp"]:"";
$pulso= isset($_POST["txtpulso"])?$_POST["txtpulso"]:"";
$temper= isset($_POST["txttemper"])?$_POST["txttemper"]:"";
$tart= isset($_POST["txttart"])?$_POST["txttart"]:"";
$fcard= isset($_POST["txtfcard"])?$_POST["txtfcard"]:"";

$talla= isset($_POST["txttalla"])?$_POST["txttalla"]:"";
$peso= isset($_POST["txtpeso"])?$_POST["txtpeso"]:"";
$imc= isset($_POST["txtimc"])?$_POST["txtimc"]:"";

if ($turno=="") $turno="NULL";

if ($fechaProxCita=="")
    $fechaProxCita="NULL";
else
    $fechaProxCita="'" . $fechaProxCita . "'";

//$codRemedios= isset($_POST["codRemedios"])?implode(", ", noescape($_POST["codRemedios"])):null;   //
//$cantidades= isset($_POST["cantidades"])?implode(", ", noescape($_POST["cantidades"])):null;   //

//echo("Arreglo:" . $_POST["codRemedios"]); 
  
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar la cedula
//
$query="Select ci, now() as hoy from tbl_pacientes where uid_paciente=" . $paciente;
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
$numReg = pg_num_rows($resultado);
if($numReg==0){
  //el registro no existe
  echo("1");
  echo($query);
  pg_close($conexion);
  die("");
}else{
  $rowpaciente = pg_fetch_array($resultado);
  $cedula = $rowpaciente['ci'];
  $fecha = $rowpaciente['hoy'];
}

$queryy = "insert into tbl_consulta (id_paciente, fecha, id_motivo, sintomas, id_medico, observaciones, id_paramedico, id_area, id_patologia, id_remitido, id_reposo, turno, observacion_medicamentos, indicaciones_comp, referencia_medica, fecha_prox_cita, condicion, resultado_eva) values (";
$queryy = $queryy . $paciente . ",'".$fecha."'," . $motivo .", '" . $sintomas . "'," . $medico . ",'" . $observaciones . "'," . $paramedico . "," . $area . "," . $patologia . "," . $remitido . "," . $reposo . "," . $turno . ",'" . $observacionMed . "','" . $indicaciones . "','" . $referencia . "'," . $fechaProxCita . ", '".$condicion."', '".$diagnostico."') returning uid; ";

$resultado = pg_query($conexion, $queryy) or die("Error en la Consulta SQL:" . $queryy);
$rowconsulta = pg_fetch_array($resultado);
$id_consulta = $rowconsulta['uid'];

$queryhistoria="SELECT uid_historia FROM tbl_historia_medica where uid_paciente=".$paciente;
$resultadohistoria = pg_query($conexion, $queryhistoria) or die("Error en la Consulta SQL: " . $queryhistoria);
$rowhistoria = pg_fetch_array($resultadohistoria);
$idhistoria = $rowhistoria['uid_historia'];

if ($medico=="NULL")
   $medico=$paramedico;

$queryUpdateHistpac = "INSERT INTO tbl_historia_paciente(fk_historia, fecha_historia, indice, motivo_historia, fk_medico, observacion, fk_consulta) VALUES (";
$queryUpdateHistpac=$queryUpdateHistpac.$idhistoria.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$fecha."', ";
$queryUpdateHistpac=$queryUpdateHistpac."(select max(indice) + 1 from tbl_historia_paciente where fk_historia=".$idhistoria."), ";
$queryUpdateHistpac=$queryUpdateHistpac."(SELECT descripcion FROM tbl_motivos WHERE uid=".$motivo."), ";
$queryUpdateHistpac=$queryUpdateHistpac.$medico.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$observaciones."',";
$queryUpdateHistpac=$queryUpdateHistpac.$id_consulta.");";

$resultado = pg_query($conexion, $queryUpdateHistpac) or die("Error en la Consulta SQL: " . $queryUpdateHistpac);

//Inserta signos vitales
if (($fresp!="") || ($fcard!="") || ($fresp!="") || ($pulso!="") || ($pulso!="") || ($tart!=""))
  { 
    $querySigvitales = "INSERT INTO tbl_signos_vitales(cedula, fresp, fcard, pulso, temper, tart, fecha) VALUES ('";
    $querySigvitales = $querySigvitales.$cedula."', '".$fresp."', '".$fcard."', '".$pulso."', '".$pulso."', '".$tart."', '".$fecha."') ;";
    $resultado = pg_query($conexion, $querySigvitales) or die("Error en la Consulta SQL: " . $querySigvitales);
  }

//Inserta datos antopometricos
if (($talla!="") || ($peso!="") || ($imc!=""))
{
    $queryDatosAntop = "INSERT INTO tbl_datos_antropometricos(cedula, talla, peso, imc, fecha) VALUES ('";
    $queryDatosAntop = $queryDatosAntop.$cedula."', '".$talla."', '".$peso."', '".$imc."', '".$fecha."') ;";
    $resultado = pg_query($conexion, $queryDatosAntop) or die("Error en la Consulta SQL: " . $queryDatosAntop);
}

//Inserta el registro de Medicamentos
//
$i=0;

if(array_key_exists('codRemedios',$_POST))
{
  $codRemedios = $_POST['codRemedios'];
  
  //echo($codRemedios);
  //die();
  
  $cantidades = $_POST['cantidades'];
  $medidas =  $_POST['medida'];
  
  foreach ($codRemedios as &$remedio)
  {
    $cantidad = $cantidades[$i];
    $md=$medidas[$i];
    $query = "insert into tbl_medicamentos_consulta (id_consulta, id_medicamento, cantidad, medidas) values (";
    $query = $query . $id_consulta . "," . $remedio . "," . $cantidad . ", '".$md."'); ";
  
    //echo($query);
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
  
    $i++;
  }

}
pg_free_result($resultado);
pg_close($conexion);

echo ($id_consulta); //OK

?>
