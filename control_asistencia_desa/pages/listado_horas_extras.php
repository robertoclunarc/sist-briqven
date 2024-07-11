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
    $query="SELECT a.*,b.nombre FROM registro_diario a, v_trabajadores_activos b where a.trabajador=b.trabajador ".$complemento." and sobre_tiempo='S' ORDER BY fecha DESC";
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
          $tabla.='<th><input type="checkbox" class="form-control" id="cbox1" value="first_checkbox" placeholder="Marcar Todo"></th>';
          $tabla.='<th>Fecha</th>';
          $tabla.='<th>C&eacute;dula</th>';
          $tabla.='<th>Nombre</th>';
          $tabla.='<th align="center">Gerencia</th>';
          $tabla.='<th align="center">Inicio_ST</th>';
          $tabla.='<th align="center">Fin_ST</th>';
          $tabla.='<th>Observaci&oacute;n</th>';
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='<tbody>';
          $i++;          $total=0;          $debe=0;          $haber=0;
	  while ($reg = ejecutar_fetch_array($result)) {
                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td align="right">'.$i.'</td>';
                  $tabla.='<td><input type="checkbox" class="form-control" id="autorizar[]" name="autorizar[]" value=""></td>';
                  $tabla.='<td><input type="hidden" class="form-control" id="fecha[]" name="fecha[]" value="'.$reg['fecha'].'">'.$reg['fecha'].'</td>';
		  /*#################
                  $os1 = array('CERRADO', 'SATIFECHO');
                  if (($_SESSION['nivel_const']<=2) ){
                      $tabla.='<A href="#" onclick="enviar('. $reg['trabajador'].' , '.$reg['fecha'].')" title="Ver/Actualizar">';
                      $tabla.=' <IMG SRC="images/note.png" WIDTH="20px" HEIGHT="20px"></A>';
                 }
                 $os2 = array('EN REVISION', 'SATIFECHO', 'PENDIENTE', 'EN ESPERA', 'EN PROCESO', 'CERRADO');
 		 //                 if (((($reg['login_solicitante']==$_SESSION['user_session']) || ($reg['login_asignado']==$_SESSION['user_session']) || ($reg['login_observador']==$_SESSION['user_session'])) && $destiempo) || ($_SESSION['nivel_const']==1)) {
                      $tabla.='<A href="#" onclick="seguir('.$reg['trabajador'].' , '.$reg['fecha'].')" title="Seguimiento">';
                       $tabla.=' <IMG SRC="images/segui.png" WIDTH="20px" HEIGHT="20px"> </A>';
                 //                 }  

		 ################
		 */
                  $tabla.='<td><input type="hidden" class="form-control" id="cedula[]" name="cedula[]" value="'.$reg['trabajador'].'">'.$reg['trabajador'].'</td>';
                  $tabla.='<td>'.$reg['nombre'].'</td>';
                  //$tabla.='<td align="center">'.$reg['gerencia'].'</td>';
                  $tabla.='<td align="center"></td>';
                  $tabla.='<td align="center"><input type="hidden" class="form-control" id="entrada_te[]" name="entrada_te[]" value="'.$reg['entrada_te'].'">'.$reg['entrada_te'].'</td>';
                  $tabla.='<td align="center"><input type="hidden" class="form-control" id="salida_te[]" name="salida_te[]" value="'.$reg['salida_te'].'">'.$reg['salida_te'].'</td>';
                  $tabla.='<td><input type="hidden" class="form-control" id="observacion_te[]" name="observacion_te[]" value="'.$reg['observacion_te'].'">'.$reg['observacion_te'].'</td>';
                  $i++;
            }
            $tabla.='</tbody>';
            $tabla.='</table>';
	    $i=$i-1;	
            $tabla.='<table class="" width="90%" id="tblGuardar" align="center" border="0">
                        <tr>
                            <td width="30%">&nbsp;</td>
                            <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar horas extras"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
                            <td width="30%">&nbsp;</td>
                        </tr>
                     </table>
                     <p>&nbsp;</p>';

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
