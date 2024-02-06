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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nueva Descarga</title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/bootstrap-dialog.css" rel="stylesheet">
     <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet"> 
</head>

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
<header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
 </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>

<section id='s1'>
<article id="consulta" >
<!-- AQUI EL CONTENIDO
-->
<form id="formulario" method='post'>

    <p><label>DESCARGA DE MEDICAMENTOS</label></p>
    <p align="right">
    Fecha:<input size="10" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("d/m/Y")); ?>'/></p>
    <article>
    

    </article>
    


<table width="100%">
<TR><td width="100%">RESPONSABLE</td></TR>
<tr><td width="100%"><INPUT type="text" id="txtResponsable" name="txtResponsable"  width="100%" class="form-control"/></td></tr>

<TR><td width="100%">CONCEPTO</td></TR>
<tr><td width="100%"><INPUT type="text" id="txtConcepto" name="txtConcepto"  width="100%" class="form-control"/></td></tr>


<TR><td width="100%"><LABEL>MEDICAMENTOS QUE SE SALEN</LABEL></td></TR>
<tr>
<table>
    <tr><td width="60%">Medicamento</td><td>Cantidad</td><td></td></tr>
    <tr><td><select name="cboMedicamentos" id="cboMedicamentos"   class="form-control" ></select></td><td><INPUT id="txtCantidad" type="text"  class="form-control" onkeyup="VerificAgregarMedicam(this);" onblur="VerificAgregarMedicam(this);"/></td><td><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="AgregarFilaMed();" disabled="disabled" /></td>
    </tr>
</table>
</tr>
<tr>
 <table class="table-bordered" width="100%" id="tblMedicamentos">
   <tr><td width="10%">Cdigo</td><td width="70%">Descripción</td><td width="10%">Cantidad</td><td width="10%"></td></tr>
 </table>
</tr>


<tr>
<table class="table-bordered" width="50%" id="tblGuardar" align="center">
<tr><td align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar Descarga"  class="form-control" onclick="GuardarConsulta();"/></td></tr>
</table>

</tr>

</table>






</form>

</article>

</section>
 <?php piedepagina(); ?>

<script  language="javascript">
//(*--------------------------------------------*)
//(*--------------------------------------------*)
//(*--------------------------------------------*)
//(*--------------------------------------------*)
function GuardarConsulta()
{
	//var dialog = new BootstrapDialog()
	
	//BootstrapDialog.alert("Guardando...");
	//alert("Guardando:Implementar");
	//$('#formulario').trigger("reset");
	
	
	//Validar
	//
	if (validaFechaDDMMAAAA($("#txtFecha").val())==false)
	{
		alert("Introduzca una fecha vlida para la Consulta (dd/mm/aaaa)");
		$("#txtFecha").focus();
		exit(0);
	}
	
	
		url = "registrar_carga_db.php?tipo=C";
		$.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
			   //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("La carga ha sido agregada Correctamente!");
			   else
			   {
					alert("La operacin Gener un Error:" . data);
			   }
			   
			   	//recargar la pgina para limpiar controles
				//
				location.reload(); //Recargar la pgina desde cero.			   
           }
         });
	

}


//(*--------------------------------------------*)

function VerificAgregarMedicam(objeto)
{
	if ($(objeto).val()>0){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
}
//(*--------------------------------------------*)
function AgregarFilaMed()
{
	
	$("#tblMedicamentos").after("<tr><td width='10%'><input size='3' name='codRemedios[]' readonly='readonly'  type='text' value='" + $("#cboMedicamentos").val() + "'/>" + "</td> <td width='70%'><input size='60' name='remedios[]'  readonly='readonly'  type='text' value='" + $("#cboMedicamentos option:selected").text() +  "'/>" + "</td> <td  width='10%'><input size='3'  name='cantidades[]' readonly='readonly' type='text' value='" + $("#txtCantidad").val() + "'/>" + "</td><td width='5%'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove();'/></td></tr>");


		//$("#tblMedicamentos").after("<tr><td>" + "A" + "</td><td>" + "B" + "</td><td>" + "C" + "</td></tr>")
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

$(document).ready(function(){
	//alert("aqui");
	
	CargarCombo($("#cboMedicamentos"), "cargar_combo_db.php?tabla=v_medicamentos&campo1=uid&campo2=descripcionlarga&selected=0&orderby=descripcion&where=activo");

	//CargarCombo($("#cboMedicamentos"), "cargar_combo_db.php?tabla=tbl_medicamentos&campo1=uid&campo2=descripcion&selected=0&orderby=descripcion&where=activo");
		
});

</script>
<body>
</body>
</html>
<?php 
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