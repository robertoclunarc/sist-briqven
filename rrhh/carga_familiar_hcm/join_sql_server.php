<?php 
set_time_limit(300);
require_once('libs/conexion.php');
$cn=  Conectarse_posgres();
$conn = Conectarse_sqlserver();   
/*$sql = "select a.trabajador, b.dato_02,c.nombre,c.sexo,c.fecha_nacimiento,
c.domicilio,c.domicilio2, c.telefono_particular, b.indice_inf_soc,
b.secuencia,0 as hcm,0 as maternidad,
b.dato_01,b.dato_02,b.dato_03,b.dato_04,b.dato_05
from tabla_para_postgres_trabajadores_grales a,
tabla_para_postgres_inf_soc_trabajador b,
tabla_para_postgres_personas_relacionada c
where a.sit_trabajador = 1
and a.trabajador =  b.trabajador
and a.trabajador =  c.trabajador
and b.indice_inf_soc='CARGFAM'
and c.persona_relacionada=b.persona_relacionada
order by a.trabajador,b.secuencia";*/

$sql = "select a.trabajador, c.persona_relacionada
from tabla_para_postgres_trabajadores_grales a,
tabla_para_postgres_inf_soc_trabajador b,
tabla_para_postgres_personas_relacionada c
where a.sit_trabajador = 1
and a.trabajador =  b.trabajador
and a.trabajador =  c.trabajador
and b.indice_inf_soc='CARGFAM'
and c.persona_relacionada=b.persona_relacionada
order by a.trabajador,b.secuencia";

$stmt = $conn->query($sql);
$stmt->setFetchMode(PDO::FETCH_NUM);

//$rows = $stmt->fetchAll();
//print_r ($rows);
$i=0;
$band='';
$acum='';
while ($row = $stmt->fetch()) {	
	/*$valor4=(empty($row[4])) ? 'NULL' : "'".$row[4]."'";
	$valor5=(empty($row[5])) ? 'NULL' : "'".$row[5]."'";
	$valor9=(empty($row[9])) ? 'NULL' : $row[9];
	$valor11=(empty($row[11])) ? 'NULL' : $row[11];
	$valor12=(empty($row[12])) ? 'NULL' : $row[12];
	$valor13=(empty($row[13])) ? 'NULL' : $row[13];
	$valor14=(empty($row[14])) ? 'NULL' : $row[14];
	$valor15=(empty($row[15])) ? 'NULL' : $row[15];
	
$insert="INSERT INTO trabajadores_grales VALUES ('".$row[0]."','".trim($row[1])."','".$row[2]."','".$row[3]."',".$valor4.",".$valor5.",'".$row[6]."','".$row[7]."','".$row[8]."',".$valor9.",'".$row[10]."',".$valor11.",".$valor12.",".$valor13.",".$valor14.",".$valor15.")";

$valor4=(empty($row[4])) ? 'NULL' : "'".$row[4]."'";
	/*$valor5=(empty($row[5])) ? 'NULL' : "'".$row[5]."'";
	$valor9=(empty($row[9])) ? 'NULL' : $row[9];
	$valor11=(empty($row[11])) ? 'NULL' : $row[11];
	$valor12=(empty($row[12])) ? 'NULL' : $row[12];
	$valor13=(empty($row[13])) ? 'NULL' : $row[13];
	$valor14=(empty($row[14])) ? 'NULL' : $row[14];
	$valor15=(empty($row[15])) ? 'NULL' : $row[15];*/
	
	
/*$insert="INSERT INTO cagar_familiar_hcm VALUES ('".trim($row[0])."','".substr(trim($row[1]),-10)."','".$row[2]."',".$row[3].",'".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."',".$row[9].",".$row[10].",".$row[11].",'".$row[12]."','".$row[13]."','".$row[14]."','".$row[15]."','".$row[16]."')";*/
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

?>