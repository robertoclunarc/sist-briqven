<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
    require('piedepagina.php');
    require('include_conex.php'); 
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

function GuardarConsulta()
{

  if ($("#txtCi").val()=="" || $("#txtId").val()=="")
  {
    alert("Seleccione un Paciente Registrado");
    $("#ci").focus();
    return;
  }

  /*if ($("#cboMedico").val()=="" || $("#cboMedico").val()=="0")
  {
    alert("Seleccione un Medico de Guardia");
    $("#cboMedico").focus();
    exit(0);
  }*/

  if ($("#cboha_sufrido_accidentes").val()=="" || $("#cboha_sufrido_accidentes").val()=="0")
  {
    alert("Responder: Ha sufrido accidentes?");
    $("#cboha_sufrido_accidentes").focus();
    return;
  }

  if ($("#cboha_padecido_enfermeda").val()=="" || $("#cboha_padecido_enfermeda").val()=="0")
  {
    alert("Responder: Ha padecido enfermedad?");
    $("#cboha_padecido_enfermeda").focus();
    return;
  }

  if ($("#cbocambia_trab_frecuente").val()=="" || $("#cbocambia_trab_frecuente").val()=="0")
  {
    alert("Responder: Cambia de trabajo frecuentemente?");
    $("#cbocambia_trab_frecuente").focus();
    return;
  }

  if ($("#motivo_historia").val()=="" || $("#motivo_historia").val()=="null")
  {
    alert("Responder: Seleccione Motivo");
    $("#motivo_historia").focus();
    return;
  }

  if ($("#txttalla").val()=="")
  {
    alert("Ingrese Talla del paciente");
    $("#txttalla").focus();
    return;
  }

  if ($("#txtpeso").val()=="")
  {
    alert("Ingrese Peso del paciente");
    $("#txtpeso").focus();
    return;
  }

  if ($("#txtimc").val()=="")
  {
    alert("Ingrese IMC del paciente");
    $("#txtimc").focus();
    return;
  }

  if ($("#txtfresp").val()=="")
  {
    alert("Ingrese F. resp del paciente");
    $("#txtfresp").focus();
    return;
  }

  if ($("#txtpulso").val()=="")
  {
    alert("Ingrese Pulso del paciente");
    $("#txtpulso").focus();
    return;
  }

  if ($("#txttemper").val()=="")
  {
    alert("Ingrese Temperatura del paciente");
    $("#txttemper").focus();
    return;
  }

  if ($("#txttart").val()=="")
  {
    alert("Ingrese T. Art. del paciente");
    $("#txttart").focus();
    return;
  }  

 if ($("#motivo_historia").val()=="")
  {
    alert("Ingrese Motivo del Registro en la Historia");    
    return;
  }  

        dir_url = "actualizar_historia_db.php";
        $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
               //alert(data); // Mostrar la respuestas del script PHP.
               if (data=="0")
                    registrar_consulta();
               else
               {
                    alert("La operación Generó un Error:" . data);
               }
               
                //recargar la página para limpiar controles
                //
                //location.reload(); //Recargar la página desde cero.            
           }
         });    

}
//(*--------------------------------------------*)
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
            if (data!="")
            {
                eval(data); //aquí vienen los datos de la pagina php
                $("#tbl_datos_personales").show();
                $("#cmdGuardar").removeAttr('disabled');
               // $("#cboMedico").focus();
                
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
    
    $("#tblAgentes").after("<tr><td width='115px'><input name='distagente[]' type='hidden' value='" + $("#cboAgentes option:selected").text() + "'/>" + $("#cboAgentes option:selected").text() + "</td> <td width='285px'><input name='descagente[]' type='hidden' value='" + $("#cbodescripcion_agente").val() +  "'/>" + $("#cbodescripcion_agente option:selected").text() + "</td> <td width='180px'><input name='txtdatosre[]' type='hidden' value='" + $("#txtdatosreq").val() + "'/>" + $("#txtdatosreq").val() + "</td> <td  width='70px'><input name='tiempoexp[]' type='hidden' value='" + $("#txttiempoexp").val() + " " +$("#cbotiempoexp").val() + "'/>" + $("#txttiempoexp").val() + " " +$("#cbotiempoexp").val() + "</td><td width='14px'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");
}

function AgregarFilaPatologia()
{
    
    $("#tblAntpatologicos").after("<tr><td width='135px'><input name='tipoanat[]' type='hidden' value='" + $("#cboanatomia option:selected").text() + "'/>" + $("#cboanatomia option:selected").text() + "</td> <td width='345px'><input  name='uidanatom[]' type='hidden' value='" + $("#cbofk_anatomia").val() +  "'/>" + $("#cbofk_anatomia option:selected").text() + "</td><td width='160px'><input name='observacionpatog[]' type='hidden' value='" + $("#txtobservacion").val() + "'/>" + $("#txtobservacion").val() + "</td><td width='14px'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");
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



$(document).ready(function(){
    //alert("aqui");
    
    //ocultar la tabla de datos personales  
   // $("#tbl_datos_personales").hide();
    
   // CargarCombo($("#cboMedico"),"cargar_combo_db.php?tabla=tbl_medicos&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo&firsttext=[Elija un médico]&first=0");

    
    CargarCombo($("#cboanatomia"),"cargar_combo_db.php?tabla=tbl_anatomia&campo1=tipo&selected=0&firsttext=[Ninguna...]");

    CargarCombo($("#motivo_historia"),"cargar_combo_db.php?tabla=v_motivos_examen&campo1=uid&campo2=descripcion&selected=0&firsttext=[Elija un Motivo...]");

    CargarCombo($("#cboAgentes"),"cargar_combo_db.php?tabla=tbl_riesgos&campo1=agente&selected=0&firsttext=[Ninguno...]");
    
    //CargarCombo($("#cboPatologias"),"cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]");
    
    CargarCombo($("#cboAntecedentesfam"), "cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=tipo&where=tipo<>'Nueva'&firsttext=[Elija una Enfermedad]");
    

    CargarCombo($("#cboParaMedico"),"cargar_combo_db.php?tabla=tbl_paramedicos&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo&selected=0&firsttext=[Elija un Paramedico]");

    var parametro = recibirQS('cedula');
    if(parametro != undefined){
        IrPaciente(getParameterByName(parametro));
    }

    //CargarCombo($("#cboRemitido"), "cargar_combo_db.php?tabla=tbl_remitido&campo1=uid&campo2=descripcion&selected=0&orderby=&firsttext=[NO REMITIDO]");

    //CargarCombo($("#cboReposo"), "cargar_combo_db.php?tabla=tbl_reposo&campo1=uid&campo2=descripcion&selected=0&orderby=uid &firsttext=[SIN REPOSO]");
    
});

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
</script>
</head>
<body>
 <header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
 </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>
<?php  //echo menu();
  
  
  $ced=isset($_GET['cedula'])?"'".$_GET['cedula']."'":"NULL";  
  
  $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
  $cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
  $Qryhistoria=pg_query($cn,"SELECT * FROM v_historias WHERE ci=".$ced);
  $Reghistoria = pg_fetch_array($Qryhistoria, null, PGSQL_ASSOC);
  $numReg = pg_num_rows($Qryhistoria);
  if ($numReg > 0){
    $idpac=$Reghistoria['uid_paciente'];  
    $idh=$Reghistoria['uid_historia'];
  }
  else{
    $idpac="NULL";  
    $idh="NULL";
    echo '<script  language="javascript"> alert("Paciente no tiene historia. Favor ir a nueva consulta"); </script>';
  }

  $QrycargoAnt=pg_query($cn,"SELECT * FROM tbl_cargos_anteriores WHERE fk_paciente=".$idpac);
  $QrycargoOtr=pg_query($cn,"SELECT * FROM tbl_cargos_anteriores_otras WHERE fk_paciente=".$idpac);
  $QryAntFamil=pg_query($cn,"SELECT * FROM v_antecedentes_famil WHERE fk_paciente=".$idpac);
  $QryRiesgExp=pg_query($cn,"SELECT * FROM v_riesgos WHERE cedula=".$ced);  
  $QryExamFun2=pg_query($cn,"SELECT * FROM v_examen_funcional WHERE cedula=".$ced);
  $QryHabitoPa=pg_query($cn,"SELECT * FROM v_habitos WHERE cedula=".$ced);
  $QryAnalPsic=pg_query($cn,"SELECT * FROM v_analisis_psico WHERE cedula isnull or cedula=".$ced);
  $QryDatAntro=pg_query($cn,"SELECT * FROM tbl_datos_antropometricos WHERE cedula=".$ced." ORDER BY fecha DESC");
  $QrySignVita=pg_query($cn,"SELECT * FROM tbl_signos_vitales WHERE cedula=".$ced." ORDER BY fecha DESC");
  $QryExamFisi1=pg_query($cn,"SELECT * FROM v_examen_fisico WHERE cedula isnull or cedula = ".$ced);    
  $QryhisoriaMedicaAnterior=pg_query($cn,"SELECT fecha_historia, fk_historia, motivo_historia, nombre_medico, observacion, fk_consulta  FROM v_historia_paciente WHERE fecha_historia > '2018-03-20 10:20:19.578328' AND uid_paciente=".$idpac);
  $resultadomedico = pg_query($cn, "SELECT uid FROM tbl_medicos_paramedicos WHERE login = '".$_SESSION['user_session']."'");
  $Regmedico = pg_fetch_array($resultadomedico, null, PGSQL_ASSOC);  

?>

<section id='s1'>
<article id="consulta" title='Registro de Historia Medica'>

<!-- AQUI EL CONTENIDO 
-->
<form id="formulario" method='post'>
 
 <section class="ac-container">
    <div>
        <input class="acordion" id="ac-1" name="accordion-1" type="checkbox" />
        <label class="lblacordion" for="ac-1">PACIENTE</label>
        <article class="ac-medium">
    <br>     
      
    
    <table id="tbl_datos_personales" width="100%">    
       
    <tr>
        <td>CI/Pasaporte<input id='txtId' name='txtId' type='hidden' value='<?php echo $Reghistoria['uid_paciente']; ?>'/><input id="ci" name="ci" type="hidden" value='<?php echo $Reghistoria['ci']; ?>'  /><input id='txtIdh' name='txtIdh' type='hidden' value='<?php echo $Reghistoria['uid_historia']; ?>'/></td>
        <td>Nombre Completo</td>
        <td>Fecha de Nac.</td>
        <td>Cargo</td>
    </tr>
    <tr>
        <td><input id='txtCi' name='txtCi' type='text' value='<?php echo $Reghistoria['ci']; ?>' disabled="disabled"/></td>
        <td><input id='txtNombre' type='text' value='<?php echo $Reghistoria['nombre']; ?>' disabled="disabled" /></td>
        <td><input id='txtFechaNac' type='text' value='<?php echo $Reghistoria['apellido']; ?>' disabled="disabled" /></td>
        <td><input id='txtCargo' type='text' value='<?php echo $Reghistoria['cargo']; ?>' disabled="disabled"/></td>
    </tr>    
    <tr>
        <td>Gerencia</td>
        <td>Departamento</td>
        <td>Contratista</td>
        <td>Tipo de Sangre</td>
    </tr>
    <tr>
        <td><input id='txtGerencia'  type='text' value='<?php echo $Reghistoria['gcia']; ?>' disabled="disabled"/></td>
        <td><input id='txtDepartamento'  type='text' value='<?php echo $Reghistoria['departamento']; ?>' disabled="disabled" /></td>
        <td><input id='txtContratista' type='text' value='<?php echo $Reghistoria['contratista']; ?>' disabled="disabled"/></td>
        <td><input id='txttipo_sangre' type='text' value='<?php echo $Reghistoria['tipo_sangre']; ?>' disabled="disabled"/></td>
    </tr>
    <tr> 
        <td>Mano Dominante</td>
        <td>Sexo</td>
        <td>Edo Civil</td>
        <td>telefono</td>
    </tr>
    <tr>        
        <td><select name="cbomano_dominante" id="cbomano_dominante" disabled="disabled">
            <option <?php if ($Reghistoria['mano_dominante']=='Izquierd@') echo 'selected'; ?> value="Izquierd@">Izquierd@</option>
            <option <?php if ($Reghistoria['mano_dominante']=='Derech@') echo 'selected'; ?> value="Derech@">Derech@</option>            
            <option <?php if ($Reghistoria['mano_dominante']=='Diestr@') echo 'selected'; ?> value="Diestr@">Diestr@</option>
        </select></td>
        <td><input id='txtSexo'  type='text' value='<?php echo $Reghistoria['sexo']; ?>' disabled="disabled"/></td>
        <td><input id='txtEdocivil'  type='text' value='<?php echo $Reghistoria['edo_civil']; ?>' disabled="disabled"/></td>
        <td><input id='txttelefono'  type='text' value='<?php echo $Reghistoria['telefono']; ?>' disabled="disabled"/></td>
    </tr>

    <tr> 
        <td>Tipo Discapacidad</td>
        <td>Desc. Discapacidad</td>
        <td>Alergia</td>
        <td>Estado Paciente</td>
    </tr>
    <tr>
        
        
        <td><select name="tipo_disca" id="tipo_disca" disabled="disabled">
            <option <?php if ($Reghistoria['desc_discapacidad']=='SIN CONDICION') echo 'selected'; ?> value="SIN CONDICION">SIN CONDICION</option>
            <option <?php if ($Reghistoria['desc_discapacidad']=='AUDITIVA') echo 'selected'; ?> value="AUDITIVA">AUDITIVA</option>
            <option <?php if ($Reghistoria['desc_discapacidad']=='MOTRIZ') echo 'selected'; ?> value="MOTRIZ">MOTRIZ</option>
            <option <?php if ($Reghistoria['desc_discapacidad']=='VISUAL') echo 'selected'; ?> value="VISUAL">VISUAL</option>                        
           </select>
       </td>
       <td> <input id='discapacidad' name="discapacidad" type='text' value='<?php echo $Reghistoria['tipo_discapacidad']; ?>' disabled="disabled"/></td>
       <td> <input id='alergia' name="alergia" type='text' value='<?php echo $Reghistoria['alergia']; ?>' disabled="disabled"/></td>
       <td> 
        <select name="estado_paciente" id="estado_paciente" >
          <option <?php if ($Reghistoria['estado_paciente']=='N/A') echo 'selected'; ?>  value="N/A">[N/A]</option>      
          <option <?php if ($Reghistoria['estado_paciente']=='APTO') echo 'selected'; ?>  value="APTO">APTO</option>
          <option <?php if ($Reghistoria['estado_paciente']=='NO APTO') echo 'selected'; ?>  value="NO APTO">NO APTO</option>            
        </select>
       </td>
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
        <td><label>Médico de que aperturó la historia:</label></td>
         <td><?php echo $Reghistoria['medico']; ?></td>
    </tr>
    </table>
<br>

</article>
  </div>

<div>
    <input class="acordion" id="ac-12" name="accordion-1" type="checkbox" />
    <label class="lblacordion" for="ac-12">REGISTRO DE HISTORIA</label>
    <article class="ac-medium">

<table width="100%">    
    <tr>
        <td width="10%"><label class="etiqueta">Fecha</label></td>
        <td width="15%"><label class="etiqueta">Motivo</label></td>
        <td width="40%"><LABEL class="etiqueta">Observacion</LABEL></td> 
        <td width="15%"><LABEL class="etiqueta">Medico</LABEL></td>
        <td width="20%"><LABEL class="etiqueta">Paramedico</LABEL></td>       
    </tr>
     <tr>
        <td><input type='text' name="fecha_historia" id="fecha_historia" value="<?php echo date("Y-m-d H:i"); ?>" readonly class="form-control" /></td>
        <td><select name="motivo_historia" id="motivo_historia" class="form-control" ></select>
        </td>
        <td><input type='text' name="observacion_historia" id="observacion_historia" value="" class="form-control" /></td>
        <td><input type='text' name="fk_medico" id="fk_medico" readonly value="<?php echo $_SESSION['username']; ?>" class="form-control" />
          <input type='hidden' name="idmedico" id="idmedico" value="<?php echo $Regmedico['uid']; ?>" class="form-control" />
        </td>
        <td>
          <select name="cboParaMedico" id="cboParaMedico" class="form-control"></select> 
        </td>     
    </tr>
</table>

<table width="100%" class="table-bordered" id="tblhistoria">
<?php
$cdgHTML='';
while ($recordhisoriaMedicaAnterior=pg_fetch_array($QryhisoriaMedicaAnterior)){   
   $fkconsulta = $recordhisoriaMedicaAnterior['fk_consulta'];
   $nombreparamedico='';
   $cdgHTML=$cdgHTML.' <tr>';
   if ($fkconsulta != ""){
        $cdgHTML=$cdgHTML.' <td width="10%"><a href="consulta_registrada.php?idconsulta='.$fkconsulta.'">'.substr($recordhisoriaMedicaAnterior['fecha_historia'],0,19).'</a></td>';

      $Qrymorvilidad=pg_query($cn,"select paramedico from v_morbilidad where uid = ".$fkconsulta);
      $recordmorvilidad=pg_fetch_array($Qrymorvilidad);
      $nombreparamedico=$recordmorvilidad['paramedico'];
      pg_free_result($Qrymorvilidad);
    }    
    else {
        $cdgHTML=$cdgHTML.' <td width="10%">'.substr($recordhisoriaMedicaAnterior['fecha_historia'],0,19).'</td>';
    }    
   $cdgHTML=$cdgHTML.' <td width="15%">'.$recordhisoriaMedicaAnterior['motivo_historia'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="40%">'.$recordhisoriaMedicaAnterior['observacion'].'</td>';  
   $cdgHTML=$cdgHTML.' <td width="15%">'.$recordhisoriaMedicaAnterior['nombre_medico'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="20%">'.$nombreparamedico.'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  } 
  echo $cdgHTML; 
?>
</table>

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
<?php
$cdgHTML='';
 while ($recordCargoAnt=pg_fetch_array($QrycargoAnt)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td width="135px">'.$recordCargoAnt['cargo'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="135px">'.$recordCargoAnt['actividad_laboral'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="85px">'.$recordCargoAnt['desde'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="82px">'.$recordCargoAnt['hasta'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="200px">'.$recordCargoAnt['riesgos'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  echo $cdgHTML;
?>
</table>

<!-- FIN TABLA PARA CARGOS ANTERIORES EN LA EMPRESA -->

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

<table width="100%" class="table-bordered" id="tblAntecedentesfam">
<?php
$cdgHTML='';
while ($recordAntFamil=pg_fetch_array($QryAntFamil)) {
     $cdgHTML=$cdgHTML.' <tr>';
     $cdgHTML=$cdgHTML.' <td width="54%">'.$recordAntFamil['descripcion'].'</td>';
     $cdgHTML=$cdgHTML.' <td width="20%">'.$recordAntFamil['paterentezco'].'</td>';
     $cdgHTML=$cdgHTML.' <td width="26%">'.$recordAntFamil['estatus_familiar'].'</td>';
     $cdgHTML=$cdgHTML.' </tr>';  
   }
  echo $cdgHTML;
?>
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

<table class="table-bordered"  id="tblAgentes">
<?php
$cdgHTML='';
while ($recordRiesgExp=pg_fetch_array($QryRiesgExp)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td width="115px">'.$recordRiesgExp['agente'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="285px">'.$recordRiesgExp['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="175px">'.$recordRiesgExp['resp'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="70px">'.$recordRiesgExp['tiempo_exposicion'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  } 
  echo $cdgHTML; 
?>
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
            <option <?php if ($Reghistoria['ha_sufrido_accidentes']=='Si') echo 'selected'; ?> value='Si'>Si</option>
            <option <?php if ($Reghistoria['ha_sufrido_accidentes']=='No') echo 'selected'; ?> value='No'>No</option>            
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">¿Que Parte(s) del Cuerpo se Lesiono?</LABEL></td>
        <td width="25%"><INPUT type="text" id="txtpartes_cuerpo_lesionados" name="txtpartes_cuerpo_lesionados" value="<?php echo $Reghistoria['partes_cuerpo_lesionados']; ?>" width="78%" class="form-control"/></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">Fecha del accidente:</LABEL></td>
        <td width="25%"><INPUT type="text" placeholder="AAAA-MM-DD" id="txtfecha_accidente" name="txtfecha_accidente" size="20%" value="<?php echo $Reghistoria['fecha_accidente']; ?>" class="form-control" /></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">Secuelas del accidente:</LABEL></td>
        <td width="25%"><INPUT type="text" id="txtdejo_secuelas" name="txtdejo_secuelas" value="<?php echo $Reghistoria['dejo_secuelas']; ?>" width="78%" class="form-control"/></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">¿Ha Padecido Alguna Enfermedad Ocupacional?</LABEL></td>
        <td width="25%"><select name="cboha_padecido_enfermeda" id="cboha_padecido_enfermeda" class="form-control" >            
            <option <?php if ($Reghistoria['ha_padecido_enfermeda']=='Si') echo 'selected'; ?> value='Si'>Si</option>
            <option <?php if ($Reghistoria['ha_padecido_enfermeda']=='No') echo 'selected'; ?> value='No'>No</option>          
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%"><LABEL class="etiqueta">¿Fue Certificada por el INPSASEL?</LABEL></td>
        <td width="25%"><select name="cbofue_certif_inpsasel" id="cbofue_certif_inpsasel" class="form-control" >
            <option <?php if ($Reghistoria['fue_certif_inpsasel']=='') echo 'selected'; ?> value=''>--</option>            
            <option <?php if ($Reghistoria['fue_certif_inpsasel']=='Si') echo 'selected'; ?> value='Si'>Si</option>
            <option <?php if ($Reghistoria['fue_certif_inpsasel']=='No') echo 'selected'; ?> value='No'>No</option>
                       
        </select></td>
    </tr>
    <tr>
        <td width="20%"><LABEL class="etiqueta">¿Cambia de Trabajo con Frecuencia?</LABEL></td>
        <td width="25%"><select name="cbocambia_trab_frecuente" id="cbocambia_trab_frecuente" class="form-control" >
            <option <?php if ($Reghistoria['cambia_trab_frecuente']=='') echo 'selected'; ?> value=''>--</option>
            <option <?php if ($Reghistoria['cambia_trab_frecuente']=='Si') echo 'selected'; ?> value='Si'>Si</option>
            <option <?php if ($Reghistoria['cambia_trab_frecuente']=='No') echo 'selected'; ?> value='No'>No</option>          
        </select></td>
        <td width="5%">&nbsp;</td>
        <td width="25%">&nbsp;</td>
        <td width="25%">&nbsp;</td>
    </tr>
</table>

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

<table width="100%" class="table-bordered" id="tblAntpatologicos">
<?php
$cdgHTML='';
while ($recordRiesgExp2=pg_fetch_array($QryExamFun2)){
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td width="20%">'.$recordRiesgExp2['tipo'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="50%">'.$recordRiesgExp2['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="30">'.$recordRiesgExp2['observacion'].'</td>';  
   $cdgHTML=$cdgHTML.' </tr>';
  } 
  echo $cdgHTML; 
?>
</table>

<!-- FIN TABLA PARA examen funcional -->
   </article>
                </div>
                <div>
                    <input class="acordion" id="ac-7" name="accordion-1" type="checkbox" />
                    <label class="lblacordion" for="ac-7">EXAMEN FUNCIONAL: Habitos</label>
                    <article class="ac-medium">
    <!-- HABITOS -->
<table>
    
     <?php
         $cont_habitos = pg_num_rows($QryHabitoPa);
         if ($cont_habitos > 0){                    
           while ($recordHabitoPa=pg_fetch_array($QryHabitoPa)) {                
     ?>        
    <tr>
        <td width="20%"><INPUT name="txtfk_habito[]" value="<?php echo $recordHabitoPa['uid_habito'] ?>" type="hidden" /><label class="etiqueta"><?php echo $recordHabitoPa['descripcion'] ?></label></td>
        <td width="20%"><INPUT placeholder="Respuesta" maxlength="15" name="txtresp[]" type="text"  class="form-control" value="<?php echo $recordHabitoPa['resp']; ?>" /></td>
        <td><INPUT name="txtobservacionhabitos[]" maxlength="50" value="<?php echo $recordHabitoPa['observacion']; ?>" placeholder="Observacion" type="text"  class="form-control" /></td>        
    </tr>
     <?php
            }
     } else {
          $Qryhabitos=pg_query($cn,"SELECT uid_habito, descripcion FROM tbl_habitos;");
          while ($recordhabitos=pg_fetch_array($Qryhabitos)) {
                ?>        
              <tr>
                  <td width="20%"><INPUT name="txtfk_habito[]" value="<?php echo $recordhabitos['uid_habito'] ?>" type="hidden" /><label class="etiqueta"><?php echo $recordhabitos['descripcion'] ?></label></td>
                  <td width="20%"><INPUT placeholder="Respuesta" maxlength="15" name="txtresp[]" type="text"  class="form-control" /></td>
                  <td><INPUT name="txtobservacionhabitos[]" maxlength="50" placeholder="Observacion" type="text"  class="form-control" /></td>        
              </tr>
               <?php
          }
          pg_free_result($Qryhabitos);
    }    
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
<table>    
     <?php
      $cont_exam_psico = pg_num_rows($QryAnalPsic);      
      if ($cont_exam_psico > 0){ 
          $aux="";
          $j=0;
          $i=0;
          $rq=array();                 
          while ($recordAnalPsic=pg_fetch_array($QryAnalPsic)) {
              $rq[$i]=$recordAnalPsic['uid_estudio'];
              $i++; 
          }
          $rs=array_count_values($rq);
          $QryAnalPsic2=pg_query($cn,"SELECT * FROM v_analisis_psico WHERE cedula isnull  or cedula = ".$ced);
          while ($recordAnalPsic2=pg_fetch_array($QryAnalPsic2)) {
            $j=$recordAnalPsic2['uid_estudio'];
            if ($recordAnalPsic2['uid_estudio']!=$aux){ 
              echo '<tr>
                      <td width="20%" rowspan="'.$rs[$j].'"><INPUT name="txtuid_estudio[]" value="'.$recordAnalPsic2['uid_estudio'].'" type="hidden" />
                      <label  class="etiqueta">'.$recordAnalPsic2['descripcion'].'</label></td>
                      <td rowspan="'.$rs[$j].'"><INPUT name="txtobservacionestudio[]" placeholder="Respuesta/Observacion" type="text" maxlength="100" class="form-control" /></td>
                      <td>'.$recordAnalPsic2['observacion'].'</td>
                      <td>'.$recordAnalPsic2['fecha_estudio'].'</td>         
                    </tr>';
            } else {
                    echo '<tr>           
                    <td class="tg-yw4l">'.$recordAnalPsic2['observacion'].' 
                    </td>
                    <td class="tg-yw4l">'.$recordAnalPsic2['fecha_estudio'].'
                    </td>        
                    </tr>';
          }
          $aux=$recordAnalPsic2['uid_estudio'];   
          } 
      pg_free_result($QryAnalPsic2);          
             
       } else {
                $QryExamPsico=pg_query($cn,"SELECT uid_estudio, descripcion FROM tbl_estudios_psicologicos ORDER BY uid_estudio;");
                  while ($recordExamPsico=pg_fetch_array($QryExamPsico)) {
    ?>        
                  <tr>
                      <td width="20%"><INPUT name="txtuid_estudio[]" value="<?php echo $recordExamPsico['uid_estudio'] ?>" type="hidden" /><label  class="etiqueta"><?php echo $recordExamPsico['descripcion'] ?></label></td>
                  <td><INPUT name="txtobservacionestudio[]" placeholder="Respuesta/Observacion" type="text" value=""  maxlength="100" class="form-control" /></td>        
                  </tr>
    <?php
                }
                pg_free_result($QryExamPsico);
       }        
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
    <td width="10%"><LABEL class="etiqueta">F. resp:</LABEL></td>
    <td width="10%"><LABEL class="etiqueta">Pulso:</LABEL></td>
    <td width="10%"><LABEL class="etiqueta">Temp.:</LABEL></td>
    <td width="10%"><LABEL class="etiqueta">T. Art.:</LABEL></td>
    <td width="10%"><LABEL class="etiqueta">F. Card:</LABEL></td>
    <td width="10%"><LABEL class="etiqueta">Fecha:</LABEL></td>
</tr>

<tr>        
        <td width="10%"><INPUT type="text" id="txtfresp" name="txtfresp" size="20%" class="form-control" /></td>        
        <td width="10%"><INPUT type="text" id="txtpulso" name="txtpulso"  width="20%" class="form-control" /></td>        
        <td width="10%"><INPUT type="text" id="txttemper" name="txttemper"  width="20%" class="form-control" /></td>        
        <td width="10%"><INPUT type="text" id="txttart" name="txttart"  width="20%" class="form-control" /></td>

        <td width="10%"><INPUT type="text" id="txtfcard" name="txtfcard"  width="20%" class="form-control" /></td>        
        <td width="10%">&nbsp;</td>
    </tr>
<?php
while ($recordSignVita=pg_fetch_array($QrySignVita)) { 
?>
  <tr>        
    <td width="10%"><?php echo $recordSignVita['fresp']; ?></td>        
    <td width="10%"><?php echo $recordSignVita['pulso']; ?></td>        
    <td width="10%"><?php echo $recordSignVita['temper']; ?></td>        
    <td width="10%"><?php echo $recordSignVita['tart']; ?></td> 
    <td width="10%"><?php echo $recordSignVita['fcard']; ?></td>       
    <td width="10%"><?php echo substr($recordSignVita['fecha'],0,19); ?></td>
</tr>  
<?php
} 
?>    
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
        <td width="10%"><LABEL class="etiqueta">Peso:</LABEL></td>
        <td width="10%"><LABEL class="etiqueta">IMC:</LABEL></td>
        <td width="5%"><LABEL class="etiqueta">Fecha:</LABEL></td>
</tr>
<tr>  
        <td width="10%"><INPUT type="text" id="txttalla" onkeyup="calc_imc(this);" onblur="calc_imc(this);" name="txttalla" size="20%" class="form-control" /></td>        
        <td width="10%"><INPUT type="text" id="txtpeso" onkeyup="calc_imc(this);" onblur="calc_imc(this);"  name="txtpeso" width="20%" class="form-control" /></td>        
        <td width="10%"><INPUT type="text" id="txtimc" name="txtimc" readonly width="20%" class="form-control" /></td>        
        <td width="10%">&nbsp;</td>         
    </tr>
<?php
while ($recordDatAntro=pg_fetch_array($QryDatAntro)) { 
?> 
    <tr>  
        <td width="10%"><?php echo $recordDatAntro['talla']; ?></td>        
        <td width="10%"><?php echo $recordDatAntro['peso']; ?></td>        
        <td width="10%"><?php echo $recordDatAntro['imc']; ?></td>        
        <td width="10%"><?php echo $recordDatAntro['fecha']; ?></td>         
    </tr>
<?php
} 
?>     
</table>
<!-- FIN TABLA PARA Datos antopometricos -->
</article>
  </div>

<div>
      <input class="acordion" id="ac-11" name="accordion-1" type="checkbox" />
      <label class="lblacordion" for="ac-11">EXAMEN FISICO: Complemento</label>
      <article class="ac-medium">
    <!-- examen fisico -->
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:11px;padding:1px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yw4l{vertical-align:top}
</style>    
<table >
    
 <?php 
 $cont_exam_fisico = pg_num_rows($QryExamFisi1);
 if ($cont_exam_fisico > 0)
 {
      $aux="";
      $j=0;
      $i=0;
      $rr=array();
      while ($recordExamFisi1=pg_fetch_array($QryExamFisi1)) {  
          $rr[$i]=$recordExamFisi1['uid_est_fisico'];
          $i++; 
      }
      $ra=array_count_values($rr);
      $QryExamFisi2=pg_query($cn,"SELECT * FROM v_examen_fisico WHERE cedula isnull  or cedula = ".$ced);
      while ($recordExamFisi2=pg_fetch_array($QryExamFisi2)) {
            
      $j=$recordExamFisi2['uid_est_fisico'];
      if ($recordExamFisi2['uid_est_fisico']!=$aux){
      echo '<tr>
            <td width="20%" class="tg-yw4l" rowspan="'.$ra[$j].'">
            <label>'.$recordExamFisi2['descripcion'].'</label>
            <INPUT name="txtuid_est_fisico[]" value="'.$j.'" type="hidden" />              
            </td>
            <td class="tg-yw4l" rowspan="'.$ra[$j].'">
            <textarea name="txtobservacionestudiofis[]" class="form-control" rows="'.$recordExamFisi2['lineas'] .'placeholder="Respuesta" wrap="soft"></textarea>             
            </td>    
            <td class="tg-yw4l">'.$recordExamFisi2['observacion'].' 
            </td>
            <td class="tg-yw4l">'.$recordExamFisi2['fecha_examen'].'
            </td>        
        </tr>';
      } else {
        echo '<tr>           
            <td class="tg-yw4l">'.$recordExamFisi2['observacion'].' 
            </td>
            <td class="tg-yw4l">'.$recordExamFisi2['fecha_examen'].'
            </td>        
        </tr>';
       }
       $aux=$recordExamFisi2['uid_est_fisico'];   
      }
      pg_free_result($QryExamFisi2);
} else {
        $j=0;
        $QryExamFisi3=pg_query($cn,"SELECT * FROM tbl_estudios_fisicos ORDER BY uid_est_fisico");
        while ($recordExamFisi3=pg_fetch_array($QryExamFisi3)) {
        echo '<tr>
                    <td width="20%" class="tg-yw4l">
                    <label>'.$recordExamFisi3['descripcion'].'</label>
                    <INPUT name="txtuid_est_fisico[]" value="'.$recordExamFisi3['uid_est_fisico'].'" type="hidden" />              
                    </td>
                    <td class="tg-yw4l">
                    <textarea name="txtobservacionestudiofis[]" class="form-control" rows="'.$recordExamFisi3['lineas'] .'placeholder="Respuesta" wrap="soft"></textarea>             
                    </td>
                </tr>';
        }
        pg_free_result($QryExamFisi3);         
}                
?>

</table>
<!-- FIN TABLA examen fisico -->

</article>
        </div>
    </section>
    <table class="" width="100%" id="tblGuardar" align="center">    
    <tr>
        <td  width="30%">&nbsp;</td>
        <td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Actualizar Historia"  class="btn btn-success" onclick="GuardarConsulta();"/></td>
        <td width="30%">&nbsp;</td>
    </tr>
</table>
 
</form>
</article>
</section> 

</body>
</html>
 <?php piedepagina();
  pg_free_result($resultadomedico);
  pg_free_result($Qryhistoria);
  pg_free_result($QrycargoAnt);
  pg_free_result($QrycargoOtr);
  pg_free_result($QryAntFamil);
  pg_free_result($QryRiesgExp);  
  pg_free_result($QryExamFun2);
  pg_free_result($QryHabitoPa);
  pg_free_result($QryAnalPsic);
  pg_free_result($QryDatAntro);
  pg_free_result($QrySignVita);
  pg_free_result($QryExamFisi1);  
  pg_free_result($QryhisoriaMedicaAnterior);
  pg_close($cn);
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