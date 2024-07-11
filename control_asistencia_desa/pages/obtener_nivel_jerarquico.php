<?php
  session_start();

  $trabajador= isset($_GET["trabajador"])?$_GET["trabajador"]:"";

  include("../BD/conexion.php");
  require_once('funciones_var.php');
  $link2=Conex_Contancia_pgsql();
  $query="SELECT nivel_jerarquico FROM adam_VW_DOTACION_BRIQVEN_02_MAS WHERE trabajador = '".$trabajador."'";
  $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
  $numReg = ejecutar_num_rows($result);
  if($numReg>0){
    $row    = ejecutar_fetch_array($result);
    $nivel_jerarquico = $row['nivel_jerarquico'];
  }
  ejecutar_close($link2);
  print $nivel_jerarquico;
?>