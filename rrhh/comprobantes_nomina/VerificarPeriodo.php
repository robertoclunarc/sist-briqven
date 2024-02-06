<?PHP
require_once('libs/conexion_2.php');
$cnx_oracle= Conectarse_oracle();

$cboTnomina= isset($_POST["cboTnomina"])?$_POST["cboTnomina"]:"";         //
$cboCnomina= isset($_POST["cboCnomina"])?$_POST["cboCnomina"]:"";   // 
$cboMes= isset($_POST["cboMes"])?$_POST["cboMes"]:"";               // 
$cboAnho= isset($_POST["cboAnho"])?$_POST["cboAnho"]:"";   // 

$TB_ENC=$cboTnomina.$cboCnomina.$cboMes.$cboAnho;

//$TB_ENC="LMME122017";

$query = "select count(*) as PERCERRADO from all_tables ";
$query .= " where table_name = 'DET_".$TB_ENC."'";

$stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL: ".$query);
oci_execute($stid);
$fila = oci_fetch_array($stid, OCI_BOTH);
$PERCERRADO=$fila['PERCERRADO'];
echo $PERCERRADO;
?>