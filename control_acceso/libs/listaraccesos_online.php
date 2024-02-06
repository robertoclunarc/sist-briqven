<script src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
////////////////////////////////////////////////////////////////////////////////////////////
function verpersonal(ced){
  window.open('verpersonal.php?ced='+ced,'popup','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=550,height=160')
}
//////////////////////////////////////////////////////////////////////////////////////////// 
function exportar_excel(){
    var login=$("#login").val();
    var ci=$("#txtCI").val();
    var direccion=$("#cbodireccion").val();
    //var motivo=$("#cboMotivos").val();
    var desde=$("#txtfdesde").val();
    var hasta=$("#txtffin").val();
    var namexls="control_acceso"+login+"_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'CONTROL DE ACCESO: '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

       $.ajax({
              type: "POST",
              url: "./buscar_consulta_online.php?enviar=SI",
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
//////////////////////////////////////////////////////////////////////////////////////////
function consultar(){
    $.ajax({
          type: "POST",
          url: "./buscar_consulta_online.php",
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
$conn=Conectarse_sitt();
$query="SELECT Cedula, Nombre, Cargo, TipodePersonal, Fecha_Inicidencia, Fecha, Hora,        
CASE   
      WHEN Dia='Monday' THEN 'Lunes'
      WHEN Dia='Tuesday' THEN 'Martes'
      WHEN Dia='Wednesday' THEN 'Miercoles'
      WHEN Dia='Thursday' THEN 'Jueves'
      WHEN Dia='Friday' THEN 'Viernes'
      WHEN Dia='Saturday' THEN 'Sabado'
      ELSE  'Domingo'
END as dia_semana,
CASE   
      WHEN Direccion='InDirection' THEN 'ENTRADA'
      ELSE  'SALIDA'
END as direction
FROM ccure_fichadas
where SUBSTRING( CONVERT(varchar, GETDATE(), 121) , 1, 4) = SUBSTRING(convert (varchar , fecha ), 1, 4)
and SUBSTRING( CONVERT(varchar, GETDATE(), 121) , 6, 2)  = SUBSTRING(convert (varchar , fecha ), 6, 2)
and SUBSTRING( CONVERT(varchar, GETDATE(), 121) , 9, 2) = SUBSTRING(convert (varchar , fecha ), 9, 2)
and SUBSTRING( CONVERT(varchar, GETDATE(), 121) , 9, 2) = SUBSTRING(convert (varchar , fecha ), 9, 2)
and Direccion='InDirection'
order by Fecha_Inicidencia desc";
$col =0;
$stmt = $conn->query($query);
//$stmt = $conn->prepare($query);
//$stmt->execute();
//$col = $stmt->fetchColumn();

?>
<form id="formulario" method='post'>
<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr>
<th>Lista de Entradas y Salidas de Personal Online</th>
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
    <option value="">TODAS</option>    
    <option selected="" value="InDirection">ENTRADA</option>
    <option value="OutDirection">SALIDA</option>
  </select>
</td>
<td> &nbsp;</td>
<td> &nbsp;</td>
<td> &nbsp;</td>
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
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Tipo Personal</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Dia</th> 
                        <th>Direccion</th>                                             
                                                         
                    </tr>
                </thead>
                
                <tbody>                     
                  <?php
                 while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {  $col++;                    
                   ?>    
                      <tr>                        
                       <td><?php echo $row['Cedula']; ?></td>                             
                       <td><A href="#" onclick="verpersonal(<?php echo $row['Cedula']; ?>)" ><?php echo $row['Nombre']; ?></A></td>
                        <td><?php echo $row['Cargo']; ?></td>
                        <td><?php echo $row['TipodePersonal']; ?></td>
                        <td><?php echo $row['Fecha']; ?></td>
                        <td><?php echo $row['Hora']; ?></td>   
                       <td><?php echo $row['dia_semana']; ?></td>
                       <td><?php echo $row['direction']; ?></td>
                                            
                      </tr>
                  <?php                                   
                    }
                   ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Cant. Reg.:</th>
                        <th><?php echo $col; ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        
                    </tr>
                </tfoot>
            </table>
 </div>
 </form>  
<?php 
//pg_free_result($listado);
?>
