<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  $link=Conex_rrhh_pgsql();
  $link2=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link2, 'CONS_PERM', basename( __FILE__ ), $_SESSION['user_session_const']);
  if ($_SESSION['nivel_const']==1  || $acceso)  
      $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";
  else   
      $query="SELECT trabajador, (nombres || ' ' || apellidos) as nombre FROM trabajadores_activos_con_jefes_1 where supervisor='".$_SESSION['cedula_session_const']."' order by nombres";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $option.= "<option value='". $fila['trabajador']."'>" ;
        $option.= $fila['trabajador']." - ".$fila['nombre']. "</option>";
    }
  }

    $qsitt="select distinct razon_ausencia, descripcion_ausencia from sw_con_apermisos_mat order by 1";
    $cn=Conectarse_sitt();
    $stmt1 = $cn->query($qsitt);
    $option1='';
    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
    {
       $option1.= "<option value='". $row['razon_ausencia']."'>" ;
       $option1.= $row['descripcion_ausencia']. "</option>"; 
    }
    /*unidades
(
  idunidad integer NOT NULL DEFAULT nextval('idunidad_seq'::regclass),
  descripcion_unidad character varying(100) NOT NULL,
  dependencia character varying(10),
  centro_costo character varying(10) NOT NULL,
  jefe_unidad character varying(10),
  CONSTRAINT unidad_key PRIMARY KEY (idunidad)*/

       
  if ($_SESSION['nivel_const']==1  || $acceso){
    $query="SELECT * FROM unidades  ORDER BY idunidad";
    $option2= "<option value='NULL'>Seleccione Centro Costo</option>";    
  }else
  {
    $query="SELECT * FROM unidades  WHERE jefe_unidad='".$_SESSION['user_session_const']."'";
    $option2='';
  }
  $result1 = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result1);
  if($numReg>0){
    
    while ($fila=ejecutar_fetch_array($result1)) 
    {
        $option2.= "<option value='". $fila['idunidad']."'>" ;
        $option2.= $fila['centro_costo']." - ".$fila['descripcion_unidad']. "</option>";
    }
  }
 
  $fecha_a =date("Y-m-d");
  $fecha_actual = date("Y-m-d",strtotime($fecha_a."- 1 days"));
  pg_free_result($result);
  pg_free_result($result1);
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Historico Permisos</title>

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
          url: "buscar_permisos_hist.php",
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
              url: "buscar_permisos_hist_excel.php",
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

//(*----------------------------------------------------------------------------*)
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
                <a class="navbar-brand" href="index.php">Permisos</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Historico de Permisos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consulta de Permisos de Periodos Pasados
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
<div class="col-lg-12">

<table width="90%">    
    <tr>
       <td width="7%"><label>Fecha Inicio:</label></td>
       <td width="1%"> &nbsp;</td>
       <td width="7%"><label>Fecha Fin:</label></td>
       <td width="1%"> &nbsp;</td>
       <td width="15%"><label>Trabajador:</label></td>
       <td width="1%"> &nbsp;</td>
       <td width="10%"><label>Tipo De Permiso:</label></td>
       <td width="1%"> &nbsp;</td>
       <td width="10%"><label>Centro Costo:</label></td>
       <td width="1%"> &nbsp;</td>
       <td width="5%"> &nbsp;</td>
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
       <td> <select name="cboccosto" id="cboccosto" data-size="5"class="selectpicker" data-live-search="true" >
             
            <?php echo $option2; ?>
            </select>
      </td>
       <td><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="consultar();"/></td>
       <td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a> </td>
    </tr>    
</table>
</div>
<p>&nbsp;</p>
<div id="capa1" class="col-lg-12">   
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

</body>

</html>
<?php
pg_close($link);
pg_close($link2); 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
