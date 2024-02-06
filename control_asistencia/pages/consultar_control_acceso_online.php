<?php
 session_start();
 require_once('funciones_var.php');

//$motivos = isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";    //
$direccion= isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";         //
$ci= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$desde= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";
$fin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$enviar= isset($_GET["enviar"])?$_GET["enviar"]:"NO";

$qry="SELECT 
  v_acceso_personal_propio.cedula, 
  v_acceso_personal_propio.fecha_acceso, 
  to_char(v_acceso_personal_propio.fecha_acceso, 'YYYY-mm-dd') as fecha,
  v_acceso_personal_propio.direccion, 
  v_acceso_personal_propio.tipo_personal, 
  v_acceso_personal_propio.nombres, 
  v_acceso_personal_propio.cargo, 
  v_acceso_personal_propio.departamento, 
  v_acceso_personal_propio.jefe_inmediato, 
  v_acceso_personal_propio.usuario, 
  v_acceso_personal_propio.turno, 
  motivos.idmotivo, 
  motivos.descripcion_motivo
FROM 
  v_acceso_personal_propio, 
  motivos
WHERE 
  v_acceso_personal_propio.fkmotivo = motivos.idmotivo and v_acceso_personal_propio.fecha_acceso::date BETWEEN '".$desde."' AND '".$fin."'";     

if (($direccion!="NULL") && ($direccion!="null") && ($direccion!=""))        
  $qry.=" and v_acceso_personal_propio.direccion='" . $direccion . "'";

if (($ci!="NULL") && ($ci!=""))
   $qry.=" and v_acceso_personal_propio.cedula ='".$ci."'";
  
//echo $qry;
buscar($qry,$enviar,$desde,$fin);     
       
function buscar($b,$enviar, $desde, $fin) {
       require_once('../BD/conexion.php');
       $link=Conex_Contancia_pgsql();
       $permiso=permiso_usuario($link, 'MIGRAR', 'consultar_control_acceso.php', $_SESSION['user_session_const']);
       $cn=Conectarse();
        
        $res = pg_query($cn,$b) or die("Error en la Consulta SQL: ".$b);
        $contar = pg_num_rows($res);
         
        if($contar == 0){
              $inpt = "No se han encontrado resultados! ";
              
        }else{
            $inpt = '<table width="100%" border="1" class="table table-striped table-bordered table-hover" id="dataTables-example">';
  
              $inpt = $inpt.'<thead>
                <tr>
                    <th></th>
                    <th>Cedula</th>                        
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Tipo Personal</th>
                    <th>Fecha</th>
                    <th>Direccion</th>
<!--                    <th>Departamento</th> 
                    <th>Jefe</th>                                             
                    <th>Motivo</th> -->
                    <th>Turno</th>
                    <!--<th>Usuario</th>-->
                </tr>
            </thead>
            <tfoot>
                    <tr>
                        <th></th>
                        <th>Cant. Reg.:</th>
                        <th>'.$contar.'</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      <!--  <th></th>
                        <th></th>
                        <th></th>-->
                        <th></th>
                        <th></th>
                       <!--  <th></th>                        -->
                    </tr>
                </tfoot>           
              <tbody>';
              while($row=pg_fetch_array($res)){
                    
                    $c1 = $row['cedula'];
                    $c2 = $row['nombres'];
                    $c3 = $row['cargo'];
                    $c4 = $row['tipo_personal'];
                    $c5 = substr($row['fecha_acceso'],0,16);
                    $c6 = $row['direccion'];                    
                    $c7 = $row['departamento'];
                    $c8 = $row['jefe_inmediato'];                    
                    $c9 = $row['descripcion_motivo'];                    
                    $c10 = $row['turno'];
                    $c11 = $row['usuario'];
                    
                    $fecha = $row['fecha'];
                    $hojaTiempo=buscar_fichada_ht($c1, $fecha);
                    $entrada= isset($hojaTiempo['entrada1'])?$hojaTiempo['entrada1']:'';
                    $esperanza= isset($hojaTiempo['esperanza'])?$hojaTiempo['esperanza']:'';
                    $op="<td>";                    
                    if ($entrada=='' && $enviar=='NO'){
                      if ($esperanza=='07:00' || $esperanza=='15:00' || $esperanza=='23:00'){
                        $op="<td class='bg-warning'>";//si tiene esperanza de trabaja pinta de amarillo
                        $title='Enviar Fichada a la Hoja de Tiempo';
                        $ent=extraer_hora($c1, $fecha, 'ENTRADA', $cn);
                        $sal=extraer_hora($c1, $fecha, 'SALIDA', $cn);
                        if ($ent!="" && $sal!=""){
                          $param="exportar_fichada({$c1}, '{$fecha}', '{$ent}', '{$sal}', 1)";
                        }else{
                          $param="exportar_fichada({$c1}, '{$fecha}', '', '', 2)";
                        }
                      }else{
                        $op="<td class='bg-info'>";// si esta LL,VV,PP,RR,CS,FF pinta de azul
                        $title='Trabajador Sin Esperanza de Trabajo';
                      }                      
                      
                      $fechaActual =date("Y-m-d");
                      
                      if ($fechaActual!=$fecha && $permiso){
                        if ($title=='Enviar Fichada a la Hoja de Tiempo'){
                            $op.="<div id='div_".$c1."_".$fecha."'><a onclick=\"$param\" title='".$title."' href='#'><img src='images/blockbullets.png' ></a></div>";
                        }
                        else{
                          $op.="<div id='div_".$c1."_".$fecha."'><a title='".$title."' href='#'><img src='images/blockbullets.png' ></a></div>";
                        }
                      }
                      
                    }
                    $op.="</td>";
                    $inpt .=$op;                    
                    $inpt .='<td>'.$c1.'</td>';                    
                    $inpt .='<td>'.$c2.'</td>';
                    $inpt .='<td>'.$c3.'</td>';
                    $inpt .='<td>'.$c4.'</td>';
                    $inpt .='<td>'.$c5.'</td>';
                    $inpt .='<td>'.$c6.'</td>';
                    //$inpt .='<td>'.$c7.'</td>';
                    //$inpt .='<td>'.$c8.'</td>';
                    //$inpt .='<td>'.$c9.'</td>';
                    $inpt .='<td>'.$c10.'</td>';                
                    //$inpt .='<td>'.$c11.'</td>';
                    $inpt .='</tr>';                        
              }
              $inpt .='</tbody></table>';                  
        }
pg_close($cn);
pg_close($link);
pg_free_result($res);      
echo $inpt; 

}

function buscar_fichada_ht($cedula, $fecha){
  $con=Conectarse_sitt();
  $query="SELECT entrada_real1, salida_real1, entrada_esperada1 FROM sw_hoja_de_tiempo_real WHERE cedula=".$cedula." and fecha='".$fecha."'";
  //echo $query;
  $stmt1 = $con->query($query);
  $fichadas = [];
  while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
    $fichadas += [ "entrada1" => $row['entrada_real1'] ];
    $fichadas += [ "salida1" => $row['salida_real1'] ];
    $fichadas += [ "esperanza" => $row['entrada_esperada1'] ];
  }
  //print_r($fichadas);
  $con=NULL;
  $stmt1=NULL;
  return $fichadas;
}

function extraer_hora($cedula, $fecha, $direccion, $conex) {
  $tope = "MIN";
  if ($direccion=='SALIDA'){
    $tope = "MAX";
  }
  $query="SELECT ".$tope."(to_char(v.fecha_acceso, 'HH24:MI')) as hora FROM v_acceso_personal_propio v WHERE v.fecha_acceso::date = '".$fecha."' and v.cedula='".$cedula."' and v.direccion='".$direccion."'";
  $res = pg_query($conex,$query) or die("Error en la Consulta SQL: ".$query);
  $contar = pg_num_rows($res);         
  if($contar == 0){
    $inpt = "";              
  }else{              
    while($row=pg_fetch_array($res)){                    
      $inpt = $row['hora'];                                        
    }              
  }
pg_free_result($res);      
return $inpt;
}
?>
