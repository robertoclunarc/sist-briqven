<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  
  $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";
  
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $option.= "<option value='". $fila['trabajador']."'" ;
        $option.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";
    }
  } 
  pg_close($link);
  $fecha_actual = date("Y-m-d");
  $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

  $option1= "" ;
  $qry="select DISTINCT CICLO_LABORAL from dbo.adam_programacion_vacaciones order by 1 Desc";
  $cn=Conectarse_sitt();        
  $stmt1 = $cn->query($qry);
  
  while($fila = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
  {
     $option1.= "<option value='". $fila['CICLO_LABORAL']."'";
     $option1.= ">". $fila['CICLO_LABORAL']. "</option>";
  }
?>
<!DOCTYPE html>
<html lang="es">

<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cambio Vacacion</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.css">    
    
    <script src="../js/jquery-1.11.1.min.js"></script>

<script  language="javascript">
//(*--------------------------------------------*)
function consultar(){
    $.ajax({
          type: "POST",
          url: "buscar_vacaciones.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                $("#capa1").append(data);
                $("#txtfinicio").val($("#hddfechainivac").val());
                $("#txtffin").val($("#hddfechafinvac").val());
                $("#txtfpago").val($("#hddfechapagovac").val());

                $("#txtfpago").val($("#hddfechapagovac").val());
                $("#txtfpago").val($("#hddfechapagovac").val());

                $("#txtInterrupcionInicio").val($("#hddIniInterrup").val());
                $("#txtInterrupcionFin").val($("#hddFinInterrup").val());
                //$("#cboCondVac").val($("#hddcondicion").val());
                //$("#cboDispVac").val($("#hdddisposicion").val());
                $("#txtMotivo").val($("#hddmotivo").val());
                $("#txtsDiasPendientes").val($("#hddDiassPend").val());
               
                //estatus($("#hddestatusvac").val());

                if(typeof $("#hddestatusvac").val() === 'undefined'){
                    estatus('0')
                  } else {
                    estatus($("#hddestatusvac").val())
                  }
                  confCondicionDisposicion($("#hddcondicion").val(), $("#hdddisposicion").val())
               //$('#cboestatus').empty();

          }
    });
}
//(*--------------------------------------------*)
function SW_con_dias_habiles(){
    if (parseInt($("#txtsDias").val())>=0){                                   
         fecha_pago($("#txtfinicio").val());

    }

    /*if (fecha_ini_mayor($("#hddsFmin").val(), $("#txtfinicio").val())){      
       $("#txtffin").val(sumar_fecha($("#txtfinicio").val(),$("#txtsDias").val()));       
       return;
    }*/

    $.ajax({
          type: "POST",
          url: "SW_con_dias_habiles.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          /*beforeSend: function(){
            //imagen de carga
            $("#capa1").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");
            document.getElementById("cmdGuardar").disabled = true;
          },*/
          error: function(){
                alert("error petición ajax");
                document.getElementById("cmdGuardar").disabled = false;
          },
          success: function(data){                                                    
                eval(data);
                //$("#capa1").empty();
                //document.getElementById("cmdGuardar").disabled = false;                
          }
    });
}
//(*--------------------------------------------*)
function estatus(newEstatus){  
 
  switch(newEstatus) {
    case '0':
      $('#cboestatus').find('[value="NULL"]').remove();
      $('#cboestatus').selectpicker('refresh'); 
      $("#cboestatus").html('<option value="NULL">Seleccione el Estatus</option>').selectpicker('refresh');
      break;
    case '1':
      $("#cboestatus").html('<option value="1">Planificada</option><option value="2">Programada</option><option value="3">Disfrutada</option>').selectpicker('refresh');     
      $('#cboestatus').selectpicker('val', 1);
      $('#cboestatus').selectpicker('refresh');
      break;
    case '2':      
      $("#cboestatus").html('<option value="1">Planificada</option><option value="2">Programada</option><option value="3">Disfrutada</option>').selectpicker('refresh');     
      $('#cboestatus').selectpicker('val', 2);
      $('#cboestatus').selectpicker('refresh');
      break;
    case '3':      
      $("#cboestatus").html('<option value="3">Disfrutada</option>').selectpicker('refresh');
      $('#cboestatus').selectpicker('val', 3);
      $('#cboestatus').selectpicker('refresh');
      break;
  }  

}
//(*--------------------------------------------*)
function confCondicionDisposicion(cond, disp){ 
  
  $('#cboCondVac').selectpicker('refresh');
  $('#cboCondVac').selectpicker('val', cond);

  $('#cboDispVac').selectpicker('refresh');
  $('#cboDispVac').selectpicker('val', disp);

}

//(*--------------------------------------------*)
function fichar(){

if ($("#cbooperacion").val()=="NULL"){
    alert("Debe Seleccionar una Operacion");    
    return;
}
else
{
  if ($("#cbociclolaboral").val()=="NULL"){
    alert("Debe Seleccionar un Ciclo Laboral");    
    return;
  }

  if ($("#cbotrabajador").val()=="NULL"){
    alert("Debe Seleccionar un Trabajador");    
    return;
  }

  if ($("#cbooperacion").val()=="UPDATE")
  {

      if ($("#txtfinicio").val()==""){
        alert("Debe colocar la fecha de Inicio");            
        return;
      }

      if ($("#txtffin").val()==""){
        alert("Debe colocar la fecha fin");    
        return;
      }

      if ($("#txtfpago").val()==""){
        alert("Debe colocar la fecha pago");    
        return;
      }

      if ($("#cboestatus").val()=="NULL"){
        alert("Debe seleccionar el estatus");    
        return;
      }  

      if ($("#cboDispVac").val()=="NULL"){
        alert("Debe seleccionar la disposicion");    
        return;
      }

      if ($("#cboCondVac").val()=="NULL"){
        alert("Debe seleccionar una condicion");    
        return;
      }else{
            if ($("#cboCondVac").val()=="Reposo" || $("#cboCondVac").val()=="Pausada"){
                if ($("#txtInterrupcionInicio").val()==""){
                    alert("Debe colocar una fecha inicio de interrpcion");
                    $("#txtInterrupcionInicio").focus();    
                    return;
                }
                if ($("#txtInterrupcionFin").val()==""){
                    alert("Debe colocar una fecha fin de interrpcion");
                    $("#txtInterrupcionFin").focus();    
                    return;
                }

                if (check_in_range($("#txtfinicio").val(), $("#txtffin").val(), $("#txtInterrupcionInicio").val())==false){
                    alert("la fecha inicio de interrpcion esta fuera de rango de las vacaciones");
                    $("#txtInterrupcionInicio").focus();    
                    return;
                }
            }

      }
  }
}


$.ajax({
          type: "POST",
          url: "updatevacation_bd_sitt.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                consultar();
                alert(data);                          
          }
    });
}
//(*--------------------------------------------*)
function calcularInterrupcion(){
    if ($("#txtInterrupcionInicio").val()!="" && $("#txtffin").val()!=""){
        if (check_in_range($("#txtfinicio").val(), $("#txtffin").val(), $("#txtInterrupcionInicio").val())==false){
                    alert("la fecha inicio de interrpcion esta fuera de rango de las vacaciones");
                    $("#txtInterrupcionInicio").focus();    
                    return;
                }
                else{

                    diastranscurridos($("#txtInterrupcionInicio").val(), $("#txtffin").val());
                }
    }
}
//(*--------------------------------------------*)
function diastranscurridos(fechaIni, fechaFin){
var fechaInicio = new Date(fechaIni).getTime();
var fechaFin    = new Date(fechaFin).getTime();

var diff = (fechaFin - fechaInicio)/(1000*60*60*24);

$("#txtsDiasPendientes").val(diff);

//console.log(diff/(1000*60*60*24) );
}
//(*--------------------------------------------*)
function limpiarFechasInterrupcion() {
   if ($("#cboCondVac").val()=="Reposo" || $("#cboCondVac").val()=="Pausada"){
      $("#txtInterrupcionInicio").val($("#txtfinicio").val());
      sumar_fecha($("#txtfinicio").val(),7, '#txtInterrupcionFin')
      
   }
   else{
      $("#txtInterrupcionInicio").val('');
      $("#txtInterrupcionFin").val('');
   }
}
//(*--------------------------------------------*)
function check_in_range(date_start, date_end, date_now) {
   datestart = new Date(date_start).getTime();
   dateend = new Date(date_end).getTime();
   datenow = new Date(date_now).getTime();
   if ((datenow >= datestart) && (datenow <= dateend))
       return true;
   return false;
}
//(*--------------------------------------------*)
function sumar_fecha(fecha,dias, objeto){
    var sfecha = new Date(fecha);    
    sfecha.setDate(sfecha.getDate() + dias);
    var day=sfecha.getDate();
    var month=sfecha.getMonth()+1;
    const year=sfecha.getFullYear();
    if(month < 10)
      month="0" + month.toString();
    if (day < 10)
      day="0" + day.toString();
    //return year+"-"+month+"-"+day;
    $(objeto).val(year+"-"+month+"-"+day);  
    
}
//(* ---------------------------------------------*)
function fecha_pago(fecha){
    var dias = 0;
    var sfecha = new Date(fecha);    
    sfecha.setDate(sfecha.getDate() + dias);
    var day=sfecha.getDate()+1;
    var month=sfecha.getMonth()+1;
    var year=sfecha.getFullYear();
    
    if (day>=11 && day<=24)
    {      
      day=15;
    }      
    else{
        
         if (day>=25 && day<=31)
         {         
           day=30;           
         }
         else{
            if (month>1)             
              month=month-1;                      
            day=30;            
         }
    }  
    if (day==30){//calcula el ultimo dia del mes
      var date = new Date(year+"/"+month+"/"+day);
      var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
      day=ultimoDia.getDate();
      month=ultimoDia.getMonth()+1;
      year=ultimoDia.getFullYear();       
    }       

    if(month < 10)
      month="0" + month.toString();
    if (day < 10)
      day="0" + day.toString();
    
    $("#txtfpago").val(year+"-"+month+"-"+day);
}
//(* ---------------------------------------------*)
/*
$(document).ready(function(){	
	$("#chksobretiempo").change(function(){
	        if ($('#chksobretiempo').is(':checked')){
                        document.getElementById("chkasistio").checked = true;
                        document.getElementById("chkcomision").checked = false;
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
});
*/
//(*----------------------------------------------*)

</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - Cambio Vacacion</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Modificar Tiempo de Vacaciones</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del Personal
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 

                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="50%" class="table table-striped" border="0">
                  <tr>
                      <th width="10%"><label>Trabajador:</label></th>
                      <th colspan="3" width="80%"><select name="cbotrabajador" onchange="consultar()" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option; ?>
                              </select>
                      </th>        
                  </tr>
                  <tr>
                      <th><label>Ciclo Laboral:</label></th>
                      <th><select name="cbociclolaboral" onchange="consultar()" id="cbociclolaboral" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el Ciclo Laboral</option>
                                    <?php echo $option1; ?>
                              </select>
                      </th>
                      <th width="10%"><label>Estatus:</label></th>
                      <th width="10%"><div id="divestatus"><select name="cboestatus" id="cboestatus" data-width="100%" data-size="4" class="selectpicker">
                              
                                   
                              </select></div></th>
                  </tr>
                  <tr>
                        <th width="10%"><label>Fecha: Inicio</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange="sumar_fecha($(this).val(), 30, $(txtffin)); fecha_pago($(this).val()); $(txtsDias).val(30)" value="" width="10" class="form-control"/></th>
                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" value="" width="10" class="form-control"/></th>
                  </tr>
                  <tr>
                        <th><label>Disposicion</label></th>
                        <th><select name="cboDispVac" id="cboDispVac" data-width="100%" data-size="4"  class="selectpicker">
                              <option  selected value="NULL">Sel. disposicion vacacion</option>
                              <option  value="Pago con distrute">Pago con Distrute</option>
                              <option  value="Solo Disfrute">Solo Disfrute</option>
                              <option  value="Solo Pago">Solo Pago</option>      
                              </select></th>
                        <th><label>Condicion</label></th>
                        <th><select name="cboCondVac" id="cboCondVac" data-width="100%" data-size="4" onchange="limpiarFechasInterrupcion(); calcularInterrupcion()"  class="selectpicker">
                              <option   selected value="NULL">Sel. condicion vacacion</option>
                              <option  value="Planificada">Planificada</option>
                              <option  value="Programada">Programada</option>
                              <option  value="Disfrute">Disfrute</option> 
                              <option  value="Reposo">Reposo</option>
                              <option  value="Pausada">Pausada</option>
                              <option  value="Finalizada">Finalizada</option>      
                              </select>
                        </th>
                        
                  </tr>
                  <tr>
                        <th width="10%"><label>Incidencia: Inicio</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtInterrupcionInicio" name="txtInterrupcionInicio" onchange="calcularInterrupcion()" value="" width="10" class="form-control"/></th>
                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtInterrupcionFin" name="txtInterrupcionFin" value="" width="10" class="form-control"/></th>
                  </tr>
                  <tr>
                        <th><label>Dias Vacaciones</label></th>
                        <th><INPUT type="text" maxlength="2" size="10" id="txtsDias" name="txtsDias" value="" width="10" class="form-control"/></th>
                        <th><label>Dias Pendientes</label></th>
                        <th><INPUT type="text" maxlength="2" size="10" id="txtsDiasPendientes" name="txtsDiasPendientes" value="" width="10" class="form-control"/></th>
                        
                        
                  </tr>
                  <tr>
                        
                        <th><label>Desc. Incidencia</label></th>
                        <th colspan="3"><INPUT type="text" maxlength="50" size="10" id="txtMotivo" name="txtMotivo" value="" width="10" class="form-control"/>
                        </th>
                        
                  </tr>
                  <tr>
                        <th width="10%"><label>Fecha Pago:</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtfpago" name="txtfpago" value="" width="10" class="form-control"/></th>
                        <th width="10%"><label>Operacion:</label></th>
                        <th width="10%"><select name="cbooperacion" id="cbooperacion" data-width="100%" data-size="5"class="selectpicker">
                              <option selected value="NULL">Seleccione la Operacion</option>
                              <option  value="UPDATE">Actualizar</option>
                              <option  value="DELETE">Eliminar</option>                              
                              </select></th>
                  </tr>
                  <tr>
                        
                        <th width="10%"><INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="fichar();"/></th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                                         
                  </tr>                  
					      </table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                      <div id="capa1" class="col-lg-12">
                                       
                                      </div>
                                     
                                    </form>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <script src="../bootstrap/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>

</body>

</html>
<?php 

}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
