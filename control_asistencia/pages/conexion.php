<?php
function Conex_asistencia_pgsql(){	
	//$servidor="10.50.188.210";
//	$servidor="10.50.188.48";
//	$basededatos="bdmatasistencia_laboral";
	$basededatos="bdmatrrhh";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	//print $strCnx.'<br>';
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
	return $cn;
}

function Conex_Contancia_pgsql(){	
	//$servidor="10.50.188.210";
	$servidor="10.50.188.48";
	$basededatos="bdmatasistencia_laboral";
//	$basededatos="bdmatrrhh";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	//print $strCnx.'<br>';
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
	return $cn;
}

function Conex_control_asistencia(){	
	$servidor="10.50.188.48";
	$basededatos="bdmatasistencia_laboral";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	//print $strCnx.'<br>';
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
	return $cn;
}

function Conex_rrhh_pgsql(){
	$servidor="10.50.188.48";
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

function Conex_servicio_medicos(){	
	$servidor="10.50.188.48";
	$basededatos="bdmatserviciomedico";
	$usuario="roberto";
	$clave="roberto";
	$port = 5432;
	$strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
	//print $strCnx.'<br>';
	$cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
	return $cn;
}


?>
