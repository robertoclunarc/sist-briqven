<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');
	$conn = Conectarse_sitt();

	//print_r($_POST);

	$ciclo= isset($_POST["cbociclolaboral"])?$_POST["cbociclolaboral"]:"NULL";
	$oper= isset($_POST["cbooperacion"])?$_POST["cbooperacion"]:"NULL";
	$estat= isset($_POST["cboestatus"])?$_POST["cboestatus"]:"NULL"; 

	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$fpago= isset($_POST["txtfpago"])?$_POST["txtfpago"]:"NULL";

	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$trabajador=str_pad($trabajador, 10, " ", STR_PAD_LEFT);

	$DispVac=isset($_POST["cboDispVac"])?$_POST["cboDispVac"]:"NULL";
	
	$CondVac=isset($_POST["cboCondVac"])?$_POST["cboCondVac"]:"NULL";
	$InterrupcionInicio=isset($_POST["txtInterrupcionInicio"])?$_POST["txtInterrupcionInicio"]:"NULL";
	$InterrupcionFin=isset($_POST["txtInterrupcionFin"])?$_POST["txtInterrupcionFin"]:"NULL";
	$DiasPendientes=isset($_POST["txtsDiasPendientes"])?$_POST["txtsDiasPendientes"]:"NULL";
	$Motivo=isset($_POST["txtMotivo"])?$_POST["txtMotivo"]:'';



	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');

	$fpagox = date_create($fpago);
	$fpag = date_format($fpagox, 'Y-m-d');

	
	if ($CondVac=='Reposo' || $CondVac=='Pausada'){
		$IntInicio = date_create($InterrupcionInicio);
		$IntInicio = date_format($IntInicio, 'Y-m-d');

		$IntFin = date_create($InterrupcionFin);
		$IntFin = date_format($IntFin, 'Y-m-d');
	}
	else{
		$IntInicio=NULL;
		$IntFin=NULL;
		//$DiasPendientes=NULL;

	}

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'updatevacation.php', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){	
	
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($CondVac == 'Planificada')
			$estat=1;
		elseif ($CondVac == 'Programada')
			$estat=2;
		else $estat=3;


		/*$sql="EXEC dbo.sp_modificar_vacaciones ".$ciclo.", '".$trabajador."', '".$fini1."', '".$ffin1."', '".$fpag."', ".$estat.", '".$oper."', '".$DispVac."', '".$CondVac."', ".$DiasPendientes.", '".$Motivo."', '". $IntInicio."', '".$IntFin."';";*/
			
		$stmt = $conn->prepare("EXEC dbo.sp_modificar_vacaciones ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?");

		$stmt->bindParam(1, $ciclo, PDO::PARAM_INT,10);
		$stmt->bindParam(2, $trabajador,  PDO::PARAM_STR,10);
		$stmt->bindParam(3, $fini1,  PDO::PARAM_STR,10);
		$stmt->bindParam(4, $ffin1,  PDO::PARAM_STR,10);
		$stmt->bindParam(5, $fpag,  PDO::PARAM_STR,10);
		$stmt->bindParam(6, $estat,  PDO::PARAM_INT,10);
		$stmt->bindParam(7, $oper,  PDO::PARAM_STR,10);
		
		$stmt->bindParam(8, $DispVac,  PDO::PARAM_STR,20);
		
		
					
		$stmt->bindParam(9, $CondVac,  PDO::PARAM_STR,20);
		
		
		if ($DiasPendientes)
			$stmt->bindParam(10, $DiasPendientes,  PDO::PARAM_INT);
		else
			$stmt->bindParam(10, $DiasPendientes,  PDO::PARAM_INT);
		
		
		$stmt->bindParam(11, $Motivo,  PDO::PARAM_STR);
		
		
		if ($IntInicio)
			$stmt->bindParam(12, $IntInicio,  PDO::PARAM_STR,10);
		else
			$stmt->bindParam(12, $IntInicio,  PDO::PARAM_INT);
		
		if ($IntFin)
			$stmt->bindParam(13, $IntFin,  PDO::PARAM_STR,10); 
		else
			$stmt->bindParam(13, $IntFin,  PDO::PARAM_INT);
		
		
		$stmt->execute();
		//echo $sql;
		echo "Procesado";
	}
	else{
		echo "No tiene privilegio para esta operacion";
	}		
}	
else
	echo "Debe Iniciar Sesion";
?>
