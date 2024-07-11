<?php
include("../BD/conexion.php");
require_once('funciones_var.php'); 
session_start();
error_reporting(E_ERROR | E_PARSE);
$trabajador = isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
$fecha      = isset($_GET["fecha"])?$_GET["fecha"]:"NULL";
$btn        = isset($_GET["btn"])?$_GET["btn"]:"NULL";
/*
$link  = Conex_Contancia_pgsql();
$quien_hizo_registro= responsable_carga_registro_stdlt($link,$trabajador,$fecha,'ST');
print "quien_hizo_registro:".$quien_hizo_registro;
*/

$qry_sitt="select a.cedula, b.nombres, a.fecha, a.entrada_real1, a.salida_real1,  a.entrada_real2, a.salida_real2,
a.entrada_esperada1, a.salida_esperada1, a.Entrada_Esperada2 as ent_esp2, a.Salida_Esperada2 as sal_esp2, a.CodError as codigo_error, a.cod_ausencia, a.horasnetapresencia, a.horasnetaausencia, b.sistema_horario, b.DESC_PUESTO as descripcion_puesto, b.centro_costo, b.desc_ccosto, a.PagoComida,  a.Inicio_ST1 as st1_inicio,  a.Fin_St1 as st1_fin, a.Horas_ST as st_hora, a.Inicio_ausencia as ausencia_inicio,  a.Fin_ausencia as ausencia_fin, a.Autorizado1, a.Inicio_DLT1 as dlt1_inicio, a.Fin_DLT1 as dlt1_fin, a.Horas_Dlt as dlt_hora, a.Sustitucion, a.Cedula_Sustituido, c.nombres as sustituido, c.DESC_PUESTO as puestosustituido, a.Causal_Sustitucion,  d.desc_causa, a.autorizado1, a.autorizado2, e.siglado, Fecha_autor1, Fecha_autor2, f.Observaciones as motivo, a.ced_dlt, a.ced_st
from sw_hoja_de_tiempo_real a
 INNER JOIN adam_datos_personales b on a.cedula = b.trabajador
 left join adam_datos_personales c on a.Cedula_Sustituido = c.trabajador 
 left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion 
 left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha 
 left join (select trabajador, nombres, siglado from adam_datos_personales
union select -1, 'Sist. Tiempo Trabajado', 'SITT') e on e.trabajador=a.Autorizado1
where a.fecha = '".$fecha."' and a.cedula=".$trabajador;

$qry="select a.cedula, b.nombre as nombres, a.fecha, a.entrada_real1, a.salida_real1,  a.entrada_real2, a.salida_real2,
a.entrada_esperada1, a.salida_esperada1, a.Entrada_Esperada2 as ent_esp2, a.salida_esperada2 as sal_esp2, a.coderror as codigo_error,
a.cod_ausencia, a.horasnetapresencia, a.horasnetaausencia, b.sistema_horario, b.desc_puesto as descripcion_puesto, b.ccosto, 
b.detalle_ccosto as desc_ccosto, a.PagoComida,  a.Inicio_ST1 as st1_inicio,  a.Fin_St1 as st1_fin, a.horas_st as st_hora, 
a.Inicio_ausencia as ausencia_inicio,  a.Fin_ausencia as ausencia_fin, a.Autorizado1, a.Inicio_DLT1 as dlt1_inicio, 
a.Fin_DLT1 as dlt1_fin, a.Horas_Dlt as dlt_hora, a.Sustitucion, a.Cedula_Sustituido, a.Causal_Sustitucion,  a.autorizado1, 
a.autorizado2, c.e_mail, Fecha_autor1, Fecha_autor2, f.Observaciones as motivo, a.ced_dlt, a.ced_st, validado_stdlt, rechazado_stdlt,
d.observacion as rechazado_por
from sw_hoja_de_tiempo_real a
 INNER JOIN adam_vw_dotacion_briqven_02_mas b on a.cedula = cast(b.trabajador as integer)
 left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha
 left join trabajadores c on cast(a.Cedula_Sustituido as integer) = cast(c.trabajador as integer) 
 left join sw_observaciones_rechazo_stdlt d on a.cedula = d.cedula  and a.fecha = d.fecha_stdlt  
 where a.fecha = '".$fecha."' and a.cedula=".$trabajador; 


 $query_semana="SELECT ID_CALENDARIO, TIPO_NOMINA, ANIO, PERIODO, FECHA_INICIO, FECHA_TERMINO, FECHA_PAGO, MES_ACUMULAR, ANIO_ACUMULAR FROM matesisitt.dbo.ADAM_CALENDARIO_NOMINA WHERE TIPO_NOMINA ='MS' and ANIO =".date('Y'). " and '".$fecha."' BETWEEN FECHA_INICIO and FECHA_TERMINO";

echo buscar($qry_sitt, $qry, $btn, $query_semana,$fecha,$trabajador);     
       
function buscar($a, $b, $btn, $query_semana, $fecha, $trabajador) {
      // include("../BD/conexion.php");
//print $b;
        $cn      = Conectarse_sitt();
        $stmt0   = $cn->query($query_semana);
        $contar0 = $stmt0->columnCount(); 
        $row0    = $stmt0->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
        //print "<br>btn".$btn;
        if ($btn=='C')
            $btn='C';
        elseif ($bnt=='E')
            $btn='E'; 

        $inicio_semana = primer_dia_semana($fecha);

       // CONSULTAMOS LA CANTIDAD DE HORAS DE SOBRE TIEMPO SEMANAL
        $query_total_ST_semanal="select SUM(Horas_ST) as total_ST_semanal from SW_Hoja_de_Tiempo_Real shdtr where cedula =".$trabajador." and fecha BETWEEN '".$inicio_semana."' and '".date('Y-m-d')."'";//substr($row0['FECHA_TERMINO'],0,10)."'";
        //print '<br>'.$query_total_ST_semanal;
        $stmt1   = $cn->query($query_total_ST_semanal);
        $contar1 = $stmt1->columnCount(); 
        $row1    = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
        $acum_horas_st_sem = $row1['total_ST_semanal'];


        // CONSULTAMOS LA CANTIDAD DE HORAS DE SOBRE TIEMPO ANUAL
        $query_total_ST_anual = "select SUM(Horas_ST) as total_ST_anual from SW_Hoja_de_Tiempo_Real shdtr where cedula = ".$trabajador." and fecha BETWEEN '".date('Y')."-01-01' and '".date('Y-m-d')."'";//substr($row0['FECHA_TERMINO'],0,10)."'";
       // print '<br>'.$query_total_ST_anual;
        $stmt2   = $cn->query($query_total_ST_anual);
        $contar2 = $stmt2->columnCount(); 
        $row2    = $stmt2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
        $acum_horas_st_anual = $row2['total_ST_anual'];

        // CONSULTAMOS SI EL PERIODO YA FUE PAGADO O CERRADO      
        $link2   = Conex_rrhh_pgsql();
        $query_periodo = "SELECT * FROM periodos_nomina pn WHERE '".$fecha."' BETWEEN inicio and fin";
        //print $query_periodo;
        $result = ejecutar_query($link2, $query_periodo) or die("Error en la Consulta SQL: ".$$query_periodo);
        $row    = ejecutar_fetch_array($result);
        ejecutar_close($link2);

        //print "<br>periodo_abierto:".$row['abierto'];
        if ($row['abierto']=='false' || $row['abierto']=='f')
            $periodo_abierto='Este periodo ya fue cerrado, el registro que ingrese o modifique sera tomado en cuenta para el proximo periodo de pago';
        else
            $periodo_abierto='';



        // CONSULTAMOS PARA VER SI YA FUERON CARGADAS HORAS EXTRAS EN EL SISTEMA DE CONTROL DE ASISTENCIA      
        $link   = Conex_Contancia_pgsql();
        $result = ejecutar_query($link, $b) or die("Error en la Consulta SQL: ".$b);
        $contar = ejecutar_num_rows($result);
        ejecutar_close($link);
//print $b;
        if ($contar>0){ 
            $row    = ejecutar_fetch_array($result);
            
            //print $row["autorizado1"]. ', '.$row["ced_st"].', '.$row["ced_dlt"] ;
            if ($row["autorizado1"]!='' && $row["autorizado1"]!='-1'){
                $status = 'Procesado'; 
                $readonly = 'readonly';
            }else{
                if ($row["validado_stdlt"]!='' && $row["validado_stdlt"]!='NULL' && $row["validado_stdlt"]!='0' ){
                    $status = 'Verificado'; 
                    $readonly = 'readonly';
                }else{
                    if ($row["autorizado2"]!=''){
                        //$inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input><i class="fa fa-check" aria-hidden="true"></i></h6></td>'; 
                        $status = 'Autorizado'; 
                        $readonly = 'readonly';
                    }elseif ($row["rechazado_stdlt"]){
                        $status = 'Rechazado';

                    }else{
                        if ($row["ced_st"]!='' && $row["ced_dlt"]!=''){
                            $status ='Cargado'; 
                        }
                    }                               
                }   
            }            
        }else{
        //    print "<br>".$a;
            $cn     = Conectarse_sitt();
            $stmt1  = $cn->query($a);
            $contar = $stmt1->columnCount(); 
            $contar_row = $stmt1->rowCount(); 
            $row    = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT); 
            if ($row['st_hora']>0 || $row['dlt_hora']>0)
                $status ='Procesado SITT';   
        }


        if($contar == 0){
            $inpt  = "No se han encontrado resultados!";
        }else{ 
            if ($row['autorizado2']!='' && $row['autorizado2']!=NULL){
                    $clase="danger";                  
            }

            if ($row['autorizado1']!='' && $row['autorizado1']!=NULL){
                    $clase="success";                  
            }
            
            if (($row['st_hora']==0) && ($row['dlt_hora']!=0)){
                $motivo_dlt = $row['motivo'];
                $motivo_st  = '';
            }elseif (($row['dlt_hora']==0) && ($row['st_hora']!=0)){
                    $motivo_st  = $row['motivo'];
                    $motivo_dlt = '';

            }elseif (($row['dlt_hora']!=0) && ($row['st_hora']!=0)){
               $motivo_st  = $row['motivo'];     
               $motivo_dlt = $row['motivo'];
            }else{
               $motivo_st  = '';     
               $motivo_dlt = '';

            }

            $fecha  = substr($row['fecha'], 0,10);             
            $inpt   = "";             
            $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
            $paramTrabFecha    = $row['cedula'].", '".$fecha."'";
            $buttonCambioEsper = '<button title="Cambiar Esperanza" type="button" onclick="activar_sp('.$paramTrabFecha.',10, \''.'Cambiar Esperanza'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-edit"></span></button>';
            
            $buttonFichadaComp = '<button title="Poner Fichada Completa" type="button"  onclick="activar_sp('.$paramTrabFecha.',1, \''.'Completar Fichadas'.'\')" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-dashboard"></span></button>';

            $buttonCompletar100 = '<button title="Completar al 100%" type="button" onclick="activar_sp('.$paramTrabFecha.',4, \''.'Completar al 100%'.'\')" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-ok-sign"></span></button>';
            
            if ($row['entrada_esperada1'] =='LL:LL' || $row['entrada_esperada1'] =='FF:FF')
                $type = '';
            else    
                $type = 'type="time"';
                if ((($row['autorizado2'] =='') || ($row['autorizado2'] =='-1')) && ($status !='Aprobado SITT')){
                    $buttonCargarDLT     = '<button title="Cargar DLT" type="button" onclick="activar_sp('.$paramTrabFecha.',5, \''.'Cargar DLT'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-wrench"></span>
                                </button>';
                    if ($row['dlt_hora']>0)
                        $buttonEliminarDLT  = '<button title="Eliminar DLT" type="button" onclick="activar_sp('.$paramTrabFecha.',6, \''.'Eliminar DLT'.'\')" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-wrench"></span>
                            </button>';
                    else    
                        $buttonEliminarDLT  = '';       
                }else    
                    $buttonEliminarDLT  = '';
           /* }else{
                $type = 'type="time" ';
                $buttonCargarDLT    = '';
                $buttonEliminarDLT  = ''; 
            } 
            */  
            if (($btn!='Ap')){
                if ((($row['autorizado2'] =='') || ($row['autorizado2'] =='-1')) && ($status !='Aprobado SITT') ){
                    $buttonCargarST     = '<button title="Cargar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',7, \''.'Cargar Sobre Tiempo'.'\')" class="btn  btn-success btn-circle"><span class="glyphicon glyphicon-time"></span></button>';
                    if ($row['st_hora']>0)
                        $buttonEliminarST   = '<button title="Eliminar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',8, \''.'Eliminar Sobre Tiempo'.'\')" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-time"></span></button>';
                    else
                        $buttonEliminarST   = '';                 
                }else{
                    $buttonCargarST   = '';
                    $buttonEliminarST = '';
                }
            }else{
                $buttonCargarST     = '<button title="Cargar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',7, \''.'Cargar Sobre Tiempo'.'\')" class="btn  btn-success btn-circle"><span class="glyphicon glyphicon-time"></span></button>';
                if ($row['st_hora']>0)
                    $buttonEliminarST   = '<button title="Eliminar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',8, \''.'Eliminar Sobre Tiempo'.'\')" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-time"></span></button>';
                else
                    $buttonEliminarST   = ''; 

            }
             if ($row['ent_esp2']=='00:00'){
                 $entradaesperada2 = null;
                 $salidaesperada2  = null;
             }else{
                 $entradaesperada2 = $row['ent_esp2'];
                 $salidaesperada2  = $row['sal_esp2'];
             }
              $inpt.= '<table  class="table table-striped table-bordered table-hover" id="dataModal-example" cellspacing="0">';
              $inpt.= '<tbody>
              
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th width="7%" class="danger">Fecha</th>
                            <th width="9%" class=""><input id="fecha" name="fecha" class="form-control" style="font-family: Arial; font-size: 8pt;" readonly value="'.$fecha.'"></th>              
                            <th width="7%" class="danger">Cedula</th>  
                            <th width="9%" class=""><input id="cedula" name="cedula" class="form-control" style="font-family: Arial; font-size: 8pt;" readonly value="'.$row['cedula'].'"></th>              
                            <th width="7%" class="danger">Nombre</th>
                            <th colspan="4" width="15%" class="">'.$row['nombres'].'</th>              
                            
                                                      
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            
                            <th  width="7%" class="danger">C. Costo</th>
                            <th  colspan="3" class="">'.$row['desc_ccosto'].'</th>              
                            <th  width="7%" class="danger">Puesto</th>
                            <th  width="15%" colspan="4" class="">'.$row['descripcion_puesto'].'</th>
                                                      
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th  width="7%"  class="danger">Ent. Esp. 1</th>
                            <th  width="9%"  class=""><input id="entEsp1" '.$type.' readonly name="entEsp1" class="form-control" value="'.$row['entrada_esperada1'].'"></th>              
                            <th  width="7%" class="danger">Sal. Esp. 1</th>
                            <th  width="9%" class=""><input id="salEsp1" '.$type.' readonly name="salEsp1" class="form-control" value="'.$row['salida_esperada1'].'"></th>
                            <th  width="7%" class="danger">Ent. Esp. 2</th>
                            <th  width="9%" class=""><input id="entEsp2" '.$type.' readonly name="entEsp2" class="form-control" value="'.$entradaesperada2.'"></th>              
                            <th  width="7%" class="danger">Sal. Esp. 2</th> 
                            <th  width="7%" class=""><input id="salEsp2" '.$type.' readonly name="salEsp2" class="form-control" value="'.$salidaesperada2.'"></th>                                        
                            <th  width="5%" class="danger">
                            <!-- <div id="sp10">'.$buttonCambioEsper.'</div>-->
                            </th>
                            
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Ent. Real 1</th>
                            <th class=""><input id="entReal1" type="time" readonly name="entReal1" class="form-control" value="'.$row['entrada_real1'].'">
                            </th>              
                            <th class="danger">Sal. Real 1</th>
                            <th class=""><input id="salReal1" type="time" readonly name="salReal1" class="form-control" value="'.$row['salida_real1'].'"></th>  
                            <th class="danger">Ent. Real 2</th> 
                            <th  class=""><input id="entReal2" type="time" readonly name="entReal2" class="form-control" value="'.$row['entrada_real2'].'"></th>             
                            <th class="danger">Sal. Real 2</th>
                            <th  class=""><input id="salReal2" type="time" readonly name="salReal2" class="form-control" value="'.$row['salida_real2'].'"></th>
                            <th  class="danger">
                             <!-- <div id="sp1">
                                '.$buttonFichadaComp.'
                              </div>-->

                              <input type="hidden" id="hddDatosCell" name="hddDatosCell" value="'.$btn.'">  
                            </th>
                        </tr>
                        <!--<tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Ini. Ausencia</th>
                            <th class="">'.$row['ausencia_inicio'].'</th> 
                            <th class="danger">Fin Ausencia</th>
                            <th class="">'.$row['ausencia_fin'].'</th> 
                            <th class="danger">H.Ausencia</th>                            
                            <th class="">'.$row['horasnetaausencia'].'</th>             
                            <th class="danger">H. Presencia</th>
                            <th class="">'.$row['horasnetapresencia'].'</th>
                            <th class="danger"><div id="sp4"></div></th>
                        </tr>-->';
                      
                     $inpt.= '<tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" style="text-align: center" colspan="9">CARGA DE HORAS EXTRAS</th>
                        </tr>
                        <tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" rowspan="2">Inicio H. Extras</th>
                            <th class=""><input id="Inicio_ST1" name="Inicio_ST1" class="form-control" type="time" value="'.$row['st1_inicio'].'" '.$readonly.'></th> 
                            <th class="danger">Fin H. Extras</th>
                            <th class=""><input id="Fin_St1" name="Fin_St1" class="form-control" onChange="calcularHorasSobreTiempo();" type="time" value="'.$row['st1_fin'].'" '.$readonly.'></th>               
                            <th class="danger">Total H. Extras</th> 
                            <th class=""><input readonly id="Horas_ST" name="Horas_ST" class="form-control" value="'.$row['st_hora'].'"></th> 
                            <th class="danger">Acum. H.E. Semanal </th> 
                            <th class=""><input readonly id="Acum_Horas_ST_Sem" name="Acum_Horas_ST_Sem" class="form-control" value="'.$acum_horas_st_sem.'"></th>  
                            <th class="danger" rowspan="2">
                              <div id="sp7">'.$buttonCargarST.'</div>
                              <div id="sp8">'.$buttonEliminarST.'</div>
                            </th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th colspan="5"><textarea name="motivo_ST" placeholder="Motivo de las horas extras" rows="2" cols="100%" '.$readonly.'>'.$motivo_st.'</textarea></th>
                            <th class="danger">Acum. H.E. Anual</th> 
                            <th class=""><input readonly id="Acum_Horas_ST_Anual" name="Acum_Horas_ST_Anual" class="form-control" value="'.$acum_horas_st_anual.'"></th> 
                        </tr>';
                        //print 'dlt1_inicio: '.$row['dlt1_inicio'];
if ($row['entrada_esperada1'] =='LL:LL' || $row['entrada_esperada1'] =='FF:FF'){                           
                     $inpt.= '<tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" style="text-align: center" colspan="9">CARGA DE DIAS LIBRE TRABAJADO (DLT)</th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger" rowspan="2">Inicio DLT</th>
                            <th class=""><input id="Inicio_DLT1" type="time" name="Inicio_DLT1" class="form-control" value="'.$row['dlt1_inicio'].'" '.$readonly.'></th> 
                            <th class="danger">Fin DLT </th><th class=""><input id="Fin_DLT1" type="time" name="Fin_DLT1" class="form-control" value="'.$row['dlt1_fin'].'" '.$readonly.'></th>               
                            <th class="danger">Horas DLT</th> 
                            <th class=""><input readonly id="Horas_Dlt" name="Horas_Dlt" class="form-control" value="'.$row['dlt_hora'].'"></th> 
                            <th class="danger" rowspan="2">Status</th>
                            <th class="" rowspan="2">'.$status.'</th> 
                            <th  class="danger" rowspan="2">
                            <div id="sp5">'.$buttonCargarDLT.'</div>
                              <div id="sp6">'.$buttonEliminarDLT.'</div>
                            </th>
                        </tr><tr style="font-family: Arial; font-size: 8pt;">
                            <th colspan="5"><textarea name="motivo_DLT" placeholder="Motivo del DLT" rows="2" cols="100%"  '.$readonly.'>'.$motivo_dlt.'</textarea></th>

                        </tr>';
}else{
                    $inpt.= '<tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" style="text-align: center" colspan="9">CARGA DE DIAS LIBRE TRABAJADO (DLT)</th>
                        </tr>
                     <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger" rowspan="2"></th>
                            <th class="" colspan="7" style="text-align: center"> Este trabajador presenta esperanza de trabajo, por lo que NO genera DLT </th> 
                            <th  class="danger" rowspan="2">
                            <div id="sp5"></div>
                              <div id="sp6"></div>
                            </th>
                        </tr>';    
}                        
                        if ($status == 'Rechazado'){

                            $inpt.= '<tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" style="text-align: center" colspan="9">RECHAZADO</th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger" rowspan="2">Rechazado Por</th>
                                <tr style="font-family: Arial; font-size: 8pt;">
                                <th colspan="5"><textarea name="rechazado_STDLT" rows="2" cols="100%" readonly>'.$row["rechazado_por"].'</textarea></th>
                                <th class="danger">Responsable</th> 
                                <th class="">'.nombre_trabajadores($row["rechazado_stdlt"]).'</th>
                                <th class="danger"></th> 
                            </tr>';
                        }
                        $inpt.= '<tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger" style="text-align: center" colspan="9">CERTIFICACION DE HORAS EXTRAS Y TRABAJO EN DIA LIBRE (DLT)</th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Razón o Justificación</th>
                            <th colspan="8">'.llenar_checkbox_razon_stdlt($link).'</th>

                        </tr>';
                       
                                           
                $inpt .=' </tbody></table><div id="mensaje"></div>';   
            //}                                
        }
return $inpt;
//print_r($row);
}         
?>