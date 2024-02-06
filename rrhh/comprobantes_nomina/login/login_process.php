<?php
	session_start();
	require_once('../libs/conexion_2.php');

	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$login = trim($_POST['login']);
		$user_password = trim($_POST['password']);
		
		
		$password = md5($user_password);
		
		try
		{	
			$cn=  Conectarse_posgres();			
		        $sql = "SELECT * FROM usuarios  WHERE login_username='" . $login. "' and estatus='ACTIVO'";
			$res = pg_query($cn,$sql);
			$row = pg_fetch_array($res);
			$count = pg_num_rows($res);
			
			if($row['login_userpass']==$password){
				
				echo "ok"; // log in
				$_SESSION['login_userpass'] = $user_password;	
				$_SESSION['user_session'] = $row['login_username'];				
				$_SESSION['username']	= $row['nombre'];
				$_SESSION['userid']	= $row['email'];
				$_SESSION['nivel']	= $row['nivel'];
				$_SESSION['ced']	= $row['cedula'];
				$_SESSION['estatususer']= $row['estatus'];
				$_SESSION['cant_entradas']= $row['cant_entradas'];

				$_SESSION['pregunta_secreta_1']=$row['pregunta_secreta_1'];
				$_SESSION['respuesta_secreta_1']=$row['respuesta_secreta_1'];
				$_SESSION['pregunta_secreta_2']=$row['pregunta_secreta_2'];
				$_SESSION['respuesta_secreta_2']=$row['respuesta_secreta_2'];

				pg_free_result($res);

				$fecha_apertura = pg_query($cn,"UPDATE usuarios SET  fecha_ultima_sesion=NOW(), cant_entradas=cant_entradas+1 WHERE login_username =  '".$_SESSION['user_session']."'");

				pg_free_result($fecha_apertura);
				pg_close($cn);
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