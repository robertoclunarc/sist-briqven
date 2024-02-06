<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nuevo Paciente</title>
    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <!-- Validacion de Fechas -->
	<script src="js/fechas.js"></script>
    
<?php 
session_start();

require('piedepagina.php');
 
//$user = "postgres";
//$password = "matesi";
//$dbname = "amedicadb";
//$port = "5432";
//$host = "localhost";

require("include_conex.php");

$ci_enviada = isset($_GET["cedula"])?$_GET["cedula"]:"";    
$modo = isset($_GET["modo"])?$_GET["modo"]:"null";
$uid="0";            
$ci ="";
$nombre= "";
$apellido= "";
$departamento= "0";
$gerencia= "0";
$esContratista= false;
$contratista= "0";
$fechaNac= "";
$sexo= "M";
$cargo= "";
$tipo_sangre="";
$mano_dominante="";
$discapacidad="";
$tipo_disca="";
$alergia="";
////////////////
$nivel_educativo="";
$fecha_ingreso="";
$antiguedad_puesto="";
$tipo_vivienda="";
$vivienda_propia="";
$medio_transp_trabajo="";
$turno = "";
$frecuencia_rotacion = "";

$nacionalidad= "";
$telefono= "";
$direccion_hab= "";
$lugar_nac= "";
$edo_civil= "";
$estado_paciente= "";

//Cargar los datos Asociados	
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
	//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

$query = "SELECT uid, descrip FROM tbl_nivel_acad ORDER BY peso_nivel";
$rsltd = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
			
if ($modo=="M")
{
	$query = "SELECT p.*, d.id_gerencia, g.nombre as nombre_gerencia, d.descripcion FROM tbl_pacientes p, tbl_departamentos d, tbl_gerencia g WHERE p.id_departamento=d.uid AND d.id_gerencia=g.uid AND ci='" . $ci_enviada . "'";	
	
	$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
	
	$numReg = pg_num_rows($resultado);
	
	if($numReg>0){
	
		while ($fila=pg_fetch_array($resultado)) {
			$uid = $fila["uid_paciente"]; 
			$ci = $fila["ci"];
			$nombre= $fila["nombre"];
			$apellido= $fila["apellido"];
			$departamento= $fila["id_departamento"];
			$gerencia= $fila["id_gerencia"];
			$esContratista= $fila["es_contratista"];
			$contratista= $fila["id_contratista"];
			//$fechaNac= substr($fila["fechanac"],8,2) . "/" . substr($fila["fechanac"],5,2) . "/". substr($fila["fechanac"],0,4)  ;
			$fechaNac= $fila["fechanac"] ;
			$sexo= $fila["sexo"];
			$cargo= $fila["cargo"];
			$tipo_sangre=$fila["tipo_sangre"];
			$mano_dominante=$fila["mano_dominante"];
			
			$turno = $fila["turno"];
  			$frecuencia_rotacion = $fila["frecuencia_rotacion"];
			$nivel_educativo=$fila["nivel_educativo"];			
			$fecha_ingreso=$fila["fecha_ingreso"];
			$antiguedad_puesto=$fila["antiguedad_puesto"];
			$tipo_vivienda=$fila["tipo_vivienda"];
			$vivienda_propia=$fila["vivienda_propia"];
			$medio_transp_trabajo=$fila["medio_transp_trabajo"];

			$nacionalidad= $fila["nacionalidad"];
			$telefono= $fila["telefono"];
			$direccion_hab= $fila["direccion_hab"];
			$lugar_nac= $fila["lugar_nac"];
			$edo_civil= $fila["edo_civil"];

			$discapacidad=$fila["desc_discapacidad"];
			$tipo_disca=$fila["tipo_discapacidad"];
			$alergia=$fila["alergia"];
			$estado_paciente=$fila["estado_paciente"];
		}

		pg_free_result($resultado);
	}
	
	pg_close($conexion);
}

?>

<section id='s1'>
<article id="consulta" title='Registro de Paciente'><!-- AQUI EL CONTENIDO
-->
<form id="formulario" method='post'>
<input type="text" id="txtCiEnviada" hidden="hidden" value="<?php echo($ci_enviada); ?>">
<input type="text" id="hddidpaciente" name="hddidpacinete" hidden="hidden">
<p><?php if ($modo=='null') { ?> <a href='inicio.php'> << Inicio | </a> <?php } ?><label><?php if ($modo=='M') echo ('ACTUALIZACIÓN DE PACIENTE');  else echo('REGISTRO DE NUEVO PACIENTE'); ?></label></p>
 <input id='hddmodo' name='hddmodo' type='hidden'  value='<?php echo ($modo); ?>' />       
<table width="100%">
<tr>
	<td width="33%">Cédula/Pasaporte</td>
	<td> <input id='txtUid' name='txtUid' type='hidden'  value='<?php echo($uid); ?>' /></td>
	<td width="33%">Nombre(s)</td>
	<td> &nbsp;</td>
	<td>Apellido(s)</td>	
</tr>
<tr>
	<td><input size="10" id='txtCi' maxlength="10" name='txtCi' type='text' value='<?php echo($ci_enviada); ?>'  onblur="$(this).val($(this).val().toUpperCase());"/></td>
	<td> &nbsp;</td>
	<td><input id='txtNombre' maxlength="60" name="txtNombre" type='text' value='<?php echo($nombre); ?>'/></td>
	<td> &nbsp;</td>
	<td><input id='txtApellido' maxlength="60" name="txtApellido" type='text' value='<?php echo($apellido); ?>'/></td>
	
</tr>
<tr>
	<td>Sexo</td>
	<td> &nbsp;</td>	
	<td>Fecha de Nac.</td>
	<td> &nbsp;</td>
	<td>Gerencia</td>	
</tr>
<tr>
	<td><select name="cboSexo" id="cboSexo" ><option value="M" <?php if ($sexo=="M") echo("selected"); ?>>Masculino</option><option value="F" <?php if ($sexo=="F") echo("selected"); ?>>Femenino</option></select></td>
	<td> &nbsp;</td>
	<td><input size="10" id="txtFechaNac" placeholder="AAAA-MM-DD" name="txtFechaNac" type='date' value='<?php echo($fechaNac); ?>'/></td>
	<td> &nbsp;</td>
	<td><select name="cboGerencia" id="cboGerencia" onchange="LlenarDepartamentos($(this).val());" ></select></td>
</tr>
<tr>
	<td>Departamento</td>
	<td> &nbsp;</td>
	<td>Cargo</td>	
	<td>Contratista</td>
	<td> &nbsp;</td>	
</tr>
<tr>
	<td><select name="cboDepartamento" id="cboDepartamento" ></select></td>
	<td> &nbsp;</td>
	<td><input id='txtCargo' name="txtCargo" maxlength="100" type='text' value='<?php echo($cargo); ?>'/></td>
	<td><input id="chkContratista" name="chkContratista" type="checkbox" <?php if ($esContratista=='t') echo("checked='checked'");?> onclick="MostrarOcultarContratista($(this).is(':checked'));" /></td>
	<td><span id="AreaContratista"><select name="cboContratista" id="cboContratista" ></select></span></td>
</tr>
<tr>
	<td>Tipo de Sangre</td>
	<td> &nbsp;</td>
	<td>Mano Dominante</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>
<tr>
	<td><input id='txttipo_sangre' maxlength="10" name="txttipo_sangre" type='text' value='<?php echo($tipo_sangre); ?>'/></td>
	<td> &nbsp;</td>
	<td> <select name="cbomano_dominante" id="cbomano_dominante" >
			<option value="Derech@" <?php if ($mano_dominante=="Derech@") echo("selected"); ?>>Derech@</option>
			<option value="Izquierd@" <?php if ($mano_dominante=="Izquierd@") echo("selected"); ?>>Izquierd@</option>
			<option value="Diestr@" <?php if ($mano_dominante=="Diestr@") echo("selected"); ?>>Diestr@</option>
		</select>
	</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>


<tr>
	<td>Turno</td>
	<td>&nbsp;</td>
	<td>Frec. Rotacion</td>
	<td>&nbsp;</td>
	<td>Nivel Educ.</td>
</tr>
<tr>
	<td><input id='txTurno' name="txTurno" maxlength="1" type='text' value='<?php echo($turno); ?>'/></td>
	<td>&nbsp;</td>
	<td><input id='txfrecuencia_rotacion' name="txfrecuencia_rotacion" type='text' value='<?php echo($frecuencia_rotacion); ?>'/></td>
	<td>&nbsp;</td>
	<td> <select name="cbonivel_educativo" id="cbonivel_educativo" >
	<?php  
		while ($niveles = pg_fetch_array($rsltd)){ 
	?>
		<option value="<?php echo $niveles['descrip']; ?>" <?php if ($niveles['descrip']==$nivel_educativo) echo("selected"); ?>><?php echo $niveles['descrip']; ?></option>
	<?php } 
	pg_free_result($rsltd);
	?>

	</select></td>	
</tr>



<tr>
	<td>Antig. Puesto</td>
	<td>&nbsp;</td>
	<td>Tipo Vivienda</td>
	<td>&nbsp;</td>
	<td>Vivienda Propia</td>
</tr>
<tr>
	<td><input id='txtantiguedad_puesto'  placeholder="AAAA-MM-DD" name="txtantiguedad_puesto" type='date' value='<?php echo($antiguedad_puesto); ?>'/></td>
	<td>&nbsp;</td>
	<td><select name="cbotipo_vivienda" id="cbotipo_vivienda" >
			<option value="" <?php if ($tipo_vivienda=="") echo("selected"); ?>>Elija un Tipo de Vivienda</option>
			<option value="Casa" <?php if ($tipo_vivienda=="Casa") echo("selected"); ?>>Casa</option>
			<option value="Apartamento" <?php if ($tipo_vivienda=="Apartamento") echo("selected"); ?>>Apartamento</option>
			<option value="Quinta" <?php if ($tipo_vivienda=="Quinta") echo("selected"); ?>>Quinta</option>
			<option value="Rustica" <?php if ($tipo_vivienda=="Rustica") echo("selected"); ?>>Rustica</option>			
			<option value="Anexo" <?php if ($tipo_vivienda=="Anexo") echo("selected"); ?>>Anexo</option>
			<option value="Habitacion" <?php if ($tipo_vivienda=="Habitacion") echo("selected"); ?>>Habitacion</option>
			<option value="Otro Tipo" <?php if ($tipo_vivienda=="Otro Tipo") echo("selected"); ?>>Otro Tipo</option>
		</select></td>
	<td>&nbsp;</td>
	<td><select name="cbovivienda_propia" id="cbovivienda_propia" >
			<option value="" <?php if ($vivienda_propia=="") echo("selected"); ?>>Elija respuesta</option>
			<option value="Si" <?php if ($vivienda_propia=="Si") echo("selected"); ?>>Si</option>
			<option value="No" <?php if ($vivienda_propia=="No") echo("selected"); ?>>No</option>
			
		</select></td>	
</tr>

<tr>
	<td>Medio Transp.</td>
	<td>&nbsp;</td>
	<td>Fecha Ingreso</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><select name="cbomedio_transp_trabajo" id="cbomedio_transp_trabajo" >
			<option value="" <?php if ($medio_transp_trabajo=="") echo("selected"); ?>>Elija un medio de Transporte</option>
			<option value="Empresa" <?php if ($medio_transp_trabajo=="Empresa") echo("selected"); ?>>De la Empresa</option>
			<option value="Propio" <?php if ($medio_transp_trabajo=="Propio") echo("selected"); ?>>Propio</option>
			<option value="Taxi" <?php if ($medio_transp_trabajo=="Taxi") echo("selected"); ?>>Taxi</option>
			<option value="Familiar" <?php if ($medio_transp_trabajo=="Familiar") echo("selected"); ?>>De un Familiar</option>			
		</select></td>
	
	<td>&nbsp;</td>
	
	<td><input id='txtfecha_ingreso' name="txtfecha_ingreso"  placeholder="AAAA-MM-DD" type='date' value='<?php echo($antiguedad_puesto); ?>'/></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
</tr>

<tr>
	<td>Nacionalidad</td>
	<td> &nbsp;</td>
	<td>Edo. Civil</td>
	<td> &nbsp;</td>
	<td>Telefono</td>
</tr>
<tr>
	<td><input id='txtNacionalidad' name="txtNacionalidad" type='text' value='<?php echo($nacionalidad); ?>'/></td>
	<td> &nbsp;</td>
	<td><select name="cboEdoCivil" id="cboEdoCivil" >
			<option value="" <?php if ($edo_civil=="") echo("selected"); ?>>Elija una opcion</option>
			<option value="Solte@" <?php if ($edo_civil=="Solte@") echo("selected"); ?>>Solte@</option>
			<option value="Casad@" <?php if ($edo_civil=="Casad@") echo("selected"); ?>>Casad@</option>
			<option value="Divorciad@" <?php if ($edo_civil=="Divorciad@") echo("selected"); ?>>Divorciad@</option>
			<option value="Viud@" <?php if ($edo_civil=="Viud@") echo("selected"); ?>>Viud@</option>			
		</select></td>
	<td> &nbsp;</td>
	<td><input id='txtTelefono' maxlength="50" name="txtTelefono" type='text' value='<?php echo($telefono); ?>'/></td>
</tr>
<tr>
	<td>Lugar Nac.</td>
	<td> &nbsp;</td>
	<td>Direccion hab.</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>
<tr>
	<td><input id='txtdireccion_hab' maxlength="500" name="txtdireccion_hab" type='text' value='<?php echo($direccion_hab); ?>'/></td>
	<td> &nbsp;</td>
	<td><input id='txtlugar_nac' maxlength="50" name="txtlugar_nac" type='text' value='<?php echo($lugar_nac); ?>'/></td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>

<tr>
	<td>Alergia</td>
	<td>&nbsp;</td>
	<td>Tipo Discapacidad</td>
	<td>&nbsp;</td>
	<td>Desc. Discapacidad</td>
</tr>
<tr>
	<td><input id='alergia' name="alergia" type='text' value='<?php echo($alergia); ?>'/></td>
	<td> &nbsp;</td>
	<td><select name="tipo_disca" id="tipo_disca" >
			<option value="SIN CONDICION" <?php if ($tipo_disca=="SIN CONDICION") echo("selected"); ?>>SIN CONDICION</option>
			<option value="AUDITIVA" <?php if ($tipo_disca=="AUDITIVA") echo("selected"); ?>>AUDITIVA</option>
			<option value="MOTRIZ" <?php if ($tipo_disca=="MOTRIZ") echo("selected"); ?>>MOTRIZ</option>
			<option value="VISUAL" <?php if ($tipo_disca=="VISUAL") echo("selected"); ?>>VISUAL</option>						
		</select>
	</td>
	<td> &nbsp;</td>
	<td><input id='discapacidad' name="discapacidad" type='text' value='<?php echo($discapacidad); ?>'/></td>
</tr>
<tr>
	<td>Estado Paciente</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>
<tr>
	<td><select name="estado_paciente" id="estado_paciente" >
			<option value="APTO" <?php if ($estado_paciente=="APTO") echo("selected"); ?>>APTO</option>
			<option value="NO APTO" <?php if ($estado_paciente=="NO APTO") echo("selected"); ?>>NO APTO</option>
			
		</select>
	</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>

<tr>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
	<td> &nbsp;</td>
</tr>
<tr>
   <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
   <td width="20%" align="center"><INPUT id="cmdGuardar" type="button"  value=<?php if ($modo=='M') echo ('Actualizar');  else echo('Registrar'); ?> class="form-control"  onclick="<?php if ($modo=='M') echo('Actualizar();'); else echo('Registrar();');?>"/></td>
   <td width="20%">&nbsp;</td>
   <td width="20%">&nbsp;</td>
</tr>
</table>
</form>
</article>
</section>
<?php
piedepagina(); 
?>
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
		
		return (0);
	}

	if ($("#txtCargo").val()=="")
	{
		alert("Ingrese el Cargo al que Aspira");
		$("#txtCargo").focus();
		
		return (0);
	}
	
	
	if ($("#txtNombre").val()=="")
	{
		alert("El nombre no puede ser vacío");
		$("#txtNombre").focus();
		
		return (0);
	}
	
	if ($("#txtApellido").val()=="")
	{
		alert("El apellido no puede ser vacío");
		$("#txtApellido").focus();
		
		return (0);
	}

	
	if ($("#cboSexo").val()=="")
	{
		alert("Seleccione Sexo");
		$("#cboSexo").focus();
		
		return (0);
	}

	if ($("#txtFechaNac").val()=="")
	{
		alert("Ingrese Fecha Nacimiento");
		$("#txtFechaNac").focus();
		
		return (0);
	}

	if ($("#cboGerencia").val()=="")
	{
		alert("Seleccione Gerencia");
		$("#cboGerencia").focus();
		
		return (0);
	}

	if ($("#cboGerencia").val()=="null")
	{
		alert("Seleccione Gerencia");
		$("#cboGerencia").focus();
		
		return (0);
	}

if ($("#cboDepartamento").val()=="")
	{
		alert("Seleccione Departamento");
		$("#cboDepartamento").focus();
		
		return (0);
	}

if ($("#cboDepartamento").val()=="null")
	{
		alert("Seleccione Departamento");
		$("#cboDepartamento").focus();
		
		return (0);
	}	

if ($("#txttipo_sangre").val()=="")
	{
		if ($("#hddmodo").val()!='null')
			document.getElementById("txttipo_sangre").value= "NULL";		
		else{
			alert("Ingrese Tipo Sangre");
			$("#txttipo_sangre").focus();
			return (0);
		}
	}
	
if ($("#txtfecha_ingreso").val()=="")
	{
		alert("Ingrese Fecha Ingreso");
		$("#txtfecha_ingreso").focus();
		
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
	
	dir_url = "registrar_paciente_db.php";
	$.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {

           	
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data>0)
		   		{
		   			if ($("#hddmodo").val()!='null'){
				   			document.getElementById("txtUid").value=data;
				   			window.opener.document.getElementById('txtId').value = $("#txtUid").val();
				   			window.opener.document.getElementById('txtCi').value = $("#txtCi").val();
				   			window.opener.document.getElementById('txtNombre').value = $("#txtNombre").val() + ' ' + $("#txtApellido").val();;
				   			
				   			window.opener.document.getElementById('txtFechaNac').value = $("#txtFechaNac").val();
				   			window.opener.document.getElementById('txtCargo').value = $("#txtCargo").val();
				   			window.opener.document.getElementById('txtGerencia').value = $("#cboGerencia option:selected").text();

				   			window.opener.document.getElementById('txtDepartamento').value = $("#cboDepartamento option:selected").text();	   			
				   			
				   			window.opener.document.getElementById('txtContratista').value = $("#cboContratista").val();

				   			window.opener.document.getElementById('txttipo_sangre').value = $("#txttipo_sangre").val();
				   			window.opener.document.getElementById('cbomano_dominante').value = $("#cbomano_dominante").val();
				   			window.opener.document.getElementById('txtSexo').value = $("#cboSexo").val();
				   			window.opener.document.getElementById('txtEdocivil').value = $("#cboEdoCivil").val();
				   			window.opener.document.getElementById('txttelefono').value = $("#txtTelefono").val();
				   			window.opener.document.getElementById('tipo_disca').value = $("#tipo_disca").val();
				   			window.opener.document.getElementById('discapacidad').value = $("#discapacidad").val();
				   			window.opener.document.getElementById('alergia').value = $("#alergia").val();
				   			window.opener.document.getElementById('estado_paciente').value = $("#estado_paciente").val();


				   			alert("El Paciente ha sido agregado Correctmente!");			   	
				   			window.close();
				   	}
				   	else{
				   		alert("El Paciente ha sido agregado Correctmente!");
				   		location.reload();
				   	}		
		   		}
			   else
			   {
			   		//alert("Error al Registrar Paciente");
					if (data=="-1")
					{
						alert("El Paciente Ya encuentraba Registrado");	
					}
					else
						alert("La operación Generó un Error:" + data);
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
		
	//if (!validar())
	//{
	//	exit(0);
	//}	
	
	dir_url = "actualizar_paciente_db.php";
	$.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //alert(data); // Mostrar la respuestas del script PHP.
			   if (data=="0")
			   		alert("El Paciente se ha actualizado!");
			   else
			   {
			   		//alert("Error al modificar Paciente");
					if (data=="1")
					{
						alert("El Paciente no se encuentraba Registrado");	
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
function MostrarOcultarContratista(mostrar)
{
	//alert(mostrar);
	if (mostrar)
		$("#AreaContratista").show();
	else
	{
		$("#AreaContratista").hide();
		$("#cboContratista option[value=null]").attr("selected",true);
	}
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
function LlenarDepartamentos(gerencia, seleccionado)
{
		url ="cargar_combo_db.php?tabla=tbl_Departamentos&campo1=uid&campo2=descripcion&selected=" + seleccionado + "&orderby=descripcion&firsttext=[Elija un Departamento]&where=id_gerencia%3D" + gerencia
		CargarCombo($("#cboDepartamento"), url);
		
		//alert(url);
		
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
	
	//ocultar el combode contratista
	//$("#AreaContratista").hide();
	
	MostrarOcultarContratista($("#chkContratista").is(':checked'));
	
	url="cargar_combo_db.php?tabla=tbl_Gerencia&campo1=uid&campo2=nombre&selected=<?php echo($gerencia); ?>&orderby=nombre&firsttext=[Elija una Gerencia]";
	
	CargarCombo($("#cboGerencia"), url);

	/*
	url="cargar_combo_db.php?tabla=tbl_nivel_acad&campo1=uid&campo2=descrip&orderby=peso_nivel&firsttext=[Elija un Nivel Acad...]"
		
	CargarCombo($("#cbonivel_educativo"), url);	
	*/

	LlenarDepartamentos(<?php echo($gerencia); ?>,<?php echo($departamento); ?>);

	url="cargar_combo_db.php?tabla=tbl_contratista&campo1=uid&campo2=nombre&selected=<?php echo($contratista); ?>&orderby=nombre&firsttext=[Elija una Contratista]";
	CargarCombo($("#cboContratista"),url);	

});
</script>
<body>
</body>
</html>
