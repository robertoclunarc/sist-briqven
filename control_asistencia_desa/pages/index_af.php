 <?php
 session_start();
 require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=Conex_Contancia_pgsql();
    if ($_SESSION['nivel_const']==3)
        $_where=" and a.trabajador_reg='".$_SESSION['user_session_const']."'";
    else
        $_where="";    
//    $listado="SELECT a.*,b.nombres,b.apellidos FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador and bloqueado=0 ".$_where." ORDER BY fecha DESC";
    //$listado="SELECT a.*,b.nombres,b.apellidos FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador and bloqueado=0 and a.trabajador_reg in (SELECT trabajador FROM trabajadores_activos_con_jefes_1 where supervisor='". $_SESSION['cedula_session_const']."') ORDER BY fecha DESC";
    $listado="SELECT a.*,b.nombres,b.apellidos FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador and a.trabajador_reg in (SELECT trabajador FROM trabajadores_activos_con_jefes_1 where supervisor='". $_SESSION['cedula_session_const']."') ORDER BY fecha DESC";
//print $listado;
    $result = ejecutar_query($link,$listado);
    $numReg = ejecutar_num_rows($result);
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registrar Fichadas</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  <script src="../js/jquery-1.11.1.min.js"></script>
  

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
//alert("paso");
    dir_url = "guardar_validacion_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               	//OJO.
           	if (data>0)
            	{
              		alert("Datos Registrados Correctamente!");
              		location.reload();
           	}else{
            		alert("La operación Generó un Error: " + data); 
            		document.getElementById("cmdGuardar").disabled = false;
           	}   
           }
         });
}

//(*--------------------------------------------*)



function enviar(idequi){
    window.location="update_activo.php?idequipo="+idequi;  
 }



$(document).ready(function(){ 

 if ($('#numreg').val()==0){
              alert('Lo siento, usted no tiene personal para validar asistencia');  
              window.location="index.php";
        }




});


</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

         <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.html">Fichadas</a>
            </div>
            <!-- /.navbar-header -->
           <?php   echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu();?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Validar Fichadas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" id="formulario" name="formulario" method='post'>
            <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data General de la Fichada
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
<!--                            <table width="100%"  id="dataTables-example" border='1'> -->
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example"> 
                                <thead>
                                    <tr> 
                                        <th>Oper.</th>
                                        <th>Validar</th>
                                        <th>Fecha</th>
                                        <th>Cedula</th>
                                        <th>Nombres</th>  
                                        <th>Asisti&oacute;</th>                      
                                        <th>Sobre tiempo</th>
                                        <th>Comisi&oacute;n</th>
                                        <th>Cambio de Turno</th>
                                        <th>Permiso</th>
                                        <th>Observaci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = ejecutar_fetch_array($result)) {
			        if ($reg['cambio_turno']=="S") $observacion="Vino a trabajar en el turno: ".$reg['turno']."<br>".$reg['observacion']; else $observacion=$reg['observacion'];
				if ($reg['bloqueado']=='1') $disabled='checked disabled'; else $disabled='';
                      ?>                 
                            <tr class="gradeA">
			        <td><A href="registrar_fichada_m.php?id='<?php echo $reg['fecha']; ?>'&id2='<?php echo $reg['trabajador'];?>'" title="Ver fichada del d&iacute;a: '<?php echo $reg['fecha']; ?>'"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A></td>
                                <td><?php echo "<input class='custom-control-input' name='chkvalidado[]' id='chkvalidado[]' type='checkbox' ".$disabled." value='".$reg['trabajador']."/".$reg['fecha']."'/>";?></td>
                                <?php echo "<input name='txtvali[]' id='txtvali[]' type='hidden' value='2'/>";?>
                                <td><?php echo $reg['fecha']."<input name='fecha_reg[]' type='hidden' value='".$reg['fecha']. "'/>";?></td>
                                <td><?php echo $reg['trabajador']."<input name='cedulas[]' type='hidden' value='".$reg['trabajador']. "'/>"?></td>
                                <td><?php echo $reg['nombres'].' '.$reg['apellidos'];?></td>
                                <td><?php echo cambiar_S_X($reg['asistio']);?></td>
                                <td><?php echo cambiar_S_X($reg['sobre_tiempo']);?></td>
                                <td><?php echo cambiar_S_X($reg['comision']);?></td>
                                <td><?php echo cambiar_S_X($reg['cambio_turno']);?></td>
                                <td><?php echo nombre_inasistencia($reg['inasistencia']);?></td>
                                <td><?php echo $observacion;?></td>
                                
                            </tr>
                      <?php                    
                         }
                         ejecutar_free_result($result);
                         ejecutar_close($link);
                      ?>              
<div align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Validaci&oacute;n"  class="btn btn-success" onclick="GuardarRegistro();"/></div>
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
<div align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Validaci&oacute;n"  class="btn btn-success" onclick="GuardarRegistro();"/></div>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            </form>            
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

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            "order": [[ 1, "desc" ]]
        });
    });
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
