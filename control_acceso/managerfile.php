<?php 
function gestion_archivo($url,$mesp, $anhop, $nroced){
require_once('libs/conexion.php');    
$cnx=  Conectarse();
$queryUpdate="UPDATE pagos SET archivo='".$url."' WHERE mes='".$mesp."' AND anho='".$anhop."' AND cedula='".$nroced."'";
$result2 = pg_query($cnx,$queryUpdate);
unlink($url);
}
?> 