 <?php
 session_start();
//if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
if (isset($_SESSION['user_session_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    require('funciones_var.php');

    include("../BD/conexion.php");

    $where= isset($_GET["fkdeuda"])?" WHERE fkdeuda=".$_GET["fkdeuda"]:"";
    $where.= isset($_GET["tipodeu"])?" AND tipo_pago='".$_GET["tipodeu"]."'":"";
    
    //$link=crearConexion();
    $link=Conex_Contancia_pgsql();
    if ($_SESSION['nivel_const']==3)
        $_where=" where supervisor='".$_SESSION['cedula_session_const']."'";
    else
        $_where="";

    $query="SELECT trabajador, e_mail,  (nombres || ' ' || apellidos) as Nombre_Completo, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia FROM public.trabajadores_activos_con_jefes_1 ".$_where. " order by nombres";
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
    $numReg = ejecutar_num_rows($result);

    if($numReg>0){
    	$option='';
    	while ($fila=ejecutar_fetch_array($result))
    	{
      		$option.= "<option value='". $fila['trabajador']."'" ;
      		if ($fila[2] != "")
        		$option.= ">". $fila[2]. "</option>";
      		else
        		$option.= ">". $fila['trabajador']. "</option>";
    	}
     }


/*
    $listado=" SELECT * FROM vw_formas_pagos";
    $listado.=$where." ORDER BY fecha desc, tipo_pago, descripcion";
    //$result = $link->query($listado);
*/
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
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

    <title>Ver Pagos</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">
 

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
<script  language="javascript">
function enviar(comp){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('comprobante_pago.php?comp='+comp, "Comprobante", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
 }
</script> 
</head>

<body>
<header id="titulo">      
<div class="portada">
    <div class="text">
      <IMG SRC="images/logo.png" width="230px" height="200px" >
    </div>
</div>
</header>
    <div id="wrapper">

         <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.html">PYME EASY</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Pagos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data General de Pagos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th width="10%">Fecha</th>
                            <th width="12%">Tipo Deuda</th> 
                            <th width="18%">Acreedor</th>                      
                            <th width="12%">Modalidad de Pago</th>
                            <th width="12%">Banco</th>  
                            <th width="12%">Monto</th>
                            <th width="12%">Nro. Transacion</th>
                            <th width="12%">Estatus</th>
                        </tr>
                        </thead>
                        <tbody>                                   
                      <?php                    
                         while ($reg = $result->fetch_assoc()) {
                      ?>                 
                         <tr class="gradeA"> 
                           
                           <td><?php echo $reg['fecha'];?></td>      
                            <td><?php echo $reg['tipo_pago'];?></td>
                            <td><?php echo $reg['descripcion'];?></td>          
                            <td><?php echo $reg['modalidad_pago'];?></td>
                            <td><?php echo $reg['banco'];?></td>
                            <td><?php echo number_format($reg['monto'],2,',','.');?></td>
                            <td><?php echo $reg['nro_referencia'];?></td>
                            <td><?php echo $reg['estatus_pago'];?></td>
                            
                            </tr>
                      <?php                    
                         }
                          $result->free();
                          $link->close();
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
            responsive: true
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
