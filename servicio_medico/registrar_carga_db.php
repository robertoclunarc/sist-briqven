<?PHP
//$user = "postgres";
//$password = "matesi";
//$dbname = "amedicadb";
//$port = "5432";
//$host = "localhost";

require("include_conex.php");

$tipo= isset($_GET["tipo"])?$_GET["tipo"]:"C";  //C: Carga (por defecto); D:Descarga 

$fecha= isset($_POST["txtFecha"])?$_POST["txtFecha"]:"";         //
$responsable= isset($_POST["txtResponsable"])?$_POST["txtResponsable"]:"";     // 
$concepto= isset($_POST["txtConcepto"])?$_POST["txtConcepto"]:"";     // 

		
//$codRemedios= isset($_POST["codRemedios"])?implode(", ", noescape($_POST["codRemedios"])):null;   //
//$cantidades= isset($_POST["cantidades"])?implode(", ", noescape($_POST["cantidades"])):null;   //

//echo("Arreglo:" . $_POST["codRemedios"]);	
	
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";



//Inserta el registro de Consulta
//
$query = "insert into tbl_mov_inventario ( fecha, tipo_op, concepto, responsable) values (";
$query = $query  . "'" . $fecha . "','"  . "C" . "','" . $concepto . "', '" . $responsable . "'" . "); ";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);


//Obtener el código recién generado
//
$query="Select currval('tbl_mov_inventario_uid_seq') as myid;";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

$fila=pg_fetch_array($resultado);

$id_nuevo  = $fila["myid"];

//echo "Consulta agregada : <" . $id_consulta . ">";

//Inserta el registro de Movimiento
//
$i=0;

if(array_key_exists('codRemedios',$_POST))
{
	$codRemedios = $_POST['codRemedios'];
	
	//echo($codRemedios);
	//die();
	
	$cantidades = $_POST['cantidades'];
	
	foreach ($codRemedios as &$remedio)
	{
		$cantidad = $cantidades[$i];
		$query = "insert into tbl_mov_inventario_detalle (id_mov, id_medicamento, cantidad) values (";
		$query = $query . $id_nuevo . "," . $remedio . "," . $cantidad . "); ";
	
		//echo($query);
		$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
	
		//Actualizar el Inventario
		if ($tipo='D')
			$signo="-";
		else
			$signo="+";
		$query = "update tbl_medicamentos set existencia=existencia" . $signo . $cantidad . " where uid=" . $remedio . ";";
		$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
		
		$i++;
	}

}

pg_close($conexion);

echo ("0"); //OK

?>
