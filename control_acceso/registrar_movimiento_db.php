<?PHP
require_once('libs/conexion.php');
session_start();
$Fecha = isset($_POST["txtFecha"])?$_POST["txtFecha"]:"-1";    //
//$idunidadautoriza= isset($_POST["hidunidadautoriza"])?$_POST["hidunidadautoriza"]:"";         //
$fecharetorno= isset($_POST["txtfecharetorno"])?"'".$_POST["txtfecharetorno"]."'":"NULL";   // 
$movimiento= isset($_POST["cbomovimiento"])?$_POST["cbomovimiento"]:"";               // 
$retorna= isset($_POST["cboretorna"])?$_POST["cboretorna"]:"";   // 
$orden= isset($_POST["txtorden"])?$_POST["txtorden"]:"NULL";     
$conductor= isset($_POST["txtconductor"])?$_POST["txtconductor"]:"";   //
$ciconductor= isset($_POST["txtciconductor"])?$_POST["txtciconductor"]:"NULL";     // 
$placa= isset($_POST["txtplaca"])?$_POST["txtplaca"]:"NULL";   //
$marca= isset($_POST["txtmarca"])?$_POST["txtmarca"]:"NULL";   //
$modelo= isset($_POST["txtmodelo"])?$_POST["txtmodelo"]:"NULL";   //
$color= isset($_POST["txtcolor"])?$_POST["txtcolor"]:"NULL";   //
$destino= isset($_POST["txtdestino"])?$_POST["txtdestino"]:"NULL";    //
$observacion= isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";  
$objetivo= isset($_POST["cboobjetivo"])?$_POST["cboobjetivo"]:"NULL";   //
$militar= isset($_POST["cbomilitar"])?$_POST["cbomilitar"]:"NULL";   //
//$login_confirma= isset($_POST["hidlogin_confirma"])?$_POST["hidlogin_confirma"]:"NULL";   //
//$cedula_confimar= isset($_POST["hidcedula_confimar"])?$_POST["hidcedula_confimar"]:"NULL";   //
//$nombre_confimar= isset($_POST["hidnombre_confimar"])?$_POST["hidnombre_confimar"]:"NULL";   //
//$email_confimar= isset($_POST["hidemail_confimar"])?$_POST["hidemail_confimar"]:"NULL";   //
$nombre_contacto= isset($_POST["txtnombre_contacto"])?$_POST["txtnombre_contacto"]:"NULL";   //
$cedula_contacto= isset($_POST["txtcedula_contacto"])?$_POST["txtcedula_contacto"]:"NULL";   //
$tlf_contacto= isset($_POST["txttlf_contacto"])?$_POST["txttlf_contacto"]:"NULL";   //
$unidad_adscripcion= isset($_POST["txtunidad_adscripcion"])?$_POST["txtunidad_adscripcion"]:"NULL";   //
$nombre_destinatario= isset($_POST["txtnombre_destinatario"])?$_POST["txtnombre_destinatario"]:"NULL";   //

if ($Fecha!="-1" && isset($_SESSION['username_ca'])) {

		$cn=  Conectarse();
		//$ciclo="COMPLETADO";
		//if ($retorna=='SI')
		$ciclo="EN ESPERA";
		$estatus="SOLICITADO";
		require_once('funciones_var.php');
		$usuarios_movimientos=datos_usuarios($_SESSION['user_session_ca'], $_SESSION['unidad_ca'], $cn);
		if (array_key_exists('nombre_pen', $usuarios_movimientos))
			$estatus="PENDIENTE";

		$queryy = "INSERT INTO movimientos (";
		$queryy .= "fecha_hora, ";
		$queryy .= "destino, ";
		$queryy .= "tipo_movimiento,";
		$queryy .= "retorna, ";
		$queryy .="fecha_retorno, ";
		$queryy .= "orden_compra, ";
		$queryy .= "objetivo_movimiento, ";
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
		$queryy .= "nombre_destinatario, ";
		$queryy .= "estatus, ";
		$queryy .= "ciclo) VALUES (";
		$queryy .= "NOW(), ";		
		$queryy .= "'" . $destino."', ";
		$queryy .= "'".$movimiento."', ";
		$queryy .= "'".$retorna."', ";
		$queryy .= $fecharetorno . ", " ;
		$queryy .= "'".$orden."', ";
		$queryy .= $objetivo.", ";
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
		$queryy .= "'".$nombre_destinatario."', ";
		$queryy .= "'".$estatus."', ";		
		$queryy .= "'".$ciclo."') returning idmovimiento;";

		$resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
		$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
		$idmovimiento_seq=$reg['idmovimiento'];
		pg_free_result($resultado);
		
		if (!$resultado) {
			echo "0";
		  	die("Ocurrió un error.\n " . $qUserMov);
		}
		else
		{			
			$qUserMov = "INSERT INTO usuarios_movimientos (";
			$qUserMov.= "fkmovimiento_part,";
			$qUserMov.= "login_participante,";
			$qUserMov.= "operacion,";			
			$qUserMov.= "unidad,";
			$qUserMov.=	"email,";
			$qUserMov.=	"nombre,";
			$qUserMov.=	"cedula,";
			$qUserMov.=	"ccosto,";
			$qUserMov.=	"cargo) VALUES (";			
			$qUserMov.= $idmovimiento_seq.", ";
			$qUserMov.= "'".$usuarios_movimientos['login_sol']."', ";			
			$qUserMov.= "'".$usuarios_movimientos['operacion_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['desccosto_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['email_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['nombre_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ci_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ccosto_sol']."', ";
			$qUserMov.= "'".$usuarios_movimientos['cargo_sol']."' ";			
			$qUserMov.= "), ";

			if ($estatus=="PENDIENTE") {
			    $qUserMov.=	"(";			
				$qUserMov.= $idmovimiento_seq.", ";
				$qUserMov.= "'".$usuarios_movimientos['login_pen']."', ";			
				$qUserMov.= "'".$usuarios_movimientos['operacion_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['desccosto_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['email_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['nombre_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['ci_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['ccosto_pen']."', ";
				$qUserMov.= "'".$usuarios_movimientos['cargo_pen']."' ";			
				$qUserMov.= "), ";
				
			}

			$qUserMov.=	"(";			
			$qUserMov.= $idmovimiento_seq.", ";
			$qUserMov.= "'".$usuarios_movimientos['login_conf']."', ";			
			$qUserMov.= "'".$usuarios_movimientos['operacion_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['desccosto_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['email_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['nombre_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ci_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['ccosto_conf']."', ";
			$qUserMov.= "'".$usuarios_movimientos['cargo_conf']."' ";			
			$qUserMov.= "); ";
			
			$result = pg_query($cn, $qUserMov) or die("Error en la Consulta SQL:" . $qUserMov);
			if ($result){

				$updatemovuser = pg_query($cn, "UPDATE usuarios_movimientos SET fecha_hora_acceso=NOW(), estatus='".$estatus."' WHERE fkmovimiento_part=".$idmovimiento_seq." AND login_participante='".$_SESSION['user_session_ca']."';") or die("Error en la Consulta SQL:" . $qUserMov);

				$i=0;
				if(array_key_exists('material',$_POST))
				{
					$codmateriales = $_POST['material'];	
					$cantidades = $_POST['cantidad'];
					$unidades = $_POST['unidad'];
					$vales = $_POST['vale'];	
					
					foreach ($codmateriales as &$mater)
					{
						$cantidad = $cantidades[$i];
						$unidad = $unidades[$i];
						$vale = $vales[$i];
						$item=$i+1;
						$query = "INSERT INTO detalles_movimientos(fkmovimiento, items, cantidad, unidad_medicion, serial_nro_almacen, descripcion) VALUES (";
						$query = $query . $idmovimiento_seq . ", " . $item . ", '" . $cantidad . "', '" . $unidad . "', '" . $vale . "', '" . $mater . "');";		
						//echo($query);
						$resultado = pg_query($cn, $query) or die("Error en la Consulta SQL:".$query);
					
						$i++;
					}
				}
				
				if (array_key_exists('nombre_pen', $usuarios_movimientos))			
					cuerpo_correo($idmovimiento_seq, $movimiento, $usuarios_movimientos['operacion_pen'], $usuarios_movimientos['email_sol'], $usuarios_movimientos['email_pen'], $usuarios_movimientos['nombre_pen']);
				
				cuerpo_correo($idmovimiento_seq, $movimiento, $usuarios_movimientos['operacion_sol'], $usuarios_movimientos['email_conf'], $usuarios_movimientos['email_sol'], $usuarios_movimientos['nombre_sol']);

				$ciclo .= " Movimiento Nro.".$idmovimiento_seq;

				$auditoria = pg_query($cn,"INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session_ca']."', NOW(), '".$ciclo."')");
				
				echo ($idmovimiento_seq); //OK
			
			}else{
				$deltemov = pg_query($cn, "DELETE FROM usuarios_movimientos WHERE fkmovimiento_part=".$idmovimiento_seq) or die("Error en la Consulta SQL: DELETE FROM usuarios_movimientos");
				if ($deltemov)
					$altermov = pg_query($cn, "ALTER SEQUENCE iditemautorizado_seq RESTART WITH ".$idmovimiento_seq) or die("Error en la Consulta SQL: ALTER SEQUENCE iditemautorizado_seq");
				echo "0";
			}			
		}
		pg_close($cn);
}
?>