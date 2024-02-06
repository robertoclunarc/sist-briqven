<?php
session_start();
require_once('../libs/conexion.php');
if(isset($_POST['btn-login']))
{		
	$login = trim($_POST['login']);
	$user_password = trim($_POST['password']);		
	$password = md5($user_password);		
	try
	{	
		$cn=Conectarse();			
	    $sql = "SELECT * FROM usuarios  WHERE login='" . $login. "' and estatus='ACTIVO'";
		$res = pg_query($cn,$sql);
		$row = pg_fetch_array($res);
		$count = pg_num_rows($res);
		if($row['passw']==$password){				
			echo "ok"; // log in
			$_SESSION['user_session_ca'] = $row['login'];
			$_SESSION['unidad_ca'] = $row['fkunidad'];
			$_SESSION['username_ca']	= $row['nombres'];
			$_SESSION['userid_ca']	= $row['email'];
			$_SESSION['nivel_ca']	= $row['nivel'];
			$_SESSION['estatususer_ca']= $row['estatus'];
			$_SESSION['permisoadic_ca']= $row['permiso_adicional'];
			$fecha_apertura = pg_query($cn,"INSERT INTO historial_accesos (login, fecha_hora , descripcion_accion) VALUES ('".$_SESSION['user_session_ca']."', NOW(), 'ACCESO AL SISTEMA')");
		}
		else{				
			echo "Usuario o Clave Incorrectas"; // wrong details 
		}				
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}
?>
