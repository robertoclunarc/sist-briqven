<script src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript"> 
function exportar_excel(){
var login=$("#login").val();
var desde=$("#txtfdesde").val();
var hasta=$("#txtffin").val();
var namexls="morbilidad_"+login+"_"+desde+"al"+hasta+".xls";
var inpt = '<table><thead><tr><th>'+'MORBILIDAD: '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th><th>'+'Usuario: '+login+'</th></tr></thead></table>';

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
require("../include_conex.php");
session_start();
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
$query="SELECT * FROM v_morbilidad WHERE fecha::date = NOW()::date and login_atendio like '%".$_SESSION['user_session']."%'";
$listado = pg_query($cn,$query);
?>
<form id="formulario" method='post'> 
<tool1>
<table >
<tr>
<td>Fecha Desde:</td>
<td><input size="10" maxlength="10" id='txtfdesde' name='txtfdesde' type='date' value='<?php echo(date("Y-m-d")); ?>'/></td>
<td>Fecha Hasta:</td>
<td><input size="10" maxlength="10" id='txtffin' name='txtffin' type='date' value='<?php echo(date("Y-m-d")); ?>'/></td>
<td> &nbsp;</td> 
<td>Turno:</td> 
<td><select name="cboTurno" onfocus='CargarCombo($("#cboTurno"),"./cargar_combo_db.php?tabla=tbl_consulta&campo1=turno&selected=0&orderby=turno&firsttext=[Ninguno]")' id="cboTurno" ></select></td>
<td> &nbsp;</td>
<td>Paciente:</td>
<td><input id='txtCI' size="10" maxlength="10" name='txtCI' type='text' value=''/></td>
<td> &nbsp;</td>
<td>Area:</td>
<td><select name="cboArea" onfocus='CargarCombo($("#cboArea"),"./cargar_combo_db.php?tabla=tbl_areas&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]")' id="cboArea" ></td>
<td> &nbsp;</td>
<td>Motivo:</td>
<td><select onfocus='CargarCombo($("#cboMotivos"),"./cargar_combo_db.php?tabla=tbl_motivos&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguno]")' name="cboMotivos" id="cboMotivos" ></select></td>
<td> &nbsp;</td> 
<td>
  <a onclick="consultar()" title="Consultar" href='#'><img src='./images/lupa.png' WIDTH='30' HEIGHT='30'></a></td>
<td> &nbsp;</td>
<td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='./images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a>
  <input id='login' name='login' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
</td>
<td> &nbsp;</td>
<td><a onclick="enviar_correo()" title="Enviar al supervisor" href='#'><img src='./images/emailforward.png' WIDTH='30' HEIGHT='30'></a></td>
</tr>
</table>
</tool1>
 <p> &nbsp; </p>
 <div id="capa1">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_morbilidad">
                <thead>
                    <tr>
                      <th>Fecha/Hora</th>
                      <th>turno</th>                                              
                      <th>Nombres</th>  
                      <th>Cedula</th>                        
                      <th>Cargo</th>
                      <th>Supervisor</th>
                      <th>Area</th>
                      <th>Motivo</th> 
                      <th>P/S</th>                      
                      <th>Tipo Afeccion por Sist.</th>
                      <th>Diag.</th>
                      <th>Condicion | Observacion</th>
                      <th>Medicamento</th>                    
                      <th>Med. Ocupante</th> 
                      <th>Direccion Paciente</th>
                      <th>Mano Dominante</th>
                      <th>Sexo</th>
                      <th>Talla</th>
                      <th>Peso</th>
                      <th>IMC</th>
                      <th>Edad</th>
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
                    if ($reg['fktipoconsulta']==1)
                        $tipoconsulta='P';
                    else
                        $tipoconsulta='S';
                     echo '<tr>';       
                     echo '<td>'.substr($reg['fecha'],0,16).'</td>';
                     echo '<td>'.$reg['turno'].'</td>';
                     echo '<td>'.$reg['nombre_completo'].'</td>';
                     echo '<td>'.$reg['ci'].'</td>';
                     echo '<td>'.$reg['cargo'].'</td>';
                     echo '<td>'.$reg['nombres_jefe'].'</td>';
                     echo '<td>'.$reg['area'].'</td>';
                     echo '<td>'.$reg['motivo'].'</td>';
                     echo '<td>'.$tipoconsulta.'</td>';
                     echo '<td>'.$reg['resultado_eva'].'</td>';
                     echo '<td>'.$reg['descripcion_afeccion'].'</td>'; 
                     echo '<td>'.$reg['motivo_consulta'].'</td>';                     
                     echo '<td>'.$reg['aplicacion'].'</td>';                     
                     echo '<td>'.$reg['login_atendio'].'</td>';
                     echo '<td>'.$reg['direccion_hab'].'</td>';
                     echo '<td>'.$reg['mano_dominante'].'</td>';
                     echo '<td>'.$reg['sexo'].'</td>';
                     echo '<td>'.$reg['talla'].'</td>';
                     echo '<td>'.$reg['peso'].'</td>';
                     echo '<td>'.$reg['imc'].'</td>';
                      echo '<td>'.$reg['edad'].'</td>';
                     echo '</tr>';                               
                    }
                    ?>
                </tbody>
      </table>
 </div>
 </form>  
<?php 
pg_free_result($listado);
?>