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
    var ventana = window.open('./planilla_consulta.php?uid='+uid, "Planilla Consulta Medica", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	$(document).ready(function(){
	$.blockUI();
	setTimeout($.unblockUI(),9999);
    	}); 
     }
 }

function ingresartrabajador(){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(600/2);   
    var ventana = window.open('./insert/index.php','Trabajadores Nuevo Ingreso', "width=850,height=600,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
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
if ($_SESSION['nivel']==5)
  $listado=pg_query($cn,"SELECT * FROM consultas_pacientes WHERE motivo = 'PRE EMPLEO'");
else
  $listado=pg_query($cn,"SELECT * FROM consultas_pacientes WHERE login_atendio LIKE '%".$_SESSION['user_session']."%'");
?> 
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr> 
<?php if ($_SESSION['nivel']!=5){ ?><th>Oper.</th> <?php } ?>
                        <th>Fecha Cons.</th>  
                        <th>Cedula</th>                        
                        <th>Paciente</th>
                        <th>Sexo</th>
                        <th>Fecha Reg.</th>
                        <th>Edad</th>
                        <th>T. Sang.</th>
                        <th>Departamento</th>
                        <th>Motivo</th>
                        <th>Fec. Prox. Cons.</th>
                        <th>Login Ocupante(S)</th>                                                  
                    </tr>
                </thead>
                
                  <tbody>
                     
                    <?php
                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                      $idc=$reg['uid']; 
                               echo '<tr>';
                               if ($_SESSION['nivel']!=5){                               
                                    echo '<td><A id="act'.$idc.'" onclick="ventanaAct('.$idc.')" title="Ver Detalles '.$reg['ci'].'" href="#"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>';
                                    echo '<A href="consulta_registrada.php?idconsulta='.$idc.'" title="Ver Detalles Consulta Medica de '.$reg['paciente'].'"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A>';
                                    if ($reg['autorizacion']=='SI'){
                                      echo '<A href="autoriz.php?idconsulta='.$idc.'" target="_blank" title="Ver Autorizacion '.$reg['paciente'].'"><IMG SRC="images/autoriz.png" WIDTH="20" HEIGHT="20"></A>';                                      
                                    }
                                }
                               echo '</td>';				
                               echo '<td>'.substr($reg['fecha'],0,16).'</td>';
                               echo '<td>'.$reg['ci'].'</td>';
                               echo '<td>'.$reg['paciente'].'</td>';
                               echo '<td>'.$reg['sexo'].'</td>';
                               echo '<td>'.$reg['fecharegistro'].'</td>';
                               echo '<td>'.$reg['edad'].'</td>';
                               echo '<td>'.$reg['tipo_sangre'].'</td>';
                               echo '<td>'.$reg['departamento'].'</td>';
                               echo '<td>'.$reg['motivo'].'</td>';
                               echo '<td>'.$reg['fecha_prox_cita'].'</td>';
                               echo '<td>'.$reg['login_atendio'].'</td>';                               
                               echo '</tr>';
                               
                    }
                    ?>
                </tbody>
            </table>
 </div>
 
 <?php 
pg_free_result($listado);
?>