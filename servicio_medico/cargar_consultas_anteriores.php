<?PHP

require("include_conex.php");

$cedula= isset($_GET["cedula"])?$_GET["cedula"]:"";            //Condicion 
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//$query1 = "select ci from v_historias where ci='" . $cedula . "'";

//$resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query1);
//if (pg_num_rows($resultado1)==0) {

	$query = "select uid,fecha,idmotivo, motivo, uid_paciente,ci from v_consulta where to_char(CURRENT_DATE,'YYYY/MM/DD')=to_char(fecha,'YYYY/MM/DD') and ci='" . $cedula . "'";	

	$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

	$numReg = pg_num_rows($resultado);
	$motivos='';
	if($numReg>0){		
		while ($fila=pg_fetch_array($resultado)) {
			$motivos.=$fila["idmotivo"].',';	
		}
		$motivos = substr($motivos, 0, -1);		
	}
	echo $motivos;
pg_free_result($resultado);
pg_close($conexion);
?>
