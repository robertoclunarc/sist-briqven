<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	include("funciones_var.php");
	require("enviodecorreoxmodulo.php");  
	$link    = Conex_Contancia_pgsql();
	$conn    = Conectarse_sitt();
	$cn_adam = Conex_oramprd();

	//$tipomovimiento    = isset($_POST["cbotipomovimiento"])?$_POST["cbotipomovimiento"]:"NULL";
 	$trabajador        = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$nombre_sustituto  = isset($_POST["hddnombresustituto"])?$_POST["hddnombresustituto"]:"NULL";
	$finicio           = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $SH                = isset($_POST["cboSH"])?$_POST["cboSH"]:"NULL";
  $ffin              = date("Y-m-d");

	$update_SH_ADAM="UPDATE ADAM.TRABAJADORES_GRALES SET SISTEMA_HORARIO = $SH WHERE TRABAJADOR=".$trabajador;
	$stic = oci_parse($cn_adam, $update_SH_ADAM);
	oci_execute($stic);
  oci_free_statement($stic);

  for ($fecha_procesar = new DateTime($finicio); $fecha_procesar <= new DateTime($ffin); $fecha_procesar->add(new DateInterval("P1D"))) {
      $fecha = $fecha_procesar->format("Y-m-d");
      
      $stmt = $conn->prepare("EXEC PROC_ACTUALIZAR_ESPERANZA_PERSONA_SH ?, ?, ?");
      $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);         
      $stmt->bindParam(2, $fecha, PDO::PARAM_STR,10); 
      $stmt->bindParam(3, $SH, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
  }   
  echo "Procesado el cambio de esperanza al trabajador: ".$trabajador.", al Sistema Horario: ".$SH.", a partir del día ".$finicio; 
	oci_close($cn_adam);
	pg_close($link);   
}else
	echo "De Iniciar Sesion";



/*******************************************************************/
/**             FUNCION QUE DA FORMATO AL NOMBRE                  **/
/*******************************************************************/
function formato_nombre($nombre)
{
	$pos = strpos($nombre, '-');

	if ($pos === false) {
	    $nombre=trim($nombre);
	} else {
	    $nombre=trim(substr($nombre, $pos+1, strlen($nombre)-1));
	}

	return $nombre;
}



/*******************************************************************/
/**                 FUNCION PARA CAMBIO DE TURNO                  **/
/*******************************************************************/
function cambiar_turno($sustituto, $motivo, $fini1, $ffin1 , $causal, $SH, $SH_anterior){	
	$link=Conex_Contancia_pgsql();
	$conn = Conectarse_sitt();
	//print "PASO3";

    $fecha_a_crear = $fini1;
    $hoy=date('Y-m-d'); 

		while ($fecha_a_crear <= $ffin1) {
            #CONSULTAMOS LA ESPERANZA DE ALGUN TRABAJADOR QUE ESTE EN EL SISTEMA HORARIO SELECCIONADO, AGRUPANDOOS POR LOS 
            #CAMPOS a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, Y EL QUE TENGA MAYOR NUMERO DE 
            #VECES REPETIDAS (AGRUPADOS) SE TOMA COMO LA ESPERANZA REAL 
			$query1="SELECT top 1 a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, 
			CASE
                WHEN a.Entrada_Esperada2 =NULL THEN  NULL else a.Entrada_Esperada2
             END  as Entrada_Esperada2, 
             CASE
                WHEN a.Salida_Esperada2 =NULL THEN  NULL  ELSE a.Salida_Esperada2
             END  as Salida_Esperada2,  COUNT(*) 
			         from sw_hoja_de_tiempo_real a 
			         inner join ADAM_DATOS_PERSONALES b on cast(b.Trabajador as integer) = a.cedula 
			         where a.fecha = '".$fecha_a_crear."' 
			         AND cast(sistema_horario as integer) = ".$SH." and (Entrada_Esperada1!='VV:VV' and Entrada_Esperada1!='RR:RR' and Entrada_Esperada1!='PP:PP' and Entrada_Esperada1!='CS:CS' and Entrada_Esperada1!='SD:SD') GROUP BY a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2  order by COUNT(*) DESC"; 

		  $stmt1  = $conn->query($query1);
		  $contar = $stmt1->columnCount(); 
		  $row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
			$entEsp1 = $row['Entrada_Esperada1'];
			$salEsp1 = $row['Salida_Esperada1'];

			if ($row['Entrada_Esperada2'] != NULL) $Entrada_Esperada2 = "'".$row['Entrada_Esperada2']."'"; else $Entrada_Esperada2 = 'NULL';
			if ($row['Salida_Esperada2']  != NULL) $Salida_Esperada2  = "'".$row['Salida_Esperada2']."'";  else $Salida_Esperada2  = 'NULL';

		
			$link2=Conex_rrhh_pgsql();
			$query="SELECT sistema_horario, descripcion  FROM public.sistema_horario WHERE sistema_horario=".$SH." order by 1;";
			$result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
			$numReg = ejecutar_num_rows($result);
			if($numReg>0){
			  $DescripcionSH='';
			   while ($fila=ejecutar_fetch_array($result)) 
			   {
			       $DescripcionSH.= $fila['descripcion'];
			   }
			}		
			pg_close($link2);		

		  $query_update="UPDATE sw_hoja_de_tiempo_real 
		                  SET Entrada_Esperada1='".$entEsp1."', 
		                      Salida_Esperada1='".$salEsp1."',  
		                      Entrada_Esperada2=".$Entrada_Esperada2.", 
		                      Salida_Esperada2=".$Salida_Esperada2.",  
                              turno=".$SH." 
		                      WHERE cedula=".$sustituto." and fecha='".$fecha_a_crear."'
		                      AND Entrada_Esperada1!='VV:VV' 
		                      AND Entrada_Esperada1!='PP:PP'
		                      AND Entrada_Esperada1!='RR:RR'
		                      AND Entrada_Esperada1!='VV:VV'
		                      AND Entrada_Esperada1!='CS:CS'
		                      AND Entrada_Esperada1!='SD:SD'
		                      ";
		  $stmt2  = $conn->query($query_update);
          //print "CAMBIO TURNO";
		  #VERIFICAMOS SI EL DIA ES FF:FF Y MODIFICAMOS EL CODIDO ERROR Y EL CODIGO DE AUSENCIA
			$query_update="UPDATE sw_hoja_de_tiempo_real 
		                 SET Cod_ausencia =26, CodError=100  
		                      WHERE cedula=".$sustituto." and fecha='".$fecha_a_crear."' and Entrada_Esperada1='FF:FF'";
			$stmt3  = $conn->query($query_update);

	        $date_now      = $fecha_a_crear;
			$date_future   = strtotime('+1 day', strtotime($date_now));
			$date_future   = date('Y-m-d', $date_future);
			$fecha_a_crear = $date_future;

		}

		$insertAuditoria="INSERT INTO cambio_cuadrilla (";
		$insertAuditoria.="trabajador,";
		$insertAuditoria.="fecha_ini,";
		$insertAuditoria.="fecha_fin,";
		$insertAuditoria.="cuadrilla,";
		$insertAuditoria.="cuadrilla_anterior,";
		$insertAuditoria.="causal,";
		$insertAuditoria.="motivo,";
		$insertAuditoria.="login_registrado,";
		$insertAuditoria.="fecha_registro";
		$insertAuditoria.=") VALUES (";
	   $insertAuditoria.="'".$sustituto."',";
	   $insertAuditoria.="'".$fini1."',";
	   $insertAuditoria.="'".$ffin1."',";
	   $insertAuditoria.="'".$SH."',";
 	   $insertAuditoria.="'".$SH_anterior."',";
 	   $insertAuditoria.="'".$causal."',";
 	   $insertAuditoria.="'".$motivo."',";
 	   $insertAuditoria.="'".$_SESSION['user_session_const']."',";
	   $insertAuditoria.="now()"; 
	   $insertAuditoria.=")";
 
	 	
	 	$result = pg_query($link,$insertAuditoria) or die("Error en la Consulta SQL:" . $insertAuditoria);	
		pg_close($link); 
		pg_free_result($result);
					
}


?>
