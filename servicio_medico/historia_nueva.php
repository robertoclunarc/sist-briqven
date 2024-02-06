<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
    require('piedepagina.php');
    require('include_conex.php');
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
    $resultadomedico = pg_query($cn, "SELECT uid FROM tbl_medicos_paramedicos WHERE login = '".$_SESSION['user_session']."'");
    $Regmedico = pg_fetch_array($resultadomedico, null, PGSQL_ASSOC); 

    $idc = isset($_GET["idp"])?$_GET["idp"]:"";
    $ced = isset($_GET["ced"])?$_GET["ced"]:""; 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Historia Medica</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/acordion.css" />
    <script type="text/javascript" src="js/modernizr.custom.29473.js"></script>

    <!-- <link href="css/bootstrap-dialog.css" rel="stylesheet">-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap-dialog.js"></script>
     <!-- Varias Funciones -->
    <script src="js/varios.js"></script>

<script  language="javascript">
//(*--------------------------------------------*)

function GuardarHISTORIA()
{

	if ($("#txtCi").val()=="" || $("#txtId").val()=="")
	{
		alert("Seleccione un Paciente Registrado");
		$("#ci").focus();
		return;
	}      

	if ($("#cboMedico").val()=="" || $("#cboMedico").val()=="0")
	{
		alert("Seleccione un Medico de Guardia");
		$("#cboMedico").focus();
		return;
	}

	if ($("#cboha_sufrido_accidentes").val()=="" || $("#cboha_sufrido_accidentes").val()=="0")
	{
		//alert("Responder: Ha sufrido accidentes?");
		//$("#cboha_sufrido_accidentes").focus();
    document.getElementById("cboha_sufrido_accidentes").value = "No";
		//return;
	}

	if ($("#cboha_padecido_enfermeda").val()=="" || $("#cboha_padecido_enfermeda").val()=="0")
	{
		//alert("Responder: Ha padecido enfermedad?");
		//$("#cboha_padecido_enfermeda").focus();
    document.getElementById("cboha_padecido_enfermeda").value = "No";
		//return;
	}

	if ($("#cbocambia_trab_frecuente").val()=="" || $("#cbocambia_trab_frecuente").val()=="0")
	{
		//alert("Responder: Cambia de trabajo frecuentemente?");
		//$("#cbocambia_trab_frecuente").focus();
    document.getElementById("cbocambia_trab_frecuente").value = "No";
		//return;
	}
    
        dir_url = "registrar_historia_db.php";
        $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
               //alert(data); // Mostrar la respuestas del script PHP.
               if (data>0){                    
                    document.getElementById("txtIdh").value=data;
                    registrar_consulta();
               }
               else
               {
                    alert("La operación Generó un Error:" + data);
               }
               
                //recargar la página para limpiar controles
                //
                //location.reload(); //Recargar la página desde cero.            
           }
         });
    

}
function registrar_consulta()
{
        dir_url = "registrar_histconsulta_db.php";
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
                      alert("Examen Registrado Correctamente!");
                      location.href ="examenes.php";
                }
               else
               {
                    alert("La operación Generó un Error: " + data);
               }
               
                //recargar la página para limpiar controles
                //
                //location.reload(); //Recargar la página desde cero.            
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
        if (data!="0"){          
          if ($("#hddIdc").val()=="")
	            window.location="actualizar_historia.php?cedula=" + cedula;
          else{
             eval(data); //aquí vienen los datos de la pagina php
             $("#tbl_datos_personales").show();
          }		         
             // $("#cmdGuardar").removeAttr('disabled');
             // $("#cboMedico").focus();
			}                
        else
				{ 
	           alert("Paciente no Existe");
            //$("#tbl_datos_personales").hide();
            //$("#cmdGuardar").attr("disabled","disabled");
            //$("#tbl_datos_personales");
             $("#tbl_datos_personales").show();
            $(':input','#tbl_datos_personales').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            
            //Abrir Ventana para Carga de Paciente
            url = "paciente_nuevo.php?cedula=" + cedula + "&modo=N";
            PopupCenter(url, "Registro de Paciente", 800, 400);
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
function LlenarAgentes(agnt)
{
        url ="cargar_combo_db.php?tabla=tbl_riesgos&campo1=uid_riesgo&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Elija un Exposicion]&where=agente='" + agnt + "'"
        CargarCombo($("#cbodescripcion_agente"), url);
        
        //alert(url);
        
}
function LlenarAnatomia(anat)
{
        url ="cargar_combo_db.php?tabla=tbl_anatomia&campo1=uid_anatom&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Elija una Patologia]&where=tipo='" + anat + "'"
        CargarCombo($("#cbofk_anatomia"), url);
        
        //alert(url);
        
}
function AgregarFilaCargos()
{
    
    $("#tblcargos").after("<tr><td width='135px'><input name='cargos[]' type='hidden' value='" + $("#txtCargoAnt").val() + "'/>" + $("#txtCargoAnt").val() + "</td> <td width='135px'><input  name='actividad[]' type='hidden' value='" + $("#txtActividad").val() +  "'/>" + $("#txtActividad").val() + "</td><td width='85px'><input name='desde[]' type='hidden' value='" + $("#txtDesde").val() + "'/>" + $("#txtDesde").val() + "</td><td width='82px'>" + "<input name='hasta[]' type='hidden' value='" + $("#txtHasta").val() + "'/>" + $("#txtHasta").val() + "</td><td width='200px'>" + "<input name='riesgos[]' type='hidden' value='" + $("#txtRiesgosExp").val() + "'/>" + $("#txtRiesgosExp").val() + "</td><td width='14px'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");
}

function AgregarFilaAntecedentesFam()
{
    
    $("#tblAntecedentesfam").after("<tr><td width='54%'><input name='antecedentes[]' type='hidden' value='" + $("#cboAntecedentesfam").val() + "'/>" + $("#cboAntecedentesfam option:selected").text() + "</td> <td width='20%'><input name='familia[]'  type='hidden' value='" + $("#cbofamiliar option:selected").text() +  "'/>" + $("#cbofamiliar option:selected").text() + "</td> <td width='20%'><input name='estatusfamilia[]'  type='hidden' value='" + $("#cboestatusfamiliar option:selected").text() +  "'/>" + $("#cboestatusfamiliar option:selected").text() + "</td> <td width='6%'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");

}

function AgregarFilaAgente()
{
    
    $("#tblAgentes").after("<tr><td width='150px'><input name='distagente[]' type='hidden' value='" + $("#cboAgentes option:selected").text() + "'/>" + $("#cboAgentes option:selected").text() + "</td> <td width='200px'><input name='descagente[]' type='hidden' value='" + $("#cbodescripcion_agente").val() +  "'/>" + $("#cbodescripcion_agente option:selected").text() + "</td> <td width='200px'><input name='txtdatosre[]' type='hidden' value='" + $("#txtdatosreq").val() +  "'/>" + $("#txtdatosreq").val() + "</td> <td  width='136px'><input name='tiempoexp[]' type='hidden' value='" + $("#txttiempoexp").val() + " " +$("#cbotiempoexp").val() + "'/>" + $("#txttiempoexp").val() + " " +$("#cbotiempoexp").val() + "</td><td width='14px'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");
}

function AgregarFilaPatologia()
{
    
    $("#tblAntpatologicos").after("<tr><td width='100px'><input name='tipoanat[]' type='hidden' value='" + $("#cboanatomia option:selected").text() + "'/>" + $("#cboanatomia option:selected").text() + "</td> <td width='150px'><input  name='uidanatom[]' type='hidden' value='" + $("#cbofk_anatomia").val() +  "'/>" + $("#cbofk_anatomia option:selected").text() + "</td><td width='236px'><input name='observacionpatog[]' type='hidden' value='" + $("#txtobservacion").val() + "'/>" + $("#txtobservacion").val() + "</td><td width='14px'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");
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

function llenarinput(nombinput, url)
{
    //$cboMotivos = $(nombinput);   
    $.ajax(url).done(function(data){
            $(nombinput).empty();
            $(nombinput).val(data);
            
            }

    );  
}

function datosrequeridos(idagente)
{
    url ="cargar_inputtext_db.php?tabla=tbl_riesgos&campo1=datos_requeridos&where=uid_riesgo=" + idagente + ""
    llenarinput ($("#txtdatosreq"), url);

}
//(*--------------------------------------------*)
/*
function recibirQS(parametros){
var urlPag = window.location.search.substring(1);
var urlVars = urlPag.split('&');
for (var i = 0; i < urlVars.length; i++){
var nombreParam = urlVars[i].split('=');
if(nombreParam[0] == parametros){
return nombreParam[1];
}
}}
*/

$(document).ready(function(){
    //alert("aqui");
    
    //ocultar la tabla de datos personales  
    $("#tbl_datos_personales").hide();    
    
    CargarCombo($("#cboParaMedico"),"cargar_combo_db.php?tabla=tbl_paramedicos&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo&selected=0&firsttext=[Elija un Paramedico]");
    
    CargarCombo($("#cboanatomia"),"cargar_combo_db.php?tabla=tbl_anatomia&campo1=tipo&selected=0&firsttext=[Ninguna...]");

    CargarCombo($("#cboAgentes"),"cargar_combo_db.php?tabla=tbl_riesgos&campo1=agente&selected=0&firsttext=[Ninguno...]");
    
    //CargarCombo($("#cboPatologias"),"cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]");
    
    CargarCombo($("#cboAntecedentesfam"), "cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=tipo&where=tipo<>'Nueva'&firsttext=[Elija una Enfermedad]");


    var parametro = recibirQS('cedula');
    if(parametro != undefined){
        IrPaciente(getParameterByName(parametro));
    }

    var parametro = recibirQS('ced');
    if(parametro != undefined){
        IrPaciente(getParameterByName(parametro));
    }


    //CargarCombo($("#cboRemitido"), "cargar_combo_db.php?tabla=tbl_remitido&campo1=uid&campo2=descripcion&selected=0&orderby=&firsttext=[NO REMITIDO]");

    //CargarCombo($("#cboReposo"), "cargar_combo_db.php?tabla=tbl_reposo&campo1=uid&campo2=descripcion&selected=0&orderby=uid &firsttext=[SIN REPOSO]");
    
});

function recibirQS(parametros){
var urlPag = window.location.search.substring(1);
var urlVars = urlPag.split('&');
var nombreParam='';
for (var i = 0; i < urlVars.length; i++){
   nombreParam = urlVars[i].split('=');
   if(nombreParam[0] == parametros){
      return nombreParam[0];
    }
}
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function calc_imc(objeto){
if ($(objeto).val()>0){
    var talla = $("#txttalla").val();
    var peso = $("#txtpeso").val();
    var imc = 0;
    if (peso > 0)
       imc = Math.round((peso / (talla * talla)) * 100)/100;
    document.getElementById("txtimc").value= imc;   
  }
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
<article id="consulta" title='Registro de Historia Medica'>

<!-- AQUI EL CONTENIDO 
-->
<form id="formulario" method='post'>
 <input id='hddIdc' name='hddIdc' type='hidden' value='<?php echo $idc; ?>'/>
 <section class="ac-container">
                <div>
                    <input class="acordion" id="ac-1" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-1">PACIENTE</label>
                    <article class="ac-medium">

    <br> 
    <table width="100%">    
     <tr>
     <td width="60%">  
    <input class="form-control" id="ci" name="ci" type="text" style="z-index: 0;" placeholder="Cédula o Pasaporte V/P/E########" onkeypress="VerificarEnter(event);" value="<?php echo $ced; ?>" onblur="$(this).val($(this).val().toUpperCase());" />
    <input id="txtIdh" name="txtIdh" type="hidden" />
   
    </td>
    <td width="40%">
     <span class="input-group-btn">
        <button id="" class="btn btn-default" type="button" onclick="IrPaciente($(ci).val());">Ver</button>
      </span>
    </td>
    </tr>  
    </table>  
      
    
    <table id="tbl_datos_personales" width="100%">    
       
    <tr>
        <td>CI/Pasaporte<input id='txtId' name='txtId' type='hidden' value=''/></td>
        <td>Nombre Completo</td>
        <td>Fecha de Nac.</td>
        <td>Cargo</td>
    </tr>
    <tr>
        <td><input id='txtCi' name='txtCi' type='text' value='' readonly=""/></td>
        <td><input id='txtNombre' type='text' value='' readonly="" /></td>
        <td><input id='txtFechaNac' type='text' value='' readonly="" /></td>
        <td><input id='txtCargo' type='text' value='' readonly=""/></td>
    </tr>    
    <tr>
        <td>Gerencia</td>
        <td>Departamento</td>
        <td>Contratista</td>
        <td>Tipo de Sangre</td>
    </tr>
    <tr>
        <td><input id='txtGerencia'  type='text' value='' readonly=""/></td>
        <td><input id='txtDepartamento'  type='text' value='' readonly="" /></td>
        <td><input id='txtContratista' type='text' value='' readonly=""/></td>
        <td><input id='txttipo_sangre' type='text' value=''/></td>
    </tr>
    <tr> 
        <td>Mano Dominante</td>
        <td>Sexo</td>
        <td>Edo Civil</td>
        <td>telefono</td>
    </tr>
    <tr>
        
        
        <td><input type='text' readonly="" name="cbomano_dominante" id="cbomano_dominante" ></td>
        <td><input id='txtSexo'  type='text' value='' readonly=""/></td>
        <td><input id='txtEdocivil'  type='text' value='' readonly=""/></td>
        <td><input id='txttelefono'  type='text' value='' readonly=""/></td>
    </tr>

    <tr> 
        <td>Tipo Discapacidad</td>
        <td>Desc. Discapacidad</td>
        <td>Alergia</td>        
        <td>Estado Paciente</td>
    </tr>
    <tr>
        
        
        <td><input type='text' readonly="" name="tipo_disca" id="tipo_disca"></td>
       <td> <input id='discapacidad' name="discapacidad" type='text' value=''/></td>
       <td> <input id='alergia' name="alergia" type='text' value=''/></td>
       <td> <input id='estado_paciente' name="estado_paciente" type='text' value='' readonly="" /></td>
    </tr>

    <tr> 
        <td> &nbsp;</td>
        <td> &nbsp;</td>
        <td> &nbsp;</td>
        <td> &nbsp;</td>
    </tr>   
    <tr>
        
        <td>&nbsp;</td>
        <td> &nbsp;</td>
        <td><button id="" class="btn btn-default" type="button" onclick="ModificarPaciente($(txtCi).val());">Ver Mas Datos...</button></td>
        <td> &nbsp;</td>
    </tr>
    
    </table>
   
<br>
    <table width="100%">      
    <tr>
        <td width="20%">Médico de Guardia:</td>
        <td width="20%"><input type='text' name="fk_medico" id="fk_medico" readonly value="<?php echo $_SESSION['username']; ?>" class="form-control" />
            <input type='hidden' name="idmedico" id="idmedico" value="<?php echo $Regmedico['uid']; ?>" class="form-control" />
       </td>
       <td width="20%">&nbsp;</td>
       <td width="20%">Paramedico de Guardia:</td>
       <td width="20%"><select name="cboParaMedico" id="cboParaMedico" class="form-control"></select>
       </td>

    </tr>
    </table>
<br>

</article>
                </div>
                <div>
                    <input class="acordion" id="ac-2" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-2">CARGOS ANTERIORES EN LA MISMA EMPRESA</label>
                    <article class="ac-medium">
  
        
<!-- TABLA PARA CAGOS ANTERIORES EN LA EMPRESA -->

<table>     
    <tr>
        <td width="20%"><label class="etiqueta">Cargos</label></td>
        <td width="20%"><label class="etiqueta">Actividad lab. y area donde la desempeñaba</label></td>
        <td width="12%"><label class="etiqueta">Desde</label></td>
        <td width="12%"><label class="etiqueta">Hasta</label></td>
        <td width="30%"><label class="etiqueta">Riesgos o procesos peligrosos expuestos</label></td>
        <td width="6%">&nbsp;</td>
    </tr>
    <tr>
        <td><INPUT id="txtCargoAnt" type="text"  class="form-control" /></td>
        <td><INPUT id="txtActividad" type="text"  class="form-control" /></td>
        <td><INPUT id="txtDesde" type="text"  class="form-control" /></td>
        <td><INPUT id="txtHasta" type="text"  class="form-control" /></td>
        <td><INPUT id="txtRiesgosExp" type="text"  class="form-control" /></td>
        <td><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="AgregarFilaCargos();" /></td>
    </tr>
</table>

<table style="border: none" class="table-bordered" id="tblcargos">
</table>

<!-- FIN TABLA PARA CAGOS ANTERIORES EN LA EMPRESA -->

</article>
                </div>
                <div>
                    <input class="acordion" id="ac-3" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-3">ANTECEDENTES FAMILIALES DEL TRABAJADOR</label>
                    <article class="ac-large">   

        <!-- TABLA PARA ANTECEDENTES FAMILIARES -->


<table width="100%">    
    <tr>
        <td width="54%"><label class="etiqueta">Enfermedad</label></td>
        <td width="20%"><LABEL class="etiqueta">Familiar que la Padece</LABEL></td> 
        <td width="20%"><LABEL class="etiqueta">Estatus</LABEL></td>         
        <td width="6%">&nbsp;</td>
    </tr>
    <tr>
        <td><select name="cboAntecedentesfam" id="cboAntecedentesfam" class="form-control" ></select></td>
        <td><select name="cbofamiliar" id="cbofamiliar" class="form-control" >
            <option value=''>Elija Familiar...</option>
            <option value='Padre'>Padre</option>
            <option value='Madre'>Madre</option>
            <option value='Abuelo'>Abuelo</option>
            <option value='Abuela'>Abuela</option>
            <option value='Hermano'>Hermano</option>
            <option value='Hermana'>Hermana</option>
            <option value='Tio'>Tio</option>
            <option value='Tia'>Tia</option>
            <option value='Otro Familiar'>Otro Familiar</option>
        </select></td>

         <td><select name="cboestatusfamiliar" id="cboestatusfamiliar" class="form-control" >
            <option value='Vivo'>Vivo</option>
            <option value='Fallecido'>Fallecido</option>            
        </select></td>
        
        <td><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="AgregarFilaAntecedentesFam();" /></td>
    </tr>
</table>

<table width="100%" class="table-bordered" width="85%" id="tblAntecedentesfam">
</table>

<!-- FIN TABLA PARA ANTECEDENTES FAMILIARES -->

</article>
                </div>
                <div>
                    <input class="acordion" id="ac-4" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-4">ANTECEDENTES OCUPACIONALES</label>
                    <article class="ac-large">
        <!-- TABLA PARA ANTECEDENTES OCUPACIONALES -->


<table width="100%">    
    <tr>
        <td width="20%"><label class="etiqueta">Agentes</label></td>
        <td width="50%"><LABEL class="etiqueta">Descripcion de la Exposicion</LABEL></td><td width="30%"><LABEL class="etiqueta">Resp.</LABEL></td>     
        <td width="10%"><LABEL class="etiqueta">Tiempo</LABEL></td>
        <td width="14%"><LABEL class="etiqueta">Periodo</LABEL></td>
        <td width="6%">&nbsp;</td>
    </tr>
    <tr>
        <td><select name="cboAgentes" id="cboAgentes" onchange="LlenarAgentes($(this).val());" class="form-control" ></select></td>
        <td><select name="cbodescripcion_agente" id="cbodescripcion_agente" class="form-control" onchange="datosrequeridos($(this).val());" ></select></td>
        <td width="30%"><INPUT type="text" id="txtdatosreq" name="txtdatosreq"  width="78%" class="form-control"/></td>
        <td><INPUT id="txttiempoexp" type="text"  class="form-control" /></td> 
        <td><select name="cbotiempoexp" id="cbotiempoexp" class="form-control" >
            <option value=''></option>
            <option value='Año(s)'>Año(s)</option>
            <option value='Mes(es)'>Mes(es)</option>
            <option value='Dia(s)'>Dia(s)</option>            
        </select></td>      
        <td><INPUT id="cmdAgregar" type="button" value="+" class="form-control" onclick="AgregarFilaAgente();" /></td>
    </tr>
</table>

<table class="table-bordered" width="85%" id="tblAgentes">
</table>

<!-- FIN TABLA PARA ANTECEDENTES OCUPACIONALES -->
  </article>
                </div>
                <div>
                    <input class="acordion" id="ac-5" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-5">ACCIDENTE OCUPACIONALES</label>
                    <article class="ac-medium">
        <!-- PREGUNTAS -->

<table>
    <tr>
        <td width="20%"><LABEL class="etiqueta" class="etiqueta">¿Ha Sufido Accidentes de Trabajo?</LABEL></td>
        <td width="25%"><select name="cboha_sufrido_accidentes" id="cboha_sufrido_accidentes" class="form-control" >
            <option value=''>Elija Resp...</option>
            <option value='Si'>Si</option>
            <option value='No'>No</option>          
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">¿Que Parte(s) del Cuerpo se Lesiono?</LABEL></td>
        <td width="25%"><INPUT type="text" id="txtpartes_cuerpo_lesionados" name="txtpartes_cuerpo_lesionados"  width="78%" class="form-control"/></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">Fecha del accidente:</LABEL></td>
        <td width="25%"><INPUT type="text" placeholder="AAAA-MM-DD" id="txtfecha_accidente" name="txtfecha_accidente" size="20%" class="form-control" /></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">Secuelas del accidente:</LABEL></td>
        <td width="25%"><INPUT type="text" id="txtdejo_secuelas" name="txtdejo_secuelas"  width="78%" class="form-control"/></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">¿Ha Padecido Alguna Enfermedad Ocupacional?</LABEL></td>
        <td width="25%"><select name="cboha_padecido_enfermeda" id="cboha_padecido_enfermeda" class="form-control" >
            <option value=''>Elija Resp...</option>
            <option value='Si'>Si</option>
            <option value='No'>No</option>          
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">¿Fue Certificada por el INPSASEL?</LABEL></td>
        <td width="25%"><select name="cbofue_certif_inpsasel" id="cbofue_certif_inpsasel" class="form-control" >
            <option value=''>Elija Resp...</option>
            <option value='Si'>Si</option>
            <option value='No'>No</option>          
        </select></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">¿Cambia de Trabajo con Frecuencia?</LABEL></td>
        <td width="25%"><select name="cbocambia_trab_frecuente" id="cbocambia_trab_frecuente" class="form-control" >
            <option value=''>Elija Resp...</option>
            <option value='Si'>Si</option>
            <option value='No'>No</option>          
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%">&nbsp;</td>
        <td width="25%">&nbsp;</td>
    </tr>
</table>
<br>
<!-- FIN PREGUNTAS -->
   </article>
                </div>
                <div>
                    <input class="acordion" id="ac-6" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-6">EXAMEN FUNCIONAL: Antecedentes Patologicos</label>
                    <article class="ac-medium">
        <!-- TABLA PARA examen funcional  -->


<table width="100%">    
    <tr>
        <td width="20%"><label class="etiqueta">Zona Corporal</label></td>
        <td width="50%"><LABEL class="etiqueta">Patologia</LABEL></td>      
        <td width="24%"><LABEL class="etiqueta">Observacion</LABEL></td>     
        <td width="6%">&nbsp;</td>
    </tr>
     <tr>
        <td><select name="cboanatomia" id="cboanatomia" onchange="LlenarAnatomia($(this).val());" class="form-control" ></select></td>
        <td><select name="cbofk_anatomia" id="cbofk_anatomia" class="form-control" ></select></td>
        <td><INPUT id="txtobservacion" type="text"  class="form-control" /></td> 
            
        <td><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="AgregarFilaPatologia();" /></td>
    </tr>
</table>

<table class="table-bordered" width="85%" id="tblAntpatologicos">
</table>

<!-- FIN TABLA PARA examen funcional -->
   </article>
                </div>
                <div>
                    <input class="acordion" id="ac-7" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-7">EXAMEN FUNCIONAL: Habitos</label>
                    <article class="ac-medium">
    <!-- HABITOS -->
<?php 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
$listado=pg_query($cn,"select * from tbl_habitos");
 ?> 

<table>
    
     <?php                    
         while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
          {       
     ?>        
    <tr>
        <td width="20%"><INPUT name="txtfk_habito[]" value="<?php echo $reg['uid_habito'] ?>" type="hidden" /><label class="etiqueta"><?php echo $reg['descripcion'] ?></label></td>
        <td width="20%"><INPUT placeholder="Respuesta" maxlength="15" name="txtresp[]" type="text"  class="form-control" /></td>
        <td><INPUT name="txtobservacionhabitos[]" maxlength="50" placeholder="Observacion" type="text"  class="form-control" /></td>
        
    </tr>
    <?php
     }
     pg_free_result($listado);
    ?>
</table>
<!-- FIN TABLA PARA HABITOS -->
   

 </article>
                </div>

<div>
                    <input class="acordion" id="ac-8" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-8">ANAMNESIS PSICOLOGICOS: Evaluacion de aspectos en el trabajo</label>
                    <article class="ac-medium">

<!-- ANAMNESIS PSICOLOGICO -->
<?php
$listado=pg_query($cn,"select * from tbl_estudios_psicologicos");
 ?> 

<table>
    
     <?php                    
         while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
          {       
     ?>        
    <tr>
        <td width="20%"><INPUT name="txtuid_estudio[]" value="<?php echo $reg['uid_estudio'] ?>" type="hidden" /><label  class="etiqueta"><?php echo $reg['descripcion'] ?></label></td>
        
        <td><INPUT max name="txtobservacionestudio[]" placeholder="Respuesta/Observacion" type="text"  maxlength="100" class="form-control" /></td>
        
    </tr>
    <?php
     }
     pg_free_result($listado);
    ?>
</table>
</article>
                </div>

<div>
                    <input class="acordion" id="ac-9" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-9">EXAMEN FISICO: Signos Vitales</label>
<article class="ac-medium">
<!-- SIGNOS VITALES -->

    <table>
        <tr>
            <td width="10%"><LABEL  class="etiqueta">F. resp:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtfresp" name="txtfresp" size="20%" class="form-control" /></td>
            
            <td width="10%"><LABEL  class="etiqueta">Pulso:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtpulso" name="txtpulso"  width="20%" class="form-control"/></td>
            
            <td width="10%"><LABEL  class="etiqueta">Temp.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txttemper" name="txttemper"  width="20%" class="form-control"/></td>
           
            <td width="10%"><LABEL  class="etiqueta">T. Art.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txttart" name="txttart"  width="20%" class="form-control"/></td>
            <td width="10%"><LABEL  class="etiqueta">F. Card.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtfcard" name="txtfcard"  width="20%" class="form-control" /></td>
        </tr>
        
    </table>
<!-- FIN TABLA PARA SIGNOS VITALES -->

</article>
</div>
<div>
                    <input class="acordion" id="ac-10" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-10">EXAMEN FISICO: Datos antopometricos</label>
                    <article class="ac-medium">
<!-- Datos antopometricos -->

<table>
    <tr>
        <td width="10%"><LABEL class="etiqueta">Talla:</LABEL></td>
        <td width="10%"><INPUT type="text" id="txttalla"  onkeyup="calc_imc(this);" onblur="calc_imc(this);" name="txttalla" size="20%" class="form-control" /></td>
        
        <td width="10%"><LABEL class="etiqueta">Peso:</LABEL></td>
        <td width="10%"><INPUT type="text" id="txtpeso" name="txtpeso"  onkeyup="calc_imc(this);" onblur="calc_imc(this);" width="20%" class="form-control"/></td>
       
        <td width="10%"><LABEL class="etiqueta">IMC:</LABEL></td>
        <td width="10%"><INPUT type="text" id="txtimc" name="txtimc" readonly width="20%" class="form-control"/></td>
        <td width="10%">&nbsp;</td> 
        <td width="10%">&nbsp;</td>
        <td width="10%">&nbsp;</td> 
        <td width="10%">&nbsp;</td> 
    </tr>
    
</table>
<!-- FIN TABLA PARA Datos antopometricos -->
</article>
                </div>

<div>
                    <input class="acordion" id="ac-11" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-11">EXAMEN FISICO: Complemento</label>
                    <article class="ac-medium">

    <!-- examen fiscio -->
<?php
$listado=pg_query($cn,"select * from tbl_estudios_fisicos order by uid_est_fisico");
 ?> 

<table>

<tr>
        <td width="20%"><label class="etiqueta">Fecha Examen Fisico</label></td>   
        <td>
<input type="date" name="txtfechaExmFis" maxlength="10" class="form-control"  placeholder="YYYY-MM-DD" value="" wrap="soft"/>
        </td>
        
    </tr>
    
     <?php                    
         while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
          {       
     ?>        
    <tr>
        <td width="20%"><INPUT name="txtuid_est_fisico[]" value="<?php echo $reg['uid_est_fisico'] ?>" type="hidden" /><label><?php echo $reg['descripcion'] ?></label></td>   
        <td>
<textarea name="txtobservacionestudiofis[]" class="form-control" rows="<?php echo $reg['lineas'] ?>" placeholder="Respuesta"  wrap="soft"></textarea>
        </td>
        
    </tr>
    <?php
     }
     pg_free_result($listado);
    ?>
</table>
<!-- FIN TABLA examen fisico -->

</article>
                </div>

            </section>

            <table class="" width="100%" id="tblGuardar" align="center">    
    <tr>
        <td width="30%">&nbsp;</td>
        <td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar Historia"  class="btn btn-success" onclick="GuardarHISTORIA();"/></td>
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