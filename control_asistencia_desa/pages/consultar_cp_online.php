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
    $desde_1 		  = strtotime($desde);
    $hasta_1 		  = strtotime($hasta);

    if(($desde_1 > $hasta_1)) echo "La fecha Desde ".$desde." es mayor que la fecha hasta ".$hasta.", se obviara este filtro";
    $complemento="";
    if(($desde_1 < $hasta_1) || ($desde_1 == $hasta_1)) $complemento.= "and fecha between '".$desde."' and '".$hasta."'";
    if ($cbotrabajador!="" && $cbotrabajador>0) $complemento.=" and a.trabajador='". $cbotrabajador."'";
    if ($_SESSION['nivel_const']==3) $complemento.=" and trabajador_sup in (".lista_trabajadores('ct').")";
    $query="SELECT a.*,b.nombre FROM registro_diario a, adam_vw_dotacion_briqven_02_mas b where a.trabajador=b.trabajador and cambio_permanencia='S' ".$complemento."  AND nivel_jerarquico>='7' and bloqueado=0 ORDER BY fecha DESC";
    //print $query;    
    $link=Conex_Contancia_pgsql();
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
    $numReg = ejecutar_num_rows($result);
    $i=0; $conta=0;$contc=0;$contsb=0;$contct=0;
    if($numReg>0){
          $tabla='<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
          $tabla.='<thead>';
          $tabla.='<tr>';
          $tabla.='<th></th>';
 //         $tabla.='<th><input type="checkbox" class="form-control" id="cbox1" value="first_checkbox" placeholder="Marcar Todo"></th>';
          $tabla.='<th>Fecha</th>';
          $tabla.='<th>C&eacute;dula</th>';
          $tabla.='<th>Nombre</th>';
          $tabla.='<th align="center">Ent. Real1</th>';
          $tabla.='<th align="center">Sal. Real1</th>';
          $tabla.='<th align="center">Ent. Esperada1</th>';
          $tabla.='<th align="center">Sal. Esperada1</th>';
//          $tabla.='<th align="center">Cambio Turno</th>';
//          $tabla.='<th align="center">Turno que asistio</th>';
          $tabla.='<th>Motivo</th>';
          $tabla.='<th>Aprobada</th>';
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='<tbody>';
          $i++;          $total=0;          $debe=0;          $haber=0;
	  while ($reg = ejecutar_fetch_array($result)) {
                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td align="right">'.$i.'</td>';
//                  $tabla.='<td width="40"><input type="checkbox" class="form-control" name="autorizado[]" id="autorizado[]" value="'.$reg['trabajador']."*".$reg['fecha']."*".$reg['nombre']."*".$reg['entrada_real1']."*".$reg['salida_real1']."*".$reg['entrada_real2']."*".$reg['salida_real2']."*".$reg['motivo_ct'].'*'.'*'.$reg['accion_ct'].'" onclick="check()"></input></td>';
                  $tabla.='<td>'.$reg['fecha'].'</td>';
                  $tabla.='<td><input type="hidden" name="cedula[]" value="'.$reg['trabajador'].'"></input>'.$reg['trabajador'].'</td>';
                  $tabla.='<td><input type="hidden" name="nombre[]" value="'.$reg['nombre'].'"></input>'.$reg['nombre'].'</td>';
                  $tabla.='<td align="center">'.$reg['entrada_real1'].'</td>';
                  $tabla.='<td align="center">'.$reg['salida_real1'].'</td>';
                  $tabla.='<td align="center">'.$reg['entrada_esperada1'].'</td>';
                  $tabla.='<td align="center">'.$reg['salida_esperada1'].'</td>';
    //              $tabla.='<td>'.$reg['turno'].'</td>';
                  $tabla.='<td><input type="hidden" name="motivo_cp[]" value="'.$reg['motivo_cp'].'"></input>'.$reg['motivo_cp'].'</td>';
                  if ($reg['autorizado_cp']!='') 
			$tabla.='<td>*</td>';
		  else
			$tabla.='<td></td>';
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
	    $i=$i-1;	
//	    $tabla.='<input  name="numreg" id="numreg" type="text" value='.$i.'>';
/*            $tabla.='<table class="" width="90%" align="center" border="0">
                        <tr>
                            <td width="30%">&nbsp;</td>
                            <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar cambio de turno"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
                            <td width="30%">&nbsp;</td>
                        </tr>
                     </table>
                     <p>&nbsp;</p>';
*/
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
