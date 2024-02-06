<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	include("funciones_var.php");
	require("enviodecorreoxmodulo.php");  
	$link=Conex_Contancia_pgsql();
	$conn = Conectarse_sitt();
	
	$tipomovimiento    = isset($_POST["cbotipomovimiento"])?$_POST["cbotipomovimiento"]:"NULL";
 	$sustituto         = isset($_POST["cbosutituto"])?$_POST["cbosutituto"]:"NULL";
	$nombre_sustituto  = isset($_POST["hddnombresustituto"])?$_POST["hddnombresustituto"]:"NULL";
	$sustituido        = isset($_POST["cbosustituido"])?$_POST["cbosustituido"]:"NULL";	 
	$nombre_sustituido = isset($_POST["hddnombresustituido"])?$_POST["hddnombresustituido"]:"NULL";
	$motivo            = isset($_POST["txtmotivo"])?$_POST["txtmotivo"]:"NULL";
	$finicio           = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin              = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$puesto            = isset($_POST["hddpuesto2"])?$_POST["hddpuesto2"]:"NULL";
	$desc_puesto       = isset($_POST["txtpuesto2"])?$_POST["txtpuesto2"]:"NULL";
	$causa             = isset($_POST["cbocausa"])?$_POST["cbocausa"]:"NULL";

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');
	
	$nombre_sustituto=formato_nombre($nombre_sustituto);
	$nombre_sustituido=formato_nombre($nombre_sustituido);


	$query="SELECT desc_puesto FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador='".$sustituido."'";	
	$result1 = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);  
	$fila1=pg_fetch_array($result1);
	$desc_puesto = $fila1['desc_puesto'];

	
	if ($tipomovimiento==4){	
		/*******************************************************************/
		/**                         SI ES SUSTITUCIONES                   **/
		/*******************************************************************/
		$SH          = isset($_POST["cboSH2"])?$_POST["cboSH2"]:"NULL";
		$SH_anterior = isset($_POST["cboSH1"])?$_POST["cboSH1"]:"NULL";

		$stmt = $conn->prepare("EXEC dbo.sw_carga_masiva_sustitucion_mat ?, ?, ?, ?, ?, ?, ?");
		$stmt->bindParam(1, $sustituto, PDO::PARAM_INT,10);	
		$stmt->bindParam(2, $fini1,  PDO::PARAM_STR,10);
		$stmt->bindParam(3, $ffin1,  PDO::PARAM_STR,10);
		$stmt->bindParam(4, $causa,  PDO::PARAM_STR,2);
		$stmt->bindParam(5, $sustituido,  PDO::PARAM_INT,10);
		$stmt->bindParam(6, $puesto,  PDO::PARAM_STR,4);
		$stmt->bindParam(7, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
		$stmt->execute();
      

		$insertAuditoria="INSERT INTO sustituciones (";
		$insertAuditoria.="sustituido,";
		$insertAuditoria.="sustituto,";
		$insertAuditoria.="fecha_ini,";
		$insertAuditoria.="fecha_fin,";
		$insertAuditoria.="cod_puesto,";
		$insertAuditoria.="desc_puesto,";
		$insertAuditoria.="causal,";
		$insertAuditoria.="login_registrado,";
		$insertAuditoria.="fecha_registro,";
		$insertAuditoria.="nombre_sustituto,";
		$insertAuditoria.="nombre_sustituido,";	
		$insertAuditoria.="cuadrilla_anterior,";
		$insertAuditoria.="cuadrilla_nueva,";
		$insertAuditoria.="observacion";		
		$insertAuditoria.=") VALUES (";
	   $insertAuditoria.="'".$sustituido."',";
	   $insertAuditoria.="'".$sustituto."',";
	   $insertAuditoria.="'".$fini1."',";
	   $insertAuditoria.="'".$ffin1."',";
	   $insertAuditoria.="'".$puesto."',";
	   $insertAuditoria.="'".$desc_puesto."',";
	   $insertAuditoria.="'".$causa."',";
	   $insertAuditoria.="'".$_SESSION['user_session_const']."',";
	   $insertAuditoria.="now(),"; 
	   $insertAuditoria.="'".$nombre_sustituto."',";
	   $insertAuditoria.="'".$nombre_sustituido."',";           
	   $insertAuditoria.="'".$SH_anterior."',"; 
	   $insertAuditoria.="'".$SH."',";
	   $insertAuditoria.="'".$motivo."'"; 	   
      $insertAuditoria.=")";
	    
	   $result = pg_query($link,$insertAuditoria) or die("Error en la Consulta SQL:" . $insertAuditoria);	
		pg_close($link); 
		pg_free_result($result);

   	/*************************************************/
	   //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
		      $asunto="Carga de sustitucion del trabajador: ".nombre_trabajadores(trim($sustituto)).", desde: ".$finicio. " hasta: ".$ffin;
		      $cuerpo  = "Saludos, Se realizo una Carga de sustitucion al trabajador<br>
		      <table border='1'>
		        <tr>
		           <td colspan='2'>Nombre </td>
		           <td colspan='2'><b>".nombre_trabajadores(trim($sustituto))."</b></td>
		        </tr>
		        <tr>
		           <td colspan='2'>C&eacute;dula de indentidad </td>
		           <td colspan='2'>".trim($sustituto)."</td>
		        </tr>
		        <tr>
		           <td colspan='2'>Desde: </td>
		           <td colspan='2'><b>".formato_fecha($finicio,'-')."</b></td>
		        </tr>
		        <tr>
		           <td colspan='2'>Hasta: </td>
		           <td colspan='2'><b>".formato_fecha($ffin,'-')."</b></td>
		        </tr>		        
		        <tr>
		           <td colspan='2'>Puesto a ocupar: </td>
		           <td colspan='2'><b>".$puesto." - ".$desc_puesto."</b></td>
		        </tr>
		        <tr>   
 		           <td colspan='2'>Observaci&oacute;n: </td>
		           <td colspan='2'>".$motivo."</td>
		        </tr>  		        
		        <tr>   
 		           <td colspan='2'>Registrado por: </td>
		           <td colspan='2'>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
		        </tr>   
		        <tr>   
		           <td colspan='2'>A trav&eacute;s del M&oacute;dulo</td>
		           <td colspan='2'> Movimiento Temporales.</td>
		        </tr></table>";

		        ENVIAR_CORREO($cuerpo,$asunto,"", $_SESSION['user_session_const'], "CAMBIO CUADRILLA",$_SESSION['user_session_const']."@briqven.com.ve","");


		       /*************************************************/   
		       echo "Procesado";    				



    }elseif ($tipomovimiento==2){
    /*******************************************************************/
		/**                       SI ES CAMBIO DE TURNO                   **/
		/*******************************************************************/

    	$SH            = isset($_POST["cboSH"])?$_POST["cboSH"]:"NULL";
		$SH_anterior   = isset($_POST["cboSH1"])?$_POST["cboSH1"]:"NULL";

      $fecha_a_crear = $finicio;

      $hoy=date('Y-m-d'); 
		while ($fecha_a_crear <= $ffin) {
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
		   //print "<br>".$query1;
		   //$contar = $stmt1->columnCount(); 
		   $row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
			$entEsp1 = $row['Entrada_Esperada1'];
			$salEsp1 = $row['Salida_Esperada1'];
//print $row['Entrada_Esperada1']."*".$row['Salida_Esperada1'];
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
			//print "<br>".$query_update;

		    #VERIFICAMOS SI EL DIA ES FF:FF Y MODIFICAMOS EL CODIDO ERROR Y EL CODIGO DE AUSENCIA
			$query_update="UPDATE sw_hoja_de_tiempo_real 
		                  SET Cod_ausencia =6, CodError=100  
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
		$insertAuditoria.="motivo,";
		$insertAuditoria.="login_registrado,";
		$insertAuditoria.="fecha_registro";
		$insertAuditoria.=") VALUES (";
	   $insertAuditoria.="'".$sustituto."',";
	   $insertAuditoria.="'".$fini1."',";
	   $insertAuditoria.="'".$ffin1."',";
	   $insertAuditoria.="'".$SH."',";
	   $insertAuditoria.="'".$SH_anterior."',";
	   $insertAuditoria.="'".$motivo."',";
	   $insertAuditoria.="'".$_SESSION['user_session_const']."',";
	   $insertAuditoria.="now()"; 
	   $insertAuditoria.=")";
 
	 	
	 	$result = pg_query($link,$insertAuditoria) or die("Error en la Consulta SQL:" . $insertAuditoria);	
		pg_close($link); 
		pg_free_result($result);
				
				
		/*************************************************/
		//SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
		$asunto="Cambio de Esperanza del trabajador: ".nombre_trabajadores(trim($sustituto)).", desde: ".$finicio. " hasta: ".$ffin;
		$cuerpo  = "Saludos, Se realizo un cambio de Esperanza al trabajador<br>
		      <table border='1'>
		        <tr>
		           <td colspan='2'>Nombre </td>
		           <td colspan='2'><b>".nombre_trabajadores(trim($sustituto))."</b></td>
		        </tr>
		        <tr>
		           <td colspan='2'>C&eacute;dula de indentidad </td>
		           <td colspan='2'>".trim($sustituto)."</td>
		        </tr>
		        <tr>
		           <td colspan='2'>Desde: </td>
		           <td colspan='2'><b>".formato_fecha($finicio,'-')."</b></td>
		        </tr>
		        <tr>
		           <td colspan='2'>Hasta: </td>
		           <td colspan='2'><b>".formato_fecha($ffin,'-')."</b></td>
		        </tr>		        
		        <tr>
		           <td colspan='2'>Cuadrilla: </td>
		           <td colspan='2'><b>".$DescripcionSH."</b></td>
		        </tr>
		        <tr>   
 		           <td colspan='2'>Observaci&oacute;n: </td>
		           <td colspan='2'>".$motivo."</td>
		        </tr>  		        
		        <tr>   
		           <td colspan='2'>Registrado por: </td>
		           <td colspan='2'>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
		        </tr>   
		        <tr>   
		           <td colspan='2'>A trav&eacute;s del M&oacute;dulo</td>
		           <td colspan='2'> Movimiento Temporales.</td>
		        </tr></table>";
      ENVIAR_CORREO($cuerpo,$asunto,"", $_SESSION['user_session_const'], "CAMBIO CUADRILLA", "",$_SESSION['user_session_const']."@briqven.com.ve","");
      /*************************************************/    
      echo "Procesado";    				

    } 
       
}else
	echo "De Iniciar Sesion";


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
?>
