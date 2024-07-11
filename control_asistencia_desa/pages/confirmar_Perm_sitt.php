<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');
	$link=Conex_Contancia_pgsql();
	$conn = Conectarse_sitt();
	
	$trabajador= isset($_GET["ced"])?$_GET["ced"]:"NULL";
	$nroperm = isset($_GET["nroper"])?$_GET["nroper"]:"NULL";
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	$stmt = $conn->prepare("EXEC dbo.SW_Autorizar_PERMISO ?, ?");
	$stmt->bindParam(1, $nroperm, PDO::PARAM_INT,10);
	$stmt->bindParam(2, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
	$stmt->execute();
	
	$operacion='SW_Autorizar_PERMISO nroper='.$nroperm.', cedula='.$trabajador;
	//echo $operacion;
	auditar($operacion, $_SESSION['user_session_const'], $link);
	pg_close($link);
	echo "Procesado";
}	
else
	echo "Debe Iniciar Sesion";

?>
