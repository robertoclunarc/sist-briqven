<?php 
session_start();
?> 
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 
function solicitar(idm,tipomov,estatus){
var r = confirm("Seguro Desea Cambiar a "+estatus+" La "+tipomov+" ID: "+idm+"?");
var control;
if (r == true) {
    dir_url = "confirmar_movimiento_db.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus;
    if (estatus=='SOLICITADO')
        {control="pend_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='CONFORMADO')
        {control="sol_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='AUTORIZADO')
        {control="aut_"+idm; document.getElementById(control).innerHTML="";}
    mostrar(control);
    $.ajax({
       type: "POST",
       url: dir_url,
       data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
       success: function(data)
       {           
           //alert(data); // Mostrar la respuestas del script PHP.
           if (data=="0"){
             if (estatus=='AUTORIZADO')
             {
                ocultar(control);
                alert("El Movimiento de " + tipomov + " Fue "+estatus+" Correctamente!. \n\nSe Envió por E-Mail al Area de Control de Acceso.\n -");
                notificar_vigil(idm,tipomov,estatus);
            }
              else
                ocultar(control);
                alert("El Movimiento de " + tipomov + " Fue "+estatus+" Correctamente!. \n\nSe Envió por E-Mail a su Jefe con Firma Autorizada.\n -");
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
function anular(idm,tipomov,estatus){
var r = prompt("Seguro Desea ANULAR La "+tipomov+" ID: "+idm+"? Explique:", "");
var control;
if (r != null){
    dir_url = "anular_movimiento.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus+"&r="+r;
    if (estatus=='SOLICITADO')
        {control="pend_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='CONFORMADO')
        {control="sol_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='AUTORIZADO')
        {control="aut_"+idm; document.getElementById(control).innerHTML="";}
    document.getElementById('null_'+idm).innerHTML="";  
    mostrar(control);
    $.ajax({
       type: "POST",
       url: dir_url,
       data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
       success: function(data)
       {           
           //alert(data); // Mostrar la respuestas del script PHP.
           if (data=="0"){
               ocultar(control);
               alert("El Registro Fue ANULADO!");
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
    dir_url = "notificar_vigilantes.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus;
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
require_once('../funciones_var.php');
$cn=Conectarse();
/*if ($_SESSION['nivel_ca']==2){
  $qccostos="SELECT dependientes FROM v_gerencias_generales WHERE ccosto_gral=".$_SESSION['unidad_ca'];
  $setccostos=pg_query($cn,$qccostos);
  $ccosto = array();  
  while($regccosto = pg_fetch_array($setccostos, null, PGSQL_ASSOC)){
    array_push($ccosto,$regccosto['dependientes']);    
  }
  $stnccosto=implode(",", $ccosto);
  pg_free_result($setccostos); 
}
*/
if ($_SESSION['nivel_ca']==1)
     $query="SELECT v_movimientos_1.*, nombres_agr, unidades_agr FROM v_movimientos_1, agrupamiento_operaciones WHERE idmovimiento=fkmovimiento_agr and login_participante = '".$_SESSION['user_session_ca']."' and estatus not in ('PENDIENTE','SOLICITADO');";
elseif ($_SESSION['nivel_ca']==2)
     $query="SELECT v_movimientos_1.*, nombres_agr, unidades_agr FROM v_movimientos_1, agrupamiento_operaciones WHERE idmovimiento=fkmovimiento_agr and login_participante = '".$_SESSION['user_session_ca']."' and estatus<>'PENDIENTE' and operacion<>'PENDIENTE';";
elseif ($_SESSION['nivel_ca']==4)
     $query="SELECT v_movimientos_1.*, nombres_agr, unidades_agr FROM v_movimientos_1, agrupamiento_operaciones WHERE idmovimiento=fkmovimiento_agr and estatus not in ('PENDIENTE','SOLICITADO','CONFORMADO');";   
   else 
     $query="SELECT v_movimientos_1.*, nombres_agr, unidades_agr FROM v_movimientos_1, agrupamiento_operaciones WHERE idmovimiento=fkmovimiento_agr and login_participante = '".$_SESSION['user_session_ca']."'";

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
                    </tr>
                </tfoot>
                <tbody>                     
                  <?php
                  $anulados = array('PENDIENTE', 'SOLICITADO', 'CONFORMADO');
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
                   ?>    
                      <tr>
                        <td> 
                   <?php
                        if (($reg['estatus']=='VALIDADO' || exist_usuario($idm, $_SESSION['user_session_ca'], $cn)) && ($reg['estatus']!='ANULADO')){
                    ?>                          
                            <div style="float:left;"><A target="_blank" title="Descargar PDF <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="<?php echo 'construir_pdf.php?idm='.$idm; ?>"><IMG SRC="images/pdf-icon.png" WIDTH="25" HEIGHT="25"></A></div>
                    <?php
                        }    
                       if ($_SESSION['nivel_ca']==3 && $_SESSION['unidad_ca']==$reg['ccosto'] &&  $reg['estatus']=='PENDIENTE'){
                    ?>
                           <div style="float:left;" id="pend_<?php echo $idm; ?>">&nbsp;<A onclick="solicitar(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','SOLICITADO')" title="Solicitar <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/pend.png" WIDTH="25" HEIGHT="25"></A></div>
                           <div style="float:left;" id="null_<?php echo $idm; ?>">&nbsp;<A onclick="anular(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','SOLICITADO')" title="Anular <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/nulo.png" WIDTH="25" HEIGHT="25"></A></div>
                    <?php
                        }
                        if ($_SESSION['nivel_ca']==2 && $_SESSION['unidad_ca']==$reg['ccosto'] &&  $reg['estatus']=='SOLICITADO'){
                    ?>
                        <div style="float:left;" id="sol_<?php echo $idm; ?>">&nbsp;<A onclick="solicitar(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','CONFORMADO')" title="CONFORMAR <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/check.png" WIDTH="25" HEIGHT="25"></A></div>
                        <div style="float:left;" id="null_<?php echo $idm; ?>">&nbsp;<A onclick="anular(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','CONFORMADO')" title="Anular <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/nulo.png" WIDTH="25" HEIGHT="25"></A></div>
                      <?php
                        }
                        if ($_SESSION['nivel_ca']==1 && $reg['estatus']=='CONFORMADO'){
                      ?>
                         <div style="float:left;" id="aut_<?php echo $idm; ?>">&nbsp;<A onclick="solicitar(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','AUTORIZADO')" title="AUTORIZAR <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/aut.png" WIDTH="25" HEIGHT="25"></A></div>
                         <div style="float:left;" id="null_<?php echo $idm; ?>">&nbsp;<A onclick="anular(<?php echo $idm; ?>,'<?php echo $reg['tipo_movimiento']; ?>','AUTORIZADO')" title="Anular <?php echo $reg['tipo_movimiento'].' ID: '.$idm; ?>" href="#"><IMG SRC="images/nulo.png" WIDTH="25" HEIGHT="25"></A></div>
                      <?php
                        }
                      ?>  
                       </td>
                       <td><?php echo $idm; ?></td>                             
                       <td><?php echo substr ($reg['fecha_hora'],0, 19); ?></td>
                       <td><?php echo $registro; ?></td>
                       <td><?php echo $unidades_agr; ?></td>
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
                <tbody>
            </table>
 </div> 
<?php
pg_free_result($listado);
?>