<?php
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$update="";
$update1="";
if(!empty($_POST['sexo']))
  $update=$update."sexo='".$_POST['sexo']."',";
if(!empty($_POST['fecha_nac']))
  $update=$update."fecha_nacimiento='".$_POST['fecha_nac']."',";
if(!empty($_POST['domicilio']))
  $update=$update."domicilio='".$_POST['domicilio']."',";
if(!empty($_POST['domicilio2']))
  $update=$update."domicilio2='".$_POST['domicilio2']."',";
if(!empty($_POST['poblacion']))
  $update=$update."poblacion='".$_POST['poblacion']."',";
if(!empty($_POST['estado_provincia']))
  $update=$update."estado_provincia='".$_POST['estado_provincia']."',";
if(!empty($_POST['codigo_postal']))
  $update=$update."codigo_postal='".$_POST['codigo_postal']."',";
if(!empty($_POST['calles_aledanas']))
  $update=$update."calles_aledanas='".$_POST['calles_aledanas']."',";
if(!empty($_POST['telefono_particular']))
  $update=$update."telefono_particular='".$_POST['telefono_particular']."',";
if(!empty($_POST['reg_seguro_social']))
  $update=$update."reg_seguro_social='".$_POST['reg_seguro_social']."',";
if(!empty($_POST['domicilio3']))
  $update=$update."domicilio3='".$_POST['domicilio3']."',";
if(!empty($_POST['e_mail']))
  $update=$update."e_mail='".$_POST['e_mail']."',";
if(!empty($_POST['fkunidad']))
  $update=$update."fkunidad=".$_POST['fkunidad'].",";

$update = substr ($update, 0, strlen($update) - 1);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['fecha_ingreso']))
  $update1=$update1."fecha_ingreso='".$_POST['fecha_ingreso']."',";
if(!empty($_POST['fecha_antiguedad']))
  $update1=$update1."fecha_antiguedad='".$_POST['fecha_antiguedad']."',";
if(!empty($_POST['fecha_baja']))
  $update1=$update1."fecha_baja='".$_POST['fecha_baja']."',";
if(!empty($_POST['fecha_vto_contrato']))
  $update1=$update1."fecha_vto_contrato='".$_POST['fecha_vto_contrato']."',";
if(!empty($_POST['relacion_laboral']))
  $update1=$update1."relacion_laboral='".$_POST['relacion_laboral']."',";
if(!empty($_POST['clase_nomina']))
  $update1=$update1."clase_nomina='".$_POST['clase_nomina']."',";
if(!empty($_POST['sistema_antiguedad']))
  $update1=$update1."sistema_antiguedad=".$_POST['sistema_antiguedad'].",";
if(!empty($_POST['sistema_horario']))
  $update1=$update1."sistema_horario=".$_POST['sistema_horario'].",";
if(!empty($_POST['turno']))
  $update1=$update1."turno=".$_POST['turno'].",";
if(!empty($_POST['sit_trabajador']))
  $update1=$update1."sit_trabajador=".$_POST['sit_trabajador'].",";
if(!empty($_POST['grupo_sanguinio']))
  $update1=$update1."grupo_sanguinio='".$_POST['grupo_sanguinio']."',";

$update1 = substr ($update1, 0, strlen($update1) - 1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$query1="UPDATE trabajadores SET ".$update." WHERE trabajador='".$_POST['cedula']."'; ";

$query2="UPDATE trabajadores_grales SET ".$update1." WHERE trabajador='".$_POST['cedula']."'; ";
	
$exe_update=  pg_query($cn,$query1.$query2);

//echo $query1.$query2;
print_r( pg_last_error() );

?>