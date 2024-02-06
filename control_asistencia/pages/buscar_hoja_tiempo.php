<?php
 session_start();

$trabajador= isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
$fecha= isset($_GET["fecha"])?$_GET["fecha"]:"NULL";
$btn= isset($_GET["btn"])?$_GET["btn"]:"NULL";

$qry="select a.cedula, b.nombres, a.fecha, a.entrada_real1, a.salida_real1,  a.entrada_real2, a.salida_real2,
a.entrada_esperada1, a.salida_esperada1,a.Entrada_Esperada2, a.Salida_Esperada2, a.CodError, a.cod_ausencia,
a.horasnetapresencia, a.horasnetaausencia, b.sistema_horario, b.DESC_PUESTO, b.centro_costo, b.desc_ccosto, a.PagoComida, 
 a.Inicio_ST1,  a.Fin_St1, a.Horas_ST, a.Inicio_ausencia,  a.Fin_ausencia, a.Autorizado1, a.Inicio_DLT1, a.Fin_DLT1, 
a.Horas_Dlt, a.Sustitucion, a.Cedula_Sustituido, c.nombres as sustituido, c.DESC_PUESTO as puestosustituido, a.Causal_Sustitucion,  d.desc_causa, a.Autorizado1, a.Autorizado2, e.siglado, Fecha_autor1, Fecha_autor2
from sw_hoja_de_tiempo_real a
 INNER JOIN adam_datos_personales b on a.cedula = b.trabajador
 left join adam_datos_personales c on a.Cedula_Sustituido = c.trabajador 
 left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion 
 left join (select trabajador, nombres, siglado from adam_datos_personales
union select -1, 'Sist. Tiempo Trabajado', 'SITT') e on e.trabajador=a.Autorizado1
where a.fecha = '".$fecha."' and a.cedula=".$trabajador;

echo buscar($qry, $btn);     
       
function buscar($b, $btn) {
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
            
            $row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);             
            
            $fecha = substr($row['fecha'], 0,10);             
            $inpt = "";             
            $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
            $paramTrabFecha=$row['cedula'].", '".$fecha."'";

            $buttonCambioEsper='<button title="Cambiar Esperanza" type="button" onclick="activar_sp('.$paramTrabFecha.',10, \''.'Cambiar Esperanza'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-edit"></span></button>';
            
            $buttonFichadaComp='<button title="Poner Fichada Completa" type="button"  onclick="activar_sp('.$paramTrabFecha.',1, \''.'Completar Fichadas'.'\')" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-dashboard"></span></button>';

            $buttonFichadaParc='<button title="Poner Fichada Parcial Con Codigo Ausencia" type="button"  onclick="activar_sp('.$paramTrabFecha.',2, \''.'Poner Fichada Parcial'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-dashboard"></span></button>'; 

            $buttonMediaHoraComida='<button title="Cargar Media Hora Comida" type="button" onclick="activar_sp('.$paramTrabFecha.',3, \''.'Registrar Media Hora de Comida'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-cutlery"></span></button>';                                     
            
            $buttonCompletar100='<button title="Completar al 100%" type="button" onclick="activar_sp('.$paramTrabFecha.',4, \''.'Completar al 100%'.'\')" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-ok-sign"></span></button>';

            $buttonCargarDLT='<button title="Cargar DLT" type="button" onclick="activar_sp('.$paramTrabFecha.',5, \''.'Cargar DLT'.'\')" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-wrench"></span>
                            </button>';
            
            $buttonEliminarDLT='<button title="Eliminar DLT" type="button" onclick="activar_sp('.$paramTrabFecha.',6, \''.'Eliminar DLT'.'\')" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-wrench"></span>
                            </button>';

            $buttonCargarST='<button title="Cargar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',7, \''.'Cargar Sobre Tiempo'.'\')" class="btn  btn-success btn-circle"><span class="glyphicon glyphicon-time"></span>
                            </button>';

            $buttonEliminarST='<button title="Eliminar Sobre Tiempo" type="button" onclick="activar_sp('.$paramTrabFecha.',8, \''.'Eliminar Sobre Tiempo'.'\')" class="btn btn-danger btn-circle"><span class="glyphicon glyphicon-time"></span>
                            </button>';

            $urlSust="'cargar_sustitucion.php?cedula=".$row['cedula']."&fecha=".$fecha."'";
            $buttonSustitucion='<button title="Cargar Sustitucion" type="button" onclick="window.location.href = '.$urlSust.'" class="btn btn-success btn-circle"><span class="glyphicon glyphicon-user"></span>
                            </button>';                
                                                                       

            $pagoComidaSI='';
            $pagoComidaNO='';

            if ($row['PagoComida']=='S'){
              $pagoComidaSI='checked';
              $pagoComidaNO='';
            } else{
              $pagoComidaSI='';
              $pagoComidaNO='checked';
            }

            $opcionesComida='<div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsComida" id="optionsSi" value="S" '.$pagoComidaSI.'>Si
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsComida" id="optionsNo" value="N" '.$pagoComidaNO.'>No
                                                </label>
                                            </div>';   
            
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
                            <th  width="15%" colspan="4" class="">'.$row['DESC_PUESTO'].'</th>
                                                      
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th  width="7%"  class="danger">Ent. Esp. 1</th>
                            <th  width="9%"  class=""><input id="entEsp1" name="entEsp1" class="form-control" value="'.$row['entrada_esperada1'].'"><input type="hidden" id="entEsp11" name="entEsp11" class="form-control" value="'.$row['entrada_esperada1'].'"></th>              
                            <th  width="7%" class="danger">Sal. Esp. 1</th>
                            <th  width="9%" class=""><input id="salEsp1" name="salEsp1" class="form-control" value="'.$row['salida_esperada1'].'"><input type="hidden" id="salEsp11" name="salEsp11" class="form-control" value="'.$row['salida_esperada1'].'"></th>
                            <th  width="7%" class="danger">Ent. Esp. 2</th>
                            <th  width="9%" class=""><input id="entEsp2" name="entEsp2" class="form-control" value="'.$row['Entrada_Esperada2'].'"><input type="hidden" id="entEsp21" name="entEsp21" class="form-control" value="'.$row['Entrada_Esperada2'].'"></th>              
                            <th  width="7%" class="danger">Sal. Esp. 2</th> 
                            <th  width="7%" class=""><input id="salEsp2" name="salEsp2" class="form-control" value="'.$row['Salida_Esperada2'].'"><input type="hidden" id="salEsp21" name="salEsp21" class="form-control" value="'.$row['Salida_Esperada2'].'"></th>                                         
                            <th  width="5%" class="danger">
                            <div id="sp10">'.$buttonCambioEsper.'</div>
                            </th>
                            
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Ent. Real 1</th>
                            <th class=""><input id="entReal1" type="time" name="entReal1" class="form-control" value="'.$row['entrada_real1'].'">
                            </th>              
                            <th class="danger">Sal. Real 1</th>
                            <th class=""><input id="salReal1" type="time" name="salReal1" class="form-control" value="'.$row['salida_real1'].'"></th>  
                            <th class="danger">Ent. Real 2</th> 
                            <th  class=""><input id="entReal2" type="time" name="entReal2" class="form-control" value="'.$row['entrada_real2'].'"></th>             
                            <th class="danger">Sal. Real 2</th>
                            <th  class=""><input id="salReal2" type="time" name="salReal2" class="form-control" value="'.$row['salida_real2'].'"></th>
                            <th  class="danger">
                              <div id="sp1">
                                '.$buttonFichadaComp.'
                              </div>
                              <div id="sp2">
                                '.$buttonFichadaParc.'
                              </div>
                              <input type="hidden" id="hddDatosCell" name="hddDatosCell" value="'.$row['CodError'].'">  
                            </th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Ini. Ausencia</th>
                            <th class="">'.$row['Inicio_ausencia'].'</th> 
                            <th class="danger">Fin Ausencia</th>
                            <th class="">'.$row['Fin_ausencia'].'</th> 
                            <th class="danger">H.Ausencia</th>                            
                            <th class="">'.$row['horasnetaausencia'].'</th>             
                            <th class="danger">H. Presencia</th>
                            <th class="">'.$row['horasnetapresencia'].'</th>
                            <th class="danger"></th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Cod. Error</th>
                            <th class="">'.$row['CodError'].'</th>              
                            <th class="danger">Cod. Ausencia</th>  
                            <th class=""><input id="cod_ausencia" name="cod_ausencia" class="form-control" value="'.$row['cod_ausencia'].'"></th>               
                            <th class="danger">Pago Comida</th>
                            <th class="">'.$opcionesComida.'</th>
                            <th class="danger"></th>
                            <th class=""></th>               
                            <th class="danger">
                              <div id="sp3">'.$buttonMediaHoraComida.'</div>
                              <div id="sp4">'.$buttonCompletar100.'</div>
                            </th>              
                                                     
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                                           
                            <th class="danger">CI. Sustituido</th>
                            <th class="">'.$row['Cedula_Sustituido'].'</th>  
                            <th class="danger">Nombre Sustituido</th> 
                            <th class="">'.$row['sustituido'].'</th>             
                            <th class="danger">Puesto Sustituido</th>
                            <th class="">'.$row['puestosustituido'].'</th>
                            <th class="danger">Causal</th>              
                            <th class="">'.$row['desc_causa'].'</th> 
                            <th class="danger">
                            <div id="sp9">'.$buttonSustitucion.'</div>
                            </th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Inicio DLT</th>
                            <th class=""><input id="Inicio_DLT1" type="time" name="Inicio_DLT1" class="form-control" value="'.$row['Inicio_DLT1'].'"></th> 
                            <th class="danger">Fin DLT</th>
                            <th class=""><input id="Fin_DLT1" type="time" name="Fin_DLT1" class="form-control" value="'.$row['Fin_DLT1'].'"></th>               
                            <th class="danger">Horas DLT</th> 
                            <th class=""><input readonly id="Horas_Dlt" name="Horas_Dlt" class="form-control" value="'.$row['Horas_Dlt'].'"></th> 
                            <th class="danger"></th>
                            <th class=""></th> 
                            <th  class="danger">
                            <div id="sp5">'.$buttonCargarDLT.'</div>
                              <div id="sp6">'.$buttonEliminarDLT.'</div>
                            </th>
                        </tr>
                        <tr style="font  -family: Arial; font-size: 8pt;">
                            <th class="danger">Inicio H. Extras</th>
                            <th class=""><input id="Inicio_ST1" name="Inicio_ST1" class="form-control" type="time" value="'.$row['Inicio_ST1'].'"></th> 
                            <th class="danger">Fin H. Extras</th>
                            <th class=""><input id="Fin_St1" name="Fin_St1" class="form-control" type="time" value="'.$row['Fin_St1'].'"></th>               
                            <th class="danger">Total H. Extras</th> 
                            <th class=""><input readonly id="Horas_ST" name="Horas_ST" class="form-control" value="'.$row['Horas_ST'].'"></th> 
                            <th class="danger"></th>
                            <th class=""></th> 
                            <th class="danger">
                              <div id="sp7">'.$buttonCargarST.'</div>
                              <div id="sp8">'.$buttonEliminarST.'</div>
                            </th>
                        </tr>
                        <tr style="font-family: Arial; font-size: 8pt;">
                            <th class="danger">Actualizado Por</th>
                            <th class="">'.$row['siglado'].'</th> 
                            <th class="danger">Fecha Actualizado</th>
                            <th class="">'.$row['Fecha_autor1'].'</th>               
                            <th class="danger">Modificado Por</th> 
                            <th class="">'.$row['Autorizado2'].'</th> 
                            <th class="danger">Fecha Modificacion</th> 
                            <th class="">'.$row['Fecha_autor2'].'</th> 
                            <th class="danger"></th>
                        </tr>';

                                           
                $inpt .=' </tbody></table><div id="mensaje"></div>';                               
        }
return $inpt;
//print_r($row);
}         
?>