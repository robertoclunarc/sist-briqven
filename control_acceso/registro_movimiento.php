<?php 
session_start();
if (isset($_SESSION['username_ca']) && isset($_SESSION['userid_ca']) ){
   require('libs/menu.php');
   require('piedepagina.php'); 
   require_once('libs/conexion.php');
$cn=  Conectarse();			
$sql1 = "SELECT descripcion_unidad FROM v_unidades  WHERE idunidad=" . $_SESSION['unidad_ca'];
$res1 = pg_query($cn,$sql1);
$row1 = pg_fetch_array($res1);

$sql2 = "SELECT v_jefes_de_unidades.* FROM v_jefes_de_unidades, v_ccostos_x_gerencias  where idunidad=ccosto and ccosto=".$_SESSION['unidad_ca'];
print $sql2;
$res2 = pg_query($cn,$sql2);
$row2 = pg_fetch_array($res2);
if ($row2['login_firma_autorizada']=='' && $_SESSION['nivel_ca']==3){
	$autoriza=$row2['dependencia'].'-'.$row2['nombre_jefe_del_jefe'];
	$ccostoautoriza=$row2['dependencia'];
	$login_jefe_del_jefe=$row2['login_jefe_del_jefe'];
	$jefe_del_jefe=$row2['jefe_del_jefe'];
	$nombre_jefe_del_jefe=$row2['nombre_jefe_del_jefe'];
	$email_jefe_del_jefe=$row2['email_jefe_del_jefe'];
}
else{
	$autoriza=$row2['centro_costo'].'-'.$row2['nombre_jefe'];
	$ccostoautoriza=$row2['centro_costo'];
	$login_jefe_del_jefe=$row2['login_jefe'];
	$jefe_del_jefe=$row2['jefe_unidad'];
	$nombre_jefe_del_jefe=$row2['nombre_jefe'];
	$email_jefe_del_jefe=$row2['e_mail'];	
}

$query_operaciones = "select fkitemautorizado, descripcion_operacion, fkunidad, permiso from v_permisos_usuarios_unidades where permiso='S' and estatus_operacion='ACTIVO' and fkunidad in (select gerencia from v_ccostos_x_gerencias where ccosto=".$_SESSION['unidad_ca'].")";
print "<br>".$query_operaciones;
$res2 = pg_query($cn,$query_operaciones);
$opciones="";
while ($row2 = pg_fetch_array($res2)){
	$opciones.="<option value='".$row2['fkitemautorizado']."'>".$row2['descripcion_operacion']."</option>";
}

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingreso/Salida Equipo(s)</title>
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
     <!-- otros -->	
    <link href="css/bootstrap-select.min.css" rel="stylesheet"/>	
	<script src="js/bootstrap-select.min.js"></script>
<script  language="javascript">
//(*--------------------------------------------*)

function GuardarConsulta()
{
if (parseInt($("#hddmateriales").val())==0)
  {
    alert("No Hay Materiales y/o Suministros Registrados");       
    return;
  }

if ($("#cboretorna").val()=="SI" && $("#txtfecharetorno").val()=="")
	{
		alert("Ingrese la fecha del retorno sino coloque NO en el campo de <<Retorna>>");
		$("#txtfecharetorno").focus();		
		return;
	}	

if ($("#txtunidadsolicitante").val()=="")
	{
		alert("Usted no pertenece a ninguna unidad");		
		return;
	}
if ($("#txtunidadautoriza").val()=="")
	{
		alert("No tiene autorización");		
		return;
	}

if ($("#cboobjetivo").val()=="")
	{
		alert("Ingrese el objetivo");
		$("#cboobjetivo").focus();		
		return;
	}

if ($("#txtdestino").val()=="")
	{
		alert("Ingrese la Direccion del Destinatario");
		$("#txtdestino").focus();		
		return;
	}		
if ($("#txtnombre_destinatario").val()=="")
	{
		alert("Ingrese el Nombre del Destinatario");
		$("#txtnombre_destinatario").focus();		
		return;
	}

if ($("#cbomovimiento").val()=="ENTRADA" && $("#txtunidad_adscripcion").val()=="")
	{
		alert("Ingrese la Unidad de Adscricion");
		$("#txtunidad_adscripcion").focus();		
		return;
	}

if ($("#cbomovimiento").val()=="SALIDA" && $("#cboretorna").val()=="SI" &&  $("#txtnombre_contacto").val()=="")
	{
		alert("Ingrese el Nombre del Contacto");
		$("#txtnombre_contacto").focus();		
		return;
	}

if ($("#cbomovimiento").val()=="SALIDA" && $("#cboretorna").val()=="SI" &&  $("#txttlf_contacto").val()=="")
	{
		alert("Ingrese el Telefono del Contacto");
		$("#txttlf_contacto").focus();		
		return;
	}
document.getElementById("cmdGuardar").disabled = true;			
mostrar();
dir_url = "registrar_movimiento_db.php";
$.ajax({
   type: "POST",
   url: dir_url,
   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
   success: function(data)
   {
       //OJO.
	   //alert(data); // Mostrar la respuestas del script PHP.
	   if (data>0){
	   		alert("La " + $("#cbomovimiento").val() + " Se Registró Correctamente!. Su Solicitud se Envió por E-Mail a su Jefe con Firma Autorizada");
	   	    location.href = "index.php";
	   }
	   else
	   {
			alert("La operación Generó un Error:" + data);
			document.getElementById("cmdGuardar").disabled = false;
			ocultar();
	   }			   
	   	//recargar la página para limpiar controles
		//
		//location.reload(); //Recargar la página desde cero.			   
   }
 });

}
//(*--------------------------------------------*)
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
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
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
} 
//(*--------------------------------------------*)
function mostrar(){
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="images/loading.gif" alt="" height="60" width="60">';
$('#ver').show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(){
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
$('#ver').hide();
$('#loading').hide();
}
//(*--------------------------------------------*)
function VerificAgregarMat(objeto)
{
	if ($(objeto).val()>0){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
}
//(*--------------------------------------------*)

function AgregarFilaMat()
{
	
	if (($("#txtCantidad").val()!="") && ($("#txtdescmaterial").val()!="") && (parseInt($("#txtCantidad").val())>=1)){	
		$("#hddmateriales").val(parseInt($("#hddmateriales").val())+1);
		$("#tblMateriales").after("<tr><td width='9%'><input style='border:none' size='10' name='cantidad[]' readonly='readonly'  type='text' value='" + $("#txtCantidad").val() + "'/>" + "</td> <td width='7%'><input style='border:none' size='10' name='unidad[]'  readonly='readonly'  type='text' value='" + $("#cbounidad option:selected").text() +  "'/>" + "</td> <td  width='30%'><input size='10' style='border:none'  name='vale[]' readonly='readonly' type='text' value='" + $("#txtvale").val() + "'/>" + "</td> <td  width='50%'><input size='50' style='border:none'  name='material[]' readonly='readonly' type='text' value='" + $("#txtdescmaterial").val() + "'/>" + "</td><td width='4%'>" + "<INPUT type='button'  value='-'  class='form-control' onclick='$(this).parent().parent().remove(); eliminar_fila();'/></td></tr>");

	}
		//$("#tblMedicamentos").after("<tr><td>" + "A" + "</td><td>" + "B" + "</td><td>" + "C" + "</td></tr>")
}
//(*--------------------------------------------*)
function eliminar_fila(){
   $("#hddmateriales").val(parseInt($("#hddmateriales").val())-1);	
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

$(document).ready(function(){
	//CargarCombo($("#cbomilitar"),"cargar_combo_db.php?tabla=personal_guardia_nacional&campo1=idguardia&campo2=nombres&selected=0&orderby=nombres&firsttext=[Personal Militar Presente]");

	//CargarCombo($("#cboobjetivo"),"cargar_combo_db.php?tabla=v_permisos_usuarios_unidades&campo1=fkitemautorizado&campo2=descripcion_operacion&selected=0&where="+$("#hidunidadautoriza").val()+"&cond=fkunidad&orderby=fkitemautorizado&firsttext=[Selecciona Operacion]");

	//CargarCombo($("#cboobjetivo"),"cargar_combo_db.php?tabla=v_permisos_usuarios_unidades&campo1=fkitemautorizado&campo2=descripcion_operacion&selected=0&orderby=fkitemautorizado&firsttext=[Selecciona Operacion]");
	
	//ocultar la tabla de datos personales	
	/*$("#tbl_datos_personales").hide();
	
	var parametro = recibirQS('cedula');
    if(parametro != undefined){
        IrPaciente(parametro);
        //alert('*'+getParameterByName(parametro)+'*');
    }
	*/
});
//(*--------------------------------------------*)
function desbloquearretorno(resp){
	if(resp=="SI" || resp==true){		
		document.getElementById("txtfecharetorno").disabled=false;// habilitamos
	    $("#txtfecharetorno").focus();
	 }   
	else{	
		document.getElementById("txtfecharetorno").disabled=true;// deshabilitamos
		document.getElementById("txtfecharetorno").value="";
	}		
}

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
<article id="consulta" title='Registro de Movimiento'>

<!-- AQUI EL CONTENIDO 
-->
<form id="formulario" method='post'>
    
    <p align="left">
    
    </p>      
<table width="100%" >      
    <tr>
    	<td width="10%"><label>Fecha:</label></td>
    	<td width="20%"><input class="form-control" readonly name='txtFecha' id='txtFecha' type='date' value='<?php echo(date("Y-m-d")); ?>'/><span class="input-group-btn">
               
              </td>
    	<td width="5%"> &nbsp;</td>
    	<td width="10%"><label>Unidad Solicitante:</label></td>
    	<td width="20%"><INPUT type="text" id="txtunidadsolicitante" value="<?php echo $row1['descripcion_unidad']; ?>" name="txtunidadsolicitante" readonly  class="form-control input-sm"/></td>
    	<td width="5%"> &nbsp;</td>
    	<td width="10%"><label>Conforma:</label></td>
    	<td width="20%"><INPUT type="text" id="txtunidadautoriza" name="txtunidadautoriza" value="<?php echo $autoriza; ?>" readonly class="form-control input-sm"/>
    		<INPUT type="hidden" id="hidunidadautoriza" name="hidunidadautoriza" value="<?php echo $ccostoautoriza; ?>" />    		
    		<INPUT type="hidden" id="hidlogin_confirma" name="hidlogin_confirma" value="<?php echo $login_jefe_del_jefe; ?>" />
    		<INPUT type="hidden" id="hidcedula_confimar" name="hidcedula_confimar" value="<?php echo $jefe_del_jefe; ?>" />
    		<INPUT type="hidden" id="hidnombre_confimar" name="hidnombre_confimar" value="<?php echo $nombre_jefe_del_jefe; ?>" />
    		<INPUT type="hidden" id="hidemail_confimar" name="hidemail_confimar" value="<?php echo $email_jefe_del_jefe; ?>" />
    	</td>
    	
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    <tr>    	
    	<td><label>Tipo Movimiento:</label></td>
    	<td><select width="100%" class="selectpicker" data-live-search="false" name="cbomovimiento" id="cbomovimiento" > 
    		    <option value="ENTRADA">ENTRADA</option>
    		    <option value="SALIDA">SALIDA</option>	
    		</select>
    	</td>
    	<td> &nbsp;</td>
    	<td><label>Retorna:</label></td>
    	<td><select  class="selectpicker" data-live-search="false" name="cboretorna" id="cboretorna" onchange="desbloquearretorno(this.value)" >    		    
    		    <option value="NO">NO</option>
    		    <option value="SI">SI</option>	
    		</select></td>
    	<td> &nbsp;</td>
    	<td><LABEL>Fecha Retorno:</LABEL></td>
		<td><INPUT type='date' id="txtfecharetorno" name='txtfecharetorno' maxlength="10" wrap="soft" class="form-control" disabled="disabled"/></td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    <tr>
		<td><label>Conductor:</label></td>
		<td><INPUT type="text" id="txtconductor" name="txtconductor" maxlength="50" class="form-control"/></td>
		<td> &nbsp;</td>
		<td><label>CI. Conductor:</label></td>
		<td><INPUT type="text" id="txtciconductor" name="txtciconductor"  maxlength="10" class="form-control"/></td>
		<td>&nbsp;</td>
		<td><label>Placa Veh.:</label></td>
		<td><INPUT type="text" id="txtplaca" name="txtplaca"  maxlength="10" class="form-control"/></td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td><LABEL>Marca Veh.:</LABEL></td>
		<td><INPUT type="text" id="txtmarca" name="txtmarca"  maxlength="20" class="form-control"/></td>
		<td>&nbsp;</td>
		<td><LABEL>Modelo Veh.:</LABEL></td>
		<td><INPUT type="text" id="txtmodelo" name="txtmodelo"  maxlength="20" class="form-control"/></td>
		<td>&nbsp;</td>
		<td><label>Color Veh.:</label></td>
		<td><INPUT type="text" id="txtcolor" name="txtcolor" maxlength="20" class="form-control"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<!-- td><LABEL>Guardia:</LABEL> </td>
		<td><select style="font-size:8pt" class="form-control" name="cbomilitar" id="cbomilitar" ></select> </td> -->
		<td><LABEL>Nombre Contacto:</LABEL></td>
		<td><input class="form-control" size="50" maxlength="50" name='txtnombre_contacto' id='txtnombre_contacto' type='text' value=''/></td>
		<td>&nbsp;</td>
		<td><LABEL>C.I. Contacto:</LABEL></td>
		<td><input class="form-control" size="50" maxlength="10" name='txtcedula_contacto' id='txtcedula_contacto' type='text' value=''/></td>
		<td>&nbsp;</td>
		<td><LABEL>Tlf. Contacto:</LABEL></td>
		<td><input class="form-control" size="30" maxlength="50" name='txttlf_contacto' id='txttlf_contacto' type='text' value=''/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>		
		<td><LABEL>Unidad Adscripcion</LABEL></td>
		<td><input class="form-control" size="50" maxlength="50" name='txtunidad_adscripcion' id='txtunidad_adscripcion' type='text' value=''/></td>
		<td>&nbsp;</td>
		<td><label>Order de Compra:</label></td>
    	<td><input class="form-control" name='txtorden' id='txtorden' maxlength="20" type='text' value=''/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>	
	</tr>
	
</table>
<p>&nbsp;</p>
<table width="100%" >
<tr>
	<td><LABEL>Motivo:</LABEL></td>
	<td><select id="cboobjetivo" name="cboobjetivo" class="form-control input-sm" data-live-search="false">
					<?php
					  echo $opciones;
					?>
			</select>
	</td>
</tr>
	<td><label>Nombre Destinatario:</label></td>
	<td><input width="90%" class="form-control" size="150" maxlength="150" name='txtnombre_destinatario' id='txtnombre_destinatario' type='text' value=''/></td>
<tr>
	<td><label>Direccion Destino:</label></td>
	<td><input width="90%" class="form-control" size="150" maxlength="150" name='txtdestino' id='txtdestino' type='text' value=''/></td>
</tr>
</table>
   <p>&nbsp;</p>
   <p><label>Descipcion de los Materiales, Herramientas, Equipos, Partes y Piezas</label></p>
<table class="table-bordered" id="tblMateriales">
    <tr>
	    <td width="9%"><label>Cantidad</label></td>
	    <td width="7%"><LABEL>Unidad</LABEL></td>
	    <td width="30%"><LABEL>N° Vale Almacen/Serial</LABEL></td>
	    <td width="50%"><LABEL>Descripcion del Material o equipo</LABEL></td>
	    <td width="4%">&nbsp;</td>
	</tr>
    <tr>    	
    	<td width="9%"><INPUT onkeypress="return validar(event)" id="txtCantidad" type="text"  size="10" maxlength="10" class="form-control" /></td>
    	<td width="7%"><select style="font-size:8pt" width="100%" class="selectpicker" data-live-search="false" name="cbounidad" id="cbounidad" > 
    		    <option value="PZA">PZA</option>
    		    <option value="MTS">MTS</option>	
    		    <option value="LTS">LTS</option>
    		    <option value="CUB">CUB</option>	
    		    <option value="MT2">MT2</option>
    		    <option value="TON">TON</option>	
    		    <option value="UND">UND</option>
    		</select></td>
    	<td width="30%"><INPUT id="txtvale" size="20" maxlength="20" type="text" value="N/A"  class="form-control"/></td>
    	<td width="50%"><INPUT id="txtdescmaterial" type="text"  size="250" maxlength="250" class="form-control" /></td>    	
    	<td width="4%"><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="AgregarFilaMat();" /></td>
    </tr>
</table>
<INPUT id="hddmateriales" name="hddmateriales" type="hidden" value="0" />
<p>&nbsp;</p>
<table>	
	  <tr>
		<td><LABEL>Observacion:</LABEL></td>		
	  </tr>
	  <tr>
	    <td><textarea class="form-control" id="txtobservacion"  maxlength="500"  name="txtobservacion" rows="3" cols="150" wrap="soft" ></textarea></td>
	  </tr>	  
</table>

<p>&nbsp;</p>

<table class="" width="100%" id="tblGuardar" align="center">	
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar Movimiento"  class="btn btn-success" ondblclick="GuardarConsulta();"/></td>
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