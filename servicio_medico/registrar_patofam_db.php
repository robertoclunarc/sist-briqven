<?PHP
require("include_conex.php");

$txtDescripcion= isset($_POST["txtDescripcion"])?$_POST["txtDescripcion"]:"";  //
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//
//Inserta el registro de Consulta
//
$query = "INSERT INTO tbl_patologias(descripcion, tipo) VALUES (";
$query = $query  . "'" . $txtDescripcion . "','Antecedente 9'); ";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

pg_close($conexion);

echo ("0"); //OK

?>
