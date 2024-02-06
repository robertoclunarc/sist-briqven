<?PHP
//$user = "postgres";
//$password = "matesi";
//$dbname = "amedicadb";
//$port = "5432";
//$host = "localhost";

require("include_conex.php");

$ci = isset($_POST["txtCi"])?$_POST["txtCi"]:"";                    //
$nombre= isset($_POST["txtNombre"])?$_POST["txtNombre"]:"";         //
$id_ss= isset($_POST["id_ss"])?$_POST["id_ss"]:"";         //
$chkActivo= isset($_POST["chkActivo"])?$_POST["chkActivo"]:"";   //
$nro_colegiado = isset($_POST["nro_colegiado"])?$_POST["nro_colegiado"]:""; 
$nombre_ssst = isset($_POST["nombre_ssst"])?$_POST["nombre_ssst"]:""; 
$tipo_ssst = isset($_POST["tipo_ssst"])?$_POST["tipo_ssst"]:""; 

if ($chkActivo=="on")
	$activo="true";
else
	$activo="false";
	
//echo $selected ;
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//buscar la cedula
//
$query="set datestyle = 'SQL, DMY'; Select * from tbl_medicos where ci='" . $ci . "'";
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

if($numReg>0){
	//el registro existe
	echo("1");
	pg_close($conexion);
	die("");
}

$query = "set datestyle = 'SQL, DMY'; INSERT INTO tbl_medicos(
            ci, nombre, activo, id_ss, nro_colegiado, nombre_ssst, tipo_ssst)
    VALUES (";
$query = $query . "'" . $ci . "','" . $nombre . "'," . $activo . ",'" . $id_ss . "', '".$nro_colegiado."', '".$nombre_ssst."' , '".$tipo_ssst."'); ";

//echo($query);

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

pg_close($conexion);

echo ("0"); //OK

?>
