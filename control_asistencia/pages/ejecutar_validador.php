<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	if ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const'] ==2)
	{
		include("../BD/conexion.php");
		$fecha_a =date("Y-m-d");
		$conn = Conectarse_sitt();
			
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
			
		$stmt = $conn->prepare("EXEC PROC_VALIDADOR_HT_PERIODICO_N_MENSUAL");
		
		$stmt->execute();
		
		echo "Procesado";
	}
	else
	   echo "No tiene Permiso Para Esta Operacion";	
}	
else
	echo "Debe Iniciar Sesion Para Realizar Esta Peticion";
?>
