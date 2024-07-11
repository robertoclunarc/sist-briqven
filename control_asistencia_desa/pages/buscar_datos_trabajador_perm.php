<?php 
$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
buscar($trabajador);
 
function buscar ($cedula){
	include("../BD/conexion.php");
	include("funciones_var.php");
	$link=Conex_rrhh_pgsql();
	$sql="SELECT trabajador, nombre, relacion_laboral, 
       sistema_horario,  ccosto, detalle_ccosto,  puesto, desc_puesto, 
       trabajador_sup, nombre_sup,
       case when relacion_laboral='L' then 'PA'
	   else 'ME' 
	  end as clase_nomina
  FROM adam_vw_dotacion_briqven_02_mas
  where trabajador='".$cedula."'";
	
	$result = ejecutar_query($link,$sql);
	                
	if (ejecutar_num_rows($result) > 0){
        
		$row=ejecutar_fetch_array($result);
		   $sisthor=$row['sistema_horario'];
		   $rel=$row['relacion_laboral'];
		   $cln=$row['clase_nomina'];
           echo "$(\"#hddsisthor\").val(\"" . $sisthor . "\");\n" ;
	       echo "$(\"#hddclasenomina\").val(\"" . $cln . "\");\n" ;
	       echo "$(\"#hddrelacion_laboral\").val(\"" . $rel . "\");\n" ;
	       echo "$(\"#hddcedtrabajadorsup\").val(\"" . $row['trabajador_sup'] . "\");\n" ;
	       echo "$(\"#txtccosto\").val(\"" . $row['detalle_ccosto'] . "\");\n" ;
	       echo "$(\"#hddccosto\").val(\"" . $row['ccosto'] . "\");\n" ;
	       echo "$(\"#hddpuesto\").val(\"" . $row['puesto'] . "\");\n" ;
	       echo "$(\"#txtpuesto\").val(\"" . $row['desc_puesto'] . "\");\n" ; 
	       echo "$(\"#txttrabajadorsup\").val(\"" . $row['nombre_sup'] . "\");\n" ;	       

	        $mbd=Conectarse_sitt();
	        
	        $stm = $mbd->prepare("EXEC dbo.SW_con_datos_basicos_persona_permiso ?");
	        $stm->bindParam(1, $cedula, PDO::PARAM_INT,10);
			$stm->execute();

			 while ($fila = $stm->fetch()) {
			    echo "$(\"#txtdisrem\").val(\"" . $fila['remun'] . "\");\n" ;
	       		echo "$(\"#txtdisnorem\").val(\"" . $fila['nremun'] . "\");\n" ;
	       		echo "$(\"#hddturno\").val(\"" . $fila['TURNO'] . "\");\n" ;
			 }

			$stmt = $mbd->prepare("EXEC dbo.SW_CON_PROXIMO_PERIODO ?");
	        $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
			$stmt->execute();

			  while ($fila = $stmt->fetch()) {
			    echo "$(\"#hddidcalendario\").val(\"" . $fila['ID_CALENDARIO'] . "\");\n" ;
			    $array= explode('/', $fila['fpago']);
    			$fecha = $array[2].'-'.$array[1].'-'.$array[0];
	       		echo "$(\"#txtppago\").val(\"" . $fecha . "\");\n" ;
			 }
 		
	}else{
			echo "$(\"#hddturno\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#hddclasenomina\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#hddrelacion_laboral\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#hddcedtrabajadorsup\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#txtccosto\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#hddccosto\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#hddpuesto\").val(\"" . "" . "\");\n" ;
	       echo "$(\"#txtpuesto\").val(\"" . "" . "\");\n" ; 
	       echo "$(\"#txttrabajadorsup\").val(\"" . "" . "\");\n" ;
	}     
	     
	ejecutar_free_result($result);
	ejecutar_close($link);    
}    
 
?>
