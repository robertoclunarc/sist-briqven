<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  $link=Conex_rrhh_pgsql();
  $link2=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link2, 'CARGAR_PERM', basename( __FILE__ ), $_SESSION['user_session_const']);
  if ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const']==2 || $acceso)  
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

    $qsitt="select  distinct a.Cod_Adam, c.descripcion_ausencia from SW_Permisos a, adam_codigo_ausencias c
where  a.cod_adam = c.codigo_hora and a.estado != 'B' order by 1";
    $cn=Conectarse_sitt();
    $stmt1 = $cn->query($qsitt);
    $option1='';
    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
    {
       $option1.= "<option value='". $row['Cod_Adam']."'>" ;
       $option1.= $row['Cod_Adam']." - ".$row['descripcion_ausencia']. "</option>"; 
    }

        $option2='';
       $option2.= "<option value='D'>D - Documentos</option>";
       $option2.= "<option value='A'>A - Autorizado</option>";
       $option2.= "<option value='E'>E - Espera</option>";
       $option2.= "<option value='S'>S - Aut. Sup.</option>";
       $option2.= "<option value='V'>V - Doc. y Aut. Sup.</option>";
       $option2.= "<option value='B'>B - Borrado</option>";
       $option2.= "<option value='L'>L - Listo</option>"; 

 /*$qsitt="select distinct estado from SW_Permisos where estado != 'B'";
    
    $stmt2 = $cn->query($qsitt);
    $option2='';
    $estado='';
    while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
    {
       
                    switch ($row2['estado']) {
                      case 'D': $estado='Documentos';
                        break;
                      case 'A': $estado='Autorizado';
                        break; 
                      case 'E': $estado='Espera';
                        break;
                      case 'S': $estado='Aut. Sup.';
                        break;
                      case 'V': $estado='Doc. y Aut. Sup.';
                        break;
                      case 'B': $estado='Borrado';
                        break;
                      case 'L': $estado='Listo';
                        break;
                      }

       $option2.= "<option value='".$row2['estado']."'>" ;
       $option2.= $row2['estado'].' - '.$estado. "</option>"; 
    }   
*/
      $fecha_a =date("Y-m-d");
      $fecha_actual = date("Y-m-d",strtotime($fecha_a."- 1 days"));
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Permisos</title>

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
function datos_trabajador(){
    $.ajax({
          type: "POST",
          url: "buscar_datos_trabajador_perm.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/loading.gif' /></p>");
                $("#capacodigo").empty();
                $("#txtcondicion").val("");
                $("#txtdocrequerido").val("");
                $("#hddubicacio").val("");
                $("#txtubicacion").val("");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                   
                eval(data);                
                $("#capa1").empty();                
                mostrar_codigos();
                $("#txthoraini").val("");
                $("#txthorafin").val("");                
                $("#hddalerta").val("");
          }
    });
}
//(*--------------------------------------------*)
function Ubicacion(){
    $.ajax({
          type: "POST",
          url: "buscar_ubicacionr_perm.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          
          success: function(data){                         
                eval(data);            
          }
    });
    if ($("#cbocodigo").val()!="NULL"){
      traer_info_causa($("#cbocodigo").val());
    }
    controlar_horas();
}
//(*--------------------------------------------*)
function mostrar_codigos(){
    var c1=$("#hddclasenomina").val();
    var c2=$("#hddrelacion_laboral").val();
    var c3=$("#hddturno").val();
    var dir="cargar_combo_sp.php?sp=SW_con_ausencias_posibles&cmp1="+c1+"&cmp2="+c2+"&cmp3="+c3;
    $.ajax({
          type: "POST",
          url: dir,
          data: $("#formulario").serialize(),
          dataType: "html",                    
          success: function(data){              
              $("#capacodigo").html(data);
              $("#txthoraini").val("");
              $("#txthorafin").val("");
              $("#hddalerta").val("");
          }
    });
}
//(*--------------------------------------------*)
function mostrar_esperanza(){
    var trabajador=$("#cbotrabajador").val();
    
    if (trabajador!="NULL"){ 
      var finicio=$("#txtfinicio").val();
      var ffin=$("#txtffin").val();
      var codper=$("#cbocodigo").val();
      var t=$("#hddsisthor").val();
      var dir="buscar_esperanza_trab.php?ci="+trabajador+"&finicio="+finicio+"&ffin="+ffin+"&codigoper="+codper+"&t="+t;
      $.ajax({
            type: "POST",
            url: dir,
            data: $("#formulario").serialize(),
            dataType: "html",
            beforeSend: function(){                
                $("#hddalerta").val("");
            },
            error: function(){
                alert("error petición ajax");
                $("#hddalerta").val("Error Calculando La Esperanza");
            }, 
            success: function(data){
                /*var cb = document.getElementById('txtfinicio');  
                cb.setAttribute("data-toggle", "modal");
                cb.setAttribute("data-target", "#exampleModalCenter");*/
                var arrayDeCadenas = data.split('-');
                if (arrayDeCadenas[0]=='horas'){
                   $("#txthoraini").val(arrayDeCadenas[1]);
                   $("#txthorafin").val(arrayDeCadenas[2]);
                   $("#hddalerta").val("");
                }
                else{
                      $("#capa2").empty();                               
                      $("#capa2").append(data);
                      $("#exampleModalCenter").modal("show");                   
                      $("#hddalerta").val(data);                   
                }
            }
      });
  }
  
}
//(*--------------------------------------------*)
function Guardar(){

  if ($("#cbotrabajador").val()=="NULL"){ 
    alert("Seleccione un Trabajador");               
    return;
  }
  if ($("#txtccosto").val()==""){
    alert("Datos del trabajador no fueron cargados correctamente");               
    return;
  }
  if ($("#cbocodigo").val()=="NULL"){
    alert("Seleccione la causa del permiso");                
    return;
  }

  if ($("#hddturno").val()=="" || $("#hddidcalendario").val()==""){
    alert("Error en la carga de la informacion. Por favor preciones F5 y vuelva a llenar los campos del permiso");                
    return;
  }

  if ($("#txtfinicio").val()==""){                
    return;
  }

  if ($("#txtffin").val()==""){        
    return;
  }

  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtffin").val()))
  {
    alert("La fecha inicial debe ser menor a la fecha final");    
    return;
  }

  if (fecha_ini_mayor($("#txtfinicio").val(), $("#txtppago").val()))
  {
    alert("La fecha inicial debe ser menor a la fecha de pago");    
    return;
  }

  if ($("#hddalerta").val()!=""){
    $("#capa2").empty();                               
    $("#capa2").append($("#hddalerta").val());
    $("#exampleModalCenter").modal("show");    
    return;
  }

$.ajax({
          type: "POST",
          url: "Valida_datos_permiso.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa2").html("Por favor Espere... &nbsp;<br><img width='60' height='60' src='images/loading.gif' />");
                $("#exampleModalCenter").modal("show");                
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
function traer_info_causa(codigo)
{     
    var input = document.getElementsByName('hddcodigoadam[]');
    var inputcond = document.getElementsByName('hddcond[]');
    var inputdoc = document.getElementsByName('hdddoc[]'); 
    var cond='';
    var doc='';
    for (var i = 0; i < input.length; i++) { 
        var a = input[i];        
        if (a.value==codigo)
        {          
          var c=inputcond[i];
          var d=inputdoc[i];          
          cond=c.value;
          doc=d.value;
        }        
    } 
    $("#txtcondicion").val(cond);
    $("#txtdocrequerido").val(doc);    
}
//(*--------------------------------------------*)
function control_pacial()
{
  document.getElementById("txthoraini").removeAttribute("readonly");
  document.getElementById("txthorafin").removeAttribute("readonly");  
  document.getElementById("optionParcial").removeAttribute("disabled");
  document.getElementById("optionTotal").removeAttribute("disabled");
  /*if ($("#txthoraini").val()!="" && $("#cbotrabajador").val()!="NULL" && $("#cbocodigo").val()!="0"){        
    mostrar_esperanza();
  }*/
}
//(*--------------------------------------------*)
function control_total()
{
  document.getElementById("txthoraini").setAttribute('readonly', true);
  document.getElementById("txthorafin").setAttribute('readonly', true);
  //$("#txthoraini").val("");
  ///$("#txthorafin").val("");  
}
//(*--------------------------------------------*)

function controlar_horas()
{
  
  var permisos_dia_completo = [32, 33, 34, 35, 36, 42, 43];
  var nro = parseInt($("#cbocodigo").val());
  var idx = permisos_dia_completo.indexOf(nro);

  if (idx>-1){
    //if ($("#txthoraini").val()!="" && $("#cbotrabajador").val()!="NULL" && $("#cbocodigo").val()!="0"){        
    //    mostrar_esperanza();
    //} 
    control_total();    
    $("#optionTotal").prop("checked", true);
    document.getElementById("optionParcial").setAttribute('disabled', true);
  }
  else{
    mostrar_esperanza();    
    //var check = document.getElementById("optionsRadiosInline2");
    $("#optionParcial").prop("checked", true);
    control_pacial();    
  }
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
        <nav class="navbar navbar-<?php echo $_SESSION['modeBlack_const']; ?> navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                    <h1 class="page-header">Permisos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos del Permiso
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
<div class="col-lg-12">

<table width="90%">    
    
    <tr>
       <td width="5%"><label>Trabajador:</label></td>
       <td width="15%"><div class="col-xs-10"><select onchange="datos_trabajador();" name="cbotrabajador" id="cbotrabajador" data-width="80%" data-size="5" class="selectpicker" data-live-search="true">
            <option selected value="NULL">Seleccione el trabajador</option>
            <?php echo $option; ?>
           </select></div>
      </td>     
      <td width="1%">
          <input  name='hddclasenomina' id='hddclasenomina' type='hidden' value=''/> 
          <input  name='hddturno' id='hddturno' type='hidden' value=''/>
          <input  name='hddsisthor' id='hddsisthor' type='hidden' value=''/>
          <input  name='hddrelacion_laboral' id='hddrelacion_laboral' type='hidden' value=''/> 
          <input  name='hddubicacion' id='hddubicacion' type='hidden' value=''/>         
          <input  name='hddcedtrabajadorsup' id='hddcedtrabajadorsup' type='hidden' value=''/>
          <input  name='hddccosto' id='hddccosto' type='hidden' value=''/>
          <input  name='hddpuesto' id='hddpuesto' type='hidden' value=''/>
          <input  name='hddidcalendario' id='hddidcalendario' type='hidden' value=''/>
          
          <input  name='hddalerta' id='hddalerta' type='hidden' value=''/>
      </td>       

      <td width="6%"><label>C.Costo:</label></td>
      <td width="15%"><div class="col-xs-10"><INPUT type="text" readonly id="txtccosto" maxlength="50" name="txtccosto" value="" width="100%" class="form-control"/></div></td>
      <td width="1%"> &nbsp;</td>   
      <td width="5%"><label>Cargo:</label></td>
      <td width="15%"><div class="col-xs-10"><INPUT type="text" readonly id="txtpuesto" maxlength="50" name="txtpuesto" value="" width="100%" class="form-control"/></div></td> 
      <td width="6%"> &nbsp;</td>  
      <td width="1%"> &nbsp;</td>      
    </tr>

    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>         
    </tr>

    <tr>
       <td><label>Trabajador Sup.:</label></td>
       <td><div class="col-xs-7"><INPUT type="text" readonly id="txttrabajadorsup" maxlength="50" name="txttrabajadorsup" value="" width="100%" class="form-control"/></div>
       </td>     
       <td> &nbsp;</td>
       <td><label>Periodo Pago:</label></td>
       <td><div class="col-xs-7"><INPUT type="text" readonly id="txtppago" maxlength="50" name="txtppago" value="" width="100%" class="form-control"/></div>
       </td>
       <td> &nbsp;</td>   
       <td><label>Causa:</label></td>
       <td><div id="capacodigo" class="col-xs-10"><select name="cbocodigo" id="cbocodigo" data-width="80%" data-size="5" onchange="controlar_horas()" class="selectpicker" data-live-search="true" >
             <option selected value="NULL">Seleccione la Causa</option>
            
            </select></div>
       </td>
       <td> &nbsp;</td>
       <td> &nbsp;</td> 
    </tr>

    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
    </tr> 

    <tr>
       <td><label>Disp. Remun:</label></td>
       <td><div class="col-xs-7"><INPUT type="text" readonly id="txtdisrem" maxlength="50" name="txtdisrem" value="" width="80%" class="form-control"/></div></td>
         <td> &nbsp;</td> 
       <td><label>Disp. No Remun:</label></td>
       <td><div class="col-xs-7"><INPUT type="text" readonly id="txtdisnorem" maxlength="50" name="txtdisnorem" value=""  width="80%" class="form-control"/></div></td>
         <td> &nbsp;</td> 
       <td><label>Ubicacion:</label></td>
       <td><div class="col-xs-10"><INPUT type="text" readonly name="txtubicacion" id="txtubicacion" onchange="Ubicacion()" class="form-control" ></div></td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
    </tr> 
    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>         
    </tr>  

    <tr>
      <td> &nbsp;</td>
      <td>
          <label class="radio-inline">
              <input type="radio" name="optionsRadiosInline" id="optionTotal" onclick="control_total()" value="Total"><label>Total</label>
          </label>
      </td>
       <td> &nbsp;</td>
       <td><label>F. Inicio:</label></td>
       <td><div class="col-xs-7"><INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange='mostrar_esperanza()' value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></div></td>
       <td> &nbsp;</td> 
       <td><label>F. Fin:</label></td>
       <td><div class="col-xs-7"><INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" onchange='mostrar_esperanza()' value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/></div></td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
    </tr> 

    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
    </tr>  

    <tr>
       <td> &nbsp;</td>
       <td><label class="radio-inline">
              <input type="radio" name="optionsRadiosInline" id="optionParcial" onclick="control_pacial()" checked value="Parcial"><label>Parcial</label>
          </label>
      </td> 
       <td> &nbsp;</td> 
       <td><label>H. Inicio:</label></td>
       <td><div class="col-xs-7"><INPUT type="time" maxlength="10" size="10" id="txthoraini" name="txthoraini" value="" width="10" class="form-control"/></div></td>
       <td> &nbsp;</td> 
       <td><label>H. Fin:</label></td>
       <td><div class="col-xs-7"><INPUT type="time" maxlength="10" size="10" id="txthorafin" name="txthorafin"  value="" width="10" class="form-control"/></div></td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>       
    </tr>

    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>         
    </tr>  

    <tr>
       <td><label>Condiciones:</label></td>
       <td colspan="7"><div class="col-xs-12"><INPUT type="text" readonly id="txtcondicion" maxlength="200" name="txtcondicion" value=""  width="80%" class="form-control"/></div></td> 
       <td> &nbsp;</td>  
      <td> &nbsp;</td>        
    </tr>

    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
    </tr>  

    <tr>
       <td><label>Doc. Requeridos:</label></td>
       <td colspan="7"><div class="col-xs-12"><INPUT type="text" readonly id="txtdocrequerido" maxlength="200" name="txtdocrequerido" value=""  width="80%" class="form-control"/></div></td>
      <td><label>Doc. Entregado?</label></td>
      <td><div class="checkbox">
              <label>
                  <input type="checkbox" id="chkDocEntregado" name="chkDocEntregado" value="S"> 
             </label>
          </div>
      </td>
    </tr>

    <tr>
      <td> &nbsp;</td>  
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td> 
      <td> &nbsp;</td>   
      <td> &nbsp;</td> 
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
    </tr>  

    <tr>
       <td><label>Observacion:</label></td>
       <td colspan="7"><div class="col-xs-12"><INPUT type="text" id="txtobservacion" maxlength="50" name="txtobservacion" value=""  width="80%" class="form-control"/></div></td>
       <td> &nbsp;</td>
      <td> &nbsp;</td>   
    </tr>
       
</table>
</div>

<div id="capa1" class="col-lg-12">   
</div>
<p>&nbsp;</p>  
      <table class="" width="90%" id="tblGuardar" align="center" border='0'>  
        <tr>
          <td width="30%">&nbsp;</td>
          <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Guardar"  class="btn btn-success" ondblclick="Guardar();"/></td>
          <td width="30%">&nbsp;</td>
        </tr>
      </table>

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
