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
<title>Nuevo Paramédico</title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet"> 
    
</head>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <!-- Validacion de Fechas -->
	<script src="js/fechas.js"></script>

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



$ci_enviada = isset($_GET["cedula"])?$_GET["cedula"]:"";    
$modo = isset($_GET["modo"])?$_GET["modo"]:"";    

$uid="0";            
$ci ="";
$nombre= "";
$activo=true;
			
if ($modo=="M")
{
		//Cargar los datos Asociados	
		$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
	//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";
	
	$query = "select * from tbl_paramedicos where ci='" . $ci_enviada . "'";
	
	//echo $query;
	
	
	$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
	
	$numReg = pg_num_rows($resultado);
	
	if($numReg>0){
	//echo ("encontrado");
		while ($fila=pg_fetch_array($resultado)) {
			$uid = $fila["uid"]; 
			$ci = $fila["ci"];
			$nombre= $fila["nombre"];
			$activo = $fila["activo"];
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
<p><label><?php if ($modo=='M') echo ('ACTUALIZACIÓN DE PARA-MEDICO');  else echo('REGISTRO DE NUEVO PARA-MEDICO'); ?></label></p>
        
<table width="100%">
<tr><td><input size="10" id='txtUid' name='txtUid' type='hidden'  value='<?php echo($uid); ?>' /></td></tr>
<tr><td width="33%">Cédula</td><td width="33%">Nombre Completo</td><td></td><td>Activo</td></tr>
<tr><td><input size="10" id='txtCi' name='txtCi' type='text' value='<?php echo($ci_enviada); ?>'  onblur="$(this).val($(this).val().toUpperCase());"/></td><td><input size="20"  id='txtNombre' name="txtNombre" type='text' value='<?php echo($nombre); ?>'/></td><td></td><td><input name="chkActivo" id="chkActivo" type="checkbox" <?php if ($activo=='t') echo("checked='checked'");?>  /></td></tr>
<tr></tr>

<tr><td><p></p></td></tr>
<tr>
   <td width="35%"></td>
  <td width="30%" align="center"><INPUT id="cmdGuardar" type="button"  value=<?php if ($modo=='M') echo ('Actualizar');  else echo('Registrar'); ?> class="form-control"  onclick="<?php if ($modo=='M') echo('Actualizar();'); else echo('Registrar();');?>"/></td>
  <td width="35%"></td>
</tr>

</table>

</form>

</article>

</section>



 <?php piedepagina(); ?>

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
	
	url = "registrar_paramedico_db.php";
	$.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("El Para-Médico ha sido agregado Correctamente!");
			   else
			   {
			   		//alert("Error al Registrar Paciente");
					if (data=="1")
					{
						alert("El Para-Medico Ya encuentraba Registrado");	
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
	
	
	
	url = "actualizar_paramedico_db.php";
	$.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("El Para-Médico se ha actualizado!");
			   else
			   {
			   		//alert("Error al modificar Paciente");
					if (data=="1")
					{
						alert("El Para-Médico no se encuentraba Registrado");	
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