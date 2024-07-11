<?php
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  $finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
  $trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $estado= isset($_POST["cboestado"])?$_POST["cboestado"]:"NULL";
  $Cod_Adam= isset($_POST["cbocodigo"])?$_POST["cbocodigo"]:"NULL";

  $qry="SELECT a.NumPermiso, a.idausencia, a.cedula, b.nombre as nombres, a.PeriodoPago, a.cod_adam, a.CentroCosto as CentroCosto, b.detalle_ccosto as desc_ccosto, a.Inicio, a.HoraIni, a.Fin, a.Horafin, a.Neto, a.fecha_proc, a.Observaciones, a.cargado, a.estado,a. tipo_notificacion FROM SW_Permisos a LEFT JOIN  adam_vw_dotacion_briqven_02_mas b ON a.cedula = b.trabajador::int
  WHERE a.estado != 'B' AND ('".$finicio."' BETWEEN a.Inicio  AND a.Fin OR ' ".$ffin."' BETWEEN a.Inicio  AND a.Fin)";

  if ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const']==2 || tieneAcceso()) {
    $trabajadores=obtenerTrabajadores(true);
  }
  else{        
    $trabajadores=obtenerTrabajadores(false);
  } 

  if ($trabajador!="NULL"){
    $qry.=" AND a.cedula=".$trabajador;
  }
  else{
    $qry.=" AND a.cedula in (".$trabajadores.") ";
  }

  if ($Cod_Adam!="NULL")
    $qry.=" AND a.Cod_Adam=".$Cod_Adam;

  if ($estado!="NULL")
    $qry.=" AND a.estado='".$estado."'";

  $qry.=" order by a.idausencia DESC";
  
  buscar($qry);

}
else{
  echo "Debe Iniciar Sesion"; 
}

function obtenerTrabajadores($permiso){
  $linkx=Conex_Contancia_pgsql();
  if ($permiso){
    $option=llenar_combo_trabajadoresTodos($linkx);
  }else{
    $option=llenar_combo_trabajadores_del_supervisor();
  }

  // Utiliza una expresión regular para encontrar todos los valores de los atributos 'value'
  preg_match_all('/value=\'([0-9]+)\'/', $option, $matches);
    
  // Los IDs se encuentran en el segundo grupo de matches
  $ids = implode(', ', $matches[1]);
  pg_close($linkx); 
  return $ids;
}

function tieneAcceso(){
  $linkx=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($linkx, 'CONS_PERM', 'consultar_permisos.php', $_SESSION['user_session_const']);
  pg_close($linkx);
  return $acceso;
} 

function buscar($b){
  //echo $b;       
  $cn1=Conectarse_sitt();
  $link=Conex_Contancia_pgsql();  
 
  $result = pg_query($link,$b);
  $contar= pg_num_rows($result);

  $stmt12 = $cn1->prepare("EXEC obtener_posibles_ausencias");
  $stmt12->execute();
  $ausencias = array();
  while ($fila = $stmt12->fetch()) {
    $ausencias[] = $fila;   
  }
  //print_r($ausencias);

      if($contar == 0){
        $inpt = "No se han encontrado resultados!";
      }else{
        $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
        $inpt = $inpt.'<thead>
                      <tr>
                          <th width="3%" class="info">Oper.</th>
                          <th width="3%" class="info">Nro.</th>
                          <th width="5%" class="info">Cedula</th>
                          <th width="5%" class="info">Nombre</th>
                          <th width="5%" class="info">Descipcion Ausencia</th>
                          <th width="3%" class="info">Ccosto</th>
                          <th width="5%" class="info">Desc. CCosto</th>
                          <th width="5%" class="info">F.Inicio</th>
                          <th width="5%" class="info">H.Inicio</th>
                          <th width="5%" class="info">F.Fin</th>
                          <th width="5%" class="info">H.Fin</th>
                          <th width="2%" class="info">Neto</th>
                          <th width="5%" class="info">F.Proceso</th>
                          <th width="5%" class="info">Periodo Pago</th>
                          <th width="5%" class="info">Observacion</th>
                          <th width="5%" class="info">Usuario</th>
                          <th width="5%" class="info">Notif.</th>
                          <th width="4%" class="info">Estado</th>
                      </tr>
                  </thead>
                  <tbody>';
          
        $delete=permiso_usuario($link, 'DELETE', basename( __FILE__ ), $_SESSION['user_session_const']);
        $confirm=permiso_usuario($link, 'CONFIRM', basename( __FILE__ ), $_SESSION['user_session_const']);
        $gerenteAutoriza=permiso_usuario($link, 'AUTORIZAR', basename( __FILE__ ), $_SESSION['user_session_const']);
         
        while($row = pg_fetch_array($result)){
            
            $fechaIni = substr($row['inicio'], 0,10);
            $fechaFin = substr($row['fin'], 0,10);

            $inpt .='<tr>';
            $eliminar=false;
            $confirmar=false;
            
            switch ($row['estado']) {
              
              case 'A': $estado='Autorizado'; $eliminar=true; $confirmar=true; $clase="label label-success";
                break;
              case 'B': $estado='Borrado'; $eliminar=false; $confirmar=false; $clase="label label-danger";
                break;                   
              case 'D': $estado='Documentos'; $eliminar=true; $confirmar=true; $clase="label label-danger";
                break;  
              case 'E': $estado='Espera'; $eliminar=true; $confirmar=false; $clase="label label-warning";
                break;
              case 'L': $estado='Listo'; $eliminar=true; $confirmar=false; $clase="label label-success";
                break;  
              case 'S': $estado='Aut. por Superv.'; $eliminar=true; $confirmar=true; $clase="label label-info";
                break;
              case 'V': $estado='Doc. y Aut. Sup.'; $eliminar=true; $confirmar=true; $clase="label label-danger";
                break;
              case 'W': $estado='Aprobado'; $eliminar=true; $confirmar=true; $clase="label label-info";
                break;  
              default:
                $estado=''; $eliminar=true; $confirmar=true;
            }

            $periodo=periodo_abierto($link, $row['periodopago']);
            $abierto=false;
            $nomina='';
            if ($periodo!==false){
              $abierto=$periodo['abierto'];
              $nomina=$periodo['tipo_nomina'].$periodo['mes'].$periodo['anio'];
            }
            
            $inpt .='<td>';
            $paramAprobar=$row['idausencia'].",'".$row['tipo_notificacion']."', '".$estado."'";
            $btnAprobarGerente='<div style="margin:3px;" id="gteAut_'.$row['idausencia'].'">
                        <button type="button" title="Aprobar" 
                          onclick="gerenteAutoriza('.$paramAprobar.')" 
                          data-toggle="modal" 
                          data-target="#exampleModalCenter" 
                          class="btn btn-warning btn-circle">
                          <i class="fa fa-clock-o"></i>
                        </button>
                      </div>';

            $paramConfirmar=$row['numpermiso'].", ".$row['cedula'].", '".$row['estado']."', ".$row['idausencia'].",'".$row['tipo_notificacion']."'";
            $btnConfirmarTTHH='<div style="margin:3px;" id="boton_'.$row['idausencia'].'">
                        <button type="button" title="Procesar"
                          onclick="procesar('.$paramConfirmar.')" 
                          data-toggle="modal" 
                          data-target="#exampleModalCenter" 
                          class="btn btn-info btn-circle">
                          <i class="fa fa-check"></i>
                        </button>
                      </div>';

            $paramElimimar=$row['numpermiso'].", ".$row['cedula'].", ".$row['idausencia'].",'".$row['tipo_notificacion']."'";
            $btnEliminar='<div style="margin:3px;" id="elimina_'.$row['idausencia'].'">
                        <button type="button" title="Borrar" 
                          onclick="eliminar_permiso('.$paramElimimar.')" 
                          data-toggle="modal" 
                          data-target="#exampleModalCenter" 
                          class="btn btn-danger btn-circle">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>';
            
            if ($estado==='Espera' && ($gerenteAutoriza=='t' || $_SESSION['nivel_const']==4 || $_SESSION['nivel_const']==5)){
              
              $inpt .=$btnAprobarGerente;
              //$inpt .='<br>';
              $inpt .=$btnEliminar;
            }
            if ($estado==='Aut. por Superv.' && $confirmar /*&& $abierto== 't'*/){//activa lo comentado
              
              $inpt .=$btnConfirmarTTHH;
              //$inpt .='<br>';
              if ($delete && $eliminar){
                $inpt .=$btnEliminar;
              }
            }

            if ($estado==='Documentos' && $confirmar /*&& $abierto== 't'*/){//activa lo comentado
              
              $inpt .=$btnConfirmarTTHH;
              //$inpt .='<br>';
              /*if ($delete && $eliminar){
                $inpt .=$btnEliminar;
              }*/
            }                
            

            $inpt .='</td>'; 
            $cargado=login_usuario($link,$row['cargado']);
            $descripcion_ausencia=buscarValor($ausencias, 'cod_adam', $row['cod_adam'], 'desc_cod_hora');
            $inpt .='<td>'.$row['idausencia'].'</td>';
            $inpt .='<td>'.$row['cedula'].'</td>';
            $inpt .='<td>'.$row['nombres'].'</td>';
            $inpt .='<td>'.$descripcion_ausencia.'</td>';
            $inpt .='<td>'.$row['centrocosto'].'</td>';
            $inpt .='<td>'.$row['desc_ccosto'].'</td>';
            $inpt .='<td>'.$fechaIni.'</td>';
            $inpt .='<td>'.$row['horaini'].'</td>';
            $inpt .='<td>'.$fechaFin.'</td>';
            $inpt .='<td>'.$row['horafin'].'</td>';
            $inpt .='<td>'.$row['neto'].'</td>';
            $inpt .='<td>'.substr($row['fecha_proc'],0,16).'</td>';
            $inpt .='<td>'.$nomina.'</td>';
            $inpt .='<td>'.$row['observaciones'].'</td>';                
            $inpt .='<td>'.$cargado.'</td>';
            $inpt .='<td>'.$row['tipo_notificacion'].'</td>';
            $inpt .='<td><div id="destado_'.$row['idausencia'].'"><span class="'.$clase.'">'.$estado.'</span></div></td>';
            
            $inpt .='</tr>';
        }
         
        $inpt .=' </tbody>
        <tfoot>
              <tr>
                  <th><span class="label label-success">Total:</span></th>
                  <th><span class="label label-success">'.$contar.'</span></th>
                  <th><span class="label label-info"></span></th>
                  <th><span class="label label-info"></span></th>
                  <th><span class="label label-danger"></span></th>
                  <th><span class="label label-danger"></span></th>
                  <th></th>
                  <th></th>
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th> 
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
          </tfoot>
          </table>';
    }
  pg_close($link);  
  pg_free_result($result);
  $cn1=null;
  $stmt12=null;
  echo $inpt;
  //print_r($row);
}         
?>