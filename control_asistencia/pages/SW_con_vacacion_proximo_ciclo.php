<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");	

	$cedula= isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'0';
	$mbd=Conectarse_sitt();	

	$stmt2 = $mbd->prepare("EXEC SW_con_vacacion_proximo_ciclo ?");
    $stmt2->bindParam(1, $cedula, PDO::PARAM_INT,10);
	$stmt2->execute();

	while ($fila2 = $stmt2->fetch()) {
	  	$sCiclo=$fila2['NextCiclo'];
		$sFmin=$fila2['fmin'];
		$sFmax=$fila2['fmax'];		
		$sDias=30;
		$ar_fin=explode("/", $sFmin);
		$sFmin = date_create($ar_fin[2].'-'.$ar_fin[1].'-'.$ar_fin[0]);
		$sFmin = date_format($sFmin, 'Y-m-d');
	}	

	$fin = date("Y-m-d",strtotime($sFmin." 29 days"));

	echo "$(\"#txtfinicio\").val(\"" . $sFmin . "\");\n" ;
	echo "$(\"#txtffin\").val(\"" . $fin . "\");\n" ;
	echo "$(\"#hddsFmin\").val(\"" . $sFmin . "\");\n" ;
	echo "$(\"#hddsFmax\").val(\"" . $sFmax . "\");\n" ;
	echo "$(\"#txtciclolaboral\").val(\"" . $sCiclo . "\");\n" ;
	echo "$(\"#txtsDias\").val(\"" . $sDias . "\");\n" ;
	echo "$(\"#hddTipoVac\").val(\"" . "CONTRA" . "\");\n" ;
		
}	
else
	echo "Debe Iniciar Sesion"; 
?>
