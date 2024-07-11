<?php
session_start();
include("../BD/conexion.php");
include ("funciones_var.php");
/*$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
if ($turno!='')
  $qry.=" where turno ".$turno."9";
if ($trabajador!='NULL')
  if ($turno!='')
    $qry.=" and cedula=".$trabajador."";
  else
    $qry.=" WHERE cedula=".$trabajador."";
$qry.=" order by 11 desc,1";
*/
$b="select  * from ALL_VARIABLES_HORIZONTAL where CAST(FECHA_HORA AS DATETIME) > CAST('2021-04-28 09:00:00' AS DATETIME) order by FECHA_HORA DESC ";

buscar($b);     
echo "<br>**********************************************************************************************<br>";




buscar2($b);     
       
function buscar($b) {
//       $cn=Conectarse_PHD();
        $cn=Conectarse_sitt2();
	print "PASO1";
        //$stmt1 = $cn->query($b);
        $stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt1->execute();
        $contar = $stmt1->columnCount(); 
        print "<br>PASO2:".$contar;
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
             print $inpt; 
        }else{
	     print "<br>PASO3";
              $i=0;
//             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
		While ($fila = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			$i=$i+1;
  	           	print "<br>PASO4";
                   	  print "<br><br>NUM:".$i."=> ".$fila['FECHA_HORA'].", ".$fila['WIC11205'].", ".$fila['TI12209D'].", ".$fila['TI12210A'].", ".$fila['TI12210B'].", ".$fila['TI12210C'].", ".$fila['WIC11205'];                  
               

			print("<br>PDO::FETCH_ASSOC: ");
			print("<br>Devolver la siguiente fila como un array indexado por nombre de colunmna\n");
			$result = $stmt1->fetch(PDO::FETCH_ASSOC);
			print_r($result); 

			print("<br>PDO::FETCH_BOTH: ");
			print("<br>Devolver la siguiente fila como un array indexado por nombre y número de columna\n");
			$result = $stmt1->fetch(PDO::FETCH_BOTH);
			print_r($result);
			print("\n");

			print("<br>PDO::FETCH_LAZY: ");
			print("<br>Devolver la siguiente fila como un objeto anónimo con nombres de columna como propiedades\n");
			$result = $stmt1->fetch(PDO::FETCH_LAZY);
			print_r($result);
			print("\n");
	
		}
             print "<br>PASO5";
             print_r($fila);
             print "<br>PASO6";
	     print("<br>".$b);
        }         
}



function buscar2($b) {
//       include("../BD/conexion.php");
print "<br>PASO7";
       $gbd=Conectarse_PHD();
//	$gbd=Conectarse_sitt2();
	$sql = $b;
	try {
	    $sentencia = $gbd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	    $sentencia->execute();
print "<br>PASO8";
	    while ($fila = $sentencia->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
	      $datos = $fila[0] . "\t" . $fila[1] . "\t" . $fila[2] . "\n";
	      print $datos;


		print("<br>PDO::FETCH_NUMERO: ");
		print("<br>Devolver la siguiente fila como un array indexado por nombre de colunmna\n");
		$result = $sentencia->fetch(PDO::FETCH_NUM);
		print_r($result);

		print("<br>PDO::FETCH_ASSOC: ");
		print("<br>Devolver la siguiente fila como un array indexado por nombre de colunmna\n");
		$result = $sentencia->fetch(PDO::FETCH_ASSOC);
		print_r($result);

		print("<br>PDO::FETCH_BOTH: ");
		print("<br>Devolver la siguiente fila como un array indexado por nombre y número de columna\n");
		$result = $sentencia->fetch(PDO::FETCH_BOTH);
		print_r($result);
		print("\n");

		print("<br>PDO::FETCH_LAZY: ");
		print("<br>Devolver la siguiente fila como un objeto anónimo con nombres de columna como propiedades\n");
		$result = $sentencia->fetch(PDO::FETCH_LAZY);
		print_r($result);
		print("\n");

	    }
print "<br>PASO9";
	    $sentencia = null;
	}
	catch (PDOException $e) {	
	    print $e->getMessage();
	}
}


?>
