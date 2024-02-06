<?php
function contador_registros($query)
{ 
require_once('libs/conexion_2.php');
$cnx_oracle= Conectarse_oracle();	
$contador=0;
$stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
oci_execute($stid);
//$contador= oci_num_rows($stid);
while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false) {
	$contador++;
}
oci_free_statement($stid);
return $contador;
}
?>