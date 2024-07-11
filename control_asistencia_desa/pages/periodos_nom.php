<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  $link=Conex_rrhh_pgsql();
  
  $anio_a =date("Y");    
  $qsitt="select distinct TOP 5 anio from dbo.ADAM_CALENDARIO_NOMINA where tipo_nomina='LM' order by anio desc";
  $cn=Conectarse_sitt();
  $stmt1 = $cn->query($qsitt);
  $option1='';
  while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
  {
     $select=($anio_a==$row['anio'])?"selected":"";
     $option1.= "<option ".$select." value='". $row['anio']."'>" ;
     $option1.= $row['anio']. "</option>"; 
  } 
      
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Periodos</title>

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
          url: "buscar_periodos.php",
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
function cerrar_periodo(idcal){

$.ajax({
          type: "POST",
          url: "cerrar_periodo.php?idcal="+idcal,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("Por favor Espere... &nbsp;<br><img width='60' height='60' src='images/loading.gif' />");
                $("#boton_"+idcal).empty();
                $("#boton_"+idcal).html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>");                
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                //$("#capa_"+nroper).empty();
                //consultar();
                $("#capa2").empty();
                $("#boton_"+idcal).empty();                
                $("#capa2").append(data);               
                
                //alert(data);                          
          }
    });

}

//(*----------------------------------------------------------------------------*)
$(document).ready(function(){   
//  CargarCombo($("#cbofkcampo"),"cargar_combo_db.php?tabla=tbl_campos_adicionales&campo1=idcampo&campo2=nombre_campo&selected=0&firsttext=[Elija un campo]&orderby=idcampo"); 
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
                <a class="navbar-brand" href="index.php">Periodos</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Periodos de Nomina</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta de Periodos de Nomina LM
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
<div class="col-lg-12">

<table width="20%">    
    <tr>
      <td><label>A&ntildeo:</label></td>
       <td> <select name="cboanio" id="cboanio" onchange="consultar()" class="form-control" >             
            <?php echo $option1; ?>
            </select>
      </td>      
    </tr>   
       
</table>
</div>
<p>&nbsp;</p>
<div id="capa1" class="col-lg-12">   
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" width="20%" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
