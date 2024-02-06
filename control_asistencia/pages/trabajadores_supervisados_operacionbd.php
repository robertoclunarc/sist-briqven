<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');	

	$trabajador_supervisado= isset($_POST["cbosupervisado"])?$_POST["cbosupervisado"]:"NULL";
	$trabajador_supervisor= isset($_POST["cbosupervisor"])?$_POST["cbosupervisor"]:"NULL";

	$operacion         = isset($_POST["operacion"])?$_POST["operacion"]:'';
	$nivel_jerarquico  = isset($_POST["txtnivel_jerarquico"])?$_POST["txtnivel_jerarquico"]:'';
	$nombresupervisado = isset($_POST["txtnombresupervisado"])?$_POST["txtnombresupervisado"]:'';
	$posicion_coincidencia = strpos($nombresupervisado, '-');
    $nombresupervisado =  substr($nombresupervisado, $posicion_coincidencia+2);

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'trabajadores_supervisados', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){
       switch ($operacion) {
	  	case ("INSERT"): $query=" INSERT INTO supervisores_trabajadores VALUES ('".$trabajador_supervisado."',  '".$nombresupervisado."' , '".$nivel_jerarquico."' ,'".$trabajador_supervisor."')";
	  	    $accion="Registrado ";
	  	break;
	  	
	  	case ("UPDATE"): $query=" UPDATE supervisores_trabajadores SET inicio='".$fini1."' , fin='".$ffin1."' , motivo='".$Motivo."' WHERE trabajador='".$trabajador."'";
	  	break;
	  	
	  	case ("DELETE"): $query=" DELETE from supervisores_trabajadores WHERE trabajador='".$trabajador_supervisado."' and trabajador_sup='".$trabajador_supervisor."'"	;
	  		  $accion="Eliminado ";
	  	break;
	  }	
	  
	  $link2=Conex_rrhh_pgsql();
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
