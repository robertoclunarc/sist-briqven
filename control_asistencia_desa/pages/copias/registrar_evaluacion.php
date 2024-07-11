<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $color="";
 // *************************  Consultamos los datos de los trabajadores ****************************

  $query="SELECT trabajador, e_mail,  (nombres || ' ' || apellidos) as Nombre_Completo, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia FROM public.trabajadores_activos_con_jefes_1 where supervisor='".$_SESSION['cedula_session_const']."'";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);

  if($numReg>0){
    $option='<option selected value="0">Seleccione el trabajador</option>';
    while ($fila=ejecutar_fetch_array($result)) 
    {
      $option.= "<option value='". $fila['trabajador']."'" ;
      if ($fila[2] != "")
        $option.= ">". $fila[2]. "</option>";
      else   
        $option.= ">". $fila['trabajador']. "</option>";
    }
  }
	$esperanza="";


 $fecha_actual = date("Y-m-d");
 $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

 // **************************** Llenamos e l combo de Periodo a evaluar  ***************************************
  $query_periodo="select * from periodo_e where hasta <= '".$fecha_a."' order by num_periodo desc";
  $result2 = ejecutar_query($link, $query_periodo) or die("Error en la Consulta SQL: ".$query_periodo);
  $numReg2 = ejecutar_num_rows($result2);
  if($numReg2>0){
    $periodo='<option selected value="0">Seleccione el Periodo a Evaluar</option>';
    while ($fila=ejecutar_fetch_array($result2))
    {
      $periodo.= "<option value='". $fila['num_periodo']."'" ;
      $periodo.= ">Desde: ". $fila['desde']. " - Hasta: ".$fila['hasta']."</option>";
    }
  }

 // **************************** Llenamos e l combo de Puntuacion   ***************************************
/* 
 $query_baremo="select * from baremo";
  $result3 = ejecutar_query($link, $query_baremo) or die("Error en la Consulta SQL: ".$query_baremo);
  $numReg3 = ejecutar_num_rows($result3);
  if($numReg3>0){
    $baremo= "<option selected value=\"0\">Seleccione la Puntuaci&oacute;n</option>"; 
    while ($fila=ejecutar_fetch_array($result3))
    {
      $baremo.= "<option value='". $fila['puntuacion']."'";
      switch ($fila['puntuacion']) {
        case 0:
      	    $baremo.= " data-content=\"<span class='label label-danger'>";
            break;
        case 1:
            $baremo.= " data-content=\"<span class='label label-warning'>";
            break;
        case 2:
            $baremo.= " data-content=\"<span class='label label-success'>";
            break;
        case 3:
            $baremo.= " data-content=\"<span class='label label-primary'>";
            break;
        case 4:
            $baremo.= " data-content=\"<span class='label label-info'>";
            break;
      }
      $baremo.=$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
      $baremo.= ">". $fila['puntuacion']." - ". $fila['porcentaje']."%</option>";
    }
  }
*/

  ?>
<!DOCTYPE html>
<html lang="es">

<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registrar Evaluaci&oacute;n</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">

     
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>

<script  language="javascript">
//(*--------------------------------------------*)
function GuardarRegistro()
{

  if (parseInt($("#hddcontador").val())==0){
    alert("Debe agregar los trabajadores");    
    return;
  }
 if (parseInt($("#hddcontador").val())<(parseInt($("#numreg").val()))){
    alert("Debe agregar todos los trabajadores");    
    return;
  }
  
if(confirm("Esta seguro de registrar la evaluacion?")){  
   document.getElementById("cmdGuardar").disabled = true;
//  mostrar("ver", "images/preloader-01.gif");
    
    dir_url = "registrar_evaluacion_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
           if (data>0)
            {
              alert("Datos Registrados Correctamente!");
              location.reload();
            }
           else
           {
            alert("La operación Generó un Error: " + data);
            document.getElementById("cmdGuardar").disabled = false;
  //          ocultar("ver");
           }   
           }
         });
}  

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
function AgregarFilaInfo(idtipoeqp)
{
//alert($('#cbopuntuacion').val());
	if ((($("#cbotrabajador").val())!='') && ($("#cbotrabajador").val())!='0' && (($("#cbotrabajador").val())!=null)  && (!valida_repetido($("#cbotrabajador").val()))){
	    if ($('#cboperiodo').val()>0){
		if ($('#cbopuntuacion').val()>=-1){
		    var cont=parseInt($("#hddcontador").val()) + 1;
		    document.getElementById('hddcontador').value=cont;
		
		    var valor_col1=$("#cbotrabajador").val();    
        	    var valor_col2=$("#cbotrabajador option:selected").text();
		    var valor_col4=$("#cbopuntuacion").val();    
		    var valor_col4t=$("#cbopuntuacion option:selected").text();
		    var valor_col5=$("#txtobservacion").val();     
		    
	    
		    var ctrl_col1="<input name='cedulas[]' type='hidden' value='" + valor_col1 + "'/>";
		    var ctrl_col2="<input name='nombres[]' type='hidden' value='" + valor_col2 + "'/>";
		    var ctrl_col4="<input name='puntuacion[]' type='hidden' value='" + valor_col4 + "'/>";
		    var ctrl_col5="<input name='observacion[]' type='hidden' value='" + valor_col5 + "'/>"

		    var tabla="<tr>";
		    tabla=tabla+"<td width='12%'>"+valor_col1+ctrl_col1+"<td>";
		    tabla=tabla+"<td width='20%'>"+valor_col2+ctrl_col2+"<td>";
		    tabla=tabla+"<td width='10%'>"+valor_col4t+ctrl_col4+"<td>";
		    tabla=tabla+"<td width='10%'>"+valor_col5+ctrl_col5+"<td>";
		    tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd();'/><td>";
		    tabla=tabla+"<tr>";
      
		    $("#tblInfoAdicional").after(tabla);
		    limpiar_campos();
	      }else{
  	        alert('Seleccione la puntuación');  
              }
	   }else{
              alert('Seleccione el periodo a evaluar');
	   }
  	}
}

//(*--------------------------------------------*)
function elimFilaProd()
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;
} 
//(*--------------------------------------------*)
function buscar_trabajador(idtipoeqp,fecha)
{
  
 if (idtipoeqp!='')
    {
      url="cargar_esperanza.php?b=" + idtipoeqp + "&c="+fecha; 
      $.ajax(url).done(function(data)
       {   
          if (data != "VACIO"){
              eval(data);
        //      AgregarFilaInfo(idtipoeqp);
          }
       }
      )
    }
}
//(*--------------------------------------------*)
function cargar_esperanza()
{

      url="cargar_esperanza.php?b=" + idtipoeqp + "&c="+fecha; 
//      alert(url);
      $.ajax(url).done(function(data)
       {   
       //alert('data:'+data);
          if (data != "VACIO"){
              eval(data);
        //      AgregarFilaInfo(idtipoeqp);
          }
       }
      )
}
//(*--------------------------------------------*)
/*
function llenar_combo_permisos()
{
// if (idtipoeqp!='')
//    {
      url="llenar_combo_permisos.php"; 
alert('paso');
      $.ajax(url).done(function(data)
       {          
          if (data != "VACIO"){
              eval(data);
//              AgregarFilaInfo(idtipoeqp);
          }
       }
      )
//    }
}
*/
//(*--------------------------------------------*)
function ventanaAct(campo){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_usuarios.php?campo='+campo, "Buscar Usuarios", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
 }
//(*--------------------------------------------*)

function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
//alert(data);
      }
  );  
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
/*
function mostrar(cdiv, foto){
document.getElementById(cdiv).innerHTML="";
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="'+foto+'" alt="" height="60" width="60">';
$(cdiv).show(); 
$('#loading').show();    
}
//(*--------------------------------------------*)
function ocultar(cdiv){
document.getElementById(cdiv).innerHTML = '<img id="loading" name="loading" src="" alt="" height="60" width="60">';
$(cdiv).hide();
$('#loading').hide();
}
*/   
//(*--------------------------------------------*)
function limpiar_campos(){
  $("#txtobservacion").val("");   
//  CargarCombo($("#cbopuntuacion"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje");
CargarCombo($("#cbopuntuacion"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje&firsttext=Seleccione la putuacion");
   CargarCombo($("#cbotrabajador"),"cargar_combo_db.php?tabla=trabajadores_activos_con_jefes_1&campo1=trabajador&campo2=(nombres || ' ' || apellidos) as Nombre_Completo&firsttext=Seleccione el trabajador&orderby=Nombre_completo&where=supervisor&vwhere="+$("#hddcedula_login").val());
}
//(* ---------------------------------------------*)
function limpiar_hora(){
  $("#txthora_entrada_real").val("");
  $("#txthora_salida_real").val("");   
 }

//(*-----------------------------------------------*)

$(document).ready(function(){ 
    /*$("select[name=cbopermiso]").change(function(){
            alert($('select[name=cbopermiso]').val());
            //$('input[name=valor1]').val($(this).val());
        });
*/

        if ($('#numreg').val()==0){
              alert('Lo siento, usted no tiene personal bajo su supervision');  
              window.location="index.php";
        }

	$("#cbopuntuacion").change(function(){
	   //  alert('paso');
	});


	$("#cboperiodo").change(function(){
		CargarCombo($("#cbotrabajador"),"cargar_combo_db.php?tabla=trabajadores_activos_con_jefes_1&campo1=trabajador&campo2=(nombres || ' ' || apellidos) as Nombre_Completo&firsttext=Seleccione el trabajador&orderby=Nombre_completo &where=supervisor&vwhere="+$("#hddcedula_login").val());
		CargarCombo($("#cbopuntuacion"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje&firsttext=Seleccione la putuacion");
	});

   //llenar_combo_puntuacion();
   CargarCombo($("#cbopuntuacion"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje&firsttext=Seleccione la puntuación");
   CargarCombo($("#cbopuntuacion1"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje&firsttext=Seleccione la puntuaciónnnnnnnnn");

   //alert($("#hddcedula_login").val());
   CargarCombo($("#cbotrabajador"),"cargar_combo_db.php?tabla=trabajadores_activos_con_jefes_1&campo1=trabajador&campo2=(nombres || ' ' || apellidos) as Nombre_Completo&firsttext=Seleccione el trabajador&orderby=Nombre_completo&where=supervisor&vwhere="+$("#hddcedula_login").val());

   // CargarCombo($("#cbologin_asignado"),"cargar_combo_db.php?tabla=tbl_usuarios&campo1=login&campo2=nombres&selected=0&firsttext=[Elija Usuario de Atencion]&orderby=nombres&where=nivel&cond=1");
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
                <a class="navbar-brand" href="index..php">Control Asistencia - Registrar Evaluaci&oacute;n</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Registrar Evaluaci&oacute;n</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del trabajador
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                       <input  name='hddcedula_login' id='hddcedula_login' type='hidden' value='<?php echo $_SESSION['cedula_session_const']; ?>'/>
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
					<input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/>
					<input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="90%" class="table table-striped" border="0">
  					  <thead>
                                             <tr>
                                                  <th width="20%"><label>Periodo a Evaluar</label></th>
                                                  <th width="20%">
							<select name="cboperiodo" onchange="" id="cboperiodo" class="form-control" data-width="80%" data-size="5"  data-hide-disabled="false" data-live-search="true">
                                              			<?php echo $periodo; ?>
							</select>
						  </th>
                                            </tr>  
                                             <tr>

                                            	  <th width="20%"><label>Trabajador:</label></th>
                                                  <th width="40%">
							<!--<select name="cbotrabajador" onchange="buscar_trabajador($('#cbotrabajador').val(),$('#txtfecha').val());" id="cbotrabajador" data-width="80%" data-size="5" class="selectpicker" data-hide-disabled="false" data-live-search="true">-->
							<select name="cbotrabajador" onchange="buscar_trabajador($('#cbotrabajador').val(),$('#txtfecha').val());" id="cbotrabajador" class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true">
                                              			<?php// echo $option; ?>
							</select>
						     <?php //echo $esperanza; ?>
                                            </tr>                                              
					    <tr>
						<th width="25%"><label>Puntuaci&oacute;n</label></th>
<!--                                                 <th width="90%" colspan='3'><select name="cbopuntuacion1" id="cbopuntuacion1" class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true"></select> 
-->
                                                <th width="90%" colspan='3'><select name="cbopuntuacion" id="cbopuntuacion"  class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true"></select><label><?php echo $color; ?></label>
<div id="ponderacion"></div>

						</th>
				 	    </tr>
				              <tr>
					        <th width="10%"><label>Observaci&oacute;n:</label></th>
                                            <td colspan="4"><textarea name="txtobservacion" id="txtobservacion" class="form-control" rows="2"></textarea></td>
				              </tr>
					      <tr>
                                                 <td>
						    <div class="form-group input-group">
                                                      <span class="input-group-btn">
                                                       	 <button class="btn btn-default" onclick="AgregarFilaInfo();" type="button"><i class="fa fa-plus-circle"></i></button>
                                                      </span>        
	                                           </div>
					         </td>
					      </tr>
                                       <!--  </tbody> --> 
  				          </thead> 
                                        </table>
                                      </div>
                                      <div class="col-lg-6">  
                                        <table width="90%" id="tblInfoAdicional" class="table table-striped" border="0">
                                          <thead>
                                              <tr>
                                                  <th width="10%">C&eacute;dula</th>
                                                  <th width="25%">Nombres</th>
                                                  <th width="10%">Puntuaci&oacute;n</th>
                                                  <th width="10%">Observaciones</th>
                                                  <th width="5%"></th>                                                                  
					      </tr>
                                          </thead>   
                                        </table>
                                       
                                      </div>
                                       <p>&nbsp;</p>  
                                      <table class="" width="90%" id="tblGuardar" align="center">  
                                        <tr>
                                          <td width="30%">&nbsp;</td>
                                          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Evaluaci&oacute;n"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
                                          <td width="30%">&nbsp;</td>
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
