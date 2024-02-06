<?php
try {
    $hostname = "MATPRDAPP01";
    $dbname = "Matesisitt";
    $username = "SIDORVE\matlux";
    $pw = "matesi.15";
    $dbh = new PDO ("dblib:host=$hostname;dbname=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Error al obtener Identif. de la BD: " . $e->getMessage() . "\n";
    exit;
}

echo "Paso!";
?>