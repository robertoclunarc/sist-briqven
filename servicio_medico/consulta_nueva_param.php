<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
	require('piedepagina.php'); 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Nueva Consulta</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet">
 	
    <!-- <link href="css/bootstrap-dialog.css" rel="stylesheet">-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap-dialog.js"></script>
     <!-- Validacion de Fechas -->
	<script src="js/fechas.js"></script>
     <!-- Varias Funciones -->
	<script src="js/varios.js"></script>


<script  language="javascript">
//(*--------------------------------------------*)

function GuardarConsulta()
{
	//var dialog = new BootstrapDialog()
	
	//BootstrapDialog.alert("Guardando...");
	//alert("Guardando:Implementar");
	//$('#formulario').trigger("reset");
	
	
	//Validar
	//

	/*if (validaFechaDDMMAAAA($("#txtFecha").val())==false)
	{
		alert("Introduzca una fecha válida para la Consulta (dd/mm/aaaa)");
		$("#txtFecha").focus();
		exit(0);
	}	

	if ($("#txtFechaProxCita").val()!="")
		if (validaFechaDDMMAAAA($("#txtFechaProxCita").val())==false)
		{
			alert("Introduzca una fecha válida para la Próxima Cita \n(dd/mm/aaaa), o déjela en blanco");
			$("#txtFechaProxCita").focus();
			exit(0);
		}
	
	*/

if ($("#txtCi").val()=="")
	{
		alert("Seleccione un Paciente Registrado");
		$("#ci").focus();
		exit(0);
	}
	
		dir_url = "registrar_consulta_db.php";
		$.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
			   //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("La consulta ha sido agregada Correctamente!");
			   else
			   {
					alert("La operación Generó un Error:" . data);
			   }
			   
			   	//recargar la página para limpiar controles
				//
				location.reload(); //Recargar la página desde cero.			   
           }
         });
	

}
//(*--------------------------------------------*)
function VerificarEnter(e)
{
    if (e.keyCode == 13) {
		$(ci).val($(ci).val().toUpperCase());
		IrPaciente($(ci).val());
    }
}
//(*--------------------------------------------*)
function IrPaciente(cedula)
{
	//url= new String("");
	//alert("Esta es la cedula:" + cedula);
	url="cargar_datos_paciente.php?cedula=" + cedula; 
	//alert("Esta es la url: " + url);
	$.ajax(url).done(function(data)
	 {
			//alert(data);
			if (data!="")
			{
				eval(data); //aquí vienen los datos de la pagina php
				$("#tbl_datos_personales").show();
				$("#cmdGuardar").removeAttr('disabled');
				$("#cboMedico").focus();
				
			}
			else
			  { alert("Paciente no Existe");
			  	$("#tbl_datos_personales").hide();
				$("#cmdGuardar").attr("disabled","disabled");
				//$("#tbl_datos_personales");
				$(':input','#tbl_datos_personales').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
				
				//Abrir Ventana para Carga de Paciente
				url = "paciente_nuevo.php?cedula=" + cedula + "&modo=M";
				PopupCenter(url, "Registro de Paciente", 800, 400)
				//window.open(url,"","toolbar=yes, scrollbars=yes, resizable=no, top=500, left=500, width=800, height=400");
			  }	
	 }

	);	
}
//(*--------------------------------------------*)

function ModificarPaciente(cedula)
{
	//url= new String("");
	//alert("Esta es la cedula:" + cedula);
	url="paciente_nuevo.php?modo=M&cedula=" + cedula; 
	//alert("Esta es la url: " + url);
	
	//Abrir Ventana para Carga de Paciente
	PopupCenter(url, "Actualizar Paciente", 800, 400);
	//window.open(url,"","toolbar=yes, scrollbars=yes, resizable=no, top=500, left=500, width=800, height=400");

}
//(*--------------------------------------------*)

function VerificAgregarMedicam(objeto)
{
	if ($(objeto).val()>0){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
}
//(*--------------------------------------------*)

function CargarCombo(nombcombo, url)
{
	$cboMotivos = $(nombcombo);
	//alert($cboMotivos.html);
    //$.post(url,$cboMotivos.html=data);	
	//$.ajax(url).done(function(data){alert(data);});
	$.ajax(url).done(function(data){
			$(nombcombo).empty();
			$(nombcombo).append(data);			
			}
	);	
}

//(*--------------------------------------------*)
function recibirQS(parametros){
var urlPag = window.location.search.substring(1);
var urlVars = urlPag.split('?');
for (var i = 0; i < urlVars.length; i++){
var nombreParam = urlVars[i].split('=');
if(nombreParam[0] == parametros){
return nombreParam[1];
}
}}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function(){
	//alert("aqui");
	
	//ocultar la tabla de datos personales	
	$("#tbl_datos_personales").hide();
	
	//CargarCombo($("#cboMedico"),"cargar_combo_db.php?tabla=v_medico_con_usuario&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo and login='"+$("#txtlogin").val()+"'");

	CargarCombo($("#cboParaMedico"),"cargar_combo_db.php?tabla=v_paramedico_con_usuario&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo and login='"+$("#txtlogin").val()+"'");
	
	CargarCombo($("#cboMotivos"),"cargar_combo_db.php?tabla=tbl_motivos&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Elija un motivo]");

	CargarCombo($("#cboArea"),"cargar_combo_db.php?tabla=tbl_areas&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]");
	
	CargarCombo($("#cboPatologias"),"cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]");
	
	CargarCombo($("#cboMedicamentos"), "cargar_combo_db.php?tabla=v_medicamentos&campo1=uid&campo2=descripcionlarga&selected=0&orderby=descripcion&where=activo");

	CargarCombo($("#cboMedicamentos2"), "cargar_combo_db.php?tabla=tbl_medicamentos&campo1=descripcion&selected=0&orderby=descripcion");
	
	CargarCombo($("#cboRemitido"), "cargar_combo_db.php?tabla=tbl_remitido&campo1=uid&campo2=descripcion&selected=0&orderby=&firsttext=[NO REMITIDO]");

	CargarCombo($("#cboReposo"), "cargar_combo_db.php?tabla=tbl_reposo&campo1=uid&campo2=descripcion&selected=0&orderby=uid &firsttext=[SIN REPOSO]");

	var parametro = recibirQS('cedula');
    if(parametro != undefined){
        IrPaciente(parametro);
        //alert('*'+getParameterByName(parametro)+'*');
    }
	
});

function insertaretxt() {	
	var str = $("#cboMedicamentos2 option:selected").text();
    var res = decodeURI(str); //
  //  str.replace("+", " ");
    document.getElementById("txtIndicaciones").value = $("#txtIndicaciones").val()  + res + ": " + $("#txtIndicaciones_x").val() + "\n";
}

function Agregarreferencia(){
	
	var esp = $("#especialidad").val();
	esp=esp.toUpperCase();
	var obsesp = $("#obsespecialidad").val();
	obsesp =obsesp.charAt(0).toUpperCase() + obsesp.slice(1); // coloca la 1ra letra en mayuscula
	//obsesp =obsesp.toLowerCase();
	var refer = $("#txtreferencia").val();
    refer = refer + ">>" + esp + ": " + "\n" + obsesp + "\n";
     document.getElementById("txtreferencia").value = refer;
}

function Verificreferencia(objeto)
{
	if ($(objeto).val()!=""){ $('#cmdreferencia').removeAttr('disabled');} else $('#cmdreferencia').attr('disabled','disabled');
}

</script>
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
  </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>
<section id='s1'>
<article id="consulta" title='Registro de Consulta'>

<!-- AQUI EL CONTENIDO 
-->
<form id="formulario" method='post'>
    
    <p align="right">
    Fecha:<input size="10" readonly="" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("Y-m-d")); ?>'/>&nbsp;Turno:<input size="10" name='txtTurno' id='txtTurno' type='text' value=''/></p>
    <article>    
    <div class="input-group">
      <input id="ci" type="text" class="form-control" style="z-index: 0;" placeholder="Cédula o Pasaporte V/P/E########" onkeypress="VerificarEnter(event);" onblur="$(this).val($(this).val().toUpperCase());" >
      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <span class="input-group-btn">
        <button id="" class="btn btn-default" type="button" onclick="IrPaciente($(ci).val());">Ver</button>
      </span>
    </div><!-- /input-group -->    
    <p></p>
    <table id="tbl_datos_personales" width="100%"> 
    <tr>
    	<td>CI/Pasaporte<input id='txtId' name='txtId' type='hidden' value=''/></td>
    	<td>Nombre Completo</td>
    	<td>Fecha de Nac.</td>
    	<td>Mano Dominante</td>
    </tr>
    <tr>
    	<td><input id='txtCi' name='txtCi' type='text' value='' disabled="disabled"/></td>
    	<td><input id='txtNombre' type='text' value='' disabled="disabled" /></td>
    	<td><input id='txtFechaNac' type='text' value='' disabled="disabled" /></td>
    	 <td><select name="cbomano_dominante" id="cbomano_dominante" disabled="disabled" >
			<option value="Izquierd@">Izquierd@</option>
			<option value="Derech@">Derech@</option>			
			<option value="Diestr@">Diestr@</option>
		</select></td>
    </tr>    
    <tr>
	    <td>Gerencia</td>
	    <td>Departamento</td>
	    <td>Cargo</td>
	    <td>Contratista</td>
    </tr>
    <tr>
    	<td><input id='txtGerencia'  type='text' value='' disabled="disabled"/></td>
    	<td><input id='txtDepartamento'  type='text' value='' disabled="disabled" /></td>
    	<td><input id='txtCargo' type='text' value='' disabled="disabled"/></td>
    	 <td><input id='txtContratista' type='text' value='' /></td>
    </tr>
    <tr>
    	<td>Tipo de Sangre</td>
    	<td>Tipo de Discapacidad</td>
    	<td>Desc. Discapacidad</td>
    	<td>Alergia</td>
    </tr>
    <tr>   
	   <td><input id='txttipo_sangre' type='text' value='' disabled="disabled"/></td>
	   <td><input id='tipo_disca' type='text' value='' disabled="disabled"/></td>
	   <td><input id='discapacidad' type='text' value='' disabled="disabled"/></td>
	   <td><input id='alergia' type='text' value='' disabled="disabled"/></td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td><button id="" class="btn btn-default" type="button" onclick="ModificarPaciente($(txtCi).val());">Ver Mas Datos...</button></td>
    	<td> &nbsp;</td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    </table>
 </article>
    
<table >      
    <tr>    	
    	<td><label>Paramédico de Guardia: </label></td>
    	<td><select name="cboParaMedico" id="cboParaMedico" ></select></td>
    	<td> &nbsp;</td>
    	<td><label>Área: </label></td>
    	<td><select name="cboArea" id="cboArea" ></select></td>
    </tr> 
    <tr> 
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>  
    </tr>  
    <tr>
    	<td><label>Motivo:</label></td>
    	<td><select name="cboMotivos" id="cboMotivos" ></select></td>
    	<td> &nbsp;</td>
    	<td><label>Patología: </label></td>
    	<td><select name="cboPatologias" id="cboPatologias" ></select></td>    	
    </tr>
   
</table>

<table width="100%">
	<tr>
		<td width="100%"><label>Motivo de la Consulta</label></td>
	</tr>
	<tr>
		<td width="100%"><INPUT type="text" id="txtSintomas" name="txtSintomas"  width="100%" class="form-control"/></td>
	</tr>
	<TR>
		<td width="100%"><label>Enfermedad Actual</label></td>
	</TR>
	<tr>
	<td width="100%"><INPUT type="text" id="txtObservaciones" name="txtObservaciones"  width="100%" class="form-control"/></td>
	</tr>
	<TR>
		<td width="100%">&nbsp;</td>
	</TR>
	<TR>
		<td width="100%"><LABEL>Resultados de la Evaluacion Medica:</LABEL></td>
	</TR>
	<tr>
		<td><label>Remitido a </label>
			<select name="cboRemitido" id="cboRemitido" ></select>			
		</td>
	</tr>
<!--	<tr>
		<td width="100%"><LABEL>Resultados</LABEL></td>
	</tr>	
	<tr>
		<td width="100%"><INPUT type="text" id="txtResultados" name="txtResultados"    width="100%" class="form-control"/></td>
	</tr>
-->	
	<tr>
		<td width="100%">&nbsp;</td>
	</tr>
	
	<tr>
		<td width="100%"><label>Observacion</label></td>
	</tr>
	<tr>
		<td width="100%"><INPUT type="text" id="txtObservacionMed" name="txtObservacionMed"  width="100%" class="form-control"/></td></tr>
	<tr>
	<tr>
		<td width="100%"><label>Referencia</label></td>
	</tr>
	<tr>
		<td width="100%">
			<table width="100%">
				<tr>
					<td width="30%"><input id="especialidad" type="text" class="form-control" onkeyup="Verificreferencia(this);" onblur="Verificreferencia(this);" placeholder="Especialidad"></td>
					<td width="64%"><input id="obsespecialidad" type="text" class="form-control" onkeyup="Verificreferencia(this);" onblur="Verificreferencia(this);" placeholder="Informe"></td>
					<td width="6%"><INPUT id="cmdreferencia" type="button" value="+" class="form-control" onclick="Agregarreferencia();" disabled="disabled" /></td>
			    </tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%"><textarea class="form-control" readonly id="txtreferencia" name="txtreferencia" rows="4" cols="50" wrap="soft"></textarea></td></tr>
	<tr>
</table>
<br>
<table>
    <tr>
	    <td width="60%"><label>Medicamentos Aplicados</label></td>
	    <td><LABEL>Medida</LABEL></td>
	    <td><LABEL>Cantidad</LABEL></td>
	    <td>&nbsp;</td>
	</tr>
    <tr>
    	<td><select name="cboMedicamentos" id="cboMedicamentos" class="form-control" ></select></td>
    	<td><select name="cboMedidas" id="cboMedidas" class="form-control" >
    			<option value="Izquierd@">Comprimidos</option>
    			<option value="Izquierd@">Ampollas</option>
    			<option value="Izquierd@">C.C.</option>
    			<option value="Izquierd@">Tableta</option>    			
    			<option value="Izquierd@">Untada</option>
    		</select>
    	</td>
    	<td><INPUT id="txtCantidad" type="text" class="form-control" onkeyup="VerificAgregarMedicam(this);" onblur="VerificAgregarMedicam(this);"/></td>
    	<td><INPUT id="cmdAgregar" type="button" value="+" class="form-control" onclick="AgregarFilaMed();" disabled="disabled" /></td>
    </tr>
</table>


<p>&nbsp;
</p>
<table>
<tr>
	<td width="50%"><LABEL>Fecha de la Próxima Cita</LABEL></td>
	<td width="50%"><INPUT type="text" placeholder="AAAA-MM-DD" id="txtFechaProxCita" name="txtFechaProxCita" size="20%" class="form-control" /></td>		
</tr>
</table>

<p>&nbsp;
</p>

<table class="" width="100%" id="tblGuardar" align="center">	
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar Consulta"  class="form-control" onclick="GuardarConsulta();"/></td>
		<td width="30%">&nbsp;</td>
	</tr>
</table>

</form>
</article>
</section>
</body>
</html>
 <?php piedepagina(); 
}
else{
    //header('Location: /login/index.php');
echo "<html>
<body>
<script type='text/javascript'>
window.location='index.php';
</script>
</body>
</html>
";
}
?>