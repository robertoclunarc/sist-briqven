<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  
  $link2=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link2, 'CONS_PERM', basename( __FILE__ ), $_SESSION['user_session_const']);
  if ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const']==2 || $acceso) {
      $option=llenar_combo_trabajadoresTodos($link2);
  }
  else{
        
      $option=llenar_combo_trabajadores_del_supervisor();
  }

  $qryMinMaxFecha="SELECT to_char(min(inicio),'YYYY-MM-DD') as minfecha, to_char(min(fin),'YYYY-MM-DD') as maxfecha FROM sw_permisos where estado not in ('B', 'L')";
  $resMinMaxFecha = pg_query($link2, $qryMinMaxFecha);  
  $rowMinMaxFecha = pg_fetch_array($resMinMaxFecha);
  if ($rowMinMaxFecha['minfecha']!=''){  
    $fecha_a = $rowMinMaxFecha['maxfecha'];
    $fecha_actual = $rowMinMaxFecha['minfecha'];
    pg_free_result($resMinMaxFecha);
  }else{
    $fecha_a =date("Y-m-d");
    $fecha_actual = date("Y-m-d",strtotime($fecha_a."- 1 days"));
  }

  pg_close($link2);
  $qsitt="select  distinct a.Cod_Adam, c.descripcion_ausencia from SW_Permisos a, adam_codigo_ausencias c where a.cod_adam = c.codigo_hora and a.estado != 'B' order by 1";
    $cn=Conectarse_sitt();
    $stmt1 = $cn->query($qsitt);
    $option1='';
    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
    {
       $option1.= "<option value='". $row['Cod_Adam']."'>" ;
       $option1.= $row['Cod_Adam']." - ".$row['descripcion_ausencia']. "</option>"; 
    }

   $option2='';
   $option2.= "<option value='D'>D - Documentos</option>";
   $option2.= "<option value='A'>A - Autorizado</option>";
   $option2.= "<option value='E'>E - Espera</option>";
   $option2.= "<option value='S'>S - Aut. Sup.</option>";
   $option2.= "<option value='V'>V - Doc. y Aut. Sup.</option>";
   $option2.= "<option value='B'>B - Borrado</option>";
   $option2.= "<option value='L'>L - Listo</option>"; 

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Permisos</title>

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
    $.ajax({
          type: "POST",
          url: "buscar_permisos.php",
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
                //alert(data); 
                $("#capa1").empty();
                $("#capa1").append(data);          
          }
    });
}
//(*--------------------------------------------*)
function exportar_excel(){    
    
    var desde=$("#txtfinicio").val();
    var hasta=$("#txtffin").val();
    var namexls="Permisos_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Hoja de Tiempo. '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

       $.ajax({
              type: "POST",
              url: "buscar_permisos_excel.php",
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
function cambio_estado(nroper, estado){
      var html = '';// fragmento de la pagina html que se va a agregar
      var est = ''; // nuevo estado del permiso
      var cls = '';// color de la etiqueta del permiso
      $("#destado_"+nroper).empty();
      switch(estado) {
        case 'D': est='Doc. y Aut. Sup.'; cls="label label-danger";
          break;
        case 'A': est='Listo'; cls="label label-info";
          break;  
        case 'E': est='Listo'; cls="label label-info";
          break;
        case 'S': est='Listo'; cls="label label-info";
          break;
        case 'V': est='Documentos'; cls="label label-danger";
          break;
        case 'B': est='Borrado'; cls="label label-danger";
          break;
        case 'L': est='Autorizado'; cls="label label-success";
          break;
        case 'Espera': est='Aprobado'; cls="label label-info";
          break;  
      } 
      html='<div id="destado_'+nroper+'"<span style="font-size: 8pt" class="'+cls+'">'+est+'</span></div>';
      $("#destado_"+nroper).html(html); 
}
//(*--------------------------------------------*)
function confirmar(nroper, cedula, estado, idausencia, tiponotif){
var opcion = confirm("Desea Cambiar el Estatus de "+tiponotif+" Nro. "+nroper+" ?");
  if (opcion == false) {
        $('#exampleModalCenter').modal('toggle');
        return;
  } else {
    $.ajax({
          type: "POST",
          url: "confirmar_Perm_sitt.php?nroper="+nroper+"&ced="+cedula,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("Por favor Espere... &nbsp;<br><img width='60' height='60' src='images/loading.gif' />");
                $("#boton_"+nroper).empty();
                $("#boton_"+nroper).html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //$("#capa_"+nroper).empty();
                //consultar();
                $("#capa2").empty();
                $("gteAut_"+idausencia).empty();                
                $("#capa2").append(data);
                cambio_estado(idausencia, estado);                
                //alert(data);                          
          }
    });
  }
}

//(*--------------------------------------------*)
function procesar(nroper, cedula, estado, idausencia, tiponotif){
var opcion = confirm("Desea ir a "+tiponotif+" Nro. "+idausencia+" ?");
  if (opcion == false) {
        $('#exampleModalCenter').modal('toggle');
        return;
  } else {
    const url = `cargar_permiso.php?idausencia=${idausencia}`;
    window.location.href = url;
  }
}
//(*--------------------------------------------*)
function gerenteAutoriza(idausencia, tiponotif, estado){
var opcion = confirm("Desea Aprobar "+tiponotif+" Nro. "+idausencia+" ?");
  if (opcion == false) {
        $('#exampleModalCenter').modal('toggle');
        return;
  } else {
    $.ajax({
          type: "GET",
          url: "gteAutorizaPermiso.php?idausencia="+idausencia+"&tiponotif="+tiponotif,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("Por favor Espere... &nbsp;<br><img width='60' height='60' src='images/loading.gif' />");
                $("#gteAut_"+idausencia).empty();
                $("#gteAut_"+idausencia).html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //$("#capa_"+nroper).empty();
                //consultar();
                $("#capa2").empty();
                $("#gteAut_"+idausencia).empty();
                $("#elimina_"+idausencia).empty();
                $("#capa2").append(data);
                cambio_estado(idausencia, estado);                
                //alert(data);                          
          }
    });
  }
}
//(*--------------------------------------------*)
function eliminar_permiso(nroper, ced, idausencia, tiponotif){
  var opcion = confirm("Desea Borrar "+tiponotif+" Nro. "+idausencia+" ?");

  if (opcion == false) {
       $('#exampleModalCenter').modal('toggle');
       return;
  } else {  
            $.ajax({
                  type: "POST",
                  url: "borrar_Perm_sitt.php?nroper="+nroper+"&ced="+ced+"&idausencia="+idausencia+"&tiponotif="+tiponotif,
                  data: $("#formulario").serialize(),
                  dataType: "html",
                  beforeSend: function(){
                        //imagen de carga
                        $("#capa2").html("Por favor Espere... &nbsp;<br><img width='60' height='60' src='images/loading.gif' />");
                        $("#elimina_"+idausencia).empty();
                        $("#elimina_"+idausencia).html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>");
                        
                  },
                  error: function(){
                        alert("error petición ajax");
                  },
                  success: function(data){                                                    
                        //$("#capa_"+nroper).empty();
                        //consultar();
                        $("#capa2").empty();
                        $("#elimina_"+idausencia).empty();
                        if($("#boton_"+idausencia).length != 0) {
                          $("#boton_"+idausencia).empty();
                        }
                        if($("#gteAut_"+idausencia).length != 0) {
                          $("#gteAut_"+idausencia).empty();
                        }                        
                        
                        $("#capa2").append(data);
                        cambio_estado(idausencia, 'B');
                        
                        //alert(data);                          
                  }
            });
          }
}
//(*----------------------------------------------------------------------------*)
$(document).ready(function(){   
  consultar();
   
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
                <a class="navbar-brand" href="index.php">Permisos</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MENU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Permisos/Ausencias</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta de Permisos y Ausencias
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
       <td> &nbsp;</td>
       <td><label>Tipo De Permiso:</label></td>
       <td> &nbsp;</td>
       <td><label>Estado:</label></td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
    </tr>   
     <tr> 
       <td><INPUT type="date" id="txtfinicio" maxlength="80" name="txtfinicio" value="<?php echo $fecha_actual; ?>" width="100%" class="form-control"/></td>
       <td> &nbsp;</td>
       <td><INPUT type="date" id="txtffin" maxlength="50" name="txtffin" value="<?php echo $fecha_a; ?>"  width="100%" class="form-control"/></td>
       <td> &nbsp;</td>
       <td><select name="cbotrabajador" id="cbotrabajador" data-width="80%" data-size="5"class="selectpicker" data-live-search="true">
            <option selected value="NULL">Seleccione el trabajador</option>
            <?php echo $option; ?>
           </select>             
      </td>
      <td> &nbsp;</td>
       <td> <select name="cbocodigo" id="cbocodigo" class="form-control" >
             <option selected value="NULL">Seleccione el Permiso</option>
            <?php echo $option1; ?>
            </select>
      </td>
      <td> &nbsp;</td>
       <td> <select name="cboestado" id="cboestado" class="form-control" >
             <option selected value="NULL">Seleccione el Estado</option>
            <?php echo $option2; ?>
            </select>
      </td>
       <td width="10%" align="center"><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/></td>
       <td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a> </td>
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

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" width="20%" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static"> 
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="capa2" class="modal-body">
        Por favor Espere... &nbsp;<br>
        <img width="60" height="60" src='images/loading.gif' />        
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

     <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
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
