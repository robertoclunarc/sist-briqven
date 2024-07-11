 <?php
 session_start();
 require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=Conex_Contancia_pgsql();
    
    //$listado="SELECT * FROM registro_diario ORDER BY fecha DESC";
    if ($_SESSION['nivel_const']==3)
        $_where=" and a.supervisor='".$_SESSION['user_session_const']."'";
    else
        $_where=""; 

//   $listado="SELECT a.*,b.nombres,b.apellidos, c.* FROM evaluacion a, trabajadores_activos_con_jefes b, periodo_e c where a.trabajador=b.trabajador and a.periodo=c.num_periodo ".$_where." ORDER BY periodo DESC";
   //$listado_supervisor="select * from adam_vw_dotacion_briqven_02_mas where CAST ('grado_trab' as integer) >=39 and  trabajador_sup='".$_SESSION['cedula_session_const']."' order by nombre;";

//   $listado_supervisor="select * from adam_vw_dotacion_briqven_02_mas where CAST(COALESCE(NULLIF(regexp_replace(grado_trab, '[^-0-9.]+', '', 'g'),''),'0') as numeric)  >=39 and  trabajador_sup='".$_SESSION['cedula_session_const']."' order by nombre;";
  //$listado_supervisor="SELECT a.*,b.nombres,b.apellidos FROM evaluacion a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador and a.supervisor in (SELECT substring(e_mail,1,6) FROM trabajadores_activos_con_jefes_1 where supervisor='". $_SESSION['cedula_session_const']."') ORDER BY fecha_reg DESC";
  $listado_supervisor="SELECT a.*,c.desde,c.hasta, b.nombres,b.apellidos,b.descripcion_gerencia FROM evaluacion a, trabajadores_activos_con_jefes_1 b,periodo_e c where a.trabajador=b.trabajador and a.periodo=c.num_periodo and a.supervisor in (SELECT substring(e_mail,1,6) FROM trabajadores_activos_con_jefes_1 where supervisor='". $_SESSION['cedula_session_const']."') ORDER BY periodo DESC";
//print $listado_supervisor; 
  $result = ejecutar_query($link,$listado_supervisor);
   $numReg = ejecutar_num_rows($result);
//print "<br>-------------------------------------<br>#".$numReg;
   $i=0;
   if($numReg>0){
      while ($reg = ejecutar_fetch_array($result)) {
             $listado_trabajadores="select * from adam_vw_dotacion_briqven_02_mas where grado_trab >='39' and  trabajador_sup='".$reg['trabajador']."' order by nombre";
//print $listado_trabajadores;
             $result_t = ejecutar_query($link,$listado_trabajadores);
	     $numReg_t = ejecutar_num_rows($result_t);             
	     if($numReg_t=='0'){
                while ($reg_t = ejecutar_fetch_array($result_t)) {
                  print $reg_t['nombre'];
 	        }
	     }
      }
   } 



//   print $listado; 
    $result = ejecutar_query($link,$listado_supervisor);

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ultimas evaluaciones</title>

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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

-
<script  language="javascript">

function enviar(idequi){
    window.location="update_activo.php?idequipo="+idequi;  
}

/*
$(document).ready(function(){
alert('paso'); 
        if ($('#numreg').val()==0){
              alert('Lo siento, usted no tiene personal bajo su supervision');  
              window.location="index.php";
        }

});
*/
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
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Ultimas evaluaciones registradas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Generales de las evaluaciones
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <input  name='numreg' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th>Validar</th>
                                        <th>Periodo</th>
                                        <th>C&eacute;dula</th>
                                        <th>Nombres</th>  
                                        <th>Puntuaci&oacute;n</th>                      
                                        <th>Unidad Administrativa</th>
                                        <th>Observaci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = ejecutar_fetch_array($result)) {
				if ($reg['validado']=='1') $disabled='checked disabled'; else $disabled='';
                      ?>                 
                            <tr class="gradeA">
<!--                                <td>  -->
                                    <?php                    
  //                                  if ($reg['estatus']=='PENDIENTE') {
                                    ?>
<!--                                   <A href="#" onclick="enviar('<?php echo $reg['cedula'];?>')" title=""><IMG SRC="images/note.png" WIDTH="20px" HEIGHT="20px"></A>  
-->                                    <?php
    //                                }
                                    ?>
<!--                                </td>  -->
<!--				<td> -->
                                        <?php 
//                                        $os1 = array('CERRADO', 'SATIFECHO');
//
//                                        if (($_SESSION['nivel']<=2) && (!in_array($reg['estado'], $os1))){ 
                                        ?>
<!--                                        <A href="#" onclick="enviar(<?php //echo $reg['idticket'];?> , '<?php //echo $reg['login_asignado'];?>')" title="Ver/Actualizar">
                                                <IMG SRC="images/note.png" WIDTH="20px" HEIGHT="20px">
                                        </A>   
-->                                                                     
                                        <?php// }
//                                        $os2 = array('EN REVISION', 'SATIFECHO', 'PENDIENTE', 'EN ESPERA', 'EN PROCESO'); 
//                                        if (((in_array($reg['estado'], $os2)) && (($reg['login_solicitante']==$_SESSION['user_session']) || ($reg['login_asignado']==$_SESSION['user_session']) ) && $destiempo) || ($_SESSION['nivel']==1)){
                                        ?>
<!--                                        <A href="#" onclick="seguir(<?php echo $reg['idticket'];?> , '<?php echo $reg['login_asignado'];?>')" title="Seguimiento">
                                               <IMG SRC="images/segui.png" WIDTH="20px" HEIGHT="20px">
                                       </A>
-->
                                        <?php //}  ?>
 
                                <td></td>
 <td><?php echo "<input class='custom-control-input' name='chkvalidado[]' id='chkvalidado[]' type='checkbox' ".$disabled." value='".$reg['trabajador']."/".$reg['periodo']."'/>";?></td>
                                <?php "<input name='cedulas[]' type='hidden' value='".$reg['trabajador']. "'/>"?>

                                <td><?php echo $reg['desde']." / ".$reg['hasta'];?></td>
                                <td><?php echo $reg['trabajador'];?></td>
                                <td><?php echo $reg['nombres'].' '.$reg['apellidos'];?></td>
                                <td><?php echo $reg['puntuacion'];?></td>
                                <td><?php echo $reg['descripcion_gerencia'];?></td>
                                <td><?php echo $reg['observacion'];?></td>
                                
                            </tr>
                      <?php                    
                         }
                         ejecutar_free_result($result);
                         ejecutar_close($link);
                      ?>              
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
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

       if ($('#numreg').val()==0){
              alert('Lo siento, usted no tiene personal bajo su supervision');  
              window.location="index.php";
        }



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