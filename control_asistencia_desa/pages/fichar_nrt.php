<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");

	$conn = Conectarse_sitt();

	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";

	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

	$hinicio1= isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";  
	$hfinal1= isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL"; 

	$hinicio2= isset($_POST["txthoraini2"])?$_POST["txthoraini2"]:"NULL";  
	$hfinal2= isset($_POST["txthorafin2"])?$_POST["txthorafin2"]:"NULL"; 

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');

	$hinix = date_create($hinicio1);
	$hini1 = date_format($hinix, 'H:i');

	$hfinz = date_create($hfinal1);
	$hfin1 = date_format($hfinz, 'H:i');

	if ($hinicio2!="NULL" && $hfinal2!="NULL" && $hinicio2!="" && $hfinal2!="")
	{	
		$hinik = date_create($hinicio2);
		$hini2 = date_format($hinik, 'H:i');
		$hfinj = date_create($hfinal2);
		$hfin2 = date_format($hfinj, 'H:i');
	}
	else
	{
		$hini2 = '';	
		$hfin2 = '';
	}

	$tiempoInicio = strtotime($fini1);
	$tiempoFin = strtotime($ffin1);
	# 24 horas * 60 minutos por hora * 60 segundos por minuto
	$dia = 86400;
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	while($tiempoInicio <= $tiempoFin){
		# Podemos recuperar la fecha actual y formatearla	
		$fechaActual = date("Y-m-d", $tiempoInicio);
		//printf("Fecha dentro del ciclo: %s\n", $fechaActual);
		$stmt = $conn->prepare("EXEC dbo.poner_fichada_parcial ?, ?, ?, ?, ?, ?, ?");
		$stmt->bindParam(1, $fechaActual, PDO::PARAM_STR,10);
		$stmt->bindParam(2, $trabajador,  PDO::PARAM_INT,10);
		$stmt->bindParam(3, $hini1,  PDO::PARAM_STR,5);
		$stmt->bindParam(4, $hfin1,  PDO::PARAM_STR,5);
		$stmt->bindParam(5, $hini2,  PDO::PARAM_STR,5);
		$stmt->bindParam(6, $hfin2,  PDO::PARAM_STR,5);
		$stmt->bindParam(7, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10); 
		$stmt->execute();
		# Sumar el incremento para que en algún momento termine el ciclo
		$tiempoInicio += $dia;
	}
	echo "Procesado";
}	
else
	echo "De Iniciar Sesion";
?>
