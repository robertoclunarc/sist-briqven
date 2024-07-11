<?php 
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	include("funciones_var.php");	

	$idausencia=isset($_POST['hddIdAusencia'])?$_POST['hddIdAusencia']:'NULL';
	$cedula= isset($_POST['cbotrabajador'])?$_POST['cbotrabajador']:'NULL';
	$fecinper= isset($_POST['txtfinicio'])?$_POST['txtfinicio']:'NULL';
	$fecfinper= isset($_POST['txtffin'])?$_POST['txtffin']:'NULL';
	$turno= isset($_POST['hddturno'])?$_POST['hddturno']:'NULL';
	$sh= isset($_POST['hddsisthor'])?$_POST['hddsisthor']:'NULL';
	$npermiso= isset($_POST['hddnpermiso'])?$_POST['hddnpermiso']:'0';
	$cod_adam= isset($_POST['cbocodigo'])?$_POST['cbocodigo']:'NULL';
	$cod_ubicacion= isset($_POST['hddubicacion'])?$_POST['hddubicacion']:'NULL';
	$clase_nomina= isset($_POST['hddclasenomina'])?$_POST['hddclasenomina']:'NULL';
	$relacion_laboral= isset($_POST['hddrelacion_laboral'])?$_POST['hddrelacion_laboral']:'NULL';
	$firma=$cedula;
	$h1= isset($_POST['txthoraini'])?$_POST['txthoraini']:'00:00';
	$h2= isset($_POST['txthorafin'])?$_POST['txthorafin']:'00:00';
	$PeriodoPago= isset($_POST['hddidcalendario'])?$_POST['hddidcalendario']:'NULL';
	$autorizado= isset($_POST['hddcedtrabajadorsup'])?$_POST['hddcedtrabajadorsup']:'NULL';
	$Obs= isset($_POST['txtobservacion']) ? trim($_POST['txtobservacion']) : 'NULL';
	$DocEntregado= isset($_POST['chkDocEntregado'])?$_POST['chkDocEntregado']:'N';
	$totalParcial= isset($_POST['optionsRadiosInline'])?$_POST['optionsRadiosInline']:'NULL';
	$tperm= isset($_POST['rdPermiso'])?$_POST['rdPermiso']:'NULL';
	$nuevo= isset($_POST['hddNuevo'])?$_POST['hddNuevo']:'NULL';

	$autorizado2=isset($_POST['hddAutorizado2'])?$_POST['hddAutorizado2']:'';
	$totalParcial = $totalParcial == 'Parcial' ? 'P' : 'T';	
	
	$ccosto= isset($_POST['hddccosto'])?$_POST['hddccosto']:'NULL';
	$puesto= isset($_POST['hddpuesto'])?$_POST['hddpuesto']:'NULL';
	//$desCccosto= isset($_POST['txtccosto'])?$_POST['txtccosto']:'NULL';
	$descPuesto= isset($_POST['txtpuesto'])?$_POST['txtpuesto']:'NULL';
	$trabajadorsup= isset($_POST['txttrabajadorsup'])?$_POST['txttrabajadorsup']:'NULL';
	$disrem= isset($_POST['txtdisrem'])?$_POST['txtdisrem']:'NULL';
	$disnorem= isset($_POST['txtdisnorem'])?$_POST['txtdisnorem']:'NULL';
	$ubicacion= isset($_POST['txtubicacion'])?$_POST['txtubicacion']:'NULL';
	$condicion= isset($_POST['txtcondicion'])?$_POST['txtcondicion']:'NULL';
	$docrequerido= isset($_POST['txtdocrequerido'])?$_POST['txtdocrequerido']:'NULL';
	$estado = isset($_POST['hddEstado'])?$_POST['hddEstado']:'';
	try {			

		$finix = date_create($fecinper);
		$fini1 = date_format($finix, 'Y-m-d');

		$ffinx = date_create($fecfinper);
		$ffin2 = date_format($ffinx, 'Y-m-d');
		
		if ($h1=='') 
			$h1='00:00';
		if ($h2=='') 
			$h2='00:00';
		
		$h1 = strtotime($h1);
		$h1 = date("H:i", $h1);

		$h2 = strtotime($h2);
		$h2 = date("H:i", $h2);

		/*$spSql = "EXEC dbo.SW_Valida_datos_permiso $cedula, '$fini1', '$ffin2', $turno, $sh,$npermiso, $cod_adam, '$cod_ubicacion', '$clase_nomina', '$relacion_laboral','$firma', '$h1','$h2', $PeriodoPago, $autorizado, '$autorizado2', '$Obs'\n";
		echo $spSql;*/	

		
		$neto='';
		$ext='';		
		$respuesta="";
		$insertarBD=0;
		
		if ($nuevo=='N'){
			
			$estado='E';
			/////////////////////////aqui guarda en espera los datos del permiso en SITT/////////////
	    	$datosPermisosSitt = guardarPermisoSITT("SW_Valida_datos_permiso_espera", $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $_SESSION['cedula_session_const'], $autorizado2, $Obs, $estado, $ccosto, $puesto, $DocEntregado);
	    	$stat=$datosPermisosSitt['Estatus'];
	    	$neto= $datosPermisosSitt['neto'];
	    	$npermiso=$datosPermisosSitt['NumPermiso'];
		    $respuesta.= $datosPermisosSitt['MError'];
		    /////////////////////////aqui guarda en postgres/////////////
			$insertarBD=guardarAusenciaPermisoBD($npermiso, $cedula, $sh, $clase_nomina, $cod_adam, $estado ,$turno , $ccosto, $puesto ,$relacion_laboral, $fini1, $ffin2, $h1, $h2 ,$neto, $PeriodoPago, $DocEntregado, $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, $firma, $tperm);			
			
			if ($insertarBD === 1 && $stat==-1){
		    	$respuesta.='Registrado Correctamente!';
		    	
			}else{
				$respuesta.=" <br>ERROR Code: {postgres: $insertarBD, SiTT: $stat}";
				$respuesta.=" <br>Por Favor Contacte al Administrador de Sistema, Ext. #293.";
			}
			echo $respuesta;

		} elseif ($nuevo == 'F') {
			
			    /////////valida si es un gerente o gerente gral
			if ($_SESSION['nivel_jerarquico']==4 || $_SESSION['nivel_jerarquico']==3){
				$estado='D';				

			}else{  /////////si es un usuario con permiso de confirmar el permiso
				$link9=Conex_Contancia_pgsql();
				$confirm=permiso_usuario($link9, 'CONFIRM', 'buscar_permisos.php', $_SESSION['user_session_const']);
				pg_close($link9);

				if ($confirm!='t'){
					$respuesta="No tiene permisos para tratar este tipo de operacion";
					echo $respuesta;
					return;
				}
			}			

			if (($estado=='D' || $estado=='S' || $estado=='V') && $DocEntregado=='S'){
				$estado='S';
				echo "<br>1";
				$datosPermisosSitt = guardarPermisoSITT("SW_actualiza_datos_permiso", $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $ccosto, $puesto, $DocEntregado);

				$response = autorizarPermisoSITT($_SESSION['cedula_session_const'], $npermiso); 
				$estado='L';

				actualizarAusenciaPermisoBD($idausencia, $npermiso, $cedula, $sh, $clase_nomina, $cod_adam, $estado, $turno, $ccosto, $puesto, $relacion_laboral, $fini1, $ffin2, $h1, $h2, $neto, $PeriodoPago, $DocEntregado, $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, $firma, $tperm);

				if (isset($response['Merror'])){
 					$respuesta.="<br>*".$response['Merror'];
 					echo "<br>1.1";
 				}
 				$respuesta= (isset($response['Estatus'])) && $response['Estatus']!="-1" ? $response['Estatus'] : "<br>".$respuesta;

			} else {		
				echo "<br>2";
				if ($DocEntregado!='S'){
					$estado='D';
					echo "<br>3";
				}
				/////////////////////////aqui actualiza en SITT/////////////
				$datosActPermisosSitt = guardarPermisoSITT("SW_actualiza_datos_permiso", $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $ccosto, $puesto, $DocEntregado);

				actualizarAusenciaPermisoBD($idausencia, $npermiso, $cedula, $sh, $clase_nomina, $cod_adam, $estado, $turno, $ccosto, $puesto, $relacion_laboral, $fini1, $ffin2, $h1, $h2, $neto, $PeriodoPago, $DocEntregado, $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, $firma, $tperm);

				/////////////////////////aqui valida la informacion en SITT/////////////
				$datosValPermisosSitt = array();
				if ($estado!='D'){
					echo "3.1";
					$datosValPermisosSitt = guardarPermisoSITT("SW_Valida_datos_permiso", $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $ccosto, $puesto, $DocEntregado);
				}
				
				if (count($datosValPermisosSitt)>0){
					echo "<br>4";						
				    $stat=$datosValPermisosSitt['Estatus'];
				    $respuesta = $datosValPermisosSitt['MError'];
			 		$npermiso=$datosValPermisosSitt['Nroper'];
			 		$estado=$datosValPermisosSitt['Estado'];
			 		$neto=$datosValPermisosSitt['neto'];
			 		$ext=$datosValPermisosSitt['ext'];
			 		$totalParcial=$datosValPermisosSitt['tp'];		 		
					
					if ($stat==false){		    	
				       	$respuesta= 'En El Registro Se Encontró '.$respuesta;
				       	echo "<br>5";
				       	if ($respuesta=="Permiso No Definido Para Las Caracteristicas Laborales de esa persona Consulte al Administrador"){
				       		echo "<br>6";
				       		$respuesta= '';
				       	}
					} else {
						echo "<br>7";
				 		actualizarAusenciaPermisoBD($idausencia, $npermiso, $cedula, $sh, $clase_nomina, $cod_adam, $estado, $turno, $ccosto, $puesto, $relacion_laboral, $fini1, $ffin2, $h1, $h2, $neto, $PeriodoPago, $DocEntregado, $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, $firma, $tperm);

				 		if ($stat==-1 && $DocEntregado=='S' && $npermiso<>0)
				 		{
				 			$response = autorizarPermisoSITT($_SESSION['cedula_session_const'], $npermiso);
				 			echo "<br>8";
			 				if (isset($response['Merror'])){
			 					$respuesta.="<br>*".$response['Merror'];
			 					echo "<br>9";
			 				}
			 				$respuesta.= (isset($response['Estatus'])) && $response['Estatus']=="-1" ? "<br>Permiso Cargado y Autorizado" : "<br>".$response['Estatus'];
				 		}
					}	    
					
				}else{
					echo 'Datos Actualizados';
				}	
			}
			
			echo $respuesta;

		} else	{
			echo 'No Realizo Ninguna Operacion. Por Favor Intente Nuevamente';
		}	

	} catch (Exception $e) {
    	echo "Error: " . $e->getMessage();
	}	
	   
}	
else{
	echo "Debe Iniciar Sesion"; 
}


function guardarAusenciaPermisoBD($npermiso,$cedula,$sh,$clase_nomina,$cod_adam,$estado ,$turno ,$ccosto,$puesto ,$relacion_laboral,$fini1,$ffin2,$h1,$h2 ,$neto,$PeriodoPago,$DocEntregado,$Obs,$autorizado,$ext,$autorizado2,$cod_ubicacion,$totalParcial,$firma ,$tperm){
	$link=Conex_Contancia_pgsql();
	$anio="EXTRACT(YEAR FROM NOW())";
	try {
		$sql = "INSERT INTO sw_permisos (numpermiso, cedula, ano, cuadrilla, clasenom, cod_adam, estado, turno,centrocosto, cod_cargo, relacion_laboral, inicio, fin, horaini, horafin, neto, periodopago, anopago,fecha_proc, docs, cargado, observaciones, autorizado, extemporaneo, autorizado2, cod_ubicacion, total_parcial, firmaelectronica, tipo_notificacion) VALUES (";
	    $sql.= $npermiso.",";
	    $sql.=$cedula.", "; 
	    $sql.=$anio.", "; 
	    $sql.=$sh.", "; 
	    $sql.="'".$clase_nomina."', "; 
	    $sql.=$cod_adam.", ";        
	    $sql.="'".$estado."', ";
	    $sql.=$turno.", ";        
	    $sql.="'".$ccosto."', "; 
	    $sql.="'".$puesto."', "; 
	    $sql.="'".$relacion_laboral."', "; 
	    $sql.="'".$fini1."', "; 
	    $sql.="'".$ffin2."', ";
	    $sql.="'".$h1."', "; 
	    $sql.="'".$h2."', "; '0'."', ";        
	    $sql.="'".$neto."', "; 
	    $sql.="'".$PeriodoPago."', ";
	    $sql.=$anio.", "; 
	    $sql.="NOW(), ";        
	    $sql.= "'".$DocEntregado."',";
	    $sql.=$_SESSION['cedula_session_const'].", "; 
	    $sql.="'".$Obs."', "; 
	    $sql.="'".$autorizado."', "; 
	    $sql.="'".$ext."', "; 
	    $sql.="'".$autorizado2."', "; 
	    $sql.="'".$cod_ubicacion."', "; 
	    $sql.="'".$totalParcial."', ";
	    $sql.="'".$firma."', "; 
	    $sql.="'".$tperm."' "; 
	    $sql.=")";

	    //echo $sql;
	    $insertado = 0;
	    $result = pg_query($link,$sql);
	    if (!$result)
	    { 	
	    	 $insertado = 0;
	         // Obtener el último mensaje de error de PostgreSQL
		    $error = pg_last_error($link);

		    // Buscar el mensaje "Duplicado" en el error
		    if (strpos($error, 'Duplicado') !== false) {
		        $mensajeError = 'Error: Duplicado. Por favor, verifique los datos.<br>';
		    } else {
		        $mensajeError = 'Error en la base de datos: ' . $error;
		    }

		    // Mostrar el mensaje de error al usuario
		    echo $mensajeError;

	    }else{
	    	$bodytag = str_replace("'", " ", $sql);//para eliminar los apostrofe de la cadena
	    	auditar($bodytag, $_SESSION['user_session_const'], $link);
	    	$insertado = 1;
	    	pg_free_result($result);
	    }
		
	} catch (Exception $e) {
		$insertado = 0;
		echo $e;
	}	
	
    pg_close($link);
    return $insertado;

}

function actualizarAusenciaPermisoBD($idausencia, $npermiso, $cedula, $sh, $clase_nomina, $cod_adam, $estado, $turno, $ccosto, $puesto, $relacion_laboral, $fini1, $ffin2, $h1, $h2, $neto, $PeriodoPago, $DocEntregado, $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, $firma, $tperm) {
    $link = Conex_Contancia_pgsql();
    $anio = date('Y');
    $fecha_proc="NOW()";
    try {
    	
        $sql = "UPDATE sw_permisos SET ";
        $sql .= "cedula =".$cedula.", ";
        $sql .= "ano = ".$anio.", ";
        $sql .= "cuadrilla = ".$sh.", ";
        $sql .= "clasenom = '".$clase_nomina."', ";
        $sql .= "cod_adam = ".$cod_adam.", ";
        $sql .= "estado = '".$estado."', ";
        $sql .= "turno = ".$turno.", ";
        $sql .= "centrocosto = '".$ccosto."', ";
        $sql .= "cod_cargo = '".$puesto."', ";
        $sql .= "relacion_laboral = '".$relacion_laboral."', ";
        $sql .= "inicio = '".$fini1."', ";
        $sql .= "fin = '".$ffin2."', ";
        $sql .= "horaini = '".$h1."', ";
        $sql .= "horafin = '".$h2."', ";
        $sql .= "neto = '".$neto."', ";
        $sql .= "periodopago = ".$PeriodoPago.", ";
        $sql .= "anopago = ".$anio.", ";
        $sql .= "fecha_proc = '".$fecha_proc."', ";
        $sql .= "docs = '".$DocEntregado."', ";
        //$sql .= "cargado = ".$_SESSION['cedula_session_const'].", ";
        $sql .= "observaciones = '".$Obs."', ";
        //$sql .= "autorizado = ".$autorizado.", ";
        $sql .= "extemporaneo = '".$ext."', ";
        //$sql .= "autorizado2 = '".$autorizado2."', ";
        $sql .= "cod_ubicacion = '".$cod_ubicacion."', ";
        $sql .= "total_parcial = '".$totalParcial."', ";
        $sql .= "firmaelectronica = '".$firma."', ";
        $sql .= "tipo_notificacion = '".$tperm."', ";
        $sql .= "numpermiso = ".$npermiso." ";
        $sql .= "WHERE idausencia=".$idausencia.";";
        
        /*
        $params = [
            $cedula, $anio, $sh, $clase_nomina, $cod_adam, $estado, $turno, $ccosto, $puesto, $relacion_laboral, 
            $fini1, $ffin2, $h1, $h2, $neto, $PeriodoPago, $anio, $fecha_proc, $DocEntregado, 
            $_SESSION['cedula_session_const'], $Obs, $autorizado, $ext, $autorizado2, $cod_ubicacion, $totalParcial, 
            $firma, $tperm, $npermiso
        ];*/

        /*$sql_with_values = $sql;
	    foreach ($params as $index => $param) {
	        $sql_with_values = str_replace('$' . ($index + 1), "'" . pg_escape_string($link, $param) . "'", $sql_with_values);
	    }*/

        // Preparar y ejecutar la sentencia
        $result = pg_query($link, $sql);	    
        if (pg_affected_rows($result) == 0) {
            echo("ERROR: " . pg_last_error($link)); 
            die(" Consulta SQL: " . $sql);
        } else {
            $actualizado = 1;
            $bodytag = str_replace("'", " ", $sql);//para eliminar los apostrofe de la cadena
	    	auditar($bodytag, $_SESSION['user_session_const'], $link);
        }
        
    } catch (Exception $e) {
        $actualizado = 0;
        echo $e;
    }	
    pg_free_result($result);
    pg_close($link);
    return $actualizado;
}


function guardarPermisoSITT($sp, $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $CentroCosto, $cod_cargo, $DocEntregado){
	
	try {
		
		//echo "EXEC $sp $cedula, $fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam, $cod_ubicacion, $clase_nomina, $relacion_laboral ,$firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs";
		
		$datosPermisos = enviarPermisoSITT($sp, $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $CentroCosto, $cod_cargo, $DocEntregado);

		return $datosPermisos;

	} catch (Exception $e) {
		echo $e;
		return $datosPermisos=[];
	}

}

function autorizarPermisoSITT($autorizador, $npermiso){
	$mbd=Conectarse_sitt();	
    
    $stmt1 = $mbd->prepare("EXEC dbo.SW_Autorizar_PERMISO ?, ?");
	$stmt1->bindParam(1, $npermiso, PDO::PARAM_INT,10);	
	$stmt1->bindParam(2, $autorizador, PDO::PARAM_INT,10);
	$stmt1->execute();
	$fila1 = $stmt1->fetch();
	
	$stmt1=null;
	$mbd=null;
	
	return $fila1;
}

?>
