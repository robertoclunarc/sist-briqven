<?php
function pg_conex_bdmatserviciomedico(){
    $servidor="localhost";
    $basededatos="bdmatserviciomedico";
    $usuario="roberto";
    $clave="roberto";
    $port = 5432;

    $strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
    $cnx = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

    return $cnx;
}

function pg_conex_bdmatrrhh(){
    $servidor="localhost";
    $basededatos="bdmatrrhh";
    $usuario="roberto";
    $clave="roberto";
    $port = 5432;

    $strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
    $cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

    return $cn;
}

function pg_conex_bdmat_ctrl_planta(){
    $servidor="localhost";
    $basededatos="bdmat_ctrl_planta";
    $usuario="roberto";
    $clave="roberto";
    $port = 5432;

    $strCnx = "host=$servidor port=$port dbname=$basededatos user=$usuario password=$clave";
    $cn = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());

    return $cn;
}

function pg_conex_bdmatasistencia_laboral(){    
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

function sqlserv_conex_sitt(){
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

function Conex_oramprd(){
    $conn = oci_connect('adam', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    return $conn;
}

?>
 