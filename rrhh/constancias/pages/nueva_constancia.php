<?php
 session_start();
if (isset($_SESSION['user_session_conslab']) && isset($_SESSION['nivel_conslab'])){
  require_once('menu.php');
  require_once('menu2.php');
  ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nueva Constancia</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

     <script src="../js/jquery-1.11.1.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script  language="javascript">
//(*--------------------------------------------*)
function GuardarRegistro(tipo)
{
  if (tipo=='A')
    formato='formato_constancias1.php';
  else
    formato='formato_constancias2.php';
  if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros");    
    return; 
  }

    dir_url = "registrar_constancia_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
         //alert(data); // Mostrar la respuestas del script PHP.
	         if (data!="ERROR")
	          {
	            alert("La Constacia Fue Emitida Correctamente!"+data);              
              abrir_constacias(data,formato);
	            location.reload();
	          }
	         else
	         {
	          alert("La operación Generó un Error: " + data);
	         }   
           }
         });  

}
//(*--------------------------------------------*)
function abrir_constacias(fecha, formato)
{
 var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open(formato+"?fec="+fecha, "Constacia", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");          
   
}
//(*--------------------------------------------*)

function BuscarInfo(cedula)
{ 
mostrar('');   
  url="buscar_datos_trabajador.php?b=" + cedula; 
  //alert("Esta es la url: " + url);
  $.ajax(url).done(function(data)
   {     
      if (data!="0")
      {
        eval(data);
        ocultar();
      }
      else{
        mostrar('<label>NO EXISTE TRABAJADOR!!</label>');
        limpiar_campos();
      }
   }
  );  
}
function limpiar_campos(){
  $("#hddcedula").val("");
  $("#txtnombres").val("");
  $("#hddtestatus").val("");   
  $("#txtdestinatario").val("A QUIEN PUEDA INTERESAR"); 

 }
//(*--------------------------------------------*)
function mostrar(nota){
document.getElementById('resultado').innerHTML="";
if (nota!='')
document.getElementById('resultado').innerHTML = nota;
else
  document.getElementById('resultado').innerHTML = '<img id="loading" name="loading" src="images/btn-ajax-loader.gif" alt="" height="40" width="40">';

$('#resultado').show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(){
  document.getElementById('resultado').innerHTML = '';
 //  document.getElementById('resultado').innerHTML = '<img id="loading" name="loading" src="" alt="" height="40" width="40">';
$('resultado').hide();
$('#loading').hide();
}
//(*--------------------------------------------*)
/*function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}*/
//(*--------------------------------------------*)
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    //if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
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
    if (tecla==13) {BuscarInfo($(txtcedula).val()); return true;}
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
}
//(*--------------------------------------------*)
function valida_repetido(ced){
  var j=parseInt($("#hddcontador").val());
  var entro = false;
  if (j>0){
    $('input[type=hidden]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='cedulas[]') && (cb.attr('value')==ced))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)
function AgregarFilaInfo()
{
  if ($("#txtnombres").val()!='' && !valida_repetido($("#hddcedula").val())){
    var cont=parseInt($("#hddcontador").val()) + 1;
    document.getElementById('hddcontador').value=cont;

    var valor_col1=$("#hddcedula").val();  
    var valor_col2=$("#txtnombres").val();    
    var valor_col3=$("#cbotipo").val();
    var valor_col4=$("#hddtestatus").val();         
    var valor_col5=$("#txtdestinatario").val();
    

    var ctrl_col1="<input name='cedulas[]' type='hidden' value='" + valor_col1 + "'/>";
    var ctrl_col2="<input name='nombres[]' type='hidden' value='" + valor_col2 + "'/>";
    var ctrl_col3="<input name='tipos[]' type='hidden' value='" + valor_col3 + "'/>";
    var ctrl_col4="<input name='estatuss[]' type='hidden' value='" + valor_col4 + "'/>";
    var ctrl_col5="<input name='destinatario[]' type='hidden' value='" + valor_col5 + "'/>";
    
    var tabla="<tr>";
    tabla=tabla+"<td width='15%'>"+valor_col1+ctrl_col1+"<td>";
    tabla=tabla+"<td width='35%'>"+valor_col2+ctrl_col2+"<td>";
    tabla=tabla+"<td width='15%'>"+valor_col3+ctrl_col3+"<td>";
    tabla=tabla+"<td width='10%'>"+valor_col4+ctrl_col4+"<td>";
    tabla=tabla+"<td width='20%'>"+valor_col5+ctrl_col5+"<td>";
    tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd();'/><td>";
    tabla=tabla+"<tr>";
      
    $("#tblInfoAdicional").after(tabla);
    $("#txtcedula").val("");
    limpiar_campos();
  }
     
}
//(*--------------------------------------------*)
function elimFilaProd()
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;
} 
//(*--------------------------------------------*)
function CargarCombo(nombcombo, url)
{
  //$cboMotivos = $(nombcombo);
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
function mayusculas(e) {
    e.value = e.value.toUpperCase();
}

//(*--------------------------------------------*)
$(document).ready(function(){   
 
  CargarCombo($("#cbotipo"),"cargar_combo_db.php?tabla=tbl_tipos_constancias&campo1=descripcion&campo2=observacion&orderby=idconstacia&where=estatus&vwhere=ACTIVO"); 
   
});
//(*--------------------------------------------*)
</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index..php">Constancias - Emision</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Nueva Constancia</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos para la Emision de Constancias
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
                                     <div class="col-lg-6">    
      
      <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_conslab']; ?>'/>
      <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_conslab']; ?>'/> 
      <input  name='hddestatus' id='hddtestatus' type='hidden' value=''/>
      <input  name='hddcedula' id='hddcedula' type='hidden' value=''/> 
      <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>
<table width="90%" >
    <tr>
      <td width="25%"><label>Cedula:</label></td>
      <td width="5%"> &nbsp;</td>
      <td width="70%"><label>Nombres</label></td>
    </tr>   
    <tr> 
       <td>
        
         <div class="form-group input-group">
           <INPUT type="text" id="txtcedula" onkeypress="return validar(event)" maxlength="10" name="txtcedula" value="" width="100%"  class="form-control"/> 
            <span class="input-group-btn">
              <button class="btn btn-default" onclick="BuscarInfo($('#txtcedula').val());" type="button">
                <i class="fa fa-search-plus"></i>
              </button>
            </span>        
         </div>
        </td>
        <td> &nbsp;</td>
        <td>
          <div class="form-group input-group">
            <input type="text" id="txtnombres" maxlength="100" placeholder="" readonly="" name="txtnombres"  class="form-control">
            <span class="input-group-btn">
              <button class="btn btn-default" onclick="AgregarFilaInfo();" type="button">
                <i class="fa fa-plus-circle"></i>
             </button>
            </span>        
          </div>
        </td>
    </tr>
    <tr>
       <td><label>Tipo</label></td>
       <td> &nbsp;</td>
       <td><label>Destinatario</label></td>
    </tr>
     <tr>
       <td><select name="cbotipo" id="cbotipo" class="form-control" >
       <!--   <option value="Salario Basico">Basico</option>
          <option value="Salario Integral">Integral</option>
          <option value="Basico + Integral">Ambos</option>  -->
          <option selected value="Ninguno">Ninguno</option>
       </select></td>
       <td> &nbsp;</td>
       <td>
       <div class="form-group input-group">
            <input type="text" id="txtdestinatario" maxlength="100" placeholder="" value="A QUIEN PUEDA INTERESAR" name="txtdestinatario"  onkeyup="mayusculas(this);" class="form-control">
            <span class="input-group-btn">
              <button class="btn btn-default" onclick="AgregarFilaInfo();" type="button">
                <i class="fa fa-plus-circle"></i>
              </button>
            </span>        
          </div>
    </tr>      
    <tr>
        <td colspan="3" align="center">
          <div id="resultado">&nbsp;</div>
        </td>  
    </tr>
</table>
</div>

 <div class="col-lg-6">
  <table width="90%" id="tblInfoAdicional" class="table table-striped" >
      <thead>
          <tr>
              <th width="15%">Cedula</th>
              <th width="35%">Nombres</th>
              <th width="15%">Tipo</th>
              <th width="10%">Estatus</th>  
              <th width="20%">Destinatario</th>
              <th width="5%"></th>                            
          </tr>
      </thead>   
  </table>
 
 </div>
 <p>&nbsp;</p>  
<table class="" width="90%" id="tblGuardar" align="center" >  
  <tr>
    <td width="25%">&nbsp;</td>
    <td width="25%" align="center"><INPUT id="cmdGuardar" type="button" value="Emitir Constancia Tipo A"  class="btn btn-success" onclick="GuardarRegistro('A');"/></td>
    <td width="25%" align="center"><INPUT id="cmdGuardar" type="button" value="Emitir Constancia Tipo B"  class="btn btn-success" onclick="GuardarRegistro('B');"/></td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
                               
                             
                               
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
<?php 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>