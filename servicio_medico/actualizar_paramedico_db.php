<?PHP
//$user = "postgres";
//$password = "matesi";
//$dbname = "amedicadb";
//$port = "5432";
//$host = "localhost";

require("include_conex.php");

$uid = isset($_POST["txtUid"])?$_POST["txtUid"]:"";
$ci = isset($_POST["txtCi"])?$_POST["txtCi"]:"";                    //
$nombre= isset($_POST["txtNombre"])?$_POST["txtNombre"]:"";         //
$chkActivo= isset($_POST["chkActivo"])?$_POST["chkActivo"]:"";   //

if ($chkActivo=="on")
	$activo="true";
else
	$activo="false";
	
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar el id
//
$query="set datestyle = 'SQL, DMY'; Select * from tbl_medicos where uid=" . $uid . "";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

if($numReg==0){
	//el registro no existe
	echo("1");
	pg_close($conexion);
	die("");
}

$query = "set datestyle = 'SQL, DMY'; update tbl_paramedicos set ";
$query = $query . "ci='" . $ci . "',";
$query = $query . "nombre='" . $nombre . "',";
$query = $query . "activo='" . $activo . "'";
$query = $query . " where uid=" .$uid . ";";

//echo($query);

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

pg_close($conexion);

echo ("0"); //OK

?>
