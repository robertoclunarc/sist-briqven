<?PHP
require("include_conex.php");

$txtDescripcion= isset($_POST["txtDescripcion"])?$_POST["txtDescripcion"]:"";  //
$agente= isset($_POST["cboAgentes"])?$_POST["cboAgentes"]:"";
$tipo= isset($_POST["txttipo"])?$_POST["txttipo"]:"";
$req= isset($_POST["txtreq"])?$_POST["txtreq"]:"";
if ($tipo!='')
	$agente=$tipo;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//
//Inserta el registro de Consulta
//
$query = "INSERT INTO tbl_riesgos(descripcion, agente, datos_requeridos) VALUES (";
$query = $query  . "'" . $txtDescripcion . "','".$agente."', '".$req."'); ";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

pg_close($conexion);

echo ("0"); //OK

?>
