<?php 
session_start();

?>
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 
function ventanaAct(idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./update/index.php?idp='+idp, "Actualizacion", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  $(document).ready(function(){
  $.blockUI();
  setTimeout($.unblockUI(),9999);
      }); 
     }
 }

function verplanilla(uid){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(600/2);   
    var ventana = window.open('./planilla_consulta.php?uid='+uid, "Planilla Consulta Medica", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  //$(document).ready(function(){
  //$.blockUI();
  //setTimeout($.unblockUI(),9999);
      //}); 
     }
 } 

 function verrecipe(uid){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(600/2);   
    var ventana = window.open('./planilla_recipe.php?uid='+uid, "Recipe", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  //$(document).ready(function(){
  //$.blockUI();
  //setTimeout($.unblockUI(),9999);
      //}); 
     }
 } 

 function verreferencia(uid){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(600/2);   
    var ventana = window.open('./planilla_referencia.php?uid='+uid, "Referencia", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  //$(document).ready(function(){
  //$.blockUI();
  //setTimeout($.unblockUI(),9999);
      //}); 
     }
 } 
</script>
<?php 
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
if ($_SESSION['nivel']==1)
  $listado=pg_query($cn,"SELECT * FROM v_consulta order by fecha desc");
else
  $listado=pg_query($cn,"SELECT * FROM v_consulta WHERE login_atendio LIKE '%".$_SESSION['user_session']."%' order by fecha desc");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr> 
                        <th>Oper.</th>
                        <th>Fecha</th> 
                        <th>Turno</th>  
                        <th>Medico</th>                        
                        <th>Paramedico</th>
                        <th>Ced. Paciente</th>
                        <th>Nombre Paciente</th>
                        <th>Departamento</th>
                        <th>Motivo</th>
                        <th>Patologia</th>
                        <th>Sintomas</th>                        
                        <th>fecha_prox_cita</th>                                               
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>                     
                    <?php                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                      $idc=$reg['uid']; 
                               echo '<tr>';                               
                               echo '<td>
                              <A id="act'.$idc.'" onclick="verplanilla('.$idc.')" title="Imprimir: '.$reg['ci'].'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                              echo '<A href="consulta_registrada.php?idconsulta='.$idc.'" title="Ver Detalles Consulta Medica de '.$reg['nombre_completo'].'"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A>';
                               if ($reg['indicaciones_comp']!='')
                                 echo '<A id="act'.$idc.'" onclick="verrecipe('.$idc.')" title="Ver Recipe: '.$reg['ci'].'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                               if ($reg['referencia_medica']!='')
                                 echo '<A id="act'.$idc.'" onclick="verreferencia('.$idc.')" title="Ver Referencia: '.$reg['ci'].'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                                
                                if ($reg['autorizacion']=='SI'){
                                      echo '<A href="autoriz.php?idconsulta='.$idc.'" target="_blank" title="Ver Autorizacion '.$reg['nombre_completo'].'"><IMG SRC="images/autoriz.png" WIDTH="20" HEIGHT="20"></A>';                                      
                                    }
                              
                               echo '</td>';
                               echo '<td>'.substr($reg['fecha'],0,16).'</td>';                              
                               echo '<td>'.$reg['turno'].'</td>';
                               echo '<td>'.$reg['medico'].'</td>';
                               echo '<td>'.$reg['paramedico'].'</td>';
                               echo '<td>'.$reg['ci'].'</td>';
                               echo '<td>'.$reg['nombre_completo'].'</td>';
                               echo '<td>'.$reg['departamento'].'</td>';
                               echo '<td>'.$reg['motivo'].'</td>';
                               echo '<td>'.$reg['patologia'].'</td>';
                               echo '<td>'.$reg['sintomas'].'</td>';                               
                               echo '<td>'.$reg['fecha_prox_cita'].'</td>';                 
                               echo '</tr>';                               
                    }
                    ?>
                <tbody>
            </table>
 </div>
 
 <?php 
pg_free_result($listado);
?>
