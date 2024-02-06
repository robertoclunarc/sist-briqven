<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');	

	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";	

	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$trabajador=str_pad($trabajador, 10, " ", STR_PAD_LEFT);
	
	$Motivo=isset($_POST["txtMotivo"])?$_POST["txtMotivo"]:'';

	$operacion=isset($_POST["cbooperacion"])?$_POST["cbooperacion"]:'';

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'd-M-Y');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'd-M-Y');
	

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'updatevacation.php', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){

	  switch ($operacion) {
	  	case ("INSERT"): $query=" INSERT INTO tb_excepcion_baja_sid VALUES ('".$trabajador."',  '".$fini1."' , '".$ffin1."' ,'".$Motivo."')";
	  	break;
	  	
	  	case ("UPDATE"): $query=" UPDATE tb_excepcion_baja_sid SET inicio='".$fini1."' , fin='".$ffin1."' , motivo='".$Motivo."' WHERE trabajador='".$trabajador."'";
	  	break;
	  	
	  	case ("DELETE"): $query=" DELETE tb_excepcion_baja_sid WHERE trabajador='".$trabajador."'"	;
	  	break;
	  }	
	  
	  $conn=Conex_oramprd();
      $stid = oci_parse($conn, $query);      
      oci_execute($stid);      
      
      echo '<div class="alert alert-success"> Registrado Correctamente!</div>';
	}
	else{
		echo "No tiene privilegio para esta operacion";
	}		
}	
else
	echo "Debe Iniciar Sesion";
?>
