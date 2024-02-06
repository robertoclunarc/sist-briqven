<script src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript"> 
function exportar_excel(){
var login=$("#login").val();
var ci=$("#txtCI").val();
var direccion=$("#cbodireccion").val();
var motivo=$("#cboMotivos").val();
var desde=$("#txtfdesde").val();
var hasta=$("#txtffin").val();
var namexls="control_acceso"+login+"_"+desde+"al"+hasta+".xls";
var inpt = '<table><thead><tr><th>'+'CONTROL DE ACCESO: '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

   $.ajax({
          type: "POST",
          url: "./buscar_consulta.php?enviar=NO",
          data: $("#formulario").serialize(),
          dataType: "html",
          success: function(data){
                $("#capa1").empty();
                $("#capa1").append(data);
                var link = document.createElement('a');
                document.body.appendChild(link); // Firefox requires the link to be in the body
                link.download = namexls;
                link.href = 'data:application/vnd.ms-excel,' + escape(inpt+data);
                link.click();
                document.body.removeChild(link);                
          }
    });   
}

function enviar_correo(){
   $.ajax({
          type: "POST",
          url: "./buscar_consulta.php?enviar=SI",
          data: $("#formulario").serialize(),
          dataType: "html",
          success: function(data){
                $("#capa1").empty();
                $("#capa1").append(data);                                
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
          url: "./buscar_consulta.php",
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
</script>
<?php 
require_once('./conexion.php');
session_start();
$cn=Conectarse();
$query="SELECT 
  v_acceso_personal_propio.cedula, 
  v_acceso_personal_propio.fecha_acceso, 
  v_acceso_personal_propio.direccion, 
  v_acceso_personal_propio.tipo_personal, 
  v_acceso_personal_propio.nombres, 
  v_acceso_personal_propio.cargo, 
  v_acceso_personal_propio.departamento, 
  v_acceso_personal_propio.jefe_inmediato, 
  v_acceso_personal_propio.usuario, 
  v_acceso_personal_propio.turno, 
  motivos.idmotivo, 
  motivos.descripcion_motivo
FROM 
  v_acceso_personal_propio, 
  motivos
WHERE 
  v_acceso_personal_propio.fkmotivo = motivos.idmotivo and v_acceso_personal_propio.fecha_acceso::date = NOW()::date
ORDER BY
  v_acceso_personal_propio.fecha_acceso";

$listado=pg_query($cn,$query);
$contar = pg_num_rows($listado);
?>
<form id="formulario" method='post'>
<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr>
<th> Lista de Entradas y Salidas de Personal Propio</th>
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
<td>CI Trabajador:</td>
<td><input id='txtCI' size="10" maxlength="10" name='txtCI' type='text' value=''/></td>
<td> &nbsp;</td>
<td>Direccion:</td>
<td>
  <select name="cbodireccion" id="cbodireccion" >
    <option value=""></option>
    <option value="ENTRADA">ENTRADA</option>
    <option value="SALIDA">SALIDA</option>
  </select>
</td>
<td> &nbsp;</td>
<td>Motivo:</td>
<td><select onfocus='CargarCombo($("#cboMotivos"),"./cargar_combo_db.php?tabla=v_motivos&campo1=idmotivo&campo2=descripcion_motivo&selected=0&orderby=idmotivo&firsttext=[Ninguno]")' name="cboMotivos" id="cboMotivos" ></select></td>
<td> &nbsp;</td> 
<td>
  <a onclick="consultar()" title="Consultar" href='#'><img src='./images/lupa.png' WIDTH='30' HEIGHT='30'></a></td>
<td> &nbsp;</td>
<td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='./images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a>
  <input id='login' name='login' type='hidden' value='<?php echo $_SESSION['user_session_ca']; ?>'/>
</td>
<!-- <td>&nbsp;</td>
<td><a onclick="enviar_correo()" title="Enviar al supervisor" href='#'><img src='./images/emailforward.png' WIDTH='30' HEIGHT='30'></a></td> -->
</tr>
</table>
</tool1>
<p>&nbsp;</p>
 <div id="capa1">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_accesos_perpro">
                <thead>
                    <tr>                        
                        <th>Cedula</th>                        
                        <th>Fecha</th>
                        <th>Direccion</th>
                        <th>Nombre</th>
                        <th>Tipo Personal</th>
                        <th>Cargo</th>
                        <th>Departamento</th> 
                        <th>Jefe</th>                                             
                        <th>Motivo</th>
                        <th>Turno</th>
                        <th>Usuario</th>                                  
                    </tr>
                </thead>
                <tfoot>
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
                    </tr>
                </tfoot>
                <tbody>                     
                  <?php
                  $aux=0;
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))                       
                   {  
                                    
                   ?>    
                      <tr>                        
                       <td><?php echo $reg['cedula']; ?></td>                             
                       <td><?php echo substr ($reg['fecha_acceso'],0, 16); ?></td>
                       <td><?php echo $reg['direccion']; ?></td>
                       <td><?php echo $reg['nombres']; ?></td>
                       <td><?php echo $reg['tipo_personal']; ?></td>
                       <td><?php echo $reg['cargo']; ?></td>
                       <td><?php echo $reg['departamento']; ?></td>
                       <td><?php echo $reg['jefe_inmediato']; ?></td>
                       <td><?php echo $reg['descripcion_motivo']; ?></td>
                       <td><?php echo $reg['turno']; ?></td>
                       <td><?php echo $reg['usuario']; ?></td>                                            
                      </tr>
                  <?php                                   
                    }
                   ?>
                <tbody>
            </table>
 </div>
 </form>  
<?php 
pg_free_result($listado);
?>
