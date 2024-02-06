<?PHP
require("libs/conexion.php");
$idunidad = isset($_GET["idunidad"])?$_GET["idunidad"]:"null";
$conex_ctl_plt = Conectarse();

$query2 = "SELECT nombres || ' ' || apellidos as jefe FROM v_departamentos_jefes where idunidad=".$idunidad;

$resultado2 = pg_query($conex_ctl_plt, $query2) or die("Error en la Consulta SQL:".$query2);
$numReg1 = pg_num_rows($resultado2);
if ($numReg1>0){
	$fila2=pg_fetch_array($resultado2);
	
	$responsable=$fila2["jefe"];			
	echo "$(\"#txtResponsable\").val(\"" . $responsable . "\");\n" ;
			
}else{
	echo "0";
}

pg_close($conex_ctl_plt);
?>