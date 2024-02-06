<?php 

//set_time_limit(300);
require_once('libs/conexion.php');
//$cn=  Conectarse_posgres();
$conn = Conectarse_sqlserver();

$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, entrada_real1, salida_real1 from tb_21_comidas order by cedula, fecha";

$stmt = $conn->query($sql);
//$stmt->setFetchMode(PDO::FETCH_NUM);

//$rows = $stmt->fetchAll();
//print_r ($rows);
$users = array();
$i = 0;
//ini_set('memory_limit', '1024M');
//while ($row = $stmt->setFetchMode(PDO::FETCH_NUM)) {	
while ($row = $stmt->fetch()) {	
	// $users[$i] = array();
	echo $row[0].'**';
	echo $row[1].'**';
	echo $row[2].'**';
	echo $row[3].'<BR>';
	//$valor11=(empty($row[11])) ? 'NULL' : $row[11];
	//$users[$i]['trabajador']=(empty($row[0])) ? 'NULL' : $row[0];
	//$users[$i]=(empty($row[0])) ? 'NULL' : "'".$row[0]."'";
	//$users[$i]['registro_fiscal']=(empty($row[1])) ? 'NULL' : "'".$row[1]."'";
	/*$users[$i]['nombre']=(empty($row[2])) ? 'NULL' : "'".$row[2]."'";
	$users[$i]['sexo']=(empty($row[3])) ? 'NULL' : "'".$row[3]."'";
	$users[$i]['fecha_nacimiento']=(empty($row[4])) ? 'NULL' : "'".$row[4]."'";
	$users[$i]['domicilio']=(empty($row[5])) ? 'NULL' : "'".$row[5]."'";
	$users[$i]['domicilio2']=(empty($row[6])) ? 'NULL' : "'".$row[6]."'";
	$users[$i]['poblacion']=(empty($row[7])) ? 'NULL' : "'".$row[7]."'";
	$users[$i]['estado_provincia']=(empty($row[8])) ? 'NULL' : "'".$row[8]."'";
	$users[$i]['pais']=(empty($row[9])) ? 'NULL' : "'".$row[9]."'";
	$users[$i]['codigo_postal']=(empty($row[10])) ? 'NULL' : "'".$row[10]."'";
	$users[$i]['calles_aledanas']=(empty($row[11])) ? 'NULL' : "'".$row[11]."'";
	$users[$i]['telefono_particular']=(empty($row[12])) ? 'NULL' : "'".$row[12]."'";
	$users[$i]['reg_seguro_social']=(empty($row[13])) ? 'NULL' : "'".$row[13]."'";
	$users[$i]['domicilio3']=(empty($row[14])) ? 'NULL' : "'".$row[14]."'";
	$users[$i]['e_mail']=(empty($row[15])) ? 'NULL' : "'".$row[15]."'";*/
	$i++;
}
$i--;

//for ($j=0;$j<=$i;$j++)
 //echo $users[$j]."<br>";

	
/*$insert="INSERT INTO trabajadores_grales VALUES ('".$row[0]."','".trim($row[1])."','".$row[2]."','".$row[3]."',".$valor4.",".$valor5.",'".$row[6]."','".$row[7]."','".$row[8]."',".$valor9.",'".$row[10]."',".$valor11.",".$valor12.",".$valor13.",".$valor14.",".$valor15.")";

$valor4=(empty($row[4])) ? 'NULL' : "'".$row[4]."'";
	$valor5=(empty($row[5])) ? 'NULL' : "'".$row[5]."'";
	$valor9=(empty($row[9])) ? 'NULL' : $row[9];
	$valor11=(empty($row[11])) ? 'NULL' : $row[11];
	$valor12=(empty($row[12])) ? 'NULL' : $row[12];
	$valor13=(empty($row[13])) ? 'NULL' : $row[13];
	$valor14=(empty($row[14])) ? 'NULL' : $row[14];
	$valor15=(empty($row[15])) ? 'NULL' : $row[15];
*/	
	
/*$insert="INSERT INTO cagar_familiar_hcm VALUES ('".trim($row[0])."','".substr(trim($row[1]),-10)."','".$row[2]."',".$row[3].",'".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."',".$row[9].",".$row[10].",".$row[11].",'".$row[12]."','".$row[13]."','".$row[14]."','".$row[15]."','".$row[16]."')";

if ($row[0] !=  $band)
  $acum=$band.$acum;
else 
	$acum=$band;
$insert="update carga_familiar_hcm set dato_02='".$row[1]."' where trabajador='".trim($row[0])."' and persona_relacionada not in "
print $insert.";\n \t ".$i."--";
pg_query($cn,$insert);
$i++;
}
$conn=null;
//(empty(row[9])) ? NULL : row[9];
*/
?>