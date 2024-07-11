<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

/*$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
if ($turno!='')
  $qry.=" where turno ".$turno."9";
if ($trabajador!='NULL')
  if ($turno!='')
    $qry.=" and cedula=".$trabajador."";
  else
    $qry.=" WHERE cedula=".$trabajador."";
$qry.=" order by 11 desc,1";
*/
$qry="select a.TRABAJADOR, b.NOMBRES, a.CICLO_LABORAL, a.FECHA_INI_PER_VAC, a.FECHA_FIN_PER_VAC, a.FECHA_PAGO_VAC, a.FECHA_ALTA, a.SITUACION_PROGRAMA from dbo.adam_programacion_vacaciones a, dbo.ADAM_DATOS_PERSONALES b";
$qry.=" where CAST(a.TRABAJADOR AS int)=b.Trabajador"; 
$qry.=" and ('".$finicio."' BETWEEN FECHA_INI_PER_VAC and FECHA_FIN_PER_VAC or '".$ffin."' BETWEEN FECHA_INI_PER_VAC and FECHA_FIN_PER_VAC)";
if ($trabajador!='NULL')
    $qry.=" and a.trabajador=".$trabajador."";
$qry.=" order by 4 desc";

buscar($qry);     
       
function buscar($b) {
       include("../BD/conexion.php");
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
            <tr>
                <th width="5%" class="info">No.</th>             
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="5%"class="info">Ciclo Laboral</th>        
                <th width="10%"class="info">Fecha Inicio</th>
                <th width="10%" class="info">Fecha Fin</th>
                <th width="10%" class="info">Fecha Pago</th>
                <th width="10%" class="info">Fecha alta</th>
                <th width="10%" class="info">Estatus</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;
              $limite=75;
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
           
                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';                    
                    $inpt .='<td>'.$row['TRABAJADOR'].'</td>';                    
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.$row['CICLO_LABORAL'].'</td>';
                    $inpt .='<td>'.substr($row['FECHA_INI_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_FIN_PER_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['FECHA_PAGO_VAC'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['FECHA_ALTA'].'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$situac.'</span></td>';
                                      
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
<!--              <tfoot>
                    <tr>
                        <th><span class="label label-success">Cant. Reg. Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th><span class="label label-info">Cant. Mayor a '.$limite.'%</span></th>
                        <th><span class="label label-info">'.$mayor_a.'</span></th>
                        <th><span class="label label-danger">Resto:</span></th>
                        <th><span class="label label-danger">'.$menor_a.'</span></th>
                        <th></th>
                        <th></th>
                        <th></th> 
                        <th></th> 
                        <th></th>                                                
                    </tr>
                </tfoot>-->
                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
