<?php
 session_start();

$anio= isset($_POST["cboanio"])?$_POST["cboanio"]:"NULL";         //

$qry="select a.ID_CALENDARIO, a.TIPO_NOMINA, a.ANIO, a.PERIODO,
min(b.FECHA_INICIO) as inicio, max(b.FECHA_TERMINO) as fin,
a.FECHA_PAGO,a.MES_ACUMULAR, b.ANIO_ACUMULAR
from dbo.ADAM_CALENDARIO_NOMINA a
join dbo.ADAM_CALENDARIO_NOMINA b on a.mes_acumular=b.mes_acumular and a.anio_acumular=b.anio_acumular and b.tipo_nomina='MS'
where a.tipo_nomina='LM' and a.ANIO=".$anio."
group by  a.ID_CALENDARIO, a.TIPO_NOMINA, a.ANIO, a.PERIODO, a.FECHA_PAGO,a.MES_ACUMULAR, b.ANIO_ACUMULAR
order by 5 desc";

buscar($qry);     
       
function buscar($b){
  //echo $b;
       include("../BD/conexion.php");
       require_once('funciones_var.php');
       $cn1=Conectarse_sitt();
       $link=Conex_Contancia_pgsql();
        
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
                <th width="3%" class="info">Oper.</th>
                <th width="3%" class="info">Per.</th>
                <th width="3%" class="info">Id Cal.</th>
                <th width="3%" class="info">Tipo Nom.</th>              
                <th width="5%" class="info">A&ntildeo</th>
                <th width="5%" class="info">Inicio</th>              
                <th width="5%" class="info">Fin</th>
                <th width="5%" class="info">F. Pago</th>
                <th width="3%" class="info">Mes Acum.</th>              
                <th width="3%" class="info">A&ntildeo Acum.</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $pcerrar=permiso_usuario($link, 'CERRAR', 'periodos_nom.php', $_SESSION['user_session_const']); 

             $link2=Conex_rrhh_pgsql();              
              
             while($row = $stmt12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
              {     
                    $contar++;
                    $clase="label label-info";                    
                    
                    $fechaIni = substr($row['inicio'], 0,10);
                    $fechaFin = substr($row['fin'], 0,10);
                    $fechaPag = substr($row['FECHA_PAGO'], 0,10);

                    $idcal=$row['ID_CALENDARIO'];

                    $abierto= periodo_abierto($link2, $idcal);

                    $inpt .='<tr>';                    
                    $inpt .='<td>';
                    if ($pcerrar && $abierto== 't'){                      
                      
                      $inpt .='<div id="boton_'.$idcal.'">
                                <button type="button" title="Cerrar"
                                  onclick="cerrar_periodo('.$idcal.')" 
                                  data-toggle="modal" 
                                  data-target="#exampleModalCenter" 
                                  class="btn btn-primary btn-circle">
                                  <i class="fa fa-list"></i>
                                </button>
                              </div>';
                    }
                    $inpt .='</td>';
                    $inpt .='<td>'.$row['PERIODO'].'</td>';
                    $inpt .='<td>'.$idcal.'</td>';
                    $inpt .='<td>'.$row['TIPO_NOMINA'].'</td>';
                    $inpt .='<td>'.$row['ANIO'].'</td>';                                        
                    $inpt .='<td>'.$fechaIni.'</td>';                    
                    $inpt .='<td>'.$fechaFin.'</td>';
                    $inpt .='<td>'.$fechaPag.'</td>';
                    $inpt .='<td>'.$row['MES_ACUMULAR'].'</td>';
                    $inpt .='<td>'.$row['ANIO_ACUMULAR'].'</td>';                    
                    
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              
                </table>';
        }
pg_close($link);
pg_close($link2);       
echo $inpt;
//print_r($row);
}         
?>