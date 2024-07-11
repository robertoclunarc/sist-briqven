<?php
 session_start();
 require_once('funciones_var.php');

  $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $nombre     = isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
  $tiporeporte= isset($_POST["tiporeporte"])?$_POST["tiporeporte"]:"NULL";

  if ($trabajador!='NULL') 
    $trabajador=" and a.cedula=".$trabajador;
  else
    $trabajador=" ";

  switch ($tiporeporte) {
      case 1:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/ 
          $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
          $qry="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, Horas_ST, Horas_Dlt, f.observaciones having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, Horas_ST, Horas_Dlt, f.observaciones having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
          break;
      case 2:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/  
          $nombre="Reporte de Horas extras y dias libres trabajados, resumido por trabajador"; 
          $qry="select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, b.GERENCIA,b.centro_costo, b.desc_ccosto having sum(Horas_ST)>0 UNION  select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto having sum(Horas_DLT)>0";
          break;   
  }        

 //print "<br>".$qry;

buscar($qry, $nombre,$tiporeporte);     
       
function buscar($b, $nombre,$tiporeporte) {
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
                       //<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">
              
              $inpt = $inpt.'<thead>
            <tr>
                <th colspan="16" style="text-align:center" >'.$nombre.'</th>
                                                               
            </tr>  
            <tr>
                ';
            if ($tiporeporte=='1'){
                $inpt.='<th width="5%" class="info" style="text-align:center">#</th>
                <th width="25%" class="info">Nombre del Trabajador</th>
                <th width="10%" class="info">Cédula</th>
                <th width="10%" class="info">Fecha</th>  ';
            }else{
                $inpt.='<th width="5%" class="info" style="text-align:center">#</th>
                <th width="35%" class="info">Nombre del Trabajador</th>
                <th width="10%" class="info">Cédula</th>';
            }
            $inpt.='<th width="5%" class="info">Horas ST</th>              
                <th width="5%" class="info">Hora DLT</th>
                <th width="20%" class="info">Motivo</th>              
                <th width="20%" class="info">Gerencia</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $total_ST=0;
              $total_DLT=0;
              //$limite=75;
              $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $total_ST  = $total_ST + $row['Horas_ST'];
                    $total_DLT = $total_DLT + $row['Horas_Dlt'];
                    $clase     = "label label-info";
                    if (isset($row['fecha'])){
                      $fecha     = substr($row['fecha'], 0,10);
                      $separador = '-';
                      $fecha     = formato_fecha($fecha,$separador);
                    }else{
                      $fecha='';
                    }
                     
                    if (isset($row['observaciones']))
                        $observaciones=$row['observaciones'];
                    else  
                        $observaciones='';
                    /*$porc=$row['entrada_real1'];
                    $pord=$row['salida_real1'];
                    $fecha = substr($row['fecha'], 0,10);
                    if ($porc=='' || $porc=='NULL' || is_null($porc))
                    {
                        if (!in_array($row['entrada_esperada1'], $libres))
                        {  
                          $clase="label label-danger";
                          $menor_a++;
                          $porc="--";
                          $pord="--";
                        }  
                    }    
                    else
                    { 
                      $mayor_a++;
                    }
*/
                    $inpt .='<tr>';                     

                    if ($tiporeporte=='1'){                     
                        $inpt .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                        $inpt .='<td width="25%">'.$row['nombres'].'</td>';
                        $inpt .='<td width="10%"><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';                        
                        $inpt .='<td width="10%">'.$fecha.'</td>';
                    }else{
                        $inpt .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                        $inpt .='<td width="35%">'.$row['nombres'].'</td>';
                        $inpt .='<td width="10%"><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';                      

                    }
                    $inpt .='<td width="5%" style="text-align:center">'.$row['Horas_ST'].'</td>';
                    $inpt .='<td width="5%" style="text-align:center">'.$row['Horas_Dlt'].'</td>';
                    $inpt .='<td width="20%">'.$observaciones.'</td>';
                    $inpt .='<td width="20%">'.$row['GERENCIA'].'</td>';

                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="4"><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.$total_ST.'</span></th>
                        <th><span class="label label-info">'.$total_DLT.'</span></th>
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
                $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    

    '; 
        }
echo $inpt;
//print_r($row);
}         
?>