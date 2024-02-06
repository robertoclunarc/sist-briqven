<?php 
$buscar = isset($_GET['b'])?$_GET['b']:"NULL";    //
//$buscar = $_GET['b'];
if(!empty($buscar)) {
      buscar($buscar);
}
 
function buscar ($b){
include("../BD/conexion.php");
$link=Conex_rrhh_pgsql();
$sql = "SELECT 
  replace(trabajadores.nombre, '/', ' ') as nombreyapellido, 
  case when trabajadores_grales.sit_trabajador=1 then 'ACTIVO'
   else 'INACTIVO' 
  end as estatus,
  trabajadores.trabajador
FROM 
  public.trabajadores, 
  public.trabajadores_grales
WHERE 
  trabajadores.trabajador = trabajadores_grales.trabajador";

if ($b!="NULL"){
  $sql.=" and trabajadores.trabajador = '".$b."' ";
}else{
  $sql.=" and trabajadores_grales.trabajador = 1";
}

$result = pg_query($link,$sql);
                
if (pg_num_rows($result) > 0){
            
        $row=pg_fetch_array($result);                 
        echo "$(\"#hddcedula\").val(\"" . $row['trabajador'] . "\");\n" ;
        echo "$(\"#txtnombres\").val(\"" . $row['nombreyapellido'] . "\");\n" ;
        echo "$(\"#hddtestatus\").val(\"" . $row['estatus'] . "\");\n" ;                   
}
else     
      echo '0';
pg_free_result($result);
pg_close($link);    
}     
?>