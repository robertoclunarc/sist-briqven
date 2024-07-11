<?php
 session_start();
if (isset($_SESSION['user_session_const'])){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  //$link=Conex_rrhh_pgsql();
  $link2=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link2, 'CARGAR_PERM', basename( __FILE__ ), $_SESSION['user_session_const']);
  
  $NumPermiso='0';
  $idausencia='';
  $cedula='NULL';
  $nombres='';
  $PeriodoPago='';
  $fechaPago='';
  $cod_adam='';
  $CentroCosto='';
  $desc_ccosto='';
  $Inicio='';
  $HoraIni='';
  $Fin='';
  $Horafin='';
  $Neto='';
  $fecha_proc='';
  $Observaciones='';
  $cargado='';
  $estado='';
  $tipo_notificacion='AUSENCIA';
  $clasenom=''; 
  $turno=''; 
  $cuadrilla=''; 
  $relacion_laboral=''; 
  $cod_ubicacion=''; 
  $autorizado='';
  $autorizado2=''; 
  $cod_cargo='';
  $desc_puesto='';
  $descripcion_ausencia='';
  $trabajador_sup='';
  $nombre_sup='';
  $remun = '';
  $nremun = '';
  $Desc_ubicacion='';
  $condiciones='';
  $documentos='';
  $total_parcial='';
  $docs = '';
  $nuevo='N';
  $option2='';
  $readOnlyText="readonly";
  $disabledOption="disabled";

  if ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const']==2 || $acceso) { 
    if (isset($_GET['idausencia'])){
      $link6=Conex_rrhh_pgsql();
      $qry="SELECT a.NumPermiso, a.idausencia, a.cedula, b.nombre as nombres, a.PeriodoPago, a.cod_adam, a.CentroCosto as CentroCosto, b.detalle_ccosto as desc_ccosto, a.Inicio, a.HoraIni, a.Fin, a.Horafin, a.Neto, a.fecha_proc, a.Observaciones, a.cargado, a.estado,a. tipo_notificacion, a.clasenom, a.turno, a.cuadrilla, a.relacion_laboral, a.cod_ubicacion, a.autorizado, a.cod_cargo, b.desc_puesto, b.trabajador_sup, a.total_parcial, a.docs FROM SW_Permisos a LEFT JOIN  adam_vw_dotacion_briqven_02_mas b ON a.cedula = b.trabajador::int WHERE a.idausencia=".$_GET['idausencia'];
      
      $result = pg_query($link2,$qry);
      $contar= pg_num_rows($result);
      if ($contar>0){
        $row = pg_fetch_array($result);
      
        $NumPermiso=$row['numpermiso'];
        $idausencia=$row['idausencia'];
        $cedula=$row['cedula'];
        $nombres=$row['nombres'];
        $PeriodoPago=$row['periodopago'];
        $rowPago=periodo_abierto($link6, $PeriodoPago);
        $fechaPago = $rowPago['fecha_pago'];
        $cod_adam=$row['cod_adam'];
        $CentroCosto=$row['centrocosto'];
        $desc_ccosto=$row['desc_ccosto'];
        $Inicio=$row['inicio'];
        $HoraIni=$row['horaini'];
        $Fin=$row['fin'];
        $Horafin=$row['horafin'];
        $Neto=$row['neto'];
        $fecha_proc=$row['fecha_proc'];
        $Observaciones=$row['observaciones'];
        $cargado=$row['cargado']; 
        $estado=$row['estado'];
        $tipo_notificacion=$row['tipo_notificacion'];
        $clasenom=$row['clasenom']; 
        $turno=$row['turno']; 
        $cuadrilla=$row['cuadrilla']; 
        $relacion_laboral=$row['relacion_laboral']; 
        $cod_ubicacion=$row['cod_ubicacion']; 
        $autorizado=$row['autorizado'];
        $autorizado2=$row['autorizado2'];
        $cod_cargo=$row['cod_cargo'];
        $desc_puesto=$row['desc_puesto'];
        $trabajador_sup=$row['trabajador_sup'];
        $nombre_sup=nombre_trabajadores($autorizado);
        $total_parcial = $row['total_parcial'];
        $docs=$row['docs'];
        $nuevo='F';
        $option="<option selected value='".$cedula."'>".$cedula." - ".$nombres."</option>";
        
        pg_free_result($result);

        if ($estado=='S' || $estado=='D'){
          $readOnlyText="";
          $disabledOption="";
        }

        $cn3=Conectarse_sitt();
      
        $tp = $tipo_notificacion === 'PERMISO' ? 1 : 0;
        $stmt3 = $cn3->prepare("EXEC SW_con_ausencias_posibles $clasenom, $relacion_laboral, $turno, $tp");
        $stmt3->execute();
        $ausencias = array();
        while ($fila = $stmt3->fetch()) {
          $ausencias[] = $fila;   
        }

        $stmt3 = $cn3->prepare("EXEC dbo.SW_con_datos_basicos_persona_permiso ?");
        $stmt3->bindParam(1, $cedula, PDO::PARAM_INT,10);
        $stmt3->execute();
        while ($fila = $stmt3->fetch()) {
            $remun = $fila['remun'];
            $nremun = $fila['nremun'];
        }

        $stmt3 = $cn3->prepare("EXEC dbo.SW_con_tiempo_ubicacion ?, ?, ?, ?");
        $stmt3->bindParam(1, $cod_adam, PDO::PARAM_INT,10);
        $stmt3->bindParam(2, $relacion_laboral, PDO::PARAM_STR,1);
        $stmt3->bindParam(3, $clasenom, PDO::PARAM_STR,2);
        $stmt3->bindParam(4, $turno, PDO::PARAM_INT,10);
        $stmt3->execute();

        while ($fila = $stmt3->fetch()) {        
          $Desc_ubicacion=$fila['Desc_ubicacion'];              
        }  
      
        $descripcion_ausencia=buscarValor($ausencias, 'Cod_Adam', $cod_adam, 'DESC_COD_HORA');
        $condiciones=buscarValor($ausencias, 'Cod_Adam', $cod_adam, 'Condiciones');
        $documentos=buscarValor($ausencias, 'Cod_Adam', $cod_adam, 'Documentos');
        $option2="<option selected value='".$cod_adam."'>".$descripcion_ausencia."</option>";
        $cn3=null;
        $stmt3=null;
      }
      pg_close($link6);


    }else{

      $option=llenar_combo_trabajadoresTodos($link2);
    }    
  }
  else{
        
      $option=llenar_combo_trabajadores_del_supervisor();
  }  

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
  if ($("#hddclasenomina").val()!='' && $("#hddrelacion_laboral").val()!='' && $("#hddturno").val()!=''){
    var c1=$("#hddclasenomina").val();
    var c2=$("#hddrelacion_laboral").val();
    var c3=$("#hddturno").val();
    var c4 = $("#rdPermiso1").is(":checked") ? "1" : "0";
    
    var dir="cargar_combo_sp.php?sp=SW_con_ausencias_posibles&cmp1="+c1+"&cmp2="+c2+"&cmp3="+c3+"&cmp4="+c4;
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
}

//(*--------------------------------------------*)
function cambioPeriodo() {
  if ($("#txtppago").val() != '') {
    var fechaPer = $("#txtppago").val();
    
    var dir = "buscar_calendario.php?fecha=" + fechaPer;
    $.ajax({
      type: "POST",
      url: dir,
      data: $("#formulario").serialize(),
      dataType: "html",                    
      success: function(data) {
        try {
          console.log("Datos recibidos del servidor: ", data); // Añadir este log para ver los datos recibidos
          
          // Convertir el string JSON a un objeto
          var periodo = JSON.parse(data);
          
          // Acceder a la propiedad id_calendario
          $("#hddidcalendario").val(periodo.id_calendario);
        } catch (e) {
          console.error("Error al parsear el JSON: ", e);
          alert("Hubo un problema al procesar la respuesta del servidor.");
        }
      },
      error: function(xhr, status, error) {
        // Manejo de errores
        console.error("Error en la solicitud AJAX:", status, error);
        alert("Error en la solicitud AJAX: " + status + " " + error);
      }
    });
  } else {
    alert("Por favor ingrese una fecha.");
  }
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
    alert("Algo NO esta bien!");
    $("#capa2").empty();                               
    $("#capa2").append($("#hddalerta").val());
    $("#exampleModalCenter").modal("show");    
    return;
  }

var urlProc = "";
var checkPermiso = document.getElementById("rdPermiso1");
if (checkPermiso.checked) {
  urlProc = "Valida_datos_permiso.php";
}else{
  urlProc = "Valida_datos_ausencia.php";
}

$.ajax({
          type: "POST",
          url:  urlProc,
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
                   $("#hddNuevo").val('F');
                   $("#capa2").empty();                               
                   $("#capa2").append(data);                                                        
          }
    });

}
//(*--------------------------------------------*)
function irConsultar(){   
    window.location.href = `consultar_permisos.php`;
}
//(*--------------------------------------------*)
function fecha_ini_mayor(fechaini, fechafin){
  var dat = false;
  var ini=new Date(fechaini).getTime();
  var fin=new Date(fechafin).getTime();
  
  if(ini > fin)
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
$(document).ready(function(){
    
    $("#exampleModalCenter").click(function(){
        $("#exampleModalCenter").modal("hide");
    });
    $("#exampleModalCenter").on('hidden.bs.modal', function () {
            irConsultar();
    });

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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Permisos/Ausencias</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header">Permisos/Ausencias</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reportar Permiso o Ausencia <?php if ($idausencia!='') echo ' Nro.'.$idausencia; ?>
                            <?php 
                              switch ($estado) {
              
                                case 'A': $estadoView='Autorizado'; $clase="label label-success";
                                  break;
                                case 'B': $estadoView='Borrado'; $clase="label label-danger";
                                  break;                   
                                case 'D': $estadoView='Documentos'; $clase="label label-danger";
                                  break;  
                                case 'E': $estadoView='Espera'; $clase="label label-warning";
                                  break;
                                case 'L': $estadoView='Listo'; $clase="label label-success";
                                  break;  
                                case 'S': $estadoView='Aut. por Superv.'; $clase="label label-info";
                                  break;
                                case 'V': $estadoView='Doc. y Aut. Sup.'; $clase="label label-danger";
                                  break;
                                case 'W': $estadoView='Aprobado'; $clase="label label-info";
                                  break;  
                                default:
                                  $estadoView=''; $clase="";
                              }

                            ?>
                             &nbsp; <span class="<?php echo $clase; ?>"><?php echo $estadoView; ?></span>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                               
                                    <form role="form" id="formulario" method='post'> 
<div class="container-fluid">

    <div class="row" style="margin-bottom: 15px;">
      <div class="col-xs-1"> 
        
          <input class="form-check-input" type="radio" name="rdPermiso" id="rdPermiso1" value="PERMISO" onchange="mostrar_codigos()" <?php if($tipo_notificacion==='PERMISO'){ echo 'checked';}else{ if($nuevo!='N'){ echo "disabled"; } } ?> >
          <label class="form-check-label" for="rdPermiso1">
            Permiso
          </label>
      </div>
      <div class="col-xs-1">  
          <input class="form-check-input" type="radio" name="rdPermiso" id="rdPermiso2" value="AUSENCIA" onchange="mostrar_codigos()" <?php if($tipo_notificacion==='AUSENCIA'){ echo 'checked';}else{ if($nuevo!='N'){ echo "disabled"; } } ?>>
          <label class="form-check-label" for="rdPermiso2">
            Ausencia
          </label>
      </div>
    </div>   
    
    <div class="row" style="margin-bottom: 15px;">
       <div class="col-xs-4">
          <label class="label">Trabajador:</label>
          <select onchange="datos_trabajador();" name="cbotrabajador" id="cbotrabajador" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
            <?php if($cedula=='NULL'){ ?>
                    <option selected value="NULL">Seleccione el trabajador</option>
            <?php  } echo $option; ?>
           </select>
       </div>     
      
          <input  name='hddclasenomina' id='hddclasenomina' type='hidden' value='<?php echo $clasenom; ?>'/> 
          <input  name='hddturno' id='hddturno' type='hidden' value='<?php echo $turno; ?>'/>
          <input  name='hddsisthor' id='hddsisthor' type='hidden' value='<?php echo $cuadrilla; ?>'/>
          <input  name='hddrelacion_laboral' id='hddrelacion_laboral' type='hidden' value='<?php echo $relacion_laboral; ?>'/> 
          <input  name='hddubicacion' id='hddubicacion' type='hidden' value='<?php echo $cod_ubicacion; ?>'/>         
          <input  name='hddcedtrabajadorsup' id='hddcedtrabajadorsup' type='hidden' value='<?php echo $autorizado; ?>'/>
          <input  name='hddccosto' id='hddccosto' type='hidden' value='<?php echo $CentroCosto; ?>'/>
          <input  name='hddpuesto' id='hddpuesto' type='hidden' value='<?php echo $cod_cargo; ?>'/>
          <input  name='hddidcalendario' id='hddidcalendario' type='hidden' value='<?php echo $PeriodoPago; ?>'/>
          <input  name='hddNuevo' id='hddNuevo' type='hidden' value='<?php echo $nuevo; ?>'/>
          <input  name='hddIdAusencia' id='hddIdAusencia' type='hidden' value='<?php echo $idausencia; ?>'/>
          <input  name='hddalerta' id='hddalerta' type='hidden' value=''/>
          <input  name='hddnpermiso' id='hddnpermiso' type='hidden' value='<?php echo $NumPermiso; ?>'/> 
          <input  name='hddEstado' id='hddEstado' type='hidden' value='<?php echo $estado; ?>'/>
          <input  name='hddAutorizado2' id='hddAutorizado2' type='hidden' value='<?php echo $autorizado2; ?>'/>
      <div class="col-xs-4">
          <label class="label">C.Costo:</label>
          <INPUT type="text" readonly id="txtccosto" maxlength="50" name="txtccosto" value="<?php echo $desc_ccosto; ?>" width="100%" class="form-control"/>
      </div> 
            
    </div>

    <div class="row" style="margin-bottom: 15px;">
      <div class="col-xs-4">
          <label class="label">Trabajador Sup.:</label>
          <INPUT type="text" readonly id="txttrabajadorsup" maxlength="50" name="txttrabajadorsup" value="<?php echo $nombre_sup; ?>" width="100%" class="form-control"/>
      </div>
      <div class="col-xs-4">
          <label class="label">Cargo:</label>
          <INPUT type="text" readonly id="txtpuesto" maxlength="50" name="txtpuesto" value="<?php echo $desc_puesto; ?>" width="100%" class="form-control"/>
      </div>              
        
    </div>

    <div class="row" style="margin-bottom: 15px;">
      <div class="col-xs-4">
          <label class="label">Causa:</label>
          <div id="capacodigo">
            <select onchange="controlar_horas();" name="cbocodigo" id="cbocodigo" data-width="100%" data-size="5" class="selectpicker" data-live-search="true">
                <?php if($cedula!='NULL'){ echo $option2; } else {  ?>
                      <option selected value="NULL">Seleccione la Causa</option>                    
                <?php  } ?>
            </select>
          </div>          

       </div>
       <div class="col-xs-4">
          <label class="label">Ubicacion:</label>
          <INPUT type="text" readonly name="txtubicacion" id="txtubicacion" onchange="Ubicacion()" value="<?php echo $Desc_ubicacion; ?>" class="form-control" >
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px;">      
       
      <div class="col-xs-2">
          <label class="label">F. Inicio:</label>
          <INPUT type="date" maxlength="10" size="10" id="txtfinicio" name="txtfinicio" onchange='mostrar_esperanza()' value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/>
      </div>
        
      <div class="col-xs-2">
          <label class="label">F. Fin:</label>
          <INPUT type="date" maxlength="10" size="10" id="txtffin" name="txtffin" onchange='mostrar_esperanza()' value="<?php echo $fecha_a ; ?>" width="10" class="form-control"/>
      </div>

      <div class="col-xs-1" style="padding-top: 20px;">          
            <input type="radio" name="optionsRadiosInline" id="optionTotal" onclick="control_total()" <?php if($total_parcial==='T') echo 'checked'; ?> value="Total"><label class="form-check-label" for="optionTotal">Total</label>          
      </div>
      <div class="col-xs-2" style="padding-top: 20px;">          
            <input type="radio" name="optionsRadiosInline" id="optionParcial" onclick="control_pacial()" <?php if($total_parcial==='P' || $cedula==='NULL') echo 'checked'; ?> value="Parcial"><label class="form-check-label" for="optionParcial">Parcial</label>         
      </div>
       
    </div>

    <div class="row" style="margin-bottom: 15px;">         
        
        <div class="col-xs-2">
          <label class="label">H. Inicio:</label>
          <INPUT type="time" maxlength="10" size="10" id="txthoraini" name="txthoraini" value="<?php echo $HoraIni; ?>" width="10" class="form-control"/>
        </div>
        
        <div class="col-xs-2">
          <label class="label">H. Fin:</label>
          <INPUT type="time" maxlength="10" size="10" id="txthorafin" name="txthorafin"  value="<?php echo $Horafin; ?>" width="10" class="form-control"/>
        </div>

        <div class="col-xs-2">
          <label class="label">Periodo Pago:</label>
          <INPUT type="date" id="txtppago" maxlength="10" onchange="cambioPeriodo()" name="txtppago" value="<?php echo $fechaPago; ?>" width="100%" <?php echo $readOnlyText; ?> class="form-control"/>
        </div>
        <div class="col-xs-1">
          <label class="label">Disp. Remun:</label>
          <INPUT type="text" readonly id="txtdisrem" maxlength="50" name="txtdisrem" value="<?php echo $remun; ?>" width="50%" class="form-control"/>
        </div>
          
        <div class="col-xs-1">
          <label class="label">No Remun:</label>
          <INPUT type="text" readonly id="txtdisnorem" maxlength="50" name="txtdisnorem" value="<?php echo $nremun; ?>"  width="50%" class="form-control"/>
        </div>
              
    </div>

    <div class="row" style="margin-bottom: 15px;">
       <div class="col-xs-8">
          <label class="label">Condiciones:</label>
          <INPUT type="text" readonly id="txtcondicion" maxlength="200" name="txtcondicion" value="<?php echo $condiciones; ?>" width="80%" class="form-control"/>
        </div>         
               
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-xs-8">
          <label class="label">Doc. Requeridos:</label>
          <INPUT type="text" readonly id="txtdocrequerido" maxlength="200" name="txtdocrequerido" value="<?php echo $documentos ;?>" width="80%" class="form-control"/>       
          <label for="chkDocEntregado">Doc. Entregado?</label>          
          <input <?php echo $disabledOption; ?> type="checkbox" id="chkDocEntregado" <?php if($docs==='S') echo 'checked';?> name="chkDocEntregado" value="S">          
       
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px;">
       <div class="col-xs-8">
          <label class="label">Observacion:</label>
          <INPUT type="text" id="txtobservacion" maxlength="50" name="txtobservacion" value="<?php echo $Observaciones ;?>" width="80%" class="form-control"/>
        </div>         
    </div>

    <div class="row">
      <div id="tblGuardar" class="col-lg-6"> 
        <INPUT id="cmdGuardar" type="button" value="Confirmar" title="Guardar y/o Confirmar Permiso" class="btn btn-success" onclick="Guardar();"/>          
      </div>

      <div id="capa1" class="col-lg-6"></div>
    </div>

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
        <button type="button" class="btn btn-secondary" onclick="irConsultar()" data-dismiss="modal">Cerrar</button>
        
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
//pg_close($link);
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
