<?PHP
require("libs/conexion.php");
$fecha = isset($_GET["fecha"])?$_GET["fecha"]:"-1";
$linea = isset($_GET["linea"])?$_GET["linea"]:"-1";
$conn = Conectarse_sio();
$conex_siteges = Conectarse();

$tn_real =0;
$tn_prog =0;
$tn_desvio =0;
$tn_real_acum =0;
$tn_prog_acum=0;
$tn_desvio_acum =0;
$tn_proy=0;
$tn_prog_orig=0;
$tn_plan_mes =0;
$tn_var_anual=0;

$tn_inv_inicial =0;
$tn_inv_real =0;
$tn_inv_plan_anual =0;

  $tn_desp_real  =0;
  $tn_desp_mes  =0;
  $tn_desp_plan_mes  =0;
  $tn_desp_var_mes  =0;
  $tn_estim_cierre  =0;
  $tn_original_plan  =0;
  $tn_var_cierre  =0;
  $tn_desp_real_acum  =0;
  $tn_desp_plan_acum  =0;
  $tn_desp_var_acum  =0;
  $tn_desp_pea =0;

$query1 = "SELECT * from prd_real_plan where fecha_produccion='".$fecha."' AND cod_linea='".$linea."'";
$resultado1 = pg_query($conex_siteges, $query1) or die("Error en la Consulta SQL:".$query1);
$numReg1 = pg_num_rows($resultado1);
if ($numReg1>0){
    echo 1;
	//$tn_var_anual=$fila1['tn_var_anual'];
	//pg_free_result($resultado1);
}
		
pg_close($conex_siteges);
?>
