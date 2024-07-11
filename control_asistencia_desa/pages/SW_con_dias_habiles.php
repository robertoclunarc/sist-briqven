<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");	

	$sFmin= isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$sDias= isset($_POST['txtsDias'])?$_POST['txtsDias']:'NULL';
	$TipoVac= isset($_POST['cboestatus'])?$_POST['cboestatus']:'NULL';
	$mbd=Conectarse_sitt();

	$sFmin = date_create($sFmin);
	$sFmin = date_format($sFmin, 'Y-m-d');
	
	if ($TipoVac!='CONTRA' && $sFmin!='NULL' && $TipoVac!='NULL')
	{	
		$stmt2 = $mbd->prepare("EXEC SW_con_dias_habiles ?, ?");
	    $stmt2->bindParam(1, $sFmin, PDO::PARAM_STR,10);	
	    $stmt2->bindParam(2, $sDias, PDO::PARAM_INT,10);
		$stmt2->execute();		
		while ($fila = $stmt2->fetch()) {
		  	$fin=$fila['ffin'];	  	
			$sDias=$fila['dias'];
			$ar_fin=explode("/", $fin);			
			$fin = date_create($ar_fin[2].'-'.$ar_fin[1].'-'.$ar_fin[0]);
			$fin = date_format($fin, 'Y-m-d');   		 	 		   		
		}		
	}
	else{
		$sDias=30;
		$fin = date("Y-m-d",strtotime($sFmin." 29 days"));
	}
	
	echo "$(\"#txtffin\").val(\"" . $fin . "\");\n" ;	
	echo "$(\"#txtsDias\").val(\"" . $sDias . "\");\n" ;	
}	
else
	echo "Debe Iniciar Sesion"; 
?>
