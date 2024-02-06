<?PHP
require_once('libs/conexion.php');
session_start();

$cedula= isset($_POST["hddcedula"])?$_POST["hddcedula"]:"NULL";   //
$direccion= isset($_POST["hdddireccion"])?$_POST["hdddireccion"]:"NULL";   //
$tipo_personal= isset($_POST["cbotipo_personal"])?$_POST["cbotipo_personal"]:"NULL";   //
$fkmotivo= isset($_POST["cbomotivo"])?$_POST["cbomotivo"]:"NULL";   //
$nombres= isset($_POST["nombre"])?$_POST["nombre"]:"NULL";   //
$cargo= isset($_POST["txtcargo"])?$_POST["txtcargo"]:"NULL";   //
$departamento= isset($_POST["txtDepartamento"])?$_POST["txtDepartamento"]:"NULL";   //
$jefe_inmediato= isset($_POST["txtjefeinmediato"])?$_POST["txtjefeinmediato"]:"NULL";   //
$turno= isset($_POST["hddturno"])?$_POST["hddturno"]:"NULL";   //
 if ($turno=='')
   $turno="NULL";

if (isset($_SESSION['username_ca'])) {
		$cn=  Conectarse();
		$queryy = "INSERT INTO acceso_personal_propio (";
		$queryy .= "fecha_acceso, ";
		$queryy .= "cedula, ";
		$queryy .= "direccion,";
		$queryy .= "tipo_personal, ";
		$queryy .="fkmotivo, ";
		$queryy .= "nombres, ";
		$queryy .= "cargo, ";
		$queryy .= "departamento, ";
		$queryy .= "jefe_inmediato, ";
		$queryy .= "usuario, ";
		$queryy .= "turno ";		
		$queryy .= ") VALUES (";
		$queryy .= "NOW(), ";		
		$queryy .= "'" . $cedula."', ";
		$queryy .= "'".$direccion."', ";
		$queryy .= "'".$tipo_personal."', ";
		$queryy .= $fkmotivo . ", " ;
		$queryy .= "'".$nombres."', ";
		$queryy .= "'".$cargo."', ";
		$queryy .= "'".$departamento."', ";
		$queryy .= "'".$jefe_inmediato."', ";
		$queryy .= "'".$_SESSION['user_session_ca']."', ";
		$queryy .= $turno . " " ;				
		$queryy .= ");";

		$resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
		$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);		
		
		if (!$resultado) {
			echo "0";
		  	die("Ocurrió un error.\n ");
		}
		else
		{			
			echo "1";
		}
		pg_close($cn);
} else
    echo "0";
?>