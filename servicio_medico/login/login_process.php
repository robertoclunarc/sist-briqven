<?php
	session_start();	
	if(isset($_POST['btn-login']))
	{		
		$user_login = trim($_POST['login']);
		$user_password = trim($_POST['password']);		
		$psw = md5($user_password);
		
		try
		{	
			require_once("../include_conex.php"); 
			$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
			$cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
			$sql = "SELECT * FROM usuarios WHERE login='".$user_login."' and estatus='ACTIVO'";
			$res = pg_query($cn,$sql);
			$row = pg_fetch_array($res);
			$count = pg_num_rows($res);
			
			if($row['passw']==$psw){
				
				echo "ok"; // log in
				$_SESSION['user_session'] = $row['login'];
				$_SESSION['username']	= $row['nombres'];
				$_SESSION['userid']	= $row['email'];
				$_SESSION['nivel']	= $row['nivel'];
				$_SESSION['estatususer']= $row['estatus'];

				$_COOKIE["user_session"]=$row['login'];
				$_COOKIE["username"]= $row['nombres'];
				$_COOKIE["userid"]= $row['email'];
				$_COOKIE["nivel"]= $row['nivel'];
				$_COOKIE["estatususer"]= $row['estatus'];

				//$fecha_apertura = pg_query($cn,"update usuarios set fecha_ultima_sesion = now() where login_username='".$_SESSION['user_session']."'");
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
