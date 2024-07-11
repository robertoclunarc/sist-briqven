<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$ccosto= isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";
$tipo= isset($_POST["cbocodigo"])?$_POST["cbocodigo"]:"NULL";

$qry="select nro_permiso,
          trabajador,
          nombre,inicio_ausencia, 
final_ausencia,
razon_ausencia,
observaciones, 
cantidad,
hora_inicio_ausencia,
hora_final_ausencia,
descripcion_ausencia,
ccosto,
desc_ccosto 
from sw_con_apermisos_mat 
where inicio_ausencia >='".$finicio."' and  final_ausencia <= '".$ffin."' ";

if ($trabajador!="NULL")
  $qry.=" and trabajador=".$trabajador;

if ($tipo!="NULL")
  $qry.=" and razon_ausencia=".$tipo;

if ($ccosto!="NULL")
  $qry.=" and ccosto='".$ccosto."'";

$qry.=" order by nro_permiso";

buscar($qry);     
       
function buscar($b){
  //echo $b;
       include("../BD/conexion.php");
       require_once('funciones_var.php');
       $cn1=Conectarse_sitt();
       $link=Conex_Contancia_pgsql();
       $link2=Conex_rrhh_pgsql(); 
       $stmt12 = $cn1->query($b);
        //$stmt12 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt12->execute();
        $contar = $stmt12->columnCount(); 
       //$contar=1;
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
             
            <tr>
                
                <th width="3%" class="info">Nro.</th>
                <th width="3%" class="info">Cedula</th>              
                <th width="7%" class="info">Nombre</th>
                <th width="3%" class="info">Ccosto</th>              
                <th width="7%" class="info">Desc. CCosto</th>                                
                <th width="3%" class="info">F.Inicio</th>              
                <th width="3%" class="info">H.Inicio</th>
                <th width="3%" class="info">F.Fin</th>
                <th width="3%" class="info">H.Fin</th>
                <th width="7%" class="info">Descipcion Ausencia</th>
                <th width="2%" class="info">Neto</th>                 
                <th width="7%" class="info">Observacion</th> 
                                               
            </tr>
        </thead>
        <tbody>';
              $contar=0;  
              $clase="";
             while($row = $stmt12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
              {     
                    $contar++;                   
                    
                    $fechaIni = substr($row['inicio_ausencia'], 0,10);
                    $fechaFin = substr($row['final_ausencia'], 0,10);
                    
                    $inpt .='<tr>';  
                    
                    $inpt .='<td>'.$row['nro_permiso'].'</td>';
                    $inpt .='<td>'.$row['trabajador'].'</td>';
                    $inpt .='<td>'.$row['nombre'].'</td>';                    
                    $inpt .='<td>'.$row['ccosto'].'</td>';
                    $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                    $inpt .='<td>'.$fechaIni.'</td>';
                    $inpt .='<td>'.$row['hora_inicio_ausencia'].'</td>';
                    $inpt .='<td>'.$fechaFin.'</td>';
                    $inpt .='<td>'.$row['hora_final_ausencia'].'</td>';
                    $inpt .='<td>'.$row['descripcion_ausencia'].'</td>';
                    $inpt .='<td>'.$row['cantidad'].'</td>';                    
                    $inpt .='<td>'.$row['observaciones'].'</td>';                    
                    
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th><span class="label label-info"></span></th>
                        <th><span class="label label-info"></span></th>
                        <th><span class="label label-danger"></span></th>
                        <th><span class="label label-danger"></span></th>
                        <th></th>
                        <th></th>
                        <th></th> 
                        <th></th> 
                        <th></th> 
                        <th></th>
                    </tr>
                </tfoot>
                </table>';
        }
pg_close($link);
//echo $b;       
echo $inpt;
//print_r($row);
}         
?>