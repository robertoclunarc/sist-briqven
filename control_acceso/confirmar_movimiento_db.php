<?PHP
session_start();
$idm=isset($_GET["idm"])?$_GET["idm"]:"NULL";
$tipo_movimiento=isset($_GET["tipomov"])?$_GET["tipomov"]:"NULL";
$estatus=isset($_GET["sta"])?$_GET["sta"]:"NULL";

if ($estatus!='NULL' && $estatus!='' && isset($_SESSION['username_ca']))	
{	
	require_once('libs/conexion.php');
	require_once('funciones_var.php');
	$cn=Conectarse();
	/*$query="SELECT u.login, v.turno, u.email, u.nombres, u.nivel FROM usuarios u, v_trabajadores v WHERE u.cedula = v.trabajador AND u.login='".$_SESSION['user_session_ca']."'";
			$resultado2 = pg_query($cn, $query) or die("Error en la Consulta SQL:" . $query);
			$fila=pg_fetch_array($resultado2);
			$turno  = $fila["turno"];
			pg_free_result($resultado2);*/
	
		$ciclo = $tipo_movimiento.' '.$estatus;
		
		$queryUpd = "UPDATE movimientos SET ";
		$queryUpd .="estatus ='".$estatus."', ";
		$queryUpd .="ciclo = '".$ciclo."' ";
		$queryUpd .="WHERE idmovimiento=".$idm."; ";
	
		$queryIns = "UPDATE usuarios_movimientos SET ";
		$queryIns .="fecha_hora_acceso=NOW(), ";
		$queryIns .="estatus='".$estatus."' ";
		$queryIns .="WHERE fkmovimiento_part=".$idm;
		$queryIns .=" AND login_participante = '".$_SESSION['user_session_ca']."'; ";

		if ($estatus=='AUTORIZADO'){
			$queryDel = "DELETE FROM usuarios_movimientos";
			$queryDel .="  WHERE operacion = 'AUTORIZADO'";
			$queryDel .="  AND estatus isnull";
			$queryDel .="  AND fkmovimiento_part=".$idm;
			$queryDel .="  AND login_participante <> '".$_SESSION['user_session_ca']."';";
		}	
	
		$resultado = pg_query($cn, $queryUpd.$queryIns.$queryDel);
				
		if (!$resultado) {			
			echo "Ocurrió un error.\n";
			die("Error en la Consulta SQL:" . $queryUpd.$queryIns.$queryDel);
		  	exit;
		}
		else
		{
			if ($estatus=='CONFORMADO'){

				$qUserMov = "INSERT INTO usuarios_movimientos(";
				$qUserMov.= "fkmovimiento_part, login_participante, operacion,";
				$qUserMov.= "unidad, email, nombre, cedula, ccosto, cargo)";
				$qUserMov.= "SELECT ".$idm.", ";			
				$qUserMov.= "login, ";
				$qUserMov.=	"'AUTORIZADO', ";
				$qUserMov.=	"descripcion_unidad, ";
				$qUserMov.=	"email, ";
				$qUserMov.=	"nombres, ";
				$qUserMov.=	"cedula, ";			
				$qUserMov.= "fkunidad, ";
				$qUserMov.= "cargo ";			
				$qUserMov.= "FROM v_usuarios_unidades ";
				$qUserMov.= "where nivel=1 and estatus='ACTIVO'";
				$result = pg_query($cn, $qUserMov) or die("Error en la Consulta SQL:" . $qUserMov);

				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'AUTORIZADO', 'planilla');
				//sleep(4);				
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'CONFORMADO', 'notificacion');
				//sleep(4);
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'SOLICITADO', 'notificacion');
				//sleep(4);
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'PENDIENTE', 'notificacion');

			}elseif ($estatus=='SOLICITADO'){
				
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'CONFORMADO', 'planilla');
				//sleep(4);
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'PENDIENTE', 'notificacion');

			}else{
				//notif_vigilantes($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'AUTORIZADO', 'planilla');
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'AUTORIZADO', 'notificacion');
				//sleep(4);	
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'CONFORMADO', 'notificacion');
				//sleep(4);
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'SOLICITADO', 'notificacion');
				//sleep(4);
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'PENDIENTE', 'notificacion');
			}
			$qaud="INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session_ca']."', NOW(), '".$ciclo."')";
			$auditoria = pg_query($cn, $qaud) or die("Error en la Consulta SQL:" . $qaud);
			echo ("0"); //OK
		}
		pg_close($cn);
} else
     echo 'Este Movimiento NO se puede Solicitar';
?>