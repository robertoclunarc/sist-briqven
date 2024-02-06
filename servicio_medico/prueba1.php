<?php
@session_start();
require("enviodecorreos.php");
require("planilla_certificado.php");
function prueba1($id_consulta){
	@session_start();
	require("include_conex.php");
	$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
 $query="SELECT m.ci, m.fecha, m.motivo, c.idmotivo, m.cargo,m.departamento, m.paramedico, m.medico, m.nombre_completo, m.nombres_jefe, c.condicion, m.sexo, m.reposo FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
  $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
  $rowmorb = pg_fetch_array($resultmorb);

$desc_mot=$rowmorb['motivo'];
$idmot=$rowmorb['idmotivo'];
$nombre_completo=$rowmorb['nombre_completo'];
$mor_sex=$rowmorb['sexo'];
$mor_ci=$rowmorb['ci'];
$nom_paramedico=$rowmorb['paramedico'];
$nom_medico=$rowmorb['medico'];
$mor_cond=$rowmorb['condicion'];
$mor_fecha=substr($rowmorb['fecha'],0,16);
$mor_depar=$rowmorb['departamento'];
$mor_cargo=$rowmorb['cargo'];
$mor_nomjefe=$rowmorb['nombres_jefe'];
$mor_reposo=$rowmorb['reposo'];
pg_free_result($resultmorb);
//require("enviodecorreos.php");
if (($idmot==7) || ($idmot==8) || ($idmot==9) || ($idmot==10))
{
	$asunto=$desc_mot." ".$nombre_completo;

	if ($mor_sex=='M')
		$enc='<p>&nbsp;</p>Se le notifica que el Sr. ';
	else
		$enc='<p>&nbsp;</p>Se le notifica que la Sra. ';
	
	$cuerpo=$enc.'<strong>'.$nombre_completo.'</strong>, con Cedula de Identidad <strong>'.$mor_ci.'</strong>, se realiz&oacute; el examen correspondiente a <strong>'.$desc_mot.'</strong>.<br>';
	
	if ($nom_medico == '')
		$atendio='param&eacute;dico ocupante <strong>'.$nom_paramedico.'</strong>';
	else
		$atendio='m&eacute;dico ocupante <strong>'.$nom_medico.'</strong>';

	if ($mor_cond=="APTO")
		$colorcondicion='<font color="green"><strong>'.$mor_cond.'</strong></font>';
	else
		$colorcondicion='<font color="red"><strong>'.$mor_cond.'</strong></font>';
	
	$cuerpo .= 'El cual est&aacute; en condici&oacute;n: '.$colorcondicion.' seg&uacute;n lo considerado en la consulta m&eacute;dica registrada en la fecha: <strong>'.$mor_fecha.'</strong> por el '.$atendio.'.<br>';

	if ($mor_sex=='M')
		$cuerpo .= 'Otros datos de inter&eacute;s sobre el trabajador:<br>';
	else	
		$cuerpo .= 'Otros datos de inter&eacute;s sobre la trabajadora:<br>';
	
	$cuerpo .= '<table><thead><tr><th>Departamento:</th><th>'.$mor_depar.'</th></tr>';
	$cuerpo .= '<tr><th>Cargo:</th><th>'.$mor_cargo.'</th></tr>';
	$cuerpo .= '<tr><th>Supervisor:</th><th>'.$mor_nomjefe.'</th></tr></thead></table>';
	$cuerpo .= '<br>';
	$cuerpo .= '<br>';
	$cuerpo .= 'Para m&aacute;s informaci&oacute;n por favor debe comunicarse con el &aacute;rea de Servicio M&eacute;dico a la Ext. Nro. 259.<p>&nbsp;</p>';

    $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['userid'], $desc_mot);
    
}
if (($idmot==7) || ($idmot==8) || ($idmot==9) || ($idmot==10)  || ($idmot==13))
{
  //require("planilla_certificado.php");

  $resp1=ENVIAR_CORREO(planilla_certificado($id_consulta),"Certificado Medico ".$nombre_completo,"",$_SESSION['userid'], $desc_mot);

  //$resp1=ENVIAR_CORREO(planilla_certificado($id_consulta),"Certificado Medico ".$nombre_completo,"",$_SESSION['userid'], "PRUEBA");
}

$os = array("NULL", "0", "N/A", "null", "");
echo 'reposo>'.$mor_reposo;
//print_r($os);
if (!in_array($mor_reposo, $os)){
  require("funciones_var.php");	
  $asunto="REPOSO ".$nombre_completo;
  $resp=ENVIAR_CORREO(nota_reposo($id_consulta), $asunto,"", $_SESSION['userid'], "REPOSO");
    //$resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['userid'], "PRUEBA");    
}

}

function prueba2_preempleo($id_consulta){
session_start();
	require("include_conex.php");
	$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());	
$query="SELECT m.ci, m.fecha, m.motivo, c.idmotivo, m.cargo, m.departamento, m.paramedico, m.medico, m.nombre_completo, m.nombres_jefe, c.condicion, m.sexo, m.reposo FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
  $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
  $rowmorb = pg_fetch_array($resultmorb);

$desc_mot=$rowmorb['motivo'];
$motivo=$rowmorb['idmotivo'];
$nombre_completo=$rowmorb['nombre_completo'];
$mor_sex=$rowmorb['sexo'];
$mor_ci=$rowmorb['ci'];
$nom_paramedico=$rowmorb['paramedico'];
$nom_medico=$rowmorb['medico'];
$mor_cond=$rowmorb['condicion'];
$mor_fecha=substr($rowmorb['fecha'],0,16);
$mor_depar=$rowmorb['departamento'];
$mor_cargo=$rowmorb['cargo'];
$mor_nomjefe=$rowmorb['nombres_jefe'];
$mor_reposo=$rowmorb['reposo'];
pg_free_result($resultmorb);

if ($mor_sex=='M')
		$enc='<p>&nbsp;</p>Se le notifica que el Sr. ';
	else
		$enc='<p>&nbsp;</p>Se le notifica que la Sra. ';
	
	$cuerpo=$enc.'<strong>'.$nombre_completo.'</strong>, con Cedula de Identidad <strong>'.$mor_ci.'</strong>, se realiz&oacute; el examen correspondiente a <strong>'.$desc_mot.'</strong>.<br>';	
	
	if ($nom_medico == '')
		$atendio='param&eacute;dico ocupante <strong>'.$nom_paramedico.'</strong>';
	else
		$atendio='m&eacute;dico ocupante <strong>'.$nom_medico.'</strong>';
	
	if ($mor_cond=="APTO")
    $colorcondicion='<font color="green"><strong>'.$mor_cond.'</strong></font>';
  else
    $colorcondicion='<font color="red"><strong>'.$mor_cond.'</strong></font>';
  
  $cuerpo .= 'El cual est&aacute; en condici&oacute;n: '.$colorcondicion.' seg&uacute;n lo considerado en la consulta m&eacute;dica registrada en la fecha: <strong>'.$mor_fecha.'</strong> por el '.$atendio.'.<br>';

	if ($mor_sex=='M')
		$cuerpo .= 'Otros datos de inter&eacute;s sobre el trabajador:<br>';
	else	
		$cuerpo .= 'Otros datos de inter&eacute;s sobre la trabajadora:<br>';
	
	$cuerpo .= '<table><thead><tr><th>Departamento:</th><th>'.$mor_depar.'</th></tr>';
	$cuerpo .= '<tr><th>Cargo:</th><th>'.$mor_cargo.'</th></tr>';
	$cuerpo .= '<tr><th>Supervisor:</th><th>'.$mor_nomjefe.'</th></tr></thead></table>';
	$cuerpo .= '<br>';
	$cuerpo .= '<br>';
	$cuerpo .= 'Para m&aacute;s informaci&oacute;n por favor debe comunicarse con el &aacute;rea de Servicio M&eacute;dico a la Ext. Nro. 259.<p>&nbsp;</p>';

	require_once("planilla_certificado.php");  	
  	require_once("enviodecorreos.php");
  	require_once("funciones_var.php");

  $dpf=construir_pdf($id_consulta, planilla_certificado($id_consulta));
  $resp1=ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.' ,"Certificado Medico ".$nombre_completo, $dpf, $_SESSION['userid'], $desc_mot);

  return $resp1. ' -> ' . $id_consulta;

}
function prueba3 ()
{
	$dirser='http://10.50.188.48/servicio_medico/';
	echo '<TABLE width="100%" border="1" cellspacing="0" cellpadding="0">
	<TR>
	<TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" width="110px" height="70px" src="'.$dirser.'images/MATESI_logo_1.png">
	</TD>
	<TD WIDTH="70%"></TD>
	<TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" width="110px" height="70px" src="'.$dirser.'images/logo_1.png">
	</TD>
	<TD WIDTH="10%"></TD><TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" src="'.$dirser.'images/logo_serv_med_1.jpg">
	</TD>
	
	</TR>
	</TABLE>';
}
/*
//print_r($_SESSION);
if (isset($_SESSION['userid'])){
	//print_r($_REQUEST);
	if ((isset($_REQUEST["id_consulta"])) && (isset($_REQUEST["actividad"]))){
	//	echo "paso";
		$id_consulta = $_REQUEST["id_consulta"];
		$actividad   = $_REQUEST["actividad"];
		if ($actividad=='PREEMPLEO')
			echo prueba2_preempleo($id_consulta);   //SOLO SE USA CUANDO ES PREEMPLEO
	        else
			echo prueba1($id_consulta);   //SE USA PARA TODOS LOS CASOS A EXCEPCION DE PREEMPLEO
	}else{
		if (!isset($_REQUEST["id_consulta"])){
	           echo "Recuerde que a la  url debe agregar la variable 'id_consulta'";
		}else{
			if (!isset($_REQUEST["actividad"])){
				echo "Recuerde que a la  url debe agregar la variable 'actividad' con el valor 'PREEMPLEO' o vacio";
			}
		}
	}
}else{
	echo "Su SESSION caduco, por favor, ingrese nuevamente";
}
*/
//echo prueba1(9213);
//sleep(10);
//echo prueba1(9221);sleep(10);
echo prueba1(10662);sleep(10);


?>
