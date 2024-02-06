<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();
if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
        $nroreg		    = isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
	$fecha_fichada	    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
	$cbotrabajador	    = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
        //$accion             = isset($_POST["accion"])?$_POST["accion"]:"NULL";
        $hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
        $hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
	$observacion_cp     = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
	//$accion_ct	    = isset($_POST["cboaccion"])?$_POST["cboaccion"]:"NULL";
	$entrada_esperada1  = isset($_POST["entrada_esperada1"])?$_POST["entrada_esperada1"]:"NULL";
	$salida_esperada1   = isset($_POST["salida_esperada1"])?$_POST["salida_esperada1"]:"NULL";
	$entrada_real1      = isset($_POST["entrada_real1"])?$_POST["entrada_real1"]:"NULL";
	$salida_real1  	    = isset($_POST["salida_real1"])?$_POST["salida_real1"]:"NULL";
	$entrada_real2      = isset($_POST["entrada_real2"])?$_POST["entrada_real2"]:"NULL";
	$salida_real2  	    = isset($_POST["salida_real2"])?$_POST["salida_real2"]:"NULL";
if ($entrada_real2=='') $entrada_real2='NULL';
if ($salida_real2=='') $salida_real2='NULL';
//echo "Hora 1:".$hinicio1.", Hora 2:".$hfinal1.", Hora Entrada1:".$entrada_real1.", Hora Salida12:".$salida_real1.", Hora Entrada2:".$entrada_real2.", Hora Salida2:".$salida_real2."<br>";
	//$esperanza_nueva    = isset($_POST["cboesperanza_cambiada"])?$_POST["cboesperanza_cambiada"]:"NULL";
	/*if ($accion=="1"){
            $entrada_real1	= "'".$entrada_esperada1."'";
	    $salida_real1	= "'".$salida_esperada1."'";
	    $entrada_real2	= "'".$entrada_esperada2."'";
	    $salida_real2	= "'".$salida_esperada2."'"; 
	}else{
            $entrada_real1	= "'".$entrada_real1."'";
	    $salida_real1	= "'".$salida_real1."'";
	    if ($entrada_real2!='') $entrada_real2	= "'".$entrada_real2."'"; else $entrada_real2='NULL';
	    if ($salida_real2!='' ) $salida_real2	= "'".$salida_real2."'";  else $salida_real2 ='NULL';
	}
        */
	if ($entrada_esperada1!='') $entrada_esperada1 = "'".$entrada_esperada1."'"; else $entrada_esperada1 ='NULL';
	if ($salida_esperada1!='')  $salida_esperada1  = "'".$salida_esperada1."'";  else $salida_esperada1  ='NULL';

	/*if ($salida_real1!=$salida_esperada1){
		$cambio_permanencia='S';
	}else
		$cambio_permanencia='N';
*/
	$hoy= date("Y") . "-" . date("m") . "-" . date("d");
	$ced="";
	$query="select * from registro_diario where trabajador in ('".trim($cbotrabajador)."') and fecha='".$fecha_fichada."'";
//print $query."-".$hinicio1."-".$hfinal1 ;
	$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
	$numReg = ejecutar_num_rows($result);

	if($numReg==0){
		    $insertConst="INSERT INTO registro_diario (";
		    $insertConst.="trabajador, ";
		    $insertConst.="fecha, ";
		    $insertConst.="asistio, ";
		    $insertConst.="cambio_turno, ";
		    $insertConst.="comision, ";
		    $insertConst.="inasistencia, ";
		    $insertConst.="motivo_cp, ";
		    $insertConst.="fecha_reg, ";
		    $insertConst.="trabajador_reg, ";
		    $insertConst.="registrado_cp, ";
		    //$insertConst.="accion_ct, ";
		    $insertConst.="entrada_real1, ";
		    $insertConst.="salida_real1, ";
		    $insertConst.="entrada_real2, ";
		    //if ($entrada_real2!="") $insertConst.="entrada_real2, ";
		    $insertConst.="salida_real2, ";
		    $insertConst.="entrada_esperada1, ";
		    $insertConst.="salida_esperada1, ";
		    //$insertConst.="turno, ";
		    $insertConst.="cambio_permanencia, ";
		    $insertConst.="bloqueado ";
		    $insertConst.=") VALUES (";
		    $insertConst.="'".trim($cbotrabajador)."',";
		    $insertConst.="'".$fecha_fichada."',";
		    $insertConst.="'N', ";
		    $insertConst.="'N', ";
		    $insertConst.="'N', ";
		    $insertConst.="0, ";
		    $insertConst.="'".$observacion_cp."', ";
		    $insertConst.="'".$hoy."', ";
		    $insertConst.="'".$_SESSION['user_session_const']."', ";
		    $insertConst.="'".$_SESSION['user_session_const']."', ";
		    //$insertConst.="'".$accion_ct."', ";
		    $insertConst.="'".$hinicio1."',";
		    $insertConst.="'".$hfinal1."', ";
		    $insertConst.=$entrada_real2.", ";
		    //if ($entrada_real2) $insertConst.=$entrada_real2.", ";
		    $insertConst.=$salida_real2.", ";
		    $insertConst.=$entrada_esperada1.", ";
		    $insertConst.=$salida_esperada1.", ";
		    //$insertConst.=$esperanza_nueva.", ";
		    $insertConst.="'S', ";
		    $insertConst.="'0'";
		    $insertConst.=")";
  	            $mens="Se registro; el Cambio de Turno al trabajador: ".nombre_trabajadores(trim($cbotrabajador));  

/*		    $result = pg_query($link,$insertConst);
	    
		    if (pg_affected_rows($result)==0)
		    { 
		         echo("ERROR"); 
		         die(" Consulta SQL: ".$insertConst);
		    }else
		      $cont++;

	    $aud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
//print $insertConst;
	  pg_close($link);
	  echo '1';      
*/
     }else{
/*	while ($fila=ejecutar_fetch_array($result)) 
    	{
      		$observacionDB= trim($fila['observacion']);
    	}
*/
	$insertConst="UPDATE registro_diario SET ";
	$insertConst.="entrada_real1='".$entrada_real1."', ";
	$insertConst.="salida_real1='".$salida_real1."'," ;
	$insertConst.="entrada_real2=".$entrada_real2.", ";
	$insertConst.="salida_real2=".$salida_real2."," ;
	$insertConst.="motivo_ct='".$observacion_cp."', ";
	$insertConst.="registrado_cp='".$_SESSION['user_session_const']."' ";
//        $insertConst.="accion_ct = '".$accion_ct."' ";
        $insertConst.="where trabajador = '".trim($cbotrabajador)."' ";
        $insertConst.="and fecha= '".$fecha_fichada."'";
        $mens="Se actulizÃ el cambio de Permanencia al trabajador: ".nombre_trabajadores(trim($cbotrabajador));  
      }
      print $insertConst;	
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
        $asunto="Carga de Cambio de Turno";
        $cuerpo="Saludos, Se le cargo un cambio de turno al trabajador: ".nombre_trabajadores(trim($cbotrabajador)).", portador de la c&eacute;dula de indentidad:".trim($cbotrabajador).", el dia: ".$fecha_fichada.", por concepto de: ".trim($observacion).".";
        $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve","matmoa@briqven.com.ve");
//        $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matzem@briqven.com.ve");
 
	header ("Location: cambio_turno.php");	
}
?>
