<?PHP
require("libs/conexion.php");
$cedula = isset($_GET["cedula"])?$_GET["cedula"]:"-1";
$conex_ctl_plt = Conectarse();

$departamento="";
$nombres="";
$tipo_personal="";
$responsable="";
$fkmotivo="";
$direccion="";
$fecha_ult="";
$descripcion_motivo="";
$hoy="";
$fk_unidad="";

$query2 = "select distinct a.cedula, a.nombres, fecha_acceso, to_char(CURRENT_DATE,'YYYY/MM/DD') as hoy, to_char(fecha_acceso,'YYYY/MM/DD') as fecha_ult, a.fkmotivo, a.direccion, tipo_personal, descripcion_motivo, departamento, responsable, a.fk_unidad from acceso_personal_foraneo a, motivos m where a.fkmotivo=idmotivo and a.cedula='".$cedula."' and fecha_acceso=(select max(fecha_acceso) from acceso_personal_foraneo a where a.cedula='".$cedula."')";

$resultado2 = pg_query($conex_ctl_plt, $query2) or die("Error en la Consulta SQL:".$query2);
$numReg1 = pg_num_rows($resultado2);
if ($numReg1>0){
	$fila2=pg_fetch_array($resultado2);
	$direccion=$fila2["direccion"];
	$cedula=$fila2["cedula"];
	$departamento=$fila2["departamento"];
	$tipo_personal=$fila2["tipo_personal"];
	$responsable=$fila2["responsable"];
	$nombres=$fila2["nombres"];
	$fkmotivo=$fila2["fkmotivo"];
	$fk_unidad=$fila2["fk_unidad"];
	if ($fecha_ult==$hoy && $direccion=='SALIDA')
		$direccion='ENTRADA';
	elseif ($fecha_ult==$hoy && $direccion=='ENTRADA') 
		$direccion='SALIDA';
	else 
		$direccion='ENTRADA';

	echo "$(\"#hddcedula\").val(\"" . $cedula . "\");\n" ;
	echo "$(\"#cbodireccion\").val(\"" . $direccion . "\");\n" ;
	echo "$(\"#hdddireccion\").val(\"" . $direccion . "\");\n" ;		
	echo "$(\"#txtResponsable\").val(\"" . $responsable . "\");\n" ;
	echo "$(\"#nombre\").val(\"" . $nombres . "\");\n" ;
	if ($tipo_personal!="")		
		echo "$(\"#cbotipo_personal\").val(\"" . $tipo_personal . "\");\n" ;
	if ($fkmotivo!="")
		echo "$(\"#cbomotivo\").val(\"" . $fkmotivo . "\");\n" ;
	if ($fk_unidad!="")
		echo "$(\"#cboDepartamento\").val(\"" . $fkmotivo . "\");\n" ;			
}else{
	echo "0";
}
				


pg_close($conex_ctl_plt);
?>