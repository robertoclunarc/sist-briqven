<?php
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$valores = explode('|',$_POST['frm']);
$pos=count($valores)/5;
$ii=0;
$i=0;
while ($ii < $pos) {
	$query="UPDATE carga_familiar_hcm SET maternidad=".$valores[$i+4].", hcm=".$valores[$i+3]." WHERE trabajador='".$valores[$i+2]."' AND persona_relacionada='".$valores[$i + 1]."' AND secuencia=".$valores[$i];
	$ii++;
	$i=$i+5;
	$inser=  pg_query($cn,$query);
	print_r( pg_last_error() );
}
?>