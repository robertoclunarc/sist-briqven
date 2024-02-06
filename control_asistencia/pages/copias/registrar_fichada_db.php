<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();
if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
        $nroreg= isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
	$fecha_fichada= isset($_POST["txtfecha"])?$_POST["txtfecha"]:date("Y")."-".date("m")."-".date("d");

        $hoy= date("Y") . "-" . date("m") . "-" . date("d");

	$query="select * from registro_diario where trabajador_reg='".$_SESSION['user_session_const']."' and fecha='".$fecha_fichada."'";
	$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
	$numReg = ejecutar_num_rows($result);
//echo $query." ".$numReg." ".$nroreg;

	if($numReg==0){
		if(array_key_exists('cedulas',$_POST) && $nroreg>0)
		{          
		  $cedulas 		= $_POST['cedulas'];          
		  $nombres 		= $_POST['nombres'];
		  //$hora_entrada_real	= $_POST['hora_entrada_real'];
		  //$hora_salida_real 	= $_POST['hora_salida_real'];
		  $asistio 		= $_POST['asistio'];
		  $cambioturno 		= $_POST['cambioturno'];
		  $comision 		= $_POST['comision'];
		  $permiso 		= $_POST['permiso'];
		  $observacion 		= $_POST['observacion'];
		  $sobretiempo 		= $_POST['sobretiempo'];
		  $turno 		= $_POST['turno'];
		  $grupo		= $_POST['grupo']; 
		  $cont=0;
		  foreach ($cedulas as $i => $cedula)
		  {            
		//    $ced=str_pad($cedula, 10, " ", STR_PAD_LEFT);
		    $cedula 		= $cedulas[$i]; 
		    $nombres1		= $nombres[$i];
		    //$hora_entrada_real1	= $hora_entrada_real[$i];
		    //$hora_salida_real1	= $hora_salida_real[$i];
		    $asistio1		= $asistio[$i];
		    $cambioturno1	= $cambioturno[$i];
		    $comision1		= $comision[$i];
		    $permiso1		= $permiso[$i];
		    $observacion1	= $observacion[$i];
		    $sobretiempo1	= $sobretiempo[$i];
		    $turno1		= $turno[$i];
		    $grupo1		= $grupo[$i];
		    //if ($hora_entrada_real1=='' or $hora_entrada_real1='undefined') $hora_entrada_real1='null'; else $hora_entrada_real1="'".$hora_entrada_real1."'";
		    //if ($hora_salida_real1==''or $hora_salida_real1=='undefined') $hora_salida_real1='null'; else $hora_salida_real1="'".$hora_salida_real1."'";
		//print ('I='.$i.', Asistio='.$asistio1.', Sobre Tiempo='.$sobretiempo1.', Cambio de Turno='.$cambioturno1.', Comision='.$comision1.'<br>');
		
		    //$datos_orampdr = ProcesarCedula($ced, $tipo, $estatus);
		
		    $insertConst="INSERT INTO registro_diario (";
		    $insertConst.="trabajador,";
		    $insertConst.="fecha,";
		    //$insertConst.="entrada_real1,";
		    //$insertConst.="salida_real1,";
		    $insertConst.="asistio,";
		    $insertConst.="sobre_tiempo,";
		    $insertConst.="cambio_turno,";
		    $insertConst.="comision,";
		    $insertConst.="inasistencia,";            
		    $insertConst.="observacion,";
		    $insertConst.="fecha_reg,";
		    $insertConst.="trabajador_reg, ";
		    $insertConst.="bloqueado, ";
		    $insertConst.="turno, ";
		    $insertConst.="grupo";
		    $insertConst.=") VALUES (";    
		    $insertConst.="'".$cedula."',";
		    $insertConst.="'".$fecha_fichada."',";
		    //$insertConst.=$hora_entrada_real1.",";
		    //$insertConst.=$hora_salida_real1.", ";
		    $insertConst.="'".$asistio1."',";
		    $insertConst.="'".$sobretiempo1."', ";
		    $insertConst.="'".$cambioturno1."', ";
		    $insertConst.="'".$comision1."', ";
		    $insertConst.="'".$permiso1."', ";
		    $insertConst.="'".$observacion1."', ";
		    $insertConst.="'".$hoy."', ";
		    $insertConst.="'".$_SESSION['user_session_const']."', ";
		    $insertConst.="'0',";
		    $insertConst.="'".$turno1."',";
		    $insertConst.="'".$grupo1."'";
		    $insertConst.=")";
    
		    $result = pg_query($link,$insertConst);
	    
		    if (pg_affected_rows($result)==0)
		    { 
		         echo("ERROR"); 
		         die(" Consulta SQL: ".$insertConst);
		    }else
		      $cont++;

	  }
	    $aud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
//print $insertConst;
	  pg_close($link);
	  echo '1';      
	}else{
	  //el registro no existe
	  echo("ERROR");     
	  die(" SIN REGISTROS PROPORCIONADOS");
	}
     }else{
	  echo("ERROR");     
	  die(" YA EXISTEN FICHADAS REGISTRADAS CON ESTOS VALORES".$insertConst);
     }
	
}
?>
