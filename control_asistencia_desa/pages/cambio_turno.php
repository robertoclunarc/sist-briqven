<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $option = llenar_combo_trabajadores('ct');
	
  $fecha_actual = date("Y-m-d");
  $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));
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

    <title>Registrar de Cambio de Turno</title>
    
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
if ($("#cbotrabajador").val()!="NULL"){
     CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");
    $.ajax({
          type: "POST",
          url: "cambio_turno2_online.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
                $("#txtobservacion").val(""); 
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                $("#capa1").append(data);
          }
    });
}
}

//(*--------------------------------------------*)
function fichar(){
if ((($("#cbotrabajador").val())!='') && ($("#cbotrabajador").val())!='0' && (($("#cbotrabajador").val())!=null) ){
	// alert($("#cboaccion").val());
	// alert($("#cboesperanza_cambiada").val());
	if ($("#cboaccion").val()==''){ 
                alert('Debe seleccionar la Accion se desea se le aplique a al trabajador');
                $("#cboaccion").focus();
                return;
            }
	if ($("#cboesperanza_cambiada").val()=='0'){ 
                alert('Debe seleccionar el turno que asistio al trabajador');
                $("#cboesperanza_cambiada").focus();
                return;
            }
	/*if ($("#txtobservacion").val()==''){ 
                alert('Debe ingresar el motivo por el cual el trabajador vino en un turno distinto a la esperanza');
                $("#txtobservacion").focus();
                return;
            }
*/
$.ajax({
          type: "POST",
          url: "cambio_turno_db.php",
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
                if (data==1){
                    $("#capa1").empty();
                    consultar();
                    alert('Cambio de clave realizado con exito');                          
                    //window.locationf="index.php"; 
                }else{
                    //$("#mensaje").remove();
                    //$("#mensaje").append('<div class="alert alert-danger">'+data+'</div>');
                    //$("#sp"+ndiv).html(botton);
                    alert(data);
                }            
          /*************** /

                $("#capa1").empty();
                consultar();
                alert(data);                          
		            window.locationf="index.php";	*/
          }
    });
  }
}
//(*--------------------------------------------*)
function CargarCombo(nombcombo, url)
{
//alert(nombcombo+' - '+url);
      $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
//      eval(data);
      }
  );  
}


//(* ---------------------------------------------*)
$(document).ready(function(){	
     CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");
});

//(*----------------------------------------------*)
</script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">   
                <img width="150px" src="../images/logotransparente.png">              
                <a class="navbar-brand" href="index.php">Sist. Control de Asistencia</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">Reportar Cambio de Turno</h5>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del Personal que cambio de turno
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-12">    
                                      <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                      <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                      <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 
                                      <input  name='cboaccion' id='cboaccion' type='hidden' value='1'/>
                                      <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>
                      				        <table width="50%" class="table table-striped" border="0">
                                    			<tr>
                                          		<th width="10%"><label>Fecha: </label></th>
                                          		<th width="80%" ><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange="consultar();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
                                    			</tr>  
                                    			<tr>
                                        		<th width="10%"><label>Trabajador:</label></th>
                                        		<th width="80%">
                  							              <select name="cbotrabajador" onchange="consultar()" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                                	<option selected value="NULL">Seleccione el trabajador</option>
                                                  <?php echo $option; ?>
                                              </select>
                                        		</th>        
                                    			</tr>
                  					              <tr>
                                            <th width="25%"><label>Turno que asistio:</label></th>
                                            <th><select id="cboesperanza_cambiada" name="cboesperanza_cambiada" class="form-control"  data-live-search="true"> </select>
                                              </th>
                                          </tr>
                                    			<!--<tr>
                                         				<th width="10%"><label>Motivo:</label></th>
                                          			<th width="80%"><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"></textarea></th>
                                    			</tr>-->
                  					       </table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                      <div id="capa1" class="col-lg-12">
                                      </div>
                                       <p>&nbsp;</p>  
                                      <table class="" width="90%" id="tblGuardar" align="center" border='0'>  
                                        <tr>
                                          <td width="30%">&nbsp;</td>
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Cambio de Turno"  class="btn btn-success" onclick="fichar();"/></td>
                                          <td width="30%">&nbsp;</td>
                                        </tr>
                                      </table>
                                      <p>&nbsp;</p>
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
