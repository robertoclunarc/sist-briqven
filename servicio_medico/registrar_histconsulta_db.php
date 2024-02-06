<?PHP
require("include_conex.php");
session_start();

$_SESSION['user_session'] = isset($_COOKIE["user_session"])?$_COOKIE["user_session"]:$_SESSION['user_session'];
$_SESSION['username']	= isset($_COOKIE["username"])?$_COOKIE["username"]:$_SESSION['username'];
$_SESSION['userid']	= isset($_COOKIE["userid"])?$_COOKIE["userid"]:$_SESSION['userid'];
$_SESSION['nivel']	= isset($_COOKIE["nivel"])?$_COOKIE["nivel"]:$_SESSION['nivel'];
$_SESSION['estatususer']= isset($_COOKIE["estatususer"])?$_COOKIE["estatususer"]:$_SESSION['estatususer'];

$paciente = isset($_POST["txtId"])?$_POST["txtId"]:"-1";    //         
$motivo= isset($_POST["motivo_historia"])?$_POST["motivo_historia"]:"9"; //   
$paramedico= isset($_POST["cboParaMedico"])?$_POST["cboParaMedico"]:"NULL";     //            
$medico= isset($_POST["idmedico"])?$_POST["idmedico"]:"NULL";   //    
$condicion= isset($_POST["estado_paciente"])?$_POST["estado_paciente"]:"N/A";
$idhistoria = isset($_POST["txtIdh"])?$_POST["txtIdh"]:"-1";
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$observacion_historia= isset($_POST["observacion_historia"])?$_POST["observacion_historia"]:"";   //
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar la cedula
//

$query="Select descripcion from tbl_motivos where uid=" . $motivo;
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
$rowmotivo = pg_fetch_array($resultado);
$sintomas= $rowmotivo['descripcion']; 
pg_free_result($resultado);

$query="Select ci, now() as hoy, area from tbl_pacientes, tbl_departamentos where id_departamento=uid and uid_paciente=" . $paciente;
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
$numReg = pg_num_rows($resultado);
if($numReg==0){
  //el registro no existe
  echo("0");
  echo($query);
  pg_free_result($resultado);
  pg_close($conexion);
  die("");
}else{
  $rowpaciente = pg_fetch_array($resultado);
  $cedula = $rowpaciente['ci'];
  $fecha = $rowpaciente['hoy'];
  $area= $rowpaciente['area'];
}
pg_free_result($resultado);

$query="Select observacion from tbl_examen_fisico where cedula = '".$cedula."' and fk_fisico=84 GROUP BY observacion HAVING max(fecha_examen) >= to_char(now(), 'YYYY-MM-DD')::date;";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
if (pg_num_rows($resultado) > 0)
{
	$rowobsercacion = pg_fetch_array($resultado);
	$observaciones= $observacion_historia."; ".$rowobsercacion['observacion'];
}
else
	$observaciones= $observacion_historia;
pg_free_result($resultado);

$queryy = "insert into tbl_consulta (id_paciente, fecha, id_motivo, sintomas, id_medico, id_area, id_patologia, turno, condicion, observacion_medicamentos, id_paramedico) values (";
$queryy = $queryy . $paciente . ",'".$fecha."'," . $motivo .", '" . $sintomas . "'," . $medico . "," . $area . ",1,1, '".$condicion."', '".$observaciones."', ".$paramedico.") returning uid;";

$resultado = pg_query($conexion, $queryy) or die("Error en la Consulta SQL:" . $queryy);
$rowconsulta = pg_fetch_array($resultado);
$id_consulta = $rowconsulta['uid'];

$queryind = "select max(COALESCE(indice,0)) + 1 as maxind from tbl_historia_paciente where fk_historia=".$idhistoria;
$recs = pg_query($conexion, $queryind) or die("Error en la Consulta SQL:" . $queryind);
if ($recs['maxind']!='')
	$id_indice = $recs['maxind'];
else
	$id_indice = 0; 

$queryUpdateHistpac = "INSERT INTO tbl_historia_paciente(fk_historia, fecha_historia, indice, motivo_historia, fk_medico, observacion, fk_consulta) VALUES (";
$queryUpdateHistpac=$queryUpdateHistpac.$idhistoria.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$fecha."', ";
$queryUpdateHistpac=$queryUpdateHistpac.$id_indice.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$sintomas."', ";
$queryUpdateHistpac=$queryUpdateHistpac.$medico.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$observaciones."',";
$queryUpdateHistpac=$queryUpdateHistpac.$id_consulta.");";

$resultado = pg_query($conexion, $queryUpdateHistpac) or die("Error en la Consulta SQL: " . $queryUpdateHistpac);

/////// Envio de correo al area de administracion de personal en caso de ser vacacion o egreso
//
$resp="";
$query="SELECT m.ci, m.fecha, m.motivo, c.idmotivo, m.cargo,m.departamento, m.paramedico, m.medico, m.nombre_completo, m.nombres_jefe, c.condicion, m.sexo, m.reposo FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
  $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
  $rowmorb = pg_fetch_array($resultmorb);

$desc_mot=$rowmorb['motivo'];
$idmot=$rowmorb['idmotivo'];
$nombre_completo=$rowmorb['nombre_completo'];
$mor_sex=$rowmorb['sexo'];
$mor_ci=$rowmorb['ci'];
$nom_paramedico=$rowmorb['paramedico'];
$nom_medico=$rowmorb['medico'];
$mor_cond=$rowmorb['condicion'];
$mor_fecha=substr($rowmorb['fecha'],0,16);
$mor_depar=$rowmorb['departamento'];
$mor_cargo=$rowmorb['cargo'];
$mor_nomjefe=$rowmorb['nombres_jefe'];
$mor_reposo=$rowmorb['reposo'];
pg_free_result($resultmorb);
require("enviodecorreos.php");
if (($idmot==7) || ($idmot==8) || ($idmot==9) || ($idmot==10))
{
	$asunto=$desc_mot." ".$nombre_completo;

	if ($mor_sex=='M')
		$enc='<p>&nbsp;</p>Se le notifica que el Sr. ';
	else
		$enc='<p>&nbsp;</p>Se le notifica que la Sra. ';
	
	$cuerpo=$enc.'<strong>'.$nombre_completo.'</strong>, con Cedula de Identidad <strong>'.$mor_ci.'</strong>, se realiz&oacute; el examen correspondiente a <strong>'.$desc_mot.'</strong>.<br>';
	
	if ($nom_medico == '')
		$atendio='param&eacute;dico ocupante <strong>'.$nom_paramedico.'</strong>';
	else
		$atendio='m&eacute;dico ocupante <strong>'.$nom_medico.'</strong>';

	if ($mor_cond=="APTO")
		$colorcondicion='<font color="green"><strong>'.$mor_cond.'</strong></font>';
	else
		$colorcondicion='<font color="red"><strong>'.$mor_cond.'</strong></font>';
	
	$cuerpo .= 'El cual est&aacute; en condici&oacute;n: '.$colorcondicion.' seg&uacute;n lo considerado en la consulta m&eacute;dica registrada en la fecha: <strong>'.$mor_fecha.'</strong> por el '.$atendio.'.<br>';

	if ($mor_sex=='M')
		$cuerpo .= 'Otros datos de inter&eacute;s sobre el trabajador:<br>';
	else	
		$cuerpo .= 'Otros datos de inter&eacute;s sobre la trabajadora:<br>';
	
	$cuerpo .= '<table><thead><tr><th>Departamento:</th><th>'.$mor_depar.'</th></tr>';
	$cuerpo .= '<tr><th>Cargo:</th><th>'.$mor_cargo.'</th></tr>';
	$cuerpo .= '<tr><th>Supervisor:</th><th>'.$mor_nomjefe.'</th></tr></thead></table>';
	$cuerpo .= '<br>';
	$cuerpo .= '<br>';
	$cuerpo .= 'Para m&aacute;s informaci&oacute;n por favor debe comunicarse con el &aacute;rea de Servicio M&eacute;dico a la Ext. Nro. 259.<p>&nbsp;</p>';

    $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['userid'], $desc_mot);
    
}
if (($idmot==7) || ($idmot==8) || ($idmot==9) || ($idmot==10)  || ($idmot==13))
{
  require("planilla_certificado.php");

  $resp1=ENVIAR_CORREO(planilla_certificado($id_consulta),"Certificado Medico ".$nombre_completo,"",$_SESSION['userid'], $desc_mot);

  //$resp1=ENVIAR_CORREO(planilla_certificado($id_consulta),"Certificado Medico ".$nombre_completo,"",$_SESSION['userid'], "PRUEBA");
}

pg_free_result($resultado);
pg_close($conexion);
if ($resp=="Correo Enviado!")
	echo ($id_consulta); //OK
else
	echo ("Registro Guardado. ".$resp);
?>
