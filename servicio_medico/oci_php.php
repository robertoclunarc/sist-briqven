<?php
require_once('libs/conexion_2.php');
$cn=  Conectarse_posgres(); 
$conn = oci_connect('matlux', 'lux1705', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$cedulas='(';
$sqlp = "select ci from tbl_pacientes";
$resp = pg_query($cn,$sqlp);
while ($rowp = pg_fetch_array($resp)){
   $cedulas=$cedulas.$rowp['ci'].',';   
}
$cedulas=$cedulas.'0)';

$stid = oci_parse($conn, "select ltrim(t.trabajador) as ci,  
substr(t.nombre,instr(t.nombre,'/',1,2)+1) as  nombre ,
 translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/','. ') as  apellido ,
 to_char(t.fecha_nacimiento, 'yyyy-mm-dd') as fecha_nacimiento,
 decode(t.sexo,1,'M','F') as sexo,
 v.desc_puesto as cargo,
 g.turno,
to_char(g.fecha_ingreso, 'yyyy-mm-dd') as antiguedad,
 to_char(g.fecha_ingreso, 'yyyy-mm-dd') as fecha_ingreso,
 i.des_documento_01 as tipo_sangre,
 i.lugar_nacimiento,
 decode(rtrim(r.dato),'SOL', 'Solter@', 'CAS','Casad@' ,'DIV','Divorciad@','VIU','Viud@','Soltera@') as edo_civil,
 i.nacionalidad,
 g.telefono_oficina as telefono,
substr(domicilio || ' ' || domicilio2 || ' ' || poblacion || ' ' || estado_provincia,1,100) as direccion_hab,
rtrim(v.centro_costo) as centro_costo, ltrim(v.trabajador_sup) as supervisor
from VW_DATOS_TRAB_SITTWEB_SID v, trabajadores t, inf_complementaria i,rel_trab_agr r,trabajadores_grales g
where 
 r.agrupacion='EDOCIVIL'
and g.sit_trabajador=1
and v.trabajador=t.trabajador
and t.trabajador=i.trabajador
and t.trabajador=r.trabajador
and t.trabajador=g.trabajador and to_number(t.trabajador) not in ".$cedulas);
oci_execute($stid);

$sql1 = "select uid, ccosto from tbl_departamentos";
$res1 = pg_query($cn,$sql1);
$i=0;
while ($row1 = pg_fetch_array($res1)){
   $cc[$i]=$row1['uid'];
   $de[$i]=$row1['ccosto'];
   $i++;
}
$entro=false;
echo "<table border='1'>\n";
//while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
	$entro=true;
    echo "<tr>\n";    
    echo "<td>" . $row['NOMBRE'] . "</td>\n";    
    echo "</tr>\n";
    $p=buscar($de,$row['CENTRO_COSTO']);
    if (isset($row['TELEFONO']))      
  		$telefono="'".$row['TELEFONO']."'";
    else
        $telefono="NULL";
    $sql = "INSERT INTO tbl_pacientes(ci ,
  nombre, 
  apellido,
  id_departamento,
  es_contratista,  
  fechanac,
  sexo, 
  cargo,
  turno,   
  antiguedad_puesto ,  
  fecha_ingreso,
  tipo_sangre,
  lugar_nac,
  edo_civil,
  nacionalidad,
  telefono,
  direccion_hab) VALUES ('".$row['CI']."', '".$row['NOMBRE']."', '".$row['APELLIDO']."',".$cc[$p].", FALSE,'".$row['FECHA_NACIMIENTO']."','".$row['SEXO']."','".$row['CARGO']."','".$row['TURNO']."','".$row['ANTIGUEDAD']."','".$row['FECHA_INGRESO']."','".$row['TIPO_SANGRE']."','".$row['LUGAR_NACIMIENTO']."','".$row['EDO_CIVIL']."','".$row['NACIONALIDAD']."',".$telefono.",'".$row['DIRECCION_HAB']."');";
    $res = pg_query($cn,$sql);
    if (!$res){
    	echo $sql;
      exit;
    }

   // $sqlv = "INSERT INTO trabajadores_supervisores(trabajador, supervisor) VALUES ('".$row['CI']."', '".$row['SUPERVISOR']."');";
    //$resv = pg_query($cn,$sqlv);
   // if (!$resv){
   //   echo $sqlv;
   //   exit;
   // }
}
if ($entro){
 $sqli = "INSERT INTO tbl_historia_medica(fecha_apertura, fk_medico, ha_sufrido_accidentes,ha_padecido_enfermeda,uid_paciente) select fecha_ingreso , 4, 'NO','NO', uid_paciente from tbl_pacientes where ci::numeric not in ".$cedulas;
 $resi = pg_query($cn,$sqli);
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

function buscar($ar,$busc){
$j=0;
  while ($j<count($ar)){
    if ($busc==$ar[$j])
      return $j;
    else
      $j++;
  }
}

/*
echo "<table border='1'>\n";
echo "<tr>\n";
$cn=  Conectarse_posgres();     
$sql = "INSERT INTO tbl_gerencia(uid, nombre) VALUES (?, ?, ?);";
$res = pg_query($cn,$sql);
$count = pg_num_rows($res);
while ($row = pg_fetch_array($res)){
    echo "<tr>\n";    
    echo "<td>" . $row['nombre'] . "</td>\n";    
    echo "</tr>\n";
}
echo "</table>\n";
*/
?>