<?php
 session_start();

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

//Conexion a la Base de Datos
//require("include_conex.php");
include("../BD/conexion.php");
require_once('funciones_var.php');


// require("enviodecorreos.php");

$desde = isset($_POST["txtdesde"])?$_POST["txtdesde"]:"NULL";    //
$hasta= isset($_POST["txthasta"])?$_POST["txthasta"]:"NULL";         //
$trabajador= $_POST["cbotrabajador"];
$permiso= isset($_POST["cbopermiso"])?$_POST["cbopermiso"]:"NULL";
$asistio= isset($_POST["chkasistio"])?$_POST["chkasistio"]:"NULL";
$comision= isset($_POST["chkcomision"])?$_POST["chkcomision"]:"NULL";
$sobretiempo= isset($_POST["chksobretiempo"])?$_POST["chksobretiempo"]:"NULL";
$cambioturno= isset($_POST["chkcambioturno"])?$_POST["chkcambioturno"]:"NULL";
$enviar= isset($_GET["enviar"])?$_GET["enviar"]:"NO";

$desde_1    = strtotime($desde);
$hasta_1    = strtotime($hasta);
$titulo     ="";
if(($desde_1 > $hasta_1)) echo "La fecha Desde ".$desde." es mayor que la fecha hasta ".$hasta.", se obviara este filtro";
$complemento="";
if(($desde_1 < $hasta_1) || ($desde_1 == $hasta_1)){ $complemento.= "and fecha between '".$desde."' and '".$hasta."'"; $titulo="desde: ".$desde.", hasta: ".$hasta; }
if ($trabajador!="" && $trabajador>0){ $complemento.=" and a.trabajador='". $trabajador."'";  $titulo="del trabajador: ".$trabajador; }
if ($asistio=="true" || $asistio=='on'){                 $complemento.=" and asistio='S'"; if ($titulo=="") $titulo= "del personal que asistio entre el ".$desde.", hasta=".$hasta; }
if ($comision=="true" || $comision=='on'){                $complemento.=" and comision='S'";}
if ($sobretiempo=="true" || $sobretiempo=='on'){             $complemento.=" and sobre_tiempo='S'";}
if ($cambioturno=="true" || $cambioturno=='on'){             $complemento.=" and cambio_turno='S'";}
if ($permiso>1){                       $complemento.=" and inasistencia=".$permiso;}
if ($_SESSION['nivel_const']==3){ $complemento.=" and supervisor='".$_SESSION['cedula_session_const']."'"; $titulo="de la oficina: ".nombre_gerencia($_SESSION['cedula_session_const']);}

$query="SELECT a.*,b.nombres,b.apellidos,b.descripcion_gerencia FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador ".$complemento." ORDER BY fecha DESC";
print $query;
$link=Conex_Contancia_pgsql();
$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
$numReg = ejecutar_num_rows($result);        
$i=0; 
if($numReg=='0'){      
   $inpt = "No se han encontrado resultados! ";
}else{
   $inpt='<table width="100%" class="table table-striped table-bordered table-hover">';
   $inpt.='<thead>';
   $inpt.='<tr>';
   $inpt.='<th></th>';
   $inpt.='<th>Fecha</th>';
   $inpt.='<th>C&eacute;dula</th>';
   $inpt.='<th>Nombre</th>';
   $inpt.='<th>Unidad Administrativo</th>';
   $inpt.='<th>Asisti&oacute;</th>';
   $inpt.='<th>Sobre Tiempo</th>';
   $inpt.='<th>Comisi&oacute;n</th>';
   $inpt.='<th>Cambio Turno</th>';
   $inpt.='<th>Permiso</th>';
   $inpt.='<th>Observaci&oacute;n</th>';
   $inpt.='</tr>';
   $inpt.='</thead>';
   $inpt.='<tbody>';
   $i++;
   $total=0;
   $debe=0;
   $haber=0;
   while ($reg = ejecutar_fetch_array($result)) {
          $inpt.='<tr class="gradeA">';
          $inpt.='<td></td>';
          $inpt.='<td>'.$reg['fecha'].'</td>';
          $inpt.='<td>'.$reg['trabajador'].'</td>';
          $inpt.='<td>'.$reg['nombres'].' '.$reg['apellidos'].'</td>';
          $inpt.='<td>'.$reg['descripcion_gerencia'].'</td>';
          $inpt.='<td>'.cambiar_S_X($reg['asistio']).'</td>';
          $inpt.='<td>'.cambiar_S_X($reg['sobre_tiempo']).'</td>';
          $inpt.='<td>'.cambiar_S_X($reg['comision']).'</td>';
          $inpt.='<td>'.cambiar_S_X($reg['cambio_turno']).'</td>';
          $inpt.='<td>'.nombre_inasistencia($reg['inasistencia']).'</td>';
          if ($reg['cambio_turno']=='S')
              $inpt.='<td>Vino en el turno: '.$reg['turno'].'<br>'.$reg['observacion'].'</td>';
          else
              $inpt.='<td>'.$reg['observacion'].'</td>';

          $inpt.='</tr>';
          $i++;
    }
    $inpt.='</tbody>';
    $inpt.='<thead>';
    $inpt.='<tr>';
    $inpt.='<th>&nbsp;</th>';
    $inpt.='<th>&nbsp;</th> ';
    $inpt.='</tr>';
    $inpt.='</thead>';
    $inpt.='</table>';
    echo $inpt;
    ejecutar_free_result($result);
    ejecutar_close($link);
} 
?>
