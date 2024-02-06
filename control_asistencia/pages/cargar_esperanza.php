 <?PHP
session_start();
require_once('funciones_var.php');
include("../BD/conexion.php");
//$link=Conex_Contancia_pgsql();

$cedula = $_GET['b'];
$fecha = $_GET['c'];

$hora = localtime(time(),true);
//$hoy = date("Y-m-d");
$hoy = $fecha;

$conn = Conectarse_sitt();
//$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, turno from sw_hoja_de_tiempo_real where cedula=".$cedula." and CONVERT(VARCHAR(10), fecha, 120)=CONVERT(VARCHAR(10), GETDATE(), 120)";
$sql = "SELECT cedula,entrada_esperada1,salida_esperada1,entrada_esperada2,salida_esperada2 FROM sw_hoja_de_tiempo_real WHERE cedula=".$cedula." and fecha='".$hoy."'";


$stmt = $conn->query($sql);
$col = $stmt->columnCount();
$esperanza="";
$ced='';
$entrada_esperada1='';
$salida_esperada1='';      
$entrada_esperada2='';
$salida_esperada2='';      

while ($row = $stmt->fetch()) {         
//echo 'paso'.$sql ;
         $ced=$row[0];
         $entrada_esperada1=$row[1];
         $salida_esperada1=$row[2];               
         $entrada_esperada2=$row[3];
         $salida_esperada2=$row[4];               
} 

if ($salida_esperada2!=null or $salida_esperada2!="")
	$salida_esperada=$salida_esperada2;
else
 	$salida_esperada=$salida_esperada1;

$esperanza = "Hora entrada esperada: ".$entrada_esperada1."   Hora salida esperada: ".$salida_esperada;
echo "$(\"#hddcedula\").val(\"" . $cedula . "\");\n" ;
echo "$(\"#txtentradaesperada\").val(\"" . $esperanza . "\");\n" ;
echo "$(\"#txthora_entrada_real\").val(\"" . $entrada_esperada1 . "\");\n" ;

echo "$(\"#txthora_salida_real\").val(\"" . $salida_esperada . "\");\n" ;
/*
echo  $esperanza;
*/


//echo "$(\"#txthora_salida_real\").val(\"2019-06-22\");\n" ;

?>
