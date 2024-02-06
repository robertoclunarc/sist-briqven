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
<title>Nuevo Medico</title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <!-- Validacion de Fechas -->
	<script src="js/fechas.js"></script> 
	 <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet">   

<body>
 <header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
 </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>

<?PHP 
//$user = "postgres";
//$password = "matesi";
//$dbname = "amedicadb";
//$port = "5432";
//$host = "localhost";

require("include_conex.php");

$ci_enviada = isset($_GET["cedula"])?$_GET["cedula"]:"";    
$modo = isset($_GET["modo"])?$_GET["modo"]:"";    

$uid="0";            
$ci ="";
$nombre= "";
$id_ss= "";
$nro_colegiado ="";
$nombre_ssst ="";
$tipo_ssst = "";
$activo=true;
			
if ($modo=="M")
{
		//Cargar los datos Asociados	
		$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
	//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";
	
	$query = "select * from tbl_medicos where ci='" . $ci_enviada . "'";
	
	//echo $query;
	
	
	$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
	
	$numReg = pg_num_rows($resultado);
	
	if($numReg>0){
	//echo ("encontrado");
		while ($fila=pg_fetch_array($resultado)) {
			$uid = $fila["uid"]; 
			$ci = $fila["ci"];
			$nombre= $fila["nombre"];
			$id_ss = $fila["id_ss"];
			$activo = $fila["activo"];
			$nro_colegiado = $fila["nro_colegiado"];
			$nombre_ssst = $fila["nombre_ssst"];
			$tipo_ssst = $fila["tipo_ssst"];
		}
	}
	//else echo ("No se encontro");
	
	pg_close($conexion);
}

?>


<section id='s1'>
<article id="consulta" >

<!-- AQUI EL CONTENIDO
-->
<form id="formulario" method='post'>
<input type="text" id="txtCiEnviada" hidden="hidden" value="<?php echo($ci_enviada); ?>">
<p><label><?php if ($modo=='M') echo ('ACTUALIZACIÓN DE MEDICO');  else echo('REGISTRO DE NUEVO MEDICO'); ?></label></p>
        
<table width="100%">
<tr>
	<td width="20%"><input size="10" id='txtUid' name='txtUid' type='hidden'  value='<?php echo($uid); ?>' /></td>
	<td width="50%">&nbsp;</td>
	<td width="20%">&nbsp;</td>
	<td width="10%">&nbsp;</td>	
</tr>
<tr>
	<td width="20%">Cédula:</td>
	<td width="50%">Nombre Completo:</td>
	<td width="20%">MPPS:</td>
	<td width="10%">Activo</td>
</tr>
<tr>
	<td><input size="10" id='txtCi' name='txtCi' type='text' value='<?php echo($ci_enviada); ?>'  onblur="$(this).val($(this).val().toUpperCase());"/></td>
	<td><input size="50"  id='txtNombre' name="txtNombre" type='text' value='<?php echo($nombre); ?>'/></td>
	<td><input size="10"  id='id_ss' name="id_ss" type='text' value='<?php echo($id_ss); ?>'/></td>
	<td><input name="chkActivo" id="chkActivo" type="checkbox" <?php if ($activo=='t') echo("checked='checked'");?>  /></td>
</tr>
<tr>
	<td>Nro. Colegiado:</td>
	<td>Nombre SSST:</td>
	<td>Tipo SSST:</td>
	<td>&nbsp;</td>	
</tr>
<tr>
	<td><input size="10" id='nro_colegiado' name='nro_colegiado' type='text' value='<?php echo $nro_colegiado; ?>' /></td>
	<td><input size="50" id='nombre_ssst' name="nombre_ssst" type='text' value='<?php echo ($nombre_ssst); ?>'/></td>
	<td><input size="10" id='tipo_ssst' name="tipo_ssst" type='text' value='<?php echo $tipo_ssst; ?>'/></td>	
	<td>&nbsp;</td>	
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
</tr>
<tr>
   <td width="35%"></td>
   <td width="30%" align="center"><INPUT id="cmdGuardar" type="button"  value=<?php if ($modo=='M') echo ('Actualizar');  else echo('Registrar'); ?> class="form-control"  onclick="<?php if ($modo=='M') echo('Actualizar();'); else echo('Registrar();');?>"/></td>
   <td width="35%"></td>
   <td>&nbsp;</td>
</tr>

</table>

</form>

</article>
</section>
<script  language="javascript">
//(*--------------------------------------------*)
//(*--------------------------------------------*)
function validar() 
{
//Validar
	if ($("#txtCi").val()=="")
	{
		alert("La cédula/pasaporte no puede ser vacía");
		$("#txtCi").focus();
		//exit(0);
		return (0);
	}
	
	if ($("#txtNombre").val()=="")
	{
		alert("El nombre no puede ser vacío");
		$("#txtNombre").focus();
		//exit(0);
		return (0);
	}
	
	
	return (1);	
}

//(*--------------------------------------------*)
function Registrar()
{
		
	if (!validar())
	{
		exit(0);
	}
	
	url = "registrar_medico_db.php";
	$.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("El Médico ha sido agregado Correctmente!");
			   else
			   {
			   		//alert("Error al Registrar Paciente");
					if (data=="1")
					{
						alert("El Paciente Ya encuentraba Registrado");	
					}
					else
						alert("La operación Generó un Error:" . data);
			   }
			   
			   if ($("#txtCiEnviada")!="")
			   {
		 			//alert("cerrando...");
		 			window.close();
		 	   }
           }
         });
		 

}
//(*--------------------------------------------*)

//(*--------------------------------------------*)
function Actualizar()
{
		
	if (!validar())
	{
		exit(0);
	}
	
	
	
	url = "actualizar_medico_db.php";
	$.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("El médico se ha actualizado!");
			   else
			   {
			   		//alert("Error al modificar Paciente");
					if (data=="1")
					{
						alert("El médico no se encuentraba Registrado");	
					}
					else
						alert("La operación Generó un Error:" . data);
			   }
			   
			   if ($("#txtCiEnviada")!="")
			   {
		 			//alert("cerrando...");
		 			window.close();
		 	   }
           }
         });
		 

}

//(*--------------------------------------------*)
function VerificarEnter(e)
{
    if (e.keyCode == 13) {
		IrPaciente($(ci).val());
    }
}
//(*--------------------------------------------*)

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
	
	//ocultar el combode contratista
	//$("#AreaContratista").hide();
	
});


</script>
<body>
</body>
</html>
<?php 
piedepagina();  
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