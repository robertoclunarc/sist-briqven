<?php
function Conectarse(){
$servidor="localhost";
$basededatos="bdmatcargas_sio";
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

function Conectarse_sio(){
	try {
    $hostname_sio = "10.50.188.46";
    $dbname_sio = "DBSio";
    $username_sio = "user_matesi";
    $pw_sio = "saomat";
    $dbh_sio = new PDO ("dblib:host=$hostname_sio;dbname=$dbname_sio","$username_sio","$pw_sio");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh_sio;
}

function Conectarse_SI(){
	try {
    $hostname_sio = "10.50.188.46";
    $dbname_sio = "mat_si";
    $username_sio = "user_matesi";
    $pw_sio = "saomat";
    $dbh_si = new PDO ("dblib:host=$hostname_sio;dbname=$dbname_sio","$username_sio","$pw_sio");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh_si;
}
?>
