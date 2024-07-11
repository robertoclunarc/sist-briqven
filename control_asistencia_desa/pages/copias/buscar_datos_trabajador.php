<?php 
$buscar = $_GET['b'];
if(!empty($buscar)) {
      buscar($buscar);
}
 
function buscar ($b){
include("../BD/conexion.php");
include("funciones_var.php");
$link=Conex_rrhh_pgsql();
$sql = "SELECT 
  trabajadores.nombres || ' ' || trabajadores.apellidos as nombreyapellido, 
  case when trabajadores_grales.sit_trabajador=1 then 'ACTIVO'
   else 'INACTIVO' 
  end as estatus,
  trabajadores.trabajador
FROM 
  public.trabajadores, 
  public.trabajadores_grales
WHERE 
  trabajadores.trabajador = trabajadores_grales.trabajador
and trabajadores.trabajador = '".$b."';";

$result = ejecutar_query($link,$sql);
                
if (ejecutar_num_rows($result) > 0){
        
//echo '<script language="javascript">alert("paso");</script>';
	$row=ejecutar_fetch_array($result);                 
//        echo "$(\"#hddcedula\").val(\"" . $row['trabajador'] . "\");\n" ;
        echo "$(\"#hddcedula\").val(\"" . $b . "\");\n" ;
        echo "$(\"#txtnombres\").val(\"" . $row['nombreyapellido'] . "\");\n" ;
        echo "$(\"#hddtestatus\").val(\"" . $row['estatus'] . "\");\n" ;                   
}
else     
      echo '0';
ejecutar_free_result($result);
ejecutar_close($link);    
}    
 
?>
