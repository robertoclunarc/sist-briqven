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
 * Creates PDF document using TCPDF
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('formato_historia_html.php');

//Conexion a la Base de Datos
//require("include_conex.php");

$idh= isset($_GET["idh"])?$_GET["idh"]:"";            //Condicion 
$ci= isset($_GET["ci"])?$_GET["ci"]:"";
$idp= isset($_GET["idp"])?$_GET["idp"]:"";
/*
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
$query = "select uid_historia,ci,uid_paciente from v_historias where uid=" . $planilla;
$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
$numReg = pg_num_rows($resultado);
if($numReg>0){
	while ($fila=pg_fetch_array($resultado)) {
*/
		

//	}
//}
//pg_close($conexion);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
       // $image_file = 'images/logo.jpg';
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
$pdf->SetTitle('Planilla de Historia Médica');
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
$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();

// set some text to print
//$txt = <<<EOD
//PLANILLA DE CONSULTA MEDICA
//EOD;
//echo $salida;

$txt=formato_historia_htlm($idh,$ci,$idp);

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