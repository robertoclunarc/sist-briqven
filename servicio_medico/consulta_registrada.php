<?php 
session_start();
if ((isset($_SESSION['username']) && isset($_SESSION['userid'])) || (isset($_GET['msj']) && ($_GET['msj']==1))){
    require('menu.php');
	require('piedepagina.php');
	require('include_conex.php');
	if (isset($_GET['msj'])) 
		$_SESSION['nivel'] = 0;
	$idconsulta = isset($_GET["idconsulta"])?$_GET["idconsulta"]:"-1";
	if (($_SESSION['nivel'] == 1) || ($_SESSION['nivel'] == 2) || ($_SESSION['nivel'] == 0)){ 		
		$idconsulta = isset($_GET["idconsulta"])?$_GET["idconsulta"]:"-1"; 
		$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
  		$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
  		
        $Qryconsulta=pg_query($cn,"SELECT * FROM v_consulta WHERE uid=".$idconsulta);

  		$Regconsulta = pg_fetch_array($Qryconsulta, null, PGSQL_ASSOC);

        $fecha=substr($Regconsulta['fecha'],0,16);
        $ci=$Regconsulta['ci'];
        
        $Qrysignos=pg_query($cn,"SELECT cedula, fresp, pulso, temper, tart, fecha, fcard FROM tbl_signos_vitales WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
        $Regsignos = pg_fetch_array($Qrysignos, null, PGSQL_ASSOC);

        $Qrydatos_ant=pg_query($cn,"SELECT cedula, talla, peso, imc, fecha FROM tbl_datos_antropometricos WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
        $Regdatos_ant = pg_fetch_array($Qrydatos_ant, null, PGSQL_ASSOC);

  		$Regmedicamentos=pg_query($cn,"SELECT * FROM v_medicamentos_consulta WHERE id_consulta=".$idconsulta);
  		$rowsmedicamentos=pg_num_rows($Regmedicamentos);
  		
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Consulta Registrada</title>
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

function verplanilla(idc, pagina){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open(pagina+'?uid='+idc, "Detalles Consulta", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");    
 }
//(*-----------------------------------------------------------------------------------*)
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
//(*--------------------------------------------------------------------------------------*)
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

	var parametro = recibirQS('cedula');
    if(parametro != undefined){
        IrPaciente(parametro);
        //alert('*'+getParameterByName(parametro)+'*');
    }
	
});
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
    Fecha:<input size="16" readonly="" name='txtFecha' id='txtFecha' type='text' value="<?php echo $fecha; ?>"/>&nbsp;Turno:<input readonly="" size="10" name='txtTurno' id='txtTurno' type='text' value="<?php echo $Regconsulta['turno']; ?>"/></p>
    <article>    
    <div class="input-group">
      <input id="ci" type="text" readonly="" class="form-control" style="z-index: 0;" value="<?php echo $ci; ?>" >
      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
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
    
<table >      
    <tr>
    	<td><label>Médico de Guardia:</label></td>
    	<td><select disabled="disabled" name="cboMedico" id="cboMedico" >
    			<option value="<?php echo $Regconsulta['ci_medico']; ?>"><?php echo $Regconsulta['medico']; ?></option>
    		</select>
    	</td>
    	<td><label>Paramédico de Guardia: </label></td>
    	<td><select disabled="disabled" name="cboParaMedico" id="cboParaMedico" >
    			<option value="<?php echo $Regconsulta['ci_paramedico']; ?>"><?php echo $Regconsulta['paramedico']; ?></option>
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
        <td><label>Tipo Diagnostico:</label></td>
        <td><select disabled="disabled" name="cboDiagnostico" id="cboDiagnostico" >
                <option value="<?php echo $Regconsulta['descripciondiagnostico']; ?>"><?php echo $Regconsulta['descripciondiagnostico']; ?></option>
        </select>
        </td>
        <td><label>Motivo: </label></td>
        <td><select disabled="disabled" name="cboMotivos" id="cboMotivos" >
                <option value="<?php echo $Regconsulta['motivo']; ?>"><?php echo $Regconsulta['motivo']; ?></option>
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
    	<td><label>Área Incidente: </label></td>
    	<td><select disabled="disabled" name="cboArea" id="cboArea" >
    			<option value="<?php echo $Regconsulta['area']; ?>"><?php echo $Regconsulta['area']; ?></option>
    		</select>
    	</td>
        <td><label>Patología:</label></td>
        <td><select disabled="disabled" name="cboPatologias" id="cboPatologias" >
                <option value="patologia"><?php echo $Regconsulta['patologia']; ?></option>
            </select>
        </td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    <!--<tr>
    	<td><label>Patología:</label></td>
    	<td><select disabled="disabled" name="cboPatologias" id="cboPatologias" >
    			<option value="patologia"><?php //echo $Regconsulta['patologia']; ?></option>
    		</select>
    	</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>-->
</table>
<p>&nbsp;</p>
<table width="100%">
	<tr>
		<td width="100%"><label>Motivo de la Consulta:</label></td>
	</tr>
	<tr>
		<td width="100%"><INPUT readonly type="text" id="txtSintomas" name="txtSintomas" value="<?php echo $Regconsulta['sintomas']; ?>"  width="100%" class="form-control"/></td>
	</tr>
	<TR>
		<td width="100%"><label>Enfermedad Actual</label></td>
	</TR>
	<tr>
	<td width="100%"><INPUT type="text" readonly value="<?php echo $Regconsulta['observaciones']; ?>" id="txtObservaciones" name="txtObservaciones" width="100%" class="form-control"/></td>
	</tr>
	<TR>
		<td width="100%">&nbsp;</td>
	</TR>
	<TR>
		<td width="100%"><LABEL>Resultados de la Evaluacion Medica:</LABEL></td>
	</TR>
	<tr>
		<td><label>Remitido a:</label>
			<select disabled="disabled" name="cboRemitido" id="cboRemitido" >
				<option value="<?php echo $Regconsulta['remitido']; ?>"><?php echo $Regconsulta['remitido']; ?></option>
			</select>&nbsp;&nbsp;&nbsp;
			<label>Reposo:</label>
			<select disabled="disabled" name="cboReposo" id="cboReposo" >
				<option value="<?php echo $Regconsulta['reposo']; ?>"><?php echo $Regconsulta['reposo']; ?></option>	
			</select>&nbsp;&nbsp;&nbsp;			
			<label>Condici&oacute;n:</label>
				<select disabled="disabled" name="cbocondicion" id="cbocondicion" >
					<option value="<?php echo $Regconsulta['condicion']; ?>"><?php echo $Regconsulta['condicion']; ?></option>											
			</select>
			
		</td>
	</tr>
    <tr>
        <td width="100%">&nbsp;</td>
    </tr>
    <tr>
        <td width="100%">&nbsp;
            <table>
                <tr>
                    <td width="10%"><LABEL  class="etiqueta">F. Resp.:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txtfresp" value="<?php echo $Regsignos['fresp']; ?>" readonly name="txtfresp" size="20%" class="form-control" /></td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL  class="etiqueta">Pulso:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txtpulso" readonly name="txtpulso" value="<?php echo $Regsignos['pulso']; ?>"  width="20%" class="form-control"/></td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL  class="etiqueta">Temp.:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txttemper" readonly name="txttemper" value="<?php echo $Regsignos['temper']; ?>"  width="20%" class="form-control"/></td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL  class="etiqueta">T. Art.:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txttart" readonly name="txttart" value="<?php echo $Regsignos['tart']; ?>" width="20%" class="form-control"/></td>
                </tr>    
              </table>
              <br>
              <table>
                <tr>
                    <td width="10%"><LABEL class="etiqueta">Talla:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txttalla" value="<?php echo $Regdatos_ant['talla']; ?>" readonly onkeyup="calc_imc(this);" onblur="calc_imc(this);" name="txttalla" size="20%" class="form-control" /></td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL class="etiqueta">Peso:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txtpeso" value="<?php echo $Regdatos_ant['peso']; ?>" readonly name="txtpeso"  onkeyup="calc_imc(this);" onblur="calc_imc(this);" width="20%" class="form-control"/> </td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL class="etiqueta">IMC:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txtimc" value="<?php echo $Regdatos_ant['imc']; ?>" readonly name="txtimc" disabled="disabled" width="20%" class="form-control"/></td>
                    <td width="5%">&nbsp;</td>
                    <td width="10%"><LABEL class="etiqueta">F. Card:</LABEL></td>
                    <td width="10%"><INPUT type="text" id="txtfcard" value="<?php echo $Regsignos['fcard']; ?>" readonly name="txtfcard" width="20%" class="form-control"/></td>
                    
                </tr>    
              </table>
        </td>
    </tr>
    <tr>
        <td width="100%">&nbsp;</td>
    </tr>






	<tr>
		<td width="100%"><LABEL>Diagnostico</LABEL></td>
	</tr>	
	<tr>
		<td width="100%"><INPUT type="text" readonly id="txtDiagnostico" name="txtDiagnostico" value="<?php echo $Regconsulta['resultado_eva']; ?>" width="100%" class="form-control"/></td>
	</tr>

	<tr>
		<td width="100%">&nbsp;</td>
	</tr>
	
	<tr>
		<td width="100%"><label>Observaci&oacute;n:</label></td>
	</tr>
	<tr>
		<td width="100%"><INPUT readonly type="text" id="txtObservacionMed" name="txtObservacionMed" value="<?php echo $Regconsulta['observacion_medicamentos']; ?>" width="100%" class="form-control"/></td></tr>
	<tr>
	<tr>
		<td width="100%"><label>Referencia:</label></td>
	</tr>	
	<tr>
		<td width="100%"><textarea class="form-control" readonly id="txtreferencia" name="txtreferencia" rows="4" cols="50" wrap="soft"><?php echo $Regconsulta['referencia_medica']; ?></textarea></td></tr>
	<tr>
</table>
<br>
<table width="100%">
    <tr>
	    <td width="60%"><label>Medicamentos Aplicados</label></td>
	    <td><LABEL>Medida</LABEL></td>
	    <td><LABEL>Cantidad</LABEL></td>	    
	</tr>
<?php while($reg = pg_fetch_array($Regmedicamentos, null, PGSQL_ASSOC)) { ?>	
    <tr>
    	<td><?php echo$reg['descripcion']; ?></td>
    	<td><?php echo$reg['unidad_medida']; ?></td>
    	<td><?php echo$reg['cantidad']; ?></td>    	
    </tr>
<?php } ?>    
</table>   
</table>

<table width="100%">
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
    <td width="100%" colspan="3"><textarea  readonly class="form-control" id="txtIndicaciones" name="txtIndicaciones" rows="4" cols="50" wrap="soft"><?php echo $Regconsulta['indicaciones_comp']; ?></textarea></td>
  </tr>	
</table>

<p>&nbsp;
</p>
<table>
<tr>
	<td width="50%"><LABEL>Fecha de la Pr&oacute;xima Cita</LABEL></td>
	<td width="50%"><INPUT readonly type="text" placeholder="AAAA-MM-DD" id="txtFechaProxCita" name="txtFechaProxCita" size="20%" class="form-control" value="<?php echo $Regconsulta['fecha_prox_cita']; ?>" /></td>		
</tr>
</table>

<p>&nbsp;
</p>

<table class="" width="100%" id="tblGuardar" align="center">	
	<tr>
		<td width="30%">
			<?php if ($Regconsulta['referencia_medica']!="") { ?>
					<INPUT type="button"  value="Imprimir Referencia"  class="form-control" onclick="verplanilla(<?php echo $idconsulta; ?>, 'planilla_referencia.php');"/>
			<?php } else echo "&nbsp;" ?>
		</td>
		<td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Imprimir Consulta"  class="form-control" onclick="verplanilla(<?php echo $idconsulta; ?>, 'planilla_consulta.php');"/></td>
		<td width="30%">
			<?php if ($rowsmedicamentos>0) { ?>
					<INPUT type="button"  value="Imprimir Recipe"  class="form-control" onclick="verplanilla(<?php echo $idconsulta; ?>, 'planilla_recipe.php');"/>
					<?php } else echo "&nbsp;" ?>
		</td>
	</tr>
</table>

</form>
</article>
</section>
</body>
</html>
<?php piedepagina();
pg_free_result($Qryconsulta);
pg_free_result($Qrysignos);
pg_free_result($Qrydatos_ant);
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