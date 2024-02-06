<script src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript"> 
function exportar_pdf(target){
var login=$("#hddcontar").val();
var contar=$("#login").val();
var desde=$("#txtfdesde").val();
var hasta=$("#txtffin").val();
var namepdf="Consulta_"+login+"_"+desde+"al"+hasta+".pdf";


   $.ajax({
          type: "POST",
          url: "./buscar_consultaMov.php?pdf="+namepdf,
          data: $("#formulario").serialize(),
          dataType: "html",
          
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='./images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){
               if (data!="No se han encontrado resultados!"){
                  document.execCommand('SaveAs',true,'./ATTACHMENT/'+namepdf);
                  window.open('./ATTACHMENT/'+namepdf);
                  consultar();
               } else{
                  $("#capa1").empty();
                  $("#capa1").append(data);
               }                                                               
          }
    });   
}

  function CargarCombo(nombcombo, url)
{
  //alert ($(nombcombo).val());
  if ($(nombcombo).val()===null)  
    $.ajax(url).done(function(data){
        $(nombcombo).empty();
        $(nombcombo).append(data);      
        }
    );
} 

function consultar(){
///////////////////////////////////////////////////////////////////////////////////////
    $.ajax({
          type: "POST",
          url: "./buscar_consultaMov.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='./images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                $("#capa1").append(data);
                                                   
          }
    });       
/////////////////////////////////////////////////////////////////////////////////////
}

$(document).ready(function(){
 /* CargarCombo($("#cboUser"),"cargar_combo_db.php?tabla=v_llenar_combo_usuarios&campo1=nombres&selected=0&orderby=nombres&firsttext=[Usuarios Solicitantes]");
 */
});
</script>
<?php 
require_once('./conexion.php');
session_start();
$cn=Conectarse();
$query="SELECT 
  idmovimiento, 
  fecha_hora, 
  destino, 
  tipo_movimiento, 
  retorna, 
  fecha_retorno, 
  orden_compra, 
  cantidad, 
  serial_nro_almacen, 
  descripcion, 
  estatus, 
  nombre_destinatario, 
  nombre_contacto, 
  cedula_contacto, 
  objetivo_movimiento, 
  descripcion_operacion,
fecha_retornada ,
cantidad_retorno,
cantidad_restante,
ciclo,
nombres_agr,
unidades_agr
FROM 
  v_movimientos_equipos_retornos
WHERE 
  fecha_hora::date = NOW()::date and estatus='VALIDADO'";

$listado=pg_query($cn,$query);
$contar = pg_num_rows($listado);
?>
<form id="formulario" method='post'>
<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr>
<th> Lista de Entradas y Salidas de Materiales y Suministros</th>
</tr>
</table>
<p>&nbsp;</p>
<tool1>
<table >
<tr>
<td>Fecha Desde:</td>
<td><input size="10" maxlength="10" id='txtfdesde' name='txtfdesde' type='date' value='<?php echo(date("Y-m-d")); ?>'/></td>
<td>Fecha Hasta:</td>
<td><input size="10" maxlength="10" id='txtffin' name='txtffin' type='date' value='<?php echo(date("Y-m-d")); ?>'/></td>
<td> &nbsp;</td>
<td>Usuario:</td>
<td><select onfocus='CargarCombo($("#cboUser"),"cargar_combo_db.php?tabla=v_llenar_combo_usuarios&campo1=nombres&selected=0&orderby=nombres&firsttext=[Usuarios Solicitantes]")' name="cboUser" id="cboUser" > </select></td>
<td> &nbsp;</td>
<td>Retornado:</td>
<td>
  <select name="cboretorno" id="cboretorno" >
    <option value=""></option>
    <option value="SI">SI</option>
    <option value="NO">NO</option>
  </select>
</td>
<td> &nbsp;</td>
<td>Equipos:</td>
<td><input size="20" maxlength="20" id='txtequipo' name='txtequipo' type='text' value=''/></td> 
<td> &nbsp;</td>
<td>
  <a onclick="consultar(this.target)" title="Consultar" href='#'><img src='./images/lupa.png' WIDTH='30' HEIGHT='30'></a></td>
<td> &nbsp;</td>
<td><a onclick="exportar_pdf()" title="Exportar a PDF" href='#'><img src='./images/pdf-icon.png' WIDTH='30' HEIGHT='30'></a>

  <input id='login' name='login' type='hidden' value='<?php echo $_SESSION['user_session_ca']; ?>'/>
</td>
<!-- <td>&nbsp;</td>
<td><a onclick="enviar_correo()" title="Enviar al supervisor" href='#'><img src='./images/emailforward.png' WIDTH='30' HEIGHT='30'></a></td> -->
</tr>
<tr>
<td>Objetivo:</td>
<td colspan="16"><select onfocus='CargarCombo($("#cboMotivos"),"./cargar_combo_db.php?tabla=items_autorizados&campo1=iditem&campo2=descripcion_operacion&selected=0&orderby=iditem&firsttext=[Ninguno]")' name="cboMotivos" id="cboMotivos" ></select></td>

</tr>
</table>
</tool1>
<p>&nbsp;</p>
 <div id="capa1">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_accesos_perpro">
                <thead>
                    <tr style="background-color: #F0EAE8;">                        
                        <th><B>IDM.</B></th>                        
                        <th><B>Fecha</B></th>
                        <th><B>Tipo Mov.</B></th>
                        <th><B>Retorna</B></th>
                        <th><B>Destino</B></th>
                        <th><B>F. Retorno</B></th>
                        <th><B>estatus</B></th> 
                        <th><B>Orden Compra</B></th>                                           
                        <th><B>Cant.</B></th>
                        <th><B>Serial</B></th>
                        <th><B>Descripcion</B></th>
                        <th><B>Nombre Dest.</B></th>
                        <th><B>Nombre Contacto</B></th>
                        <th><B>C.I. Contacto</B></th>
                        <th><B>Operacion</B></th>
                        <th><B>Fecha Retornada</B></th>
                        <th><B>Cant. Retorno</B></th>
                        <th><B>Cant. Restante</B></th>
                        <th><B>Ciclo</B></th>
                        <th><B>Usuarios</B></th>
                        <th><B>Unidades</B></th>
                                                       
                    </tr>
                </thead>
               
                <tbody>                     
                  <?php
                  $i=1;
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))                       
                   {
                      $color=$i%2==0?'#F0EAE8':'#FCFBFA';
                      $i++;  
                      $registro=str_replace('{', '', $reg['nombres_agr']);
                      $registro=str_replace('}', '', $registro);
                      $registro=str_replace('"', '', $registro);
                      $registro=str_replace(',','<br>',$registro);

                      $unidades_agr=str_replace('{', '', $reg['unidades_agr']);
                      $unidades_agr=str_replace('}', '', $unidades_agr);
                      $unidades_agr=str_replace('"', '', $unidades_agr);
                      $unidades_agr=str_replace(',','<br>',$unidades_agr);                
                   ?>    
                      <tr style="background-color: <?php echo $color;  ?> ;" >
                       <td><?php echo $reg['idmovimiento']; ?></td>
                       <td><?php echo substr ($reg['fecha_hora'],0, 16); ?></td>
                       <td><?php echo $reg['tipo_movimiento']; ?></td>
                       <td><?php echo $reg['retorna']; ?></td>
                       <td><?php echo $reg['destino']; ?></td>
                       <td><?php echo $reg['fecha_retorno']; ?></td>
                       <td><?php echo $reg['estatus']; ?></td>
                       <td><?php echo $reg['orden_compra']; ?></td>
                       <td><?php echo $reg['cantidad']; ?></td>
                       <td><?php echo $reg['serial_nro_almacen']; ?></td>
                       <td><?php echo $reg['descripcion']; ?></td>                       
                        <td><?php echo $reg['nombre_destinatario']; ?></td>
                         <td><?php echo $reg['nombre_contacto']; ?></td>
                          <td><?php echo $reg['cedula_contacto']; ?></td>
                           <td><?php echo $reg['descripcion_operacion']; ?></td>
                            <td><?php echo $reg['fecha_retornada']; ?></td>
                             <td><?php echo $reg['cantidad_retorno']; ?></td>
                              <td><?php echo $reg['cantidad_restante']; ?></td>
                               <td><?php echo $reg['ciclo']; ?></td>
                               <td><?php echo $registro; ?></td>
                               <td><?php echo $unidades_agr; ?></td>
                                                                           
                      </tr>
                  <?php                                   
                    }
                   ?>
                </tbody> <tfoot>
                    <tr>
                        <th>Cant. Reg.:</th>
                        <th><?php echo $contar; ?></th>
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
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                      
                    </tr>
                </tfoot>
            </table>
            <input id='hddcontar' name='hddcontar' type='hidden' value='<?php echo $contar; ?>'/>
 </div>
 </form>  
<?php 
pg_free_result($listado);
?>
