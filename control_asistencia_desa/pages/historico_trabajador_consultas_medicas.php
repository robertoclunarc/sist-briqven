<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

    $qry="SELECT * FROM v_consulta "; 
    
    if ($trabajador!='NULL')
    {
      $qry.=" WHERE ci='".$trabajador."'";
    }  
 
    //$qry.=" order by t.trabajador, p.fecha_nacimiento"; 
    $qry.=" order by fecha desc";


    //print $qry;
    buscar($qry);     
}else
      echo "Debe Iniciar Sesion";       

function buscar($b) {
          include("../BD/conexion.php");
          $cn=Conex_servicio_medicos();
          $listado=pg_query($cn,$b);
          $contar=1;        
            if($contar == 0){
                  $inpt = "No se han encontrado resultados!";
                  
            }else{
              $inpt = '<b>Consultas M&eacute;dicas</b><table width="100%" class="table table-striped" id="dataTables-example04" border="0" >';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>#.</h6></th>
           <!-- <th class = "info"><h6>Oper.</h6></th>-->
            <th class = "info"><h6>Fecha</h6></th>
            <th class = "info"><h6>Motivo</h6></th>
            <th class = "info"><h6>Sintomas</h6></th>
            <th class = "info"><h6>Medico</h6></th>
            <th class = "info"><h6>Paramedico</h6></th>  
            <th class = "info"><h6>Observaci&oacute;n</h6></th>            
            <th class = "info"><h6>Resultado Eval.</h6></th>  
            <th class = "info"><h6>Departamento</h6></th>
            <th class = "info"><h6>Turno</h6></th>
            </tr>
        </thead>
        <tbody>';
                  
                $contar=0;
                while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC)) { 
                  $contar++;
                  $idc=$reg['uid'];
                  $inpt .='<tr>';
                  $inpt .='<td><h6>'.$contar.'</h6></td>';                   
                  /*$inpt .='<td>
                              <A id="act'.$idc.'" onclick="verplanilla('.$idc.')" title="Imprimir: '.$reg['ci'].'" href="#"><IMG SRC="../images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                  $inpt .='<A href="consulta_registrada.php?idconsulta='.$idc.'" title="Ver Detalles Consulta Medica de '.$reg['nombre_completo'].'"><IMG SRC="../images/note.png" WIDTH="20" HEIGHT="20"></A>';
                  if ($reg['indicaciones_comp']!='')
                      $inpt .='<A id="act'.$idc.'" onclick="verrecipe('.$idc.')" title="Ver Recipe: '.$reg['ci'].'" href="#"><IMG SRC="../images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                  if ($reg['referencia_medica']!='')
                      $inpt .='<A id="act'.$idc.'" onclick="verreferencia('.$idc.')" title="Ver Referencia: '.$reg['ci'].'" href="#"><IMG SRC="../images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                                
                  if ($reg['autorizacion']=='SI'){
                      $inpt .='<A href="autoriz.php?idconsulta='.$idc.'" target="_blank" title="Ver Autorizacion '.$reg['nombre_completo'].'"><IMG SRC="images/autoriz.png" WIDTH="20" HEIGHT="20"></A>';                                      
                  }
                              
                  $inpt .='</td>';
                   */
                  $inpt .='<td><h6>'.formato_fecha(substr($reg['fecha'],0,10),'-').'</h6></td>';
                  //$inpt .='<td><h6>'.$reg['fecha'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['motivo'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['sintomas'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['medico'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['paramedico'].'</h6></td>';   
                  $inpt .='<td><h6>'.$reg['observaciones'].'</h6></td>';                  
                  $inpt .='<td><h6>'.$reg['resultado_eva'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['departamento'].'</h6></td>';
                  $inpt .='<td><h6>'.$reg['turno'].'</h6></td>';
              
                  $inpt .='</tr>';    
                  } 
                
                  $inpt .=' </tbody>

                    </table>';

            }
    echo $inpt;
    //print_r($row);
}         


?>
