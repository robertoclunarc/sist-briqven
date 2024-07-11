<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();

  
  $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";
  
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option1='';
    $option2='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $option1.= "<option value='". $fila['trabajador']."'" ;
        $option1.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";

        $option2.= "<option value='". $fila['trabajador']."'" ;
        $option2.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";

    }
  } 
  pg_close($link);


  $fecha_actual = date("Y-m-d");
  $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

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

    <title>Trabajadores en situación especial</title>
    
    <!-- Bootstrap Core CSS -->
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
$(document).ready(function(){
    buscar_trabajadores();
 });

//(*--------------------------------------------*)
function buscar_trabajadores(){
    $.ajax({
          type: "POST",
          url: "trabajadores_casos_excepcionales_consultabd.php",
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
               console.log(data);                        
                $("#capa1").empty();
                $("#capa1").append(data);
          }
    });
}

//(*--------------------------------------------*)
function eliminar(trabajador){

  $('#modalConfirmacion').modal('show');
  //alert (trabajador);
  
  // Manejar la confirmación
  $('#btnConfirmar').click(() => {
      $("#operacion").val('DELETE');
      $("#cbotrabajador").val(trabajador); 
      $. ajax({
              type: "POST",
              url: "trabajadores_casos_excepcionales_operacionbd.php",
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
              console.log(data);                                                 
                    $("#capa2").empty();
                    $("#capa1").empty();
                    buscar_trabajadores();
                    $("#capa2").html(data);
              }
        });
    $('#modalConfirmacion').modal('hide');
  });  

  /*$("#operacion").val('DELETE');
  $("#cbotrabajador").val(trabajador); 
  $. ajax({
          type: "POST",
          url: "trabajadores_casos_excepcionales_operacionbd.php",
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
          console.log(data);                                                 
                $("#capa2").empty();
                $("#capa1").empty();
                buscar_trabajadores();
                $("#capa2").html(data);
          }
    });*/
}

//(*--------------------------------------------*)
function agregar(){
  $("#operacion").val('INSERT');

  /*if ($("#cbosupervisor").val()=="NULL"){
    alert("Debe Seleccionar el Supervisor ");    
    return;
 }*/

  if ($("#cbotrabajador").val()=="NULL"){
    alert("Debe Seleccionar un Trabajador ");     
    return;
  }  

$.ajax({
          type: "POST",
          url: "trabajadores_casos_excepcionales_operacionbd.php",
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
                $("#capa2").empty();
                $("#capa1").empty();
                buscar_trabajadores();
                $("#capa2").html(data);
          }
    });
}

//(*--------------------------------------------*)
function cambio(){
  const btnAgregar = document.getElementById('Agregar');
  /*if (($("#cbosupervisor").val()!="NULL") and ($("#cbosupervisado").val()=="NULL")){
    btnAgregar.disabled = false;
   }else{
    tnAgregar.disabled = true;
   }
*/
   
}
//(*--------------------------------------------*)
function diastranscurridos(fechaIni, fechaFin){
var fechaInicio = new Date(fechaIni).getTime();
var fechaFin    = new Date(fechaFin).getTime();

var diff = (fechaFin - fechaInicio)/(1000*60*60*24);

return diff;


}

//(* ---------------------------------------------*)

$(document).ready(function(){	
	//consultar();
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
                <a class="navbar-brand" href="index.php">Sist. Control Asistencia</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Trabajadores en casos excepcionales </h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Trabajador
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-12">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       
                                       <input  name='operacion' id='operacion' type='hidden' value=''/> 

                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

                      				        <table width="50%" class="table table-striped" border="0">
                                                <tr>
                                                    <th width="10%"><label>Trabajador:</label></th>
                                                    <th colspan="3" width="80%"><select name="cbotrabajador" id="cbotrabajador" onchange="cambio();" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                                            <option selected value="NULL">Seleccione el trabajador</option>
                                                                  <?php echo $option2; ?>
                                                            </select>
                                                    </th>
                                                    <th align="center">
                                                      <div align="center"><INPUT id="cmdGuardar" type="button" value="Agregar"  class="btn btn-success" onclick="agregar();"/></div>
                                                    </th>
                                                    <th colspan="3">
                                                      <div id="capa2"></div>
                                                    </th>                                                    
                                                </tr> 
                                                <tr>
                                                  <th><label>Fecha: Inicio</label></th>
                                                  <th><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" value="<?php echo $fecha_actual; ?>" width="10" class="form-control"/></th>
                                                  <th><label>&nbsp;Fin</label></th>
                                                  <th><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" value="<?php echo $fecha_a; ?>" width="10" class="form-control"/></th>
                                                  <th><label>Motivo:</label></th>
                                                  <th>
                                                    <select name="motivo" id="motivo" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                                      <option selected value="NULL">Seleccione el motivo</option>
                                                      <option value="No Requerido">No Requerido</option>
                                                      <option value="Suspendido">Suspendido</option>
                                                    </select>
                                                  </th>
                                                  <th><label>Status:</label></th>
                                                  <th>
                                                    <select name="status" id="status" data-width="100%" data-size="5"class="selectpicker" data-live-search="true" onchange="buscar_trabajadores();">
                                                      <option selected value="NULL">Seleccione el status</option>
                                                      <option value="ACTIVO">ACTIVO</option>
                                                      <option value="INACTIVO">INACTIVO</option>
                                                    </select>
                                                  </th>                                          
                                                </tr>      
                                                <tr>
                                                  <th><label>Observación:</label></th>
                                                  <th colspan="8"><INPUT type="text" size="100%" id="observacion" name="observacion"  class="form-control"/></th>
                                                </tr> 
                      					      </table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                 
                                  <div id="capa1" class="col-lg-12">
                                       
                                  </div>
                                  <div id="capa0" class="col-lg-12">
                                      
                                  </div>
                                     
                                </form>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                        <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar Eliminación</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                ¿Esta seguro de eliminar este trabajador de la lista de Casos Excepcionales?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" id="btnConfirmar">Si</button>
                              </div>
                            </div>
                          </div>
                        </div>
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

