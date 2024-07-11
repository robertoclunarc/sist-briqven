<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
/*  $link=Conex_rrhh_pgsql();
    $link=Conex_Contancia_pgsql();
    $desde= date("Y-m-d",time()-3600);
    $hasta= date("Y-m-d",time()-3600);
    $numreg='';
    if ($_SESSION['nivel_const']==2 )
        $query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS";
    else
        $query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS WHERE trim(TRABAJADOR_SUP)='".$_SESSION['cedula_session_const']."'";
    $query.=" order by nombre";
    $conn=Conex_oramprd();
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $option='';
    while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false){
        $option.= "<option value='". $fila['TRABAJADOR']."'" ;
        $option.= ">". $fila['TRABAJADOR']." - ".$fila['NOMBRE']. "</option>";
    }
*/
$option = llenar_combo_trabajadores('te');
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

    <title>Autorizar Horas Extras</title>

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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script  language="javascript">
//(*--------------------------------------------*)
function GuardarRegistro()
{

  if (parseInt($("#hddcontador").val())==0){
    alert("Debe agregar los trabajadores");    
    return;
  }
    document.getElementById("cmdGuardar").disabled = true;
    
    dir_url = "autorizar_te_db.php";
//alert ('paso');
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
         //alert(data); // Mostrar la respuestas del script PHP.
           if (data>0)
            {
              alert("Datos Registrados Correctamente!");
              location.reload();
           }else{
            alert("La operación Generó un Error: " + data);
            document.getElementById("cmdGuardar").disabled = false;
  //          ocultar("ver");
           }   
           }
         });
  
//  }
}

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
          url: "./horas_extras_online.php",
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
    var namexls="Kardex_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Reporte de tiempo trabajado. '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

       $.ajax({
              type: "POST",
              url: "horas_extras_online.php",
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
/*$(document).ready(function(){   
 CargarCombo($("#cbofktipo"), "cargar_combo_db.php?tabla=tbl_tipos_elementos&campo1=idtipo&campo2=descripcion_tipo");

  CargarCombo($("#cbofkcampo"),"cargar_combo_db.php?tabla=tbl_campos_adicionales&campo1=idcampo&campo2=nombre_campo&selected=0&firsttext=[Elija un campo]&orderby=idcampo"); 
   
});*/
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
                    <h1 class="page-header">Autorizar horas extras</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reporte de tiempo trabajado para la emision de n&oacute;mina
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
       <td width="10%" align="center"><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/></td>
       <td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a> 
    </tr>    
</table>
</div>
<p>&nbsp;</p>
<div id="capa1" class="col-lg-12"> 
<!--
    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="5%"class="info">CCosto</th>        
                <th width="10%"class="info">Desc. CCosto</th>
                <th width="5%" class="info">Ger. Gral.</th>
                <th width="5%" class="info">Turno</th>
                <th width="5%" class="info">Horas Pres.</th>
                <th width="5%" class="info">Dias Pres.</th>
                <th width="5%" class="info">Jorn.</th>
                <th width="5%" class="info">Horas Total Jorn.</th>
                <th width="5%" class="info">%</th>
            </tr>
        </thead>
        <tbody>
          <tr>        
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
          </tr>
        </tbody> 
    </table> 
   -->     
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
