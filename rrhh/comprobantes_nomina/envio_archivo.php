<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
   require('menu.php');
//	require('piedepagina.php'); 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Envio de Correo</title>
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
	

<script  language="javascript">
//(*--------------------------------------------*)

function Enviar()
{
	
if (($("#asunto").val()=="") || ($("#txtTrabajador").val()=="") || ($("#txtMensaje").val()==""))
	{
		alert("Seleccione los campos requeridos para la generacion de la informacion");
		$("#txtTrabajador").focus();
		//exit(0);
		return;
	}

dir_url = "construir_query_archivo.php";

var file2 = $('#uploadedfile');   //Ya que utilizas jquery aprovechalo...
var archivo = file2[0].files;       //el array pertenece al elemento

var formData = new FormData(document.getElementById("formulario"));
formData.append("uploadedfile", archivo);

$.ajax({
   type: "POST",
   url: dir_url,
  // data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
   data: formData,
   processData: false,
	contentType: false,
   success: function(data)
   {
       //OJO.
	   //alert(data); // Mostrar la respuestas del script PHP.
	   if (data>0)
        if (data==1)
          alert("Se generó un registro y se envió!");
        else
	   		  alert("Se generaron "+data+ " archivos para su envio");
	   else
	   {
		if (data==0)
          alert("No se generó ningun archivo");
        else
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
		$(asunto).val($(asunto).val().toUpperCase());
		Enviar();
    }
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

function agregar_trabajador(ci)
{
if ($(txtTrabajador).val()=="")
	document.getElementById("txtTrabajador").value = ci;
else
	document.getElementById("txtTrabajador").value = $(txtTrabajador).val() + "," + ci;

}


function Limpiar()
{
document.getElementById("txtTrabajador").value = "";
document.getElementById("cboTrabajador").selectedIndex = 0;
}

function Quitar_Ultimo()
{
var cadena = document.getElementById("txtTrabajador").value;
var indeof=cadena.lastIndexOf(',');
var subCadena = "";
if(indeof != -1)
    subCadena = cadena.substring(0, indeof);
document.getElementById("txtTrabajador").value = subCadena;
}

$(document).ready(function(){
//alert("aqui");
CargarCombo($("#cboTrabajador"),"cargar_combo_trabajadores.php?tabla=VW_DATOS_TRAB_SITTWEB_SID&campo1=TRABAJADOR&campo2=NOMBRES&selected=0&orderby=NOMBRES&firsttext=[Seleccione Trabajador]");	
	
});

</script>
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
  </header>
<div id="barramenu">
<?php  echo menu(); ?>
</div>
<section id='s1'>
<article id="consulta" title='Envio de Comprobates de Pago'>

<!-- AQUI EL CONTENIDO 
-->
<form enctype="multipart/form-data" name="formulario" id="formulario" method='POST'>
    
    <p align="right">
    Fecha:<input size="10" readonly="" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("Y-m-d")); ?>'/></p>
    <article>    
    <div class="input-group">
      <input id="asunto" name="asunto" placeholder="Asunto" value="" type="text" class="form-control" style="z-index: 0;" onblur="$(this).val($(this).val().toUpperCase());" >

      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php //echo $_SESSION['user_session']; ?>'/>
      <span class="input-group-btn">
        <button id="" class="btn btn-default" type="button" onclick="Enviar();">Enviar</button>

      </span>
    </div><!-- /input-group -->    
    <p></p>
    
 </article> 

<table width="100%">		
	<tr>
		<td width="10%"><input type="checkbox" title="Enviar a Todos" name="chkTrabajador" id="chkTrabajador"><br><label>Todos</label> </td>
		<td width="10%"><label>Trabajador(es):</label></td>
		<td width="40%"><select onchange="agregar_trabajador($(this).val())" name="cboTrabajador" id="cboTrabajador" class="form-control" ></select></td>
		<td width="40%"></td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	<tr>
		<td width="10%">&nbsp; </td>
		<td width="10%">&nbsp;</td>
		<td width="40%">
			<textarea class="form-control" id="txtTrabajador" name="txtTrabajador" rows="3" cols="50" wrap="soft"></textarea>

		</td>
		<td width="40%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%"><button id="" class="btn btn-default" type="button" onclick="Quitar_Ultimo();">Quitar Ultimo</button><button id="" class="btn btn-default" type="button" onclick="Limpiar();">Limpiar</button></td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	<tr>
		<td width="10%">&nbsp; </td>
		<td width="10%"><label>Mensaje:</label></td>
		<td width="40%">
			<textarea class="form-control" id="txtMensaje" name="txtMensaje" rows="3" cols="50" wrap="soft"></textarea>

		</td>
		<td width="40%">&nbsp;</td>
		<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%"><label>Adjuntar Archivo</label></td> 
		<td width="40%"><input name="uploadedfile" id="uploadedfile" type="file" /></td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	</tr>
</table>

</form>
</article>
</section>
</body>
</html>
 <?php //piedepagina(); 
}
else{
    header('Location: /login/index.php');
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