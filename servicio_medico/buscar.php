<?php
 
      $buscar = $_POST['b'];
       
      if(!empty($buscar)) {
            buscar($buscar);
      }
       
      function buscar($b) {
           require("include_conex.php"); 
                  $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
                  $cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
                  $sql = "SELECT * FROM tbl_patologias WHERE descripcion ILIKE '%".$b."%' OR codigo_etica ILIKE '%".$b."%' ORDER BY codigo_etica";
                  $res = pg_query($cn,$sql);
                  $row = pg_fetch_array($res);
             
            $contar = pg_num_rows($res);
             
            if($contar == 0){
                  echo "No se han encontrado resultados para '<b>".$b."</b>'.";
            }else{
                  while($row=pg_fetch_array($res)){
                        $nombre = $row['descripcion'];
                        $cod = $row['codigo_etica'];
                        $id = $row['uid'];
                         
                        echo control($id, $cod." - ".$nombre)."";    
                  }
            }
      }
function control($valor, $descr) {
$inpt = '<div class="col-lg-6">
    <div class="input-group">
      <span class="input-group-addon">
        <input name="gender" type="radio" onclick="seleccionar_diag('.$valor.');">
      </span>
      <input readonly size="20" type="text" value="'.$descr.'" class="form-control">
    </div>
  </div>
</div>';
return $inpt;
}       
?>