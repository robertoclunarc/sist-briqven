<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();
if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
//        $nroreg= isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
	$fecha_fichada= isset($_POST["txtfecha"])?$_POST["txtfecha"]:date("Y")."-".date("m")."-".date("d");

        $hoy= date("Y") . "-" . date("m") . "-" . date("d");
	$trabajador=$_POST["txttrabajador"];
	$query="select * from registro_diario where trabajador='".$trabajador."' and fecha='".$fecha_fichada."'";
	$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
	$numReg = ejecutar_num_rows($result);
//echo $query." ".$numReg." ".$nroreg;

	if($numReg>0){
//		  $cedulas 		= $_POST['cedulas'];          
		  //$nombres 		= $_POST['nombres'];
		  $asistio 		= isset($_POST['chkasistio'])?$_POST['chkasistio']:"N";
		  $cambioturno 		= isset($_POST['chkcambioturno'])?$_POST['chkcambioturno']:"N";
		  $comision 		= isset($_POST['chkcomision'])?$_POST['chkcomision']:"N";
		  $permiso 		= isset($_POST['cbopermiso'])?$_POST['cbopermiso']:"N";
		  $observacion 		= isset($_POST['txtobservacion'])?$_POST['txtobservacion']:"";
		  $sobretiempo 		= isset($_POST['chksobretiempo'])?$_POST['chksobretiempo']:"N";
		  $turno 		= isset($_POST['cboesperanza_cambiada'])?$_POST['cboesperanza_cambiada']:"";
		  $grupo		= isset($_POST['cbogrupo'])?$_POST['cbogrupo']:"N"; 
		  $cont=0;
		
		  $observacion1=substr($observacion,stripos($observacion,"\n")+1);
//		  print $observacion1."<br>".$turno;

		//    $ced=str_pad($cedula, 10, " ", STR_PAD_LEFT);
		  if ($asistio=="on") $asistio="S";
		  if ($cambioturno=="on") $cambioturno="S";
		  if ($comision=="on") $comision="S";
		  if ($permiso=="on") $permiso="S";
		  if ($sobretiempo=="on") $sobretiempo="S";



		    $insertConst="UPDATE registro_diario SET ";
		    $insertConst.=" asistio='".$asistio."',";
		    $insertConst.=" sobre_tiempo='".$sobretiempo ."',";
		    $insertConst.=" cambio_turno='".$cambioturno."',";
		    $insertConst.=" comision='".$comision."',";
		    $insertConst.=" inasistencia=".$permiso.",";            
		    $insertConst.=" observacion='".$observacion."',";
//		    $insertConst.=" fecha_ult_mod='".$hoy."',";
		    //$insertConst.="bloqueado, ";
		    $insertConst.=" turno='".$turno."',";
		    $insertConst.=" grupo=".$grupo;
		    $insertConst.=" where trabajador='".$trabajador."'";    
		    $insertConst.=" and fecha='".$fecha_fichada."'";    
//   print $insertConst; 
		    $result = pg_query($link,$insertConst);
	    
		    if (pg_affected_rows($result)==0)
		    { 
		         echo("ERROR"); 
		         die(" Consulta SQL: ".$insertConst);
		    }else
		      $cont++;
	    $aud=auditar("Modificar fichada de ".$trabajador." de fecha=".$fecha_fichada, $_SESSION['cedula_session_const'],$link);

	  pg_close($link);
	  echo '2';      

     }else{
	  echo("ERROR");     
	  die("NO  EXISTEN FICHADAS REGISTRADAS CON ESTOS VALORES");
     }
	
}
?>
