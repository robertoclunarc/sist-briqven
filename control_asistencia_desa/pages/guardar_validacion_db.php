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
	$validados = $_POST['chkvalidado'];

	if (isset($validados)) {

	    foreach($validados as $validado2){
		$datos=explode("/",$validado2);
   		$updateConst="update registro_diario set ";
                    $updateConst.="bloqueado='1' ";
                    $updateConst.=" where ";
                    $updateConst.="trabajador='".$datos[0]."' and  ";
                    $updateConst.="fecha='".$datos[1]."'";
                    $result = pg_query($link,$updateConst);
                    if (pg_affected_rows($result)==0)
                    { 
                         echo("ERROR"); 
                         die(" Consulta SQL: ".$updateConst);
                    }

            }
           $aud=auditar("Registrar validar fichada de ".$datos[0]." de fecha=".$datos[1], $_SESSION['user_session_const'],$link);
	  pg_close($link);
	  echo '1';      
	}else{
	  //el registro no existe
	  echo("ERROR");     
	  die(" SIN REGISTROS SELECCIONADOS");
	}
	
}
?>
