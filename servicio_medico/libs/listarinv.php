<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadoinv.js"></script>

<?php 
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
$listado=pg_query($cn,"select * from tbl_mov_inventario order by fecha");
 ?> 
 <p> &nbsp; </p>
 <div id="capa1">
         <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_inv">
                <thead>
                    <tr> 
                        <th>IUD</th> 
                        <th>Tipo Op.</th>                       
                        <th>Concepto</th>
                        <th>Responsable</th>
                        <th>Fecha</th>
                                                                       
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
                       echo '<td>'.$reg['tipo_op'].'</td>';
                       echo '<td>'.$reg['concepto'].'</td>';
                       echo '<td>'.$reg['responsable'].'</td>';
                       echo '<td>'.$reg['fecha'].'</td>';                                
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
