<?PHP
require_once('libs/conexion.php');
session_start();

$cedula= isset($_POST["hddcedula"])?$_POST["hddcedula"]:"NULL";   //
$direccion= isset($_POST["hdddireccion"])?$_POST["hdddireccion"]:"NULL";   //
$tipo_personal= isset($_POST["cbotipo_personal"])?$_POST["cbotipo_personal"]:"NULL";   //
$fkmotivo= isset($_POST["cbomotivo"])?$_POST["cbomotivo"]:"NULL";   //
$nombres= isset($_POST["nombre"])?$_POST["nombre"]:"NULL";   //
$departamento= isset($_POST["cboDepartamento"])?$_POST["cboDepartamento"]:"NULL";   //
$jefe_inmediato= isset($_POST["txtResponsable"])?$_POST["txtResponsable"]:"NULL";   //

if (isset($_SESSION['username_ca'])) {
		$cn=  Conectarse();
		$queryy = "INSERT INTO acceso_personal_foraneo (";
		$queryy .= "fecha_acceso, ";
		$queryy .= "cedula, ";
		$queryy .= "direccion,";
		$queryy .= "tipo_personal, ";
		$queryy .="fkmotivo, ";
		$queryy .= "nombres, ";		
		$queryy .= "departamento, ";
		$queryy .= "responsable, ";
		$queryy .= "usuario, ";
		$queryy .="fk_unidad ";		
		$queryy .= ") VALUES (";
		$queryy .= "NOW(), ";		
		$queryy .= "'" . $cedula."', ";
		$queryy .= "'".$direccion."', ";
		$queryy .= "'".$tipo_personal."', ";
		$queryy .= $fkmotivo . ", " ;
		$queryy .= "'".$nombres."', ";		
		$queryy .= "(select descripcion_unidad from v_departamentos_jefes where idunidad=".$departamento."), ";
		$queryy .= "'".$jefe_inmediato."', ";
		$queryy .= "'".$_SESSION['user_session_ca']."', ";
		$queryy .= $departamento." ";
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