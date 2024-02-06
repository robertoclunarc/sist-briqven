<?php
	session_start();
	require_once('../libs/conexion.php');

	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_email = trim($_POST['user_email']);
		$user_password = trim($_POST['password']);
		
		$password = md5($user_password);
		
		try
		{	
			$cn=  Conectarse();			
		        $sql = "SELECT * FROM usuarios WHERE email='" . $user_email. "' and estatus='ACTIVO'";
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
				//REGISTRA LA ULTIMA VEZ QUE SE REGISTRO EL USUARIO
				$fecha_apertura = pg_query($cn,"update usuarios set fecha_ultima_sesion = now() where login_username='".$_SESSION['user_session']."'");
				//CONSULTA EL PERIOS ACTUAL
				$periodoActivo = pg_query($cn,"select mesp, anhop, idperiodo from periodos where estatusp='ABIERTO'");
				$perAct = pg_fetch_array($periodoActivo);
				$_SESSION['mesact'] = $perAct['mesp'];
				$_SESSION['anact'] = $perAct['anhop'];
				$_SESSION['idact'] = $perAct['idperiodo'];
				// CUENTAS LAS NOTAS DISPONIBLES SIN LEER
				$notas=  pg_query($cn,"SELECT count(*) as nronotas FROM notas WHERE leida='N'");
				$row_notas = pg_fetch_array($notas, null, PGSQL_ASSOC);
				$_SESSION['notas']=$row_notas['nronotas'];

				pg_free_result($res);
				pg_free_result($periodoActivo);
				pg_free_result($notas);
			}
			else{
				
				echo "Email or password no existen."; // wrong details 
			}
			pg_close($cn);	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

?>
