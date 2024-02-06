<!-- Bootstrap Core CSS -->
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css"> 
<script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>
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
  //print_r($_GET);
  if ($_SESSION['nivel_const']==1){
    $qry="select u.*, t.nombre  from usuarios u inner join trabajadores t on u.trabajador = t.trabajador where 1=1 ";
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
    print $b;
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
                      /*$inpt .='<tr>';
                      $inpt .='<td width="5%" style="text-align: right"><h6>'.$contar.'</h6></td>';    
                      $inpt .='<td width="5%"><h6><input type="hidden" name="id_acceso[]" value="'.$row['idacceso'].'"></input>'.$row['idacceso'].'</h6></td>'; 
                      $inpt .='<td width="5%"><h6><input type="hidden" name="descripcion[]" value="'.$row['descripcion'].'"></input>'.$row['descripcion'].'</h6></td>'; 
                      $inpt .='<td width="5%"><h6><input type="hidden" name="operacion[]" value="'.$row['operacion'].'"></input>'.$row['operacion'].'</h6></td>'; 
                      
                      $inpt .='</tr>';                        
                      */
                      $usuario[]=$row['trabajador'];
                      $usuario[]=$row['nombre'];
                      $usuario[]=$row['estatus'];
                      $usuario[]=$row['nivel'];
          } 
          $inpt .='</table>';

          /*$row1=ejecutar_fetch_array($result);


$inpt .="<div class=\"modal fade\" id=\"modificarUser\">
                <div class=\"modal-dialog modal-dialog-centered\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">
                      <h5 class=\"modal-title\">Modificar Usuario</h5>
                      <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                    </div>
                    <div class=\"modal-body\">
                      <div class=\"row mb-3\">
                        <div class=\"col-sm-12\">
                          <!-- Vertical Form -->
                            <form class=\"row g-3\" novalidate id=\"formularioModificar\" name=\"formularioModificar\">
                            <div class=\"col-12\">
                              <label for=\"cedula\" class=\"form-label\">Cédula</label>
                              <input type=\"text\" class=\"form-control\" id=\"cedula\" placeholder=\"\" value=\"".$row1['trabajador']."\" required>
                            </div>  
                            <div class=\"col-12\">
                              <label for=\"nombre\" class=\"form-label\">Nombre</label>
                              <input type=\"text\" class=\"form-control\" id=\"nombre\" placeholder=\"\" value=\"".$row1['nombre']."\" required>
                            </div>                                                        
                            <div class=\"text-center\">
                              <button type=\"reset\" class=\"btn btn-secondary\">Reset</button>
                              <button type=\"button\" class=\"btn btn-primary\" onclick=\"guardarModificar(".$row1['trabajador'].")\">Guardar</button>                              
                            </div>                              
                          </form><!-- Vertical Form -->
                          <div id=\"info\"></div>
                        </div>
                      </div>
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div><!-- End Vertically centered Modal-->";
*/



              $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    ';   
          }
    //echo $inpt;//."-".$b;
    //print_r($row);
    print_r($usuario);      
  }        

?>
