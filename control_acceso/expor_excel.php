<?php
require_once('libs/conexion.php');
$cn=  Conectarse();
$listado =  pg_query($cn,"SELECT a.*, round(a.total_pagar::numeric, 2) as totalredondeado FROM pagos_periodos_trabajadores a  WHERE estatusp='ABIERTO' and estatus='ACTIVO'");
header('Content-Type: application/vnd.ms-excel');

header('Expires: 0');

header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

header('content-disposition: attachment;filename=PCT0616.xls');
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>comolohago.clt</title>
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" class="display">
                <thead>
                    <tr> 
                        <th>Periodo</th>                        
                        <th>Cedula</th>
                        <th>Trabajador</th>
                        <th>F. Ingreso</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Rel. Lab.</th>
                        <th>Tipo Nom.</th>
                        <th>CC</th>
                        <th>Sist. Hor.</th>
                        <th>Turno</th>
                        <th>Dias Transc.</th>
                        <th>Monto Estimado</th>
                        <th>Desc.</th>
                        <th>Deudas</th>
                        <th>Total Pagar</th>
                        <th>Estatus Trab.</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>
                     
                    <?php
                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                     
                               echo '<tr>';
                               echo '<td>'.$reg['periodo'].'</td>';                              
                               echo '<td>'.$reg['cedula'].'</td>';
                               echo '<td>'.$reg['trabajador'].'</td>';
                               echo '<td>'.$reg['fecha_ingreso'].'</td>';
                               echo '<td>'.$reg['cargo'].'</td>';
                               echo '<td>'.$reg['email'].'</td>';
                               echo '<td>'.$reg['relacion_laboral'].'</td>';
                               echo '<td>'.$reg['tipo_contrato'].'</td>';
                               echo '<td>'.$reg['centro_costo'].'</td>';
                               echo '<td>'.$reg['sistema_horario'].'</td>';
                               echo '<td>'.$reg['turno'].'</td>';
                               echo '<td>'.$reg['diast'].'</td>';
                               echo '<td>'.str_replace('.',',',$reg['montoactual']).'</td>';
                               echo '<td>'.$reg['descuento'].'</td>';
                               echo '<td>'.$reg['deudas'].'</td>';
                               echo '<td>'.str_replace('.',',',$reg['totalredondeado']).'</td>';
                               echo '<td>'.$reg['estatus'].'</td>';
                               echo '</tr>';
                               
                    }
                    ?>
                <tbody>
            </table>
</body>
</html>
 <?php 
pg_free_result($listado);
?>
	