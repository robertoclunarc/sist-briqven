<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";
$CondVac= isset($_POST["cboCondVac"])?$_POST["cboCondVac"]:"NULL";

$export= isset($_GET["export"])?$_GET["export"]:"NULL";

$qry="SELECT pv.CICLO_LABORAL, pv.FECHA_PAGO_VAC, tg.TRABAJADOR, tg.NOMBRES, tg.fecha_ingreso, pv.SITUACION_PROGRAMA, pv.FECHA_INI_PER_VAC, pv.FECHA_FIN_PER_VAC, pv.FECHA_ALTA, pv.disposicion, pv.condicion, pv.dias_pendientes, pv.motivo_interrupcion, pv.fecha_ini_interrupcion, pv.fecha_fin_interrupcion, pv.dias_pendientes, SUM(tiempo_prog_vac)
FROM dbo.adam_programacion_vacaciones pv,dbo.ADAM_DATOS_PERSONALES tg
WHERE  ( pv.fecha_ini_per_vac BETWEEN '".$finicio."' AND '".$ffin."'
OR ( pv.fecha_ini_per_vac < '".$finicio."' AND pv.fecha_fin_per_vac > '".$ffin."' )
OR pv.fecha_fin_per_vac BETWEEN '".$finicio."' AND '".$ffin."'
OR pv.fecha_pago_vac BETWEEN '".$finicio."' AND '".$ffin."' )
AND pv.trabajador = tg.trabajador";

if ($trabajador!='NULL')
    $qry.=" and pv.trabajador=".$trabajador."";

  if ($CondVac!='NULL')
    $qry.=" and pv.condicion='".$CondVac."'";

$qry.=" GROUP BY ciclo_laboral, fecha_pago_vac, tg.trabajador, tg.NOMBRES ,tg.fecha_ingreso, FECHA_INI_PER_VAC , FECHA_FIN_PER_VAC ,pv.situacion_programa, FECHA_ALTA, pv.disposicion, pv.condicion, pv.dias_pendientes, pv.motivo_interrupcion, pv.fecha_ini_interrupcion, pv.fecha_fin_interrupcion, pv.dias_pendientes";

$qry.=" ORDER BY ciclo_laboral, fecha_pago_vac, tg.trabajador, tg.fecha_ingreso, pv.situacion_programa DESC";    


buscar($qry, $export);     
       
function buscar($b, $export) {
       include("../BD/conexion.php");
       require_once('funciones_var.php');
       $linkPerm=Conex_Contancia_pgsql();
        $acceso=permiso_usuario($linkPerm, 'IMPRIMIR', 'planilla_vacacion01.php', $_SESSION['user_session_const']);
        pg_close($linkPerm);
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
            <tr>';
            if ($export!='excel'  && $acceso){                
              $inpt.='<th width="5%" class="info">Imp.</th>';
            }
            $inpt.='<th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="5%"class="info">Ciclo Laboral</th>        
                <th width="8%"class="info">Fecha Inicio</th>
                <th width="8%" class="info">Fecha Fin</th>
                <th width="8%" class="info">Fecha Pago</th>
                <th width="8%" class="info">Fecha alta</th>
                <th width="8%" class="info">Estatus</th>
                <th width="10%" class="info">Condicion</th>
                <th width="10%" class="info">Disposicion</th>
                <th width="8%" class="info">Ini. Incidencia</th>
                <th width="8%" class="info">Fin Incidencia</th>
                <th width="8%" class="info">Dias Pend.</th>
                <th width="20%" class="info">Desc. Incidencia</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    if ($row['SITUACION_PROGRAMA']==3)
                    { 
                      $situac="Disfrutado";
                      $clase="label label-info"; 
                    }
                    elseif ($row['SITUACION_PROGRAMA']==2) 
                    {
                      $situac="Programada";
                      $clase="label label-danger";
                    }
                    else
                    {
                      $situac="Planificada";
                      $clase="label label-success";
                    }
                    $param_conf=$row['TRABAJADOR'].", ".$row['CICLO_LABORAL'];
                    $inpt .='<tr>';
                    //if ($export!='excel' && $row['condicion']!='Finalizada' && $acceso){
                    if ($export!='excel' && $acceso){
                        $inpt .='<td><div id="boton_'.$row['TRABAJADOR'].'">
                                    <button type="button" title="Imprimir PDF"
                                      onclick="impPDF('.$param_conf.')" 
                                      data-toggle="modal" 
                                      data-target="#exampleModalCenter" 
                                      class="btn btn-danger btn-circle">
                                      <i class="fa fa-file-pdf-o"></i>
                                    </button>
                                  </div></td>';
                    }              
                    $inpt .='<td>'.$row['TRABAJADOR'].'</td>';                    
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.$row['CICLO_LABORAL'].'</td>';
                    $inpt .='<td>'.substr($row['FECHA_INI_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_FIN_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_PAGO_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['FECHA_ALTA'].'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$situac.'</span></td>';

                    $inpt .='<td>'.$row['condicion'].'</td>';
                    $inpt .='<td>'.$row['disposicion'].'</td>';
                    $inpt .='<td>'.substr($row['fecha_ini_interrupcion'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['fecha_fin_interrupcion'], 0, 10).'</td>';
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
