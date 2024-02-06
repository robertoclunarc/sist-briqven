<?php
require_once('../libs/conexion.php');
$fecha_apertura = pg_query($cn,"INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session']."', NOW(), 'CIERRE DE SESION'");
	session_start();
	session_destroy();
	header('Location: ./');
	exit(0);
?>
