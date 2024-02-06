<?php
function Conectarse(){
$servidor="localhost";
$basededatos="bdmat_ctrl_planta";
$usuario="roberto";
$clave="roberto";
$port = 5432;

$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

//pg_select_db($basededatos ,$cn) or die("Error seleccionando la Base de datos");
//pg_query ("SET NAMES 'utf8'");

return $cn;
}

function Conectarse_rrhh(){
	$servidor_rrhh="localhost";
	$basededatos_rrhh="bdmatrrhh";
	$usuario_rrhh="roberto";
	$clave_rrhh="roberto";
	$port_rrhh = 5432;

	$strCnx_rrhh = "host=$servidor_rrhh port=$port_rrhh dbname=$basededatos_rrhh user=$usuario_rrhh password=$clave_rrhh";
	$cn_rrhh = pg_connect($strCnx_rrhh) or die ("Error de conexion. ". pg_last_error());

return $cn_rrhh;
}

function Conectarse_sitt(){
	try {
    $hostname_sitt = "10.50.188.41";
    $dbname_sitt = "matesisitt";
    $username_sitt = "sittweb";
    $pw_sitt = "matesi.6";
    $dbh_sitt = new PDO ("dblib:host=$hostname_sitt;dbname=$dbname_sitt","$username_sitt","$pw_sitt");
     $dbh_sitt->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh_sitt;
}

function Conectarse_ccure(){
	try {
    $hostname_ccure = "10.50.188.37";
    $dbname_ccure = "SWHSystemJournal";
    $username_ccure = "recovery";
    $pw_ccure = "Inicio01";
    $dbh_ccure = new PDO ("dblib:host=$hostname_ccure;dbname=$dbname_ccure","$username_ccure","$pw_ccure");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh_ccure;
}

function Conectarse_prueba(){
    $serverName = "10.50.188.37\SQL Server"; //serverName\instanceName

// Puesto que no se han especificado UID ni PWD en el array  $connectionInfo,
// La conexión se intentará utilizando la autenticación Windows.
$connectionInfo = array( "Database"=>"SWHSystemJournal", "UID"=>"recovery", "PWD"=>"Inicio01");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
    return $conn;
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
}
?>
