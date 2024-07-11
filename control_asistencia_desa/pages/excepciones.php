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
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $option.= "<option value='". $fila['trabajador']."'" ;
        $option.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";
    }
  } 
  pg_close($link);
  $fecha_actual = date("Y-m-d");
  $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

  $option1= "" ;
  $qry="select DISTINCT CICLO_LABORAL from dbo.adam_programacion_vacaciones order by 1 Desc";
  $cn=Conectarse_sitt();        
  $stmt1 = $cn->query($qry);
  
  while($fila = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
  {
     $option1.= "<option value='". $fila['CICLO_LABORAL']."'";
     $option1.= ">". $fila['CICLO_LABORAL']. "</option>";
  }
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

    <title>Excepciones</title>
    
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
//(*--------------------------------------------*)
function consultar(){
    $.ajax({
          type: "POST",
          url: "excepciones_consultarbd.php",
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
                $("#txtfinicio").val($("#hddfechainivac").val());
                $("#txtffin").val($("#hddfechafinvac").val());
          }
    });
}

//(*--------------------------------------------*)
function fichar(){

if ($("#cbooperacion").val()=="NULL"){
    alert("Debe Seleccionar una Operacion");    
    return;
}
else
{  

  if ($("#cbotrabajador").val()=="NULL"){
    alert("Debe Seleccionar un Trabajador");    
    return;
  }

  if ($("#cbooperacion").val()=="UPDATE" || $("#cbooperacion").val()=="DELETE" || $("#cbooperacion").val()=="INSERT")
  {

      if (($("#cbooperacion").val()=="UPDATE" || $("#cbooperacion").val()=="INSERT") && $("#txtfinicio").val()==""){
        alert("Debe colocar la fecha de Inicio");            
        return;
      }

      if (($("#cbooperacion").val()=="UPDATE" || $("#cbooperacion").val()=="INSERT") && $("#txtffin").val()==""){
        alert("Debe colocar la fecha fin");    
        return;
      }      

      if (($("#cbooperacion").val()=="UPDATE" || $("#cbooperacion").val()=="INSERT") && $("#txtMotivo").val()=="NULL"){
        alert("Debe Indicar el Motivo");    
        return;
      }  

      if (diastranscurridos<=0){
        alert("Debe Corregir las fechas");    
        return;
      }      
  }
}


$.ajax({
          type: "POST",
          url: "excepciones_operacionbd.php",
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
                consultar();
                $("#capa2").html(data);
                                          
          }
    });
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
                <a class="navbar-brand" href="index.php">Excepciones</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Excepciones</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Autorizacion a Personal para Fichar
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 

                                        <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>

				        <table width="50%" class="table table-striped" border="0">
                  <tr>
                      <th width="10%"><label>Trabajador:</label></th>
                      <th colspan="3" width="80%"><select name="cbotrabajador" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                              <option selected value="NULL">Seleccione el trabajador</option>
                                    <?php echo $option; ?>
                              </select>
                      </th>        
                  </tr>
                  
                  <tr>
                        <th width="10%"><label>Fecha: Inicio</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange="" value="" width="10" class="form-control"/></th>
                        <th width="10%"><label>&nbsp;Fin</label></th>
                        <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" value="" width="10" class="form-control"/></th>
                  </tr>
                  <tr>                        
                        <th><label>Motivo</label></th>
                        <th colspan="3"><INPUT type="text" maxlength="50" size="10" id="txtMotivo" name="txtMotivo" value="" width="10" class="form-control"/>
                        </th>                        
                  </tr>
                  <tr>
                        <th width="10%"><label>&nbsp;</label></th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%"><label>Operacion:</label></th>
                        <th width="10%"><select name="cbooperacion" id="cbooperacion" data-width="100%" data-size="5"class="selectpicker">
                              <option selected value="NULL">Seleccione la Operacion</option>
                              <option  value="INSERT">Registrar</option>
                              <option  value="UPDATE">Actualizar</option>
                              <option  value="DELETE">Eliminar</option>                              
                              </select></th>
                  </tr>
                  <tr>
                        
                        <th ><INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="fichar();"/></th>
                        <th colspan="3"><div id="capa2"></th>
                        
                                         
                  </tr>                  
					      </table>						
                                  </div>
                                  <p>&nbsp;</p> 
                                      <div id="capa1" class="col-lg-12">
                                       
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
