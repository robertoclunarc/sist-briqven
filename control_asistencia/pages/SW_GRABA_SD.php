<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");	
	require_once('funciones_var.php');

	//print_r($_POST);
	$cedula      = isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'NULL';	
	$observacion = isset($_POST['txtobservacion'])?$_POST['txtobservacion']:'NULL';
	$TipoCausal  = isset($_POST['cboTipoCausal'])?$_POST['cboTipoCausal']:'NULL';
	$TipoMed     = isset($_POST['cboTipoMed'])?$_POST['cboTipoMed']:'NULL';
	//$descTipoCom = isset($_POST['hddDescTipoCom'])?$_POST['hddDescTipoCom']:'NULL';
	$fmedida     = isset($_POST['txtfmedida'])?$_POST['txtfmedida']:'NULL';
	$flaborales  = isset($_POST['txtflaborales'])?$_POST['txtflaborales']:'NULL';
	$finicio     = isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$ffin        = isset($_POST['txtffin'])?$_POST['txtffin']:'NULL';
	$nro         = isset($_POST['hddnro'])?$_POST['hddnro']:'-1';
	$firmo       = isset($_POST['firmo'])?$_POST['firmo']:'N';
	if ($firmo=='on') $firmo='S';
	
	$mbd=Conectarse_sitt();

	$qry="select CENTRO_COSTO, PUESTO, DESC_PUESTO, RELACION_LABORAL, SISTEMA_HORARIO, CLASE_NOMINA, TURNO, GERENCIA, GETDATE() as proceso from ADAM_DATOS_PERSONALES where TRABAJADOR = ?";
	/*@Numero_suspension, 
	@cedula bigint , 
	@tipo int, //TIPO MEDIDA
	@inicio_suspension datetime , 
	@fin_suspension datetime,
	@autorizante bigint, 
	@CentroCosto varchar(10), 
	@Cod_cargo varchar(4),
	@Desc_cargo varchar(50),
	@Relacion_Laboral varchar(1), 
	@cuad varchar(20), //NO SE QUE ESTO Y POR ENDE NO SE DE DONDE SACO ESTE CAMPO
	@Clasenom varchar(2), 
	@Turno int ,
	@cod_suspension int ,
	@Observaciones varchar(255), 
	@f_proceso datetime,
	@Cuadrilla int ,  //DE DONDE SACO ESTE CAMPO
	@ger varchar(20), 
	@Fecha_aplicacion datetime, 
	@firmo_medida varchar(1) ,
	@fecha_laborales datetime*/
	$stmt1 = $mbd->prepare($qry);
    $stmt1->bindParam(1, $cedula, PDO::PARAM_INT,10);
	$stmt1->execute();

	while ($fila1 = $stmt1->fetch())
	{			
		$CENTRO_COSTO    = trim($fila1['CENTRO_COSTO']);		
		$SISTEMA_HORARIO = $fila1['SISTEMA_HORARIO'];		
		$COD_CARGO       = $fila1['PUESTO'];		
		$DESC_PUESTO     = trim($fila1['DESC_PUESTO']);			
		$RELACION_LABORAL= $fila1['RELACION_LABORAL'];		
		$CLASE_NOMINA    = $fila1['CLASE_NOMINA'];		
		$TURNO           = $fila1['TURNO'];
		$fproceso        = $fila1['proceso'];
		$ger             = $fila1['GERENCIA'];	
	}		

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');	

	$faplicacion = date_create($fmedida);
	$faplicacion1 = date_format($faplicacion, 'Y-m-d');	

	$flaboralesx = date_create($flaborales);
	$flaborales1 = date_format($flaboralesx, 'Y-m-d');	

    $hoy = date('Y-m-d');
	$cuad='';

/*print ("Numero_suspension: ".$nro.", cedula: ".$cedula.", tipo: ".$TipoMed.", inicio_suspension: ". $fini1.", fin_suspension: ".$ffin1.", autorizante: ". $_SESSION['cedula_session_const'].", 
CentroCosto: ".$CENTRO_COSTO. ", Cod_cargo: ". $COD_CARGO.", Desc_cargo: ". $DESC_PUESTO.", Relacion_Laboral: ".$RELACION_LABORAL.", cuad: XXXXXXXXXXXXXXXXXX, Clasenom: ".$CLASE_NOMINA.", Turno: ".$TURNO.", cod_suspension: ". $TipoCausal .", Observaciones: ".$observacion.", f_proceso:".$faplicacion1.",
Cuadrilla:  ".$SISTEMA_HORARIO." ,ger: ". $ger.", Fecha_aplicacion: ".$hoy.", firmo_medida: ". $firmo.", fecha_laborales:".$flaborales1);
*/
	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'TODO', 'suspenciones', $_SESSION['user_session_const']);
	pg_close($link);

	if ($_SESSION['nivel_const']==1 || $acceso){
		$stat="";		
		$Merror="";
		//alert('PASO');

//		$qry="exec SW_grabar_comisiones_servicio ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?, ?,  ?;"; 
		/*$qry="exec SW_grabar_suspensiones (@Numero_suspension bigint, @cedula bigint , @tipo int,@inicio_suspension datetime , 
		@fin_suspension datetime, @autorizante bigint, @CentroCosto varchar(10), @Cod_cargo varchar(4),@Desc_cargo varchar(50), 
		@Relacion_Laboral varchar(1), @cuad varchar(20), @Clasenom varchar(2), @Turno int ,@cod_suspension int , @Observaciones varchar(255), @f_proceso datetime,
		@Cuadrilla int ,
		@ger varchar(20), @Fecha_aplicacion datetime, @firmo_medida varchar(1) ,@fecha_laborales datetime)");
		*/		
		/*$qry="exec SW_grabar_suspensiones ".$nro.", ".$cedula.", ".$TipoMed.", '".$fini1."', '".$ffin1."', ".$_SESSION['cedula_session_const'].", '".$CENTRO_COSTO."', '".$COD_CARGO."', '".$DESC_PUESTO."', '".$RELACION_LABORAL."', NULL, '".$CLASE_NOMINA."', '".$TURNO."', ".$TipoCausal.", ".$observacion.", '".$fproceso."', ".$SISTEMA_HORARIO.", '".$ger."', '".$faplicacion1."', '".$firmo."', '".$flaborales1."'";
		$stmt = $mbd->prepare($qry);
		$stmt->execute();*/
		$qry="exec SW_grabar_suspensiones ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";		
		$stmt2 = $mbd->prepare($qry);
		$stmt2->bindParam(1, $nro, PDO::PARAM_INT,10);
		$stmt2->bindParam(2, $cedula, PDO::PARAM_INT,10);   
		$stmt2->bindParam(3, $TipoMed, PDO::PARAM_STR,10);
	    $stmt2->bindParam(4, $fini1, PDO::PARAM_STR,10);    
	    $stmt2->bindParam(5, $ffin1, PDO::PARAM_STR,10);
	    $stmt2->bindParam(6, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
	    $stmt2->bindParam(7, $CENTRO_COSTO, PDO::PARAM_STR,10);
	    $stmt2->bindParam(8, $COD_CARGO, PDO::PARAM_STR,4);
	    $stmt2->bindParam(9, $DESC_PUESTO, PDO::PARAM_STR,50);
	    $stmt2->bindParam(10, $RELACION_LABORAL, PDO::PARAM_STR,1);
		$stmt2->bindParam(11, $cuad, PDO::PARAM_STR,20);              
	    $stmt2->bindParam(12, $CLASE_NOMINA, PDO::PARAM_STR,2);
	    $stmt2->bindParam(13, $TURNO, PDO::PARAM_INT,1);
	    $stmt2->bindParam(14, $TipoCausal, PDO::PARAM_INT,2);
	    $stmt2->bindParam(15, $observacion, PDO::PARAM_STR,255);    
	    $stmt2->bindParam(16, $fproceso, PDO::PARAM_STR,16);    
		$stmt2->bindParam(17, $SISTEMA_HORARIO, PDO::PARAM_INT,2);  
		$stmt2->bindParam(18, $ger, PDO::PARAM_INT,20);        
		$stmt2->bindParam(19, $faplicacion1, PDO::PARAM_STR,10);
		$stmt2->bindParam(20, $firmo, PDO::PARAM_STR,1); 
		$stmt2->bindParam(21, $flaborales1, PDO::PARAM_STR,10);
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