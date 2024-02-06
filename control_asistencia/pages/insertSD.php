<?php
 session_start();
 //$hoy = date("Y-m-d");
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  require_once('funciones_var.php');
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=Conex_Contancia_pgsql();
  $link2=Conex_Contancia_pgsql();
  $cn=Conectarse_sitt();
  $acceso=permiso_usuario($link, 'CARGAR', 'insertcomision.php', $_SESSION['user_session_const']);
  //if ($_SESSION['nivel_const']==1 || $acceso){
      $query="SELECT trabajador, nombre FROM adam_vw_dotacion_briqven_02_mas order by nombre";
  //}elseif ($_SESSION['nivel_const']==2) {
  //    $query="SELECT trabajador, nombre FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup='".$_SESSION['cedula_session_const']."' order by nombre";
  //}   
  
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $option='';
    while ($fila=ejecutar_fetch_array($result)) 
    {
        $option.= "<option value='". $fila['trabajador']."'" ;
        $option.= ">". $fila['trabajador']." - ".$fila['nombre']. "</option>";
    }
  }
  
  $qryTipoSD="SELECT cod_suspension, Desc_suspension  FROM SW_causas_suspensiones_dis";
  $stmt1 = $cn->query($qryTipoSD);
  $contar = $stmt1->columnCount();
  $optionTipoSD='';
  while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
    $optionTipoSD.= "<option value='". $row['cod_suspension']."'" ;
    $optionTipoSD.= ">". $row['cod_suspension']." - ".$row['Desc_suspension']. "</option>";                      
} 
  

$qryTipoMedida="SELECT id_tipo_suspensiones, tipo_suspension FROM public.tipo_suspensiones;";
$result = ejecutar_query($link2, $qryTipoMedida) or die("Error en la Consulta SQL: ".$qryTipoMedida);
$numReg = ejecutar_num_rows($result);
if($numReg>0){
  $optionTipoMedida='';
  while ($fila=ejecutar_fetch_array($result)) 
  {
      $optionTipoMedida.= "<option value='". $fila['id_tipo_suspensiones']."'" ;
      $optionTipoMedida.= ">".$fila['tipo_suspension']. "</option>";
  }
}


  $fecha_a =date("Y-m-d");
  $fecha_actual = date("Y-m-d",strtotime($fecha_a."- 7 days"));
  pg_free_result($result);
  
?>
<!DOCTYPE html>
<html lang="es">

<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=SQL_Latin1_General_CP1_CI_AS">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cargar Medida Disciplinaria (SD).</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.css">
     
    <script src="../js/jquery-1.11.1.min.js"></script>
    

<script  language="javascript">

  //(*--------------------------------------------*)
  function validar_fecha_laborales(fecha){
    var hoy = new Date();
    var f = hoy;
    //alert(hoy+'=>'+fecha);
    if (fecha_ini_mayor(fecha,hoy)){
        alert('La fecha laborales no puede ser mayor a la fecha actual');
        hoy = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
        //alert(hoy+'=>'+fecha);
        $("#txtflaborales").val(hoy);
        //$("#txtffin").val(fecha); 
    }

  }
  //(*--------------------------------------------*)
  function fecha_fin(fecha){
      finicio = $("#txtfinicio").val();
      ffin    = $("#txtffin").val();
      tmedida = $("#cboTipoMed").val();
      //alert(fecha+'>'+finicio+'='+ffin+'('+tmedida+')');
      //finicio = $("#txtfinicio").val(fecha);

      //if (ffin<fecha){
      if (fecha_ini_mayor(finicio,fecha)){
          alert('La fecha fin no puede ser menor a la fecha de inicio de la medida');
          $("#txtffin").val(finicio);
      }

  }
  //(*--------------------------------------------*)
function fecha_inicio(fecha){
  finicio = $("#txtfinicio").val();
  ffin    = $("#txtffin").val();
  tmedida = $("#cboTipoMed").val();
  //alert(fecha+'>'+finicio+'='+ffin+'('+tmedida+')');
  finicio = $("#txtfinicio").val(fecha);

  //if (ffin<fecha){
  if (fecha_ini_mayor(fecha,ffin)){
      $("#txtffin").val(fecha);
  }else{
        if (tmedida==1 || tmedida==2)
            $("#txtffin").val(fecha);
      }

 }
//(*--------------------------------------------*)
function consultar(fecha){
  var dir="";
    if (fecha!=""){
        finicio=$("#txtfinicio").val();
        ffin=$("#txtffin").val();
        dir="buscar_consulta_SD.php?finicio="+finicio+"&ffin="+ffin;
    }else{
        dir="buscar_consulta_SD.php";
    }
    $("#hddnro").val('-1');
    $.ajax({
          type: "POST",
          url: dir,
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("<p align='center'><img src='images/loading.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                    
                $("#capa2").empty();
                $("#capa2").append(data);          
          }
    });
}

//(*--------------------------------------------*)
function fecha_ini_mayor(fechaini, fechafin){
var dat = false;
  if(new Date(fechaini).getTime() > new Date(fechafin).getTime())
  {
        dat= true;
  } 
  return dat;
}
//(*--------------------------------------------*)
function sumar_fecha(fecha,dias){
    var sfecha = new Date(fecha);    
    sfecha.setDate(sfecha.getDate() + dias);
    var day=sfecha.getDate();
    var month=sfecha.getMonth()+1;
    const year=sfecha.getFullYear();
    if(month < 10)
      month="0" + month.toString();
    if (day < 10)
      day="0" + day.toString();
    return year+"-"+month+"-"+day;
    
}

//(*--------------------------------------------*)
function graba(){

  if ($("#cbotrabajador").val()=="NULL"){
    alert("Debe Seleccionar un Trabajador");    
    return;
  }else{
    //var nombre_trabajador = $("#cbotrabajador").text();
    var combo = document.getElementById("cbotrabajador");
    var nombre_trabajador = combo.options[combo.selectedIndex].text;    
  } 

  if ($("#cboTipoMed").val()=="NULL"){
    alert("Debe Seleccionar un tipo de Medida"); 
     
    return;
  }else{
    var combo = document.getElementById("cboTipoMed");
    var TipoMedida = combo.options[combo.selectedIndex].text;   
  }

  if ($("#cboTipoCausal").val()=="NULL"){
    alert("Debe Seleccionar un tipo de Casual");    
    return;
  }

  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val())){
    alert("la fecha inicio es mayor a la fecha fin");    
    return;
  } 
  
  if (confirm('Esta seguro de registrar una '+TipoMedida+' a: '+nombre_trabajador)) {

    $.ajax({
          type: "POST",
          url: "SW_GRABA_SD.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img  src='images/loading.gif' /></p>");
                $("#capa3").empty();
          },
          error: function(request, error){
                console.log(arguments);
                //alert(" Can't do because: " + error);
                $("#capa3").append('<div class="alert alert-danger">error petición ajax</div>');
          },
          success: function(data){                                                    
                $("#capa1").empty();
                consultar(); 
                limpiar();
                if (data.indexOf("error")>=0){
                  $("#capa3").append('<div class="alert alert-danger">'+data+'</div>');
                } else {
                  if (data=="")
                    $("#capa3").append('<div class="alert alert-success">Trabajador correctamente  registrado en SD</div>');
                  else      
                    $("#capa3").append('<div class="alert alert-warning"> '+data+' </div>');
                }                          
          }
          
    });
  }
}
//(* ---------------------------------------------*)
function limpiar()
{  
  $("#hddnro").val('-1');
  $("#txtobservacion").val('');  
  $("#txtfinicio").val(formatoFechaActual(0));
  $("#txtffin").val(formatoFechaActual(0));
  $("#txtflaborales").val(formatoFechaActual(0));
  $("#txtfmedida").val(formatoFechaActual(0));
  
  $('#cboTipoCom').selectpicker('val', 'NULL');
  $('#cboTipoCom').selectpicker('refresh');

  $('#cboTipoMedida').selectpicker('val', 'NULL');
  $('#cboTipoMedida').selectpicker('refresh');
  $('#cbotrabajador').selectpicker('val', 'NULL');
  $('#cbotrabajador').selectpicker('refresh');
  consultar();
}
//(* ---------------------------------------------*)
function ver_comision(nro, cedula, inicio_comision, fin_comision, inicio_comision, Observaciones, cod_comision)
{
  $("#hddnro").val(nro);
  $("#txtobservacion").val(Observaciones);
  $("#txtfinicio").val(inicio_comision);
  $("#txtffin").val(fin_comision);
  $('#cboTipoCom').selectpicker('val', cod_comision);
  $('#cboTipoCom').selectpicker('refresh');
}
//(* ---------------------------------------------*)
function ShowSelected()
{
/* Para obtener el texto */
var combo = document.getElementById("cboTipoCausal");
var selected = combo.options[combo.selectedIndex].text;
$("#hddDescTipoCausal").val(selected);
}

//(* ---------------------------------------------*)
function ShowSelected2()
{
  /* Para obtener el texto */
  var combo = document.getElementById("cboTipoMed");
  var selected = combo.options[combo.selectedIndex].text;
  $("#hddDescTipoMed").val(selected);

  txtfmedida    = $("#txtfmedida").val();
  fecha_inicio(txtfmedida);
  cboTipoMed=$("#cboTipoMed").val();
  if (cboTipoMed==1 || cboTipoMed==2){
     $("#txtffin").val(txtfmedida);
  }    
}
//(* ---------------------------------------------*)
function formatoFechaActual(dias) {
    var today = new Date();
    today.setDate(today.getDate() + dias);
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
      dd = '0' + dd;
    }

    if (mm < 10) {
      mm = '0' + mm;
    }
    today = yyyy + '-' + mm + '-' + dd;
    return(today);

}
//(* ---------------------------------------------*)
/*
$(document).ready(function(){	
	$("#chksobretiempo").change(function(){
	        if ($('#chksobretiempo').is(':checked')){
                        document.getElementById("chkasistio").checked = true;
                        document.getElementById("chkcomision").checked = false;
	 		CargarCombo($("#cbopermiso"),"cargar_combo_array.php?combo=permiso");
	       	} 
	});
});
*/
//(*----------------------------------------------*)
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
                <a class="navbar-brand" href="index.php">Control Asistencia - Cargar Medida  Disciplinaria (SD)</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
           <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
           <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Cargar de Medida Disciplinaria</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos del Personal SD
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               <form role="form" id="formulario" name="formulario" method='post'> 
                                  <div class="col-lg-6">    
                                      <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_const']; ?>'/>
                                      <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_const']; ?>'/>
                                      <input  name='hddnro' id='hddnro' type='hidden' value='-1'/>

                                      <table borde="0" width="50%" class="table table-striped" >
                                        <tr>
                                            <th width="10%"><label>Trabajador:</label></th>
                                            <th colspan="3" width="80%"><select name="cbotrabajador" onchange="consultar('')" id="cbotrabajador" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
                                                    <option selected value="NULL">Seleccione el trabajador</option>
                                                          <?php echo $option; ?>
                                                    </select>
                                            </th>        
                                        </tr>
                                        <tr>
                                            <th width="10%"><label>Tipo Medida:</label></th>
                                            <th width="10%"><select name="cboTipoMed" id="cboTipoMed" data-width="100%" data-size="4" onchange="ShowSelected2();"  class="selectpicker">
                                                    <option  value="NULL">Sel. Tipo Medida</option>
                                                    <?php echo $optionTipoMedida; ?>      
                                                    </select><input  name='hddDescTipoMed' id='hddDescTipoMed' type='hidden' value=''/>
                                            </th>  
                                            <th width="10%"><label>Causal:</label></th>
                                            <th width="10%"><select name="cboTipoCausal" id="cboTipoCausal" data-width="100%" data-size="4" onchange="ShowSelected();" class="selectpicker">
                                                    <option  value="NULL">Sel. Causal</option>
                                                    <?php echo $optionTipoSD; ?>      
                                                    </select><input  name='hddDescTipoCausal' id='hddDescTipoCausal' type='hidden' value=''/>
                                            </th>      
                                        </tr>
                                        <tr>
                                              <th width="10%"><label>Fecha Laborales:</label></th>
                                              <th width="10%"><INPUT type="date" onchange="validar_fecha_laborales(this.value)" maxlength="10" size="10" id="txtflaborales" name="txtflaborales"  value="<?php echo $fecha_a; ?>" width="10"  class="form-control"/></th>
                                              <th width="10%"><label> Fecha Medida:</label></th>
                                              <th width="10%"><INPUT type="date"  onchange="fecha_inicio(this.value)" maxlength="10" size="10" id="txtfmedida" name="txtfmedida"  value="<?php echo $fecha_a; ?>" width="10" class="form-control"/></th>
                                        </tr>                  
                                        <tr>
                                              <th width="10%"><label>Fecha: Inicio</label></th>
                                              <th width="10%"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio"   value="<?php echo $fecha_a; ?>" width="10"  class="form-control"/></th>
                                              <th width="10%"><label>&nbsp;Fin</label></th>
                                              <th width="10%"><INPUT type="date"   maxlength="10" size="10" id="txtffin" name="txtffin" onchange="fecha_fin(this.value)" value="<?php echo $fecha_a; ?>" width="10" class="form-control"/></th>
                                        </tr>
                                        <tr> 
                                              <th width="10%"><label></label></th>
                                              <th width="10%"></th>
                                              <th width="10%"></th>
                                              <th width="10%">Trabajador Firm&oacute;: &nbsp;&nbsp;<input type="checkbox" id="firmo" name="firmo"></th>
                                        </tr>                    
                                        <tr>
                                            <th><label>Observaci&oacute;n:</label></th>
                                            <th colspan=3><textarea rows="4" cols="50" name="txtobservacion" class="form-control"></textarea></th>
                                            <!-- <th><INPUT type="text" maxlength="10" size="10" id="txtobservacion" name="txtobservacion" value="" width="10" class="form-control"/> -->
                                            </th>
                                        </tr> 
                              
                                        <tr>
                                              <th width="10%"><INPUT id="cmdGuardar" type="button" value="Aplicar"  class="btn btn-success" onclick="graba();"/></th>
                                              <th width="10%">
                                                <div id="capa1">                                       
                                                </div>
                                              </th>
                                              <th width="10%">&nbsp;</th>
                                              <th width="10%"><INPUT id="cmdLimpiar" type="button" value="Limpiar"  class="btn btn-info" onclick="limpiar();"/></th>
                                                              
                                        </tr>                  
                                      </table>						
                                  </div>
                                  <div id="capa3" class="col-lg-6"><p>&nbsp;</p></div>
                                   
                                      <div id="capa2" class="col-lg-12"></div>
                                     
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

    <script src="../bootstrap/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>

</body>

</html>
<?php
pg_close($link);
//pg_close($link2); 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
