<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $fecha_dada=$_GET['id'];
  $trabajador=$_GET['id2'];
//print $fecha_dada;
//  $fecha_fichada= isset($_POST["txtfecha"])?$_POST["txtfecha"]:date("Y")."-".date("m")."-".date("d");
  $query="SELECT * from registro_diario where trabajador_reg='".$_SESSION['user_session_const']."' and trabajador=".$trabajador."and fecha=".$fecha_dada." order by trabajador";
//print $query."<br>".$trabajador;
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
         $grupo= $fila['grupo'];
         if ($fila['asistio']=="S") $asistio="checked"; else $asistio="";
         if ($fila['sobre_tiempo']=="S") $sobre_tiempo="checked"; else $sobre_tiempo="";
         if ($fila['comision']=="S") $comision="checked"; else $comision="";
         if ($fila['cambio_turno']=="S"){
             $cambio_turno="checked";
             $observacion="Vino a trabajar de ".$fila['turno'].",\n".$fila['observacion'];
//	     $observacion=$fila['observacion'];
         }else{
	     $cambio_turno="";
	     $observacion=$fila['observacion'];
          }
    }
  }
  $esperanza="";
	
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

    <title>Registrar Fichadas</title>
    
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
function GuardarRegistro()
{
 
 if ($("#cbogrupo").val()==0){ 
     alert('Debe seleccionar el grupo al que pertenece el trabajador');
     $("#cbogrupo").focus();
     return;
  }

  if (($('#chkcambioturno').is(':checked')) && ($("#cboesperanza_cambiada").val()==0)){
       alert('Debe seleccionar el turno');
       $("#cboesperanza_cambiada").focus();
       return;
  }

  if(confirm("Esta seguro de modificar la fichada?")){ 
    document.getElementById("cmdGuardar").disabled = true;
//  mostrar("ver", "images/preloader-01.gif");
    
    dir_url = "modificar_fichada_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
         //alert(data); // Mostrar la respuestas del script PHP.
           if (data>0)
            {
              alert("Datos Registrados Correctamente!");
              location.reload();
	      location.href = "index.php"
           }else{
            alert("La operación Generó un Error: " + data);
            document.getElementById("cmdGuardar").disabled = false;
  //          ocultar("ver");
           }   
           }
         });  
  }
}

//(*--------------------------------------------*)
function valida_repetido(ced){
  var j=parseInt($("#hddcontador").val());
  var entro = false;
  if (j>0){
    $('input[type=hidden]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='cedulas[]') && (cb.attr('value')==ced))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)
function elimFilaProd()
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;
} 
//(*--------------------------------------------*)
function buscar_trabajador(idtipoeqp,fecha)
{
 
 if (idtipoeqp!='')
    {
      url="cargar_esperanza.php?b=" + idtipoeqp + "&c="+fecha; 
//      alert(url);
      $.ajax(url).done(function(data)
       {   
//       alert('data:'+data);
          if (data != "VACIO"){
              eval(data);
        //      AgregarFilaInfo(idtipoeqp);
          }
       }
      )
    }
}
//(*--------------------------------------------*)
function ventanaAct(campo){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
//    var ventana = window.open('buscar_usuarios.php?campo='+campo, "Buscar Usuarios", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
 }
//(*--------------------------------------------*)

function CargarCombo(nombcombo, url)
{
//alert(nombcombo+' - '+url);
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
    //  eval(data);
      }
  );  
}

//(*--------------------------------------------*)
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
    if (tecla==48) return true;
    if (tecla==49) return true;
    if (tecla==50) return true;
    if (tecla==51) return true;
    if (tecla==52) return true;
    if (tecla==53) return true;
    if (tecla==54) return true;
    if (tecla==55) return true;
    if (tecla==56) return true;
    if (tecla==57) return true;
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
}
//(*--------------------------------------------*)
/*
function mostrar(cdiv, foto){
document.getElementById(cdiv).innerHTML="";
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="'+foto+'" alt="" height="60" width="60">';
$(cdiv).show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(cdiv){
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
$(cdiv).hide();
$('#loading').hide();
}
*/   
//(*--------------------------------------------*)
function limpiar_campos(){
//  $("#cbotrabajador").val('');
  $("#txtentradaesperada").val("");   
  limpiar_hora();
  document.getElementById("chkcomision").checked = false;
  limpiar_esperanza();
}
//(* ---------------------------------------------*)
function limpiar_hora(){
  $("#txthora_entrada_real").val("");
  $("#txthora_salida_real").val("");   

  document.getElementById("chkasistio").checked = false;
  document.getElementById("chksobretiempo").checked = false;
  document.getElementById("chkcambioturno").checked = false;
  $("#txtobservacion").val("");   
 }
//(* ---------------------------------------------*)
function limpiar_esperanza(){
	var sel = document.getElementById("cboesperanza_cambiada");
        while (sel.length > 0) {
               sel.remove(sel.selectedIndex);
        }
	var sel = document.getElementById("cbogrupo");
        while (sel.length > 0) {
               sel.remove(sel.selectedIndex);
        }
        CargarCombo($("#cbogrupo"),"cargar_combo_array.php?combo=grupo");

 }

//(*-----------------------------------------------*)

//(*-----------------------------------------------*)

$(document).ready(function(){ 
	$("#cbopermiso").change(function(){
	    limpiar_esperanza();
	});
	$("#chksobretiempo").change(function(){
	        if ($('#chksobretiempo').is(':checked')){
                        document.getElementById("chkasistio").checked = true;
                        document.getElementById("chkcomision").checked = false;
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
	$("#chkasistio").change(function(){
	        if ($('#chkcomision').is(':checked')){
  			document.getElementById("chkcomision").checked = false;
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
	$("#chkcomision").change(function(){
	        if ($('#chkcomision').is(':checked')){
			limpiar_hora();
			limpiar_esperanza();
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
	$("#chkcambioturno").change(function(){
	        if ($('#chkcambioturno').is(':checked')){
			CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	}else{
			limpiar_esperanza();
			limpiar_hora();
		} 
	});
/*
	$("#cbotrabajador").change(function(){
	    limpiar_hora();
	    limpiar_esperanza();
	    document.getElementById("chkcomision").checked = false;
	    document.getElementById("chkasistio").checked = true;
	    $("#cbopermiso option[value=0]").prop('selected', 'selected');
 	});
*/
        CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
        CargarCombo($("#cbogrupo"),"cargar_combo_array.php?combo=grupo");
	CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");

	if ($("#cbogrupo").val()==0){ 
                $("#cbogrupo").focus();
        }

});

//(*--------------------------------------------*)
</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index..php">Control Asistencia - Registrar Fichada</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Modificar Fichada</h1>
                </div>	
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del trabajador
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 
<!--                                        <input  name='hddcedula' id='hddcedula' type='hidden' value=''/>   -->
<!--                                        <input  name='hddcedula' id='hddcedula' type='text' value=''/>-->   
                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="90%" class="table table-striped" border="0">
  					  <thead>
                                             <tr>
                                                  <th width="20%"><label>Fecha:</label></th>
                                                  <th width="80%">
						     <table>
							<tr>
							   <th>
							<INPUT type="date" maxlength="10" size="10" id="txtfecha" name="txtfecha"  readonly value=<?php echo $fecha_dada ; ?> width="80" class="form-control"/>
							   </th>
							</tr>
						     </table>
						  </th>
                                            </tr>  
                                             <tr>

                                            	  <th width="20%"><label>Trabajador:</label></th>
                                                  <th width="80%"><input  name='txttrabajador' id='txttrabajador' type='text' readonly value=<?php echo $trabajador; ?>/></th>                                           </tr>                                              
					    <tr>
						<td colspan="2">
						    <table width="100%" border="0">
							<tr>
							    <td><label>Grupo: </label></td>
                                                            <td><select id="cbogrupo" name='cbogrupo' class="form-control"> </select></td>
                                                         </tr>
                                                    </table>         
                                                </td>
                                            </tr>      
                                            <tr>
                                                <td colspan="2">
			                             <table width="100%" border="0">  
							    <td><label>&nbsp;&nbsp;Asisti&oacute;: </label></td>
        	                                            <td><input type="checkbox" class="custom-control-input" id="chkasistio" name="chkasistio" <?php echo $asistio; ?>></td>
								    <td align="right"><label>Comisi&oacute;n de Servicio: &nbsp;&nbsp;</label></td>
        	                                            <td><input type="checkbox" class="custom-control-input" id="chkcomision" name="chkcomision" <?php echo $comision; ?>></td>
                	                                    <td align="right"><label>Horas extras: &nbsp;&nbsp;</label></td>
                        	                            <td><input type="checkbox" class="custom-control-input" id="chksobretiempo" name="chksobretiempo" <?php echo $sobre_tiempo; ?>> </td>
						        </tr> 
					             </table>
						</td>
					    </tr>
					    <tr>
						<th width="20%"><label>Ausencia:</label></th>
                                                <th width="80%" colspan='3'><!--<select name="cbopermiso"  onchange="limpiar_hora();" id="cbopermiso"  data-width="80%" data-size="5" class="selectpicker" data-hide-disabled="false" data-live-search="true"><?php llenar_combo_permiso(); ?>
-->
 <select id="cbopermiso" name="cbopermiso" onchange="limpiar_hora();"  class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true" > </select>                                                     
						</th>
				 	    </tr>
                                             <tr>
                                                <th width="100%" colspan="4">
	    					   <table width="100%"border = "0">
                                            <tr>
<!--                                               <th width="20%" colspan="4">
                                                  <table border="1" width="90%">
-->
						    <tr>
						       <th width="25%"><label>Cambio de turno:</label></th>
                        	                       <th width="10%"><input type="checkbox" class="custom-control-input" id="chkcambioturno" name="chkcambioturno" <?php echo $cambio_turno; ?>> </th>
                                                       <th>
                                                      <!--<div id="entrada_esperada"><input type="text" id="txtentradaesperada" maxlength="100" placeholder="" readonly="" name="txtentradaesperada"  class="form-control"></div>  -->
<select id="cboesperanza_cambiada" name="cboesperanza_cambiada" class="form-control"> </select>                                                      
<?php //echo $esperanza; ?>
                                                       </th>
 						     </tr>
<!--                                                  </table>
                                               </th>
-->
                                            </tr>

					           </table>

				                </th>

					      </tr>

				              <tr>
					        <th width="20%"><label>Observaci&oacute;n:</label></th>
                                                <td colspan='80%'><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"><?php echo $observacion; ?></textarea></td>
				              </tr>
	<!--					      <tr>
                                                 <td colspan='2' align='center'>
						    <div class="form-group input-group">
                                                      <span class="input-group-btn">
                                                       	 <button class="btn btn-default" onclick="AgregarFilaInfo();" type="button"><i class="fa fa-plus-circle"></i></button>
                                                         <INPUT id="AgregarRegistro" type="button" value="Agregar Registro"  class="btn btn-success" onclick="AgregarFilaInfo();"/></td>
                                                      </span>        
	                                           </div>
					         </td>
					      </tr>
	-->
                                       <!--  </tbody> --> 
  				          </thead> 
                                        </table>
                                      </div>
	<!--                                      <div class="col-lg-6">  
	-->
                                        <table width="90%" id="tblInfoAdicional" class="table table-striped" border="0">
<!--                                          <thead> -->
        <!--                                      <tr>
	-->
<!--                                                  <th width="10%">Cedula</th> -->
        <!--                                          <th width="25%">C&eacute;dula - Nombres</th>
                                                  <th width="8%">Grupo</th>
	-->
<!--                                                  <th width="10%">Hora Entrada</th>
                                                  <th width="10%">Hora Salida</th>       
-->
        <!--                                          <th width="8%">Asisti&oacute;</th>
                                                  <th width="8%">Sobre<br>Tiempo</th>
                                                  <th width="8%">Cambio<br>de Turno</th>
                                                  <th width="8%">Comisi&oacute;n</th>
                                                  <th width="8%">Tipo Ausencia</th>
                                                  <th width="8%">Observaciones</th>
                                                  <th width="5%"></th>                                                                  
					      </tr>
	-->
<!--                                          </thead>   -->
        <!--                                </table>
                                       
                                      </div>
	-->
                                       <p>&nbsp;</p>  
	                                      <table class="" width="90%" id="tblGuardar" align="center" border='0'>  
	
                                        <tr>
	<!--                                          <td width="30%">&nbsp;</td>
	-->
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Modificar fichada"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
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
