<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();
if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
        $periodo		= $_POST['cboperiodo'];
        $nroreg= isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 

  $query="select * from evaluacion where periodo=".$periodo." and supervisor='".$_SESSION['user_session_const']."'";
  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);

  if($numReg>0){
	echo("Ya existen registros cargados para este periodo");

  }else{
        //ejecutar_close($link); 
	//$link=Conex_Constancia_pgsql();
     
	if(array_key_exists('cedulas',$_POST) && $nroreg>0)
	{          
	  $cedulas 		= $_POST['cedulas'];          
	  $nombres 		= $_POST['nombres'];
	  $puntuacion		= $_POST['puntuacion'];
	  $observacion 		= $_POST['observacion'];
	  $cont=0;
	  foreach ($cedulas as $i => $cedula)
	 {
	    $nombre		= $nombres[$i];
	    $puntuacion1	= $puntuacion[$i];
	    $observacion1	= $observacion[$i];
	    //$datos_orampdr = ProcesarCedula($ced, $tipo, $estatus);

	    $insertConst="INSERT INTO evaluacion (";
	    $insertConst.="periodo,";
	    $insertConst.="trabajador,";
	    $insertConst.="puntuacion,";
	    $insertConst.="observacion,";
	    $insertConst.="fecha_reg,";
	    $insertConst.="supervisor ";
	    $insertConst.=") VALUES (";    
	    $insertConst.="".$periodo.",";
	    $insertConst.="'".$cedula."',";
	    $insertConst.=$puntuacion1.",";
	    $insertConst.="'".$observacion1."', ";
	    $insertConst.="'".date("Y-m-d")."',";
	    $insertConst.="'".$_SESSION['user_session_const']."' ";
	    $insertConst.=")";
	    $insertConst."<br>"; 
	    
	    $result = pg_query($link,$insertConst);
	    
	    if (pg_affected_rows($result)==0)
	    { 
        	 echo("ERROR"); 
	         die(" Consulta SQL: ".$insertConst);
	    }else	
	      $cont++;

	  }
	//  $aud=auditar("Emision de Constancia: ".$fecha, $_SESSION['user_session_const'],$link);
	  ejecutar_close($link);
	   echo '1';

	}else{
	  //el registro no existe
	  echo("ERROR");     
	  die("SIN REGOSTROS PROPORCIONADOS");
	}

}	
}
?>
