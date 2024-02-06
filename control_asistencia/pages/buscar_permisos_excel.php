<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
//$nombre= isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
$tipo= isset($_POST["cbocodigo"])?$_POST["cbocodigo"]:"NULL";

$qry="select a.NumPermiso, a.cedula, b.nombres, a.PeriodoPago, a.Cod_Adam, c.descripcion_ausencia, a.CentroCosto, b.desc_ccosto, a.Inicio, a.HoraIni, a.Fin, a.Horafin, a.Neto, a.fecha_proc, a.Observaciones, a.cargado, a.estado
from SW_Permisos a, adam_datos_personales b, adam_codigo_ausencias c
where a.cedula = b.trabajador and a.cod_adam = c.codigo_hora
and a.estado != 'B' and a.Inicio >='".$finicio."' and  a.Fin <= '".$ffin."'";

if ($trabajador!="NULL")
  $qry.=" and a.cedula=".$trabajador;

if ($tipo!="NULL")
  $qry.=" and a.Cod_Adam=".$tipo;

$qry.=" order by a.numpermiso";

buscar($qry);     
       
function buscar($b){
  //echo $b;
       include("../BD/conexion.php");
        $cn=Conectarse_sitt();
        require_once('funciones_var.php');
        $link=Conex_Contancia_pgsql();
        $stmt11 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt11->columnCount(); 
       //$contar=1;
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
             
            <tr>
                <th width="5%" class="info">Nro</th>
                <th width="5%" class="info">Cedula</th>              
                <th width="5%" class="info">Nombre</th>                        
                <th width="5%" class="info">Descipcion Ausencia</th>
                <th width="5%" class="info">Ccosto</th>              
                <th width="5%" class="info">Desc. CCosto</th>
                <th width="5%" class="info">F.Inicio</th>              
                <th width="5%" class="info">H.Inicio</th>
                <th width="5%" class="info">F.Fin</th>
                <th width="5%" class="info">H.Fin</th>
                <th width="5%" class="info">Neto</th>
                <th width="5%" class="info">F.Proceso</th> 
                <th width="5%" class="info">Observacion</th> 
                <th width="5%" class="info">Usuario</th> 
                <th width="5%" class="info">Estado</th>                                  
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              
             while($row = $stmt11->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
              {     
                    $contar++;
                    $clase="label label-info";                    
                    
                    $fechaIni = substr($row['Inicio'], 0,10);
                    $fechaFin = substr($row['Fin'], 0,10);
                    
                        if ($row['estado']!='L')
                        {  
                          $clase="label label-danger";                          
                        } 

                    $inpt .='<tr>';                     
                    
                    $cargado=login_usuario($link,$row['cargado']);
                    
                    $inpt .='<td>'.$row['NumPermiso'].'</td>';
                    $inpt .='<td>'.$row['cedula'].'</td>';
                    $inpt .='<td>'.$row['nombres'].'</td>';                    
                    $inpt .='<td>'.$row['descripcion_ausencia'].'</td>';
                    $inpt .='<td>'.$row['CentroCosto'].'</td>';
                    $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                    $inpt .='<td>'.$fechaIni.'</td>';
                    $inpt .='<td>'.$row['HoraIni'].'</td>';
                    $inpt .='<td>'.$fechaFin.'</td>';
                    $inpt .='<td>'.$row['Horafin'].'</td>';
                    $inpt .='<td>'.$row['Neto'].'</td>';
                    $inpt .='<td>'.$row['fecha_proc'].'</td>';
                    $inpt .='<td>'.$row['Observaciones'].'</td>';
                    $inpt .='<td>'.$cargado.'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$row['estado'].'</span></td>';
                    
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
                        <th></th> 
                        <th></th> 
                        <th></th>                                                
                    </tr>
                </tfoot>
                </table>';
        }
pg_close($link);        
echo $inpt;
//print_r($row);
}         
?>