<?php
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$ciclo= isset($_POST["cbociclolaboral"])?$_POST["cbociclolaboral"]:"NULL";

$qry="SELECT a.TRABAJADOR, b.NOMBRES, a.CICLO_LABORAL, a.FECHA_INI_PER_VAC, a.FECHA_FIN_PER_VAC, a.FECHA_PAGO_VAC, a.FECHA_ALTA, a.situacion_programa, a.disposicion, a.condicion, a.dias_pendientes, a.motivo_interrupcion, a.fecha_ini_interrupcion, a.fecha_fin_interrupcion, a.dias_pendientes FROM dbo.adam_programacion_vacaciones a INNER JOIN dbo.ADAM_DATOS_PERSONALES b ON CAST(a.TRABAJADOR AS int)=b.Trabajador WHERE 1=1 ";

if ($trabajador!='NULL')
{
   $qry.=" AND b.TRABAJADOR=".$trabajador."";
   if ($ciclo!='NULL')
     $qry.=" AND a.CICLO_LABORAL=".$ciclo."";   
}  
else
    $qry.=" AND a.CICLO_LABORAL=".$ciclo."";

$qry.=" ORDER BY a.TRABAJADOR desc, a.CICLO_LABORAL DESC, a.FECHA_INI_PER_VAC desc";
//print $qry;
buscar($qry,$ciclo);     
}
else
  echo "Debe Iniciar Sesion";       

function buscar($b,$ciclo) {
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);        
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info">Item</th>             
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="5%"class="info">Ciclo Laboral</th>        
                <th width="8%"class="info">Fecha Inicio</th>
                <th width="8%" class="info">Fecha Fin</th>
                <th width="8%" class="info">Fecha Pago</th>
                <th width="5%" class="info">Estatus</th>
                <th width="10%" class="info">Condicion</th>
                <th width="10%" class="info">Disposicion</th>
                <th width="8%" class="info">Ini. Incidencia.</th>
                <th width="8%" class="info">Fin Incidencia.</th>
                <th width="8%" class="info">Dias Pend.</th>
                <th width="20%" class="info">Desc. Incidencia</th>
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

                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';                    
                    $inpt .='<td>'.$row['TRABAJADOR'].'</td>';          
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.$row['CICLO_LABORAL'].'</td>';
                    $inpt .='<td>'.substr($row['FECHA_INI_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_FIN_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_PAGO_VAC'], 0, 10).'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$situac.'</span></td>';

                    $inpt .='<td>'.$row['condicion'].'</td>';
                    $inpt .='<td>'.$row['disposicion'].'</td>';
                    $inpt .='<td>'.substr($row['fecha_ini_interrupcion'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['fecha_fin_interrupcion'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['dias_pendientes'].'</td>';
                    $inpt .='<td>'.$row['motivo_interrupcion'].'</td>';
                                      
                    $inpt .='</tr>';
                    if ($ciclo!='NULL')
                    { 
                      $inpt .='<input  name="hddestatusvac" id="hddestatusvac" type="hidden" value="'.$row['situacion_programa'].'"/>';
                      $inpt .='<input  name="hddfechainivac" id="hddfechainivac" type="hidden" value="'.substr($row['FECHA_INI_PER_VAC'], 0, 10).'"/>';
                      $inpt .='<input  name="hddfechafinvac" id="hddfechafinvac" type="hidden" value="'.substr($row['FECHA_FIN_PER_VAC'], 0, 10).'"/>';
                      $inpt .='<input  name="hddfechapagovac" id="hddfechapagovac" type="hidden" value="'.substr($row['FECHA_PAGO_VAC'], 0, 10).'"/>';

                      $inpt .='<input  name="hddIniInterrup" id="hddIniInterrup" type="hidden" value="'.substr($row['fecha_ini_interrupcion'], 0, 10).'"/>';
                      $inpt .='<input  name="hddFinInterrup" id="hddFinInterrup" type="hidden" value="'.substr($row['fecha_fin_interrupcion'], 0, 10).'"/>';
                      $inpt .='<input  name="hddcondicion" id="hddcondicion" type="hidden" value="'.$row['condicion'].'"/>';
                      $inpt .='<input  name="hdddisposicion" id="hdddisposicion" type="hidden" value="'.$row['disposicion'].'"/>';
                      $inpt .='<input  name="hddmotivo" id="hddmotivo" type="hidden" value="'.$row['motivo_interrupcion'].'"/>';
                      $inpt .='<input  name="hddDiassPend" id="hddDiasPend" type="hidden" value="'.$row['dias_pendientes'].'"/>';
                    }                          
              } 
             
              $inpt .=' </tbody>

                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
