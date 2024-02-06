<?php
	session_start();	
	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_login = trim($_POST['user']);
		$user_password = trim($_POST['password']);		
		$psw = md5($user_password);
		
		try
		{	
			include("BD/conexion.php");
			$link=Conex_Contancia_pgsql();
			$result = pg_query($link, "SELECT * FROM tbl_usuarios WHERE login_username='".$user_login."' and estatus='ACTIVO'");
			$row = pg_fetch_array($result);			
			$count = pg_num_rows($result);			
			
			if($row['login_userpass']==$psw){
				
				echo "ok"; // log in
				$_SESSION['user_session_conslab'] = $row['login_username'];
				$_SESSION['username_conslab']	= $row['nombres'];				
				$_SESSION['userid_conslab']	= $row['email'];
				$_SESSION['nivel_conslab']	= $row['nivel'];
				$_SESSION['estatususer_conslab']= $row['estatus'];				
			}
			else{
				
				echo "Login y/o password no coinciden."; // wrong details
				 
			}
			
 			pg_free_result($result);
 			pg_close($link);
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		} 		
	}
?>
