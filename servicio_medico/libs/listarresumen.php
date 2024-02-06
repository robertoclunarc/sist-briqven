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
$query="select vc.patologia,vc.fktipoconsulta,count(*) as cantidad from v_consulta vc where fecha between '".date("Y-m")."-01' and '".date("Y-m-d")."' group by vc.patologia, vc.fktipoconsulta";

print $query;
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
<!--<td>Turno:</td> 
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
-->
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
      <table cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_morbilidad">
                <thead>
                    <tr>
                      <th rowspan="2">Patologia</th>
                      <th></th>
                      <th>Cantidad</th>  
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>                     
                    <?php     
                    $cont=1;
                    $inicio=true; 
                    $contador2=0;
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                  { 

                    if ($inicio){
                        $PA=$reg['patologia'];
                        $inicio=false;
                    }

                    $contador2++;
                    echo '<tr>'; 
                    if ($cont==1 && $PA==$reg['patologia']){
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        if ($reg['fktipoconsulta']==1) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=2;
                    }elseif ($cont==2 && $PA==$reg['patologia']){
                        echo '<td>S</td>';
                        if ($reg['fktipoconsulta']==2) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=1;
                        $inicio=true;
                    }elseif ($cont==1 && $PA!=$reg['patologia']){
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        if ($reg['fktipoconsulta']==1) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $PA=$reg['patologia'];
                        $cont=2;
                    }elseif($cont==2 && $PA!=$reg['patologia']){
                        echo '<td>S</td>';
                        if ($reg['fktipoconsulta']==2) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=1;                      
                    }

                    if ($PA!=$reg['patologia'] && $reg['fktipoconsulta']==NULL) {
                        echo '</tr>'; 
                        echo '<tr>'; 
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        if ($reg['fktipoconsulta']==1) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        echo '</tr>'; 
                        echo '<tr>'; 
                        echo '<td>S</td>';
                        echo '<td>0</td>';
                        echo '</tr>'; 
                        echo '<tr>'; 
                    }
                    echo '</tr>';                               
                  }
                  ?>
                </tbody>
      </table>
 </div>

<div id="capa2">
      <table cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_morbilidad">
                <thead>
                    <tr>
                      <th rowspan="2">Diagnostico</th>
                      <th></th>
                      <th>Cantidad</th>  
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>                     
                    <?php     
                    $cont=1;
                    $inicio=true; 
                    $contador2=0;
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                  { 

                    if ($inicio){
                        $PA=$reg['patologia'];
                        $inicio=false;
                    }

                    $contador2++;
                    echo '<tr>'; 
                    if ($cont==1 && $PA==$reg['patologia']){
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        if ($reg['fktipoconsulta']==1) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=2;
                    }elseif ($cont==2 && $PA==$reg['patologia']){
                        echo '<td>S</td>';
                        if ($reg['fktipoconsulta']==2) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=1;
                    }elseif ($cont==1 && $PA!=$reg['patologia']){
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        if ($reg['fktipoconsulta']==1) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $PA=$reg['patologia'];
                        $cont=2;
                    }elseif($cont==2 && $PA!=$reg['patologia']){
                        echo '<td>S</td>';
                        if ($reg['fktipoconsulta']==2) echo '<td>'.$reg['cantidad'].'</td>';
                        if ($reg['fktipoconsulta']==NULL) echo '<td>0</td>';
                        $cont=1;                      
                    }
                    if ($PA!=$reg['patologia'] && $reg['fktipoconsulta']==NULL) {
                        echo '</tr>'; 
                        echo '<tr>'; 
                        echo '<td rowspan="2">'.$reg['patologia'].'</td>';
                        echo '<td>P</td>';
                        echo '<td rowspan="2">'.$reg['cantidad'].'</td>';
                        echo '</tr>'; 
                        echo '<tr>'; 
                        echo '<td>S</td>';
                        echo '</tr>'; 
                        echo '<tr>'; 
                    }
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