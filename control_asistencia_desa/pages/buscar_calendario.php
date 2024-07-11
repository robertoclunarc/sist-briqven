<?PHP
include("../BD/conexion.php");
require_once('funciones_var.php');

try {
	$link5=Conex_Contancia_pgsql();	

	$fecha= isset($_GET["fecha"])?$_GET["fecha"]:"NULL";
	
	$periodo=dadaFechaDevuelveIdCalendario($link5, $fecha);
    $abierto=false;
    $tipo_nomina='';
    $mes='';
    $anio='';
    $fecha_pago='';
    $inicio='';
    $fin='';
    $id_calendario='';
    $response="";
    if ($periodo!==false){
      $abierto=$periodo['abierto'];
      $tipo_nomina=$periodo['tipo_nomina'];
      $mes=$periodo['mes'];
      $anio=$periodo['anio'];
      $fecha_pago=$periodo['fecha_pago'];
      $id_calendario=$periodo['id_calendario'];
      $inicio=$periodo['inicio'];
      $fin=$periodo['fin'];
    }
	
	$response='{ ';
	$response.='"abierto": "'.$abierto.'", ';
	$response.='"tipo_nomina": "'.$tipo_nomina.'", ';
	$response.='"mes": "'.$mes.'", ';
	$response.='"anio": "'.$anio.'", ';
	$response.='"fecha_pago": "'.$fecha_pago.'", ';
	$response.='"id_calendario" : "'.$id_calendario.'", ';
	$response.='"inicio": "'.$inicio.'", ';
	$response.='"fin": "'.$fin.'" }';
	
	pg_close($link5);
	echo $response;
} catch (Exception $e) {
	echo $e;
}

?>
