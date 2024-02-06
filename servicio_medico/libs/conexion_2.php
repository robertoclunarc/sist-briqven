<?php
/*function Conectarse_posgres(){
$servidor="localhost";
$basededatos="bdmatserviciomedico";
$usuario="roberto";
$clave="roberto";
$port = 5432;

$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

//pg_select_db($basededatos ,$cn) or die("Error seleccionando la Base de datos");
//pg_query ("SET NAMES 'utf8'");

return $cn;
}
*/
function Conectarse2_postgres(){
$servidor="localhost";
$basededatos="bdmatserviciomedico";
$usuario="roberto";
$clave="roberto";
$port = 5432;

$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
$cnx = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

return $cnx;
}

/*function Conectarse_sqlserver(){
try {
    $hostname = "10.50.188.40";
    $dbname = "matesisitt";
    $username = "matlux";
    $pw = "matesi.15";
    $dbh = new PDO ("dblib:host=$hostname;dbname=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

return $dbh;
}*/
?>
