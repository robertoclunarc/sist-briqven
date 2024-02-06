<?php
function Conectarse_posgres(){
$servidor="localhost";
$basededatos="bdmat_comprobantes";
$usuario="roberto";
$clave="roberto";
$port = 5432;

$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

//pg_select_db($basededatos ,$cn) or die("Error seleccionando la Base de datos");
//pg_query ("SET NAMES 'utf8'");

return $cn;
}

function Conectarse_sqlserver(){
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
}

function Conectarse_oracle(){
$conx = oci_connect('ADAM', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
if (!$conx) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

return $conx;
}
?>
