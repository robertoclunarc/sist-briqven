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
<title>Cargar Riesgo Lab.</title>

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

    <p><label>INGRESAR RIESGO LABORAL</label></p>   

<table width="50%">
<tr>
	<td width="100%">DESCRIPCION:</td>
</tr>
<tr>
	<td width="100%"><INPUT type="text" id="txtDescripcion" name="txtDescripcion" maxlength="100" width="100%" class="form-control"/></td>
</tr>
<tr>
	<td width="100%">TIPO:</td>
</tr>
<tr>
	<td width="100%"><select name="cboAgentes" id="cboAgentes" class="form-control" ></select></td>
</tr>
<tr>
  <td width="100%"><INPUT type="text" id="txttipo" name="txttipo" maxlength="100" width="100%" class="form-control"/></td>
</tr>
<tr>
  <td width="100%">DATOS QUE REQUIERE</td>
</tr>
<tr>
  <td width="100%"><INPUT type="text" id="txtreq" name="txtreq" maxlength="50" value="" width="100%" class="form-control"/></td>
</tr>
<tr>
	<td width="100%">&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="table-bordered" width="50%" id="tblGuardar" align="center">
		<tr>
			<td align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar"  class="form-control" onclick="GuardarConsulta();"/></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>

</article>

</section>
 <?php piedepagina(); ?>

<script  language="javascript">
//(*--------------------------------------------*)

//(*--------------------------------------------*)
function GuardarConsulta()
{
    
        url = "registrar_riesgo_db.php";
        $.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
               //alert(data); // Mostrar la respuestas del script PHP.
               if (data=="0")
                    alert("La carga ha sido agregada correctamente!");
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
$(document).ready(function(){
CargarCombo($("#cboAgentes"),"cargar_combo_db.php?tabla=tbl_riesgos&campo1=agente&selected=0&firsttext=[Ninguno...]");
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