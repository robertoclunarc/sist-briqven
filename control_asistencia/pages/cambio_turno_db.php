<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();
if (isset($_SESSION['user_session_const']))
{   
	$link=Conex_Contancia_pgsql();
     
    $nroreg		        = isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
	$fecha      	    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
	$cbotrabajador	    = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
    $accion             = isset($_POST["accion"])?$_POST["accion"]:"NULL";
    $hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
    $hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
	$observacion        = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
	$accion_ct	        = isset($_POST["cboaccion"])?$_POST["cboaccion"]:"NULL";
	$entrada_esperada1  = isset($_POST["entrada_esperada1"])?$_POST["entrada_esperada1"]:"NULL";
	$salida_esperada1   = isset($_POST["salida_esperada1"])?$_POST["salida_esperada1"]:"NULL";
	$entrada_real1      = isset($_POST["entrada_real1"])?$_POST["entrada_real1"]:"NULL";
	$salida_real1  	    = isset($_POST["salida_real1"])?$_POST["salida_real1"]:"NULL";
	$entrada_real2      = isset($_POST["entrada_real2"])?$_POST["entrada_real2"]:"NULL";
	$salida_real2  	    = isset($_POST["salida_real2"])?$_POST["salida_real2"]:"NULL";
	$esperanza_nueva    = isset($_POST["cboesperanza_cambiada"])?$_POST["cboesperanza_cambiada"]:"NULL";
	$SP                 = "SW_GRABA_CAMBIO_ESPERANZA_CEDULA";

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

    switch ($esperanza_nueva) {
      case '1':
        $entEsp1='23:00';
        $salEsp1='07:00';
        $entrada2='';
        $salida2='';
        break;
      case '2':
        $entEsp1='07:00';
        $salEsp1='15:00';
        $entrada2='';
        $salida2='';
        break;
      case '3':
        $entEsp1='15:00';
        $salEsp1='23:00';
        $entrada2='';
        $salida2='';

        break;   
      case '4':
        $entEsp1='07:00';
        $salEsp1='12:00';
        $entrada2='13:00';
        $salida2='16:00';
        break;   

    }

//if ($entrada2==''){
      //$entrada2='';
      // $salida2='';
      //}
       //print $cbotrabajador.', '.$fecha.', '.$entEsp1.', '.$salEsp1.', '.$entrada2.', '.$salida2.', '.$_SESSION['cedula_session_const'];
      $mbd=Conectarse_sitt(); 
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?, ?");
      $stmt->bindParam(1, $cbotrabajador,  PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha,  PDO::PARAM_STR,10);
      $stmt->bindParam(3, $entEsp1,  PDO::PARAM_STR,5);
      $stmt->bindParam(4, $salEsp1,  PDO::PARAM_STR,5);
      $stmt->bindParam(5, $entrada2,  PDO::PARAM_STR,5);
      $stmt->bindParam(6, $salida2,  PDO::PARAM_STR,5); 
      $stmt->bindParam(7, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10); 
      $stmt->execute();


        require("enviodecorreos.php");
/*************************************************/
        //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
      $asunto="Cambio de Esperanza del trabajador: ".nombre_trabajadores(trim($cbotrabajador)).", de fecha: ".formato_fecha($fecha,'-');
      $cuerpo  = "Saludos, Se realizo un cambio de Esperanza al trabajador<br>
      <table border='1'>
        <tr>
           <td colspan='2'>Nombre </td>
           <td colspan='2'><b>".nombre_trabajadores(trim($cbotrabajador))."</b></td>
        </tr>
        <tr>
           <td colspan='2'>C&eacute;dula de indentidad </td>
           <td colspan='2'>".trim($cbotrabajador)."</td>
        </tr>
        <tr>
           <td colspan='2'>En fecha </td>
           <td colspan='2'><b>".formato_fecha($fecha,'-')."</b></td>
        </tr>
        <tr>
           <td rowspan='2' colspan='2'><b>Entrada Esperada</b></td>
           <td style='text-align:center; vertical-align:middle'>Anterior</td>
           <td>".$entrada_esperada1."</td>
        </tr>
        <tr>
          <td style='text-align:center; vertical-align:middle'><b>Nueva</b></td> 
          <td><b>".$entEsp1."</b></td>
        </tr>           
        <tr>   
           <td rowspan='2' colspan='2'><b>Salida Esperada</b></td>           
           <td style='text-align:center; vertical-align:middle'>Anterior</td>
           <td>".$salida_esperada1."</td>           
        </tr>   
        <tr>
           <td style='text-align:center; vertical-align:middle'><b>Nueva</b></td>
           <td><b>".$salEsp1."</b></td>
        </tr>
        <tr>   
           <td colspan='2'>Registrado por: </td>
           <td colspan='2'>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
        </tr>   
        <tr>   
           <td colspan='2'>A trav&eacute;s del M&oacute;dulo</td>
           <td colspan='2'> Control Asistencia.</td>
        </tr></table>";
      ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","informatico12@gmail.com","matzem@briqven.com.ve");
      echo '1';
       /*************************************************/      

}
?>
