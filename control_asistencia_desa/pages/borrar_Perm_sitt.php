<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');

	try {
		$link4=Conex_Contancia_pgsql();
		
		$trabajador= isset($_GET["ced"])?$_GET["ced"]:"NULL";
		$nroperm = isset($_GET["nroper"])?$_GET["nroper"]:"0";
		$tipoNotif= isset($_GET["tiponotif"])?$_GET["tiponotif"]:"NULL";
		$idAusencia = isset($_GET["idausencia"])?$_GET["idausencia"]:"NULL";

		if ($tipoNotif=='PERMISO' && $nroperm!='' && $nroperm!='0'){
			$conn = Conectarse_sitt();
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			$stmt = $conn->prepare("EXEC dbo.SW_BORRA_PERMISO ?, ?");
			$stmt->bindParam(1, $nroperm, PDO::PARAM_INT,10);
			$stmt->bindParam(2, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);		
			$stmt->execute();
			$conn=null;
			$stmt=null;
		}

		$queryDelete="UPDATE sw_permisos SET estado='B' WHERE idausencia=$idAusencia";
		$result = pg_query($link4, $queryDelete) or die("Error en la Consulta SQL: ".$queryDelete);
		
		$operacion='BORRA $tiponotif, idausencia = $idAusencia, cedula='.$trabajador;
		auditar($operacion, $_SESSION['user_session_const'], $link4);
		pg_close($link4);
		echo "Procesado";
		
	} catch (Exception $e) {
		echo $e;
	}
		
}	
else
	echo "Debe Iniciar Sesion";

?>
