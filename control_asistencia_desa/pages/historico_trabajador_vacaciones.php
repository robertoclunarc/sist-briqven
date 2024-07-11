<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

$qry="SELECT a.TRABAJADOR, 
a.CICLO_LABORAL, 
a.FECHA_INI_PER_VAC, 
a.FECHA_FIN_PER_VAC, 
a.FECHA_PAGO_VAC, 
a.FECHA_ALTA, 
a.situacion_programa, 
a.disposicion, 
a.condicion, 
a.dias_pendientes, 
a.motivo_interrupcion, 
a.fecha_ini_interrupcion, 
a.fecha_fin_interrupcion, 
a.dias_pendientes 
FROM dbo.adam_programacion_vacaciones a 
WHERE 1=1 ";

if ($trabajador!='NULL')
{
   $qry.=" AND a.TRABAJADOR=".$trabajador.""; 
}  


$qry.=" ORDER BY a.TRABAJADOR desc, a.CICLO_LABORAL DESC, a.FECHA_INI_PER_VAC desc";
//print $qry;
buscar($qry);     
}
else
  echo "Debe Iniciar Sesion";       

function buscar($b) {
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);        
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<b>Vacaciones</b><table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example03">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info">Item</th>             
                <th width="5%"class="info">Ciclo Laboral</th>        
                <th width="8%"class="info">Fecha Inicio</th>
                <th width="8%" class="info">Fecha Fin</th>
                <th width="8%" class="info">Fecha Pago</th>
                <th width="5%" class="info">Estatus</th>
                <th width="10%" class="info">Condicion</th>
                <th width="10%" class="info">Disposicion</th>
                <th width="8%" class="info">Ini. Interrup.</th>
                <th width="8%" class="info">Fin Interrup.</th>
                <th width="8%" class="info">Dias Pend.</th>
                <th width="20%" class="info">Motivo Interrupcion</th>
            </tr>
        </thead>
        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    if ($row['situacion_programa']==3)
                    { 
                      $situac="Disfrutado";
                      $clase="label label-info"; 
                    }
                    elseif ($row['situacion_programa']==2) 
                    {
                      $situac="Programada";
                      $clase="label label-danger";
                    }
                    else
                    {
                      $situac="Planificada";
                      $clase="label label-success";
                    }
                    if ($row['fecha_ini_interrupcion']!='' && $row['fecha_ini_interrupcion']!=null)
                       $fecha_ini_interrupcion=formato_fecha(substr($row['fecha_ini_interrupcion'], 0, 10),'-');
                    else
                        $fecha_ini_interrupcion='';

                    if ($row['fecha_fin_interrupcion']!='' && $row['fecha_fin_interrupcion']!=null)
                        $fecha_fin_interrupcion=formato_fecha(substr($row['fecha_fin_interrupcion'], 0, 10),'-');
                    else
                        $fecha_fin_interrupcion='';

                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';                    
                    $inpt .='<td>'.$row['CICLO_LABORAL'].'</td>';
                    $inpt .='<td>'.formato_fecha(substr($row['FECHA_INI_PER_VAC'], 0, 10),'-').'</td>';
                    $inpt .='<td>'.formato_fecha(substr($row['FECHA_FIN_PER_VAC'], 0, 10),'-').'</td>';
                    $inpt .='<td>'.formato_fecha(substr($row['FECHA_PAGO_VAC'], 0, 10),'-').'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$situac.'</span></td>';

                    $inpt .='<td>'.$row['condicion'].'</td>';
                    $inpt .='<td>'.$row['disposicion'].'</td>';
                    $inpt .='<td>'.$fecha_ini_interrupcion.'</td>';
                    $inpt .='<td>'.$fecha_fin_interrupcion.'</td>';
                    $inpt .='<td>'.$row['dias_pendientes'].'</td>';
                    $inpt .='<td>'.$row['motivo_interrupcion'].'</td>';
                                      
                    $inpt .='</tr>';
              } 
             
              $inpt .=' </tbody>

                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
