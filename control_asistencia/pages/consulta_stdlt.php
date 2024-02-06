<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');

  $option = llenar_combo_trabajadores('ct');
  $link=Conex_rrhh_pgsql();
  $query="SELECT id_calendario, inicio, semanas, mes, anio, (inicio || ' al ' || fin) as periodo FROM periodos_nomina where abierto=true order by anio desc, mes desc";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optPeriodo='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optPeriodo.= "<option value='". $fila['inicio'].'|'.$fila['semanas']."'>" ;
        $optPeriodo.= $fila['periodo']. "</option>";
    }
  }
  

  $query="SELECT distinct sistema_horario  FROM trabajadores_grales WHERE sit_trabajador=1 order by 1";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optSH='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optSH.= "<option value='". $fila['sistema_horario']."'>" ;
        $optSH.= $fila['sistema_horario']. "</option>";
    }
  }

  $query="SELECT idunidad, descripcion_unidad  FROM unidades order by 1";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optCCosto='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optCCosto.= "<option value='". $fila['idunidad']."'>" ;
        $optCCosto.= $fila['idunidad'].'-'.$fila['descripcion_unidad']. "</option>";
    }
  }

$fecha_a =date("Y-m-d");
$fecha_actual = date("Y-m-d",strtotime($fecha_a."- 1 days"));
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Horas Extras y DLT</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">    

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

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
    $.ajax({
          type: "POST",
          url: "consulta_stdlt_online.php",
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
function ver_fichadas(trabajador, fecha, button){
    $.ajax({
          type: "POST",
          url: "consulta_stdlt_online.php?trabajador="+trabajador+"&nombre="+nombre,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#fichadas1").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa2").empty();
                $("#capa2").append(data);          
          }
    });
}
//(*--------------------------------------------*)
function exportar_excel(){
    var desde=$("#txtfinicio").val();
    var hasta=$("#txtffin").val();
    if ($("#cbotrabajador").val()!="NULL")
       var nombre_archivo="por_el_trabajador_"+$("#txtffin").text();
    else 
      var nombre_archivo="";
    var namexls="Acumulado_de_STDLT_"+nombre_archivo+"_desde"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Horas extras y dia libre trabajados. '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

       $.ajax({
              type: "POST",
              url: "consulta_stdlt_online.php",
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
function editCell(trabajador, fecha, ndiv){  
  
    var t = $('#dataTables-example').DataTable();   
    
    //alert($('#hddDatosCell').val()+ '/' +$('#hddCellX').val() +','+ $('#hddCellY').val());
             
    var cell = t.cell( $('#hddCellY').val() , $('#hddCellX').val() );

     var param= trabajador+", '"+fecha+"', '"+ndiv+"'";
    var boton = '<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" onclick="ver_fichadas('+param+')" data-target="#exampleModalCenter">' + $('#hddDatosCell').val() + '</button>'
                  
    cell.data( boton ).draw();
            
}



</script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header"> 
                <img width="150px" src="../images/logotransparente.png">               
                <a class="navbar-brand" href="index.php">Sist. Control de Asistencia                  
                </a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Acumulado de Horas extras</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta de Reporte de la Horas extras y DLT 
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
                                      <div class="col-lg-12">

                                      <table width="90%">    
                                        <tr>
                                          <td><label>Fecha Inicio:</label></td>
                                          <td> &nbsp;</td>
                                          <td><label>Fecha Fin:</label></td>
                                          <td> &nbsp;</td>
                                          <td><label>Trabajador:</label></td>
                                          <!--<td> &nbsp;</td>
                                           <td><label>Cento de Costo:</label></td>-->
                                          <td> &nbsp;</td>                                        
                                          <td><label>Tipo de Reporte</label></td>
                                          <td> &nbsp;</td>
                                          <td> &nbsp;</td>
                                        </tr>   
                                        <tr> 
                                          <td><INPUT type="date" id="txtfinicio" maxlength="80" name="txtfinicio" value="<?php echo $fecha_actual; ?>" width="100%" class="form-control"/></td>
                                          <td> &nbsp;</td>
                                          <td><INPUT type="date" id="txtffin" maxlength="50" name="txtffin" value="<?php echo $fecha_a; ?>"  width="100%" class="form-control"/></td>
                                          <td> &nbsp;</td>
                                          <td><select name="cbotrabajador" id="cbotrabajador" data-width="80%" data-size="5" class="selectpicker" data-live-search="true">
                                                <option selected value="NULL">Todos los trabajadores</option>
                                                <?php echo $option; ?>
                                              </select>             
                                          </td>
                                        <!--  
                                          <td> &nbsp;</td>
                                          <td> <select class="selectpicker" name="cboccosto" id="cboccosto" class="form-control" >
                                                <option selected value="NULL">Todos los CCosto</option>
                                                <?php echo $option1; ?>
                                                </select>
                                          </td>
                                        -->
                                          <td> &nbsp;</td>
                                          <td> 
                                              <select name="tiporeporte" id="tiporeporte" class="form-control" >
                                                   <option value="1">Detallado por Trabajador</option>
                                                   <option value="2">Resumido por Trabajador</option>
                                              </select>
                                          </td> 
                                          <td> &nbsp;</td>
                                          <td width="10%" aling="center"><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/></td>
                                          <td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a> </td>
                                        </tr>    
                                      </table>
                                      </div>
                                      <p>&nbsp;</p>
                                      <input type="hidden" name="hddExisteDataTable" id="hddExisteDataTable">

                                      <div id="capa1" class="col-lg-12"> 
                                        <table id="dataTables-example" >
                                            <thead>
                                              <tr>
                                                <th></th>
                                              </tr>  
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td></td>
                                              </tr>  
                                            </tbody>
                                        </table>     
                                      </div>

                                      <!-- Modal -->
                                      <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" style="width:80%; left: 15%;" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" style="width:80%;" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h4 class="modal-title" id="exampleModalLongTitle">Hoja de Tiempo</h4>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <input type="hidden" id="hddCellX" name="hddCellX">
                                            <input type="hidden" id="hddCellY" name="hddCellY">
                                            <div id="capa2" class="modal-body">
                                              <input type="hidden" name="hddDatosCell" id="hddDatosCell">
                                              
                                              ...
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                              
                                            </div>
                                          </div>
                                        </div>
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    function dataTable() {
        $('#dataTables-example').DataTable({
            responsive: true,
             "order": [[ 0, "asc" ]],
             "scrollX": true,
             "scrollY": "700px",
             "scrollCollapse": true,
        });

      var table = $('#dataTables-example').DataTable();
        $('#dataTables-example').on( 'click', 'td', function () {
          //var cell = table.cell( this );
          $('#hddCellX').val(table.cell( this ).index().columnVisible);
          $('#hddCellY').val(table.cell( this ).index().row);
          //alert($('#hddCellX').val() + '-'+ $('#hddCellY').val())
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
?>
