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

    <title>Registrar de Horas Extras</title>
    
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
//    alert("paso");
	$.ajax({
          type: "POST",
          //url: "buscar_he_online.php",
          url: "cambio_turno2_online.php",
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
//                alert("Paso2");
		$("#capa1").empty();
                $("#capa1").append(data);         
		 
	        var entrada_esperada= document.getElementById("entrada_esperada1").value;
	        var salida_esperada= document.getElementById("salida_esperada1").value;
               	var entrada_real   = document.getElementById("entrada_real1").value;
               	var salida_real    = document.getElementById("salida_real1").value;
          	$("#txthoraini1").val(salida_esperada);
		$("#txthorafin1").val(salida_real);
//  		alert("Entrada:"+entrada_esperada);
		if (entrada_esperada=='LL:LL' ){
			var respuesta = confirm("Trabajador con esperanza libre (LL:LL), No permite carga de horas extras, Desea cargarle DLT a este trabajador?");
			if (respuesta){
				alert("Este proceso no esta operativo en estos momentos");						
			/*	
				$.ajax({
		                    type: "POST",
                		    url: "cargar_DTL_db.php",
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
		                        //eval(data); 
                		   }
		              });
			*/	
			}else{
    				header('Location: cargar_horas_extras.php');
			}
		}else{
			if (entrada_esperada=='VV:VV' || entrada_esperada=='PP:PP' || entrada_esperada=='RR:RR') {
				alert("Esperanza no Permite Horas Extras");
 			}else{
				if (entrada_real==''){ 
					alert("No presenta entrada Real");
				}/*else{
          	        		$("#txthoraini1").val(salida_esperada);
		               		$("#txthorafin1").val(salida_real);
				}*/
			}
		}
          }
    });
  }
}

//(*--------------------------------------------*)
function fichar(){
	var observacion=$("#txtobservacion").val();
	if (observacion==""){
		alert('Debe indicar el motivo por el cual este trabajador de quedo laborando horas extras');  
	       // window.location="index.php";
		return
	}else{
/*	     $sms='';
	    if ((hinicio1 < salida_esperada1) && (hfinal1 > entrada_esperada1)){
	        $sms="Error: La hora de inicio debe se mayor que la hora de salida esperada";
	    }elseif ((hfinal1 > entrada_esperada1) && (hfinal1 < salida_esperada1)){        
        	$sms="Error: La hora fin debe ser menor que la entrada Esperada";
    	    }elseif (entrada_esperada1=="VV:VV" || entrada_esperada1=="PP:PP" || entrada_esperada1=="RR:RR" || entrada_esperada1=="LL:LL" || entrada_esperada1=="FF:FF" || entrada_esperada1=="SD:SD" || entrada_esperada1=="CS:CS"){
        	$sms="Error: La Esperanza del trabajador no permite cargarle horas extras";
    	    }elseif (RestarHoras(hinicio1,hfinal1)>8){
        	$sms="Error: No puede tener mas de 8 horas de horas extras";
    	    }
*/
		$.ajax({
        	    type: "POST",
	            url: "cargar_horas_extras_db.php",
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
			//eval(data); 
        	   }
 	      });
	}
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
                <a class="navbar-brand" href="index.php">Control Asistencia - Cargar Horas Extras</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Registrar Horas Extras</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del Personal con horas extras
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       <input  name='proceso' id='proceso' type='hidden' value='cargar_horas_extras_db.php'/> 
                                       <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 

                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="50%" class="table table-striped" >
                  <tr>
                        <th width="10%"><label>Fecha:</label></th>
                        <th width="10%" colspan=3><div class="col-xs-5"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange="consultar();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></div></th>
<!--                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" onchange="consultar();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
-->
                  </tr>  
                  <tr>
                      <th width="10%"><label>Trabajador:</label></th>
                      <th colspan="3" width="80%"><div class="col-xs-10"><select name="cbotrabajador" onchange="consultar()" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option; ?>
                              </select></div>
                      </th>        
                  </tr>
                  <tr>
                        <th><label>Entrada Real:</label></th>
                        <th><div class="col-xs-10"><INPUT type="time" maxlength="10" size="10" id="txthoraini1" name="txthoraini1"  value="<?php echo $fecha_a ; ?>" readonly width="10" class="form-control"/></div></th>
                        <th><label>Salida Real:</label></th>
                        <th><div class="col-xs-10"><INPUT type="time" maxlength="10" size="10" id="txthorafin1" name="txthorafin1"  value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></div></th>
                  </tr>
                  <tr>
                        <th width="20%"><label>Observaci&oacute;n:</label></th>
                        <td colspan='80%'><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"></textarea></td>
                  </tr>

<!--                  <tr>
                        <th><label>Ent. Real 2</label></th>
                        <th><INPUT type="time" maxlength="10" size="10" id="txthoraini2" name="txthoraini2"  value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></th>
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
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Horas Extras"  class="btn btn-success" onclick="fichar();"/></td>
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
