<?PHP
session_start();
$idm=isset($_GET["idm"])?$_GET["idm"]:"NULL";
$tipo_movimiento=isset($_GET["tipomov"])?$_GET["tipomov"]:"NULL";
$estatus=isset($_GET["sta"])?$_GET["sta"]:"NULL";
$nota=isset($_GET["nota"])?$_GET["nota"]:"planilla";

if ($estatus!='NULL' && $estatus!='' && isset($_SESSION['username_ca']))	
{	
	require_once('funciones_var.php');
	$ciclo = $tipo_movimiento.' '.$estatus;
	notif_vigilantes($idm, $tipo_movimiento, $estatus, "", $_SESSION['username_ca'], $nota);
	echo ("0");
} else
     echo 'No se pudo reportar al area de Proteccion de Planta';
?>