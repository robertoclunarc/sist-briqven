<?php
 session_start();
 require_once('funciones_var.php');

  $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $nombre     = isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
  //$tiporeporte= isset($_POST["tiporeporte"])?$_POST["tiporeporte"]:"NULL";
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
  


          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/ 
          $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
          $qry="select a.cedula, b.nombres, a.fecha, a.Entrada_Real1, a.Salida_Real1, a.Entrada_Real2, a.Salida_Real2, a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2, a.Inicio_ST1, a.Fin_St1, a.Inicio_DLT1, a.Fin_DLT1,  Horas_ST, Horas_Dlt, Horas_ST + Horas_Dlt as total, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, f.observaciones, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, a.Entrada_Real1, a.Salida_Real1, a.Entrada_Real2, a.Salida_Real2, a.Inicio_ST1, a.Fin_St1, a.Inicio_DLT1, a.Fin_DLT1, a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, a.Entrada_Real1, a.Salida_Real1, a.Entrada_Real2, a.Salida_Real2, a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2, a.Inicio_ST1, a.Fin_St1, a.Inicio_DLT1, a.Fin_DLT1, Horas_ST, Horas_Dlt, Horas_ST + Horas_Dlt as total, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, f.observaciones, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, a.Entrada_Real1, a.Salida_Real1, a.Entrada_Real2, a.Salida_Real2, a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2, a.Inicio_ST1, a.Fin_St1, a.Inicio_DLT1, a.Fin_DLT1, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
 
     
 //print "<br>query:".$qry;

buscar($qry, $nombre);     
       
function buscar($b, $nombre) {
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
                $inpt.='<th class="info" style="text-align:center">#</th>
                <th class="info">Nombre del Trabajador</th>
                <th class="info">Cédula</th>
                <th class="info">Fecha</th>

                <th class="info">Entrada Esperada</th>
                <th class="info">Salida Esperada</th>

                <th class="info">Entrada Real</th>
                <th class="info">Salida Real</th>

                <th class="info">Inicio ST</th>
                <th class="info">Fin ST</th>

                <th class="info">Horas ST<br> diurnas</th>              
                <th class="info">Horas ST<br> nocturnas</th>
                <th class="info">Horas ST</th>
                
                <th class="info">Inicio DLT</th>
                <th class="info">Fin DLT</th>

                <th class="info">Hora DLT<br>diurnas</th>
                <th class="info">Hora DLT<br>nocturnas</th>
                <th class="info">Hora DLT</th> 

                <th class="info">Gerencia</th>
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

                      $horas_st_diurnas   = '';
                      $horas_st_nocturnas = '';
                      $Inicio_ST          = '';
                      $Fin_ST             = '';
                      $Inicio_DLT         = '';
                      $Fin_DLT            = '';                      
                      $horas_stdlt        = $row['Horas_ST'];

                    if ($row['Horas_ST']>0){ 
                      $Inicio_ST = $row['Inicio_ST1'];
                      $Fin_ST    = $row['Fin_St1'];
                      
                      if ($Inicio_ST >= '05:00' and $Inicio_ST <= '19:00') {
                        if ($Fin_ST  >= '05:00' and $Fin_ST    <='19:00'){
                            $horas_st_diurnas   = $horas_stdlt; //$fechaAuxiliar  = strtotime ( "2 seconds" , strtotime ( $fechaEntrada ) ) ; 
                            //$horas_st_diurnas   =  date ( 'H:i' ,strtotime ( $horas_stdlt )) ;
                            $horas_st_nocturnas = '';
                        }else{
                            //$horas_st_diurnas   =  calcular_horas_extras2($row['fecha'],$Inicio_ST,'19:00');
                            $horas_st_diurnas   =  calcular_horas_extras($Inicio_ST,'19:00');
                            $horas_st_nocturnas = $horas_stdlt - $horas_st_diurnas;
                        }
                      }elseif ($Inicio_ST >= '19:00' and $Inicio_ST <= '23:59') {
                        if ($Fin_ST>='19:00' and $Fin_ST<='23:59'){
                            $horas_st_diurnas   = '';
                            $horas_st_nocturnas = $horas_stdlt;
                        }else{
                          if ($Fin_ST>='23:59' and $Fin_ST<='05:00'){
                              $horas_st_nocturnas1 =  calcular_horas_extras($Inicio_ST,'23:59');
                              $horas_st_nocturnas2 =  calcular_horas_extras('00:01',$Fin_ST);


                              $mifecha = new DateTime($horas_st_nocturnas2); 
                              $mifecha->modify('+'.$horas_st_nocturnas1.' minute');
                              $mifecha->modify('+2 minute');
                              $horas_st_nocturnas = $horas_st_nocturnas1 + $horas_st_nocturnas2;
                              $horas_st_nocturnas = $mifecha->format('g,i');
                              $ciclo=explode(",", $horas_st_nocturnas);
                               
                              if ($ciclo[1]){
                                if ($ciclo[1]!='00'){
                                  $ciclo2='.'.round(($ciclo[1]*60)/100, 0);
                                }else{
                                  $ciclo2='';
                                }
                              }
                              $ciclo3=$ciclo[0];
                                 
                              $horas_st_nocturnas = $ciclo3.$ciclo2;
                                //$horas_st_nocturnas =  calcular_horas_extras2($row['fecha'],$Inicio_ST,'23:59');
                              $horas_st_diurnas   = $horas_stdlt - $horas_st_nocturnas;
                            }else{
                              if ($Fin_ST>='05:01' and $Fin_ST<='19:00'){
                                  $horas_st_nocturnas1 =  calcular_horas_extras($Inicio_ST,'23:59');
                                  $horas_st_nocturnas2 =  calcular_horas_extras('00:01','05:00');

                                  $mifecha = new DateTime($horas_st_nocturnas2); 
                                  $mifecha->modify('+'.$horas_st_nocturnas1.' minute');
                                  $mifecha->modify('+2 minute');
                                  $horas_st_nocturnas = $horas_st_nocturnas1 + $horas_st_nocturnas2;
                                  $horas_st_nocturnas = $mifecha->format('g,i');
                                  $ciclo=explode(",", $horas_st_nocturnas);
                                   
                                  if ($ciclo[1]){
                                    if ($ciclo[1]!='00'){
                                      $ciclo2='.'.round(($ciclo[1]*60)/100, 0);
                                    }else{
                                      $ciclo2='';
                                    }
                                  }
                                  $ciclo3=$ciclo[0];
                                     
                                  $horas_st_nocturnas = $ciclo3.$ciclo2;
                                    //$horas_st_nocturnas =  calcular_horas_extras2($row['fecha'],$Inicio_ST,'23:59');
                                  $horas_st_diurnas   = $horas_stdlt - $horas_st_nocturnas;
                                }

                            }
                        }
                      }if ($Inicio_ST >= '00:00' and $Inicio_ST <= '04:59') {
                        if ($Fin_ST >= '00:00' and $Fin_ST <= '04:59'){
                            $horas_st_diurnas   = $horas_stdlt; 
                            $horas_st_nocturnas = '';
                        }else{
                            $horas_st_diurnas   =  calcular_horas_extras2($row['fecha'],$Inicio_ST,'19:00');
                            $horas_st_nocturnas = $horas_stdlt - $horas_st_diurnas;
                        }
                      }
                    }

                    $horas_dlt_diurnas   = '';
                    $horas_dlt_nocturnas = '';
                    $Inicio_DLT         = '';
                    $Fin_DLT            = '';                      
                    $horas_stdlt= $row['Horas_Dlt'];

                    if ($row['Horas_Dlt']>0){ 
                      $Inicio_DLT = $row['Inicio_DLT1'];
                      $Fin_DLT    = $row['Fin_DLT1'];
                      
                      if ($Inicio_DLT >= '05:00' and $Inicio_DLT <= '19:00') {
                        if ($Fin_DLT  >= '05:00' and $Fin_DLT    <='19:00'){
                            $horas_dlt_diurnas   = $horas_stdlt; 
                            $horas_dlt_nocturnas = '';
                        }else{
                            $horas_dlt_diurnas   =  calcular_horas_extras($Inicio_DLT,'19:00');
                            $horas_dlt_nocturnas = $horas_stdlt - $horas_dlt_diurnas;
                        }
                      }elseif ($Inicio_DLT >= '19:00' and $Inicio_DLT <= '23:59') {
                        if ($Fin_DLT>='19:00' and $Fin_DLT<='23:59'){
                            $horas_dlt_diurnas   = '';
                            $horas_dlt_nocturnas = $horas_stdlt;
                        }else{
                          if ($Fin_DLT>='23:59' and $Fin_DLT<='05:00'){
                              $horas_dlt_nocturnas1 =  calcular_horas_extras($Inicio_DLT,'23:59');
                              $horas_dlt_nocturnas2 =  calcular_horas_extras('00:01',$Fin_DLT);

                              $mifecha = new DateTime($horas_dlt_nocturnas2); 
                              $mifecha->modify('+'.$horas_st_nocturnas1.' minute');
                              $mifecha->modify('+2 minute');
                              $horas_dlt_nocturnas = $horas_dlt_nocturnas1 + $horas_dlt_nocturnas2;
                              $horas_dlt_nocturnas = $mifecha->format('g,i');
                              $ciclo=explode(",", $horas_dlt_nocturnas);
                               
                              if ($ciclo[1]){
                                if ($ciclo[1]!='00'){
                                  $ciclo2='.'.round(($ciclo[1]*60)/100, 0);
                                }else{
                                  $ciclo2='';
                                }
                              }
                              $ciclo3=$ciclo[0];
                                 
                              $horas_dlt_nocturnas = $ciclo3.$ciclo2;
                              $horas_dlt_diurnas   = $horas_stdlt - $horas_dlt_nocturnas;
                            }else{
                              if ($Fin_DLT>='05:01' and $Fin_DLT<='19:00'){
                                                              print "PASO2";
                                  $horas_st_nocturnas1 =  calcular_horas_extras($Inicio_DLT,'23:59');
                                  $horas_st_nocturnas2 =  calcular_horas_extras('00:01','05:00');

                                  $mifecha = new DateTime($horas_st_nocturnas2); 
                                  $mifecha->modify('+'.$horas_st_nocturnas1.' minute');
                                  $mifecha->modify('+2 minute');
                                  $horas_dlt_nocturnas = $horas_st_nocturnas1 + $horas_st_nocturnas2;
                                  $horas_dlt_nocturnas = $mifecha->format('g,i');
                                  $ciclo=explode(",", $horas_dlt_nocturnas);
                                   
                                  if ($ciclo[1]){
                                    if ($ciclo[1]!='00'){
                                      $ciclo2='.'.round(($ciclo[1]*60)/100, 0);
                                    }else{
                                      $ciclo2='';
                                    }
                                  }
                                  $ciclo3=$ciclo[0];
                                     
                                  $horas_dlt_nocturnas = $ciclo3.$ciclo2;
                                  $horas_dlt_diurnas   = $horas_stdlt - $horas_dlt_nocturnas;
                                }

                            }
                        }
                      }if ($Inicio_DLT >= '00:00' and $Inicio_DLT <= '04:59') {
                        if ($Fin_DLT >= '00:00' and $Fin_DLT <= '04:59'){
                            $horas_dlt_diurnas   = $horas_stdlt; 
                            $horas_dlt_nocturnas = '';
                        }else{
                            $horas_dlt_diurnas   =  calcular_horas_extras2($row['fecha'],$Inicio_DLT,'19:00');
                            $horas_dlt_nocturnas = $horas_stdlt - $horas_dlt_diurnas;
                        }
                      }
                    }

                    if ($row['Salida_Esperada2']!='00:00' && $row['Salida_Esperada2']!=NULL)
                        $salida_esperada=$row['Salida_Esperada2'];
                    else
                      $salida_esperada=$row['Salida_Esperada1'];

                    $inpt .='<tr>';                     
                    $inpt .='<td style="text-align:center">'.$contar.'</td>'; 
                    $inpt .='<td>'.$row['nombres'].'</td>';
                    $inpt .='<td><button type="button"  data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].',\''.$row['fecha'].'\')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';                        
                    $inpt .='<td>'.$fecha.'</td>';

                    $inpt .='<td style="text-align:center">'.$row['Entrada_Esperada1'].'</td>';
                    $inpt .='<td style="text-align:center">'.$salida_esperada.'</td>';

                    $inpt .='<td style="text-align:center">'.$row['Entrada_Real1'].'</td>';
                    $inpt .='<td style="text-align:center">'.$row['Salida_Real1'].'</td>';

                    $inpt .='<td>'.$Inicio_ST.'</td>';
                    $inpt .='<td>'.$Fin_ST.'</td>';  

                    $inpt .='<td style="text-align:center">'.$horas_st_diurnas.'</td>';                  
                    $inpt .='<td style="text-align:center">'.$horas_st_nocturnas.'</td>';
                    $inpt .='<td style="text-align:center">'.$row['Horas_ST'].'</td>';

                    $inpt .='<td>'.$Inicio_DLT.'</td>';
                    $inpt .='<td>'.$Fin_DLT.'</td>';

                    $inpt .='<td style="text-align:center">'.$horas_dlt_diurnas.'</td>';                  
                    $inpt .='<td style="text-align:center">'.$horas_dlt_nocturnas.'</td>';
                    $inpt .='<td style="text-align:center">'.$row['Horas_Dlt'].'</td>';

                    $inpt .='<td style="text-align:center">'.$row['GERENCIA'].'</td>';
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="14"><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.$total_ST.'</span></th>
                        <th><span class="label label-danger"></span></th>
                        <th><span class="label label-info">'.$total_DLT.'</span></th>
                        <th><span class="label label-info"></span></th>
                    </tr>
                    ';

              $inpt .='<tr>
                        <td colspan="18" align="center"> 
                        <!-- <INPUT id="cmdGuardar" type="button" value="Planilla HTML" class="btn btn-success" onclick="Imprimirplanilla2();"/>
                        <INPUT id="cmdGuardar" type="button" value="Planilla PDF" class="btn btn-success" onclick="Imprimirplanilla();"/></td> -->
                        <input type="hidden" id="query" name="query" value="'.$b.'"></input>
                    </tr>
                    ';
              
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