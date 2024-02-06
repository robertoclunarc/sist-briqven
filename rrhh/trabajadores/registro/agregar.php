<?php
require_once('../libs/conexion.php');
$cn=  Conectarse();
$valores = explode('|',$_POST['frm']);
$query="INSERT INTO notas (fecha_nota,cedula,fkpagonota,leida,nota) VALUES ('".$valores[0]."','".$valores[1]."',".$valores[2].",'".$valores[3]."','".$valores[4]."')";
$inser=  pg_query($cn,$query);
print_r( pg_last_error() );
?>
