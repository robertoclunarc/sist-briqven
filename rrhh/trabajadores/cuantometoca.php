<?php
require_once('libs/conexion.php');
$conn = oci_connect('matlux', 'lux1705', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
//$query="select a.* from trabajadores a where a.nombre like '%VITMA%'";
$query="select a.id_calendario, a.trabajador, c.nombre, a.concepto, b.descripcion, a.factor_01,a.factor_02,a.factor_03, a.importe
from transacciones_ns a, conceptos b, trabajadores c
where a.trabajador=c.trabajador 
and a.concepto=b.concepto
and  a.trabajador='  10065223' order by a.concepto";

/*$query="select a.id_calendario, a.trabajador, c.nombre, a.concepto, b.descripcion, a.factor_01,a.factor_02,a.factor_03, a.importe
from transacciones_ns a, conceptos b, trabajadores c
where a.trabajador=c.trabajador 
and a.concepto=b.concepto
and a.concepto = 9999
and  a.trabajador in ('  10065223', '  15354710', '  17218799', '  16395343',  '  11586419', '  14836899', '  17631002', '  18665507', '  17110005') order by a.trabajador";
*/
$stid = oci_parse($conn, $query);
oci_execute($stid);

echo "<table border='1'>\n";
/*
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    echo "<tr>\n";
    
        echo "    <td>" . $row['TRABAJADOR'] . "</td>\n";
    
    echo "</tr>\n";
}
*/
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
?>