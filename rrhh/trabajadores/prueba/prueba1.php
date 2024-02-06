<?php	
	require_once('conexion.php');	
		//$user_name = $_POST['user_name'];
	/*	$user_email = trim($_POST['user_email']);
		$user_password = trim($_POST['password']);
		
		$password = md5($user_password);
		
		try
		{	
			$cn=  Conectarse();			
		        $sql = "SELECT * FROM usuarios WHERE email='" . $user_email. "'";
			$res = pg_query($cn,$sql);
			$row = pg_fetch_array($res);
			$count = pg_num_rows($res);
			
			if($row['login_userpass']==$password){
				
				echo "ok"; // log in
				$_SESSION['user_session'] = $row['login_username'];
				$_SESSION['username']	= $row['nombres'];
				$_SESSION['userid']	= $row['login_username'];
				$_SESSION['nivel']	= $row['nivel'];
				$_SESSION['estatususer']= $row['estatus'];
				$fecha_apertura = pg_query($cn,"update usuarios set fecha_ultima_sesion = now() where login_username='".$_SESSION['user_session']."'");
			}
			else{
				
				echo "Email or password no existen."; // wrong details 
			}
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		} */

		$cn=  Conectarse();
		 $sql = "SELECT * FROM usuarios";
			$res = pg_query($cn,$sql);
			$row = pg_fetch_array($res);
			echo $row['login_userpass'];
?>
