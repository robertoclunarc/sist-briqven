<?PHP
require("libs/conexion.php");
$cedula = isset($_GET["cedula"])?$_GET["cedula"]:"-1";
$conexion = Conectarse_rrhh();
$conex_ctl_plt = Conectarse();

$trabajador="";
$cargo="";
$descripcion_gerencia="";
$nombres_jefe="";
$nombres="";
$clase_nomina="";
$fkmotivo="";

$query2 = "select a.* from acceso_personal_propio a where a.cedula='".$cedula."' and to_char(fecha_acceso,'YYYY/MM/DD') = to_char(CURRENT_DATE,'YYYY/MM/DD') and fecha_acceso = (select max(fecha_acceso) from acceso_personal_propio p where a.cedula=p.cedula)";
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
	$direccion2='';
	$resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query);
	$numReg1 = pg_num_rows($resultado1);
	$fila1=pg_fetch_array($resultado1);
	$trabajador=$fila1["trabajador"];
	$cargo=$fila1["cargo"];
	$descripcion_gerencia=$fila1["descripcion_gerencia"];
	$nombres_jefe=$fila1["nombres_jefe"];
	$nombres=$fila1["nombres"]/*.' '.$fila1["apellidos"]*/;
	$clase_nomina=$fila1["clase_nomina"];
	pg_free_result($resultado1);
	$cont=$numReg1;
}

$hora = localtime(time(),true);

$conn = Conectarse_sitt();
$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, turno, entrada_esperada1, salida_esperada1, CONVERT(VARCHAR(10), GETDATE(), 120) hoy from sw_hoja_de_tiempo_real where cedula=".$cedula." and CONVERT(VARCHAR(10), fecha, 120)=CONVERT(VARCHAR(10), GETDATE(), 120)";

$stmt              = $conn->query($sql);
$col               = $stmt->columnCount();
$ced               = '';
$fecha_asist       = '';
$turno             = '';	
$entrada_esperada1 = '';
$salida_esperada1  = '';
while ($row = $stmt->fetch()) {		
	 $ced=$row[0];
	 $fecha_asist=$row[1];
	 $turno=$row[2];
	 $entrada_esperada1=$row[3];
	 $salida_esperada1=$row[4];
	 $hoy=$row[5];		
}
if ($entrada_esperada1=='23:00' && $salida_esperada1=='07:00')
   $turno=1;
elseif ($entrada_esperada1=='07:00' && ($salida_esperada1=='12:00' || $salida_esperada1=='15:00'))
   $turno=2;
elseif ($entrada_esperada1=='15:00' && $salida_esperada1=='23:00')
   $turno=3;
else
	$turno=$turno;

if ($numReg2==0)
	$direccion='ENTRADA';
elseif ($numReg2>0 && $direccion2=='ENTRADA')
	$direccion='SALIDA';
else
	$direccion='ENTRADA';

if ($turno==1 && $hora["tm_hour"]>=22 && $direccion2=='')
   $direccion='ENTRADA';
elseif ($turno==1 && $direccion2=='SALIDA')
	$direccion='ENTRADA';
elseif ($turno==1  && $direccion2=='' && $direccion!='ENTRADA')
	$direccion='ENTRADA';
elseif ($turno==1  && $direccion2=='' && $hora["tm_hour"]>=4 && $hora["tm_hour"]<=8)
	$direccion='SALIDA';
elseif ($turno==1)
	$direccion='SALIDA';

$no_entra='P';
	if($cont>0){
		//$suspendidos = array("11172998", "16844828", "25324604","11168531","27874493");//<---aqui se colacan la cedula de los suspendidos
		$suspendidos = array("16844828", "27874493","14063657", "13782025", "17337234","17839510");//<---aqui se colacan la cedula de los suspendidos
		$no_requeridos = array("10386016", "10391948","11516284","12126806", "12653437", "13087633", "13336163", "13348340", "13646701", "14088508", "14223728", "14726427", "15679903", "16617136", "17110005", "17210088", "17218799", "17337234", "17430725", "17464178", "18248750", "19622104", "24559816", "5554777", "8372754", "8524918", "15971666", "25324604", "16844828", "25324604", "27874493", "8305641", "12004682", "13016226", "15542109", "18665365");
		if ($entrada_esperada1=='VV:VV')
			$no_entra='V';
		elseif	(($entrada_esperada1=='SD:SD') || (in_array($trabajador, $suspendidos)))
			$no_entra='S';		
		elseif	(in_array($trabajador, $no_requeridos))
			$no_entra='NR';		
			
				
		echo "$(\"#hddcedula\").val(\"" . $trabajador . "\");\n" ;
		echo "$(\"#cbodireccion\").val(\"" . $direccion . "\");\n" ;
		echo "$(\"#hdddireccion\").val(\"" . $direccion . "\");\n" ;
		echo "$(\"#hddturno\").val(\"" . $turno . "\");\n" ;
		echo "$(\"#hddpasa\").val(\"" . $no_entra . "\");\n" ;
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
