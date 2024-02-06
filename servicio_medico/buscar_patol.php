<?php
$buscar = $_GET['b'];       
      
 require("include_conex.php"); 
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
        $sql = "select * from tbl_patologias where codigo_etica='".$buscar."' order by codigo_etica";
        $res = pg_query($cn,$sql);                  
   
  $contar = pg_num_rows($res);
   
  if($contar == 0){
        echo $contar;
  }else{
        $row=pg_fetch_array($res);
        echo "$(\"#cboPatologias\").val(\"" . $row["uid"] . "\");\n" ;
        echo "$(\"#patol\").val(\"" . $row["descripcion"] . "\");\n" ;
  }
  
?>