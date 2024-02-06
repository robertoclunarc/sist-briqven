<?php 
session_start();
?> 
<script type="text/javascript" language="javascript" src="./js/jslistadomovequipos.js"></script>

<?php 
require_once('./conexion.php');
//require_once('../funciones_var.php');
$cn=Conectarse();
$query="SELECT 
  detalles_movimientos.fkmovimiento, 
  detalles_movimientos.cantidad, 
  detalles_movimientos.serial_nro_almacen, 
  detalles_movimientos.descripcion, 
  detalles_movimientos.items, 
  detalles_movimientos.unidad_medicion, 
  movimientos.fecha_hora, 
  movimientos.destino, 
  movimientos.tipo_movimiento, 
  movimientos.retorna, 
  movimientos.fecha_retorno, 
  items_autorizados.descripcion_operacion, 
  movimientos.ciclo, 
  movimientos.estatus
FROM 
  public.detalles_movimientos, 
  public.movimientos, 
  public.items_autorizados
WHERE 
  detalles_movimientos.fkmovimiento = movimientos.idmovimiento AND
  items_autorizados.iditem = movimientos.objetivo_movimiento AND
  movimientos.idmovimiento IN (SELECT fkmovimiento_part FROM usuarios_movimientos WHERE
ccosto = ".$_SESSION['unidad_ca']."  OR login_participante = '".$_SESSION['user_session_ca']."') ORDER BY detalles_movimientos.fkmovimiento DESC, detalles_movimientos.items;";

$listado=pg_query($cn,$query);
?> 
<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr>
<th> Lista de Entradas y Salidas de Materiales, Equipos y Herramientas</th>
</tr>
</table>
 <p>&nbsp;</p> 
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr>  
                        <th>Oper.</th>  
                        <th>ID Mov.</th>                        
                        <th>Fecha Registro</th>
                        <th>Equipo</th>
                        <th>Cantidad</th>
                        <th>Medicion</th>
                        <th>Serial/Vale Almacen</th>
                        <th>Destino</th> 
                        <th>Tipo Movimiento</th>                                             
                        <th>Retorna</th>
                        <th>Fecha Retorno</th>
                        <th>Motivo</th>
                        <th>Estatus</th>
                        <th>Ciclo</th>          
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>                     
                  <?php
                  $aux=0;
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))                       
                   {  
                      $idm=$reg['fkmovimiento'];                      
                   ?>    
                      <tr>
                        <td>
                           <?php
                              if ($aux!=$idm){
                            ?>             
                            <div style="float:left;"><A target="_blank" title="Descargar PDF <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="<?php echo 'construir_pdf.php?idm='.$idm; ?>"><IMG SRC="images/pdf-icon.png" WIDTH="25" HEIGHT="25"></A></div>
                            <?php
                              }
                              $aux=$idm;
                            ?>                    
                       </td>
                       <td><?php echo $idm; ?></td>                             
                       <td><?php echo substr ($reg['fecha_hora'],0, 19); ?></td>
                       <td><?php echo $reg['descripcion']; ?></td>
                       <td><?php echo $reg['cantidad']; ?></td>
                       <td><?php echo $reg['unidad_medicion']; ?></td>
                       <td><?php echo $reg['serial_nro_almacen']; ?></td>
                       <td><?php echo $reg['destino']; ?></td>
                       <td><?php echo $reg['tipo_movimiento']; ?></td>
                       <td><?php echo $reg['retorna']; ?></td>
                       <td><?php echo $reg['fecha_retorno']; ?></td>
                       <td><?php echo $reg['descripcion_operacion']; ?></td>
                       <td><?php echo $reg['estatus']; ?></td>
                       <td><?php echo $reg['ciclo']; ?></td>                       
                      </tr>
                  <?php                                   
                    }
                   ?>
                </tbody>
            </table>
 </div> 
<?php
pg_free_result($listado);
?>