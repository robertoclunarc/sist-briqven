<?php 
session_start();
if (isset($_SESSION['user_session_ca'])){
    require('libs/menu.php');
    require('piedepagina.php');
    if (($_SESSION['nivel_ca'] == 4)){ 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Acceso Foraneo</title>
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

<script  language="javascript">
//(*--------------------------------------------*)

function GuardarRegistro()
{

  if ($("#cbotipo_personal").val()=="")
  {
    alert("Seleccione un Tipo Personal");    
    return;
  }
  
  if ($("#hddcedula").val()=="")
  {
    alert("Ingrese Cedula del Personal");
    $("#ci").focus();
    return;
  }

  if ($("#cboDepartamento").val()=="" || $("#cboDepartamento").val()=="null")
  {
    alert("Ingrese el Departamento");    
    return;
  }
  
  if ($("#txtResponsable").val()=="")
  {
    alert("Ingrese el Responsable");
    $("#txtResponsable").focus();
    return;
  }
  
  if ($("#nombre").val()=="")
  {
    alert("Ingrese el Nombre del Personal");
    $("#nombre").focus();
    return;
  }
  
  if ($("#cbodireccion").val()=="")
  {
    alert("Ingrese direccion (ENTRADA/SALIDA)");    
    return;
  }
  
  if ($("#cbomotivo").val()=="null" || $("#cbomotivo").val()=="")
  {
    alert("Ingrese el Motivo");    
    return;
  }
  document.getElementById("cmdGuardar").disabled = true;
  mostrar("ver", "images/loading.gif");
    dir_url = "registrar_acceso_fora_db.php";
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
            alert($("#cbodireccion").val() + " Agregada Correctamente!");
            //location.href = "consulta_registrada.php?idconsulta="+data;
            location.reload(); //Recargar la página desde cero.
          }
         else
         {
          alert("La operación Generó un Error:" + data);
          document.getElementById("cmdGuardar").disabled = false;
          ocultar("ver");
         }
         
         }
         });
}
//(*--------------------------------------------*)
function cambiar_direccion(){
  $("#hdddireccion").val($("#cbodireccion").val()); 
}
//(*--------------------------------------------*)
function VerificarEnter(e)
{
    if (e.keyCode == 13) {
    $(ci).val($(ci).val().toUpperCase());
    IrPersonal($(ci).val());
    }
}
//(*--------------------------------------------*)
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    //if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
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
    if (tecla==13) {IrPersonal($(ci).val()); return true;}
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
} 
//(*--------------------------------------------*)

function IrPersonal(cedula)
{
  mostrar("foto", "images/loading.gif");
  //url= new String("");
  //alert("Esta es la cedula:" + cedula);
  url="cargar_datos_foraneos.php?cedula=" + cedula; 
  //alert("Esta es la url: " + url);
  $.ajax(url).done(function(data)
   {
      //alert(data);
      if (data!="0")
      {
        eval(data); //aquí vienen los datos de la pagina php
        $("#tbl_datos_personales").show();
        $("#cbotipo_personal").attr("disabled","disabled");        
        var foto="images/silueta.png";
        $("#cbodireccion").attr("disabled","disabled"); 
        ocultar("foto");     
      }
      else
        { 
	          alert("Personal no Existe");
	          limpiar_campos();
	          desbloquear_campos();
	          $("#hddcedula").val($("#ci").val());
	          ocultar("foto");
	          $("#tbl_datos_personales").show();
	          $("#cbotipo_personal").removeAttr('disabled');
	          $("#cbodireccion").removeAttr('disabled');
	          $(':input','#tbl_datos_personales').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
        } 
   });  
}
//(*--------------------------------------------*)
function IrJefeResponsable(fkunidad)
{
  
  url="cargar_jefe_resposable.php?idunidad=" + fkunidad; 
  
  $.ajax(url).done(function(data)
   {
      //alert(data);
      if (data!="0")
      {
        eval(data);        
        //$("#txtResponsable").val(data);             
      }
      else
        { 
	          $("#txtResponsable").val("");
        } 
   });  
}
//(*--------------------------------------------*)
function desbloquear_campos(){  
  $("#nombre").removeAttr('readonly'); 
  
  $("#txtDepartamento").removeAttr('readonly'); 
  $("#txtResponsable").removeAttr('readonly'); 
 }
//(*--------------------------------------------*)
function existeUrl(url) {
   var http = new XMLHttpRequest();
   http.open('HEAD', url, false);
   http.send();
   return http.status!=404;
}
//(*--------------------------------------------*)
function mostrar(cdiv, foto){
document.getElementById(cdiv).innerHTML="";
if (cdiv=="foto")
  document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="'+foto+'" alt="" height="100%" width="100%">';
else
  document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="'+foto+'" alt="" height="60" width="60">';
$(cdiv).show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(cdiv){
if (cdiv=="foto")
  document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="images/silueta.png" alt="" height="100%" width="100%">';
else
  document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
//$(cdiv).hide();
//$('#loading').hide();
}
//(*--------------------------------------------*)
function limpiar_campos(){
  $("#hddcedula").val("");
  $("#nombre").val("");  
  //$("#cboDepartamento").val("");
  $("#txtResponsable").val("");  
 }
//(*--------------------------------------------*)

function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}
//(*--------------------------------------------*)

$(document).ready(function(){
  //alert("aqui");  
  //ocultar la tabla de datos personales  
  //$("#tbl_datos_personales").hide();
  
  CargarCombo($("#cbomotivo"),"cargar_combo_db.php?tabla=motivos_visitas&campo1=idmotivo&campo2=descripcion_motivo&selected=0&orderby=idmotivo&firsttext=[Elija un motivo]");

  CargarCombo($("#cboDepartamento"),"cargar_combo_db.php?tabla=v_departamentos_jefes&campo1=idunidad&campo2=descripcion_unidad&selected=0&orderby=descripcion_unidad&firsttext=[Elija el Area]");  

});
//(*--------------------------------------------*)

</script>
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/Seguridad-Patrimonial-BigOne.png" width="100%" height="220px" >
  </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>
<section id='s1'>
<article id="consulta" title='Registro de Acceso'>
<!-- AQUI EL CONTENIDO -->
<form id="formulario" method='post'>
  <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_ca']; ?>'/>
  <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_ca']; ?>'/>  
    <p align="right">
    Fecha:<input size="10" readonly="" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("Y-m-d")); ?>'/></p>
    <article>
    <table width="100%">
    <tr>
      <td width="15%"></td>
      <td width="2%">&nbsp;</td>
      <td width="66%"><h3>Acceso de Personal Foraneo</h3></td> 
      <td width="2%">&nbsp;</td>
      <td width="15%"><div id="foto"><IMG SRC="images/silueta.png" width="100%" height="10%" > </div></td>         
    </tr>
        <tr>
         <td width="15%">    
            <div class="input-group">
            <input id="ci" name="ci" type="text" class="form-control" style="z-index: 0;" placeholder="Cédula" maxlength="10" onkeypress="return validar(event)" >        
            <span class="input-group-btn">
              <button id="" class="btn btn-default" type="button" onclick="IrPersonal($(ci).val());">Ver</button>
            </span>
            </div><!-- /input-group -->
        </td>
       <td width="2%"> &nbsp;</td>    
        <td width="66%">
          <input id="nombre" name="nombre" type="text" readonly="" class="form-control" style="z-index: 0;" placeholder="Nombre"  >
        </td>
        <td width="2%"> &nbsp;</td> 
        <td width="15%"><select class="form-control" name="cbotipo_personal" id="cbotipo_personal" >
          <option selected="" value="">[Tipo Personal]</option>
          <option value="VISITANTE">VISITANTE</option>
          <option value="PROVEEDOR">PROVEEDOR</option>
          <option value="ASPIRANTE">ASPIRANTE</option>
          </select>
      </td>    
      </tr>
    </table>
    <input name='hddcedula' id='hddcedula' type='hidden'/>
    <br>
   <table id="tbl_datos_personales" width="100%">
    <tr>
      
      <td width="10%"><label>Departamento:</label></td>
      <td width="35%"><select id='cboDepartamento' name="cboDepartamento" class="form-control" onchange="IrJefeResponsable(this.value)" ></select></td>
      <td width="10%">&nbsp;</td>
      <td width="10%"><label>Responsable:</label></td>
      <td width="35%"><input id='txtResponsable' name="txtResponsable" class="form-control" type='text' value='' readonly="" /></td>     
    </tr>
    
    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>         
    </tr> 
    </table>
 </article>
<br>    
<table width="100%">      
    <tr>      
      <td width="10%" ><label>Direccion:</label></td>
      <td width="35%"><select class="form-control" name="cbodireccion" id="cbodireccion" onchange="cambiar_direccion()" >
          <option selected="" value="ENTRADA">ENTRADA</option>
          <option value="SALIDA">SALIDA</option>
          </select>
          <input name='hdddireccion' id='hdddireccion' value="ENTRADA" type='hidden'/>
      </td>
      <td width="10%">&nbsp;</td>
      <td width="10%" align="rigth"><label>Motivo:</label></td>
      <td width="35%"><select class="form-control" name="cbomotivo" id="cbomotivo" ></select></td>
    </tr>
    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>      
    </tr>    
    
</table>
<p>&nbsp;</p>

<table class="" width="100%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar Acceso"  class="btn btn-success" ondblclick="GuardarRegistro();"/></td>
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