<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");	
	require_once('funciones_var.php');
	$cedula= isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'NULL';	
	$observacion= isset($_POST['txtobservacion'])?$_POST['txtobservacion']:'NULL';
	$TipoCom= isset($_POST['cboTipoCom'])?$_POST['cboTipoCom']:'NULL';
	$descTipoCom= isset($_POST['hddDescTipoCom'])?$_POST['hddDescTipoCom']:'NULL';
	$finicio= isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$ffin= isset($_POST['txtffin'])?$_POST['txtffin']:'NULL';
	$nro= isset($_POST['hddnro'])?$_POST['hddnro']:'-1';
	
	$mbd=Conectarse_sitt();

	$qry="select CENTRO_COSTO, PUESTO, DESC_PUESTO, RELACION_LABORAL,
SISTEMA_HORARIO, CLASE_NOMINA, TURNO, GETDATE() as proceso
   from ADAM_DATOS_PERSONALES where TRABAJADOR = ?";

	$stmt1 = $mbd->prepare($qry);
    $stmt1->bindParam(1, $cedula, PDO::PARAM_INT,10);
	$stmt1->execute();

	while ($fila1 = $stmt1->fetch())
	{			
		$CENTRO_COSTO=trim($fila1['CENTRO_COSTO']);		
		$SISTEMA_HORARIO=$fila1['SISTEMA_HORARIO'];		
		$PUESTO=$fila1['PUESTO'];		
		$DESC_PUESTO=trim($fila1['DESC_PUESTO']);			
		$RL=$fila1['RELACION_LABORAL'];		
		$CLASE_NOMINA=$fila1['CLASE_NOMINA'];		
		$TURNO=$fila1['TURNO'];
		$fproceso=$fila1['proceso'];	
	}		

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');	

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'CARGAR', 'insertcomision.php', $_SESSION['user_session_const']);
	pg_close($link);

	if ($_SESSION['nivel_const']==1 || $acceso){
		$stat="";		
		$Merror="";
		if ($observacion=="NULL" || $observacion=="")
			$observacion="Causal ".$descTipoCom;

		$qry="exec SW_grabar_comisiones_servicio ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?, ?,  ?;";
		$stmt2 = $mbd->prepare($qry);
		$stmt2->bindParam(1, $nro, PDO::PARAM_INT,10);
		$stmt2->bindParam(2, $cedula, PDO::PARAM_INT,10);   
	    $stmt2->bindParam(3, $fini1, PDO::PARAM_STR,10);    
	    $stmt2->bindParam(4, $ffin1, PDO::PARAM_STR,10);
	    $stmt2->bindParam(5, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
	    $stmt2->bindParam(6, $CENTRO_COSTO, PDO::PARAM_STR,10);
	    $stmt2->bindParam(7, $PUESTO, PDO::PARAM_STR,4);
	    $stmt2->bindParam(8, $DESC_PUESTO, PDO::PARAM_STR,50);
	    $stmt2->bindParam(9, $RL, PDO::PARAM_STR,1);
	    $stmt2->bindParam(10, $SISTEMA_HORARIO, PDO::PARAM_INT,10);
	    $stmt2->bindParam(11, $CLASE_NOMINA, PDO::PARAM_STR,2);
	    $stmt2->bindParam(12, $TURNO, PDO::PARAM_INT,1);
	    $stmt2->bindParam(13, $TipoCom, PDO::PARAM_INT,2);
	    $stmt2->bindParam(14, $observacion, PDO::PARAM_STR,255);    
	    $stmt2->bindParam(15,$fproceso, PDO::PARAM_STR,16);    
		$stmt2->execute();

		while ($fila2 = $stmt2->fetch())
		{			
			$stat=trim($fila2['stat']);		
			$Merror=$fila2['Merror'];			
		}
		$stmt1=null;
		$stmt2=null;
		$fila1=null;
		$fila2=null;
		echo $Merror;
	}else{
		$Merror="No tiene suficientes privilegios para esta operacion";
		echo $Merror;
	}    
}	
else
	echo "Debe Iniciar Sesion"; 
?>