<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link, 'CARGAR', 'insertvacation.php', $_SESSION['user_session_const']);
  $acceso2=permiso_usuario($link, 'NOUPDATE', 'insertvacation.php', $_SESSION['user_session_const']);
  if ($_SESSION['nivel_const']==1  || $acceso || $acceso2){

      $query="SELECT trabajador, nombre FROM adam_vw_dotacion_briqven_02_mas order by nombre";
  }elseif ($_SESSION['nivel_const']==2) {
      $query="SELECT trabajador, nombre FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup='".$_SESSION['cedula_session_const']."' order by nombre";
  }   
  
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

    <title>Cargar Vacacion</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">
     
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>

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
                $("#capa2").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa2").empty();
                $("#capa2").append(data);          
          }
    });
}
//(*--------------------------------------------*)
function SW_con_vacacion_proximo_ciclo(){
    $.ajax({
          type: "POST",
          url: "SW_con_vacacion_proximo_ciclo.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                eval(data); 
                $("#capa1").empty();
                
                if (parseInt($("#txtsDias").val())>=0){ 
                                  
                  fecha_pago($("#txtfinicio").val()); 

                }
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
          beforeSend: function(){
            //imagen de carga
            $("#capa1").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");
            document.getElementById("cmdGuardar").disabled = true;
          },
          error: function(){
                alert("error petición ajax");
                document.getElementById("cmdGuardar").disabled = false;
          },
          success: function(data){                                                    
                eval(data);
                $("#capa1").empty();
                document.getElementById("cmdGuardar").disabled = false;                
          }
    });
}
//(*--------------------------------------------*)
function fecha_ini_mayor(fechaini, fechafin){
var dat = false;
  if(new Date(fechaini).getTime() > new Date(fechafin).getTime())
  {
        dat= true;
  } 
  return dat;
}
//(*--------------------------------------------*)
function sumar_fecha(fecha,dias){
    var sfecha = new Date(fecha);    
    sfecha.setDate(sfecha.getDate() + dias);
    var day=sfecha.getDate();
    var month=sfecha.getMonth()+1;
    const year=sfecha.getFullYear();
    if(month < 10)
      month="0" + month.toString();
    if (day < 10)
      day="0" + day.toString();
    return year+"-"+month+"-"+day;
    
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
//(*--------------------------------------------*)
function graba(){

if ($("#txtciclolaboral").val()=="NULL"){
  alert("Debe Tener un Ciclo Laboral");    
  return;
}

if ($("#cbotrabajador").val()=="NULL"){
  alert("Debe Seleccionar un Trabajador");    
  return;
} 

if ($("#cboTipoVac").val()=="NULL"){
  alert("Debe Seleccionar un tipo de vacacion");    
  return;
}

if ($("#txtciclolaboral").val()==""){
  alert("Debe Seleccionar un Ciclo Laboral");    
  return;
}

if ($("#txtsDias").val()=="" || parseInt($("#txtsDias").val())==0){
  alert("Debe Seleccionar los dias de Vacaciones");    
  return;
}

if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val())){
  alert("la fecha inicio es mayor a la fecha fin");    
  return;
} 

if (fecha_ini_mayor($("#txtfpago").val(), $("#txtffin").val())){
  alert("la fecha pago no puede ser mayor a la fecha fin");    
  return;
} 

$.ajax({
          type: "POST",
          url: "SW_GRABAR_PLAN_VACACIONES.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img  src='images/loading.gif' /></p>");
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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - Cargar Vacacion</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Cargar de Vacaciones</h4>
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

				        <table width="50%" class="table table-striped" border="0">
                  <tr>
                      <th width="10%"><label>Trabajador:</label></th>
                      <th colspan="3" width="80%"><select name="cbotrabajador" onchange="consultar(); SW_con_vacacion_proximo_ciclo()" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option; ?>
                              </select>
                      </th>        
                  </tr>
                  <tr>
                      <th><label>Ciclo Laboral:</label></th>
                      <th><INPUT type="text" readonly maxlength="10" size="10" id="txtciclolaboral" name="txtciclolaboral" value="" width="10" class="form-control"/>
                      </th>
                      <th width="10%"><label>Tipo Vacacion:</label></th>
                      <th width="10%"><select name="cboTipoVac" id="cboTipoVac" data-width="100%" data-size="4" onchange="SW_con_dias_habiles()" class="selectpicker">
                              <option  value="NULL">Sel. Tipo Vacacion</option>
                              <option selected value="CONTRA">CONTRACTUAL</option>
                              <option  value="LEGAL_SDA">LEGAL SIN DIAS ADICIONALES</option>
                              <option  value="LEGAL_CDA">LEGAL CON DIAS ADICIONALES</option>      
                              </select></th>      
                  </tr>
                  <tr>
                        <th width="10%"><label>Fecha: Inicio</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" value="" width="10" onchange="SW_con_dias_habiles()" class="form-control"/></th>
                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" readonly maxlength="10" size="10" id="txtffin" name="txtffin" value="" width="10" class="form-control"/></th>
                  </tr>
                  <tr>
                        <th><label>Dias</label></th>
                        <th><INPUT type="text" readonly maxlength="2" size="10" id="txtsDias" name="txtsDias" value="" width="10" class="form-control"/></th>
                        <th><label>Fecha Pago</label>
                          <input name='hddsFmin' id='hddsFmin' type='hidden' value=''/></th>
                        <th><INPUT type="date" maxlength="10" size="10" id="txtfpago" name="txtfpago" value="" width="10"  class="form-control"/>
                          <input name='hddsFmax' id='hddsFmax' type='hidden' value=''/>
                            <input name='hddTipoVac' id='hddTipoVac' type='hidden' value='CONTRA'/>
                        </th>
                        
                  </tr>
                  
                  <tr>
                        
                        <th width="10%">
                          <?php   if ($_SESSION['nivel_const']==1  || $acceso ){ ?>
                          <INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="graba();"/>
                          <?php } ?>
                        </th>
                        <th width="10%">
                          <div id="capa1">                                       
                          </div>
                        </th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                                         
                  </tr>                  
					      </table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                      <div id="capa2" class="col-lg-12">
                                       
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
