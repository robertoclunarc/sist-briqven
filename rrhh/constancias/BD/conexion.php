<?php
function Conex_Contancia_pgsql(){	
	$servidor="localhost";
	$basededatos="bdmatconstancia";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
return $cn;
}

function Conex_rrhh_pgsql(){
	$servidor="localhost";
	$basededatos="bdmatrrhh";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
return $cn;
}

function Conex_oramprd(){
$conn = oci_connect('adam', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
return $conn;
}
?>