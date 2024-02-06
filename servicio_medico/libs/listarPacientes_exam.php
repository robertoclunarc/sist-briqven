<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistapacientes.js"></script>
<script type="text/javascript"> 
function ventanaAct(uid){ 
    var x=900; 
    var y=470; 
    posicion_x=(screen.width/2); 
    posicion_y=(screen.height/2)-(x/2);   
    var ventana = window.open('./paciente_nuevo.php?modo=M&cedula='+uid, "Historia", "width="+x+",height="+y+",menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
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
$listado=pg_query($cn,"select * from v_historias_pacientes order by ci");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
         <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_pacientes">
                <thead>
                    <tr> 
                        <th>Oper.</th> 
                        <th>Cedula</th>                       
                        <th>Paciente</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Fecha Nac.</th>
                        <th>T. Sang.</th>
                        <th>Sexo</th>
                        <th>Mono Dominante</th>                                                
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
                      $idc=$reg['ci'];                      
                       echo '<tr>';                                
                       echo '<td>';
                       echo '<A href="actualizar_historia.php?cedula='.$idc.'" title="Examen Medico a '.$reg['nombre_completo'].'"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A>                        
                       </td>';                    
                       echo '<td>'.$reg['ci'].'</td>';
                       echo '<td>'.$reg['nombre_completo'].'</td>';
                       echo '<td>'.$reg['departamento'].'</td>';
                       echo '<td>'.$reg['cargo'].'</td>';
                       echo '<td>'.$reg['fechanac'].'</td>';
                       echo '<td>'.$reg['tipo_sangre'].'</td>';
                       echo '<td>'.$reg['sexo'].'</td>';
                       echo '<td>'.$reg['mano_dominante'].'</td>';         
                       echo '</tr>';                               
                    }
                    ?>
                <tbody>
            </table>
 </div> 
<?php 
pg_free_result($listado);
?>
