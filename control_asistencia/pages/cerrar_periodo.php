<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	//require_once('funciones_var.php');
	$link=Conex_rrhh_pgsql();	
	
	$idcal= isset($_GET["idcal"])?$_GET["idcal"]:"NULL";

	$Updatesql="UPDATE periodos_nomina SET abierto=FALSE, fecha_cierre=now() where id_calendario=".$idcal;
	$result = pg_query($link, $Updatesql);
	$cmdtuples = pg_affected_rows($result);
	 if ($cmdtuples==1)
	 	echo $cmdtuples." Registro afectado. Se Ha Cerrado el Periodo ".$idcal." con Exito";
	 else
	 	echo $cmdtuples."Fallo, Favor Validar";
	
	pg_close($link);		
}	
else
	echo "Debe Iniciar Sesion";

?>
