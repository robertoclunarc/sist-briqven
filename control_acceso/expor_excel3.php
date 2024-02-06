<?php
require_once('libs/conexion.php');
$cn=  Conectarse();
$listado =  pg_query($cn,"SELECT * FROM pagos_periodo_abierto");
$periodo_actual=pg_query($cn,"SELECT mesp,anhop FROM periodos  WHERE estatusp='ABIERTO'");
$prd = pg_fetch_array($periodo_actual, null, PGSQL_ASSOC);
function nombremes($mes){
 switch ($mes) {
    case '01':
        return 'ENERO';
        break;
     case '02':
        return 'FEBRERO';
        break;
     case '03':
        return 'MARZO';
        break;
 case '04':
        return 'ABRIL';
        break; 
 case '05':
        return 'MAYO';
        break;
 case '06':
        return 'JUNIO';
        break;
 case '07':
        return 'JULIO';
        break;
 case '08':
        return 'AGOSTO';
        break;
 case '09':
        return 'SEPTIEMBRE';
        break;
 case '10':
        return 'OCTUBRE';
        break;
 case '11':
        return 'NOVIEMBRE';
        break;
 case '12':
        return 'DICIEMBRE';
        break;
} 
 
} 
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('content-disposition: attachment;filename=datospreliminares'.nombremes($_GET['me']).'20'.$_GET['an'].'.xls');
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Excel</title>
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
                               echo '<td>'.$reg['descuento'].'</td>';
                               echo '<td>'.$reg['deudas'].'</td>';
                               echo '<td>'.str_replace('.',',',$reg['total_a_pagar']).'</td>';
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
	
