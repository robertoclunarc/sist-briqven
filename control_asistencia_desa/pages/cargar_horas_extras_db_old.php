<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();

function RestarHoras($horaini,$horafin)

{

    $f1 = new DateTime($horaini);

    $f2 = new DateTime($horafin);

    $d = $f1->diff($f2);

    return $d->format('%H:%I:%S');

}

if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
        $nroreg= isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
	$fecha_fichada= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
	$cbotrabajador=isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
       
	$hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
    $hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
	$observacion        = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
	$entrada_esperada1  = isset($_POST["entrada_esperada1"])?$_POST["entrada_esperada1"]:"NULL";
    $salida_esperada1   = isset($_POST["salida_esperada1"])?$_POST["salida_esperada1"]:"NULL";
    $entrada_real1      = isset($_POST["entrada_real1"])?$_POST["entrada_real1"]:"NULL";
    $salida_real1       = isset($_POST["salida_real1"])?$_POST["salida_real1"]:"NULL";

    $sms='';
    if ($hinicio1<$salida_esperada1) && ($inicio>$entrada_esperada1)
        $sms="Error: La hora de inicio debe se mayor que la hora de salida esperada";
    elseif (($hfinal1>$entrada_esperada1) && ($hfinal1<$salida_esperada1))
    	$sms="Error: La hora fin debe ser menor que la entrada Esperada";
    elseif ($entrada_esperada1=="VV:VV" || $entrada_esperada1=="PP:PP" || $entrada_esperada1=="RR:RR" || $entrada_esperada1=="LL:LL" || $entrada_esperada1=="FF:FF" || $entrada_esperada1=="SD:SD" || $entrada_esperada1=="CS:CS")
    	$sms="Error: La Esperanza del trabajador no permite cargarle horas extras";
    elseif (RestarHoras($hinicio1,$hinicio1)>8)
    	$sms="Error: No puede tener mas de 8 horas de horas extras";




    if ($sms!=""){

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
			    $insertConst.="entrada_salida1, ";
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
	                    $insertConst.="'".$entrada_real1."',";
	                    $insertConst.="'".$salida_real1."',";
	                    $insertConst.="'".$entrada_esperada1."',";
	                    $insertConst.="'".$salida_esperada1."',";
			    $insertConst.="'0'";
			    $insertConst.=")";
	  	            $mens="Se registraron las horas extras";  
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
	        $mens="Se actualizaron las horas extras";  
	      }	
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
	        $cuerpo="Saludos, Se le cargaron horas extras al trabajador: ".nombre_trabajadores(trim($cbotrabajador)).", portador de la c&eacute;dula de indentidad:".trim($cbotrabajador).", el dia: ".$fecha_fichada.", por concepto de: ".trim($observacion).".";
	        $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve","matzem@briqven.com.ve");
	}else{

		echo $sms;
	} 
		
}
?>
