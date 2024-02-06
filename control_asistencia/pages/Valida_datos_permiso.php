<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	include("funciones_var.php");

	$cedula= isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'NULL';
	$fecinper= isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$fecfinper= isset($_POST['txtffin'])?$_POST['txtffin']:'NULL';
	$turno= isset($_POST['hddturno'])?$_POST['hddturno']:'NULL';
	$sh= isset($_POST['hddsisthor'])?$_POST['hddsisthor']:'NULL';
	$npermiso= isset($_POST['hddnpermiso'])?$_POST['hddnpermiso']:'0';
	$cod_adam= isset($_POST['cbocodigo'])?$_POST['cbocodigo']:'NULL';
	$cod_ubicacion= isset($_POST['hddubicacion'])?$_POST['hddubicacion']:'NULL';
	$clase_nomina= isset($_POST['hddclasenomina'])?$_POST['hddclasenomina']:'NULL';
	$relacion_laboral= isset($_POST['hddrelacion_laboral'])?$_POST['hddrelacion_laboral']:'NULL';
	$firma=$cedula;
	$h1= isset($_POST['txthoraini'])?$_POST['txthoraini']:'00:00';
	$h2= isset($_POST['txthorafin'])?$_POST['txthorafin']:'00:00';
	$PeriodoPago= isset($_POST['hddidcalendario'])?$_POST['hddidcalendario']:'NULL';
	$autorizado= isset($_POST['hddcedtrabajadorsup'])?$_POST['hddcedtrabajadorsup']:'NULL';
	$Obs= isset($_POST['txtobservacion'])?$_POST['txtobservacion']:'NULL';
	$DocEntregado= isset($_POST['chkDocEntregado'])?$_POST['chkDocEntregado']:'N';
	$tperm= isset($_POST['optionsRadiosInline'])?$_POST['optionsRadiosInline']:'NULL';

	if ($_SESSION['cedula_session_const']!=$autorizado)
	{	
		$autorizado=$_SESSION['cedula_session_const'];
		$aut='N';
	}else
	{
		$aut='S';
		
	}
	/*
	$ccosto= isset($_POST['hddccosto'])?$_POST['hddccosto']:'NULL';
	$puesto= isset($_POST['hddpuesto'])?$_POST['hddpuesto']:'NULL';
	$ccosto= isset($_POST['txtccosto'])?$_POST['txtccosto']:'NULL';
	$puesto= isset($_POST['txtpuesto'])?$_POST['txtpuesto']:'NULL';
	$trabajadorsup= isset($_POST['txttrabajadorsup'])?$_POST['txttrabajadorsup']:'NULL';
	$disrem= isset($_POST['txtdisrem'])?$_POST['txtdisrem']:'NULL';
	$disnorem= isset($_POST['txtdisnorem'])?$_POST['txtdisnorem']:'NULL';
	$ubicacion= isset($_POST['txtubicacion'])?$_POST['txtubicacion']:'NULL';
	$condicion= isset($_POST['txtcondicion'])?$_POST['txtcondicion']:'NULL';
	$docrequerido= isset($_POST['txtdocrequerido'])?$_POST['txtdocrequerido']:'NULL';
	*/

	$mbd=Conectarse_sitt();	

	$finix = date_create($fecinper);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($fecfinper);
	$ffin2 = date_format($ffinx, 'Y-m-d');
	
	if ($h1=='') 
		$h1='00:00';
	if ($h2=='') 
		$h2='00:00';
	
	$h1 = strtotime($h1);
	$h1 = date("H:i", $h1);

	$h2 = strtotime($h2);
	$h2 = date("H:i", $h2);

	/*
	echo $finix;
	echo $ffin1;
	echo $h1;
	echo $h2;
	*/
    
	$stmt = $mbd->prepare("EXEC dbo.SW_Valida_datos_permiso ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?");
    $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
	$stmt->bindParam(2, $fini1, PDO::PARAM_STR,10);
	$stmt->bindParam(3, $ffin2, PDO::PARAM_STR,10);
	$stmt->bindParam(4, $turno, PDO::PARAM_INT,10);
	$stmt->bindParam(5, $sh, PDO::PARAM_INT,10);
	$stmt->bindParam(6, $npermiso, PDO::PARAM_INT,10);
	$stmt->bindParam(7, $cod_adam , PDO::PARAM_INT,10);
	$stmt->bindParam(8, $cod_ubicacion, PDO::PARAM_STR,4);
	$stmt->bindParam(9, $clase_nomina, PDO::PARAM_STR,2);
	$stmt->bindParam(10, $relacion_laboral , PDO::PARAM_STR,1);
	$stmt->bindParam(11, $firma, PDO::PARAM_STR,12);
	$stmt->bindParam(12, $h1, PDO::PARAM_STR,5);
	$stmt->bindParam(13, $h2, PDO::PARAM_STR,5);
	$stmt->bindParam(14, $PeriodoPago, PDO::PARAM_INT,10);
	$stmt->bindParam(15, $autorizado, PDO::PARAM_INT,10);
	$stmt->bindParam(16, $aut, PDO::PARAM_STR,1);
	$stmt->bindParam(17, $Obs, PDO::PARAM_STR,255);	

	/*
	echo  '1)'.$cedula.'<br>';
	echo  '2)'.$fini1.'<br>';
	echo  '3)'.$ffin2.'<br>';
	echo  '4)'.$turno.'<br>';
	echo  '5)'.$sh.'<br>';
	echo  '6)'.$npermiso.'<br>';
	echo  '7)'.$cod_adam.'<br>';
	echo  '8)'.$cod_ubicacion.'<br>';
	echo  '9)'.$clase_nomina.'<br>';
	echo  '10)'.$relacion_laboral .'<br>';
	echo  '11)'.$firma.'<br>';
	echo  '12)'.$h1.'<br>';
	echo  '13)'.$h2.'<br>';
	echo  '14)'.$PeriodoPago.'<br>';
	echo  '15)'.$autorizado.'<br>';
	echo  '16)'.$aut.'<br>';
	echo  '17)'.$Obs.'<br>';
	*/

	$stmt->execute();

	  while ($fila = $stmt->fetch()) {
	    if ($fila['Estatus']==false) 
   		 	echo 'Falla en la Carga: '.$fila['MError'];
   		else{
   		 		$stat=$fila['Estatus'];
   		 		$npermiso=$fila['Nroper'];
   		 		echo 'Permiso <b>Nro. '.$fila['Nroper'].'</b> Cargado Correctamente: '.$fila['MError'].'<br>';
   		 		if ($stat==-1 && $DocEntregado=='S' && $npermiso<>0)
   		 		{
   		 			$stmt1 = $conn->prepare("EXEC dbo.SW_Autorizar_PERMISO ?, ?");
					$stmt1->bindParam(1, $npermiso, PDO::PARAM_INT,10);	
					$stmt1->bindParam(2, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
					$stmt1->execute();
					$fila1 = $stmt1->fetch();
					echo $fila1['Merror'];
   		 		}
   		 			
   		 	}   		   		
	 } 
	   
}	
else
	echo "Debe Iniciar Sesion"; 
?>
