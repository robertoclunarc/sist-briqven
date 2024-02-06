<?php
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$valores = explode('|',$_POST['frm']);
$query="INSERT INTO seleccion_regalos (trabajador,fkopcion,estatus) SELECT '".$valores[0]."',idopcion,'SELECCIONADO' FROM regalos WHERE grupo_opcion='".$valores[1]."'";
$inser=  pg_query($cn,$query);
print_r( pg_last_error() );
?>
