<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $conFoto     = isset($_POST["conFoto"])?$_POST["conFoto"]:false;

    //$qry="select * from ADAM_DSSC_HIST_TRABAJADOR_SID adhts  inner join VW_trabajadores_01 vt on LTRIM(adhts.trabajador)=LTRIM(vt.trabajador)";
    $qry="select DISTINCT adhts.*, 
    ash.DESCRIPCION,
    vt.DOMICILIO, 
    vt.DOMICILIO2, 
    vt.POBLACION, 
    vt.ESTADO_PROVINCIA, 
    vt.PAIS, 
    vt.CODIGO_POSTAL, 
    vt.CALLES_ALEDANAS, 
    vt.TELEFONO_PARTICULAR, 
    vt.REG_SEGURO_SOCIAL, 
    vt.DOMICILIO3, 
    vt.E_MAIL, 
    vt.ENTEADSCRI 
    from ADAM_DSSC_HIST_TRABAJADOR_SID adhts 
    inner join VW_trabajadores_01 vt on LTRIM(adhts.trabajador)=LTRIM(vt.trabajador) 
    INNER JOIN ADAM_SISTEMAS_HORARIOS ash on adhts.SISTEMA_HORARIO = ash.SISTEMA_HORARIO";
    if ($trabajador!='NULL')
    {
      $qry.=" where adhts.TRABAJADOR=".$trabajador."";
    }  

    //print $qry;
    buscar($qry,$conFoto);     
}else
      echo "Debe Iniciar Sesion";       

function buscar($b, $conFoto) {
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
                $inpt = '<table width="100%" border="0" class="table table-striped" id="dataTables-example0">';
                  
                $contar=0;
                  
                while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                      if ($row['SIT_TRABAJADOR']==2){
                        $clase      ="danger";
                        $status     ="DESACTIVADO";   
                        //$fecha_baja =  $row['FECHA_BAJA'];  
                        $fecha_baja = str_replace('/', '-', substr($row['FECHA_BAJA'],0,10));
                        $inpt2 ='</tr>';
                        $inpt2 .='<tr>';                                             
                        $inpt2 .='<th><label>Fecha de Baja:</label></th>';
                        $inpt2 .='<th colspan="3">'.formato_fecha($fecha_baja,"-").'</th>';            
                      }elseif ($row['SIT_TRABAJADOR']==1){ 
                        $clase="success"; 
                        $status="ACTIVO";               
                        $fecha_baja =  $row['FECHA_BAJA'];  
                        $inpt2 ='';              
                      }

                      if ($row['ENTEADSCRI']!=null){
                        $inpt3 ='</tr>';
                        $inpt3 .='<tr>';                                             
                        $inpt3 .='<th><label>Ente Adscripci&oacute;n:</label></th>';
                        $inpt3 .='<th colspan="3">'.$row['ENTEADSCRI'].'</th>';            
                      }else{
                        $inpt3 ='';              
                      }

                      $direccion='';
                      if ($row['DOMICILIO']!='' && $row['DOMICILIO']!=null)
                         $direccion.=$row['DOMICILIO'].', ';
                      
                      if ($row['DOMICILIO2']!='' && $row['DOMICILIO2']!=null)
                         $direccion.=$row['DOMICILIO2'];
                      
                      if ($row['DOMICILIO3']!='' && $row['DOMICILIO3']!=null)
                         $direccion.=', MUNICIPIO: '.$row['DOMICILIO3']; 

                      if ($row['ESTADO_PROVINCIA']!='' && $row['ESTADO_PROVINCIA']!=null)
                         $direccion.=', ESTADO:'.$row['ESTADO_PROVINCIA'];

                        //$row['DOMICILIO'].', '.$row['DOMICILIO2'].', '.$row['POBLACION'].', MUNICIPIO: '.$row['DOMICILIO3'].', ESTADO:'.$row['ESTADO_PROVINCIA']
                        $contar++;
                        $fecha_nacimiento = str_replace('/', '-', $row['FECHA_NACIMIENTO']);
                        $fecha_ingreso = str_replace('/', '-', $row['FECHA_INGRESO']);
                        $inpt .='<tr>';
                        if ($conFoto!=false){
                          $url_img="../../rrhh/fotcarmat_new/".trim($row['TRABAJADOR']).".bmp";
                          if (!is_file($url_img))
                          $url_img="images/user.png";

                          //$inpt .='<td colspan="2" rowspan="5" align="center" valign="center"><br><img width="150px" height="150px" style="border-radius: 100%;" src="'.$url_img.'" /></td>'; 
                          $inpt .='<td colspan="2" rowspan="5" align="center" valign="center"><br><img width="150px" height="150px" style="border-radius: 100%;" data-toggle="modal" src="'.$url_img.'" onclick="cargarfoto('.$row['TRABAJADOR'].')" data-target="#exampleModalCenter"/></td>'; 
                        }
                        $inpt .='<th><label>Fecha de Nacimiento:</label></th>';          
                        $inpt .='<th>'.$fecha_nacimiento.'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';
                        $inpt .='<th><label>Edad:</label></th>';
                        $inpt .='<th>'.calcular_edad($row['FECHA_NACIMIENTO']).'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                    
                        $inpt .='<th><label>Sexo: </label></th>';
                        $inpt .='<th>'.sexo($row['SEXO']).'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';  
                        $inpt .='<th><label>T&iacute;tulo acad&eacute;mico: </label></th>';
                        $inpt .='<th>'.$row['TITULO'].'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';   
                        $inpt .='<th><label>Domicilio: </label></th>';
                        $inpt .='<th>'.$direccion.'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>'; 
                        $inpt .='<th><label>Situaci&oacute;n Laboral:</label></th>';          
                        $inpt .='<th colspan="3"><span class="label label-'.$clase.'">'.$status.'</span></th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                                             
                        $inpt .='<th><label>Siglado:</label></th>';
                        $inpt .='<th colspan="3">'.$row['SIGLADO'].'</th>';                        
                        $inpt .='</tr>';
                        $inpt .='<tr>';                                             
                        $inpt .='<th><label>Fecha de Ingreso:</label></th>';
                        $inpt .='<th colspan="3">'.$fecha_ingreso.'</th>';
                        $inpt .=$inpt2;
                        $inpt .='</tr>';
                        $inpt .='<tr>'; 
                        $inpt .='<th><label>Antiguedad:</label></th>';
                        $inpt .='<th colspan="3"><label>'.antiguedad($row['FECHA_INGRESO'],$fecha_baja).'</label></th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';   
                        $inpt .='<th><label>Tipo Nomina:</label></th>';
                        $inpt .='<th colspan="3">'.$row['NOMINA'].'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                          
                        $inpt .='<th><label>Clase Nomina:</label></th>';
                        $inpt .='<th colspan="3">'.$row['CLASE_NOMINA'].'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                          
                        $inpt .='<th><label>Cuadrilla:</label></th>';
                        $inpt .='<th colspan="3">'.$row['DESCRIPCION'].'</th>';
                        $inpt .='</tr>';                        
                        $inpt .='<tr>';                                               
                        $inpt .='<th><label>Cargo:</label></th>';
                        $inpt .='<th colspan="3">'.$row['PUESTO']." - ".$row['DESC_PUESTO'].'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                        
                        $inpt .='<th><label>Centro de Costo:</label></th>';
                        $inpt .='<th colspan="3">'.$row['CENTRO_COSTO']." - ".strtoupper($row['DEPARTAMENTO']).'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                     
                        $inpt .='<th><label>Supervisor Inmediato:</label></th>';
                        $inpt .='<th colspan="3">'.$row['NOMBRE_JEFE'].'</th>';
                        $inpt .='</tr>';
                        $inpt .='<tr>';                            
                        $inpt .='<th><label>Cargo Supervisor Inmediato:</label></th>';  
                        $inpt .='<th colspan="3">'.$row['DESC_PSUPERIOR'].'</th>';  
                        $inpt .='</tr>';
                        $inpt .=$inpt3;
                  } 
                
                  $inpt .=' </tbody>

                    </table>';

            }
    echo $inpt;
    //print_r($row);
}         
?>
