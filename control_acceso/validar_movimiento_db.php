<?PHP
session_start();
$idm=isset($_GET["idm"])?$_GET["idm"]:"NULL";
$tipo_movimiento=isset($_GET["tipomov"])?$_GET["tipomov"]:"NULL";
$estatus=isset($_GET["sta"])?$_GET["sta"]:"NULL";
$retorna=isset($_GET["ret"])?$_GET["ret"]:"NULL";
		
if ($estatus!='NULL' && $estatus!='' && isset($_SESSION['username_ca']))		
{	
	require_once('libs/conexion.php');
	$cn=Conectarse();
	$query="SELECT u.login, v.turno, u.email, u.nombres, u.nivel FROM usuarios u, v_trabajadores v WHERE u.cedula = v.trabajador AND u.login='".$_SESSION['user_session_ca']."'";
			$resultado2 = pg_query($cn, $query) or die("Error en la Consulta SQL:" . $query);
			$fila=pg_fetch_array($resultado2);
			$turno  = $fila["turno"];
			pg_free_result($resultado2);
	
		$ciclo = $tipo_movimiento.' PEND RETORNO';
		if ($retorna=='NO')
			$ciclo="COMPLETADO";
		
		$queryUpd = "UPDATE movimientos SET ";
		$queryUpd .="estatus ='".$estatus."', ";
		$queryUpd .="ciclo = '".$ciclo."' ";
		$queryUpd .="WHERE idmovimiento=".$idm.";";
	
		$resultado = pg_query($cn, $queryUpd);
				
		if (!$resultado) {			
			echo "Ocurrió un error.\n";
			die("Error en la Consulta SQL:" . $queryUpd);
		  	exit;
		}
		else
		{
			$qUserMov = "INSERT INTO usuarios_movimientos(";
			$qUserMov.= "fkmovimiento_part, fecha_hora_acceso, login_participante, operacion,";
			$qUserMov.= "unidad, email, nombre, cedula, ccosto, cargo, estatus, turno)";
			$qUserMov.= "SELECT ".$idm.", ";			
			$qUserMov.= "NOW(), login, ";
			$qUserMov.=	"'".$estatus."', ";
			$qUserMov.=	"descripcion_unidad, ";
			$qUserMov.=	"email, ";
			$qUserMov.=	"nombres, ";
			$qUserMov.=	"cedula, ";			
			$qUserMov.= "fkunidad, ";
			$qUserMov.= "cargo, ";
			$qUserMov.= "'".$estatus."', ";
			$qUserMov.= $turno." ";			
			$qUserMov.= "FROM v_usuarios_unidades ";
			$qUserMov.= "where login = '".$_SESSION['user_session_ca']."';";
			$result = pg_query($cn, $qUserMov) or die("Error en la Consulta SQL:" . $qUserMov);

			$auditoria = pg_query($cn,"INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session_ca']."', NOW(), 'Nro. Movimiento ".$idm.". ".$ciclo."')");

			require_once('funciones_var.php');

				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'AUTORIZADO', 'notificacion');
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'CONFORMADO', 'notificacion');
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'SOLICITADO', 'notificacion');
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'PENDIENTE', 'notificacion');
				enviar_nota($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'nota_patrimonial', 'planilla');

			echo ("0"); //OK
		}
		pg_close($cn);
} else
     echo 'Este Movimiento NO se puede Validar';
?>