<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadohistorias.js"></script>
<script type="text/javascript"> 
function ventanaAct(idh,ci,idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./planilla_historia.php?idh='+idh+'&ci='+ci+'&idp='+idp, "Actualizacion", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  $(document).ready(function(){
  $.blockUI();
  setTimeout($.unblockUI(),9999);
      }); 
     }
 } 
</script>
<?php 
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
$listado=pg_query($cn,"select uid_historia, ci, uid_paciente, fecha_apertura, medico, nombre_completo, departamento from v_historias order by fecha_apertura ASC");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_historias">
                <thead>
                    <tr> 
                        <th>Oper.</th>
                        <th>Fecha Aper.</th> 
                        <th>Medico</th>                        
                        <th>Ced. Paciente</th>
                        <th>Nombre Paciente</th>
                        <th>Departamento</th>                                     
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
                      $idh=$reg['uid_historia'];
                      $ci=$reg['ci'];
                      $idp=$reg['uid_paciente']; 
                               echo '<tr>';                                
                               echo '<td><A id="act'.$idh.'" onclick="ventanaAct('.$idh.','.$ci.','.$idp.')" title="Ver Detalles '.$ci.'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A></td>';       
                               echo '<td>'.$reg['fecha_apertura'].'</td>';
                               echo '<td>'.$reg['medico'].'</td>'; 
                               echo '<td>'.$ci.'</td>';
                               echo '<td>'.$reg['nombre_completo'].'</td>';
                               echo '<td>'.$reg['departamento'].'</td>';
                               echo '</tr>';                               
                    }
                    ?>
                <tbody>
            </table>
 </div> 
<?php 
pg_free_result($listado);
?>
