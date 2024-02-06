<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $query="SELECT trabajador, e_mail,  (nombres || ' ' || apellidos) as Nombre_Completo, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia FROM public.trabajadores_activos_con_jefes_1 where supervisor='".$_SESSION['cedula_session_const']."'";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);

  if($numReg>0){
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
      $option.= "<option value='". $fila['trabajador']."'" ;
      if ($fila[2] != "")
        $option.= ">". $fila[2]. "</option>";
      else   
        $option.= ">". $fila['trabajador']. "</option>";
    }
  }
  $esperanza="";
	
$fecha_actual = date("Y-m-d");
$fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

//echo date("Y-m-d",strtotime($fecha_actual."- 1 days"));
//echo $fecha_a;
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

  if (parseInt($("#hddcontador").val())==0){
    alert("Debe agregar los trabajadores");    
    return;
  }
  if (parseInt($("#hddcontador").val())==0){
    alert("Debe agregar los trabajadores");    
    return;
  }
  if (parseInt($("#hddcontador").val())<(parseInt($("#numreg").val()))){
    alert("Debe agregar todos los trabajadores");    
    return;
  }
      
  document.getElementById("cmdGuardar").disabled = true;
//  mostrar("ver", "images/preloader-01.gif");
    
    dir_url = "registrar_fichada_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
//         alert(data); // Mostrar la respuestas del script PHP.
           if (data>0)
            {
              alert("Datos Registrados Correctamente!");
              location.reload();
            }
           else
           {
            alert("La operación Generó un Error: " + data);
            document.getElementById("cmdGuardar").disabled = false;
  //          ocultar("ver");
           }   
           }
         });  

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
function AgregarFilaInfo(idtipoeqp)
{
//alert($("#cbopermiso").val());
	if ((($("#cbotrabajador").val())!='') && ($("#cbotrabajador").val())!='0' && (($("#cbotrabajador").val())!=null)  && (!valida_repetido($("#cbotrabajador").val()))){
	    var cont=parseInt($("#hddcontador").val()) + 1;
	    document.getElementById('hddcontador').value=cont;
	
	    var valor_col1=$("#cbotrabajador").val();    
            var valor_col2=$("#cbotrabajador option:selected").text();
	    var valor_col3=$("#txthora_entrada_real").val();
	    var valor_col4=$("#txthora_salida_real").val();     
	    var valor_col5=$('#chkasistio').is(':checked');
	    var valor_col6=$('#chksobretiempo').is(':checked');
	    var valor_col7=$('#chkcambioturno').is(':checked');
	    var valor_col8=$('#chkcomision').is(':checked');
	    var valor_col9_1=$("#cbopermiso option:selected").text();    
	    var valor_col9=$("#cbopermiso").val();    
	    var valor_col10=$("#txtobservacion").val();     
	    
	    
	    var ctrl_col1="<input name='cedulas[]' type='hidden' value='" + valor_col1 + "'/>";
	    var ctrl_col2="<input name='nombres[]' type='hidden' value='" + valor_col2 + "'/>";
	    var ctrl_col3="<input name='hora_entrada_real[]' type='hidden' value='" + valor_col3 + "'/>";
	    var ctrl_col4="<input name='hora_salida_real[]' type='hidden' value='" + valor_col4 + "'/>";
            // verificamos si seleccionaron el checkbox de ASISTIO
	    if ($('#chkasistio').is(':checked')){
	       var ctrl_col5="<input name='asistio[]' type='hidden' value='S'/>";
               var valor_col5="S";
	    }else{
	       var ctrl_col5="<input name='asistio[]' type='hidden' value='N'/>";
               var valor_col5='N';               
            }
            // verificamos si seleccionaron el checkbox de SOBRE TIEMPO
	    if ($('#chksobretiempo').is(':checked')){
	       var ctrl_col6="<input name='sobretiempo[]' type='hidden' value='S'/>";
               var valor_col6="S";
	    }else{
	       var ctrl_col6="<input name='sobretiempo[]' type='hidden' value='N'/>";
               var valor_col6='N';               
            }
            // verificamos si seleccionaron el checkbox de CAMBIO DE TURMNO
	    if ($('#chkcambioturno').is(':checked')){
	       var ctrl_col7="<input name='cambioturno[]' type='hidden' value='S'/>";
               var valor_col7="S";
	    }else{
	       var ctrl_col7="<input name='cambioturno[]' type='hidden' value='N'/>";
               var valor_col7='N';               
            }
	    // Verificamos si seleccionaron el checkbox de COMISION
	    if ($('#chkcomision').is(':checked')){
	       var ctrl_col8="<input name='comision[]' type='hidden' value='S'/>";
               var valor_col8="S";
	    }else{
	       var ctrl_col8="<input name='comision[]' type='hidden' value='N'/>";
               var valor_col8='N';               
            }
            var ctrl_col9="<input name='permiso[]' type='hidden' value='" + valor_col9 + "'/>";
	    var ctrl_col10="<input name='observacion[]' type='hidden' value='" + valor_col10 + "'/>"

	    var tabla="<tr>";
	    tabla=tabla+"<td width='12%'>"+valor_col1+ctrl_col1+"<td>";
	    tabla=tabla+"<td width='20%'>"+valor_col2+ctrl_col2+"<td>";
	    tabla=tabla+"<td width='10%'>"+ctrl_col3+"<td>";
	    tabla=tabla+"<td width='10%'>"+ctrl_col4+"<td>";
	    tabla=tabla+"<td width='10%'>"+valor_col5+ctrl_col5+"<td>";
	    tabla=tabla+"<td width='10%'>"+valor_col6+ctrl_col6+"<td>";
	    tabla=tabla+"<td width='10%'>"+valor_col7+ctrl_col7+"<td>";
	    tabla=tabla+"<td width='10%'>"+valor_col8+ctrl_col8+"<td>";
	    if ($("#cbopermiso").val()>0){
	    	tabla=tabla+"<td width='10%'>"+valor_col9_1+ctrl_col9+"<td>";
	    }else{
	    	tabla=tabla+"<td width='10%'>"+ctrl_col9+"<td>";
            }
	    tabla=tabla+"<td width='10%'>"+valor_col10+ctrl_col10+"<td>";

	    tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd();'/><td>";
	    tabla=tabla+"<tr>";
      
	    $("#tblInfoAdicional").after(tabla);
	    //$("#txtcedula").val("");
	    limpiar_campos();
  	}
  //}
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
/*
function llenar_combo_permisos()
{
// if (idtipoeqp!='')
//    {
      url="llenar_combo_permisos.php"; 
alert('paso');
      $.ajax(url).done(function(data)
       {          
          if (data != "VACIO"){
              eval(data);
//              AgregarFilaInfo(idtipoeqp);
          }
       }
      )
//    }
}
*/
//(*--------------------------------------------*)
function ventanaAct(campo){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_usuarios.php?campo='+campo, "Buscar Usuarios", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
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
//  $("#cbotrabajador option:selected").text("");
  $("#cbotrabajador").val('');
//setSelectedIndex(0)
  $("#txthora_entrada_real").val("");
  $("#txthora_salida_real").val("");   
  $("#txtentradaesperada").val("");   
  document.getElementById("chkasistio").checked = false;
  document.getElementById("chksobretiempo").checked = false;
  document.getElementById("chkcambioturno").checked = false;
  document.getElementById("chkcomision").checked = false;
  $("#txtobservacion").val("");   
//$('#chkcomision').is(':checked')

//  $('#cbotrabajador').val($('#cbotrabajador > option:first').val());
//  $("#cbopermiso option[value='0']").attr("selected",true);
//  $("#cbopermiso option[value=0]").prop('selected', 'selected');``
//  $("#cbotrabajador option[value='0']").attr("selected", true);
//  $("#cbpermiso option[value='0']").attr("selected", true);
//  $("#cbopermiso option:selected").text('Seleccione el Tipo de Ausencia')
//  $('#cbotrabajador').val($(this).find('option:first').val());
//  $('#cbotrabajador').prop('selectedIndex',0);
//  $('#cbotrabajador').val( $('#cbotrabajador').prop('defaultSelected') );
//  $('#cbotrabajador').val($(this).find('option:first').val());
//  $('#cbotrabajador').options[0].selected="selected";
//  $("#txthora_entrada_real").val("");  
// $("#cbotrabajador").val('0');  
//document.getElementById("cbotrabajor").selectedIndex='0'; 
//  document['forms']['formulario']['icbopermiso']['value'] = '0'; 
}
//(* ---------------------------------------------*)
function limpiar_hora(){
  $("#txthora_entrada_real").val("");
  $("#txthora_salida_real").val("");   

  document.getElementById("chkasistio").checked = false;
  document.getElementById("chksobretiempo").checked = false;
  document.getElementById("chkcambioturno").checked = false;
  //document.getElementById("chkcomision").checked = false;
  $("#txtobservacion").val("");   
 }

//(*-----------------------------------------------*)

$(document).ready(function(){ 
    /*$("select[name=cbopermiso]").change(function(){
            alert($('select[name=cbopermiso]').val());
            //$('input[name=valor1]').val($(this).val());
        });
*/
	$("#chkcomision").change(function(){
	        if ($('#chkcomision').is(':checked')){
			limpiar_hora();
			//CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");
//	    CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	    //     	CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
	$("#chkcambioturno").change(function(){
	        if ($('#chkcambioturno').is(':checked')){
			//limpiar_hora();
			CargarCombo($("#cboesperanza_cambiada"),"cargar_combo_array.php?combo=esperanza");
	    CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	    //     	CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
	$("#cbotrabajador").change(function(){
//	    alert('cambio');
	    limpiar_hora();
	    document.getElementById("chkcomision").checked = false;
	   // alert($("#cbopermiso").val()); 
	    $("#cbopermiso option[value=0]").prop('selected', 'selected');
//	    CargarCombo($("#cboper"),"cargar_combo_array.php?combo=permiso");
//	    document.getElementById('cbopermiso').text('EEEEEEE');	   
            //alert($("#cbopermiso").val());
	    //alert($("#cbopermiso option:selected").text());
 	});
	    CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");


// CargarCombo($("#cbologin_asignado"),"cargar_combo_db.php?tabla=tbl_usuarios&campo1=login&campo2=nombres&selected=0&firsttext=[Elija Usuario de Atencion]&orderby=nombres&where=nivel&cond=1");
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
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                    <h1 class="page-header">Registrar Fichada</h1>
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
                                       <input  name='hddestatus' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 
<!--                                        <input  name='hddcedula' id='hddcedula' type='hidden' value=''/>   -->
<!--                                        <input  name='hddcedula' id='hddcedula' type='text' value=''/>-->   
                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="90%" class="table table-striped" border="0">
  					  <thead>
                                             <tr>
                                                  <th width="20%"><label>Fecha:</label></th>
                                                  <th width="20%">
						     <table>
							<tr>
							   <th>
							<INPUT type="date" maxlength="10" size="10" id="txtfecha" name="txtfecha"  value="<?php echo $fecha_a ; ?>" width="80" class="form-control"/>
							   </th>
							</tr>
						     </table>
						  </th>
<!--                                            </tr>  
                                             <tr>
-->
                                            	  <th width="20%"><label>Trabajador:</label></th>
                                                  <th width="40%">
							<select name="cbotrabajador" onchange="buscar_trabajador($('#cbotrabajador').val(),$('#txtfecha').val());" id="cbotrabajador" data-width="80%" data-size="5" class="selectpicker" data-hide-disabled="false" data-live-search="true">
                                              			<option selected value="0">Seleccione el trabajador</option>
                                              			<?php echo $option; ?>
							</select>
<!--						</th>                                        
                                             </tr>
                                             <tr>

                                               <th width="25%" colspan="2">
                                               	  <table border="0" width="90%">
                    	                            <th>

			  			      <div id="entrada_esperada"><input type="text" id="txtentradaesperada" maxlength="100" placeholder="" readonly="" name="txtentradaesperada"  class="form-control"></div>
						      <?php //echo $esperanza; ?>
						    </th>
                                                  </table>                                        
                                               </th> 
-->
                                            </tr>                                              
					    <tr>
						<td colspan="4">
						    <table width="100%" border="0">
							<tr>
							    <th width="10%"><label>Asisti&oacute;: </label></th>
        	                                            <th width="10%"><input type="checkbox" class="custom-control-input" id="chkasistio"></th>
							    <th width="25%"><label>Comisi&oacute;n de Servicio: </label></th>
        	                                            <th width="10%"><input type="checkbox" class="custom-control-input" id="chkcomision"></th>
                	                                    <th width="20%"><label>Cambio de turno: </label></th>
                        	                            <th width="10%"><input type="checkbox" class="custom-control-input" id="chkcambioturno"> </th>
                	                                    <th width="20%"><label>Horas extras: </label></th>
                        	                            <th width="10%"><input type="checkbox" class="custom-control-input" id="chksobretiempo"> </th>
						        </tr> 
					             </table>
						</td>
					    </tr>
					    <tr>
						<th width="25%"><label>Ausencia:</label></th>
                                                <th width="90%" colspan='3'><!--<select name="cbopermiso"  onchange="limpiar_hora();" id="cbopermiso"  data-width="80%" data-size="5" class="selectpicker" data-hide-disabled="false" data-live-search="true"><?php llenar_combo_permiso(); ?>
-->
<select id="cbopermiso" onchange="limpiar_hora();"  data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true" > </select>                                                      
						</th>
				 	    </tr>
                                             <tr>
                                                <th width="100%" colspan="4">
	    					   <table width="100%"border = "0">
<!-- ################################################# -->
                                            <tr>
                                               <th width="25%" colspan="4">
                                                  <table border="0" width="90%">
						    <tr>
                                                       <th>
                                                      <div id="entrada_esperada"><input type="text" id="txtentradaesperada" maxlength="100" placeholder="" readonly="" name="txtentradaesperada"  class="form-control"></div>
<select id="cboesperanza_cambiada"> </select>                                                      
<?php //echo $esperanza; ?>
                                                       </th>
 						     </tr>
                                                  </table>
                                               </th>
                                            </tr>

<!-- ################################################# -->						     
					     <tr>
                                                        <th width="20%"><label>Hora entrada real:</label></th>
                                                        <th >
                                                             <table border="0"> 
                                                                <tr>  
							           <th>
							              <INPUT type="time" maxlength="10" size="10" id="txthora_entrada_real" name="txthora_entrada_real"  value="<?php echo $entrada_esperada1; ?>" width="80" class="form-control"/>
							           </th>
                                                                </tr>
                                                             </table> 
                                                         </th>                                        
                                                         <th width="20%"><label>Hora salida real:</label></th>
                                                         <th >
                                                	     <table>
		                                                <tr>  
							 	   <th>
								      <INPUT type="time" id="txthora_salida_real" maxlength="10" size="10" name="txthora_salida_real" value="" width="80" class="form-control"/>
								   </th>
		                                                </tr>
                	                                     </table>
                                                         </th>                                           
                                                     </tr>
					           </table>

				                </th>

					      </tr>

				              <tr>
					        <th width="10%"><label>Observaci&oacute;n:</label></th>
<!--				              </tr>
				       
					      <tr>
-->    
                                            <td colspan="4"><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"></textarea></td>
				              </tr>
					      <tr>
                                                 <td>
						    <div class="form-group input-group">
                                                      <span class="input-group-btn">
                                                       	 <button class="btn btn-default" onclick="AgregarFilaInfo();" type="button"><i class="fa fa-plus-circle"></i></button>
                                                      </span>        
	                                           </div>
					         </td>
					      </tr>
                                       <!--  </tbody> --> 
  				          </thead> 
                                        </table>
                                      </div>
                                      <div class="col-lg-6">  
                                        <table width="90%" id="tblInfoAdicional" class="table table-striped" border="0">
                                          <thead>
                                              <tr>
                                                  <th width="10%">Cedula</th>
                                                  <th width="25%">Nombres</th>
<!--                                                  <th width="10%">Hora Entrada</th>
                                                  <th width="10%">Hora Salida</th>       
-->
                                                  <th width="10%">Asisti&oacute;</th>
                                                  <th width="10%">Sobre<br>Tiempo</th>
                                                  <th width="10%">Cambio<br>de Turno</th>
                                                  <th width="10%">Comisi&oacute;n</th>
                                                  <th width="10%">Tipo Ausencia</th>
                                                  <th width="10%">Observaciones</th>
                                                  <th width="5%"></th>                                                                  
					      </tr>
                                          </thead>   
                                        </table>
                                       
                                      </div>
                                       <p>&nbsp;</p>  
                                      <table class="" width="90%" id="tblGuardar" align="center">  
                                        <tr>
                                          <td width="30%">&nbsp;</td>
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar fichada"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
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
