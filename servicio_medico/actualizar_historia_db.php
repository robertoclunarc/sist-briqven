<?PHP
require("include_conex.php");
session_start();
$idhistoria = isset($_POST["txtIdh"])?$_POST["txtIdh"]:"-1";    //
$paciente = isset($_POST["txtId"])?$_POST["txtId"]:"-1";    //
$cedula = isset($_POST["ci"])?$_POST["ci"]:"-1";    //
$medico= isset($_POST["idmedico"])?$_POST["idmedico"]:"NULL";   //

$ha_sufrido_accidentes= isset($_POST["cboha_sufrido_accidentes"])?$_POST["cboha_sufrido_accidentes"]:"";   // 
$partes_cuerpo_lesionados= isset($_POST["txtpartes_cuerpo_lesionados"])?$_POST["txtpartes_cuerpo_lesionados"]:"";   // 
$fecha_accidente= isset($_POST["txtfecha_accidente"])?$_POST["txtfecha_accidente"]:"";   // ";   // 
$dejo_secuelas= isset($_POST["txtdejo_secuelas"])?$_POST["txtdejo_secuelas"]:"";   // 
$ha_padecido_enfermeda= isset($_POST["cboha_padecido_enfermeda"])?$_POST["cboha_padecido_enfermeda"]:"";   // 
$fue_certif_inpsasel= isset($_POST["cbofue_certif_inpsasel"])?$_POST["cbofue_certif_inpsasel"]:"";   // 
$cambia_trab_frecuente= isset($_POST["cbocambia_trab_frecuente"])?$_POST["cbocambia_trab_frecuente"]:"";   // 

$fresp= isset($_POST["txtfresp"])?$_POST["txtfresp"]:"";
$pulso= isset($_POST["txtpulso"])?$_POST["txtpulso"]:"";
$temper= isset($_POST["txttemper"])?$_POST["txttemper"]:"";
$tart= isset($_POST["txttart"])?$_POST["txttart"]:"";
$fcard= isset($_POST["txtfcard"])?$_POST["txtfcard"]:"";

$talla= isset($_POST["txttalla"])?$_POST["txttalla"]:"";
$peso= isset($_POST["txtpeso"])?$_POST["txtpeso"]:"";
$imc= isset($_POST["txtimc"])?$_POST["txtimc"]:"";

$motivo_historia= isset($_POST["motivo_historia"])?$_POST["motivo_historia"]:"";   // 
$observacion_historia= isset($_POST["observacion_historia"])?$_POST["observacion_historia"]:"";   // 

$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

$queryhoy="SELECT to_char(now(), 'YYYY-MM-DD HH:MI:SS') as hoy;";
$resultadohoy = pg_query($conexion, $queryhoy) or die("Error en la Consulta SQL: " . $queryhoy);
$rowhoy = pg_fetch_array($resultadohoy);
$fecha_act = $rowhoy['hoy'];
pg_free_result($resultadohoy);

if ($fecha_accidente=="")
	$fecha_accidente="NULL";
else
	$fecha_accidente="'" . $fecha_accidente . "'";

$queryUpdateHist = "UPDATE tbl_historia_medica SET 
             ha_sufrido_accidentes='".$ha_sufrido_accidentes."', partes_cuerpo_lesionados='".$partes_cuerpo_lesionados."', fecha_accidente=".$fecha_accidente.", dejo_secuelas='".$dejo_secuelas."', ha_padecido_enfermeda='".$ha_padecido_enfermeda."', cambia_trab_frecuente='".$cambia_trab_frecuente."', fue_certif_inpsasel='".$fue_certif_inpsasel."', fecha_ultima_actualizacion='".$fecha_act."' WHERE uid_historia = ".$idhistoria;

$resultado = pg_query($conexion, $queryUpdateHist) or die("Error en la Consulta SQL: " . $queryUpdateHist);



/*$queryUpdateHistpac = "INSERT INTO tbl_historia_paciente(fk_historia, fecha_historia, indice, motivo_historia, fk_medico, observacion) VALUES (";
$queryUpdateHistpac=$queryUpdateHistpac.$idhistoria.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$fecha_act."', ";
$queryUpdateHistpac=$queryUpdateHistpac."(select max(indice) + 1 from tbl_historia_paciente where fk_historia=".$idhistoria."), ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$motivo_historia."', ";
$queryUpdateHistpac=$queryUpdateHistpac.$medico.", ";
$queryUpdateHistpac=$queryUpdateHistpac."'".$observacion_historia."');";

$resultado = pg_query($conexion, $queryUpdateHistpac) or die("Error en la Consulta SQL: " . $queryUpdateHistpac);
*/

$i=0;
//  insertar en tabla de cargos anteriores
if(array_key_exists('cargos',$_POST))
{
	$cargos = $_POST['cargos'];
	$actividad = $_POST['actividad'];
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	$riesgos = $_POST['riesgos'];	
	
	//die();
	
	foreach ($cargos as &$cargo)
	{
		
		$queryCargosAnt = "INSERT INTO tbl_cargos_anteriores(
            fk_paciente, cargo, actividad_laboral, desde, hasta, riesgos)
    VALUES (";
		$queryCargosAnt = $queryCargosAnt.$paciente.", '".$cargo."', '".$actividad[$i]."', '".$desde[$i]."', '".$hasta[$i]."', '".$riesgos[$i]."');";
	
		//echo($query);
		$resultado = pg_query($conexion, $queryCargosAnt) or die("Error en la Consulta SQL: ".$queryCargosAnt);	
		$i++;
	}
}

$i=0;
//  insertar en tabla de antecedentes familiares
if(array_key_exists('antecedentes',$_POST))
{
	$antecedentes = $_POST['antecedentes'];
	$familia = $_POST['familia'];
	$estatusfamilia = $_POST['estatusfamilia'];
	foreach ($antecedentes as &$antecedente)
	{
		
		$queryAntecFam = "INSERT INTO tbl_antecedentes_famil(fk_paciente, fk_patologia, paterentezco, estatus_familiar) VALUES (";
		$queryAntecFam = $queryAntecFam.$paciente.", ".$antecedente.", '".$familia[$i]."', '".$estatusfamilia[$i]."');";
	
		//echo($query);
		$resultado = pg_query($conexion, $queryAntecFam) or die("Error en la Consulta SQL: ".$queryAntecFam);	
		$i++;
	}
}

$i=0;
//  insertar en tabla de antecedentes ocupacionales
if(array_key_exists('distagente',$_POST))
{
	$distagentes = $_POST['distagente'];
	$descagente = $_POST['descagente'];
	$tiempoexp = $_POST['tiempoexp'];
	
	foreach ($distagentes as &$distagente)
	{
		
		$queryAntecOcup = "INSERT INTO tbl_riesgos_historia(cedula, fk_riesgo, tiempo_exposicion) VALUES ('";
		$queryAntecOcup = $queryAntecOcup.$cedula."', ".$descagente[$i].", '".$tiempoexp[$i]."');";
	
		//echo($query);
		$resultado = pg_query($conexion, $queryAntecOcup) or die("Error en la Consulta SQL: ".$queryAntecOcup);	
		$i++;
	}
}

$i=0;
//  insertar en tabla de examen funcional
if(array_key_exists('tipoanat',$_POST))
{
	$tipoanat = $_POST['tipoanat'];
	$uidanatom = $_POST['uidanatom'];
	$observacionpatog = $_POST['observacionpatog'];
	
	foreach ($tipoanat as &$tipoanats)
	{
		
		$queryExamFunc = "INSERT INTO tbl_examen_funcional(cedula, fk_anatomia, observacion) VALUES ('";
		$queryExamFunc = $queryExamFunc.$cedula."', ".$uidanatom[$i].", '".$observacionpatog[$i]."');";
	
		//echo($query);
		$resultado = pg_query($conexion, $queryExamFunc) or die("Error en la Consulta SQL: ".$queryExamFunc);	
		$i++;
	}
}

$i=0;
//  insertar en tabla de habitos del paciente
if(array_key_exists('txtfk_habito',$_POST))
{
	$txtfk_habito = $_POST['txtfk_habito'];
	$txtresp = $_POST['txtresp'];
	$txtobservacionhabitos = $_POST['txtobservacionhabitos'];
	
	foreach ($txtfk_habito as &$txtfk_habitos)
	{
		$queryhabitos = "UPDATE tbl_habitos_pacientes SET resp='".$txtresp[$i]."', observacion='".$txtobservacionhabitos[$i]."' WHERE cedula='".$cedula."' AND fk_habito=".$txtfk_habitos.";";	
		//echo($query);
		$resultado = pg_query($conexion, $queryhabitos) or die("Error en la Consulta SQL: ".$queryhabitos);	
		$i++;
	}
}

$i=0;
//  insertar en tabla de analisis psicologicos
if(array_key_exists('txtuid_estudio',$_POST))
{
	$txtuid_estudio = $_POST['txtuid_estudio'];	
	$txtobservacionestudio = $_POST['txtobservacionestudio'];
	
	foreach ($txtuid_estudio as &$txtuid_estudios)
	{
		$querypsicologico = "UPDATE tbl_analisis_psicologicos SET observacion='".$txtobservacionestudio[$i]."' WHERE cedula='".$cedula."' AND fk_estudio=".$txtuid_estudios.";";		
	
		//echo($query);
		$resultado = pg_query($conexion, $querypsicologico) or die("Error en la Consulta SQL: ".$querypsicologico);	
		$i++;
	}
}

//Inserta signos vitales
if (($fresp!="") || ($fcard!="") || ($fresp!="") || ($pulso!="") || ($pulso!="") || ($tart!="")){
$querySigvitales = "INSERT INTO tbl_signos_vitales(cedula, fresp, pulso, temper, tart, fecha, fcard) VALUES ('";
$querySigvitales = $querySigvitales.$cedula."', '".$fresp."', '".$pulso."', '".$temper."', '".$tart."', '".$fecha_act."', '".$fcard."') ;";

$resultado = pg_query($conexion, $querySigvitales) or die("Error en la Consulta SQL: " . $querySigvitales);
}
//Inserta datos antopometricos
if (($talla!="") || ($peso!="") || ($imc!="")){
$queryDatosAntop = "INSERT INTO tbl_datos_antropometricos(cedula, talla, peso, imc, fecha) VALUES ('";
$queryDatosAntop = $queryDatosAntop.$cedula."', '".$talla."', '".$peso."', '".$imc."', '".$fecha_act."');";

$resultado = pg_query($conexion, $queryDatosAntop) or die("Error en la Consulta SQL: " . $queryDatosAntop);
}

$i=0;
//  insertar en tabla de examen fisico
if(array_key_exists('txtuid_est_fisico',$_POST))
{
	$txtuid_est_fisico = $_POST['txtuid_est_fisico'];	
	$txtobservacionestudiofis = $_POST['txtobservacionestudiofis'];
	
	foreach ($txtuid_est_fisico as &$txtuid_est_fisicos)
	{
		if ($txtobservacionestudiofis[$i]!=''){
			$queryEstFisico = "INSERT INTO tbl_examen_fisico(cedula, fk_fisico, observacion, fecha_examen) VALUES ('";
			$queryEstFisico = $queryEstFisico.$cedula."', ".$txtuid_est_fisicos.", '".$txtobservacionestudiofis[$i]."', '".$fecha_act."');";
	
			//echo($query);
			$resultado = pg_query($conexion, $queryEstFisico) or die("Error en la Consulta SQL: ".$queryEstFisico);
		}
		$i++;	
	}
}


pg_close($conexion);

echo ("0"); //OK

?>
