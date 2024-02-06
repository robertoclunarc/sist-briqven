<?PHP

require_once('libs/conexion.php');
$cn=  Conectarse();
$conn = Conectarse_sqlserver();

$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, entrada_real1, salida_real1 
from tb_21_comidas order by cedula, fecha";

$stmt = $conn->query($sql);

while ($row = $stmt->fetch()) {	
	
	echo $row[0].'**';
	echo $row[1].'**';
	echo $row[2].'**';
	echo $row[3].'<BR>';

	$valor11= $row[0];
	$valor12= $row[1];
	$valor13=(empty($row[2])) ? NULL : $row[2];
	$valor14=(empty($row[3])) ? NULL : $row[3];	
	
}

?>
