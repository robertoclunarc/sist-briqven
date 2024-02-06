<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $link2=Conex_rrhh_pgsql();
  $link_sitt=Conectarse_sitt();

  $trabajador= isset($_GET["cedula"])?$_GET["cedula"]:"";
  $fecha_actual= isset($_GET["fecha"])?$_GET["fecha"]:date("Y-m-d");
  $fecha_a =date("Y-m-d",strtotime($fecha_actual."+ 1 days"));
  
  $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";
  
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option1='';
    $option2='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $selected=$fila['trabajador']==$trabajador?"selected":"";
        $option1.= "<option ".$selected."  value='". $fila['trabajador']."'" ;
        $option1.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";

        $option2.= "<option value='". $fila['trabajador']."'" ;
        $option2.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";
    }
  }

  $option3= "" ;
  $qry="select cod_causa, desc_causa from causas_st_dlt order by 1";
  $result1 = ejecutar_query($link, $qry) or die("Error en la Consulta SQL: ".$qry);
  
  while ($fila1=ejecutar_fetch_array($result1))
  {
     $option3.= "<option value='". $fila1['cod_causa']."'" ;
     $option3.= ">". $fila1['desc_causa']. "</option>";    
  }
 
  $option4= "" ;
  $qry4="select puesto, desc_puesto from ADAM_PUESTOS order by 1";
  $stmt4 = $link_sitt->query($qry4);
  $option4='';
  while($row4 = $stmt4->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
  {
     $option4.= "<option value='". $row4['puesto']."'>" ;
     $option4.= $row4['puesto']." - ".$row4['desc_puesto']. "</option>"; 
  }

   $query="SELECT sistema_horario, concat(sistema_horario, ' ', descripcion) descipcion  FROM public.sistema_horario order by 1;";
  $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optSH='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optSH.= "<option value='". $fila['sistema_horario']."'>" ;
        $optSH.= $fila['descipcion']. "</option>";
    }
  }

$option5= llenar_combo_movimiento();

  pg_close($link);
  pg_close($link2);
  pg_free_result($result);
  pg_free_result($result1);

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

    <title>Carga Movimiento</title>
    
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.css">    
    
    <script src="../js/jquery-1.11.1.min.js"></script>

<script  language="javascript">
//(*--------------------------------------------*)
function consultar_tipo(){
  let tipo=document.getElementById("cbotipomovimiento").value;
  let capa01 = document.getElementById('capa01');
  let capa02 = document.getElementById('capa02');
  let capa03 = document.getElementById('capa03');
  let capa05 = document.getElementById('capa05');
  let capa06 = document.getElementById('capa06');
  
  if (tipo==="1" || tipo==="2"){ 
    //alert("tipo1");
    document.getElementById('capa01').style.display = 'none';
    document.getElementById('capa02').style.display = '';
    document.getElementById('capa03').style.display = 'none';
    document.getElementById('capa05').style.display = '';
    document.getElementById('capa06').style.display = 'none';        

  }else if (tipo==="2"){
    //alert("tipo2");
    document.getElementById('capa01').style.display = '';
    document.getElementById('capa02').style.display = 'none';
    document.getElementById('capa03').style.display = '';
    document.getElementById('capa05').style.display = 'none';
    document.getElementById('capa06').style.display = '';        
  }else if (tipo==="4"){
    //alert("tipo4");
    document.getElementById('capa01').style.display = '';
    document.getElementById('capa02').style.display = 'none';
    document.getElementById('capa03').style.display = '';
    document.getElementById('capa05').style.display = 'none';
    document.getElementById('capa06').style.display = '';  

  }else{
    document.getElementById('capa01').style.display = 'none';
    document.getElementById('capa02').style.display = 'none';
    document.getElementById('capa03').style.display = 'none';
    document.getElementById('capa05').style.display = 'none';
    document.getElementById('capa06').style.display = 'none';  
  }
  ShowSelected();
}


//(*--------------------------------------------*)
function consultar(){
  $("#capa1").empty();
/* if ($("#txtfinicio").val()==""){                
    return;
  }

  if ($("#txtffin").val()==""){        
    return;
  }
*/

  if (($("#txtfinicio").val()!="") && ($("#txtffin").val()!="")){
    if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val()))
    {
      alert("La fecha inicial debe ser menor a la fecha final");    
      return;
    }
  }else{ 
    return;
  }
  
  let tipo = document.getElementById("cbotipomovimiento").value;


  if ($("#cbosutituto").val()!="NULL"){


     if (tipo==4)
         url="buscar_sustituciones.php";
     else if (tipo==2)
         url="buscar_cambio_cuadrilla.php";
      

     $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario").serialize(),
            dataType: "html",
            beforeSend: function(){
                  //imagen de carga
                  $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            error: function(){
                  alert("error petición ajax");
            },
            success: function(data){                                                    
                  $("#capa1").empty();
                  $("#capa1").append(data); 

            }
      });
    } 
}

//(*--------------------------------------------*)
function fecha_ini_mayor(fechaini, fechafin){
var dat = false;
  if(new Date(fechaini).getTime() >= new Date(fechafin).getTime())
  {
        dat= true;
  } 
  return dat;
}

//(*--------------------------------------------*)

function puesto_sustituto(){
   var sustituto=$("#cbosutituto").val();
   url="cargar_datos_sitt.php?sustituido="+sustituto+"&puesto=1";;
   $.ajax(url).done(function(data)
   {
   
      //alert(data);
      if (data!="0")
      {
        eval(data); //aquí vienen los datos de la pagina php        
      }
       
   }

  );
}



//(*--------------------------------------------*)
function puesto_sustituido(){
  //alert('paso2');
   var trabajador=$("#cbosustituido").val();
   url="cargar_datos_sitt.php?trabajador="+trabajador+"&puesto=2";
   $.ajax(url).done(function(data)
   {
      //alert(data);
      if (data!="0")
      {
        //alert(data);
        eval(data); //aquí vienen los datos de la pagina php
        confCondicionDisposicion('hddpuesto2',$("#hddpuesto2").val());
        confCondicionDisposicion('cboSH2',$("#cboSH2").val());
      }
       
   }

  );
}

//(*--------------------------------------------*)
function confCondicionDisposicion(elemento, disp){ 
  if (elemento=='hddpuesto2'){
     $('#cbopuesto2').selectpicker('val', disp);
     $('#cbopuesto2').selectpicker('refresh');
  }
  if (elemento=='cboSH2'){
     $('#cboSH2').selectpicker('val', disp);
     $('#cboSH2').selectpicker('refresh');
  }    
}

//(*--------------------------------------------*) 
function puesto(cedula,puesto){
   var trabajador=cedula;
   url="cargar_datos_sitt.php?trabajador="+trabajador+"&puesto="+puesto;
   $.ajax(url).done(function(data)
   {
      //alert(data);
      if (data!="0")
      {
        eval(data); //aquí vienen los datos de la pagina php   
      }
       
   }

  );
}

//(*--------------------------------------------*)
function cambio_cuadrilla(){

    var select = document.getElementById("cboSH2"); //El <select>

        value = select.value, //El valor seleccionado
         //alert("PASO"+value);
        text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
        console.log(text.substring(0,2));
        $("#hddcuadrilla").val(text.substring(0,2));
}


//(*--------------------------------------------*)
function ShowSelected()
{ 
/* Para obtener el texto */
var combo1 = document.getElementById("cbosustituido");
var selected1 = combo1.options[combo1.selectedIndex].text;
document.getElementById("hddnombresustituido").value=selected1;

var combo2 = document.getElementById("cbosutituto");
var selected2 = combo2.options[combo2.selectedIndex].text;
document.getElementById("hddnombresustituto").value=selected2;


var combo3 = document.getElementById("cbotipomovimiento");
var selected3 = combo3.options[combo3.selectedIndex].text;
document.getElementById("hddnombremovimiento").value=selected3;
}

//(*--------------------------------------------*)
function cambiar_puesto(){
  var select = document.getElementById("cbopuesto2"), //El <select>
  value = select.value, //El valor seleccionado
  text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
  $("#hddpuesto2").val(text.substring(0,4));
}

//(*--------------------------------------------*)
function fichar(){

  var motivo = document.getElementById('txtmotivo').value; 
  let tipo=document.getElementById("cbotipomovimiento").value;
  
  if ($("#cbotipomovimiento").val()=="NULL"){
    alert("Debe Seleccionar el Tipo de Movimiento");
    $("#cbotipomovimiento").focus;
    return;
  }

  if ($("#cbosutituto").val()=="NULL"){
    alert("Debe Seleccionar el trabajador que sera objeto del movimiento");    
    return;
  }

  //alert (tipo);
  //alert($("#cbocausa").val());
  if (tipo=="3" || tipo=="4"){
    if ($("#cbosustituido").val()=="NULL"){
       alert("Debe Seleccionar al Sustituido");    
       return;
    }  

    if ($("#cbocausa").val()=="" || $("#cbocausa").val()=="NULL"){
       alert("Debe seleccionar el Causal");   
       return;
    }

    if ($("#hddpuesto2").val()=="" || $("#txtpuesto2").val()==""){
      alert("El Sustituido Debe Tener Un Puesto");    
      return;
    }
  }
  
  if ($("#cbotipomovimiento").val()=="2"){
    if ($("#cboSH").val()=="NULL"){
      alert("Debe Seleccionar el Sistema Horario");    
      return;
    }
  }

  if ($("#txtfinicio").val()==""){
    alert("Debe colocar la fecha de Inicio");            
    return;
  }

  if ($("#txtffin").val()==""){
    alert("Debe colocar la fecha fin");    
    return;
  }

  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val()))
  {
    alert("La fecha inicial debe ser menor a la fecha final");    
    return;
  }

  /*if (fecha_ini_mayor($("#txtffin").val(), $("#hddhoy").val()))
  {
    alert("La fecha final debe ser menor a la fecha actual");    
    return;
  }*/

  if (motivo==""){
    alert("Debe ingresar el motivo del movimiento");   
    return;
  }

  ShowSelected();

$.ajax({
          type: "POST",
          url: "cargar_movimiento_bd_sitt.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                consultar();
                alert(data);                          
          }
    });

}
//(* ---------------------------------------------*)

$(document).ready(function(){	
	 consultar();
   document.getElementById('capa01').style.display = 'none';
   document.getElementById('capa02').style.display = 'none';
   document.getElementById('capa03').style.display = 'none';
   document.getElementById('capa05').style.display = 'none';
   document.getElementById('capa06').style.display = 'none'; 
 });

//(*----------------------------------------------*)
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
                <a class="navbar-brand" href="index.php">Control Asistencia - Movimiento</a>
            </div>
            <!-- /navba.r-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Movimiento Temporal de Personal</h1>
            </div>
                <!-- /.col-lg-12 -->
          </div>
            <!-- /.row -->
          <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  Datos del Movimiento
                </div>
                <div class="panel-body">
                  <div class="row">
                    <form role="form" id="formulario" name="formulario" method='post'> 
                        <div class="col-lg-6">    
                          <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                          <input  name='hddnivel' id='hddnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                          <input  name='hddhoy' id='hddhoy' type='hidden' value='<?php echo $fecha_actual; ?>'/>
                          <input  name='hddnombresustituto' id='hddnombresustituto' type='hidden' value=''/> 
                          <input  name='hddnombresustituido' id='hddnombresustituido' type='hidden' value=''/>
                          <input  name='hddnombremovimiento' id='hddnombremovimiento' type='hidden' value=''/>
                          <input  name='hddcuadrilla' id='hddcuadrilla' type='hidden' value=''/>
                          <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>
          				        <table width="50%" class="table table-striped" border="0">
                            <tr>
                              <th width="10%"><label>Tipo:</label></th>
                              <th width="10%" colspan="3">
                                <select name="cbotipomovimiento" id="cbotipomovimiento" onchange="consultar_tipo()" data-width="100%" data-size="5"class="selectpicker">
                                  <option selected value="NULL">Seleccione el tipo de movimiento</option>
                                  <?php echo $option5; ?>                         
                                </select>
                              </th>
                            </tr>  
                            <tr>
                              <th width="10%"><label>Trabajador:</label></th>
                              <th colspan="3" width="80%">
                                <select name="cbosutituto" onchange="consultar(); puesto(this.value,'1');" id="cbosutituto" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                  <option selected value="NULL">Seleccione el trabajador</option> 
                                  <?php echo $option1; ?>
                                </select>
                              </th>        
                            </tr>
                            <tr>
                              <th width="10%"><label>Puesto:</label></th>
                              <th width="90%">
                                <INPUT type="text" maxlength="10" size="10" id="txtpuesto1" name="txtpuesto1" value="" width="10" readonly class="form-control"/>
                                <input  name='hddpuesto1' id='hddpuesto1' type='hidden' value=''/> 
                              </th>
                              <th width="10%"><label>Sist. Hor.::</label></th>
                              <th width="90%">
                                <INPUT type="text" maxlength="10" size="10" id="cboSH1" name="cboSH1" value="" width="10" readonly class="form-control"/>
                              </th>
                            </tr>                                 
                          </table>
                          <div id="capa01">
                            <table width="50%" class="table table-striped" border="0">
                              <tr>
                                <th width="10%"><label>Sustituido:</label></th>
                                <th colspan="3" width="80%">
                                  <select name="cbosustituido" onchange="puesto_sustituido(this.value,'2')" id="cbosustituido" data-width="100%"  data-size="5"class="selectpicker" data-live-search="true">
                                    <option selected value="NULL">Seleccione el trabajador</option>
                                          <?php echo $option2; ?>
                                    </select>
                                </th>        
                              </tr>
                            </table>
                          </div>
                          <div id="capa02">
                            <table width="50%" class="table table-striped" border="0">
                              <tr>
    		                        <th width="10%"><label>Sist. Hor.:</label></th>
                                <th>
                                  <select name="cboSH" id="cboSH" onchange="cambio_cuadrilla(this.value);" data-width="80%" data-size="5" class="selectpicker" data-live-search="false">
                                      <option selected value="NULL">Seleccione Sistema Horario</option>
                                      <?php echo $optSH; ?>
                                    </select>
                                </th>
                                <th>&nbsp;</th>
                              </tr>
                            </table>
                          </div>
                          <div id="capa03">
                            <table width="50%" class="table table-striped" border="0">                  
                               <tr>
                                  <th width="10%"><label>Puesto:</label></th>
                                  <th width="50%">
                                    <!--<INPUT type="text" maxlength="10" size="10" id="txtpuesto2" name="txtpuesto2" value="" width="10" readonly class="form-control"/>-->
                                    <input name='hddpuesto2' id='hddpuesto2' type='hidden' value=''/> 
                                    <!-- ********************* AQUI ********************** -->
                                    <div id="cboPuestoSustituido">
                                      <select name="cbopuesto2" id="cbopuesto2" data-width="100%" data-size="5" onchange="cambiar_puesto(this.value);" class="selectpicker">
                                        <option value="NULL">Seleccione el Puesto</option>
                                        <?php echo $option4; ?>                 
                                      </select> 
                                    </div>                                    
                                  </th>
                                  <th width="10%"><label>Sist. Hor.:</label></th>
                                  <th width="30%">
                                    <select name="cboSH2" id="cboSH2" onchange="cambio_cuadrilla(this.value);" data-width="80%" data-size="5" class="selectpicker" data-live-search="false">
                                      <option selected value="NULL">Seleccione Sistema Horario</option>
                                      <?php echo $optSH; ?>
                                    </select>
                                    
                                  </th>
                                </tr>
                            </table>
                          </div>  
                          <div id="capa04">                          
                            <table width="50%" class="table table-striped" border="0">                            
                              <tr>
                                <th width="10%"><label>Fecha: Inicio</label></th>
                                <th width="30%"><INPUT onchange="consultar()" type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" value="<?php echo $fecha_actual; ?>" width="10" class="form-control"/></th>
                                <th width="10%"><label>&nbsp;Fin</label></th>
                                <th width="25%"><INPUT type="date" onchange="consultar()" maxlength="10" size="10" id="txtffin" name="txtffin" value="<?php echo $fecha_a; ?>" width="10" class="form-control"/></th>
                              </tr>
                              <tr>
                                <th width="10%"><label>Causal:</label></th>
                                <th width="25%"><select name="cbocausa" id="cbocausa" data-width="100%" data-size="5"class="selectpicker">
                                        <option selected value="NULL">Seleccione la Causa</option>
                                        <?php echo $option3; ?>                         
                                      </select>
                                </th>      
                              </tr>
                            </table>
                          </div>
                          <div id="capa08">
                            <table width="50%" class="table table-striped" border="0">                 
                              <tr>
                                <th width="10%"><label>Motivo:</label></th>
                                <th width="90%" colspan="3">
                                    <textarea name="txtmotivo" id="txtmotivo" placeholder="Motivo del movimiento" rows="2" cols="40%"></textarea>
                                </th>
                              </tr>                  
    		                   </table>
                          </div>  
                          <div id="capa05">
                            <table width="50%" class="table table-striped" border="0">  
                              <tr>
                                  <th colspan="4" width="10%" style="text-align: center">
                                    <?php 
                                     if ((permiso_usuario(Conex_Contancia_pgsql(), 'CONSULTAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                                     { ?>
                                    <INPUT id="cmdConsultar" type="button" value="Consultar"  class="btn btn-success" onclick="consultar();"/>
                                    <?php }
                                    if ((permiso_usuario(Conex_Contancia_pgsql(), 'REALIZAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                                     { ?> 
                                    <INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="fichar();"/>
                                    
                                    <?php } ?> 
                                </th>
                              </tr>                  
                            </table>
                          </div>
                          <div id="capa06">
                            <table width="50%" class="table table-striped" border="0">  
                              <tr>
                                  <th style="text-align: center"><label>Cambiar Esperanza:</label>
                                    <input class="form-check-input" type="checkbox" id="cambiarEsperanza" name="cambiarEsperanza" checked>&nbsp;&nbsp;&nbsp;
                                    <INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="fichar();"/>
                                  </th>  
                              </tr>                  
                            </table>
                          </div>
                        </div>                               
                        <p>&nbsp;</p> 
                        <div id="capa1" class="col-lg-6">
                                     
                        </div>
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

    <script src="../bootstrap/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>

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
