 <?php
 session_start();
//if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    require_once('funciones_var.php');    

    //$link=crearConexion(); 
    $link=Conex_Contancia_pgsql(); 
    $desde= date("Y-m-d",time()-3600);
    $hasta= date("Y-m-d",time()-3600);
    //if ($_SESSION['nivel_const']==3)
    //    $_where=" where supervisor='".$_SESSION['cedula_session_const']."'";
    //else
    //    $_where="";
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


 /*   $query="SELECT trabajador, e_mail,  (nombres || ' ' || apellidos) as Nombre_Completo, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia FROM public.trabajadores_activos_con_jefes_1 ".$_where. " order by nombres";
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
*/

     $esperanza="";

     $fecha_actual = date("Y-m-d");
     $fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));
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
    <link href="../css/estilo.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
<!--<!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
-->

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

   <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">
   <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>
<!--
   <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
   <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
-->



<script  language="javascript">
//(*--------------------------------------------*)
function exportarPDF(uid){ 
    var login=$("#login").val();
    var ci=$("#txtCI").val();
    var desde=$("#txtdesde").val();
    var hasta=$("#txthasta").val();
    var trabajador=$("#cbotrabajador").val();
    var permiso=$("#cbopermiso").val();
    var asistio=$("#chkasistio").is(':checked');
    var comision=$("#chkcomision").is(':checked');
    var sobretiempo=$("#chksobretiempo").is(':checked');
    var cambioturno=$("#chkcambioturno").is(':checked');
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./fichadapdf.php?d='+desde+'&h='+hasta+'&t='+trabajador+'&p='+permiso+'&c='+comision+'&s='+sobretiempo+'&ct='+cambioturno+'&a='+asistio, "Planilla Examen Medico", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
        ventana.focus();
        $(document).ready(function(){
        $.blockUI();
        setTimeout($.unblockUI(),9999);
        }); 
     }
 } 



//(*--------------------------------------------*)
function buscar_trabajador(idtipoeqp,fecha)
{
 
 if (idtipoeqp!='')
    {
      url="cargar_esperanza.php?b=" + idtipoeqp + "&c="+fecha; 
      $.ajax(url).done(function(data)
       {   
          if (data != "VACIO"){
              eval(data);
          }
       }
      )
    }

}
//(*--------------------------------------------*)
    function ventanaAct(){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_clientes_cta_cobrar.php', "Buscar Cliente", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
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
function VerificarEnter()
{
 $("#hddfkcliente").val("");
 $("#txtrazon_social").val("");
 IrCuentas();  
}
//(*--------------------------------------------*)
function listar_permisos()
{             
//alert('paso');
  $.ajax({
        type: "POST",
        //url: "listado_asistencia.php",
        url: "listado_permisos.php",
        data: $("#formulario").serialize(),
        dataType: "html",
        beforeSend: function(){
              //imagen de carga
              $("#resultado").html("<p align='center'><img src='images/preloader-01.gif' /></p>");
        },
        error: function(){
              alert("error petición ajax");
        },
        success: function(data){               
              $("#resultado").empty();
              $("#resultado").append(data);
        }
  });
} 
//(*--------------------------------------------*)
function limpiar(){
    location.reload();  
 }
//(*--------------------------------------------*)
$(document).ready(function() {
       CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
        $("#cbopermiso").change(function(){
                alert($('#cbopermiso').val());
        });
});

//(*--------------------------------------------*)

function exportar_excel(){
    var login=$("#login").val();
    var ci=$("#txtCI").val();
    var desde=$("#txtdesde").val();
    var hasta=$("#txthasta").val();
    var trabajador=$("#cbotrabajador").val();
    var permiso=$("#cbopermiso").val();
    var asistio=$("#chkasistio").is(':checked');
    var comision=$("#chkcomision").is(':checked');
    var sobretiempo=$("#chksobretiempo").is(':checked');
    var cambioturno=$("#chkcambioturno").is(':checked');
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2); 
    var namexls="Registro_Horas_extras_cargadas_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'REGISTRO DE FICHADA: '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';
   $.ajax({
          type: "POST",
          url: "./fichadaexcel.php?enviar=NO",
          data: $("#formulario").serialize(),
          dataType: "html",
          success: function(data){
                $("#resultado").empty();
                $("#resultado").append(data);
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
function GuardarRegistro(){
$.ajax({
          type: "POST",
          url: "autorizar_horas_extras_db.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#resultado").html("<p align='center'><img src='images/loading.gif' /></p>");
	//	$("#resultado").html("<p align='center'><img src='images/preloader-01.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa1").empty();
                consultar();
                alert(data);                          
          }
    });
}

</script>

</head>

<body>
<header id="titulo">      
	<IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

         <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Control Asistencia - </a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reporte de Reposos Remunerados y No Remunerados</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data General
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" id="formulario" method='post'>
                            <div class="col-lg-12">
                            <table width="100%" class="table" border ="0">
                                <tr> 
                                        <th>Desde</th>  
                                        <th><input type="date" class="form-control" name="txtdesde" value="<?php echo date("Y-m-d",time()-3600); ?>" id="txtdesde" ></th>        
                                        <th>Hasta</th>
                                        <th><input type="date" class="form-control" name="txthasta" value="<?php echo date("Y-m-d",time()-3600); ?>" id="txthasta" ></th>
                                        <th>Trabajador</th>  
                                        <th><select name="cbotrabajador" id="cbotrabajador" data-width="80%" data-size="5" class="selectpicker" data-live-search="true">
                                                       <option selected value="0">Seleccione el trabajador</option>
							<?php echo $option; ?>
							</select>
                                        </th>
					<th><INPUT id="cmdGuardar" type="button" value="Consultar"  class="btn btn-primary" onclick="listar_permisos();"/></th>
					<!--<th><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a></th>-->
                                </tr>
                                <tr>        
				  <td colspan="6">
<!--				     <table border="0" width=100%>	                                        
                                        <td><INPUT title="Buscar" id="cmdAgregar" type="button" value="Q" class="btn btn-primary" onclick="listar_asistencia();" /></td>
                                        <td><INPUT title="Reiniciar Busqueda" id="cmdAgregar" type="button" value="#" class="btn btn-primary" onclick="limpiar();" /></td>
					<td><a onclick="exportar_excel()" title="Exportar Excel" href='#'><img src='../images/expor_excel1.png' WIDTH='30' HEIGHT='30'></a>
					    <A id="act'.$idc.'" onclick="exportarPDF('.$idc.')" title="Ver Detalles '.$reg['ci'].'" href="#"><IMG SRC="../images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A>
  					    <input id='login' name='login' type='hidden' value='<?php echo $_SESSION['user_session_ca']; ?>'/>
					</td>
				     </tr>
				   </table>
-->
				  </td>
				</tr>
                              </table>
                            </div>
                            <div id="resultado">    
                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                
                                <thead>
                                    <tr>
                                        <th>NumPermiso</th>
                                        <th>C&eacute;dula</th>
                                        <th>Nombres</th>
                                        <th>A&ntilde;o</th>
                                        <th>Cod_Adam</th>
                                        <th>descripcion_ausencia</th>
                                        <th>CentroCosto</th>
                                        <th>Desc_ccosto</th>
                                        <th>Inicio</th>
                                        <th>HoraIni</th>
                                        <th>Fin</th>
                                        <th>Neto</th>
                                        <th>fecha_proc</th>
                                        <th>Observaciones</th>
                                        <th>cargado</th>
                                    </tr>
                                
				</thead>
                                <tbody>                               
                      <?php 
                         $total=0;
                         $debe=0;
                         $haber=0;                   
			 //while ($reg = ejecutar_fetch_array($result)) {
                      ?>                 
                                </tbody>
                            </table>
                        </div>
                            <!-- /.table-responsive -->
                        </form>    
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
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
