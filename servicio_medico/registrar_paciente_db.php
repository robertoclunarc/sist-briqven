<?PHP
require("include_conex.php");
function edad($fechanac){
    $cumpleanos = new DateTime($fechanac);
    $hoy = new DateTime();
    $annos = $hoy->diff($cumpleanos);
    return $annos->y;
}
if (!isset($_SESSION['username']))
    session_start();

$ci = isset($_POST["txtCi"])?$_POST["txtCi"]:"";                    //
$nombre= isset($_POST["txtNombre"])?$_POST["txtNombre"]:"";         //
$apellido= isset($_POST["txtApellido"])?$_POST["txtApellido"]:"";   // 
$departamento= isset($_POST["cboDepartamento"])?$_POST["cboDepartamento"]:""; //
$contratista= isset($_POST["cboContratista"])?$_POST["cboContratista"]:"";     // 
$chkContratista= isset($_POST["chkContratista"])?$_POST["chkContratista"]:"";   //
$fechaNac= isset($_POST["txtFechaNac"])?$_POST["txtFechaNac"]:"";     // 
$sexo= isset($_POST["cboSexo"])?$_POST["cboSexo"]:"";   //
$tipo_sangre= isset($_POST["txttipo_sangre"])?$_POST["txttipo_sangre"]:"";   //
$mano_dominante= isset($_POST["cbomano_dominante"])?$_POST["cbomano_dominante"]:"";  
$cargo= isset($_POST["txtCargo"])?$_POST["txtCargo"]:"";   //
$discapacidad=isset($_POST["discapacidad"])?$_POST["discapacidad"]:"";
$tipo_disca=isset($_POST["tipo_disca"])?$_POST["tipo_disca"]:"";
$alergia=isset($_POST["alergia"])?$_POST["alergia"]:"";

$turno=isset($_POST["txTurno"])?$_POST["txTurno"]:"";
$antiguedad_puesto=isset($_POST["txtantiguedad_puesto"])?$_POST["txtantiguedad_puesto"]:"";
$fecha_ingreso=isset($_POST["txtfecha_ingreso"])?$_POST["txtfecha_ingreso"]:"";
$lugar_nac=isset($_POST["txtlugar_nac"])?$_POST["txtlugar_nac"]:"";
$edo_civil=isset($_POST["cboEdoCivil"])?$_POST["cboEdoCivil"]:"";
$telefono=isset($_POST["txtTelefono"])?$_POST["txtTelefono"]:"";
$nacionalidad=isset($_POST["txtNacionalidad"])?$_POST["txtNacionalidad"]:"";
$direccion_hab=isset($_POST["txtdireccion_hab"])?$_POST["txtdireccion_hab"]:"";
$frecuencia_rotacion=isset($_POST["txfrecuencia_rotacion"])?$_POST["txfrecuencia_rotacion"]:"";
$nivel_educativo=isset($_POST["cbonivel_educativo"])?$_POST["cbonivel_educativo"]:"";
$tipo_vivienda=isset($_POST["cbotipo_vivienda"])?$_POST["cbotipo_vivienda"]:"";
$vivienda_propia=isset($_POST["cbovivienda_propia"])?$_POST["cbovivienda_propia"]:"";
$medio_transp_trabajo=isset($_POST["cbomedio_transp_trabajo"])?$_POST["cbomedio_transp_trabajo"]:"";
$estado_paciente=isset($_POST["estado_paciente"])?$_POST["estado_paciente"]:"";

if ($antiguedad_puesto=="")
   $antiguedad_puesto=$fecha_ingreso;

if ($chkContratista=="on")
	$escontratista="true";
else
	$escontratista="false";
	
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar la cedula
//
$query="Select * from tbl_pacientes where ci='" . $ci . "'";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

if($numReg>0){
	//el registro existe
	echo("-1");
	pg_close($conexion);
	die("");
}

$query = "set datestyle = 'SQL, DMY'; INSERT INTO tbl_pacientes(
            ci, nombre, apellido, id_departamento, es_contratista, 
            fechanac, sexo, cargo, turno, antiguedad_puesto, fecha_ingreso, 
            tipo_sangre, lugar_nac, edo_civil, nacionalidad, telefono, direccion_hab, 
            fecharegistro, id_contratista, mano_dominante, frecuencia_rotacion, 
            nivel_educativo, tipo_vivienda, vivienda_propia, medio_transp_trabajo, 
            alergia, tipo_discapacidad, desc_discapacidad, estado_paciente)
    VALUES (";
$query = $query . "'" . $ci . "', upper('" . $nombre ."'), upper('" . $apellido . "')," . $departamento . ",'" . $escontratista . "', '" . $fechaNac . "','" . $sexo. "','" . $cargo . "', '".$turno."', '".$antiguedad_puesto."', '".$fecha_ingreso."', '".$tipo_sangre."', '".$lugar_nac."', '".$edo_civil."', '".$nacionalidad."', '".$telefono."', '".$direccion_hab."', now(), ".$contratista.", '".$mano_dominante."', '".$frecuencia_rotacion."', '".$nivel_educativo."', '".$tipo_vivienda."', '".$vivienda_propia."', '".$medio_transp_trabajo."' ,'".$alergia."','".$tipo_disca."', '".$discapacidad."', '".$estado_paciente."') returning uid_paciente;";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
$uidp=$reg['uid_paciente'];
pg_free_result($resultado);

$cuerpo="Se Notifica que fue registrado un nuevo paciente para EXAMEN PRE-EMPLEO. Puede ver su registro en el modulo de Consultas <<Listar PRE-EMPLEADOS>> del Sistema Servicio Medico. El cual fue ingresado por <strong>".$_SESSION['username']."</strong>. <br>Datos Generales del Paciente:<br>Nombre y Apellido: ".$nombre." ".$apellido.".<br>Cedula: ".$ci."<br>Cargo: ".$cargo."<br>Departamento: ".$departamento."<br>Sexo: ".$sexo."<br>Edad: ".edad($fechaNac)."<br>Discapacidad: ".$tipo_disca."<br>Alergia: ".$alergia;

require("enviodecorreos.php");
$resp=ENVIAR_CORREO($cuerpo,"Registro de Paciente ".$nombre." ".$apellido,"",$_SESSION['userid'], "INGRESO");

//echo($query);

pg_close($conexion);
echo ($uidp); //OK
//echo 'entro en el echo!';
?>