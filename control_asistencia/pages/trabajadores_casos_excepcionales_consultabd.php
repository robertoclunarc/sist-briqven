<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
  include("../BD/conexion.php");
  require_once('funciones_var.php');  

    $link=Conex_Contancia_pgsql();
  $acceso=permiso_usuario($link, 'UPDATE', 'trabajadores_supervisados', $_SESSION['user_session_const']);
    pg_close($link);

  //print_r($_POST);
  //print_r($_GET);

    //$link=Conex_rrhh_pgsql();
  $link=Conectarse();    

  if ($acceso){ 
      $id         = isset($_GET["id"])?$_GET["id"]:'';
      $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
      $status     = isset($_POST["status"])?$_POST["status"]:'NULL';
      $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';

      if ($status=='ACTIVO'){
          $complemento = " and (fecha_hasta is null or fecha_hasta>=DATE_TRUNC('day', CURRENT_DATE)) and status ='".$status."'";
      }elseif ($status=='INACTIVO'){
          $complemento="and status ='".$status."'";
      }else{
        $complemento="";
      }

      
      $query="SELECT id, vt.nombres, cedula, TO_CHAR(fecha_desde, 'DD-MM-YYYY') as fecha_desde, fecha_hasta, motivo, TO_CHAR(fecha_registro, 'DD-MM-YYYY HH12:MI:SS AM') as fecha_registro, usuario_registrador, observacion, status FROM public.personal_bloqueado p inner join v_trabajadores vt on p.cedula = vt.trabajador where 1=1 ".$complemento." order by nombres";
      
      $inpt = $inpt.'<thead>
                <tr>
                    <th width="5%" class="info">Cedula</th>             
                    <th width="15%" class="info">Nombres</th> 
                    <th width="10%" class="info">Fecha Desde</th> 
                    <th width="10%" class="info">Fecha Hasta</th> 
                    <th width="15%" class="info">Motivo</th> 
                    <th width="15%" class="info">Observación</th> 
                    <th width="10%" class="info">Fecha Registro</th>                       
                    <th width="15%" class="info"></th> 
                </tr>
            </thead>
            <tbody>';

      //print $query;
      $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
      while ($fila = ejecutar_fetch_array($result)){

             if ($fila['status']=='ACTIVO'){
                 $boton='<td aling="center"><INPUT id="eliminar_'.$fila['cedula'].'" type="button" value="Eliminar"  class="btn btn-success" onclick="eliminar('.$fila['cedula'].');"/></td>';
             }elseif ($fila['status']=='INACTIVO'){
                      $boton='<td aling="center"></td>';

             }
             $inpt .='<tr>';
                        
             $inpt .='<td>'.$fila['cedula'].'</td>';          
             $inpt .='<td>'.$fila['nombres'].'</td>';
             $inpt .='<td>'.$fila['fecha_desde'].'</td>';
             $inpt .='<td>'.$fila['fecha_hasta'].'</td>';
             $inpt .='<td>'.$fila['motivo'].'</td>';
             $inpt .='<td>'.$fila['observacion'].'</td>';
             $inpt .='<td>'.$fila['fecha_registro'].'</td>';
            // $inpt .='<td>'.$fila['status'].'</td>';
             $inpt .=$boton;
             $inpt .='</tr>';
      }

      pg_close($link);      
      echo $inpt;
  }else{
    echo "No tiene privilegio para esta operacion";
  } 
  
} 
else
  echo "Debe Iniciar Sesion";
?>
