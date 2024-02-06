<?php
require_once('../libs/conexion.php');

$cn=  Conectarse();
$query="select * from dias_descuentos_deudas order by fkpago, fecha";
$listado=  pg_query($cn,$query);
$cont=1;
$idpx=0;
while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
 {  
   	$idp=$reg['fkpago']; 
  	$fe=$reg['fecha'];
	$tip=$reg['tipo'];
	$con=$reg['condicion'];
	$des=$reg['descripcion_descuento'];
 	$mon=$reg['monto'];
	
	if ($idp==$idpx)
           $cont++;
	else{
           $cont=1;
	   $idpx=$reg['fkpago'];
   	}
$query1="update dias_descuentos_deudas set indice=".$cont." where fkpago=".$idp." and fecha='".$fe."' and tipo='".$tip."' and condicion='".$con."' and monto='".$mon."' and descripcion_descuento='".$des."'";
   
	$upd=pg_query($cn,$query1);
	
}

//echo pg_result_error($update);
//exit;
echo "listo";
?>
