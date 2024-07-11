<?php
 session_start();
 require_once('funciones_var.php');

  $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $nombre     = isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
  $tiporeporte= isset($_POST["tiporeporte"])?$_POST["tiporeporte"]:"NULL";
  $cboccosto  = isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";
  //print $nombre;

  if ($trabajador!='NULL') 
    $trabajador=" and a.cedula=".$trabajador;
  else
    $trabajador=" ";

  if ($cboccosto!='NULL') 
    $cboccosto=" and b.GERENCIA='".$cboccosto."'";
  else
    $cboccosto=" ";
  

  switch ($tiporeporte) {
      case 1:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/ 
          $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
          $qry="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
          break;
      case 2:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/  
          $nombre="Reporte de Horas extras y dias libres trabajados, resumido por trabajador"; 
          $qry="select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto."  group by a.cedula, b.nombres, b.GERENCIA,b.centro_costo, b.desc_ccosto having sum(Horas_ST)>0 UNION  select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto having sum(Horas_DLT)>0";
          break;   
  }        
// print "<br>query:".$qry;

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
                <th width="10%" class="info" style="text-align:center">Cédula</th>;
                <th width="10%" class="info">Fecha</th>  ';
            }else{
                $inpt.='<th width="5%" class="info" style="text-align:center">#</th>
                <th width="35%" class="info">Nombre del Trabajador</th>
                <th width="10%" class="info" style="text-align:center">Cédula</th>';
            }
            $inpt.='<th width="5%" class="info">Horas ST</th>              
                <th width="5%" class="info">Hora DLT</th>';
            if ($tiporeporte=='1'){ 
                $inpt.='<th width="20%" class="info">Motivo</th> ';
            }
            $inpt.='<th width="20%" class="info">Gerencia</th>
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

                    $inpt .='<tr>';               

                    if (is_float($row['Horas_ST']) || (strrpos($row['Horas_ST'], '.')) || (strrpos($row['Horas_ST'], ','))){
                        $Horas_ST = number_format($row['Horas_ST'],2,',','.');
                    }else{
                        $Horas_ST = $row['Horas_ST'];
                    } 

                    if (is_float($row['Horas_Dlt']) || (strrpos($row['Horas_Dlt'], '.')) || (strrpos($row['Horas_Dlt'], ','))){
                        $Horas_Dlt = number_format($row['Horas_Dlt'],2,',','.');
                    }else{
                        $Horas_Dlt = $row['Horas_Dlt'];
                    } 

                    if ($tiporeporte=='1'){                     
                        $inpt .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                        $inpt .='<td width="25%">'.$row['nombres'].'</td>';
                        //$inpt .='<td width="10%"><button type="button"  data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].',\''.$row['fecha'].'\')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';    
                        $inpt .='<td width="10%" style="text-align:right">'.$row['cedula'].'</td>';                        
                        $inpt .='<td width="10%">'.$fecha.'</td>';
                    }else{
                        $inpt .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                        $inpt .='<td width="35%">'.$row['nombres'].'</td>';
                        //$inpt .='<td width="10%"><button type="button"  data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';   
                        $inpt .='<td width="10%" style="text-align:right">'.$row['cedula'].'</td>';                    

                    }
                    $inpt .='<td width="5%" style="text-align:center">'.$Horas_ST.'</td>';
                    $inpt .='<td width="5%" style="text-align:center">'.$Horas_Dlt.'</td>';
                    if ($tiporeporte=='1'){ 
                         $inpt .='<td width="20%">'.$observaciones.'</td>';
                    }  
                    $inpt .='<td width="20%">'.$row['GERENCIA'].'</td>';

                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="6"><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.number_format($total_ST,2,',','.').'</span></th>
                        <th><span class="label label-info">'.number_format($total_DLT,2,',','.').'</span></th>
                        <th><span class="label label-info"></span></th>
                        <th><span class="label label-danger"></span></th>
                        <th><span class="label label-danger"></span></th>
                    </tr>
                    ';
               if ($tiporeporte=='1'){ 
              $inpt .='<tr>
                        <td colspan="18" align="center"> 
                        <INPUT id="cmdGuardar" type="button" value="Planilla HTML" class="btn btn-success" onclick="Imprimirplanilla2();"/>
                        <INPUT id="cmdGuardar" type="button" value="Planilla PDF" class="btn btn-success" onclick="Imprimirplanilla();"/></td>
                        <input type="hidden" id="query" name="query" value="'.$b.'"></input>
                    </tr>
                    ';
              }
              $inpt .='
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