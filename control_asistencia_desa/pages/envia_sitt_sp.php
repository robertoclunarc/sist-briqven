<?php
session_start();

$fecha= isset($_GET["fecha"])?$_GET["fecha"]:NULL;
$trabajador= isset($_GET["cedula"])?$_GET["cedula"]:NULL;
$entrada1 = isset($_GET["entrada1"])?$_GET["entrada1"]:NULL;
$salida1 = isset($_GET["salida1"])?$_GET["salida1"]:NULL;
$entrada2 = isset($_GET["entrada2"])?$_GET["entrada2"]:'NULL';
$salida2 = isset($_GET["salida2"])?$_GET["salida2"]:'NULL';
$codigo = isset($_GET["coderror"])?$_GET["coderror"]:NULL;

$Sp= isset($_GET["SP"])?$_GET["SP"]:NULL;

if (isset($_SESSION['user_session_const'])){

    switch ($Sp) {
      case 1:
        $storeP='poner_fichada_codausencia_horas';
        break;
      case 2:
        $storeP='poner_fichada_y_codigo_ausencia';
        break;           
      /*
      default:
        # code...
        break;
     */
    }
    
    echo ejecutarSp($fecha, $trabajador, $entrada1, $salida1, $entrada2, $salida2, $codigo, $storeP);     
}
else{
    echo 'Debe iniciar la su sesion'; 
}

function ejecutarSp($fecha, $trabajador, $entrada1, $salida1, $entrada2, $salida2, $codigo, $SP) {
   include("../BD/conexion.php");
   include("funciones_var.php");
      
   $mbd=Conectarse_sitt();       
   $inpt="No se ejecuto ninguna operacion";    
   if ($SP=='poner_fichada_y_codigo_ausencia'){
      $exeSp="exec ".$SP." '".$fecha."', ".$trabajador.", ".$codigo.", ".$_SESSION['cedula_session_const'];
      //echo $exeSp;
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $codigo, PDO::PARAM_INT);           
      $stmt->bindParam(4, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);      
      
      $inpt='Fichada Completada!';
   }
   elseif ($SP=='poner_fichada_codausencia_horas') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $codigo, PDO::PARAM_INT);
      $stmt->bindParam(4, $entrada1, PDO::PARAM_STR,5);
      $stmt->bindParam(5, $salida1, PDO::PARAM_STR,5);           
      $stmt->bindParam(6, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->bindParam(7, $entrada2, PDO::PARAM_STR,5);
      $stmt->bindParam(8, $salida2, PDO::PARAM_STR,5);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      $exeSp="exec ".$SP." '".$fecha."', ".$codigo.", '".$entrada1."', '".$salida1."', ".$_SESSION['cedula_session_const'];
      if ($row['cedula']==$trabajador){
        if ($row['entrada_real1']==''){
          $inpt='El trabajador no tiene esperanza de trabajo. ';//.$exeSp." : ";
        }else{         
          $inpt='Entrada: '.$row['entrada_real1'].'. Salida: '.$row['salida_real1'];
        }  
      }
      else
        $inpt='Error al colocar la fichada parcial con codigo de ausencia';
      
   }
    
  return $inpt;

}         
?>
