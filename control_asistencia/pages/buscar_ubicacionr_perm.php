<?php 

$codigo = isset($_POST["cbocodigo"])?$_POST["cbocodigo"]:"NULL";
$relab=isset($_POST["hddrelacion_laboral"])?$_POST["hddrelacion_laboral"]:"NULL";
$clasenom=isset($_POST["hddclasenomina"])?$_POST["hddclasenomina"]:"NULL";
$turno=isset($_POST["hddturno"])?$_POST["hddturno"]:"NULL";

buscar($codigo, $relab, $clasenom, $turno);
 
function buscar ($codigo, $relab, $clasenom, $turno){
	include("../BD/conexion.php");
	include("funciones_var.php");

	//echo $codigo.'<br>'. $relab.'<br>'. $clasenom.'<br>'. $turno;
	
	$mbd=Conectarse_sitt();	        
    
	$stmt = $mbd->prepare("EXEC dbo.SW_con_tiempo_ubicacion ?, ?, ?, ?");
    $stmt->bindParam(1, $codigo, PDO::PARAM_INT,10);
    $stmt->bindParam(2, $relab, PDO::PARAM_STR,1);
    $stmt->bindParam(3, $clasenom, PDO::PARAM_STR,2);
    $stmt->bindParam(4, $turno, PDO::PARAM_INT,10);
	$stmt->execute();

	  while ($fila = $stmt->fetch()) {
	    echo "$(\"#hddubicacion\").val(\"" . $fila['Cod_ubicacion'] . "\");\n" ;
   		echo "$(\"#txtubicacion\").val(\"" . $fila['Desc_ubicacion'] . "\");\n" ;
   		   		
	 }	  
} 
?>
