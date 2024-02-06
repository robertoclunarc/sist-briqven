<?php
//Configuracion de la conexion a base de datos
require('../conexion.php');
$query="SELECT 
  trabajadores.cedula, 
  trabajadores.nombres, 
  trabajadores.apellidos, 
  trabajadores.fecha_ingreso, 
  trabajadores.relacion_laboral, 
  trabajadores.email, 
  trabajadores.centro_costo,
  trabajadores.cargo, 
  trabajadores.sistema_horario, 
  pagos.fecha_pago, 
  pagos.mes, 
  pagos.anho,
  pagos.pago_dia,
  pagos.total_dias_pagar,
  pagos.total_pagar,
  pagos.monto_base_diario
FROM 
  public.trabajadores, 
  public.pagos
WHERE 
  trabajadores.cedula = pagos.cedula and trabajadores.estatus='ACTIVO' and 
  pagos.mes='01' and pagos.anho='16'";
    $con = pg_connect ($strCnx) or die ("Error de conexion. ". pg_last_error());
    $resultado = pg_query($con, $query);



//muestra los datos consultados
echo "</p>Nombres - fecha_ingreso - email</p> \n";
while ($reg = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
	echo "<p>".$reg['nombres']." ".$reg['apellidos']." - ".$reg['fecha_ingreso']." - ".$reg['email']."</p> \n";
}
?>
