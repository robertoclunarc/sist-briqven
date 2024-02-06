<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid'])){
    require('menu.php');
  require('piedepagina.php');
  if (($_SESSION['nivel'] == 1) || ($_SESSION['nivel'] == 2)){
    $horai =date("H");
    if ($horai>=7 && $horai<15)
      $turno=2;
    elseif ($horai>=15 && $horai<23)
        $turno=3;
    else  $turno=1;

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
//document.getElementById("cmdGuardar").disabled = true;    
if ($("#cboPatologias").val()=="")
  {
    seleccionar_diag(1);
  }

if ($("#txtCi").val()=="")
  {
    alert("Seleccione un Paciente Registrado");
    //document.getElementById("cmdGuardar").disabled = false;
    $("#ci").focus();
    return;
  }
if ($("#txtTurno").val()=="")
  {
    alert("Ingrese el Turno");
    //document.getElementById("cmdGuardar").disabled = false;
    $("#txtTurno").focus();
    return;
  }
if ($("#cboMotivos").val()=="null")
  {
    alert("Seleccione el Motivo");
    //document.getElementById("cmdGuardar").disabled = false;
    $("#cboMotivos").focus();
    return;;   
  }

if ($("#cboArea").val()=="null")
  {
    alert("Seleccione el Area del Incidente");
    //document.getElementById("cmdGuardar").disabled = false;
    $("#cboArea").focus();
    return;   
  }

if ($("#cbocondicion").val()=="N/A" && $("#cboMotivos").val()==9)
  {
    alert("Seleccione la Condicion del Paciente");
    $("#cbocondicion").focus();
    return;   
  }

if ( (($("#cboMotivos").val()==7) || ($("#cboMotivos").val()==8) || ($("#cboMotivos").val()==9) || ($("#cboMotivos").val()==10) || ($("#cboMotivos").val()==13) || ($("#cboMotivos").val()==19)))
{
  if ($("#txtfresp").val()=="")
    {
      alert("Debe colocar la frecuencia respiratoria");
      $("#txtfresp").focus();
      return;   
    }

  if ($("#txtpulso").val()=="")
    {
      alert("Debe colocar el numero de pulso");
      $("#txtpulso").focus();
      return;   
    }

  if ($("#txttemper").val()=="")
    {
      alert("Debe colocar el numero de temperatura");
      $("#txttemper").focus();
      return;   
    }

  if ($("#txttart").val()=="")
    {
      alert("Debe colocar el numero de tension arterial");
      $("#txttart").focus();
      return;   
    }

  if ($("#txttalla").val()=="")
    {
      alert("Debe colocar el numero de talla");
      $("#txttalla").focus();
      return;   
    }

  if ($("#txtpeso").val()=="")
    {
      alert("Debe colocar el numero de peso");
      $("#txtpeso").focus();
      return;   
    } 

  if (parseFloat($("#txttalla").val())>0){
        var talla= parseFloat($("#txttalla").val());
        var peso = parseFloat($("#txtpeso").val());
        var imc = Math.round((peso / (talla * talla)) * 100)/100;
        document.getElementById("txtimc").value= imc; 
  }

  if ($("#txtfcard").val()=="")
    {
      alert("Debe colocar la frecuencia cardiaca");
      $("#txtpeso").focus();
      return;   
    }
}

if ($("#txtSintomas").val()=="")
  {
    alert("Debe colocar una descripcion de motivo de la consulta");
    $("#txtSintomas").focus();
    return;   
  }           

  if ($("#txtDiagnostico").val()=="")
  {
    alert("Debe dar su dignostico de la consulta");
    $("#txtDiagnostico").focus();
    return;   
  }  

//document.getElementById("cmdGuardar").disabled = false;

if (($("#hddcons_ant").val()!="") && (($("#cboMotivos").val()==7) || ($("#cboMotivos").val()==8) || ($("#cboMotivos").val()==9) || ($("#cboMotivos").val()==10) || ($("#cboMotivos").val()==13)))
  {
    
    var str = $("#hddcons_ant").val();
    var res = str.split(',');
    var a = res.indexOf($("#cboMotivos").val());
    
    if (a>=0){
      alert("Este Paciente ya tiene cargada una consulta con este motivo para esta fecha");
      $("#cboMotivos").focus();
      document.getElementById("cmdGuardar").disabled = false;
      return; 
    }

  }  
mostrar();  
    dir_url = "registrar_consulta_db.php";
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
            alert("La consulta ha sido agregada Correctamente!");
            location.href = "consulta_registrada.php?idconsulta="+data;
          }
         else
         {
          alert("La operación Generó un Error:" + data);
          ocultar();
          document.getElementById("cmdGuardar").disabled = false;
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
function VerificarCodEt(e)
{
    if (e.keyCode == 13) {
    $(busqueda).val($(busqueda).val().toUpperCase());
    Irpatologia_direc($(busqueda).val());
    }
}
//(*--------------------------------------------*)
function Irpatologia_direc(cod)
{
 if (cod!='')
    {
      url="buscar_patol.php?b=" + cod; 
      $.ajax(url).done(function(data)
       {          
          if (data == "0")
          {     
             //aquí vienen los datos de la pagina php
            alert("No hay Registro");

          }
          else
             eval(data);           
       }
      );
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
        cargar_consultas_anteriores(cedula);
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
function IrPatologia(){ 
    var x=900; 
    var y=470; 
    posicion_x=(screen.width/2); 
    posicion_y=(screen.height/2)-(x/2);   
    var ventana = window.open('libs/listarPatologias.php', "Patologia", "width="+x+",height="+y+",menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
  ventana.focus();
  $(document).ready(function(){
  $.blockUI();
  setTimeout($.unblockUI(),9999);
      }); 
     }
 } 

//(*--------------------------------------------*)
function cargar_consultas_anteriores(cedula)
{    
  url="cargar_consultas_anteriores.php?cedula=" + cedula; 
  //alert("Esta es la url: " + url);
  $.ajax(url).done(function(data)
   {     
      if (data!="")
      {
        eval(data);
        document.getElementById("hddcons_ant").value = data;
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
function AgregarFilaMed()
{
  
  $("#tblMedicamentos").after("<tr><td width='10%'><input style='border:none' size='3' name='codRemedios[]' readonly='readonly'  type='text' value='" + $("#cboMedicamentos").val() + "'/>" + "</td> <td width='66%'><input style='border:none' size='50' name='remedios[]' readonly='readonly' type='text' value='" + $("#cboMedicamentos option:selected").text() +  "'/>" + "</td> <td width='10%'><input style='border:none' size='10' name='medida[]' readonly='readonly'  type='text' value='" + $("#cboMedidas option:selected").text() +  "'/>" + "</td>  <td width='10%'><input size='3' style='border:none' name='cantidades[]' readonly='readonly' type='text' value='" + $("#txtCantidad").val() + "'/>" + "</td><td width='10%'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");

    //$("#tblMedicamentos").after("<tr><td>" + "A" + "</td><td>" + "B" + "</td><td>" + "C" + "</td></tr>")
}
//(*--------------------------------------------*)
function mostrar(){
document.getElementById('ver').innerHTML="";
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="images/loading.gif" alt="" height="60" width="60">';
$('ver').show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(){
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="" alt="" height="1" width="1">';
$('#loading').hide();
$('ver').hide();
}
//(*--------------------------------------------*)
function CargarCombo(nombcombo, url)
{
  //$cboMotivos = $(nombcombo);
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
function seleccionar_diag(id){
  document.getElementById("cboPatologias").value = id;
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
  
  CargarCombo($("#cboMedico"),"cargar_combo_db.php?tabla=v_medico_con_usuario&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo and login='"+$("#txtlogin").val()+"'");

    
    if ($("#txtnivel").val()==1)
      {
        CargarCombo($("#cboParaMedico"),"cargar_combo_db.php?tabla=tbl_paramedicos&campo1=uid&campo2=nombre&selected=0&firsttext=[Elija una Enfermera]&orderby=nombre&where=activo");
        CargarCombo($("#cboMotivos"),"cargar_combo_db.php?tabla=tbl_motivos&campo1=uid&where=activo=true&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Elija un motivo]");
      }
    else  
    {   
      CargarCombo($("#cboParaMedico"),"cargar_combo_db.php?tabla=tbl_paramedicos&campo1=uid&campo2=nombre&selected=0&orderby=nombre&where=activo and login='"+$("#txtlogin").val()+"'");
      CargarCombo($("#cboMotivos"),"cargar_combo_db.php?tabla=v_motivos_no_examen&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Elija un motivo]");
    }
  
  CargarCombo($("#cboArea"),"cargar_combo_db.php?tabla=tbl_areas&campo1=uid&campo2=descripcion&selected=26&orderby=descripcion&firsttext=[Ninguna]");
  
  //CargarCombo($("#cboPatologias"),"cargar_combo_db.php?tabla=tbl_patologias&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&firsttext=[Ninguna]");
  
  CargarCombo($("#cboMedicamentos"), "cargar_combo_db.php?tabla=v_medicamentos_2&campo1=uid&campo2=descripcionlarga&selected=0&orderby=descripcion&where=activo");

  CargarCombo($("#cboMedicamentos2"), "cargar_combo_db.php?tabla=tbl_medicamentos&campo1=descripcion&selected=0&orderby=descripcion");
  
  CargarCombo($("#cboRemitido"), "cargar_combo_db.php?tabla=tbl_remitido&campo1=uid&campo2=descripcion&selected=0&orderby=&firsttext=[NO REMITIDO]");

  CargarCombo($("#cboAfecciones"), "cargar_combo_db.php?tabla=tbl_tipo_afecciones_sistemas&campo1=idafecciones&campo2=descripcion_afeccion&selected=0&orderby=idafecciones&firsttext=[Seleccione Tipo de Afeccion]");

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

function cambio_motivo(){
var mot = $("#cboMotivos").val();
if (mot==1)
  autorizacion.checked = true;
else
  autorizacion.checked = false;
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
    Fecha:<input size="10" readonly="" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("Y-m-d")); ?>'/>&nbsp;Turno:<input size="10"  maxlength="1" name='txtTurno' id='txtTurno' type='text' value='<?php echo $turno; ?>'/></p>
    <article>    
    <div class="input-group">
      <input id="ci" type="text" class="form-control" style="z-index: 0;" placeholder="Cédula o Pasaporte V/P/E########" onkeypress="VerificarEnter(event);" onblur="$(this).val($(this).val().toUpperCase());" >
      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
       <input  name='hddcons_ant' id='hddcons_ant' type='hidden' />
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
    
<table width="100%">      
    <tr>
      <td><?php if ($_SESSION['nivel']==1) {  ?><label>Médico de Guardia:</label><?php } ?></td>
      <td><select class="form-control" <?php if ($_SESSION['nivel']!=1) echo 'disabled style="display:none;"';  ?>  name="cboMedico" id="cboMedico" ></select></td>
      <td> &nbsp;</td>
      <td align="rigth"><label>Personal de Guardia:</label></td>
      <td><select class="form-control" name="cboParaMedico" id="cboParaMedico" ></select></td>
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
      <td><select class="form-control" onchange="cambio_motivo();" name="cboMotivos" id="cboMotivos" ></select></td>
      <td> &nbsp;</td>
      <td align="rigth">&nbsp;&nbsp;&nbsp;&nbsp;<label>Área Incidente o Atencion:</label></td>
      <td><select class="form-control" name="cboArea" id="cboArea" ></select></td>
    </tr>
    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
    </tr>
    <tr>
      <td><label>Patología:</label></td>
      <td><input type="hidden" name="cboPatologias" id="cboPatologias" />
        <input class="form-control" onkeypress="VerificarCodEt(event);" placeholder="Codigo Etica" type="text" id="busqueda" name="busqueda"  />
      </td>  
        
       <td colspan="3"> <div class="input-group">
              <input id="patol" name="patol" type="text" readonly="" class="form-control" style="z-index: 0;" >             
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" onclick="IrPatologia();">Ver</button>
              </span>
            </div>
      </td>
    </tr>
</table>
<p>&nbsp;</p>
<table width="100%">
  <tr>
    <td width="100%"><label>Motivo de la Consulta:</label></td>
  </tr>
  <tr>
    <!-- <td width="100%"><INPUT type="text" id="txtSintomas" name="txtSintomas"  width="100%" class="form-control"/></td> -->
    <td width="100%"><textarea maxlength="100" name="txtSintomas" id="txtSintomas" rows="4"  width="100%" class="form-control"></textarea></td> 
  </tr>
  <TR>
    <td width="100%"><?php if ($_SESSION['nivel']==1) {  ?><label>Enfermedad Actual</label><?php } ?></td>
  </TR>
  <tr>
  <td width="100%"><?php if ($_SESSION['nivel']==1) {  ?>
  	<!-- <INPUT type="text" id="txtObservaciones" name="txtObservaciones" width="100%" class="form-control"/> -->
  	<textarea name="txtObservaciones" id="txtObservaciones" rows="4"  width="100%" maxlength="250" class="form-control"></textarea></td> 
  	<?php }  ?></td>
  </tr>
  <TR>
    <td width="100%">&nbsp;</td>
  </TR>
  <TR>
    <td width="100%"><LABEL>Resultados de la Evaluacion Medica:</LABEL></td>
  </TR>
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
  <tr>
    <td><label>Remitido a:</label>
      <select  name="cboRemitido" id="cboRemitido" ></select>&nbsp;&nbsp;&nbsp;
      <label>Reposo:</label>
      <select name="cboReposo" id="cboReposo" >
        <option value="0">[SIN REPOSO]</option>
      <?php if ($_SESSION['nivel']==1) { ?>
        <option value="1">UN (1) DIA</option>
        <option value="2">DOS (2) DIAS</option>
        <option value="3">TRES (3) DIAS</option>
        <option value="99">RESTO DE LA JORNADA</option> 
      <?php } ?>      
      </select>&nbsp;&nbsp;&nbsp;
      <?php if ($_SESSION['nivel']==1) { ?>
        <label>Condici&oacute;n:</label>
        <select name="cbocondicion" id="cbocondicion" >
          <option value="N/A">[N/A]</option>      
          <option value="APTO" selected>APTO</option>
          <option value="NO APTO">NO APTO</option> 
          <option value="APTO RESTR">APTO CON RESTRICCIONES</option>           
        </select>
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td width="100%">&nbsp;</td>
  </tr> 
  <tr>
    <td width="100%">
      <table>
        <tr>
            <td width="10%"><LABEL  class="etiqueta">F. Resp.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtfresp" name="txtfresp"  maxlength="10" size="20%" class="form-control" /></td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL  class="etiqueta">Pulso:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtpulso" name="txtpulso"  maxlength="10"  width="20%" class="form-control"/></td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL  class="etiqueta">Temp.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txttemper" name="txttemper"  maxlength="10"  width="20%" class="form-control"/></td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL  class="etiqueta">T. Art.:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txttart" name="txttart"  maxlength="10" placeholder="mmhg" width="20%" class="form-control"/></td>
        </tr>    
      </table>
      <br>
      <table>
        <tr>
            <td width="10%"><LABEL class="etiqueta">Talla:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txttalla" placeholder="Mts."  maxlength="10"  onkeyup="calc_imc(this);" onblur="calc_imc(this);" name="txttalla" size="20%" class="form-control" /></td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL class="etiqueta">Peso:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtpeso" placeholder="Kg." name="txtpeso"  maxlength="10"  onkeyup="calc_imc(this);" onblur="calc_imc(this);" width="20%" class="form-control"/> </td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL class="etiqueta">IMC:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtimc" name="txtimc"  maxlength="10" disabled="disabled" width="20%" class="form-control"/></td>
            <td width="5%">&nbsp;</td>
            <td width="10%"><LABEL class="etiqueta">F. Card:</LABEL></td>
            <td width="10%"><INPUT type="text" id="txtfcard" placeholder="xm" name="txtfcard"  maxlength="10" width="20%" class="form-control"/></td>            
        </tr>    
      </table>

    </td>
  </tr>
  <tr>
    <td width="100%">&nbsp;</td>
  </tr> 
  <tr>
    <td width="100%">
        <table width="100%">
        <tr>
            <td width="50%"><br><LABEL>Diagnostico</LABEL></td>
            <td width="50%"><br><LABEL>Tipo de Afecciones por Sistema</LABEL></td>
        </tr>
        <tr>
            <td><INPUT type="text"  maxlength="1000" id="txtDiagnostico" name="txtDiagnostico" width="100%" class="form-control"/></td>
            <td><select class="form-control" name="cboAfecciones" id="cboAfecciones" ></select></td>
        </tr>    
      </table>
    </td>
  </tr>
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="100%"><label>Observaci&oacute;n:</label></td>
  </tr>
  <tr>
    <td width="100%"><INPUT type="text" id="txtObservacionMed" name="txtObservacionMed"  maxlength="250"  width="100%" class="form-control"/></td></tr>
  <tr>
  <tr>
    <td width="100%"><label>Referencia:</label></td>
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
    <td width="100%"><textarea  maxlength="1500" class="form-control" readonly id="txtreferencia" name="txtreferencia" rows="4" cols="50" wrap="soft"></textarea></td></tr>
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
          <option value="Comprimidos">Comprimidos</option>
          <option value="Ampollas">Ampollas</option>
          <option value="C.C.">C.C.</option>
          <option value="Tableta">Tableta</option>          
          <option value="Torunda">Torunda</option> 
		  <option value="Blister">Blister</option>
		  <option value="Aplica">Aplica</option>
        </select>
      </td>
      <td><INPUT id="txtCantidad" type="text" class="form-control" onkeyup="VerificAgregarMedicam(this);" onblur="VerificAgregarMedicam(this);"/></td>
      <td><INPUT id="cmdAgregar" type="button" value="+" class="form-control" onclick="AgregarFilaMed();" disabled="disabled" /></td>
    </tr>
</table>

 <table class="table-bordered" width="85%" id="tblMedicamentos">   
 </table>
<?php if ($_SESSION['nivel']==1) {  ?>
<table>
  <tr>
    <td width="20%">&nbsp;</td>
    <td width="70%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%"><LABEL>Medicina</LABEL></td>
    <td width="70%"><LABEL>Indicaciones</LABEL></td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%"><select name="cboMedicamentos2" id="cboMedicamentos2" class="form-control" ></select></td>
    <td width="70%"><INPUT type="text" id="txtIndicaciones_x" name="txtIndicaciones_x"  width="78%" class="form-control"/></td>   
    <td width="10%"><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="insertaretxt();" /></td>
  </tr>

  <tr>
    <td width="100%" colspan="3"><textarea  maxlength="1000" readonly class="form-control" id="txtIndicaciones" name="txtIndicaciones" rows="3" cols="20" wrap="soft"></textarea></td>
  </tr> 
</table>
<?php }  ?>
<p>&nbsp;</p>
<table width="54%">
<tr>
  <td width="15%"><LABEL>Fecha de la Pr&oacute;xima Cita</LABEL></td>
  <td width="12%"><INPUT type="text"  maxlength="10" placeholder="AAAA-MM-DD" id="txtFechaProxCita" name="txtFechaProxCita" size="20%" class="form-control" /></td>
  <td width="10%">&nbsp;</td>
  <td width="12%"><LABEL>Requiere Autorizaci&oacute;n</LABEL></td>
  <td width="5%"><input class="form-control" type="checkbox" name="autorizacion" id="autorizacion" value="SI"></td>   
</tr>

</table>

<p>&nbsp;</p>

<table class="" width="100%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Consulta"  class="btn btn-success" ondblclick="GuardarConsulta();"/></td>
    <td width="30%"><div id='ver'></div></td>
  </tr>
</table>

</form>
</article>
</section>
</body>
</html>
 <?php piedepagina(); 
}
}else{
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