<?PHP
require("libs/conexion.php");
$ano = isset($_GET["fecha"])?$_GET["fecha"]:"-1";
$linea = isset($_GET["linea"])?$_GET["linea"]:"-1";
$conn = Conectarse_sio();
$conex_siteges = Conectarse();

/*$tn_real =0;
$tn_prog =0;
$tn_desvio =0;
$tn_real_acum =0;
$tn_prog_acum=0;
$tn_desvio_acum =0;
*/
$tn_proy=0;
$tn_prog_orig=0;
$tn_plan_mes =0;
//$tn_var_anual=0;

//$tn_inv_inicial =0;
//$tn_inv_real =0;
$tn_inv_plan_anual =0;

//$tn_desp_real  =0;
//$tn_desp_mes  =0;
$tn_desp_plan_mes  =0;
//$tn_desp_var_mes  =0;
$tn_estim_cierre  =0;
$tn_original_plan  =0;
//$tn_var_cierre  =0;
//$tn_desp_real_acum  =0;
$tn_desp_plan_acum  =0;
//$tn_desp_var_acum  =0;
$tn_desp_pea =0;
$accion='I';

$query1 = "SELECT * from programacion where mes='".$ano."' AND idreactor='".$linea."' and ano=".date("Y");
//print $query1;
$resultado1 = pg_query($conex_siteges, $query1) or die("Error en la Consulta SQL:".$query1);
$numReg1 = pg_num_rows($resultado1);
if ($numReg1>0){
	$accion='U';
	$fila1=pg_fetch_array($resultado1);
//	$tn_real =$fila1['tn_real'];
//	$tn_prog =$fila1['tn_prog'];
//	$tn_desvio =$fila1['tn_desvio'];
//	$tn_real_acum =$fila1['tn_real_acum'];
//	$tn_prog_acum=$fila1['tn_prog_acum'];
//	$tn_desvio_acum =$fila1['tn_desvio_acum'];
	$tn_proy=$fila1['tn_ajus_prog_orig_mes'];
	$tn_prog_orig=$fila1['tn_prog_orig'];
	$tn_plan_mes =$fila1['tn_plan_anu_mes'];
//	$tn_var_anual=$fila1['tn_var_anual'];
////	    $fila2=pg_fetch_array($resultado2);
//	    $tn_inv_inicial =$fila2['tn_inv_inicial'];
//	    $tn_inv_real =$fila2['tn_inv_real'];
	$tn_inv_plan_anual = $fila1['tn_inv_plan_anual'];
	///////////DESPACHO/////////////////////////////
  //	    $tn_desp_real  =$fila4['tn_desp_real'];
//	    $tn_desp_mes  =$fila4['tn_desp_mes'];
         $tn_desp_plan_mes = $fila1['tn_desp_plan_mes'];
//	    $tn_desp_var_mes  =$fila4['tn_desp_var_mes'];
	 $tn_estim_cierre  = $fila1['tn_esti_cierre_mes'];
	 $tn_original_plan = $fila1['tn_plan_orig_mes'];
//	    $tn_var_cierre  =$fila4['tn_var_cierre'];
//	    $tn_desp_real_acum  =$fila4['tn_desp_real_acum'];
	    $tn_desp_plan_acum  =$fila1['tn_desp_plan_acum'];
//	    $tn_desp_var_acum  = $fila1['tn_desp_var_acum'];
	    $tn_desp_pea =$fila1['tn_presu_anual_desp'];

}				
	
/*if ($tn_inv_real==0)
	$tn_inv_real=$tn_inv_inicial+$tn_real;

if ($tn_desp_var_mes==0)
	$tn_desp_var_mes=$tn_desp_plan_mes-$tn_desp_mes;

*/

//	echo "$(\"#tn_real\").val(\"" . $tn_real . "\");\n" ;
//	echo "$(\"#tn_prog\").val(\"" . $tn_prog . "\");\n" ;
//	echo "$(\"#tn_desvio\").val(\"" . $tn_desvio . "\");\n" ;
//	echo "$(\"#tn_real_acum\").val(\"" . $tn_real_acum . "\");\n" ;
//	echo "$(\"#tn_prog_acum\").val(\"" . $tn_prog_acum . "\");\n" ;
//	echo "$(\"#tn_desvio_acum\").val(\"" . $tn_desvio_acum . "\");\n" ;
	echo "$(\"#tn_proy\").val(\"" . $tn_proy . "\");\n" ;
	echo "$(\"#tn_prog_orig\").val(\"" . $tn_prog_orig . "\");\n" ;
	echo "$(\"#tn_plan_mes\").val(\"" . $tn_plan_mes . "\");\n" ;
//	echo "$(\"#tn_var_anual\").val(\"" . $tn_var_anual . "\");\n" ;

//	echo "$(\"#tn_inv_inicial\").val(\"" . $tn_inv_inicial . "\");\n" ;
//	echo "$(\"#tn_inv_real\").val(\"" . $tn_inv_real  . "\");\n" ;
	echo "$(\"#tn_inv_plan_anual\").val(\"" . $tn_inv_plan_anual . "\");\n" ;

//	echo "$(\"#tn_desp_real\").val(\"" . $tn_desp_real . "\");\n" ;
//	echo "$(\"#tn_desp_mes\").val(\"" . $tn_desp_mes . "\");\n" ;
	echo "$(\"#tn_desp_plan_mes\").val(\"" . $tn_desp_plan_mes . "\");\n" ;
//	echo "$(\"#tn_desp_var_mes\").val(\"" . $tn_desp_var_mes . "\");\n" ;
	echo "$(\"#tn_estim_cierre\").val(\"" . $tn_estim_cierre . "\");\n" ;
	echo "$(\"#tn_original_plan\").val(\"" . $tn_original_plan . "\");\n" ;
//	echo "$(\"#tn_var_cierre\").val(\"" . $tn_var_cierre . "\");\n" ;
//	echo "$(\"#tn_desp_real_acum\").val(\"" . $tn_desp_real_acum . "\");\n" ;
	echo "$(\"#tn_desp_plan_acum\").val(\"" . $tn_desp_plan_acum . "\");\n" ;
//	echo "$(\"#tn_desp_var_acum\").val(\"" . $tn_desp_var_acum . "\");\n" ;
	echo "$(\"#tn_desp_pea\").val(\"".$tn_desp_pea."\");\n" ;
	echo "$(\"#accion\").val(\"" . $accion . "\");\n" ;
		
pg_close($conex_siteges);
?>
