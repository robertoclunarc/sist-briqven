<?php 
session_start();
?> 
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 
function solicitar(idm,tipomov,estatus,ret){
var r = confirm("Seguro Desea Cambiar a "+estatus+" La "+tipomov+" ID: "+idm+"?");
if (r == true) {
    dir_url = "validar_movimiento_db.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus+"&ret="+ret;
    if (estatus=='VALIDADO')
        {control="val_"+idm; document.getElementById(control).innerHTML="";}
    mostrar(control);       
    $.ajax({
       type: "POST",
       url: dir_url,
       data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
       success: function(data)
       {           
           //alert(data); // Mostrar la respuestas del script PHP.
           if (data=="0"){
             if (estatus=='VALIDADO')
                alert("La " + tipomov + " Fue "+estatus+" Correctamente!. \n\nSe Notificara por E-Mail al Area Solicitante.\n -");
                ocultar(control);
                notificar_vigil(idm,tipomov,estatus)              
            }
           else
           {
                alert("La operación Generó un Error:" + data);
                ocultar(control);
           }
            //location.reload(); //Recargar la página desde cero.            
       }
     });  
}  
}
//(*--------------------------------------------*)
function notificar_vigil(idm,tipomov,estatus){
    dir_url = "notificar_vigilantes.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus+"&nota=notificacion";
    $.ajax({
       type: "POST",
       url: dir_url,
       data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
       success: function(data)
       { 
           if (data!="0")
              alert(data);                        
       }
     });  
}
//(*--------------------------------------------*)
function mostrar(cdiv){
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="images/loading2.gif" alt="" height="25" width="25">';
$(cdiv).show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(cdiv){
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="" alt="" height="25" width="25">';
$(cdiv).hide();
$('#loading').hide();
}
//(*--------------------------------------------*)  
</script>
<?php 
require_once('./conexion.php');
$cn=Conectarse();

$query="SELECT distinct m.idmovimiento, a.nombres_agr, a.fechas_agr, a.unidades_agr, m.tipo_movimiento, m.estatus, m.fecha_hora, m.destino, m.tipo_movimiento, m.retorna, m.ciclo, i.descripcion_operacion FROM movimientos m, agrupamiento_operaciones a, items_autorizados i WHERE m.idmovimiento=a.fkmovimiento_agr AND m.objetivo_movimiento = i.iditem and  m.estatus in ('AUTORIZADO','VALIDADO') 
GROUP BY m.idmovimiento, a.nombres_agr, a.fechas_agr, a.unidades_agr, m.tipo_movimiento, m.estatus, m.fecha_hora, m.destino, m.tipo_movimiento, m.retorna, m.ciclo, i.descripcion_operacion
ORDER BY m.fecha_hora DESC;";

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
                        <th>Registro</th>
                        <th>Unidades</th>
                        <th>Fechas Op.</th>
                        <th>Destino</th>
                        <th>Tipo Mov.</th>
                        <th>Motivo</th> 
                        <th>Retorna</th>                                             
                        <th>Estatus Mov.</th>
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
                    </tr>
                </tfoot>
                <tbody>                     
                  <?php
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                      $idm=$reg['idmovimiento'];
                      $registro=str_replace('{', '', $reg['nombres_agr']);
                      $registro=str_replace('}', '', $registro);
                      $registro=str_replace('"', '', $registro);
                      $registro=str_replace(',','<br>',$registro);

                      $unidades_agr=str_replace('{', '', $reg['unidades_agr']);
                      $unidades_agr=str_replace('}', '', $unidades_agr);
                      $unidades_agr=str_replace('"', '', $unidades_agr);
                      $unidades_agr=str_replace(',','<br>',$unidades_agr);

                      $fechas_agr=str_replace('{', '', $reg['fechas_agr']);
                      $fechas_agr=str_replace('}', '', $fechas_agr);
                      $fechas_agr=str_replace('"', '', $fechas_agr);
                      $fechas_agr=str_replace(',','<br>',$fechas_agr);
                   ?>    
                      <tr>                                                                                 
                       <td>
                         <?php
                        if ($reg['estatus']=='VALIDADO' && $reg['ciclo']!='COMPLETADO'){
                        ?>    
                            <div style="float:left;"><A title="Retorno de Material <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="<?php echo 'retorno_movimiento.php?idm='.$idm; ?>"><IMG SRC="images/retorno.png" WIDTH="25" HEIGHT="25"></A></div>&nbsp;
                            
                     <?php
                       }
                        if ($_SESSION['nivel_ca']==4 && $reg['estatus']=='AUTORIZADO'){
                      ?>
                         <div style="float:left;" id="val_<?php echo $idm; ?>">&nbsp;<A onclick="solicitar(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','VALIDADO','<?php echo $reg['retorna']; ?>')" title="VALIDAR <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/validado.png" WIDTH="30" HEIGHT="25"></A></div>
                      <?php
                        }
                      ?>  
                       </td>
                       <td><?php echo $idm; ?></td>                             
                       <td><?php echo substr ($reg['fecha_hora'],0, 19); ?></td>
                       <td><?php echo $registro; ?></td>
                       <td><?php echo $unidades_agr; ?></td>
                       <td><?php echo $fechas_agr; ?></td>
                       <td><?php echo $reg['destino']; ?></td>
                       <td><?php echo $reg['tipo_movimiento']; ?></td>                       
                       <td><?php echo $reg['descripcion_operacion']; ?></td> 
                       <td><?php echo $reg['retorna']; ?></td>                               
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