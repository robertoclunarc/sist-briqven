<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 
function ventanaAct(uid){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./planilla_examen.php?uid='+uid, "Planilla Examen Medico", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	$(document).ready(function(){
	$.blockUI();
	setTimeout($.unblockUI(),9999);
    	}); 
     }
 } 

 function ventanaplani_cert(uid){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(670/2); 
    posicion_y=(screen.height/2)-(720/2);   
    var ventana = window.open('./imprimir_certificado.php?uid='+uid, "Certificado Medico", "width=670,height=720,menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
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

$listado=pg_query($cn,"SELECT * FROM v_examen_ocupacional order by fecha desc");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr> 
                        <th>Oper.</th> 
                        <th>Fecha</th>  
                        <th>Cedula</th>                        
                        <th>Paciente</th>
                        <th>Sexo</th>
                        <th>Fecha Nac.</th>
                        <th>Edad</th>
                        <th>Fecha Ingreso</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Motivo</th>
                        <th>Paramedico Presente</th>
                        <th>Medico</th>                                                  
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
                               echo '<td><A id="act'.$idc.'" onclick="ventanaAct('.$idc.')" title="Ver Detalles '.$reg['ci'].'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';

                               echo '<A id="pln'.$idc.'" onclick="ventanaplani_cert('.$idc.')" title="Ver Certificado Medico '.$reg['ci'].'" href="#"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A></td>';


                               echo '<td>'.substr($reg['fecha'],0,16).'</td>';
                               echo '<td>'.$reg['ci'].'</td>';
                               echo '<td>'.$reg['paciente'].'</td>';
                               echo '<td>'.$reg['sexo'].'</td>';
                               echo '<td>'.$reg['fechanac'].'</td>';
                               echo '<td>'.$reg['edad'].'</td>';
                               echo '<td>'.$reg['fecha_ingreso'].'</td>';
                               echo '<td>'.$reg['departamento'].'</td>';
                               echo '<td>'.$reg['cargo'].'</td>';
                               echo '<td>'.$reg['motivo'].'</td>';
                               echo '<td>'.$reg['paramedico'].'</td>';
                               echo '<td>'.$reg['medico'].'</td>';                               
                               echo '</tr>';                               
                    }
                    ?>
                <tbody>
            </table>
 </div>
 
 <?php 
pg_free_result($listado);
?>
