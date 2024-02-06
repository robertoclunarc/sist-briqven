 <?php
 session_start();
 require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=Conex_Contancia_pgsql();
    
    //$listado="SELECT * FROM registro_diario ORDER BY fecha DESC";
    if ($_SESSION['nivel_const']==3){
        $complemento=" and a.supervisor='".$_SESSION['user_session_const']."'";
        $_where=" where supervisor='".$_SESSION['cedula_session_const']."'";
    }else{
        $complemento=" "; 
	$_where=" ";
    }
   $listado="SELECT a.*,b.nombres,b.apellidos, c.* FROM evaluacion a, trabajadores_activos_con_jefes b, periodo_e c where a.trabajador=b.trabajador and a.periodo=c.num_periodo ".$complemento." ORDER BY periodo DESC";
//   print $listado; 
    $result = ejecutar_query($link,$listado);

// *************************  Consultamos los datos de los trabajadores ****************************

    $query="SELECT trabajador, e_mail,  (nombres || ' ' || apellidos) as Nombre_Completo, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia FROM public.trabajadores_activos_con_jefes_1 ".$_where. " order by nombres";
  $result_2 = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
//print $query;
  if($numReg>0){
    $option='<option selected value="0">Seleccione el trabajador</option>';
    while ($fila=ejecutar_fetch_array($result_2))
    {
      $option.= "<option value='". $fila['trabajador']."'" ;
      if ($fila[2] != "")
        $option.= ">". $fila[2]. "</option>";
      else
        $option.= ">". $fila['trabajador']. "</option>";
    }
  }


 // **************************** Llenamos e l combo de Periodo a evaluar  ***************************************
 $fecha_actual = date("Y-m-d");
 $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));
  $query_periodo="select * from periodo_e where hasta <= '".$fecha_a."' order by num_periodo desc";
  $result2 = ejecutar_query($link, $query_periodo) or die("Error en la Consulta SQL: ".$query_periodo);
  $numReg2 = ejecutar_num_rows($result2);
  if($numReg2>0){
    $periodo='<option selected value="0">Seleccione el Periodo a Evaluar</option>';
    while ($fila=ejecutar_fetch_array($result2))
    {
      $periodo.= "<option value='". $fila['num_periodo']."'" ;
      $periodo.= ">Desde: ". $fila['desde']. " - Hasta: ".$fila['hasta']."</option>";
    }
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
<script  language="javascript">

function buscar_trabajador(idtipoeqp,fecha)
{
alert('paso');  
 if (idtipoeqp!='')
    {
      url="cargar_esperanza.php?b=" + idtipoeqp + "&c="+fecha; 
      $.ajax(url).done(function(data)
       {   
          if (data != "VACIO"){
              eval(data);
        //      AgregarFilaInfo(idtipoeqp);
          }
       }
      )
    }
}
//(*--------------------------------------------*)


function enviar(idequi){
    window.location="update_activo.php?idequipo="+idequi;  
}

function CargarCombo(nombcombo, url)
{
alert(url);
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}



</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

         <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
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
 <input  name='hddcedula_login' id='hddcedula_login' type='hidden' value='<?php echo $_SESSION['cedula_session_const']; ?>'/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Generales de las evaluaciones
                        </div>
					<table width="80%" class="table table-striped" border="1">
                                          <thead>
                                             <tr>
                                                  <th width="20%"><label>Periodo a Evaluar</label></th>
                                                  <th width="20%">
                                                        <select name="cboperiodo" onchange="" id="cboperiodo" class="form-control" data-width="80%" data-size="5"  data-hide-disabled="false" data-live-search="true">
                                                                <?php echo $periodo; ?>
                                                        </select>
                                                  </th>
                                            </tr>
                                             <tr>

                                                  <th width="20%"><label>Trabajador:</label></th>
                                                  <th width="40%">
                                                        <select id="cbotrabajador" name="cbotrabajador" onchange="buscar_trabajador($('#cbotrabajador').val(),$('#txtfecha').val());" id="cbotrabajador" class="form-control" data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true">
<?php echo $option; ?>

                                                        </select>
                                            </tr>
                                            <tr>
                                                <th><label>Puntuaci&oacute;n</label></th>
                                                <th><select name="cbopuntuacion" id="cbopuntuacion"  data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true"></select><label><?php echo $color; ?></label>
<div id="ponderacion"></div>

                                                </th>
                                            </tr>
                                  	</thead>
                                      </table>



                        <!-- /.panel-heading -->
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th>Periodo</th>
                                        <th>C&eacute;dula</th>
                                        <th>Nombres</th>  
                                        <th>Puntuaci&oacute;n</th>                      
                                        <th>Observaci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = ejecutar_fetch_array($result)) {
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
                                <td><?php echo $reg['desde']." / ".$reg['hasta'];?></td>
                                <td><?php echo $reg['trabajador'];?></td>
                                <td><?php echo $reg['nombres'].' '.$reg['apellidos'];?></td>
                                <td><?php echo $reg['puntuacion'];?></td>
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
      CargarCombo($("#cbopuntuacion"),"cargar_combo_db.php?tabla=baremo&campo1=puntuacion&campo2=porcentaje&orderby=porcentaje&firsttext=Seleccione la putuacion");
    //  CargarCombo($("#cbotrabajador"),"cargar_combo_db.php?tabla=trabajadores_activos_con_jefes_1&campo1=trabajador&campo2=(nombres || ' ' || apellidos) as Nombre_Completo&firsttext=Seleccione el trabajador&orderby=Nombre_completo&where=supervisor&vwhere="+$("#hddcedula_login").val());




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
