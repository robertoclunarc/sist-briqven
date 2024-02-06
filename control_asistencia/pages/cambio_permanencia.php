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
          url: "cambio_permanencia_online.php",
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
//eval(data);          
                $("#capa1").empty();
                $("#capa1").append(data);
		//$("#txthoraini1").val($("#salida_real1").val());
		var salida=document.getElementById("entrada_real1").value;
		$("#txthoraini1").val(salida);
		var salida=document.getElementById("salida_real1").value;
		$("#txthorafin1").val(salida);
//alert(salida);
//eval(data);          
//			alert(data);
//                if (data!="")
///			alert(data);
//		else
//			alert("Periodo Cerrado, no acepta modificaciones");
          }
    });
}
}

//(*--------------------------------------------*)
function fichar(){
if ((($("#cbotrabajador").val())!='') && ($("#cbotrabajador").val())!='0' && (($("#cbotrabajador").val())!=null) ){
	if ($("#cboaccion").val()==''){ 
                alert('Debe seleccionar la Accion se desea se le aplique a al trabajador');
                $("#cboaccion").focus();
                return;
            }
	if ($("#txtobservacion").val()==''){ 
                alert('Debe ingresar el motivo por el cual el trabajador vino en un turno distinto a la esperanza');
                $("#txtobservacion").focus();
                return;
            }

$.ajax({
          type: "POST",
          url: "cambio_permanencia_db.php",
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
//                alert(data);                          
		window.locationf="index.php";	
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
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - Reportar Cambio de Turno</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="page-header">Reportar Cambio de Permanencia</h5>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del Personal que Cambio de Permanencia
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
                        			<th width="10%"><label>Fecha: </label></th>
                        			<th width="80%" ><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange="consultar();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
<!--                        			<th width="10%"><label>&nbsp;Fin</label></th>
                        			<th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" onchange="consultar();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
-->
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
                        			<th><label>Ent. Real 1</label></th>
                        			<th><INPUT type="time" maxlength="10" size="10" id="txthoraini1" name="txthoraini1"  value="" width="10" class="form-control" readonly/></th>
					</tr>
					<tr>
                        			<th><label>Sal. Real 1</label></th>
                        			<th><INPUT type="time" maxlength="10" size="10" id="txthorafin1" name="txthorafin1"  value="" width="10" class="form-control"/></th>
                  			</tr>
 <!--                 			<tr>
                        			<th width="10%"><label>Acci&oacute;n</label></th>
                        			<th  width="80%">
							<select name="cboaccion" id="cboaccion"  class="form-control"  data-live-search="true">
                  		 				<option selected="" value="">Seleccionar Accion a Ejecutar</option>
                   						<option value="1">Cambiar Esperanza</option>
                   						<option value="0">Mantener Esperanza</option>
           		    				</select>
		  	 			</th>
                  			</tr>
-->
<!--					<tr><th width="25%"><label>Turno que asistio:</label></th>
                                        <th><select id="cboesperanza_cambiada" name="cboesperanza_cambiada" class="form-control"  data-live-search="true"> </select></th></tr>
-->
                  			<tr>
                       				<th width="10%"><label>Motivo:</label></th>
                        			<th width="80%"><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"></textarea></th>
                  			</tr>

<!--                  			<tr>
                        			<th><label>Ent. Real 2</label></th>
                        			<th><INPUT type="time" maxlength="10" size="10" id="txthoraini2" name="txthoraini2"  value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
   					 </tr>
                                        <tr>

                        			<th><label>Sal. Real 2</label></th>
                        			<th><INPUT type="time" maxlength="10" size="10" id="txthorafin2" name="txthorafin2"  value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
                  			</tr>                      
-->
					</table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                      <div id="capa1" class="col-lg-6">
                                       
                                      </div>
                                       <p>&nbsp;</p>  
                                      <table class="" width="90%" id="tblGuardar" align="center" border='0'>  
                                        <tr>
                                          <td width="30%">&nbsp;</td>
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Cambio de Permanencia"  class="btn btn-success" onclick="fichar();"/></td>
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
