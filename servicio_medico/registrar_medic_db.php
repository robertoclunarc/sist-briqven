<?PHP
require("include_conex.php");

$txtDescripcion= isset($_POST["txtDescripcion"])?$_POST["txtDescripcion"]:"";  //C: Carga (por defecto); D:Descarga 

$cboMedicamentos= isset($_POST["cboMedicamentos"])?$_POST["cboMedicamentos"]:"";         //

$cbotipos= isset($_POST["cbotipos"])?$_POST["cbotipos"]:"";
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//Inserta el registro de Consulta
//
$query = "insert into tbl_medicamentos ( descripcion, unidad_medida, tipo) values (";
$query = $query  . "'" . $txtDescripcion . "','"  . $cboMedicamentos . "','"  . $cbotipos . "'); ";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

pg_close($conexion);

echo ("0"); //OK

?>
