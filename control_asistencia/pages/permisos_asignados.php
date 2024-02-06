<?php
if (isset($_SESSION['user_session_const'])){
  echo "<body>
  <script type='text/javascript'>
  window.location='../index.php';
  </script>
  </body>";
} 
include("../BD/conexion.php");
require_once('funciones_var.php'); 
session_start();
  $trabajador = isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
  //  print_r($_POST);
  if ($_SESSION['nivel_const']==1){
    $qry="select u.*, t.nombre, au.operacion, a.idacceso, a.descripcion  from usuarios u inner join trabajadores t on u.trabajador = t.trabajador left join accesos_usuarios au on u.login_username = au.login  left join accesos a on au.fkacceso = a.idacceso where 1=1 ";
      if ($trabajador!='NULL')
            $qry.=" and u.trabajador = '".$trabajador."'";
        
     $qry.=" order by cast (t.trabajador AS integer)";

      buscar($qry);     
    }else{
      //header('Location: /login/index.php');
    echo "<body>
    <script type='text/javascript'>
    window.location='../index.php';
    </script>
    </body>";
  }   

  function buscar($b) {
    $link=Conex_Contancia_pgsql();
    //print $b;
    $result = ejecutar_query($link, $b) or die("Error en la Consulta SQL: ".$b);
    $contar = ejecutar_num_rows($result);
        
          if($contar == 0){
                $inpt = "No se han encontrado resultados!";
                
          }else{
                $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
                
                $inpt = $inpt.'<thead>
              <tr>
                  <th width="5%" class="info"></th>             
                  <th width="5%" class="info"><h6>ID Acceso</h6></th>
                  <th width="5%" class="info"><h6>Acceso</h6></th>
                  <th width="5%" class="info"><h6>Operacion</h6></th>
              </tr>
          </thead>
          <tbody>';
              $contar=0;
          while ($row=ejecutar_fetch_array($result)){
                    $separador='-';
                    //$fecha = formato_fecha(substr($row['fecha'], 0,10),$separador);
                    //$param = $row["trabajador"].",'".$row['fecha']."', 'E'";
                      $contar++;
                      $inpt .='<tr>';
                      $inpt .='<td width="5%" style="text-align: right"><h6>'.$contar.'</h6></td>';    
                      $inpt .='<td width="5%"><h6><input type="hidden" name="id_acceso[]" value="'.$row['idacceso'].'"></input>'.$row['idacceso'].'</h6></td>'; 
                      $inpt .='<td width="5%"><h6><input type="hidden" name="descripcion[]" value="'.$row['descripcion'].'"></input>'.$row['descripcion'].'</h6></td>'; 
                      $inpt .='<td width="5%"><h6><input type="hidden" name="operacion[]" value="'.$row['operacion'].'"></input>'.$row['operacion'].'</h6></td>'; 
                      
                      $inpt .='</tr>';                        

                } 
              $inpt .='</table>
              <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    ';   
          }
    echo $inpt;//."-".$b;
    //print_r($row);
  }        

?>
