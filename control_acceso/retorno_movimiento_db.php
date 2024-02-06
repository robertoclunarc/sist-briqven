<?PHP
require_once('libs/conexion.php');
session_start();
$Fecha = isset($_POST["txtFecha"])?$_POST["txtFecha"]:"-1";    //
$idmov=isset($_POST["hddidmovimiento"])?$_POST["hddidmovimiento"]:"NULL";
$movimiento= isset($_POST["cbomovimiento"])?$_POST["cbomovimiento"]:"";               //
$conductor= isset($_POST["txtconductor"])?$_POST["txtconductor"]:"";   //
$ciconductor= isset($_POST["txtciconductor"])?$_POST["txtciconductor"]:"NULL";     // 
$placa= isset($_POST["txtplaca"])?$_POST["txtplaca"]:"NULL";   //
$marca= isset($_POST["txtmarca"])?$_POST["txtmarca"]:"NULL";   //
$modelo= isset($_POST["txtmodelo"])?$_POST["txtmodelo"]:"NULL";   //
$color= isset($_POST["txtcolor"])?$_POST["txtcolor"]:"NULL";   //
$destino= isset($_POST["txtdestino"])?$_POST["txtdestino"]:"NULL";    //
$observacion= isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
$militar= isset($_POST["cbomilitar"])?$_POST["cbomilitar"]:"NULL";   //
$nombre_contacto= isset($_POST["txtnombre_contacto"])?$_POST["txtnombre_contacto"]:"NULL";   //
$cedula_contacto= isset($_POST["txtcedula_contacto"])?$_POST["txtcedula_contacto"]:"NULL";   //
$tlf_contacto= isset($_POST["txttlf_contacto"])?$_POST["txttlf_contacto"]:"NULL";   //
$unidad_adscripcion= isset($_POST["txtunidad_adscripcion"])?$_POST["txtunidad_adscripcion"]:"NULL";   //
$nombre_destinatario= isset($_POST["txtnombre_destinatario"])?$_POST["txtnombre_destinatario"]:"NULL";   //

if ($Fecha!="-1") {

		$cn=  Conectarse();			

		$queryy = "INSERT INTO movimientos_retornos (";
		$queryy .= "fkmovimiento, ";
		$queryy .= "fecha_hora, ";
		$queryy .= "destino, ";
		$queryy .= "tipo_movimiento,";
		$queryy .= "conductor, ";
		$queryy .= "cedula, ";
		$queryy .= "marca, ";
		$queryy .= "modelo, ";
		$queryy .= "colores, ";
		$queryy .= "placa, ";
		$queryy .= "observaciones, ";
		$queryy .= "fkguardia_turno, ";		
		$queryy .= "nombre_contacto, ";
		$queryy .= "cedula_contacto, ";
		$queryy .= "tlf_contacto, ";
		$queryy .= "unidad_adscripcion, ";
		$queryy .= "nombre_destinatario ";
		$queryy .= ") VALUES (";
		$queryy .= $idmov.", ";
		$queryy .= "NOW(), ";		
		$queryy .= "'" . $destino."', ";
		$queryy .= "'".$movimiento."', ";
		$queryy .= "'".$conductor."', ";
		$queryy .= "'".$ciconductor."', ";
		$queryy .= "'".$marca."', ";
		$queryy .= "'".$modelo."', ";
		$queryy .= "'".$color."', ";
		$queryy .= "'".$placa."', ";;
		$queryy .= "'".$observacion."', ";
		$queryy .= $militar.", ";		
		$queryy .= "'".$nombre_contacto."', ";
		$queryy .= "'".$cedula_contacto."', ";
		$queryy .= "'".$tlf_contacto."', ";
		$queryy .= "'".$unidad_adscripcion."', ";		
		$queryy .= "'".$nombre_destinatario."' ";		
		$queryy .= ")  returning fecha_hora;";

		$resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL: " . $queryy);
		$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
		$fecha_retorno=$reg['fecha_hora'];
		pg_free_result($resultado);
		
		if (!$resultado) {
			echo "0";
		  	die("Ocurrió un error.\n " .  $queryy);
		}
		else
		{
			require_once('funciones_var.php');
			$usuarios_movimientos=datos_usuarios($_SESSION['user_session_ca'], $_SESSION['unidad_ca'], $cn);
			$estatus="SOLITADO";
			$qUserMov = "INSERT INTO usuarios_movimientos (";
			$qUserMov.= "fkmovimiento_part,";
			$qUserMov.= "login_participante,";
			$qUserMov.= "operacion,";			
			$qUserMov.= "unidad,";
			$qUserMov.=	"email,";
			$qUserMov.=	"nombre,";
			$qUserMov.=	"cedula,";
			$qUserMov.=	"ccosto,";
			$qUserMov.=	"fecha_hora_acceso,";
			$qUserMov.=	"estatus,";
			$qUserMov.=	"cargo) VALUES (";			
			$qUserMov.= $idmov.", ";
			$qUserMov.= "'".$usuarios_movimientos['login_pen']."', ";			
			$qUserMov.= "'RETORNO', ";
			$qUserMov.= "'".$usuarios_movimientos['desccosto_pen']."', ";
			$qUserMov.= "'".$usuarios_movimientos['email_pen']."', ";
			$qUserMov.= "'".$usuarios_movimientos['nombre_pen']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ci_pen']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ccosto_pen']."', ";
			$qUserMov.= "'".$fecha_retorno."', ";
			$qUserMov.= "'VALIDADO', ";
			$qUserMov.= "'".$usuarios_movimientos['cargo_pen']."' ";			
			$qUserMov.= ") ";
			
			$result = pg_query($cn, $qUserMov) or die("Error en la Consulta SQL: " . $qUserMov);
			if ($result){
				
				if(array_key_exists('material',$_POST))
				{
					$codmateriales = $_POST['material'];	
					$cantidades = $_POST['cantidad'];															
					$unidades = $_POST['unidad'];
					$vales = $_POST['vale'];

					$cantrets = $_POST['hddcantret'];
					$materiales_retonos=$_POST['hdddescrip'];	
					
					foreach ($codmateriales as $i => $mater)
					{						
						$cantidad = $cantidades[$i];						
						$unidad = $unidades[$i];
						$vale = $vales[$i];
						$item=$i+1;
						$clave = array_search($mater, $materiales_retonos);
						$cantidad_restante = $cantrets[$clave]-$cantidad;
						$query = "INSERT INTO detalles_movimientos_retornos (fkmovimiento, items, cantidad, unidad_medicion, serial_nro_almacen, descripcion, fecha_retorno, cantidad_restante) VALUES (";
						$query = $query . $idmov . ", " . $item . ", '" . $cantidad . "', '" . $unidad . "', '" . $vale . "', '" . $mater .  "', '" . $fecha_retorno."', '".$cantidad_restante."');";		
						//echo($query);
						$resultado = pg_query($cn, $query) or die("Error en la Consulta SQL: ".$query);
					}
					$unidades_ret= $_POST['hddunid'];
					$vales_ret= $_POST['hddvale'];
					foreach ($materiales_retonos as $j => $materj)
					{						
						if (array_search($materj, $codmateriales)===FALSE){
							//echo array_search($materj, $codmateriales)." ind<br>".$materj." mat;";
							$item++;
							$unidad=$unidades_ret[$j];
							$vale=$vales_ret[$j];
							$query = "INSERT INTO detalles_movimientos_retornos (fkmovimiento, items, cantidad, unidad_medicion, serial_nro_almacen, descripcion, fecha_retorno, cantidad_restante) VALUES (";
							$query = $query . $idmov . ", " . $item . ", '0', '" . $unidad . "', '" . $vale . "', '" . $materj .  "', '" . $fecha_retorno."', '".$cantrets[$j]."');";
							$rsltd = pg_query($cn, $query) or die("Error en la Consulta SQL: ".$query);
						}	
					}
				}			
				
				enviar_nota($idmov, $movimiento, 'RETORNO', "", $_SESSION['username_ca'], 'AUTORIZADO', 'planilla');
				enviar_nota($idmov, $movimiento, 'RETORNO', "", $_SESSION['username_ca'], 'CONFORMADO', 'planilla');
				enviar_nota($idmov, $movimiento, 'RETORNO', "", $_SESSION['username_ca'], 'SOLICITADO', 'planilla');
				enviar_nota($idmov, $movimiento, 'RETORNO', "", $_SESSION['username_ca'], 'PENDIENTE', 'planilla');
				enviar_nota($idmov, $movimiento, 'RETORNO', "", $_SESSION['username_ca'], 'nota_patrimonial', 'planilla');	
				
				echo ($idmov); //OK
			
			}else{
				
				echo "0";
			}			
		}
}
?>