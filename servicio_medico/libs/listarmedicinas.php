<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadomedicinas.js"></script>

<?php 
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
$listado=pg_query($cn,"select tbl_medicamentos.*, case when activo = true then 'ACTIVO' else 'INACTIVO' end estatus from tbl_medicamentos order by descripcion");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
         <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_medicinas">
                <thead>
                    <tr> 
                        <th>uid</th> 
                        <th>Descripcion</th>                       
                        <th>Unid. Med.</th>
                        <th>Tipo</th>
                        <th>Existencia</th>
                        <th>Estatus</th>
                                                                       
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
                                                                          
                       echo '<td>'.$reg['uid'].'</td>';
                       echo '<td>'.$reg['descripcion'].'</td>';
                       echo '<td>'.$reg['unidad_medida'].'</td>';
                       echo '<td>'.$reg['tipo'].'</td>';
                       echo '<td>'.$reg['existencia'].'</td>';
                       echo '<td>'.$reg['estatus'].'</td>';                                
                       echo '</tr>';                               
                    }
                    ?>
                <tbody>
            </table>
 </div> 
<?php 
pg_free_result($listado);
pg_close($cn);
?>
