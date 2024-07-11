<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();

if (isset($_SESSION['user_session_const']))
{   
    	$link=Conex_Contancia_pgsql();
    	print_r($_POST);
     
    	$nroreg             = isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
    	$fecha_fichada      = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
    	$cbotrabajador      = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
       	$hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
    	$hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
    	$INST1		        = $hinicio1;
    	$FINST1  	        = $hfinal1;
    	$INST2		        = "NULL";
    	$FINST2 	        = "NULL";
    	$observacion        = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
    	$entrada_esperada1  = isset($_POST["entrada_esperada1"])?$_POST["entrada_esperada1"]:"NULL";
    	$salida_esperada1   = isset($_POST["salida_esperada1"])?$_POST["salida_esperada1"]:"NULL";
    	$entrada_real1      = isset($_POST["entrada_real1"])?$_POST["entrada_real1"]:"NULL";
    	$salida_real1       = isset($_POST["salida_real1"])?$_POST["salida_real1"]:"NULL";

		$cedula 	        = "'".trim($cbotrabajador)."'";
		$fecha		        = "'".$fecha_fichada."'";
		$INST1		        = "'".$hinicio1."'";
		$FINST1             = "'".$hfinal1."'"; 
		$INST2              = "NULL";
	    $FINST2             = "NULL";
		$COD1               = "08";
		$COD2 		        = "0";
		$CS 		        = "'".$_SESSION['cedula_session_const']."'";
		$OBS 		        = "'".$observacion."'";
        $inicio_st2 	    = "00:00";
        $fin_st2 	        = "00:00";
        $codigo1 	        = "08";
        $codigo2 	        = "0";
        
//echo "<br>cedula: ".$cedula.", fecha: ".$fecha.", INST1: ".$INST1.", FINST1: ".$FINST1.", INST2: ".$INST2.", FINST2: ".$FINST2.", COD1: ".$COD1.", COD2: ".$COD2.", CS: ".$CS.", obs: ".$OBS;
	$conn = Conectarse_sitt();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("EXEC dbo.SW_GRABA_CAMBIO_DLT_CEDULA_VALIDA ".$cedula.", ".$fecha.", ".$INST1.", ".$FINST1.", NULL, NULL, ".$COD1.", ".$COD2.", ".$CS.", ".$OBS);
        $stmt->execute();
        $result_set = $stmt->fetchAll();
        var_dump($result_set);
        /*Get the current error mode of PDO*/
        $current_error_mode = $conn->getAttribute(PDO::ATTR_ERRMODE);

print_r($result_set);

echo "<br>Resultado:".$result_set[0][1]."<br>";


/*echo $current_error_mode["Error"];
*/


//print "Salida:".$salida_real1;
$sms=$result_set[0][1];
$sms="OK";
    if ($sms=="OK"){
	    $hoy= date("Y") . "-" . date("m") . "-" . date("d");
		$ced="";
		$query="select * from registro_diario where trabajador in ('".trim($cbotrabajador)."') and fecha='".$fecha_fichada."'";
	//print $query."-".$hinicio1."-".$hfinal1 ;
		$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
		$numReg = ejecutar_num_rows($result);
		if($numReg==0){
		    $insertConst="INSERT INTO registro_diario (";
		    $insertConst.="trabajador,";
		    $insertConst.="fecha,";
		    $insertConst.="asistio,";
		    $insertConst.="sobre_tiempo,";
		    $insertConst.="cambio_turno,";
		    $insertConst.="comision,";
		    $insertConst.="dlt,";
		    $insertConst.="inasistencia,";
		    $insertConst.="motivo_dlt,";
		    $insertConst.="fecha_reg,";
		    $insertConst.="fecha_reg_dlt,";
		    $insertConst.="trabajador_reg, ";
		    $insertConst.="registrado_dlt, ";
		    $insertConst.="entrada_real1, ";
		    $insertConst.="salida_real1, ";
		    $insertConst.="bloqueado ";
		    $insertConst.=") VALUES (";
		    $insertConst.="'".trim($cbotrabajador)."',";
		    $insertConst.="'".$fecha_fichada."',";
		    $insertConst.="'S', ";
		    $insertConst.="'N', ";
		    $insertConst.="'N', ";
		    $insertConst.="'N', ";
		    $insertConst.="'S', ";
		    $insertConst.="0, ";
		    $insertConst.="'".$observacion."', ";
		    $insertConst.="'".$hoy."', ";
		    $insertConst.="'".$hoy."', ";
		    $insertConst.="'".$_SESSION['user_session_const']."', ";
		    $insertConst.="'".$_SESSION['user_session_const']."', ";
	        $insertConst.=$INST1.",";
	        $insertConst.=$FINST1.",";
		    $insertConst.="'0'";
		    $insertConst.=")";
	  	    $mens="Se registro el DLT";  
	     }else{
		    $insertConst="UPDATE registro_diario SET ";
		    $insertConst.="entrada_real1='".$entrada_real1."', ";
		    $insertConst.="salida_real1=".$FINST1.", ";
		    $insertConst.="motivo_dlt='".$observacion."' ";
	        $insertConst.="where trabajador = '".trim($cbotrabajador)."' ";
	        $insertConst.="and fecha= '".$fecha_fichada."'";
	        $mens="Se actualizo el DLT";  
	      }	

		  echo $insertConst;
		  $result = pg_query($link,$insertConst);
	      if (pg_affected_rows($result)==0){
	            echo("ERROR");
	            die(" Consulta SQL: ".$insertConst);
	      }
	      $ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
	      pg_close($link);
	      echo $mens;  

	      require("enviodecorreos.php");
	      $asunto="Carga de DLT";
	      $cuerpo="Saludos, Se cargo el DLT del trabajador: <br>Nombre: ".nombre_trabajadores(trim($cbotrabajador)).", <br>C&eacute;dula de indentidad:".trim($cbotrabajador)."<br>Fecha: ".$fecha_fichada."<br>Motivo : ".trim($observacion).".";
		  $correo1=$_SESSION['user_session_const']."@briqven.com.ve";
		  $correo2="matzem@briqven.com.ve";
	      ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
		  header('Location: cargar_horas_extras.php');
	}else{
		echo $sms;
	} 
		
}



function diferencia_hora($inicio,$fin){
$date1 = new DateTime($inicio);
$date2 = new DateTime($fin);
$diff = $date1->diff($date2);
// 38 minutes to go [number is variable]
echo ( ($diff->days * 24 ) * 60 ) + ( $diff->i ) . ' minutes';
// passed means if its negative and to go means if its positive
echo ($diff->invert == 1 ) ? ' passed ' : ' to go ';
}




?>
