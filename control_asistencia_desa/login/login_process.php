<?php
	session_start();	
	require_once('../pages/funciones_var.php');
	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_login = trim($_POST['login']);
		$user_password = trim($_POST['password']);		
		$psw = md5($user_password);
		$modeBlack=isset($_POST['chkModeBlack'])?$_POST['chkModeBlack']:"default";
		try
		{	
			include("../BD/conexion.php");
			$link=Conex_Contancia_pgsql();
			//$result = ejecutar_query($link, "SELECT * FROM usuarios WHERE login_username='".$user_login."' and estatus='ACTIVO'");
			$query="select t.trabajador,login_username,nombres,apellidos,email,nivel,estatus,login_userpass, v.nivel_jerarquico , v.ccosto
	from USUARIOS u inner join trabajadores t on u.trabajador=t.trabajador and estatus='ACTIVO'	inner join adam_vw_dotacion_briqven_02_mas v on u.trabajador=v.trabajador where login_username='".$user_login."' ";
			$result = ejecutar_query($link, $query);
//print $query;
			$row = ejecutar_fetch_array($result);			
			$count = ejecutar_num_rows($result);			
//print $row['login_userpass'].'='.$psw;
			if($row['login_userpass']==$psw && $count>0){
				$_SESSION['modeBlack_const'] = $modeBlack;
			 	if ($row['nivel']==5){
			 		
			 		echo "N5|".$modeBlack; // limitados

			 		$_SESSION['cedula_session_const'] = $row['trabajador'];
					$_SESSION['user_session_const'] = $row['login_username'];
					$_SESSION['username_const']	= $row['nombres'].' '.$row['apellidos'];
					$_SESSION['nivel_const']	= $row['nivel'];
					$_SESSION['nivel_jerarquico']	= $row['nivel_jerarquico'];
					$_SESSION['ccosto']	= $row['ccosto'];					
                }else{
                	
			 		echo "ok|".$modeBlack; // log in			 		
					
					$_SESSION['cedula_session_const'] = $row['trabajador'];
					$_SESSION['user_session_const'] = $row['login_username'];
					$_SESSION['username_const']	= $row['nombres'].' '.$row['apellidos'];
					$_SESSION['ccosto']	= $row['ccosto'];		
					$_SESSION['userid_const']	= $row['email'];
					$_SESSION['nivel_const']	= $row['nivel'];
					$_SESSION['estatususer_const']= $row['estatus'];
					$_SESSION['nivel_jerarquico']	= $row['nivel_jerarquico'];                        
					$query_supervisor="select * FROM adam_vw_dotacion_briqven_02_mas where cast(grado_trab as int) >= 35 and trabajador='".$row['trabajador']."'";
       				        $result_supervisor = ejecutar_query($link, $query_supervisor);
//print $query_supervisor;
		                        $row_sup = ejecutar_fetch_array($result_supervisor);
                		        $count_sup = ejecutar_num_rows($result_supervisor);

		                        if($count_sup>0){
								   $_SESSION['grado_trab']  = $row_sup['grado_trab'];
								}else{
								    $_SESSION['grado_trab'] = 0;
								}
				}
			}
			else{
				echo "Login y/o password no coinciden.<br>";// wrong details
			}
			
 			ejecutar_free_result($result);
 			ejecutar_close($link);
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		} 		
	}
?>
