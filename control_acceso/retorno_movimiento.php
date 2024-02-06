<?php 
session_start();
if (isset($_SESSION['username_ca']) && isset($_SESSION['userid_ca']) ){
   $idmov=isset($_GET["idm"])?$_GET["idm"]:"NULL";
   require('libs/menu.php');
   require('piedepagina.php'); 
   require_once('libs/conexion.php');
   require_once('funciones_var.php');
$cn=  Conectarse();     
$sql1 = "SELECT * FROM v_movimientos_1 WHERE estatus_part='VALIDADO' AND ciclo IN ('SALIDA PEND RETORNO', 'ENTRADA PEND RETORNO') AND idmovimiento=".$idmov;
$res1 = pg_query($cn,$sql1);
$row1 = pg_fetch_array($res1);
$sql2 = "SELECT * FROM usuarios_movimientos WHERE operacion='SOLICITADO' AND estatus='SOLICITADO' AND fkmovimiento_part=".$idmov;
$res2 = pg_query($cn,$sql2);
$row2 = pg_fetch_array($res2);
$sql3 = "SELECT * FROM usuarios_movimientos WHERE operacion='CONFORMADO' AND estatus='CONFORMADO' AND fkmovimiento_part=".$idmov;
$res3 = pg_query($cn,$sql3);
$row3 = pg_fetch_array($res3);
$sql4 = "select a.* from detalles_movimientos_retornos a where a.fecha_retorno= (select max(b.fecha_retorno) from detalles_movimientos_retornos b where a.fkmovimiento=b.fkmovimiento) and cantidad_restante::numeric>0 and fkmovimiento=".$idmov;
$res4 = pg_query($cn,$sql4);
if (pg_num_rows($res4)==0){
    $sql5 = "select a.* from detalles_movimientos a where cantidad::numeric>0 and a.fkmovimiento=".$idmov;
    $res4 = pg_query($cn,$sql5);
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <title>Retorno de Equipo(s)</title>
<script  language="javascript">
//(*--------------------------------------------*)

function GuardarConsulta()
{
if (parseInt($("#hddmateriales").val())==0)
  {
    alert("No Hay Materiales y/o Suministros Registrados");       
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

if ($("#cbomovimiento").val()=="SALIDA" &&  $("#txtnombre_contacto").val()=="")
  {
    alert("Ingrese el Nombre del Contacto");
    $("#txtnombre_contacto").focus();   
    return;
  }

if ($("#cbomovimiento").val()=="SALIDA" &&  $("#txttlf_contacto").val()=="")
  {
    alert("Ingrese el Telefono del Contacto");
    $("#txttlf_contacto").focus();    
    return;
  }

document.getElementById("cmdGuardar").disabled = true;      
mostrar();
dir_url = "retorno_movimiento_db.php";
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
          alert("La " + $("#cbomovimiento").val() + " Se Registró Correctamente!.");
          location.href = "index1.php";
        }
     else
     {
      alert("La operación Generó un Error:" + data);
      ocultar();
      document.getElementById("cmdGuardar").disabled = false;
     }         
     
    //location.reload(); //Recargar la página desde cero.        
   }
 });

}
//(*--------------------------------------------*)
function VerificAgregarMat(objeto)
{
  if ($(objeto).val()>0){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
}
//(*--------------------------------------------*)
function AgregarFilaMat()
{

  if (valida_material($("#txtdescmaterial").val())){
        alert ("Material Ya Incluido");
        return;
    }
  
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
//(*--------------------------------------------*)
function valida_material(mater)
{
  var j=parseInt($("#hddmateriales").val());
  var entro = false;
  if (j>0){
    $('input[type=text]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='material[]') && (cb.attr('value')==mater))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)  
function valida_cantidad()
{
  var cant = parseInt($("#txtCantidad").val());	
  var can_mat = parseInt($("#hddCanMat").val());
    for (i=0; i<can_mat; i++){
  	    if ($("#hdddescrip_"+i).val()==$("#txtdescmaterial").val())  	      		
  		     if (cant > parseInt($("#hddcantret_"+i).val())){
  				alert("Esta Cantidad No Concuerda Con La "+$("#cbomovimiento").val());
  				$("#txtCantidad").val($("#hddcantret_"+i).val());
  		     }
    }
}
//(*--------------------------------------------*)
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
</script>    
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/Seguridad-Patrimonial-BigOne.png" width="100%" height="220px" >
  </header>
  <?php echo menu(); ?>     
<section id='s1'>
<article id="consulta" title='Registro de Movimiento'> 
<form id="formulario" method='post'>
<INPUT id="hddidmovimiento" name="hddidmovimiento" type="hidden" value="<?php echo $idmov; ?>" /> 
<table width="100%">      
    <tr>
      <td width="10%"><label>Fecha:</label></td>
      <td width="20%"><input class="form-control" readonly name='txtFecha' id='txtFecha' type='date' value='<?php echo(date("Y-m-d")); ?>'/><span class="input-group-btn"></td>
      <td width="5%"> &nbsp;</td>
      <td width="10%"><label>Unidad Solicitante:</label></td>
      <td width="20%"><INPUT type="text" id="txtunidadsolicitante" value="<?php echo $row2['unidad']; ?>" name="txtunidadsolicitante" readonly  class="form-control input-sm"/></td>
      <td width="5%"> &nbsp;</td>
      <td width="10%"><label>Conforma:</label></td>
      <td width="20%"><INPUT type="text" id="txtunidadautoriza" name="txtunidadautoriza" value="<?php echo $row3['unidad']; ?>" readonly class="form-control input-sm"/>
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
      <td><select width="100%" class="form-control" name="cbomovimiento" id="cbomovimiento" > 
            <option <?php $tipo=($row1['tipo_movimiento'] == 'ENTRADA')?'SALIDA"':"ENTRADA"; ?> value="<?php echo $tipo; 
?>"><?php echo $tipo; ?></option>
        </select>
      </td>
      <td>&nbsp;</td>
      <td><label>Conductor:</label></td>
      <td><INPUT type="text" id="txtconductor" name="txtconductor" maxlength="50" value="<?php echo $row1['conductor']; ?>" class="form-control"/></td>
      <td>&nbsp;</td>
      <td><label>CI. Conductor:</label></td>
    <td><INPUT type="text" id="txtciconductor" name="txtciconductor" maxlength="10" value="<?php echo $row1['ci_conductor']; ?>" class="form-control"/></td>
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
      <td><label>Placa Veh.:</label></td>
      <td><INPUT type="text" id="txtplaca" name="txtplaca"  maxlength="10" value="<?php echo $row1['placa']; ?>" class="form-control"/></td>
      <td> &nbsp;</td>
      <td><LABEL>Marca Veh.:</LABEL></td>
      <td><INPUT type="text" id="txtmarca" name="txtmarca"  maxlength="20" value="<?php echo $row1['marca']; ?>" class="form-control"/></td>
      <td>&nbsp;</td>
      <td><LABEL>Modelo Veh.:</LABEL></td>
      <td><INPUT type="text" id="txtmodelo" name="txtmodelo"  maxlength="20" value="<?php echo $row1['modelo']; ?>" class="form-control"/></td>
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
    <td><label>Color Veh.:</label></td>
    <td><INPUT type="text" id="txtcolor" name="txtcolor" maxlength="20" value="<?php echo $row1['colores']; ?>" class="form-control"/></td>
    <td>&nbsp;</td>
    <td><LABEL>Nombre Contacto:</LABEL></td>
    <td><input class="form-control" size="50" maxlength="50" name='txtnombre_contacto' id='txtnombre_contacto' value="<?php echo $row1['nombre_contacto']; ?>" type='text' /></td>
    <td>&nbsp;</td>
    <td><LABEL>C.I. Contacto:</LABEL></td>
    <td><input class="form-control" size="50" maxlength="10" name='txtcedula_contacto' id='txtcedula_contacto' type='text' value="<?php echo $row1['cedula_contacto']; ?>"/></td>
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
    <td><LABEL>Tlf. Contacto:</LABEL></td>
    <td><input class="form-control" size="30" maxlength="50" name='txttlf_contacto' id='txttlf_contacto' type='text' value="<?php echo $row1['tlf_contacto']; ?>"/></td>
    <td>&nbsp;</td>
    <td><LABEL>Unidad Adscripcion</LABEL></td>
    <td><input class="form-control" size="50" maxlength="50" name='txtunidad_adscripcion' id='txtunidad_adscripcion' type='text' value="<?php echo $row1['unidad_adscripcion']; ?>"/></td>
    <td>&nbsp;</td>
    <td><label>Nombre Destinatario:</label></td>
    <td><input class="form-control" maxlength="150" name='txtnombre_destinatario' id='txtnombre_destinatario' type='text' value="<?php echo $row1['nombre_destinatario']; ?>"/></td>
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
  
</table>
<p>&nbsp;</p>
<table width="100%" >
<tr>
  <td><LABEL>Motivo:</LABEL></td>
  <td><input id="txtobjetivo" name="txtobjetivo" readonly="" value="<?php echo $row1['descripcion_operacion']; ?>" class="form-control" >   
  </td>
</tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
<tr>
  <td><label>Direccion Destino:</label></td>
  <td><input width="90%" class="form-control" size="150" maxlength="150" name='txtdestino' id='txtdestino' type='text' value='<?php echo $row1['destino']; ?>'/></td>
</tr>
</table>
   <p>&nbsp;</p>
   <p><label>Descipcion de los Materiales, Herramientas, Equipos, Partes y Piezas</label></p>
<table class="table-bordered" id="tblMateriales">
  <tr>
      <th width="9%"><label>Cantidad</label></th>
      <th width="7%"><LABEL>Unidad</LABEL></th>
      <th width="30%"><LABEL>N° Vale Almacen/Serial</LABEL></th>
      <th width="50%"><LABEL>Descripcion del Material o equipo</LABEL></th>
      <th width="4%"><LABEL>Retorno</LABEL></th>
  </tr>
<?php
$i=0;
$materiales = array();
while($row4 = pg_fetch_array($res4, null, PGSQL_ASSOC)){
	if (array_key_exists('fecha_retorno', $row4)){
		if ($row4['cantidad']>0)
			$fecha_retorno=$row4['fecha_retorno'];			
		else
			$fecha_retorno=fecha_max_retorno_material($idmov, $row4['descripcion'], $cn);
		$cantidad_restante=$row4['cantidad_restante'];		
	}
	else{
		$fecha_retorno='';
		$cantidad_restante=$row4['cantidad'];
	}	
?>  
  <tr>
      <td width="9%"><?php echo $cantidad_restante; ?><INPUT id="hddcantret_<?php echo $i; ?>" name="hddcantret[]" type="hidden" value="<?php echo $cantidad_restante; ?>" /></td>
      <td width="7%"><?php echo $row4['unidad_medicion']; ?><INPUT id="hddunid_<?php echo $i; ?>" name="hddunid[]" type="hidden" value="<?php echo $row4['unidad_medicion']; ?>" /></td>
      <td width="30%"><?php echo $row4['serial_nro_almacen']; ?><INPUT id="hddvale_<?php echo $i; ?>" name="hddvale[]" type="hidden" value="<?php echo $row4['serial_nro_almacen']; ?>" /></td>
      <td width="50%"><?php echo $row4['descripcion']; ?><INPUT id="hdddescrip_<?php echo $i; ?>" name="hdddescrip[]" type="hidden" value="<?php echo $row4['descripcion']; ?>" /></td>
      <td width="4%"><?php echo substr ($fecha_retorno,0, 16); ?></td>
  </tr>
<?php   
   array_push($materiales, $row4['descripcion']);
   $i++;
}
$i=count($materiales);
?>   
  <tr>      
      <td width="9%"><INPUT id="txtCantidad" onchange="valida_cantidad()" onkeypress="return validar(event)" type="text" size="10" maxlength="10" class="form-control" /></td>
      <td width="7%"><select style="font-size:8pt" width="100%" class="form-control input-sm" name="cbounidad" id="cbounidad" > 
            <option value="PZA">PZA</option>
            <option value="MTS">MTS</option>  
            <option value="LTS">LTS</option>
            <option value="CUB">CUB</option>  
            <option value="MT2">MT2</option>
            <option value="TON">TON</option>  
            <option value="UND">UND</option>
        </select></td>
      <td width="30%"><INPUT id="txtvale" size="20" maxlength="20" type="text" value="N/A"  class="form-control"/></td>
      <td width="50%"><select id="txtdescmaterial" onchange="valida_cantidad()" width="100%" class="form-control input-sm" >
      	 				<?php foreach ($materiales as &$material){ ?>
      					<option value="<?php echo $material; ?>"><?php echo $material; ?></option>
      					<?php } ?>
      				</select>	
      </td>      
      <td width="4%"><INPUT id="cmdAgregar" type="button"  value="+"  class="form-control" onclick="valida_cantidad(); AgregarFilaMat();" /></td>
  </tr>
</table>
<INPUT id="hddCanMat" name="hddCanMat" type="hidden" value="<?php echo $i; ?>" />
<INPUT id="hddmateriales" name="hddmateriales" type="hidden" value="0" />
<p>&nbsp;</p>
<table> 
    <tr>
    <td><LABEL>Observacion:</LABEL></td>    
    </tr>
    <tr>
      <td><textarea class="form-control" id="txtobservacion"  maxlength="500"  name="txtobservacion" rows="3" cols="150" wrap="soft" ><?php echo $row1['observaciones']; ?></textarea></td>
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
</html>";
}
?>