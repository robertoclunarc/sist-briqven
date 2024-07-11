<?php
 session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    require_once('funciones_var.php');
    $desde= isset($_POST["txtdesde"])?$_POST["txtdesde"]:date("Y-m-d",time()-3600);
    $hasta= isset($_POST["txthasta"])?$_POST["txthasta"]:date("Y-m-d",time()-3600);
    $cbotrabajador        = isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:"";
    $asistio              = isset($_POST['chkasistio']); //?$_POST['chkasistio']:false;
    $comision             = isset($_POST['chkcomision']); //?$_POST['chkcomision']:false;
    $sobretiempo          = isset($_POST['chksobretiempo']);//?$_POST['chksobretiempo']:false;
    $permiso              = $_POST['cbopermiso'];//?$_POST['cbopermiso']:false;
    $cambioturno          = isset($_POST['chkcambioturno']);
    $desde_1 		  = strtotime($desde);
    $hasta_1 		  = strtotime($hasta);

    if(($desde_1 > $hasta_1)) echo "La fecha Desde ".$desde." es mayor que la fecha hasta ".$hasta.", se obviara este filtro";
    $complemento="";
    if(($desde_1 < $hasta_1) || ($desde_1 == $hasta_1)) $complemento.= "and fecha between '".$desde."' and '".$hasta."'";
    if ($cbotrabajador!="" && $cbotrabajador>0) $complemento.=" and a.trabajador='". $cbotrabajador."'";
    if ($asistio=='S')              $complemento.=" and asistio='S'";
    if ($comision=="S")             $complemento.=" and comision='S'";
    if ($sobretiempo=="S")          $complemento.=" and sobre_tiempo='S'";
    if ($cambioturno=="on")         $complemento.=" and cambio_turno='S'";
    if ($permiso>1)                 $complemento.=" and inasistencia=".$permiso;
    if ($_SESSION['nivel_const']==3) $complemento.=" and supervisor='".$_SESSION['cedula_session_const']."'";
    //$query="SELECT a.*,b.nombres,b.apellidos,b.descripcion_gerencia FROM registro_diario a, v_trabajadores_activos b where a.trabajador=b.trabajador ".$complemento." ORDER BY fecha DESC";
    $query="SELECT a.*,b.nombre FROM registro_diario a, v_trabajadores_activos b where a.trabajador=b.trabajador ".$complemento." ORDER BY fecha DESC";
//print $query;    
$link=Conex_Contancia_pgsql();
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
    $numReg = ejecutar_num_rows($result);
    $i=0; $conta=0;$contc=0;$contsb=0;$contct=0;
    if($numReg>0){
          $tabla='<table width="100%" class="table table-striped table-bordered table-hover">';
          $tabla.='<thead>';
          $tabla.='<tr>';
          $tabla.='<th></th>';
          $tabla.='<th>Fecha</th>';
          $tabla.='<th>C&eacute;dula</th>';
          $tabla.='<th>Nombre</th>';
          $tabla.='<th align="center">Asisti&oacute;</th>';
          $tabla.='<th align="center">Sobre Tiempo</th>';
          $tabla.='<th align="center">Comisi&oacute;n</th>';
          $tabla.='<th align="center">Cambio Turno</th>';
          $tabla.='<th>Permiso</th>';
          $tabla.='<th>Observaci&oacute;n</th>';
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='<tbody>';
          $i++;          $total=0;          $debe=0;          $haber=0;
	  while ($reg = ejecutar_fetch_array($result)) {
                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td align="right">'.$i.'</td>';
                  $tabla.='<td>'.$reg['fecha'].'</td>';
                  $tabla.='<td>'.$reg['trabajador'].'</td>';
                  $tabla.='<td>'.$reg['nombre'].'</td>';
                  //$tabla.='<td>'.$reg['nombres'].' '.$reg['apellidos'].'</td>';
                  $tabla.='<td align="center">'.cambiar_S_X($reg['asistio']).'</td>';
                  $tabla.='<td align="center">'.cambiar_S_X($reg['sobre_tiempo']).'</td>';
                  $tabla.='<td align="center">'.cambiar_S_X($reg['comision']).'</td>';
                  $tabla.='<td align="center">'.cambiar_S_X($reg['cambio_turno']).'</td>';
                  $tabla.='<td>'.nombre_inasistencia($reg['inasistencia']).'</td>';
		  if ($reg['cambio_turno']=='S') 
                      $tabla.='<td>Vino en el turno: '.$reg['turno'].'<br>'.$reg['observacion'].'</td>';
		  else
                      $tabla.='<td>'.$reg['observacion'].'</td>';
                  $tabla.='</tr>';
                  $i++;
		  if ($reg['asistio']=='S') $conta++;
		  if ($reg['sobre_tiempo']=="S") $contsb++;
		  if ($reg['comision']=="S") $contc++;
		  if ($reg['cambio_turno']=="S") $contct++;
            }
	    if ($cbotrabajador>0){
	        $tabla.='<tr>';
            	$tabla.='<td colspan="4"><b>Total </b></td>';
	        $tabla.='<td align="center"><b>'.$conta.'</b></td>';
	        $tabla.='<td align="center"><b>'.$contsb.'</b></td> ';
	        $tabla.='<td align="center"><b>'.$contc.'</b></td>';
	        $tabla.='<td align="center"><b>'.$contct.'</b></td> ';
                $tabla.='<td>&nbsp;</th>';
                $tabla.='<td>&nbsp;</th> ';
	        $tabla.='</tr>';
	    }
            $tabla.='</tbody>';
          $tabla.='</table>';
            ejecutar_free_result($result);
	    ejecutar_close($link);
            echo $tabla;
    }else{
         echo "No hay registros que mostrar";
    }

}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
