<?php
include("../BD/conexion.php");
require('funciones_var.php');

session_start();
         
$nroreg= isset($_POST["hddcontador"])?$_POST["hddcontador"]:0;

$link=Conex_Contancia_pgsql();
$fecha = fecha_actual('LARGO', '',$link);

if(array_key_exists('cedulas',$_POST) && $nroreg>0)
{          
  $cedulas = $_POST['cedulas'];          
  $nombres = $_POST['nombres'];
  $tipos = $_POST['tipos'];
  $estatuss = $_POST['estatuss']; 
  //$destinatario = $_POST["destinatario"];
  $cont=0;
  foreach ($cedulas as $i => $cedula)
  {            
    $ced=str_pad($cedula, 10, " ", STR_PAD_LEFT);
    $nombre = $nombres[$i]; 
    $tipo=$tipos[$i];
    $estatus=$estatuss[$i];

    $datos_orampdr = ProcesarCedula($ced, $tipo, $estatus);

    $insertConst="INSERT INTO tbl_constacias (";
    $insertConst.="fecha,";
    $insertConst.="cedula,";
    $insertConst.="nombres,";
    $insertConst.="fecha_ingreso,";
    $insertConst.="cargo,";
    $insertConst.="sitiodetrabajo,";
    $insertConst.="bsennumero,";
    $insertConst.="bsenletras,";            
    $insertConst.="mes,";
    $insertConst.="tipo,";
    $insertConst.="bsintennumeros, ";
    $insertConst.="bsintenletras, ";
    $insertConst.="usuario, ";
    $insertConst.="sexo, ";
    $insertConst.="comision, ";
    $insertConst.="ente_adscrito";
    $insertConst.=") VALUES (";
    $insertConst.="'".$fecha."',";
    $insertConst.="'".$cedula."',";
    $insertConst.="'".$nombre."',";
    $insertConst.="'".$datos_orampdr['fecha_ingreso']."', ";
    $insertConst.="'".$datos_orampdr['cargo']."',";
    $insertConst.="'".$datos_orampdr['SitioDeTrabajo']."', ";
    $insertConst.="'".$datos_orampdr['bsennumero']."', ";
    $insertConst.="'".$datos_orampdr['bsenletras']."', ";
    $insertConst.="'".$datos_orampdr['mes']."', ";
    if ($datos_orampdr['clase_nomina']=='PA')
      $tipo='Salario Basico';
    $insertConst.="'".$tipo."', ";
    $insertConst.="'".$datos_orampdr['bsintennumeros']."', ";
    $insertConst.="'".$datos_orampdr['bsintenletras']."', ";
    $insertConst.="'".$_SESSION['user_session_conslab']."', ";
    $insertConst.="'".$datos_orampdr['SEXO']."', ";
    if ($datos_orampdr['comision']==1)
      $insertConst.="TRUE, ";
    else
      $insertConst.="FALSE, ";
    $insertConst.="'".$datos_orampdr['ENTEADSCRI']."'";
    $insertConst.=")";
    
    $result = pg_query($link,$insertConst);
    
    if (pg_affected_rows($result)==0)
    { 
         echo("ERROR"); 
         die(" Consulta SQL: ".$insertConst);
    }
    else
      $cont++;

  }
  $aud=auditar("Emision de Constancia: ".$fecha, $_SESSION['user_session_conslab'],$link);
  pg_close($link);
  echo $fecha;      
}else{
  //el registro no existe
  echo("ERROR");     
  die("SIN REGOSTROS PROPORCIONADOS");
}
?>