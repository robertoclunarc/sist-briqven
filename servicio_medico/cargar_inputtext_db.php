<?PHP
require("include_conex.php");

$tabla = isset($_GET["tabla"])?$_GET["tabla"]:"";
$campo1 = isset($_GET["campo1"])?$_GET["campo1"]:"";        //Campo de la tabla que contiene el códigoo valor del elemento

$orderby= isset($_GET["orderby"])?$_GET["orderby"]:"";      //Campo(s) de ordenamiento, separados por ","
$where= isset($_GET["where"])?$_GET["where"]:"";            //Condicion 

$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

		$query = "select ". $campo1 . " from " . $tabla;

		if ($where!=""){
			$query=$query . " where " . $where;  
		}

		if ($orderby!=""){
			$query=$query . " order by " . $orderby;  
		}		
			
	
//echo $query;

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

if($numReg>0){	
	
	$fila=pg_fetch_array($resultado);
	echo ($fila[$campo1]);
		
}

//echo $query; 

pg_close($conexion);

?>
