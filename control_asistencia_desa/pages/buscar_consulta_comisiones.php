<?php
session_start();
if (isset($_SESSION['user_session_const'])){
  $finicio= isset($_GET["finicio"])?$_GET["finicio"]:$_POST["txtfinicio"];         //
  $ffin= isset($_GeT["ffin"])?$_GET["ffin"]:$_POST["txtffin"];
  $trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $nro= isset($_POST["hddnro"])?$_POST["hddnro"]:"NULL";

  $qry="SELECT cs.Numero_comision, cs.cedula, adp.NOMBRES, cs.inicio_comision, cs.fin_comision,
  cs.CentroCosto, cs.Desc_cargo,  cs.Cuadrilla, cs.Estado, cs.cod_comision, tcs.Desc_comision, cs.Observaciones, cs.autorizante, cs.f_proceso, adpx.siglado
  FROM SW_comisiones_de_servicio cs 
  INNER JOIN SW_tipos_comisiones_servicio tcs ON cs.cod_comision=tcs.cod_comision
  LEFT JOIN ADAM_DATOS_PERSONALES adp ON cs.cedula=adp.Trabajador 
  LEFT JOIN ADAM_DATOS_PERSONALES adpx ON cs.autorizante=adpx.Trabajador WHERE 1=1
  ";  

  if (!isset($_GET["finicio"])){
      $qry.="AND  (cs.inicio_comision BETWEEN '".$finicio."' AND '".$ffin."'
    OR ( cs.inicio_comision < '".$finicio."' AND cs.fin_comision > '".$ffin."' )
    OR cs.fin_comision BETWEEN '".$finicio."' AND '".$ffin."') ";
  }
  if ($trabajador!='NULL')
      $qry.=" AND cs.cedula=".$trabajador." ";

  $qry.="ORDER BY cs.Numero_comision DESC";

  buscar($qry, $nro);
}else{
  echo "Debe Iniciar Sesion para Consultar";
}       
       
function buscar($b, $nro) {
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();        
        $stmt1 = $cn->query($b);        
        $contar = $stmt1->columnCount(); 
             
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="4%" class="info">No.</th>             
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="8%"class="info">Inicio</th>        
                <th width="8%"class="info">Fin</th>
                <th width="7%" class="info">CCosto</th>
                <th width="10%" class="info">Cargo</th>
                <th width="4%" class="info">SH</th>
                <th width="3%" class="info">Estado</th>
                <th width="10%" class="info">Desc. Comision</th>
                <th width="10%" class="info">Observacion</th>
                <th width="9%" class="info">Autorizante</th>
                <th width="10%" class="info">F.Proceso</th>
            </tr>
        </thead>
        <tbody>';
             $contar=0; 
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    $contar++;
                    $inpt .='<tr>';
                    if ($nro=="NULL")
                      {$inpt .='<td>'.$row['Numero_comision'].'</td>';}
                    else{
                      $param=$nro.", ";
                      $param.=$row['cedula'].", ";
                      $param.="'".substr($row['inicio_comision'], 0, 10)."', ";
                      $param.="'".substr($row['fin_comision'], 0, 10)."', ";
                      $param.="'".substr($row['inicio_comision'], 0, 10)."', ";
                      $param.="'".$row['Observaciones']."', ";
                      $param.=$row['cod_comision'];
                      $inpt .='<td><button title="Presione para actualizar" type="button" class="btn btn-primary"  onclick="ver_comision('.$param.')">'.$row['Numero_comision'].'</button></td>';
                    }
                    $inpt .='<td>'.$row['cedula'].'</td>';
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.substr($row['inicio_comision'], 0, 10).'</td>';
                    $inpt .='<td>'.substr($row['fin_comision'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['CentroCosto'].'</td>';
                    $inpt .='<td>'.$row['Desc_cargo'].'</td>';
                    $inpt .='<td>'.$row['Cuadrilla'].'</td>';
                    $inpt .='<td>'.$row['Estado'].'</td>';
                    $inpt .='<td>'.$row['Desc_comision'].'</td>';
                    $inpt .='<td>'.$row['Observaciones'].'</td>';
                    $inpt .='<td>'.$row['siglado'].'</td>';
                    $inpt .='<td>'.$row['f_proceso'].'</td>';
                                      
                    $inpt .='</tr>';                        
              } 
             
             /* $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="13" ><span class="label label-success">Cant. Reg. Total: '.$contar.'</span></th>
                        
                    </tr>
                </tfoot>
                </table>';
*/
                $inpt .=' </tbody></table>';
                

        }
echo $inpt;
//print_r($row);
}         
?>
