<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadopreempleo.js"></script>
<script type="text/javascript"> 
function ventanaAct(uid){ 
   location.href = "consulta_registrada.php?idconsulta="+data;
 } 
</script>
<?php 
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());

 $listado=pg_query($cn,"SELECT * FROM v_pre_empleo");

?> 
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_preempleo">
                <thead>
                    <tr> 
                       <?php if ($_SESSION['nivel']==1){  ?><th>Oper.</th><?php }  ?>
                        <th>Fecha Registro</th>  
                        <th>Cedula</th>                        
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha Ing.</th>
                        <th>Sexo</th>
                        <th>Cargo</th>
                        <th>Turno</th>
                        <th>Departamento</th>
                        <th>Centro Costo</th>
                        <th>Tipo Discapacidad</th>
                        <th>Desc. Discapacidad</th> 
                        <th>Alergia</th> 
                        <th>Tipo Sangre</th> 
                        <th>Telefono</th>                                                  
                    </tr>
                </thead>
                
                  <tbody>
                     
                    <?php
                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                      $idc=$reg['uid_paciente'];
                      $ced =$reg['ci'];
                               echo '<tr>';
                               if ($_SESSION['nivel']==1){                               
                                    echo '<td><A title="Registrar Historia '.$reg['ci'].'" href="historia_nueva.php?idp='.$idc.'&ced='.$ced.'"><IMG SRC="images/act.png" WIDTH="20" HEIGHT="20"></A></td>';
                                }				
                               echo '<td>'.substr($reg['fecharegistro'],0,16).'</td>';
                               echo '<td>'.$ced.'</td>';
                               echo '<td>'.$reg['nombre'].'</td>';
                               echo '<td>'.$reg['apellido'].'</td>';
                               echo '<td>'.$reg['fecha_ingreso'].'</td>';
                               echo '<td>'.$reg['sexo'].'</td>';
                               echo '<td>'.$reg['cargo'].'</td>';
                               echo '<td>'.$reg['turno'].'</td>';
                               echo '<td>'.$reg['descripcion'].'</td>';
                               echo '<td>'.$reg['ccosto'].'</td>';
                               echo '<td>'.$reg['tipo_discapacidad'].'</td>';
                               echo '<td>'.$reg['desc_discapacidad'].'</td>';
                               echo '<td>'.$reg['alergia'].'</td>';
                               echo '<td>'.$reg['tipo_sangre'].'</td>';
                               echo '<td>'.$reg['telefono'].'</td>';                                                             
                               echo '</tr>';
                               
                    }
                    ?>
                </tbody>
            </table>
 </div>
 
 <?php 
pg_free_result($listado);
?>
