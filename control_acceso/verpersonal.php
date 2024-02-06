<?php
$ced= isset($_GET["ced"])?$_GET["ced"]:"NULL";
require("libs/conexion.php");
$conexion = Conectarse_rrhh();
$query1 = "SELECT 
  trabajadores.trabajador, 
  trabajadores_grales.fecha_ingreso, 
  trabajadores_grales.cargo, 
  v_trabajadores_supervisores.nombres_jefe, 
  unidades.descripcion_unidad, 
  trabajadores_grales.turno, 
  trabajadores.e_mail, 
  trabajadores.nombres, 
  trabajadores.apellidos
FROM 
  trabajadores
inner join  trabajadores_grales on (trabajadores.trabajador = trabajadores_grales.trabajador)
inner join unidades on (trabajadores.fkunidad = unidades.idunidad)
left join  v_trabajadores_supervisores on (trabajadores_grales.trabajador = v_trabajadores_supervisores.trabajador
)
where trabajadores.trabajador='".$ced."'";

$resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query);

if (pg_num_rows($resultado1)>0){
  $fila1=pg_fetch_array($resultado1);
  $trabajador=$fila1["trabajador"];
  $cargo=$fila1["cargo"];
  $descripcion_gerencia=$fila1["descripcion_unidad"];
  $nombres_jefe=$fila1["nombres_jefe"];
  $nombres=$fila1["nombres"].' '.$fila1["apellidos"];
  $fecha_ingreso=$fila1["fecha_ingreso"];
  $foto="../intranet/briqven/sites/all/modules/cumples/img/fotcarmat_new/".$trabajador.".bmp";
  if (!file_exists($foto))
      $foto="images/silueta.png";
} else {

  $trabajador='';
  $cargo='';
  $descripcion_gerencia='';
  $nombres_jefe='';
  $nombres='';
  $fecha_ingreso='';
  $foto="images/silueta.png";
}
  pg_free_result($resultado1);

?>
<table width="100%" cellpadding="1" cellspacing="0" border="1" class="display">
  <tr>
    <td>
<table cellpadding="0" cellspacing="0" border="0" class="display">
  <tr>
    <td rowspan="7"><IMG SRC="<?php echo $foto; ?>" WIDTH="120" HEIGHT="140"></td>
    <th></th>
    <th></th>
  </tr>
  <tr>
    <td><b>Nombre:</b></td>
    <td><?php echo $nombres; ?></td>
  </tr>
  <tr>
    <td><b>Cedula:</b></td>
    <td><?php echo $trabajador; ?></td>
  </tr>
  <tr>
    <td><b>Fecha Ingreso:</b></td>
    <td><?php echo $fecha_ingreso; ?></td>
  </tr>
  <tr>
    <td><b>Departamento:</b></td>
    <td><?php echo $descripcion_gerencia; ?></td>
  </tr>
  <tr>
    <td><b>Cargo:</b></td>
    <td><?php echo $cargo; ?></td>
  </tr>
  <tr>
    <td><b>Jefe Inmediato:</b></td>
    <td><?php echo $nombres_jefe; ?></td>
  </tr>
</table>
</td></tr></table>
<?php pg_close($conexion); ?>