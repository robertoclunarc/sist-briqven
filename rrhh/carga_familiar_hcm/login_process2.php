<?php
	session_start();
	require_once('libs/conexion.php');

	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$cedula = trim($_POST['cedula']);
		$fecha_nac = trim($_POST['fecha_nac']);
		$fecha_ingreso = trim($_POST['fecha_ingreso']);
		
		//$password = md5($user_password);
		
		try
		{	
			$cn=  Conectarse_posgres();			
		        $sql = "SELECT c.email, a.fecha_nacimiento, b.fecha_ingreso,c.login_username, a.trabajador || '123' as clave, a.trabajador, c.nivel, c.estatus FROM trabajadores a, trabajadores_grales b, usuarios c WHERE a.trabajador=b.trabajador and b.trabajador=c.trabajador and a.trabajador='" . $cedula. "' and a.fecha_nacimiento = '".$fecha_nac. "' and b.fecha_ingreso = '".$fecha_ingreso."'";
			$res = pg_query($cn,$sql);
			$row = pg_fetch_array($res);
			$count = pg_num_rows($res);
			
			if($row['trabajador']==$cedula){
				
				echo "ok"; // log in

				//echo $sql;
				$_SESSION['clave']=$row['clave'];
				$_SESSION['user_session'] = $row['login_username'];
				$_SESSION['username']	= $row['trabajador'];
				$_SESSION['userid']	= $row['login_username'];
				$_SESSION['nivel']	= $row['nivel'];
				$_SESSION['estatususer']= $row['estatus'];
				$fecha_apertura = pg_query($cn,"update usuarios set fecha_ultima_sesion = now(), login_userpass=MD5('".$_SESSION['clave']."') where login_username='".$_SESSION['user_session']."'");
			}
			else{
				
				echo "Datos del Trabajador no Coinciden"; // wrong details 
			}
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		} 		
	} 

?>
