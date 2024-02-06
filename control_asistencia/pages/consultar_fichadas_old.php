<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
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
  $esperanza="";
	
$fecha_actual = date("Y-m-d");
$fecha_a =date("Y-m-d",strtotime($fecha_actual."- 1 days"));

  ?>
<!DOCTYPE html>
<html lang="es">

<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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


<!--      <link href="../css/estilo.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">

     
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>

<script  language="javascript">
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
function ventanaAct(campo){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_usuarios.php?campo='+campo, "Buscar Usuarios", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
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
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
    if (tecla==48) return true;
    if (tecla==49) return true;
    if (tecla==50) return true;
    if (tecla==51) return true;
    if (tecla==52) return true;
    if (tecla==53) return true;
    if (tecla==54) return true;
    if (tecla==55) return true;
    if (tecla==56) return true;
    if (tecla==57) return true;
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
}
//(*--------------------------------------------*)
function limpiar_campos(){
  $("#cbotrabajador").val('');
  $("#txtentradaesperada").val("");   
  limpiar_hora();
  document.getElementById("chkcomision").checked = false;
  limpiar_esperanza();
}
//(* ---------------------------------------------*)
function limpiar_hora(){
  $("#txthora_entrada_real").val("");
  $("#txthora_salida_real").val("");   

  document.getElementById("chkasistio").checked = false;
  document.getElementById("chksobretiempo").checked = false;
  document.getElementById("chkcambioturno").checked = false;
  $("#txtobservacion").val("");   
 }
//(* ---------------------------------------------*)
function limpiar_esperanza(){
	var sel = document.getElementById("cboesperanza_cambiada");
        while (sel.length > 0) {
               sel.remove(sel.selectedIndex);
        }

 }

//(*-----------------------------------------------*)

$(document).ready(function(){
CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
 
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
                <a class="navbar-brand" href="index..php">Control Asistencia - Consultar Fichadas</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consultar Fichadas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del trabajador
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
                                  <div class="col-lg-6">    
                                       <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                       <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/> 
                                       <input  name='hddestatus' id='numreg' type='hidden' value='<?php echo $numReg; ?>'/> 
                                       <input  name='hddcontador' id='hddcontador' type='hidden' value='0'/>
<?php
# AQUI INICIA LA IMPRESION DEL LISTADO 
if(isset($_POST['submit'])) 
{ 
isset($_POST["hddcontador"])?$_POST["hddcontador"]:0;
                  $cbotrabajador        = isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:"";
		  $desde		= $_POST['txtfecha_d']; //?$_POST['txtfecha_d']:;		                  
		  $hasta		= $_POST['txtfecha_h']; //?$_POST['txtfecha_h'];		                  
		  $asistio              = isset($_POST['chkasistio']); //?$_POST['chkasistio']:false;
                  $comision    		= isset($_POST['chkcomision']); //?$_POST['chkcomision']:false;
                  $sobretiempo     	= isset($_POST['chksobretiempo']);//?$_POST['chksobretiempo']:false;
                  $permiso              = isset($_POST['cbopermiso']);//?$_POST['cbopermiso']:false;
                  $cambioturno          = isset($_POST['chkcambioturno']);
		  
		  $desde_1 = strtotime($desde);
		  $hasta_1 = strtotime($hasta);

		  if(($desde_1 > $hasta_1)) echo "La fecha Desde ".$desde." es mayor que la fecha hasta ".$hasta.", se obviara este filtro";



//print "<br>".$cbotrabajador." - ".$desde." <br>Hasta: ".$hasta." <br>Asistio: ".$asistio." <br>Comision:  ".$comision." <br>SobreTiempo: ".$sobretiempo." <br>Permiso: ".$permiso." <br>Cambio Turno: ".$cambioturno;
	  	$complemento="";
   		if(($desde_1 < $hasta_1) || ($desde_1 == $hasta_1)) $complemento.= "and fecha between '".$desde."' and '".$hasta."'";
   		if ($cbotrabajador!="" && $cbotrabajador>0) $complemento.=" and a.trabajador='". $cbotrabajador."'";
   		if ($asistio=='S') 	   	$complemento.=" and asistio='S'";
   		if ($comision=="S")     	$complemento.=" and comision='S'";
   		if ($sobretiempo=="S")  	$complemento.=" and sobre_tiempo='S'";
   		if ($cambioturno=="on")  	$complemento.=" and cambio_turno='S'";
   		if ($permiso>1)  		$complemento.=" and inasistencia=".$permiso;
    
		if ($_SESSION['nivel_const']==3) $complemento.=" and supervisor='".$_SESSION['cedula_session_const']."'";



    $listado="SELECT a.*,b.nombres,b.apellidos FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador ".$complemento." ORDER BY fecha DESC";
// print $listado;   
    $result = ejecutar_query($link,$listado);



?>

<div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Oper.</th>
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
                      ?>
                            <tr class="gradeA">
                                <td></td>
                                <td><?php echo $reg['fecha'];?></td>
                                <td><?php echo $reg['trabajador'];?></td>
                                <td><?php echo $reg['nombres'].' '.$reg['apellidos'];?></td>
                                <td><?php echo $reg['asistio'];?></td>
                                <td><?php echo $reg['sobre_tiempo'];?></td>
                                <td><?php echo $reg['comision'];?></td>
                                <td><?php echo $reg['cambio_turno'];?></td>
                                <td><?php echo nombre_inasistencia($reg['inasistencia']);?></td>
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
                            <table class="" width="90%" id="tblGuardar" align="center">  
                              <tr>
                                <td width="30%">&nbsp;</td>
                                <td width="40%" align="center"><a href="javascript:history.back(1)" class="btn btn-success">Volver Atrás</a></td>
                                <td width="30%">&nbsp;</td>
                             </tr>
                            </table>

                        </div>
                        <!-- /.panel-body -->
                    </div>



<?php 
}else{
?>
				        <table width="90%" class="table table-striped" border="0">
  					  <thead>
                                             <tr>
                                                  <th width="20%"><label>Desde:</label></th>
                                                  <th width="20%">
						     <table>
							<tr>
							   <th>
							<INPUT type="date" maxlength="10" size="10" id="txtfecha_d" name="txtfecha_d"  value="<?php echo $fecha_a ; ?>" width="80" class="form-control"/>
							   </th>
							</tr>
						     </table>
						  </th>
                                                  <th width="20%"><label>Hasta:</label></th>
                                                  <th width="20%">
						     <table>
							<tr>
							   <th>
							<INPUT type="date" maxlength="10" size="10" id="txtfecha_h" name="txtfecha_h"  value="<?php echo $fecha_a ; ?>" width="80" class="form-control"/>
							   </th>
							</tr>
						     </table>
						  </th>
                                            </tr>  
                                             <tr>
                                            	  <th width="20%"><label>Trabajador:</label></th>
                                                  <th width="80%">
							<select name="cbotrabajador" onchange="buscar_trabajador($('#cbotrabajador').val(),$('#txtfecha').val());" id="cbotrabajador" data-width="80%" data-size="5"class="selectpicker" data-hide-disabled="false" data-live-search="true">
                                              			<option selected value="0">Seleccione el trabajador</option>
                                              			<?php echo $option; ?>
							</select>
                                            </tr>                                              
					    <tr>
						<td colspan="4">
						    <table width="100%" border="0">
							<tr>
							    <th width="10%"><label>Asisti&oacute;: </label></th>
        	                                            <th width="10%"><input type="checkbox" name="chkasistio" id="chkasistio" class="custom-control-input" value='S'></th>
							    <th width="25%"><label>Comisi&oacute;n de Servicio: </label></th>
        	                                            <th width="10%"><input type="checkbox" name="chkcomision" id="chkcomision" class="custom-control-input" value='S'></th>
                	                                    <th width="20%"><label>Horas extras: </label></th>
                        	                            <th width="10%"><input type="checkbox" name="chksobretiempo" id="chksobretiempo" class="custom-control-input" value='S'> </th>
						        </tr> 
					             </table>
						</td>
					    </tr>
					    <tr>
						<th width="25%"><label>Ausencia:</label></th>
                                                <th width="90%" colspan='3'><select id="cbopermiso" name="cbopermiso" onchange="limpiar_hora();"  data-width="80%" data-size="5" data-hide-disabled="false" data-live-search="true" > </select></th>
				 	    </tr>
                                            <tr>
                                                <th width="100%" colspan="4">
	    					   <table width="100%"border = "0">
                                       	     		<tr>
                                               		    <th width="25%" colspan="4">
                                                  		<table border="0" width="90%">
						    			<tr>
						       				<th width="25%"><label>Cambio de turno:</label></th>
                        	                       				<th width="10%"><input type="checkbox" class="custom-control-input" id="chkcambioturno" name="chkcambioturno"> </th>
                                                       				<th><select id="cboesperanza_cambiada"> </select></th>
 						     			</tr>
                                                  		</table>
                                               		    </th>
                                            		</tr>
                                        	   </table>
						</th>
					    </tr>
                                      </div>
                                       <p>&nbsp;</p>  
                                      <table class="" width="90%" id="tblGuardar" align="center">  
                                        <tr>
                                          <td width="30%">&nbsp;</td>
                                          <td width="40%" align="center"><INPUT id="submit" name="submit" type="submit" value="Consultar" class="btn btn-success"/></td>
                                          <td width="30%">&nbsp;</td>
                                        </tr>
                                      </table>
                                      <p>&nbsp;</p>
                                    </form>
                            </div>
                            <!-- /.row (nested) -->
                        </div>


<?php
}
 ?>
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
