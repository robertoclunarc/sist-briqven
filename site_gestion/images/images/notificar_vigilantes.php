<?PHP
session_start();
$idm=isset($_GET["idm"])?$_GET["idm"]:"NULL";
$tipo_movimiento=isset($_GET["tipomov"])?$_GET["tipomov"]:"NULL";
$estatus=isset($_GET["sta"])?$_GET["sta"]:"NULL";
		
if ($estatus!='NULL' && $estatus!='' && isset($_SESSION['username_ca']))		
{	
	require_once('funciones_var.php');
	notif_vigilantes($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], 'AUTORIZADO', 'planilla');
	echo '0';
} else
    echo 'Este Movimiento NO se puede Autorizar';
?>