<?PHP
require_once('libs/conexion.php');
session_start();

//$hoy = getdate();
$hoy=date("Ymd");


$fecha_actual = date("d-m-Y");
//sumo 1 día
//echo date("d-m-Y",strtotime($fecha_actual."+ 1 days")); 
//resto 1 día
$fecha_inicio= date("Y-m-d",strtotime($fecha_actual."- 7 days")); 
//print "Fecha de Inicio:".$fecha_inicio;

$cod_linea= isset($_POST["cod_linea"])?$_POST["cod_linea"]:"NULL";   //
$fecha= isset($_POST["txtFecha"])?$_POST["txtFecha"]:"NULL";   //

$apertura_archivo= isset($_POST["apertura_archivo"])?$_POST["apertura_archivo"]:"NULL";   //

$ano			= date("Y");
//$ano			= isset($_POST["cdomes"])?$_POST["cdomes"]:"NULL";   //
$mes			= isset($_POST["cbomes"])?$_POST["cbomes"]:"NULL";   //
//$tn_real		= isset($_POST["tn_real"])?$_POST["tn_real"]:"NULL";   //
//$tn_prog		= isset($_POST["tn_prog"])?$_POST["tn_prog"]:"NULL";   //
//$tn_desvio		= isset($_POST["tn_desvio"])?$_POST["tn_desvio"]:"NULL";   //
//$tn_real_acum		= isset($_POST["tn_real_acum"])?$_POST["tn_real_acum"]:"NULL";   //
//$tn_prog_acum		= isset($_POST["tn_prog_acum"])?$_POST["tn_prog_acum"]:"NULL";   //
//$tn_desvio_acum		= isset($_POST["tn_desvio_acum"])?$_POST["tn_desvio_acum"]:"NULL";   //
$tn_proy		= isset($_POST["tn_proy"])?$_POST["tn_proy"]:"NULL";   //
$tn_prog_orig		= isset($_POST["tn_prog_orig"])?$_POST["tn_prog_orig"]:"NULL";   //
$tn_plan_mes		= isset($_POST["tn_plan_mes"])?$_POST["tn_plan_mes"]:"NULL";   //
//$tn_var_anual		= isset($_POST["tn_var_anual"])?$_POST["tn_var_anual"]:"NULL";   //
//$tn_inv_inicial		= isset($_POST["tn_inv_inicial"])?$_POST["tn_inv_inicial"]:"NULL";   //
//$tn_inv_real		= isset($_POST["tn_inv_real"])?$_POST["tn_inv_real"]:"NULL";   //
$tn_inv_plan_anual	= isset($_POST["tn_inv_plan_anual"])?$_POST["tn_inv_plan_anual"]:"NULL";   //
//$tn_desp_real		= isset($_POST["tn_desp_real"])?$_POST["tn_desp_real"]:"NULL";   //
//$tn_desp_mes		= isset($_POST["tn_desp_mes"])?$_POST["tn_desp_mes"]:"NULL";   //
$tn_desp_plan_mes	= isset($_POST["tn_desp_plan_mes"])?$_POST["tn_desp_plan_mes"]:"NULL";   //
//$tn_desp_var_mes	= isset($_POST["tn_desp_var_mes"])?$_POST["tn_desp_var_mes"]:"NULL";   //
$tn_estim_cierre	= isset($_POST["tn_estim_cierre"])?$_POST["tn_estim_cierre"]:"NULL";   //
$tn_original_plan	= isset($_POST["tn_original_plan"])?$_POST["tn_original_plan"]:"NULL";   //
//$tn_var_cierre		= isset($_POST["tn_var_cierre"])?$_POST["tn_var_cierre"]:"NULL";   //
//$tn_desp_real_acum	= isset($_POST["tn_desp_real_acum"])?$_POST["tn_desp_real_acum"]:"NULL";   //
$tn_desp_plan_acum	= isset($_POST["tn_desp_plan_acum"])?$_POST["tn_desp_plan_acum"]:"NULL";   //
//$tn_desp_var_acum	= isset($_POST["tn_desp_var_acum"])?$_POST["tn_desp_var_acum"]:"NULL";   //
$tn_desp_pea		= isset($_POST["tn_desp_pea"])?$_POST["tn_desp_pea"]:"NULL";   //
$accion			= isset($_POST["accion"])?$_POST["accion"]:"NULL";   //

$mercado= isset($_POST["mercado"])?$_POST["mercado"]:"NULL";   //
$producto= isset($_POST["producto"])?$_POST["producto"]:"NULL";   //

$fecha_prd = date("Ymd", strtotime($fecha));



	$cn=  Conectarse();
if (isset($_SESSION['user_session_sio'])) {
	//$cn=  Conectarse();
        if ($accion=='I'){
	    $queryy = "INSERT INTO programacion (";
	    $queryy .= "fecha_reg, ";
	    $queryy .= "idreactor, ";
	    $queryy .= "ano, ";
	    $queryy .= "mes, ";
	    $queryy .= "tn_ajus_prog_orig_mes, ";
	    $queryy .= "tn_prog_orig, ";
	    $queryy .= "tn_plan_anu_mes, ";
	    $queryy .= "tn_inv_plan_anual, ";
	    $queryy .= "tn_esti_cierre_mes, ";
	    $queryy .= "tn_plan_orig_mes, ";
	    $queryy .= "tn_presu_anual_desp, ";
	    $queryy .= "tn_desp_plan_mes, ";
	    $queryy .= "tn_desp_plan_acum, ";
	    $queryy .= "usuario_reg ";
	    $queryy .= ") VALUES (";
	    $queryy .= "'" . date("Y-m-d")."', ";		
	    $queryy .= "'" . $cod_linea."', ";
	    $queryy .= "'" . $ano."', ";
	    $queryy .= "'" . $mes."', ";
	    $queryy .= "'" . $tn_proy."', ";
	    $queryy .= "'" . $tn_prog_orig."', ";
	    $queryy .= "'" . $tn_plan_mes."', ";
	    $queryy .= "'" . $tn_inv_plan_anual."', ";
	    $queryy .= "'" . $tn_estim_cierre."', ";
	    $queryy .= "'" . $tn_original_plan."', ";
	    $queryy .= "'" . $tn_desp_pea."', ";
	    $queryy .= "'" . $tn_desp_plan_mes."', ";
	    $queryy .= "'" . $tn_desp_plan_acum ."', ";
	    $queryy .= "'" . $_SESSION['user_session_sio']."' ";
	    $queryy .= ");";
//print $queryy;		
	    $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
	    $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);		
	    if (!$resultado) {
		echo "0";
	  	die("Ocurrió un error.\n ");
            }else{
		$query_auditoria="INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', '".date("Y-m-d")."', 'Nuevo Registro de Programacion para el Reactor=".$cod_linea." del mes=".$mes." del ano=".$ano."')";
//		print $query_auditoria;
		$auditoria = pg_query($cn,$query_auditoria);
	        echo "1";
            }

   	//   llenar_txt($cn,$fecha_inicio,$hoy,$fecha_prd,$apertura_archivo);
        
	}else{  // SI EL VALOR DE LA VARIABLE "ACCION" ES "U" DE UPDATE
            
	    $queryy = "UPDATE programacion SET ";
            $queryy .= "fecha_reg= '".date("Y-m-d")."', ";
            $queryy .= "tn_ajus_prog_orig_mes='" . $tn_proy."', ";
            $queryy .= "tn_prog_orig='" . $tn_prog_orig."', ";
            $queryy .= "tn_plan_anu_mes='" . $tn_plan_mes."', ";
            $queryy .= "tn_inv_plan_anual='" . $tn_inv_plan_anual."', ";
            $queryy .= "tn_esti_cierre_mes='" . $tn_estim_cierre."', ";
            $queryy .= "tn_plan_orig_mes='" . $tn_original_plan."', ";
            $queryy .= "tn_presu_anual_desp='" . $tn_desp_pea."', ";
            $queryy .= "tn_desp_plan_mes='" . $tn_desp_plan_mes."', ";
            $queryy .= "tn_desp_plan_acum='" . $tn_desp_plan_acum ."', ";
            $queryy .= "usuario_reg='" . $_SESSION['user_session_sio']."' ";
            $queryy .= " WHERE ";
            $queryy .= "idreactor='".$cod_linea."' and ano='".$ano."' AND mes='".$mes."' ";

            $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
            $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
            if (!$resultado) {
                echo "0";
                die("Ocurrió un error.\n ");
            }else{
		$query_auditoria="INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', '".date("Y-m-d")."', 'Modificacion Registro de Programacion para el Reactor=".$cod_linea." del mes=".$mes." del ano=".$ano."')";
		echo 1;
                
            }
   	  // llenar_txt($cn,$fecha_inicio,$hoy,$fecha_prd,$apertura_archivo);

        } //FIN DE LA CONDOICIONAL "ACCION"
		pg_close($cn);
}else
    echo "Su session esta cerrada";
?>
