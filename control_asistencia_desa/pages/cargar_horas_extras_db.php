<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();

/*function RestarHoras($horaini,$horafin)
{

    $f1 = new DateTime($horaini);

    $f2 = new DateTime($horafin);

    $d = $f1->diff($f2);

    return $d->format('%H:%I:%S');

}
*/
if (isset($_SESSION['user_session_const']))
{   

    	$link=Conex_Contancia_pgsql();

    	$nroreg             = isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
    	$fecha_fichada      = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
    	$cbotrabajador      = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
       	$hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
    	$hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
    	$INST1	  	        = $hinicio1;
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

        if ($salida_real1=='' or $salida_real1 == 'null') $salida_real1= $hfinal1;
        
//	echo "<br>cedula: ".$cedula.", fecha: ".$fecha.", INST1: ".$INST1.", FINST1: ".$FINST1.", INST2: ".$INST2.", FINST2: ".$FINST2.", COD1: ".$COD1.", COD2: ".$COD2.", CS: ".$CS.", obs: ".$OBS;
	$conn = Conectarse_sitt();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("EXEC dbo.SW_VALIDA_ST_CEDULA ".$cedula.", ".$fecha.", ".$INST1.", ".$FINST1.", NULL, NULL, ".$COD1.", ".$COD2.", ".$CS.", ".$OBS);
    /*    $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
        $stmt->bindParam(2, $fecha,  PDO::PARAM_STR,10);
        $stmt->bindParam(3, $INST1,  PDO::PARAM_STR,5);
        $stmt->bindParam(4, $FINST1,  PDO::PARAM_STR,5);
        $stmt->bindParam(5, $INST2,  PDO::PARAM_STR,5);
        $stmt->bindParam(6, $FINST2,  PDO::PARAM_STR,5);
        $stmt->bindParam(7, $COD1,  PDO::PARAM_STR,2);
        $stmt->bindParam(8, $COD2,  PDO::PARAM_STR,2);
        $stmt->bindParam(9, $CS, PDO::PARAM_INT,10);
        $stmt->bindParam(10, $OBS,  PDO::PARAM_STR,255);
*/
        $stmt->execute();
        $result_set = $stmt->fetchAll();
//        var_dump($result_set);
        /*Get the current error mode of PDO*/
        $current_error_mode = $conn->getAttribute(PDO::ATTR_ERRMODE);

//echo var_dump;

//print_r($result_set);

//echo "<br>Resultado:".$result_set[0][1]."<br>";


    $sms=$result_set[0][1];
//echo $sms;
 //   $sms="PRUEBA";
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
			    $insertConst.="inasistencia,";
			    $insertConst.="observacion_te,";
			    $insertConst.="fecha_reg,";
			    $insertConst.="trabajador_reg, ";
			    $insertConst.="entrada_te, ";
			    $insertConst.="salida_te, ";
			    $insertConst.="entrada_real1, ";
			    $insertConst.="salida_real1, ";
			    $insertConst.="entrada_esperada1, ";
			    $insertConst.="salida_esperada1, ";
			    $insertConst.="bloqueado ";
			    $insertConst.=") VALUES (";
			    $insertConst.="'".trim($cbotrabajador)."',";
			    $insertConst.="'".$fecha_fichada."',";
			    $insertConst.="'S', ";
			    $insertConst.="'S', ";
			    $insertConst.="'N', ";
			    $insertConst.="'N', ";
			    $insertConst.="0, ";
			    $insertConst.="'".$observacion."', ";
			    $insertConst.="'".$hoy."', ";
			    $insertConst.="'".$_SESSION['user_session_const']."', ";
	            $insertConst.="'".$hinicio1."',";
	            $insertConst.="'".$hfinal1."',";
	            $insertConst.="'".$entrada_real1."',";
	            $insertConst.="'".$salida_real1."',";
	            $insertConst.="'".$entrada_esperada1."',";
	            $insertConst.="'".$salida_esperada1."',";
			    $insertConst.="'0'";
			    $insertConst.=")";
	  	        $mens="Se registraron sarisfactoriamente las horas extras, se ha enviado un correo a su cuenta";  
	     }else{
		        $insertConst="UPDATE registro_diario SET ";
		        $insertConst.="entrada_real1='".$entrada_real1."', ";
		        $insertConst.="salida_real1='".$salida_real1."', ";
		        $insertConst.="entrada_te='".$hinicio1."', ";
		        $insertConst.="salida_te='".$hfinal1."'," ;
		        $insertConst.="salida_esperada1='".$salida_esperada1."'," ;
		        $insertConst.="entrada_esperada1='".$entrada_esperada1."'," ;
		        $insertConst.="observacion_te='".$observacion."' ";
	            $insertConst.="where trabajador = '".trim($cbotrabajador)."' ";
	            $insertConst.="and fecha= '".$fecha_fichada."'";
	            $mens="Se actualizaron las horas extras, se ha enviado un correo a su cuenta";  
	      }	
	//	echo $insertConst;
		$result = pg_query($link,$insertConst);
	        if (pg_affected_rows($result)==0)
	        {
	            echo("ERROR");
	            die(" Consulta SQL: ".$insertConst);
	        }
	        $ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
	        pg_close($link);
	        echo $mens;  

	        require("enviodecorreos.php");
	        $asunto="Carga de Horas Extras";
	        $cuerpo="Saludos, <br>Se le cargaron horas extras <br>al trabajador: ".nombre_trabajadores(trim($cbotrabajador)).", <br>portador de la c&eacute;dula de indentidad:".trim($cbotrabajador).", <br>el dia: ".$fecha_fichada.", <br>por concepto de: ".trim($observacion).".";
		    $correo=$_SESSION['user_session_const']."@briqven.com.ve";
	        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo,"matzem@briqven.com.ve");
			//$resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo,"matVxl@briqven.com.ve");
	        //$resp2=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmoa@briqven.com.ve","matzem@briqven.com.ve");
		//header('Location: cargar_horas_extras.php');;
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
