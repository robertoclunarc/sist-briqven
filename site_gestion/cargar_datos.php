<?PHP
require("libs/conexion.php");
$fecha = isset($_GET["fecha"])?$_GET["fecha"]:"-1";
$conexion = Conectarse();
$conex_sio = Conectars_sio();

$trabajador="";
$cargo="";
$descripcion_gerencia="";
$nombres_jefe="";
$nombres="";
$clase_nomina="";
$fkmotivo="";

$query2 = "select a.* from prd_real_plan a where fecha_produccion = '".$fecha."'";
$resultado2 = pg_query($conex_ctl_plt, $query2) or die("Error en la Consulta SQL:".$query);
$numReg2 = pg_num_rows($resultado2);
if ($numReg2>0){
	$fila2=pg_fetch_array($resultado2);
	$direccion2=$fila2["direccion"];
	$trabajador=$fila2["cedula"];
	$cargo=$fila2["cargo"];
	$descripcion_gerencia=$fila2["departamento"];
	$nombres_jefe=$fila2["jefe_inmediato"];
	$nombres=$fila2["nombres"];
	$clase_nomina=$fila2["tipo_personal"];
	$fkmotivo=$fila2["fkmotivo"];
	pg_free_result($resultado2);
	$cont=$numReg2;
}else{
	$query1 = "select t.* from trabajadores_activos_con_jefes t where t.trabajador='" . $cedula . "'";
	$direccion2='ENTRADA';
	$resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query);
	$numReg1 = pg_num_rows($resultado1);
	$fila1=pg_fetch_array($resultado1);
	$trabajador=$fila1["trabajador"];
	$cargo=$fila1["cargo"];
	$descripcion_gerencia=$fila1["descripcion_gerencia"];
	$nombres_jefe=$fila1["nombres_jefe"];
	$nombres=$fila1["nombres"].' '.$fila1["apellidos"];
	$clase_nomina=$fila1["clase_nomina"];
	pg_free_result($resultado1);
	$cont=$numReg1;
}

$hora = localtime(time(),true);

$conn = Conectarse_sitt();
$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, turno from sw_hoja_de_tiempo_real where cedula=".$cedula." and CONVERT(VARCHAR(10), fecha, 120)=CONVERT(VARCHAR(10), GETDATE(), 120)";

$stmt = $conn->query($sql);
$col = $stmt->columnCount();
$ced='';
$fecha_asist='';
$turno='';	
while ($row = $stmt->fetch()) {		
	 $ced=$row[0];
	 $fecha_asist=$row[1];
	 $turno=$row[2];		
}

if ($numReg2==0)
	$direccion='ENTRADA';

if ($numReg2>0 && $direccion2=='ENTRADA')
	$direccion='SALIDA';
else
	$direccion='ENTRADA';

if ($turno==1 && $hora["tm_hour"]>=22)
   $direccion='ENTRADA';
elseif ($turno==1 && $direccion2=='SALIDA')
	$direccion='ENTRADA';
elseif ($turno==1)
	$direccion='SALIDA';

	if($cont>0){
				
		echo "$(\"#hddcedula\").val(\"" . $trabajador . "\");\n" ;
		echo "$(\"#cbodireccion\").val(\"" . $direccion . "\");\n" ;
		echo "$(\"#hdddireccion\").val(\"" . $direccion . "\");\n" ;
		echo "$(\"#hddturno\").val(\"" . $turno . "\");\n" ;
		echo "$(\"#txtcargo\").val(\"" . $cargo . "\");\n" ;
		echo "$(\"#txtDepartamento\").val(\"" . $descripcion_gerencia . "\");\n" ;
		echo "$(\"#txtjefeinmediato\").val(\"" . $nombres_jefe . "\");\n" ;
		echo "$(\"#nombre\").val(\"" . $nombres . "\");\n" ;
		$clnom='PROPIO';
		if ($clase_nomina=='PA')
			$clnom='PASANTE';
		echo "$(\"#cbotipo_personal\").val(\"" . $clnom . "\");\n" ;
		if ( $fkmotivo!='')
			echo "$(\"#cbomotivo\").val(\"" . $fkmotivo . "\");\n" ;
	}
 	else
		echo "0";

pg_close($conexion);
pg_close($conex_ctl_plt);
?>
