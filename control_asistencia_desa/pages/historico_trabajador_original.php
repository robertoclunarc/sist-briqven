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
  if ($_SESSION['nivel_const']==1 || $acceso){
      $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM trabajadores order by nombre";
  }elseif ($_SESSION['nivel_const']==2) {
      $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM trabajadores WHERE trabajador_sup='".$_SESSION['cedula_session_const']."' order by nombre";
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
  
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Control de Asistencia</title>
    
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

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>

<script  language="javascript">
//(*--------------------------------------------*)
function consultar(){
  document.getElementById("conFoto").value=true;
    $.ajax({
          type: "POST",
          url: "historico_trabajador_datos_personales.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa0").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa0").empty();
                //$("#capa1").empty();
                $("#capa0").append(data);    
               // dataTable0();      
          }
    });
}
//(*--------------------------------------------*)
function historico_medidas_disciplinarias(){
  //alert('PASO');
    $.ajax({
          type: "POST",
          url: "historico_trabajador_medidas_disciplinarias.php",
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
                //eval(data); 
                $("#capa1").empty();
                $("#capa1").append(data);
                dataTable1();
          }
    });
}

//(*--------------------------------------------*)
function historico_reposos(){
  //alert('PASO');
    $.ajax({
          type: "POST",
          url: "historico_trabajador_reposos.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //eval(data); 
                $("#capa2").empty();
                $("#capa2").append(data);
                dataTable2();
          }
    });
}

//(*--------------------------------------------*)
function historico_permisos(){
  //alert('PASO');
    $.ajax({
          type: "POST",
          url: "historico_trabajador_permisos.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa3").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //eval(data); 
                $("#capa3").empty();
                $("#capa3").append(data);
                dataTable3();
          }
    });
}

//(*--------------------------------------------*)
function historico_ausencias(){
  //alert('PASO');
    $.ajax({
          type: "POST",
          url: "historico_trabajador_ausencias_injustificadas.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa4").html("<p align='center'><img width='35px' height='35px' src='images/loading.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //eval(data); 
                $("#capa4").empty();
                $("#capa4").append(data);
                dataTable4();
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
      <IMG SRC="images/header.jpg" width="100%" height="150px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - Historico</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Hist&oacute;rico del trabajador</h4>
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
                                      <INPUT type="hidden" id="conFoto" name="conFoto" value="false"/>                                  

                                      <table width="50%" class="table table-striped" >
                                        <tr>
                                            <th width="10%"><label>Trabajador:</label></th>
                                            <th colspan="3" width="80%"><select name="cbotrabajador" onchange="consultar(); historico_permisos(); historico_ausencias(); historico_reposos(); historico_medidas_disciplinarias();" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                                    <option selected value="NULL">Seleccione el trabajador</option>
                                                          <?php echo $option; ?>
                                                    </select>
                                                    
                                            </th>        
                                        </tr>
                                      </table>
                                      <div id="capa0">
                                        <table width="50%" class="table table-striped" id="dataTables-example0">
                                          <tr>
                                              <th colspan="2" rowspan="3"><label></label></th>
                                              <th><label>Fecha de Nacimiento:</label></th>
                                              <th></th>      
                                          </tr>
                                          <tr>
                                                <th><label>Edad:</label></th>
                                                <th></th>
                                          </tr>      
                                          <tr>
                                                <th><label>Sexo: </label></th>
                                                <th></th>
                                          </tr>
                                          <tr>
                                                <th><label>Fecha de Ingreso</label></th>
                                                <th></th>
                                                <th><label>Antiguedad:</label></th>
                                                <th></th>
                                          </tr>
                                          <tr>
                                                <th><label>Cargo:</label></th>
                                                <th colspan="3"></th>
                                          </tr>
                                          <tr>                        
                                                <th><label>Centro de Costo:</label></th>
                                                <th colspan="3"></th>
                                          </tr>
                                          <tr>                        
                                                <th><label>Supervisor Inmediato:</label></th>
                                                <th colspan="3"></th>
                                          </tr>                  
                                        
                                        </table>
                                      </div>						
                                   </div>
                                   <div id="capa1" class="col-lg-6">
                                      <b>Hist&oacute;rico de Medidas Disciplinarias</b>
                                      <table class="table table-striped" id="dataTables-example1">
                                          <tr>
                                              <th><label>Num.</label></th>
                                              <th><label>Inicio</label></th>
                                              <th>Fin</th>      
                                              <th>Aplicaci&oacute;n</th>
                                              <th>Tipo</th>
                                              <th>Causa</th>
                                              <th>Observaciones</th>
                                          </tr>
                                          <tr>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>      
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                          </tr>      
                                        </table>    
                                   </div>
                                   <div>&nbsp;<br></div>
                                   <div id="capa2" class="col-lg-6">
                                      <b>Hist&oacute;rico de Reposos</b>
                                      <table class="table table-striped" id="dataTables-example2" >
                                          <tr>
                                              <th>Num.</th>
                                              <th>Inicio</th>
                                              <th>Fin</th>      
                                              <th>Nro. d&iacute;s</th>
                                              <th>C&oacute;digo</th>
                                              <th>Causa</th>
                                              <th>Observaciones</th>
                                          </tr>
                                          <tr>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>      
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                          </tr>      
                                        </table>
                                   </div>
                                   <div>&nbsp;<br></div>
                                   <div id="capa3" class="col-lg-6">
                                      <b>Hist&oacute;rico de Permisos</b>
                                      <table class="table table-striped" id="dataTables-example3" >
                                          <tr>
                                              <th>Num.</th>
                                              <th>Inicio</th>
                                              <th>Hora Ini.</th>      
                                              <th>Fin</th>
                                              <th>Hora Fin</th>
                                              <th>C&oacute;digo</th>
                                              <th>Causa</th>
                                              <th>Observaciones</th>
                                          </tr>
                                          <tr>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>      
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                          </tr>      
                                        </table>
                                   </div>
                                   <div>&nbsp;<br></div>
                                   <div id="capa4" class="col-lg-6">
                                      <b>Hist&oacute;rico Ausencias Injustificadas</b>
                                      <table class="table table-striped" id="dataTables-example4">
                                          <tr>
                                              <th>A&ntilde;o</th>
                                              <th>Cantidad <br>d&iacute;s</th>
                                          </tr>
                                          <tr>
                                              <th>&nbsp;</th>
                                              <th>&nbsp;</th>
                                            </tr>      
                                        </table>                                       
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
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <script>
  /*  function dataTable0() {
        //DATOS PERSONALES  
        $('#dataTables-example0').DataTable({
             responsive: true,
             "order": [[ 1, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": true,
             "searching": false,
        });   
    }
*/    
    function dataTable1() {     
        //MEDIDAS DISCIPLINARIAS
        $('#dataTables-example1').DataTable({
             responsive: true,
             "order": [[ 0, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": false,
             "searching": false, //elimina la busqueda
             "info":     false,  //elimina el label del Showing 1 to 10 of 21 entries
             "paging":   false, //elimina el paginado 
             "lengthChange": false
        }); 
    }    
    
    function dataTable2() {           
        // HISTORICO DE REPOSOS
        $('#dataTables-example2').DataTable({
             responsive: true,
             "order": [[ 0, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": false,
             "searching": false,  //elimina la busqueda
             "info":     false,  //elimina el label del Showing 1 to 10 of 21 entries
             "paging":   false, //elimina el paginado              
             "lengthChange": false
        }); 
    }    
    
    function dataTable3() {           
        //HISTORICO DE PERMISOS
        $('#dataTables-example3').DataTable({
             responsive: true,
             "order": [[ 0, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": false,
             "searching": false,
             "info":     false,
             "paging":   false,
             "lengthChange": false
        });   
      }    
    function dataTable4() {           
        //HISTORICO AUSENCIAS INJUSTIFICADAS
        $('#dataTables-example4').DataTable({
             responsive: true,
             "order": [[ 0, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": false,
             "searching": false,
             "info":     false,  //elimina el label del Showing 1 to 10 of 21 entries
             "paging":   false, //elimina el paginado 
             "lengthChange": false
        }); 
     };
     
    </script>
</body>

</html>
<?php
pg_close($link); 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
