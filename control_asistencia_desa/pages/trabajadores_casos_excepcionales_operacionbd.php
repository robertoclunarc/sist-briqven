<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');	

	$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$motivo     = isset($_POST["motivo"])?$_POST["motivo"]:"NULL";
	$operacion  = isset($_POST["operacion"])?$_POST["operacion"]:'';
	$observacion= isset($_POST["observacion"])?$_POST["observacion"]:'';
	$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:'';
	$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:'';

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'trabajadores_supervisados', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){
       switch ($operacion) {
	  	case ("INSERT"): $query=" INSERT INTO public.personal_bloqueado (cedula, fecha_desde, fecha_hasta, motivo, fecha_registro, usuario_registrador, status, observacion) VALUES('".$trabajador."', '".$finicio."', '".$ffin."', '".$motivo."', now(), '".$_SESSION['user_session_const']."', 'ACTIVO','".$observacion."')";
	  	    $accion="Registrado ";
	  	break;
	  	
	  	case ("UPDATE"): $query=" UPDATE public.personal_bloqueado SET inicio='".$fini1."' , fin='".$ffin1."' , motivo='".$Motivo."' WHERE cedula='".$trabajador."'";
	  	break;
	  	
	  	case ("DELETE"): $query=" UPDATE public.personal_bloqueado SET fecha_hasta=now(), status='INACTIVO' WHERE cedula='".$trabajador."' ";
	  		  $accion="Eliminado ";
	  	break;
	  }	
	  
	  //$link2=Conex_rrhh_pgsql();
	  $link2=Conectarse(); 
	  $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
	  pg_close($link2);

      echo '<div class="alert alert-success"> '.$accion.' Correctamente!</div>';
	}
	else{
		echo "No tiene privilegio para esta operacion";
	}	
		
}	
else
	echo "Debe Iniciar Sesion";
?>
