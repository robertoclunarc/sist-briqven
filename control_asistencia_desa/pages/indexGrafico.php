 <?php
 session_start();
 require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=Conex_rrhh_pgsql();
    $query="SELECT min(inicio) as inicio, min(fin) as fin from periodos_nomina where abierto=true and tipo_nomina='LM'";
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
    $fila=ejecutar_fetch_array($result);
    $inicio=$fila['inicio'];
    $fin=$fila['fin'];
    pg_free_result($result);
    pg_close($link);
    $hoy =date("Y-m-d");
    if (strtotime($hoy) < strtotime($fin))
      $fin = date("Y-m-d",strtotime($hoy."- 1 days"));
  
  if ($_SESSION['nivel_const']==1  || $_SESSION['nivel_const']==2)  
  {
        $qsitt="select b.centro_costo, b.desc_ccosto, sum (horasnetaausencia) as horaausencia, count(*) as cantidadausencia 
from sw_hoja_de_tiempo_real a, adam_datos_personales b
where a.cedula = b.trabajador
and b.clase_nomina = 'ME'
and fecha between '".$inicio."' and '".$fin."'
and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF','SP:SP')
and horasnetaausencia >0
and cod_ausencia not in (32,33,34,44,72)
and b.turno != 9
group by b.centro_costo, b.desc_ccosto
order by 2,3;";
    $cn=Conectarse_sitt();
    $stmt1 = $cn->query($qsitt);
   
  }
  else{
    $link=Conex_Contancia_pgsql();
    $ccosto=ccosto_usuario($link, $_SESSION['user_session_const']);
    pg_close($link);
    $qsitt="select b.centro_costo, b.desc_ccosto, sum (horasnetaausencia) as horaausencia, count(*) as cantidadausencia 
from sw_hoja_de_tiempo_real a, adam_datos_personales b
where a.cedula = b.trabajador
and b.clase_nomina = 'ME'
and fecha between '".$inicio."' and '".$fin."'
and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF','SP:SP')
and horasnetaausencia >0
and cod_ausencia not in (32,33,34,44,72)
and b.turno != 9 and b.centro_costo='".$ccosto."' 
group by b.centro_costo, b.desc_ccosto
order by 2,3;";
    $cn=Conectarse_sitt();
    $stmt1 = $cn->query($qsitt);

  }   
 
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Principal</title>

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

</head>
<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

         <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Principal</a>
            </div>
            <!-- /.navbar-header -->
           <?php   echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu();?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Sistema Control de Asistencia</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">                
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Representacion Grafica de Ausencias por Centro de Costo <?php echo 'del '.$inicio.' al '.$fin; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <form>
                            <?php
                            $i=0;
                             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
                            {
                                    $i++;
                            ?>    
                               <input  name='hddcant_horas_<?php echo $i; ?>' id='hddcant_horas_<?php echo $i; ?>' type='hidden' value='<?php echo $row['horaausencia']; ?>'/>

                               <input  name='hddccosto_<?php echo $i; ?>' id='hddccosto_<?php echo $i; ?>' type='hidden' value='<?php echo $row['desc_ccosto']; ?>'/>
                            <?php    
                            }
                           ?>                       
                           <input  name='hddtotal' id='hddtotal' type='hidden' value='<?php echo $i; ?>'/>

                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-pie-chart"></div>

                            </div>
                            <br>
                            <br>
                            <br> 
                            <br>                                  
                        </div>
                        <!-- /.panel-body -->
                        </form>
                    </div>
                    <!-- /.panel -->
                </div>
                
                
                <!-- /.col-lg-6 -->
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

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <!-- Flot Charts JavaScript -->
    <script src="../vendor/flot/excanvas.min.js"></script>
    <script src="../vendor/flot/jquery.flot.js"></script>
    <script src="../vendor/flot/jquery.flot.pie.js"></script>
    <script src="../vendor/flot/jquery.flot.resize.js"></script>
    <script src="../vendor/flot/jquery.flot.time.js"></script>
    <script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
    <script src="../data/flot-data.js"></script>
    
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
