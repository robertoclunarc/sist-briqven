<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid'])){
  if ($_SESSION['nivel']!=3){	
   require('menu.php');
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Envio de Comprobante</title>
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

function Enviar()
{
	
if ($("#asunto").val()=="")
	{
		alert("Seleccione los campos requeridos para la generacion de la informacion");
		$("#txtTrabajador").focus();
		//exit(0);
		return;
	}
$.when(
		$.ajax({
            type: "POST",
            //dataType: "json",
            url: "generar_query_recibo.php",
           // async: true,
            data: $("#formulario").serialize(),
            success: function(data) {
            	var arry = data.split("#");
            	var total=arry[1];
				var progreso = 0;
				var porcentaje = 0;				
				var idIterval = setInterval(function(){
				progreso +=1;
				porcentaje = Math.floor((progreso*100/total));
				$('#bar').css('width', porcentaje + '%');
				$("#bar").text(arry[progreso+1]+" => "+porcentaje + "% ("+progreso+" de "+total+")");             
				$("#bar").attr("aria-valuenow", porcentaje);
				//Si llegó a 100 elimino el interval
				if(porcentaje >= 100){
					clearInterval(idIterval);
					mostrar();
				}
				},3000);
                ////////////////// 
            }
        }),
		$.ajax({
		   type: "POST",
		   url: "construir_recibo.php",
		   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
		       //OJO.
			   //alert(data); // Mostrar la respuestas del script PHP.
			   if (data>0)
			   {
			        if (data==1)
			              alert("Se generó un ("+data+") registro y se envió!");
			        else
				   		  alert("Se generaron "+data+ " archivos para su envio");
			   }	   		
			   else
			   {
			   if (data==0)
		          	alert("No se generó ningun archivo");
		       else
		          	alert("La operación Generó un Error:" + data);
			   }
			   activar_boton(false);
			   ocultar(); 
			   	//recargar la página para limpiar controles
				//
				//location.reload(); //Recargar la página desde cero.			   
		   }
		 })
		).done(function() {
       // escribir html o lo que necesites...
       	//alert("success: ");
       ///////////////////////////////////////////
       })
}

//(*--------------------------------------------*)
function VerificarPeriodo(operacion)
{
dir_url = "VerificarPeriodo.php";
$.ajax({
   type: "POST",
   url: dir_url,
   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
   success: function(data)
   {       
       if (data!="0"){
       		activar_boton(true);
          if (operacion=='enviar') 
              Enviar();
          else
             generar_pdf();
       } else {
       		alert("Este Periodo Aun NO Esta Disponible");
       }           
   }
 });
}
//(*--------------------------------------------*)

function generar_pdf()
{
$.when(
        $.ajax({
            type: "POST",
            //dataType: "json",
            url: "generar_query_recibo.php",
           // async: true,
            data: $("#formulario").serialize(),
            success: function(data) {
            	var arry = data.split("#");
            	var total=arry[1];
				var progreso = 0;
				var porcentaje = 0;				
				var idIterval = setInterval(function(){
				progreso +=1;
				porcentaje = Math.floor((progreso*100/total));
				$('#bar').css('width', porcentaje + '%');
				$("#bar").text(arry[progreso+1]+" => "+porcentaje + "% ("+progreso+" de "+total+")");             
				$("#bar").attr("aria-valuenow", porcentaje);
				//Si llegó a 100 elimino el interval
				if(porcentaje >= 100){
					clearInterval(idIterval);
					mostrar();
				}
				},500);
                ////////////////// 
            }
        }),
        $.ajax({
            type: "POST",
            //dataType: "json",
            url: "generarpdf.php",
           // async: true,
            data: $("#formulario").serialize(),            
            success: function(data) {
                var res = data.split(",");     
			     if (res[0]>0)
			     {
			        if (res[0]==1)
			            {
			              alert("Se generó un registro!");            
			            }
			        else{
			              alert("Se generaron "+res[0]+" registros! ");
			              //window.location = res[1];         
			            }
			        ocultar();    
			        window.open(res[1] , "Recibos" , "width=300,height=300,scrollbars=YES");
			      }  
			     else
			     {
			        alert("No se generó ningun registro. Error "+data);
			     }
            }
        })
    ).done(function() {
       // escribir html o lo que necesites...
       	//alert("success: ");
       ///////////////////////////////////////////
       activar_boton(false);
       })
}

function mostrar(){
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="images/loading.gif" alt="" height="60" width="60">';
$('#ver').show(); 
$('#loading').show();    
}

function ocultar(){
document.getElementById('ver').innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
$('#ver').hide();
$('#loading').hide();
}


//(*--------------------------------------------*)
function puntoycoma()
{
    var dest=document.getElementById("txtdestino").value;
    dest = dest.trim();
    if (dest!='')   
      if (dest.substr(-1)!=';')
        document.getElementById("txtdestino").value=dest + ";";
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
agregar_asunto();
}


function agregar_asunto()
{
var titulo="Comprobante de Pago ";
titulo = titulo + $(cboTnomina).val() + $(cboCnomina).val();
titulo = titulo + $(cboMes).val() + $(cboAnho).val() ;
if (document.getElementById("chkCcosto").checked)
	titulo = titulo + "-" + $("#cboCcosto option:selected").text();
else if (document.getElementById("chkRlaboral").checked)
	titulo = titulo + "-" + $("#cboRlaboral option:selected").text();
else if(document.getElementById("chkTrabajador").checked)
		{
			var cadena = document.getElementById("txtTrabajador").value;
			var indeof=cadena.lastIndexOf(',');
			if(indeof != -1)				
				titulo = titulo + " del Personal Solicitado";
			else
				titulo = titulo + "-" +$("#cboTrabajador option:selected").text();
		}

document.getElementById("asunto").value = titulo;

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
//(*--------------------------------------------*)

function activar_boton(activar){
    document.formulario.cmdEnviar.disabled=activar;
    document.formulario.cmdGuardar.disabled=activar;
}
//(*--------------------------------------------*)
$(document).ready(function(){
	//alert("aqui");
	
	CargarCombo($("#cboTnomina"),"cargar_combo_db.php?tabla=calendario_procesos&campo1=TIPO_NOMINA&selected=LA&orderby=TIPO_NOMINA");

	CargarCombo($("#cboCnomina"),"cargar_combo_db.php?tabla=CLASES_NOMINA&campo1=CLASE_NOMINA&campo2=DESCRIPCION&selected=ME&orderby=CLASE_NOMINA");
	
	CargarCombo($("#cboCcosto"),"cargar_combo_db.php?tabla=datos_agr_trab&campo1=DATO&campo2=DESCRIPCION&where=trim(agrupacion)&valor_cond='TRACOST' and trim(descripcion) <> 'S/N'&orderby=DESCRIPCION&firsttext=[Elija Centro Costo]");

	CargarCombo($("#cboTrabajador"),"cargar_combo_db.php?tabla=trabajadores&campo1=TRABAJADOR&campo2=NOMBRE&selected=0&orderby=NOMBRE&firsttext=[Seleccione Trabajador]");	
	
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
<form id="formulario" name="formulario" autocomplete="on" method='post'>
    
    <p align="right">
    Fecha:<input size="10" readonly="" name='txtFecha' id='txtFecha' type='text' value='<?php echo(date("Y-m-d")); ?>'/></p>
    <article>    
    <div class="input-group">
      <input id="asunto" name="asunto" placeholder="Asunto" type="text" class="form-control" style="z-index: 0;" onblur="$(this).val($(this).val().toUpperCase());" >

      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php //echo $_SESSION['user_session']; ?>'/>
      <span class="input-group-btn">
        <button id="cmdEnviar" name="cmdEnviar" class="btn btn-default" type="button" onclick="VerificarPeriodo('enviar');">Enviar</button>

      </span>
    </div><!-- /input-group -->    
    <p></p>
    
 </article>
    
<table width="60%">      
    <tr>
    	<td width="10%"><label>Tipo Nomina:</label></td>
    	<td width="20%"><select onchange="agregar_asunto()" name="cboTnomina" id="cboTnomina" class="form-control"></select></td>
    	<td width="40%"> &nbsp;</td>
    	<td width="10%"><label>Clase Nomina:</label></td>
    	<td width="20%"><select onchange="agregar_asunto()" name="cboCnomina" id="cboCnomina" class="form-control"></select></td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
    <tr>
    	<td><label>Mes:</label></td>
    	<?php  
                 	$mes = date("m");                  
         ?>
    	<td><select onchange="agregar_asunto()" name="cboMes" id="cboMes" class="form-control">
    		 <option <?php if ($mes==1) echo 'selected' ?> value="01">Enero</option>
    		 <option <?php if ($mes==2) echo 'selected' ?> value="02">Febrero</option>
    		 <option <?php if ($mes==3) echo 'selected' ?> value="03">Marzo</option>    		
    		 <option <?php if ($mes==4) echo 'selected' ?> value="04">Abril</option>
    		 <option <?php if ($mes==5) echo 'selected' ?> value="05">Mayo</option>
    		 <option <?php if ($mes==6) echo 'selected' ?> value="06">Junio</option>
    		 <option <?php if ($mes==7) echo 'selected' ?> value="07">Julio</option>
    		 <option <?php if ($mes==8) echo 'selected' ?> value="08">Agosto</option>
    		 <option <?php if ($mes==9) echo 'selected' ?> value="09">Septiembre</option>
    		 <option <?php if ($mes==10) echo 'selected' ?> value="10">Octubre</option>
    		 <option <?php if ($mes==11) echo 'selected' ?> value="11">Noviembre</option>
    		 <option <?php if ($mes==12) echo 'selected' ?> value="12">Diciembre</option>
    	    </select>
    	</td>
    	<td> &nbsp;</td>
    	<td><label>Año:</label></td>
    	<td><select onchange="agregar_asunto()" name="cboAnho" id="cboAnho" class="form-control">    			
                 <?php  
                 	$year = date("Y");
                 for($i=$year; $i>=2004; $i--) 
                    echo "<option value='".$i."'>".$i."</option>"; 
                  ?>
    		</select>
    	</td>
    </tr>
    <tr>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    	<td> &nbsp;</td>
    </tr>
   
</table>

<table width="100%">
	<tr>
		<td width="10%"> &nbsp;</td>
		<td width="10%"> <label>A Quien Enviar?</label></td> 
		<td width="40%"><input type="radio" name="entrega" checked value="1"><label>Destinatario</label><br>
						<input type="radio" name="entrega" value="2"><label>Otro(s):</label>				
		</td>
		<td width="40%"><input type="radio" name="clave" checked value="1"><label>Encriptado</label><br>
						<input type="radio" name="clave" value="2"><label>Desencriptado</label></td>	
	</tr>
	<tr>
		<td width="10%"> &nbsp;</td>
		<td width="10%"> &nbsp;</td> 
		<td width="40%"><INPUT type="text" autocomplete="on" id="txtdestino" placeholder="correo_1@dominio.com; correo_2@dominio.com;" name="txtdestino" Onblur="puntoycoma()" width="100%" class="form-control"/></td>				
		<td width="40%"> &nbsp;</td>	
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
<!--	<tr>		
		<td width="10%"><input type="checkbox" name="Adjutar" checked id="Adjutar"> </td>
		<td width="10%"><label>Adjutar Archivo</label></td>
		<td width="40%"> &nbsp;</td>
		<td width="40%"> &nbsp;</td>
	</tr>
 
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
-->  
	<tr>
		<td width="10%"><input type="checkbox" name="chkCcosto" id="chkCcosto"> </td>
		<td width="10%"><label>Centro Costo:</label></td>
		<td width="40%"><select onchange="agregar_asunto()" name="cboCcosto" id="cboCcosto" class="form-control" ></select></td>
		<td width="40%"> &nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>	
	<tr>
		<td width="10%"><input type="checkbox" name="chkTrabajador" id="chkTrabajador"> </td>
		<td width="10%"><label>Trabajador(es):</label></td>
		<td width="40%"><select onchange="agregar_trabajador($(this).val())" name="cboTrabajador" id="cboTrabajador" class="form-control" ></select></td>
		<td width="40%"></td>
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
	<TR>
		<td width="10%"><input type="checkbox" name="chkRlaboral" id="chkRlaboral"> </td>
		<td width="10%"><label>Relacion Laborar:</label></td>
		<td width="40%"><select onchange="agregar_asunto()" name="cboRlaboral" id="cboRlaboral" class="form-control" >
								<option value="B">Convenio</option>
								<option value="W">Conduccion</option>
								<option value="E">Contratado</option>
						</select>
		</td>
		<td width="40%"> &nbsp;</td>
	</TR>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>
	<TR>
		<td width="10%"><input type="checkbox" name="chkexcepcion" id="chkexcepcion"> </td>
		<td width="10%"><label>Excepcion(es):</label></td>
		<td width="40%"><INPUT type="text" id="txtexcepcion" name="txtexcepcion"  width="100%" class="form-control"/></td>
		<td width="40%"> &nbsp;</td>
	</TR>
	<tr>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td> 
		<td width="40%">&nbsp;</td>				
		<td width="40%">&nbsp;</td>	
	</tr>		
	<tr>
		<td width="10%"> &nbsp;</td>
		<td width="10%"> <label>Tienen Correo?</label></td> 
		<td width="40%"><input type="radio" name="correo" value="1"><label>Si</label><br>
						<input type="radio" name="correo" value="2"><label>No</label><br>
						<input type="radio" name="correo" value="3" checked><label>Todos</label>
		</td>
		<td width="40%"> &nbsp;</td>		
	</tr>	
	<tr>
		<td width="10%"> &nbsp;</td>
		<td width="10%"> &nbsp;</td> 
		<td width="40%"> &nbsp;</td>
		<td width="40%"> &nbsp;</td>		
	</tr>
		
</table>

<p>&nbsp;</p>

<table class="" width="100%" id="tblGuardar" align="center">	
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Generar PDF"  class="form-control" onclick="VerificarPeriodo('generarpdf');"/></td>
		<td width="30%">&nbsp;</td>
	</tr>
</table>
<p>&nbsp;</p>

<div class="progress progress-striped active">
  <div id="bar" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
       aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
       style="width: 0%">
    <span class="">0% completado</span>
  </div>
</div>
 <div id='ver'></div>

</form>
</article>
</section>
</body>
</html>
 <?php //piedepagina(); 
} else 
	header('Location: envio_comp_nv2.php');
}	
else{
    //header('Location: /login/index.php');
echo "<html>
<body>
<script type='text/javascript'>
window.location='login/index.php';
</script>
</body>
</html>
";
}
?>