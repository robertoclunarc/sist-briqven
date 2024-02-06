<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');

  $option = llenar_combo_trabajadores('ct');
  $link=Conex_rrhh_pgsql();
  $link2=Conex_control_asistencia();
  $query="SELECT * FROM accesos order by descripcion asc";
  $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $optAcceso='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $optAcceso.= "<option value='". $fila['idacceso']."'>" ;
        $optAcceso.= $fila['descripcion']. "</option>";
    }
  }
  
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

    <title>Registrar Fichadas</title>

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
function listar()
{          
  /*if ($('#cbotipooperacion').val()=='NULL'){
    alert ('Ingrese el Tipo de Operacion');
    $('#cbotipooperacion').focus();
    return;
  }  
  */ 
//alert('paso');
  $.ajax({
        type: "POST",
        url: "consulta_usuario_online.php",
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
function exportar_excel(){
  if ($('#cbotipooperacion').val()=='NULL'){
    alert ('Ingrese el Tipo de Operacion');
    $('#cbotipooperacion').focus();
    return;
  }
    var desde=$("#txtfinicio").val();
    var hasta=$("#txtffin").val();
    var SP = $('#cbotipooperacion').val();  
    var listado = '';
    if (SP=='1') 
        listado = 'HORAS EXTRAS';
    if (SP=='2') 
      listado = 'DLT';
    
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2); 
    var namexls="Registro_"+listado+"_cargadas_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'LISTADO DE '+listado+': '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';
   $.ajax({
          type: "POST",
          url: "consultar_stdlt_online.php",
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
function ver_fichadas(trabajador, fecha, button){
    
    $.ajax({
          type: "POST",
          url: "permisos_asignados.php?trabajador="+trabajador+"&fecha="+fecha+"&btn="+button,
          data: $("#formulario").serialize(),
          async: false,
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("<p align='center'><img src='images/loading.gif' /></p>");
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
function updateUser(parametros){
    console.log (parametros);

    alert("paso: "+parametros);
    /*$.ajax({
          type: "POST",
          url: "usuario_modificar.php?trabajador="+trabajador,
          data: $("#formulario").serialize(),
          async: false,
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){  
                console.log(data);   
const modal = document.querySelector('#modificarUser');

  // Convierte el elemento DOM en un objeto modal
  //const modalObj = bootstrap.Modal(modal);

  // Muestra el modal
  modal.show();                                                               
                $("#capa2").empty();
                $("#capa2").append(data);
                              
          }
    });
    */
}

//(*--------------------------------------------*)
function activar_sp(trabajador, fecha, ndiv, msj){
    
    if (ndiv=='8'){
      $('#Inicio_ST1').val('00:00');
      $('#Fin_ST1').val('00:00');
    }

    //alert(trabajador+', '+fecha+', '+ndiv+', '+msj);
    var botton = $("#sp"+ndiv).html();
    $.ajax({
          type: "POST",
          url: "fichadas_db.php?SP="+ndiv,
          data: $("#formulario").serialize(),
          async: false,
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#sp"+ndiv).empty();
                $("#sp"+ndiv).html("<p align='center'><img width='25' src='images/loading.gif' /></p>");
                //$("#mensaje").html('<p align='center'><img width='25' src='images/loading.gif' /></p>');
          },
          error: function(){
                $("#mensaje").append('<div class="alert alert-danger">Error petición ajax </div>');
          },
          success: function(data){               
                //alert(data);
                if (data==1){
                  ver_fichadas(trabajador, fecha, 'C');
                  if (($('#hddDatosCell').val()!='') && ($('#hddCellY').val()!='')){
                    editCell(trabajador, fecha, ndiv);                    
                  }
                  setTimeout(() => { 

                    $("#mensaje").append('<div class="alert alert-success">Operacion Exitosa en '+msj+' </div>'); 
                  }, 2000);
                }else{
                    //$("#mensaje").remove();
                    $("#mensaje").append('<div class="alert alert-danger">'+data+'</div>');
                    $("#sp"+ndiv).html(botton);
                }
          }
    });
    
}

//(*--------------------------------------------*)
function editCell(trabajador, fecha, ndiv){  
  
    var t = $('#dataTables-example').DataTable();   
    
    //alert($('#hddDatosCell').val()+ '/' +$('#hddCellX').val() +','+ $('#hddCellY').val());
             
    var cell = t.cell( $('#hddCellY').val() , $('#hddCellX').val() );

     var param= trabajador+", '"+fecha+"', '"+ndiv+"'";
    //var boton = '<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" onclick="ver_fichadas('+param+')" data-target="#exampleModalCenter">' + $('#hddDatosCell').val() + '</button>'
    var boton = '<h6><button type="button" data-toggle="modal" onclick="ver_fichadas('+param+')" data-target="#exampleModalCenter">'+trabajador+'</button><h6>'
                  
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
                    <h4 class="page-header">Consultar Usuarios de Sistema</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reporte de Usuarios con Acceso al sistema
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
                                      <div class="col-lg-12">

                                      <table width="90%">    
                                        <tr>
                                          <td><label>Status:</label></td>
                                          <td> &nbsp;</td>
                                           <td><label>Nivel:</label></td>
                                           <td> &nbsp;</td>
                                           <td><label>Trabajador:</label></td>
                                           <td> &nbsp;</td>
                                           <td><label>Acceso:</label></td>                                           
                                           <td> &nbsp;</td>
                                           <td> &nbsp;</td>
                                        </tr>   
                                         <tr> 
                                           <td><select name="status" id="status" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
                                               <option selected value="NULL">Seleccione ...</option>
                                               <option value="ACTIVO">Activo</option>
                                               <option value="INACTIVO">Inactivo</option>
                                               </select></td>
                                           <td> &nbsp;</td>
                                           <td><select name="nivel" id="nivel" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
                                               <option selected value="NULL">Seleccione ...</option>
                                               <option value="1">1 - Administrador</option>
                                               <option value="2">2 - GTH</option>
                                               <option value="3">3 - Jefes </option>
                                               <!--<option value="4">4</option> -->
                                               <option value="5">5 - Usuario Simple</option>
                                               <!--<option value="6">6</option>-->
                                               </select></td>
                                           <td> &nbsp;</td>
                                           <td><select name="cbotrabajador" id="cbotrabajador" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                                <option selected value="NULL">Seleccione el trabajador</option>
                                                <?php echo $option; ?>
                                               </select>             
                                          </td>
                                           <td> &nbsp;</td>
                                           <td><select name="acceso" id="acceso" data-width="100%" data-size="5"class="selectpicker" data-live-search="true">
                                               <option selected value="NULL">Seleccione ...</option>
                                               <?php echo $optAcceso; ?>
                                               </select>             
                                          </td>                                          
                                           <th width="10%" align="center"><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="listar();"/></td>
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
                                              <h4 class="modal-title" id="exampleModalLongTitle">Permisos</h4>
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
                                      <!-- fin Modal -->
                                      </form>
                                      <!-- Modal -->
            <div class="modal fade" id="modificarUser">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Modificar Usuario</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row mb-3">
                        <div class="col-sm-12">
                          <!-- Vertical Form -->
                            <form class="row g-3" novalidate id="formularioModificar" name="formularioModificar">
                            <div class="col-12">
                              <label for="cedula" class="form-label">Cédula</label>
                              <input type="text" class="form-control" id="cedula" placeholder="" value="" required>
                            </div>  
                            <div class="col-12">
                              <label for="nombre" class="form-label">Nombre</label>
                              <input type="text" class="form-control" id="nombre" placeholder="" value="" required>
                            </div>                                                        
                            <div class="text-center">
                              <button type="reset" class="btn btn-secondary">Reset</button>
                              <button type="button" class="btn btn-primary" onclick="guardarModificar()">Guardar</button>                              
                            </div>                              
                          </form><!-- Vertical Form -->
                          <div id="info"></div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
                                      <!-- fin Modal -->
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
            //responsive: true,
             "order": [[ 1, "asc" ]],
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
pg_close($link);
//pg_close($link2);
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
} 
?>
