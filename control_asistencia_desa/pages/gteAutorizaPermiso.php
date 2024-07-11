<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');

	try {
		$link5=Conex_Contancia_pgsql();
	
		$tiponotif= isset($_GET["tiponotif"])?$_GET["tiponotif"]:"NULL";
		$idausencia = isset($_GET["idausencia"])?$_GET["idausencia"]:"NULL";

		$query="UPDATE sw_permisos SET estado='S', autorizado2='".$_SESSION['cedula_session_const']."' WHERE idausencia=".$idausencia;
		$result = pg_query($link5,$query);
		
		$response=actualizarPermisoSITT($link5, $idausencia);

		if (pg_affected_rows($result)>0){
			$operacion=$tiponotif.' estado=S Aprobado por '.$_SESSION['cedula_session_const'].'; idausencia='.$idausencia;
			//echo $operacion;
			auditar($operacion, $_SESSION['user_session_const'], $link5);
			$response="<br>$tiponotif $response Aprobado y Procesado";
		}else{
			$response.="<br>Hubo un problema, Consulte con el Administrador de Sistema. #293";
		}
		
		pg_close($link5);
		echo $response;
	} catch (Exception $e) {
		echo $e;
	}

}
else{
	echo "Debe Iniciar Sesion";
}

function actualizarPermisoSITT($linkTmp, $idausencia){
	try {
		$qry="SELECT * FROM sw_permisos WHERE idausencia = $idausencia";
		$rslt = pg_query($linkTmp,$qry);
		$row = pg_fetch_array($rslt);

        $cedula = $row['cedula'];
		$fini1 = substr($row['inicio'], 0,10);
		$ffin2 =  substr($row['fin'], 0,10);
		$turno = $row['turno']; 
		$sh = $row['cuadrilla']; 
		$npermiso = $row['numpermiso']; 
		$cod_adam  = $row['cod_adam']; 
		$cod_ubicacion = $row['cod_ubicacion']; 
		$clase_nomina = $row['clasenom']; 
		$relacion_laboral  = $row['relacion_laboral']; 
		$firma = $row['firmaelectronica']; 
		$h1 = $row['horaini']; 
		$h2 = $row['horafin']; 
		$PeriodoPago = $row['periodopago']; 
		$autorizado = $row['autorizado']; 
		$autorizado2 = $row['autorizado2']; 
		$Obs = $row['observaciones']; 
		$estado = $row['estado']; 
		$CentroCosto = $row['centrocosto']; 
		$cod_cargo = $row['cod_cargo'];
		$DocEntregado = $row['docs'];

		$datosPermisosSitt = enviarPermisoSITT("SW_actualiza_datos_permiso", $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $CentroCosto, $cod_cargo, $DocEntregado);

		pg_free_result($rslt);

		$respuesta="";
		if (count($datosPermisosSitt)>0){										
		    $stat = isset($datosPermisosSitt['Estatus']) ? $datosPermisosSitt['Estatus'] : "";
		    $respuesta = isset($datosPermisosSitt['MError']) ? $datosPermisosSitt['MError'] : "";
	 		$npermiso= isset($datosPermisosSitt['Nroper']) ? $datosPermisosSitt['Nroper'] : "";
	 		$estado= isset($datosPermisosSitt['Estado']) ? $datosPermisosSitt['Estado'] : "";
	 		$neto= isset($datosPermisosSitt['neto']) ? $datosPermisosSitt['neto'] : "";
	 		$ext= isset($datosPermisosSitt['ext']) ? $datosPermisosSitt['ext'] : "";
	 		$totalParcial= isset($datosPermisosSitt['tp']) ? $datosPermisosSitt['tp'] : "";
	 		$totalParcial= $totalParcial=="P" ? "Parcial" : "Total";
	 		/*echo $stat."<br>";
	 		echo $neto."<br>";
	 		echo $estado."<br>";
	 		echo $totalParcial."<br>";
	 		echo $ext."<br>";*/
	 		
	 		if ($stat != ""){
		       	$respuesta= "Falla en la Carga. code: $stat. ".$respuesta;		       	
			}else{
				$respuesta="$totalParcial Nro. $idausencia de $neto Horas";
			}
		}

		return $respuesta;

	} catch (Exception $e) {
		return $e;
	}

}
?>
