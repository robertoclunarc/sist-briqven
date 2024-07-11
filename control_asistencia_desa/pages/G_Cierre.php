<?php 
session_start();
$semana= isset($_GET["sem"])?$_GET["sem"]:"NULL"; 
$anio= isset($_GET["anio"])?$_GET["anio"]:"NULL"; 
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['user_session_const'])){
  include("../BD/conexion.php");	
  $mbd=Conectarse_sitt(); 
  $pdo_options = array();
$pdo_options[PDO::ATTR_EMULATE_PREPARES] = true;

  $query= "exec dbo.SW_Cierre_Semana_ME_repetir ?, ?";
  $stmt = $mbd->prepare($query, $pdo_options);
  
  $stmt->bindParam(1, $semana, PDO::PARAM_INT);           
  $stmt->bindParam(2, $anio, PDO::PARAM_INT);
  $stmt->execute();
  
  $stmt =null;
  $mbd=null;
	echo '<div class="alert alert-success">Operacion Exitosa en la Semana '.$semana.'.</div>';
   	
}	
else
	echo "<div class='alert alert-danger'>Debe Iniciar Sesion </div>"; 
?>