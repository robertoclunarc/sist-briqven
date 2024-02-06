<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  require_once('funciones_var.php');
      
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cierre Periodo</title>

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
var bar_progreso_activo = [];
var procesoID = 0;
var cantBarras=-1;
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
          url: "SW_CON_DATOS_CIERRE.php",
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
function cerrar_periodo(semana, anio){

$.ajax({
          type: "POST",
          url: "G_Cierre.php?sem="+semana+"&anio="+anio,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#"+semana).html("<img width='25' height='25' src='images/loading.gif' />");
                $("#boton_"+semana).empty();
                $("#boton_"+semana).html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>"); 
                
                //$("#capa3").append('<div class="progress"><div id="bar_" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
                $("#capa3").html('<div class="progress"><div id="bar_" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
                
              cantBarras=cantBarras+1;
              //$('#progreso_'+semana).val('0');
              //limpiar_procesos();
              bar_progreso_activo.push(semana); 
              if (cantBarras==0)             
                  progreso_semana(semana, 0.625);
              else
                  progreso_semana(semana, 0.625/cantBarras);
              //programar_barra(0.625);
                              
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){             

                if (data.indexOf("error")>=0){
                  $("#capa2").append('<div class="alert alert-danger">'+data+'</div>');
                }
                else{                  
                  cantBarras=cantBarras-1;
                  consultar();
                                   
                  //$("#"+semana).empty();
                  $("#capa2").append(data);
                  //alert(data);
                }                                          
          }
    });
}
//(*--------------------------------------------*)
function limpiar_procesos(){
  for (i=0; i<=cantBarras; i++){
    clearInterval($('#progreso_'+bar_progreso_activo[i]).val());
    
  }
}
//(*--------------------------------------------*)
function programar_barra(tiempo){
  var espera = bar_progreso_activo.length;
  if (espera>0){
    if (espera==1){
      progreso_semana(bar_progreso_activo[0], tiempo);
    }else {
      espera=(procesoID+(espera*100))/2;
      
      $('#bar_'+bar_progreso_activo[cantBarras]).text('Esperando por la Semana ' + bar_progreso_activo[cantBarras-1] + ', en ' + parseInt(espera) + ' segundos');
      $("#capa3").append('Esperando por la Semana ' + bar_progreso_activo[cantBarras-1] + ', en ' + parseInt(espera) + ' segundos');
      //setTimeout(progreso_semana(bar_progreso_activo[cantBarras], tiempo), espera);

      setTimeout(function(){
        progreso_semana(bar_progreso_activo[cantBarras], tiempo);
      }, espera*1000);
    }    
  }
}

//(*--------------------------------------------*)
function progreso_semana(semana, tiempo){
   
    var progreso = 0;
    
    var idIterval = setInterval(function(){
    // Aumento el progeso
    procesoID = 100-progreso;
    progreso +=tiempo;
    $('#bar_').css('width', progreso + '%');
    //$('#bar_'+semana).css({'background-color': "red"})
    $('#bar_').text(Math.round(progreso,-1) + '%' + ' Semana ' + semana);
    $('#progreso_'+semana).val(idIterval);  
    //Si llegó a 100 elimino el interval
      if(progreso >= 100){
        clearInterval(idIterval);
        $('#progreso_'+semana).val(0);
        var pos = bar_progreso_activo.indexOf(semana);
        bar_progreso_activo.splice(pos, 1);
        procesoID=0;
        
      }
    },1000); 

}
//(*--------------------------------------------*)

function progreso_transferencia(){
   
   var progreso = 0;   
   var idIterval = setInterval(function(){
   // Aumento el progeso   
   progreso +=0.24;
   $('#bar_').css('width', progreso + '%');
   $('#bar_').css({'background-color': "red"});   
   $('#bar_').text(Math.round(progreso,-1) + '%' + ' transfiriendo al ADAM');   
   //Si llegó a 100 elimino el interval
     if(progreso >= 100){
       clearInterval(idIterval);
     }
   },1000); 

}
//(*--------------------------------------------*)
function enviarAdam(){

  var x;
  var r=confirm("Desea Transferir los Tiempos al Sistem Nomina");
  if (r==true)
  {
    x="OK";
  }
  else
  {
    return
  }

  if (x=="OK"){
    $.ajax({
          type: "POST",
          url: "G_CierreAdam.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga                
                $("#adam").empty();
                $("#adam").html("<p align='center'><img  width='25' height='25' src='images/ajax_loader.gif' /></p>");

                 //$("#capa3").append('<div class="progress"><div id="bar_" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
                $("#capa3").html('<div class="progress"><div id="bar_" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
                
                progreso_transferencia();
               
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                   
                if (data.indexOf("error")>=0){
                  $("#capa2").append('<div class="alert alert-success">'+data+'</div>');
                }
                else{                  
                  $("#capa1").empty();
                  $("#capa1").append(data);
                  $("#adam").empty();
                  $("#adam").html("<p align='center'>Listo!</p>");
                }                                          
          }
    });
  }
}

//(*----------------------------------------------------------------------------*)
$(document).ready(function(){   
//  CargarCombo($("#cbofkcampo"),"cargar_combo_db.php?tabla=tbl_campos_adicionales&campo1=idcampo&campo2=nombre_campo&selected=0&firsttext=[Elija un campo]&orderby=idcampo"); 
  consultar();

  /*var progreso = 0;
  var idIterval = setInterval(function(){
  // Aumento en 10 el progeso
  progreso +=10;
  $('#bar').css('width', progreso + '%');
     
  //Si llegó a 100 elimino el interval
    if(progreso == 100){
      clearInterval(idIterval);
    }
  },500);*/
   
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
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                    <h3 class="page-header">Periodos de Nomina</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta de Periodos de Nomina MS
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
<div class="col-lg-12">

<table width="20%">    
    <tr>
      <td><div id="adam"><button type="button" title="Enviar al Adam" onclick='enviarAdam()' class="btn btn-success btn-circle"><span class="glyphicon glyphicon-circle-arrow-up"> </span>
                            </button></div></td>
       <td> <button type="button" title="Ver Kardex" class="btn btn-warning btn-circle"><span class="glyphicon glyphicon-list-alt"> </span>
                            </button>
      </td> 

    </tr>   
       
</table>
<br>

<div id="capa2" class="col-lg-12">   
</div>
<div id="capa1" class="col-lg-12">   
</div>
<div id="capa3" class="col-lg-12">   
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
