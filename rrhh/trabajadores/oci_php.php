<?php
require_once('libs/conexion.php');
$conn = oci_connect('matlux', 'lux1705', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT trabajador FROM trabajadores");
oci_execute($stid);

echo "<table border='1'>\n";
//while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    echo "<tr>\n";
    
        echo "    <td>" . $row['TRABAJADOR'] . "</td>\n";
    
    echo "</tr>\n";
}

/*
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";
    }
    echo "</tr>\n";
}
*/
echo "</table>\n";
echo "<table border='1'>\n";
echo "<tr>\n";
$cn=  Conectarse_posgres();     
$sql = "SELECT * FROM tbl_gerencia ";
$res = pg_query($cn,$sql);
$count = pg_num_rows($res);
while ($row = pg_fetch_array($res)){
    echo "<tr>\n";    
    echo "<td>" . $row['nombre'] . "</td>\n";    
    echo "</tr>\n";
}
echo "</table>\n";
?>