<?php
function Conectarse_posgres(){
$servidor="localhost";
$basededatos="bdmatrrhh";
$usuario="roberto";
$clave="roberto";
$port = 5432;

$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

//pg_select_db($basededatos ,$cn) or die("Error seleccionando la Base de datos");
//pg_query ("SET NAMES 'utf8'");

return $cn;
}

function Conectarse_SITT(){
	try {
    $hostname = "10.50.188.41";
    $dbname = "matesisitt";
    $username = "sittweb";
    $pw = "matesi.6";
    $dbh = new PDO ("dblib:host=$hostname;dbname=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh;
}
?>
