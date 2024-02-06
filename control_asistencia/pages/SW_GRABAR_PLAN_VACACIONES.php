<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");	
	require_once('funciones_var.php');
	$cedula= isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'NULL';	
	$ciclolaboral= isset($_POST['txtciclolaboral'])?$_POST['txtciclolaboral']:'NULL';
	$TipoVac= isset($_POST['cboTipoVac'])?$_POST['cboTipoVac']:'NULL';
	$finicio= isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$ffin= isset($_POST['txtffin'])?$_POST['txtffin']:'NULL';
	$fpago= isset($_POST['txtfpago'])?$_POST['txtfpago']:'NULL';
	$sDias= isset($_POST['txtsDias'])?$_POST['txtsDias']:'NULL';
	
	$mbd=Conectarse_sitt();

	$stmt1 = $mbd->prepare("EXEC SW_con_datos_basicos_persona_vacacion ?");
    $stmt1->bindParam(1, $cedula, PDO::PARAM_INT,10);
	$stmt1->execute();

	while ($fila1 = $stmt1->fetch())
	{		
		//$NOMBRES=$fila1['NOMBRES'];	
		$CENTRO_COSTO=$fila1['CENTRO_COSTO'];		
		//$DESC_CCOSTO=$fila1['DESC_CCOSTO'];		
		//$PUESTO=$fila1['PUESTO'];		
		//$DESC_PUESTO=$fila1['DESC_PUESTO'];		
		//$Fecha_ingreso=$fila1['Expr1'];		
		//$RL=$fila1['RELACION_LABORAL'];		
		//$CLASE_NOMINA=$fila1['CLASE_NOMINA'];		
		//$TURNO=$fila1['TURNO'];	
	}

	/*
	if ($RL=="A" || $RL=="B" || $RL=="C" ||  $RL=="E")
		$srl="CONVENIO";
	elseif  ($RL=="J" || $RL=="W" || $RL=="F" || $RL=="2" || $RL=="1" || $RL=="3" ||  $RL=="4" || $RL=="5")
		$srl="CONDUCCION";
	elseif ($RL=="I" || $RL=="X" || $RL=="R")
		$srl="CONFIDENCIAL";
	elseif ($RL=="L" ||  $RL=="G")
		$srl="Aprendiz";
	elseif ($RL=="H" || $RL=="T")
		$srl="Joven Entrenante";
	else
		$srl="OTROS";
	*/
		

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');

	$fpagx = date_create($fpago);
	$fpag1 = date_format($fpagx, 'Y-m-d');

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'CARGAR', 'insertvacation.php', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){
		//if ($_SESSION['nivel_const']==1 || $acceso)
			$estado_vac=2;
		//else
		//	$estado_vac=1;
		
		$stmt2 = $mbd->prepare("EXEC SW_GRABAR_PROG_VACACIONES ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
	    $stmt2->bindParam(1, $cedula, PDO::PARAM_INT,10);
	    $stmt2->bindParam(2, $ciclolaboral, PDO::PARAM_STR,8);
	    $stmt2->bindParam(3, $fini1, PDO::PARAM_STR,10);
	    $stmt2->bindParam(4, $ffin1, PDO::PARAM_STR,10);
	    $stmt2->bindParam(5, $fpag1, PDO::PARAM_STR,10);
	    $stmt2->bindParam(6, $sDias, PDO::PARAM_INT,10);
	    $stmt2->bindParam(7, $CENTRO_COSTO, PDO::PARAM_STR,6);
	    $stmt2->bindParam(8, $TipoVac, PDO::PARAM_STR,10);
	    $stmt2->bindParam(9, $estado_vac, PDO::PARAM_INT);    
	    $stmt2->bindParam(10, $_SESSION['user_session_const'], PDO::PARAM_STR,6);    
		$stmt2->execute();

		/*echo "EXEC SW_GRABAR_PROG_VACACIONES ".$cedula.", ".$ciclolaboral.", ".$fini1.", ".$ffin1.", ".$fpag1.", ".$sDias.", ".$CENTRO_COSTO.", ".$TipoVac.", ".$estado_vac.", ".$_SESSION['user_session_const'];*/
		
		
	    //if ($acceso){
			$stmt3 = $mbd->prepare("EXEC SW_Autorizar_Vacaciones ?, ?, ?");    
		    $stmt3->bindParam(1, $ciclolaboral, PDO::PARAM_STR,8);
		    $stmt3->bindParam(2, $cedula, PDO::PARAM_INT,10);
		    $stmt3->bindParam(3, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);    
			$stmt3->execute();
		//}
	}
	else{
		echo "No tiene privilegio para esta operacion"; 
	}	
}	
else
	echo "Debe Iniciar Sesion"; 
?>
