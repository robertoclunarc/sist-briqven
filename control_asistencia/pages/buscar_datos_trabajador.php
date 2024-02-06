<?php 
$buscar = $_GET['b'];
$fecha = $_GET['f'];
if(!empty($buscar)) {
      buscar($buscar,$fecha);
}

 
function buscar ($b,$f){
	include("../BD/conexion.php");
	include("funciones_var.php");
	$link=Conex_rrhh_pgsql();
	$sql = "SELECT  trabajadores.nombres || ' ' || trabajadores.apellidos as nombreyapellido,  
	 case when trabajadores_grales.sit_trabajador=1 then 'ACTIVO'
	   else 'INACTIVO' 
	  end as estatus,
	 trabajadores.trabajador,sistema_horario
	FROM 
	  public.trabajadores, 
	  public.trabajadores_grales
	WHERE 
	  trabajadores.trabajador = trabajadores_grales.trabajador and trabajadores.trabajador = '".trim($b)."'";
	
	//        echo "$(\"#grupodb\").val(\"" . $sql. "\");\n" ;
	$result = ejecutar_query($link,$sql);
	                
	if (ejecutar_num_rows($result) > 0){
        
		$row=ejecutar_fetch_array($result);                 
        	echo "$(\"#grupodb\").val(\"" . $row['sistema_horario'] . "\");\n" ;
	       echo "$(\"#hddcedula\").val(\"" . $b . "\");\n" ;
	        echo "$(\"#txtnombres\").val(\"" . $row['nombreyapellido'] . "\");\n" ;
	        echo "$(\"#hddtestatus\").val(\"" . $row['estatus'] . "\");\n" ;                   
	//        echo "$(\"#tn_desp_real\").val(\"" . $tn_desp_real . "\");\n" ;


 		$link2=Conectarse_sitt();
		$query="select entrada_esperada1,salida_esperada1,entrada_esperada2,salida_esperada2 from dbo.SW_Hoja_de_Tiempo_Real where cedula=".trim($b)." and fecha='".$f."'";
//print $query;
		$stmt1 = $link2->query($query);
		while($fila = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
		{
			echo "$(\"#entrada_real1\").val(\"" . $fila['entrada_esperada1'] . "\");\n" ;
			if ($fila['salida_esperada2']!=null || $fila['salida_esperada2']!=''){
			    echo "$(\"#salida_real1\").val(\"" . $fila['salida_esperada2'] . "\");\n" ;
  			}else{
			    echo "$(\"#salida_real1\").val(\"" . $fila['salida_esperada1'] . "\");\n" ;
			}

    	        }
	}else     
	      echo '0';
	ejecutar_free_result($result);
	ejecutar_close($link);    
}    
 
?>
