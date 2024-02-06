<?PHP
require("include_conex.php");

$uid = isset($_POST["txtUid"])?$_POST["txtUid"]:"";
$ci = isset($_POST["txtCi"])?$_POST["txtCi"]:"";                    //
$nombre= isset($_POST["txtNombre"])?$_POST["txtNombre"]:"";         //
$apellido= isset($_POST["txtApellido"])?$_POST["txtApellido"]:"";   // 
$departamento= isset($_POST["cboDepartamento"])?$_POST["cboDepartamento"]:"";               // 
$contratista= isset($_POST["cboContratista"])?$_POST["cboContratista"]:"";     // 
$chkContratista= isset($_POST["chkContratista"])?$_POST["chkContratista"]:"";   //
$fechaNac= isset($_POST["txtFechaNac"])?$_POST["txtFechaNac"]:"";     // 
$sexo= isset($_POST["cboSexo"])?$_POST["cboSexo"]:"";   //
$cargo= isset($_POST["txtCargo"])?$_POST["txtCargo"]:"";   //
$tipo_sangre= isset($_POST["txttipo_sangre"])?$_POST["txttipo_sangre"]:"";   //
$mano_dominante= isset($_POST["cbomano_dominante"])?$_POST["cbomano_dominante"]:"";
//
$turno = isset($_POST["txTurno"])?$_POST["txTurno"]:"";
$frecuencia_rotacion = isset($_POST["txfrecuencia_rotacion"])?$_POST["txfrecuencia_rotacion"]:"";
$nivel_educativo=isset($_POST["cbonivel_educativo"])?$_POST["cbonivel_educativo"]:"";
$fecha_ingreso=isset($_POST["txtfecha_ingreso"])?$_POST["txtfecha_ingreso"]:"";
$antiguedad_puesto=isset($_POST["txtantiguedad_puesto"])?$_POST["txtantiguedad_puesto"]:"";
$tipo_vivienda=isset($_POST["cbotipo_vivienda"])?$_POST["cbotipo_vivienda"]:"";
$vivienda_propia=isset($_POST["cbovivienda_propia"])?$_POST["cbovivienda_propia"]:"";
$medio_transp_trabajo=isset($_POST["cbomedio_transp_trabajo"])?$_POST["cbomedio_transp_trabajo"]:"";

$nacionalidad= isset($_POST["txtNacionalidad"])?$_POST["txtNacionalidad"]:"";
$telefono= isset($_POST["txtTelefono"])?$_POST["txtTelefono"]:"";
$direccion_hab= isset($_POST["txtdireccion_hab"])?$_POST["txtdireccion_hab"]:"";
$lugar_nac= isset($_POST["txtlugar_nac"])?$_POST["txtlugar_nac"]:"";
$edo_civil= isset($_POST["cboEdoCivil"])?$_POST["cboEdoCivil"]:"";

$discapacidad=isset($_POST["discapacidad"])?$_POST["discapacidad"]:"";
$tipo_disca=isset($_POST["tipo_disca"])?$_POST["tipo_disca"]:"";
$alergia=isset($_POST["alergia"])?$_POST["alergia"]:"";

$estado_paciente=isset($_POST["estado_paciente"])?$_POST["estado_paciente"]:"";

if ($chkContratista=="on")
	$escontratista="true";
else
	$escontratista="false";
	
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar el id
//
$query="Select * from tbl_pacientes where uid_paciente=" . $uid . "";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

if($numReg==0){
	//el registro no existe
	echo("1");
	pg_close($conexion);
	die("");
}

$query = "update tbl_pacientes set ";
$query = $query . "ci='" . $ci . "',";
$query = $query . "nombre='" . $nombre . "',";
$query = $query . "id_departamento=" . $departamento . ",";
$query = $query . "id_contratista=" . $contratista . ",";
$query = $query . "es_contratista=" . $escontratista . ",";
$query = $query . "fechaNac='" . $fechaNac . "',";
$query = $query . "sexo='" . $sexo . "',";
$query = $query . "cargo='" . $cargo . "',";
$query = $query . "mano_dominante='" . $mano_dominante . "',";
$query = $query . "tipo_sangre='" . $tipo_sangre . "',";
//
$query = $query . "turno='" . $turno . "',";
$query = $query . "frecuencia_rotacion='" . $frecuencia_rotacion . "',";
$query = $query . "nivel_educativo='" . $nivel_educativo . "',";
$query = $query . "fecha_ingreso='" . $fecha_ingreso . "',";
$query = $query . "antiguedad_puesto='" . $antiguedad_puesto . "',";
$query = $query . "tipo_vivienda='" . $tipo_vivienda . "',";
$query = $query . "vivienda_propia='" . $vivienda_propia . "',";
$query = $query . "medio_transp_trabajo='" . $medio_transp_trabajo . "',";
$query = $query . "nacionalidad='" . $nacionalidad . "',";
$query = $query . "telefono='" . $telefono . "',";
$query = $query . "direccion_hab='" . $direccion_hab . "',";
$query = $query . "lugar_nac='" . $lugar_nac . "',";
$query = $query . "edo_civil='" . $edo_civil . "',";
$query = $query . "desc_discapacidad='" . $discapacidad . "',";
$query = $query . "tipo_discapacidad='" . $tipo_disca . "',";
$query = $query . "alergia='" . $alergia . "',";
$query = $query . "estado_paciente='" . $estado_paciente . "'";
//
$query = $query . " where uid_paciente=" .$uid . ";";

//echo($query);

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

pg_close($conexion);

echo ("0"); //OK

?>
