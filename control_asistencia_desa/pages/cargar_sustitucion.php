<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();

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
  pg_close($link);
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

    <title>Carga Sustitucion</title>
    
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
function consultar(){
  if ($("#txtfinicio").val()==""){                
    return;
  }

  if ($("#txtffin").val()==""){        
    return;
  }


  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val()))
  {
    alert("La fecha inicial debe ser menor a la fecha final");    
    return;
  }

    $.ajax({
          type: "POST",
          url: "buscar_sustituciones.php",
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

function puesto_sustituido(){
   var sustituido=$("#cbosustituido").val();
   url="cargar_datos_sitt.php?sustituido="+sustituido;
   $.ajax(url).done(function(data)
   {
   
      //alert(data);
      if (data!="0")
      {
        eval(data); //aquí vienen los datos de la pagina php        
        //$("#cbotipo_personal").attr("disabled","disabled");        
        //var foto="../intranet/briqven/sites/all/modules/cumples/img/fotcarmat_new/"+$("#hddcedula").val()+".bmp";
        //$("#cbodireccion").attr("disabled","disabled");   
        /*if (existeUrl(foto))
            mostrar("foto", foto);
        else
            ocultar("foto");
          */             
      }
       
   }

  );
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
}

//(*--------------------------------------------*)
function fichar(){


  if ($("#cbosutituto").val()=="NULL"){
    alert("Debe Seleccionar el Susutituto");    
    return;
  }

  if ($("#cbosustituido").val()=="NULL"){
    alert("Debe Seleccionar al Sustituido");    
    return;
  }  

  if ($("#txtfinicio").val()==""){
    alert("Debe colocar la fecha de Inicio");            
    return;
  }

  if ($("#txtffin").val()==""){
    alert("Debe colocar la fecha fin");    
    return;
  }

  if ($("#cbocausa").val()==""){
    alert("Debe colocar el Causal");    
    return;
  }

  if ($("#hddpuesto").val()=="" || $("#txtpuesto").val()==""){
    alert("El Sustituido Debe Tener Un Puesto");    
    return;
  }

  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val()))
  {
    alert("La fecha inicial debe ser menor a la fecha final");    
    return;
  }

  if (fecha_ini_mayor($("#txtffin").val(), $("#hddhoy").val()))
  {
    alert("La fecha final debe ser menor a la fecha actual");    
    return;
  }

  ShowSelected();

$.ajax({
          type: "POST",
          url: "cargar_sust_bd_sitt.php",
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
                <a class="navbar-brand" href="index.php">Control Asistencia - Sustituciones</a>
            </div>
            <!-- /navba.r-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Sustituciones</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos de la Sustitucion
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

                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="50%" class="table table-striped" border="0">
                  <tr>
                      <th width="10%"><label>Sustituto:</label></th>
                      <th colspan="3" width="80%"><select name="cbosutituto" onchange="consultar()" id="cbosutituto" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option1; ?>
                              </select>
                      </th>        
                  </tr>
                  <tr>
                      <th width="10%"><label>Sustituido:</label></th>
                      <th colspan="3" width="80%"><select name="cbosustituido" onchange="puesto_sustituido()" id="cbosustituido" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option2; ?>
                              </select>
                      </th>        
                  </tr>
                
                  <tr>
                        <th width="10%"><label>Fecha: Inicio</label></th>
                        <th width="10%"><INPUT onchange="consultar()" type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" value="<?php echo $fecha_actual; ?>" width="10" class="form-control"/></th>
                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" onchange="consultar()" maxlength="10" size="10" id="txtffin" name="txtffin" value="<?php echo $fecha_a; ?>" width="10" class="form-control"/></th>
                  </tr>
                  <tr>
                        <th width="10%"><label>Puesto:</label></th>
                        <th width="10%"><INPUT type="text" maxlength="10" size="10" id="txtpuesto" name="txtpuesto" value="" width="10" readonly class="form-control"/>
                        <input  name='hddpuesto' id='hddpuesto' type='hidden' value=''/> 
                        </th>
                        <th width="10%"><label>Causal:</label></th>
                        <th width="10%"><select name="cbocausa" id="cbocausa" data-width="100%" data-size="5"class="selectpicker">
                              <option selected value="NULL">Seleccione la Causa</option>
                                  <?php echo $option3; ?>                         
                              </select></th>
                  </tr>
                  <tr>
                        
                        <th width="10%"><INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="fichar();"/></th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                                         
                  </tr>                  
					      </table>						
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
