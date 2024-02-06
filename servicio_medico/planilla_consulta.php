<?php
//============================================================+
// File name   : planilla_consulta.php
// Begin       : 2015-03-05
// Last Update : 2015-03-05
//
// Description : Planilla de Consulta
//               en pdf
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates PDF document using TCPDF gcia
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

//Conexion a la Base de Datos
require("include_conex.php");

$planilla= isset($_GET["uid"])?$_GET["uid"]:"";            //Condicion 

$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

$qmorb = "SELECT ci,uid,supervisor,nombres_jefe,fecha FROM v_morbilidad where uid=" . $planilla;
$resultmorb = pg_query($conexion, $qmorb) or die("Error en la Consulta SQL: ".$qmorb);
$filamorb=pg_fetch_array($resultmorb);
$cdsup=$filamorb['supervisor'];
$nombsup=$filamorb['nombres_jefe'];
$ci=$filamorb['ci'];
$fecha=substr($filamorb['fecha'],0,16);

$Qrysignos=pg_query($conexion,"SELECT cedula, fresp, pulso, temper, tart, fecha, fcard FROM tbl_signos_vitales WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
$Regsignos = pg_fetch_array($Qrysignos, null, PGSQL_ASSOC);

$Qrydatos_ant=pg_query($conexion,"SELECT cedula, talla, peso, imc, fecha FROM tbl_datos_antropometricos WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
$Regdatos_ant = pg_fetch_array($Qrydatos_ant, null, PGSQL_ASSOC);

$fresp=$Regsignos['fresp'];
$pulso=$Regsignos['pulso'];
$temper=$Regsignos['temper'];
$tart=$Regsignos['tart'];
$talla=$Regdatos_ant['talla'];
$peso=$Regdatos_ant['peso'];
$imc=$Regdatos_ant['imc'];
$fcard=$Regsignos['fcard'];

pg_free_result($Qrysignos);
pg_free_result($Qrydatos_ant);
//
$query = "select * from v_consulta where uid=" . $planilla;
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);

$numReg = pg_num_rows($resultado);

$salida="";

if($numReg>0){

	while ($fila=pg_fetch_array($resultado)) {

		//$salida = $salida . '<div style="position:relative"><img align="center" width="110px" src="imagenes/logo.jpg"></div>';
		$salida = $salida . '<TABLE width="93%" border="0" cellspacing="0" cellpadding="0">' ;
		$salida = $salida . '<TR><TD style="vertical-align:middle; text-align:center" WIDTH="20%"><img align="center" width="119px" height="70px" src="images/logo.jpg"></TD><TD WIDTH="90%">' ;
		$salida = $salida . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
		$salida = $salida . '<TR><TD align="center">GERENCIA DE TALENTO HUMANO</TD></TR>' ;
		$salida = $salida . '<TR><TD align="center">Depto. de Higiene y Seguridad Industrial</TD></TR>' ;
		$salida = $salida . '<TR><TD align="center"><h1>Servicio Medico Ocupacional</h1></TD></TR>' ;
		$salida = $salida . '</TABLE></TD></TR>' ;
		$salida = $salida . '</TABLE>' ;
		$salida = $salida . '<TABLE width="93%" BORDER="1">' ;
		
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="15%">FECHA</td><td width="65%">APELLIDO Y NOMBRE DEL SOLICITANTE</td><td width="30%">CEDULA</td></tr>';
		$salida = $salida . '<tr><td  height="30px">' . substr($fila['fecha'],0,10) . '</td><td>' .  $fila['nombre_completo'] . '</td><td>' . $fila["ci"] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="15%">TURNO</td><td width="65%">CARGO</td><td width="30%">FIRMA</td></tr>';
		$salida = $salida . '<tr><td  height="30px">' . $fila['turno'] . '</td><td>' . $fila['cargo'] . '</td><td>' .  "" . '</td></tr>' ;
	
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="35%">GERENCIA</td><td width="45%">DEPARTAMENTO</td><td width="30%">CONTRATISTA</td></tr>';
		$salida = $salida . '<tr><td  height="30px">' .  $fila['gcia'] . '</td><td>' . $fila['departamento'] . '</td><td>' . $fila['contratista'] . '</td></tr>' ;
	
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="55%">APELLIDOS Y NOMBRE DEL SUPERVISOR</td><td width="25%">CEDULA SUPERVISOR</td><td width="30%">CONDICION TRABAJADOR</td></tr>';
		$salida = $salida . '<tr><td  height="30px">' . $nombsup . '</td><td>' . $cdsup . '</td><td>' .  $fila["condicion"] . '</td></tr>' ;
	
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="35%">MOTIVO</td><td width="45%">REFERIDO A</td><td width="30%">REPOSO</td></tr>';
		$salida = $salida . '<tr><td  height="30px">' . $fila['motivo'] . '</td><td>' . $fila['remitido'] . '</td><td>' .  $fila['reposo'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">ENFERMEDAD ACTUAL</td></tr>';
		$salida = $salida . '<tr><td colspan="3"  height="30px">' . $fila['observaciones'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">MOTIVO DE LA CONSULTA</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['sintomas'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">PATOLOGIA</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['patologia'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">DIAGNOSTICO</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['resultado_eva'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">EVALUACION MEDICA</td></tr>';
		
		$evaliacion='';
		if ($fresp!='')
        	$evaliacion.='F. Resp.: '.$fresp.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($pulso!='')
        	$evaliacion.='Pulso: '.$pulso.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($temper!='')
        	$evaliacion.='Temp.: '.$temper.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($talla!='')
        	$evaliacion.='Talla: '.$talla.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($peso!='')
        	$evaliacion.='Peso: '.$peso.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($imc!='')
        	$evaliacion.='IMC: '.$imc.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($tart!='')
        	$evaliacion.='T. Art.: '.$tart.';&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($fcard!='')
        	$evaliacion.='F. Card.: '.$fcard.';&nbsp;&nbsp;&nbsp;&nbsp;';
		
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $evaliacion . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">OBSERVACION</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['observacion_medicamentos'] . '</td></tr>' ;

		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">REFERENCIA MEDICA</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['referencia_medica'] . '</td></tr>' ;

		$query = "select * from v_medicamentos_consulta where id_consulta=" . $planilla;
		$resultado2 = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);		
		$numReg = pg_num_rows($resultado2);	

		if($numReg>0){
		
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">MEDICAMENTOS ADMINISTRADOS</td></tr>';
		
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="35%">MEDICAMENTO</td><td width="45%">UNIDAD/MEDIDA</td><td width="30%">CANTIDAD</td></tr>';

			while ($fila2=pg_fetch_array($resultado2)) {
				$salida = $salida . '<tr><td  height="30px">' . $fila2['descripcion'] . '</td><td>' .  $fila2['unidad_medida'] . '</td><td>' . $fila2['cantidad'] . '</td></tr>' ;

				//$salida = $salida . "<p>DESCRIPCION: " . $fila2["descripcion"] . " U/M: " . $fila2["unidad_medida"] . " CANTIDAD:  " . $fila2["cantidad"] . "</p>";
 			}
			pg_free_result($resultado2);
		
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="3" width="110%">OBSERVACIÓN (MEDICAMENTOS)</td></tr>';
		$salida = $salida . '<tr><td colspan="3" height="30px">' . $fila['observacion_medicamentos'] . '</td></tr>' ;
		 
		}
		
		$salida = $salida . '<tr bgcolor="#F0ECEC"><td colspan="2" width="55%">MÉDICO DE GUARDIA</td><td width="55%">PARAMÉDICO/ENFERMERO(A) DE GUARDIA</TD></tr>';
		
		//$salida = $salida . '<tr bgcolor="#F0ECEC"><td width="30%">APELLIDOS Y NOMBRES</td><td width="25%">FIRMA</td><td width="30%">APELLIDOS Y NOMBRES</td><TD width="25%">FIRMA</TD></tr>';

		$salida = $salida . '<tr><td colspan="2" width="55%">'.$fila['medico'].'</td><td width="55%">'.$fila['paramedico'].'</td></tr>';

		$salida = $salida . '<tr><td colspan="2" width="55%"><font color="#F0ECEC">FIRMA</font></td><TD width="55%"><font color="#F0ECEC">FIRMA</font></TD></tr>';

		//$salida = $salida . '<tr><td  height="30px">' .  $fila['medico'] . '</td><td>' .  '<font color="#F0ECEC">FIRMA</font>' . '</td><td>' .  $fila['paramedico'] . '</td><td>' . '<font color="#F0ECEC">FIRMA</font>' . '</td></tr>' ;

		$salida = $salida . '</TABLE>' ;
				

	}
}
pg_free_result($resultmorb);
pg_free_result($resultado);
pg_close($conexion);


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'images/logo.jpg';
        //$this->Image($image_file, 15, 10, 55, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
        // Title
        
		//$this->Cell(0, 15, 'GERENCIA DE SEGURIDAD INTEGRAL PATRIMONIAL', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Matesi');
$pdf->SetTitle('Planilla de Consulta Médica');
$pdf->SetSubject('');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// set font
$pdf->SetFont('times', 'BI', 10);

// add a page
$pdf->AddPage();

// set some text to print
//$txt = <<<EOD
//PLANILLA DE CONSULTA MEDICA
//EOD;
//echo $salida;

$txt=$salida; 

// print a block of text using Write()
//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);



// output the HTML content
$pdf->writeHTML($txt, true, 0, true, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+