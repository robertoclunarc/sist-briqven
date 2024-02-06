<?php  
 session_start();
 if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  require_once("../BD/conexion.php");
  require_once('funciones_var.php');
   
  $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";
  $link=Conex_rrhh_pgsql();
  $link2=Conex_Contancia_pgsql();
  $result1 = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result1);
  if($numReg>0){
        $option1='';
        while ($fila=ejecutar_fetch_array($result1)) 
        {
            $option1.= "<option value='". $fila['trabajador']."'>" ;
            $option1.= $fila['trabajador']." - ".$fila['nombre']. "</option>";
        }
  }  

  $query="SELECT descripcion_unidad, centro_costo FROM unidades Order By descripcion_unidad;";
  $result2 = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result2);
  if($numReg>0){
        $option2='';
        while ($fila=ejecutar_fetch_array($result2)) 
        {
            $option2.= "<option value='". $fila['centro_costo']."'>" ;
            $option2.= $fila['centro_costo']." - ".$fila['descripcion_unidad']. "</option>";
        }
  }


  $qryTipoMedida="SELECT id_tipo_suspensiones, tipo_suspension FROM public.tipo_suspensiones;";
  $result = ejecutar_query($link2, $qryTipoMedida) or die("Error en la Consulta SQL: ".$qryTipoMedida);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optionTipoMedida='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optionTipoMedida.= "<option value='". $fila['id_tipo_suspensiones']."'" ;
        $optionTipoMedida.= ">".$fila['tipo_suspension']. "</option>";
    }
  }
  pg_free_result($result1);
  pg_free_result($result2);

  pg_close($link);

$fecha_a =date("Y-m-d");
$fecha_actual = date("Y-m-d",strtotime($fecha_a."- 1 days"));
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=USQL_Latin1_General_CP1_CI_ASTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta lang="es">
  


    <title>Ctrl. Asistencia</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">     

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">    

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">


    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">   
    
   <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script  language="javascript">
//(*--------------------------------------------*)

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
function consultar(){
  document.getElementById("hddoper").value="consultar_SD_online.php";
  document.getElementById("conFoto").value=true;
    $.ajax({
          type: "POST",
          url: "consultar_SD_online.php",
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
                dataTable();         
          }
    });
}
//(*--------------------------------------------*)
function porcentajes(){

  document.getElementById("hddoper").value="fichada_ccure_online_porc.php";

    $.ajax({
          type: "POST",
          url: "fichada_ccure_online_porc.php",
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
function agrupar(){

  document.getElementById("hddoper").value="fichada_ccure_online_agr.php";

    $.ajax({
          type: "POST",
          url: "fichada_ccure_online_agr.php",
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
function exportar_excel(){

var dir=$("#hddoper").val();    
    
    var desde=$("#txtfinicio").val();
    var hasta=$("#txtffin").val();
    var namexls="Medidas_disciplinarias_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Reporte de Medidas Disciplinarias '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';


       $.ajax({
              type: "POST",
              url: dir,
              data: $("#formulario").serialize(),
              dataType: "html",
              success: function(data){
                    $("#capa1").empty();
                    $("#capa1").append(data);
                    var link = document.createElement('a');
                    document.body.appendChild(link); // Firefox requires the link to be in the body
                    link.download = namexls;
                    link.href = 'data:application/vnd.ms-excel,' + escape(inpt+data);
                    link.click();
                    document.body.removeChild(link);                
              }
        });   
}
//(*--------------------------------------------*)
$(document).ready(function(){   
 
 //$('#exampleModalCenter').modal('show');
   
});
//(*--------------------------------------------*)
</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="150px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->       <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation" style="margin-bottom: 0">-->
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Reporte de Asistencia</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Reporte de Medidas Disciplinarias (SD)</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta del Reporte de Medidas Disciplinarias aplicadas
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
                                      <INPUT type="hidden" id="hddoper" name="hddoper" value="fichada_ccure_online.php"/>
                                      <INPUT type="hidden" id="conFoto" name="conFoto" value="false"/>
                                        <div class="col-lg-12">

                                            <table width="90%">    
                                                <tr>
                                                    <td width="10%">

                                                        <label>Fecha Inicio:</label></td>
                                                    <td width="1%"> &nbsp;</td>
                                                    <td width="10%"><label>Fecha Fin:</label></td>
                                                    <td width="1%"> &nbsp;</td>
                                                    <td width="28%"><label>Trabajador:</label></td>
                                                    <td width="1%"> &nbsp;</td>
                                                    <td width="15%"> <label>Tipo de Medida</label></td>
                                                    <td width="5%"> &nbsp;</td>
                                                    <td width="5%"> &nbsp;</td>                                                    
                                                    <td width="5%"> &nbsp;</td>
                                                    
                                                </tr>   
                                                    <tr> 
                                                    <td><INPUT type="date" id="txtfinicio" maxlength="80" name="txtfinicio" value="<?php echo $fecha_a; ?>" width="100%" class="form-control"/></td>
                                                    <td> &nbsp;</td>
                                                    <td><INPUT type="date" id="txtffin" maxlength="50" name="txtffin" value="<?php echo $fecha_a; ?>"  width="100%" class="form-control"/></td>
                                                    <td> &nbsp;</td>
                                                    <td><select name="cbotrabajador" id="cbotrabajador" data-width="80%" data-size="5"class="selectpicker" data-live-search="true">
                                                            <option selected value="NULL">Todos los trabajadores</option>
                                                            <?php echo $option1; ?>
                                                        </select>             
                                                    </td>
                                                    <td> &nbsp;</td>
                                                    <td><select name="cbotmedida" id="cbotmedida" data-width="80%" data-size="5"class="selectpicker" data-live-search="true">
                                                            <option selected value="NULL">Todos las Medidas</option>
                                                            <?php echo $optionTipoMedida; ?>
                                                        </select>             
                                                    </td>
                                                    <td aling="center">
                                                            <INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/>
                                                    </td>
                                                                                                      
                                                    <td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a> 
                                                    </td> 
                                                </tr>    
                                            </table>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div id="capa1" class="col-lg-12"> 
                                          <table id="dataTables-example" >
                                            <thead>
                                              <tr>
                                                <th>
                                                </th>
                                              </tr>  
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td></td>
                                              </tr>  
                                            </tbody>
                                          </table>
                                        </div>

                                        <!-- Inicio Modal -->
                                        <div class="modal fade bd-example-modal-lg"  width="70%" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
                                          <div class="modal-dialog modal-lg" role="document">
                                            <div style="background:transparent url('images/banner.JPG') no-repeat center center /cover; height: 500px" class="modal-content">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                              <h1 class="form-signin-heading">
                                                <p align="center"  style="margin-top:150px; margin-bottom: 160px; color: #e9e1e2; background: rgb(27,93,204);
                                                              background: radial-gradient(circle, rgba(27,93,204,1) 0%, rgba(200,209,237,1) 96%);" ><strong>Bienvenidos al<br>Sistema de Control de Asistencia</strong>
                                                </p>
                                                <div style="text-align: center;">
                                                  <a style="text-decoration:none; color:#e9e1e2; font-size: 14px;" href="logIn.php"> <strong>>>Click Aqu&iacute; Si Tienes LogIn<< </strong></a>
                                                </div>
                                              </h1>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- fin Modal -->

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
    

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script>
    function dataTable() {
        $('#dataTables-example').DataTable({
             responsive: true,
             "order": [[ 1, "desc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": true,
             "searching": false,
        });      
        
    };
    </script>

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