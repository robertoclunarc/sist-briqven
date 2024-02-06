<?php 
session_start();
if (isset($_SESSION['user_session_sio'])){
    require('libs/menu.php');  
    require_once('funciones_var.php');
    //echo date('Y-m-d',strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));  
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Reg. Produccion</title>
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

function GuardarRegistro()
{
  if ($("#tn_proy").val()=="")
  {
    alert("Ingrese la cantidad de Tonelada Programa Original Mens");
    $("#tn_proy").focus();
    return;
  }

  if ($("#tn_prog_orig").val()=="")
  {
    alert("Ingrese cantidad Programada Original del Mes");
    $("#tn_prog_orig").focus();
    return;
  }
  
  if ($("#tn_plan_mes").val()=="")
  {
    alert("Ingrese cantidad Planif. Anual. del Mes");
    $("#tn_plan_mes").focus();
    return;
  }
  
  if ($("#apertura_archivo").val()<=0)
  {
    alert("Seleccione la modalidad de apertura del archivo .txt");
    $("#apertura_archivo").focus();
    return;
  }

  document.getElementById("cmdGuardar").disabled = true;
//  mostrar("ver", "images/loading.gif");
    dir_url = "carga_mensual_db.php";
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
            alert("*** Datos Guardados Correctamente! ***");
 	    //alert(data); // Mostrar la respuestas del script PHP.
//            ocultar("ver");
/*	    if(confirm("Desea enviar los datos por FTP?")){ 
                 dir_url2 = "enviar_archivos.php";
		 $.ajax({
           		type: "POST",
           		url: dir_url,
           		data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           		success: function(data)
           		{	
         	 	    alert(data); // Mostrar la respuestas del script PHP.
           		}
         	}); 
            }
*/
            //location.href = "index.php";
            location.reload(); //Recargar la página desde cero.
          }
         else
            {
              alert("La operación Generó un Error:" + data);
              document.getElementById("cmdGuardar").disabled = false;
              ocultar("ver");
            }         
          }
         });

}
//(*--------------------------------------------*)
function buscarDatosAntMes()
{
//alert('paso');
  var linea = $("#cod_linea").val();
  var ano= $("#cbomes").val();
  if (linea != "null" && ano > 0){
      mostrar("ver", "images/loading.gif");  
      url="cargar_datos_anteriores_mes.php?fecha=" +ano+"&linea="+linea;  
      $.ajax(url).done(function(data)
       {     
          if (data!="0")
          {
            eval(data); //aquí vienen los datos de la pagina php               
            ocultar("ver");  
          }      
       }
      );
  }      
}

//(*--------------------------------------------*)
function existeUrl(url) {
   var http = new XMLHttpRequest();
   http.open('HEAD', url, false);
   http.send();
   return http.status!=404;
}

//(*--------------------------------------------*)
function mostrar(cdiv, img){
document.getElementById(cdiv).innerHTML="";
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="'+img+'" alt="" height="60" width="60">';
$(cdiv).show(); 
$('#loading').show();
}
//(*--------------------------------------------*)
function ocultar(cdiv){
 document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
$(cdiv).hide();
$('#loading').hide();

}
//(*--------------------------------------------*)
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
//alert(tecla) 
    if (tecla==8) {  return true; }  //Tecla de retroceso (para poder borrar)
    if (tecla==46) {  return true; }  //Coma ( En este caso para diferenciar los decimales )
    if (tecla==48) {  return true; } 
    if (tecla==49) {  return true; } 
    if (tecla==50) {  return true; } 
    if (tecla==51) {  return true; } 
    if (tecla==52) {  return true; } 
    if (tecla==53) {  return true; } 
    if (tecla==54) {  return true; } 
    if (tecla==55) {  return true; } 
    if (tecla==56) {  return true; } 
    if (tecla==57) {  return true; } 
    patron = /1/; //ver nota
//alert(patron);
    te = String.fromCharCode(tecla);
    return patron.test(te); 
} 
//(*--------------------------------------------*)
function limpiar_campos(){
  $("#hddcedula").val("");
  $("#nombre").val("");
  $("#txtcargo").val("");
  $("#txtDepartamento").val("");
  $("#txtjefeinmediato").val("");  
 }
//(*--------------------------------------------*)

function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}
//(*--------------------------------------------*)
function calcular(){
  var tn_real = $("#tn_real").val();
 }
//(*--------------------------------------------*)
$(document).ready(function(){
  //alert("aqui");  
  //ocultar la tabla de datos personales  
  $("#tbl_datos_personales").hide();
  
 CargarCombo($("#cod_linea"),"cargar_combo_db.php?tabla=reactores&campo1=idreactor&campo2=descripcion&where='ACTIVO'&cond=estatus&selected=0&orderby=idreactor&firsttext=[Elija Linea de Produccion]");
 
// if ($('#tn_desp_var_mes').val()==0){

//   calcular_diferencia(tn_desp_mes,tn_desp_plan_mes);
 //}

});
//(*--------------------------------------------*)

</script>
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/sitegestion.jpg" width="100%" height="220px" >
  </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>
<section id='s1'>
<article id="consulta" title='Registro de Acceso'>
<!-- AQUI EL CONTENIDO -->
<?php
$fecha = date('Y-m-d');
$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
?>
<form id="formulario" method='post'>
  <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_sio']; ?>'/>
  <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_sio']; ?>'/>
  <input  name='apertura_archivo' id='apertura_archivo' type='hidden' value='2'/>
  <input  name='accion' id='accion' type='hidden'/>
<table width="80%" border="0">
<h4>Programaci&oacute;n mensual</h4>      
    <tr>      
      <td><label>Linea:</label></td>
      <td><select class="form-control" onchange="buscarDatosAntMes()" name="cod_linea" id="cod_linea"></select> </td>
      <td>&nbsp;</td>
      <td align="rigth"><label>Mes:</label></td>
      <td> <select id="cbomes" name="cbomes" onchange="buscarDatosAntMes()"  class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true" ><?php echo llenar_combo_mes(); ?></select></td>
      <td>&nbsp;</td>
    </tr>    
</table>
<p>&nbsp;</p>    
    <table width="100%" border="0">
	<h4>Producci&oacute;n </h4>      
       <tr>
          <td width="12%"> &nbsp;</td>   
          <td width="8%"> &nbsp;</td>                                             
          <td width="12%"> &nbsp;</td>   
          <td width="8%"> &nbsp;</td>
          <td width="12%"> &nbsp;</td>   
        </tr>

       <tr>
          <th width="12%">Tn. Ajustadas Programa Original Mens.</th>
          <td width="8%"> &nbsp;</td>
          <th width="12%">Tn. Programada Original del Mes</th>
          <td width="8%"> &nbsp;</td> 
          <th width="12%">Tn. Planif. Anual. del Mes</th>
        </tr>
        <tr>
          <td >            
            <input id="tn_proy" name="tn_proy" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >
          </td>
          <td > &nbsp;</td>
          <td >            
            <input id="tn_prog_orig" name="tn_prog_orig" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" > 
          </td>
          <td > &nbsp;</td>
          <td>            
            <input id="tn_plan_mes" name="tn_plan_mes" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >
          </td>
      </tr>
    </table>
<p>&nbsp;</p>
  <h4>Inventario </h4>
<table width="100%">
    <tr>
      
      <th width="12%">Mercado</th>
      <td width="8%"> &nbsp;</td>
      <th width="12%">Producto</th>
      <td width="8%"> &nbsp;</td>
      <th width="12%">Tn. Planificadas anualmente</th> 
    </tr>
        <tr>
         <td >            
            <select class="form-control" name="mercado" id="mercado">
              <option selected="" value="V1">Mercado Exportación (V1)</option>
            <!--  <option value="V2">Mercado Interno (V2)</option>  -->
            </select>              
        </td>
       <td > &nbsp;</td>    
       <td >            
            <select class="form-control" name="producto" id="producto">
              <option selected="" value="112">Briquetas (112)</option>              
            </select>
        </td> 
        <td > &nbsp;</td>    
       <td >            
            <input id="tn_inv_plan_anual" name="tn_inv_plan_anual" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >              
        </td>       
       
       </tr>
    </table>   
 
<p>&nbsp;</p>
 <h4>Despacho</h4>

    <table width="100%" border="0">
    <tr>
      <th width="12%">Tn. de despacho plan. lo que va de mes</th>
      <td width="8%"> &nbsp;</td>
      <th width="12%">Tn. estimadas al cierre del mes</th> 
      <td width="8%"> &nbsp;</td>
      <th width="12%">Tn. del plan original del mes</th>
    </tr>
    <tr>
       <td >
            <input id="tn_desp_plan_mes" name="tn_desp_plan_mes" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >
        </td>
          <td > &nbsp;</td>   

       <td >            
            <input id="tn_estim_cierre" name="tn_estim_cierre" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >              
        </td>
          <td > &nbsp;</td>   
        <td >            
            <input id="tn_original_plan" name="tn_original_plan" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >              
        </td>
       </tr>
       <tr>
          <td > &nbsp;</td>   
          <td > &nbsp;</td>                                             
       </tr>
       <tr>
      	  <th width="12%">Tn. del presupuesto anual de despacho</th>
          <td > &nbsp;</td>

          <th>Tn. de despacho plan. acum. del año en curso (pea)</th>
          <td > &nbsp;</td>
        </tr>
        <tr>
        <td>
            <input id="tn_desp_pea" name="tn_desp_pea" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" > 
        </td>
          <td > &nbsp;</td>
        <td>            
            <input id="tn_desp_plan_acum" name="tn_desp_plan_acum" type="text" class="form-control" style="z-index: 0;" placeholder="eeeeeeeee.dd" maxlength="12" onkeypress="return validar(event);" onkeyup="calcular()" >              
        </td>
        <td > &nbsp;</td>
      </tr>
      <tr>
          <td > &nbsp;</td>   
      </tr> 	
    </table> 

<p>&nbsp;</p>

<table class="" width="100%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar"  class="btn btn-success" ondblclick="GuardarRegistro();"/></td>
    <td width="30%"><div id='ver'></div></td>
  </tr>
</table>
<p>&nbsp;</p>
</article>
</form>

</article>
</section>
</body>
</html>
 <?php 

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
