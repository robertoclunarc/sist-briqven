<?php
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$valores = explode('|',$_POST['frm']);
$pos=count($valores)-1;
$i=1;
while ($i <= $pos) {
	$query="UPDATE carga_familiar_hcm SET sit_carga=2,maternidad=0,hcm=0 WHERE trabajador='".$valores[0]."' AND secuencia=".$valores[$i];	
	$i=$i+1;
	$inser=  pg_query($cn,$query);
	print_r( pg_last_error() );

}
?>