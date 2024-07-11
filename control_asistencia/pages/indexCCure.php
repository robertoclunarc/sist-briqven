<?php 
  session_start();
  //if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const']))
      
  require_once('menu.php');       
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');  
  
  if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $link1=Conex_Contancia_pgsql();
    $acceso=permiso_usuario($link1, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);
    if ($_SESSION['nivel_const']==1 || $acceso){        

        $option1=llenar_combo_trabajadoresTodos($link1);
    }
    else{
        $option1=llenar_combo_trabajadores_del_supervisor();
    }     
      
    $link2=Conex_rrhh_pgsql();
    if ($_SESSION['nivel_const']==1  || $acceso){
      $query="SELECT descripcion_unidad, centro_costo FROM unidades Order By descripcion_unidad;";
      $result2 = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
      $numReg = ejecutar_num_rows($result2);
      if($numReg>0){
        $option2='';
        while ($fila=ejecutar_fetch_array($result2)) 
        {
          $option2.= "<option value='". $fila['centro_costo']."'>" ;
          $option2.= $fila['centro_costo']." - ".$fila['descripcion_unidad']. "</option>";
        }
      }
      
      pg_free_result($result2);
    }
    else{
      $option2= llenar_combo_ccosto_del_supervisor();
    }
    
    pg_close($link1);
    pg_close($link2);

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
  document.getElementById("hddoper").value="fichada_ccure_online.php";
  document.getElementById("conFoto").value=true;
    $.ajax({
          type: "POST",
          url: "fichada_ccure_online.php",
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
    var namexls="Fichadas_CCURE_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Reporte de fichadas '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';


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
function cargarfoto(imagen){
  $("#capa6").html("<p align='center'><img src='../../rrhh/fotcarmat_new/"+imagen+".bmp' /></p>");
 }

//(*--------------------------------------------*)
$(document).ready(function(){   
 
 $('#exampleModalCenter').modal('show');
   
});
//(*--------------------------------------------*)
</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="150px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - Principal</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>
          <div id="page-wrapper">  
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <strong>Reporte de Asistencia (CCURE)</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
                                      <INPUT type="hidden" id="hddoper" name="hddoper" value="pages/fichada_ccure_online.php"/>
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
                                                    <td width="8%"><label>Direcci&oacute;n:</label></td>
                                                    <td width="1%"> &nbsp;</td>
                                                    <td width="15%"> <label>Centro Costo</label></td>
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
                                                    <td> 
                                                        <select name="cbodireccion" id="cbodireccion"  class="form-control">
                                                                <option value="">TODAS</option>
                                                                <option selected="" value="InDirection">ENTRADA</option>
                                                                <option value="OutDirection">SALIDA</option>
                                                        </select>
                                                    </td>
                                                    <td> &nbsp;</td>
                                                    <td><select name="cboccosto" id="cboccosto" data-width="80%" data-size="5"class="selectpicker" data-live-search="true">
                                                            <option selected value="NULL">Todos los Centro Costo</option>
                                                            <?php echo $option2; ?>
                                                        </select>             
                                                    </td>
                                                    <td  align="center">
                                                            <INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/>
                                                    </td>
                                                    <td  align="center">          
                                                            <INPUT id="cmdagrupado" type="button" value="%" title="Agrupar Entradas"  class="btn btn-danger" onclick="agrupar();"/>         
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
<div class="modal fade bd-example-modal-lg"  width="70%" id="exampleModalCenter" tabindex="-1" role="dialog"  aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
  <div class="modal-dialog modal-lg" role="document">
    <div style="background:transparent url('images/banner.JPG') no-repeat center center /cover; height: 500px" class="modal-content">
     
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
           
      <div  class="modal-body">
        <p align="center"  style="margin-top:150px; margin-bottom: 160px; color: #e9e1e2; background: rgb(27,93,204);
background: radial-gradient(circle, rgba(27,93,204,1) 0%, rgba(200,209,237,1) 96%);" ><strong>Bienvenidos al<br>Sistema de Control de Asistencia</strong></p>
    
      </div>

    </div>
      </h1>        
     
    </div>
  </div>
</div>
<!-- fin Modal -->

<!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalCenterFoto" style="width:80%; left: 15%;" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:80%;" role="document">
      
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLongTitle">Foto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div id="capa6" class="modal-body">
          
          
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          
        </div>
      </div>
      </div>
   
  </div>
  <!-- End Modal -->

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

    <script>
    function dataTable() {
        $('#dataTables-example').DataTable({
             
             "order": [[ 1, "asc" ]],
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
 }
 else{
    echo '<IMG SRC="seguridad-informatica.jpg" width="100%" height="100%" >
    <script  language="javascript">
        setTimeout(() => {
          alert("Acceso Restringido");
        }, 2000);
        setTimeout(() => {
          window.history.back();
        }, 2000);
        
    </script>';
    
 }
?>