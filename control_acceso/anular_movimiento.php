<?PHP
session_start();
$idm=isset($_GET["idm"])?$_GET["idm"]:"NULL";
$tipo_movimiento=isset($_GET["tipomov"])?$_GET["tipomov"]:"NULL";
$estatus=isset($_GET["sta"])?$_GET["sta"]:"NULL";
$motivo_nulacion=isset($_GET["r"])?$_GET["r"]:"NULL";
		
if ($estatus!='NULL' && $estatus!='' && isset($_SESSION['username_ca']))		
{	
	require_once('libs/conexion.php');
	$cn=Conectarse();
	
	$ciclo = $tipo_movimiento.' '.$estatus;
	$queryUpd = "UPDATE movimientos SET ";
	$queryUpd .="estatus ='".$estatus."', ";
	$queryUpd .="ciclo = '".$ciclo."', ";
	$queryUpd .="motivo_nulacion = '".$motivo_nulacion."' ";
	$queryUpd .="WHERE idmovimiento=".$idm."; ";
	
	$resultado = pg_query($cn, $queryUpd);
				
	if (!$resultado) {			
		echo "Ocurrió un error.\n";
		die("Error en la Consulta SQL:" . $queryUpd);
	  	exit;
	}
	else
	{
		$qUserMov = "UPDATE usuarios_movimientos SET ";
		$qUserMov.= "fecha_hora_acceso = NOW(), ";
		$qUserMov.=	"estatus='".$estatus."' ";
		$qUserMov.=	"WHERE fkmovimiento_part = ".$idm;			
		$qUserMov.=	" AND login_participante = '".$_SESSION['user_session_ca']."';";
		$result = pg_query($cn, $qUserMov) or die("Error en la Consulta SQL:" . $qUserMov);

		$ciclo .= " Movimiento Nro.".$idm;

		$auditoria = pg_query($cn,"INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session_ca']."', NOW(), '".$ciclo."')");

		echo ("0"); //OK
	}
	pg_close($cn);
} else
     echo 'Este Movimiento NO se puede Anular';
?>