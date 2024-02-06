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
			$_SESSION['user_session_sio'] = $row['login'];			
			$_SESSION['username_sio']	= $row['nombres'];
			$_SESSION['userid_sio']	= $row['email'];
			$_SESSION['nivel_sio']	= $row['nivel'];
			$_SESSION['estatususer_sio']= $row['estatus'];
			$fecha_apertura = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'ACCESO AL SISTEMA')");
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