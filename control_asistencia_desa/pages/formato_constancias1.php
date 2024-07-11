<?php
include("../BD/conexion.php");
require_once('tcpdf/tcpdf.php');
require('funciones_var.php');

$fec= isset($_GET["fec"])?$_GET["fec"]:""; 
$link=Conex_Contancia_pgsql();	
$query="SELECT tbl_constacias.*, to_char(fecha, 'DD') as dia, to_char(fecha, 'MM') as mess, to_char(fecha, 'YYYY') as year  FROM tbl_constacias WHERE fecha='".$fec."'";
$result = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);


/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML justification
 * @author roberto
 * @since 2019-02-20
 */

// Include the main TCPDF library (search for installation path).

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Roberto Lunar');
$pdf->SetSubject('MATESI');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

/// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->setCellHeightRatio(1.6);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetLeftMargin(25);
$pdf->SetRightMargin(25);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
    require_once(dirname(__FILE__).'/lang/spa.php');
    $pdf->setLanguageArray($l);
}
$i=1;
// ---------------------------------------------------------
while ($row = pg_fetch_array($result))
{// add a page
$pdf->AddPage();

// set font
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$fecha=$row['dia'].' de '.mes_espanol($row['mess']).' '.$row['year'];

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Write(0, 'Puerto Ordaz, '. $fecha, '', 0, 'R', true, 0, false, false, 0);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->Write(0, 'A QUIEN PUEDA INTERESAR', '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

// create some HTML content
if ($row['sexo']=='M')
  	$ciudadano='el ciudadano';
else
	$ciudadano='la ciudadana';
$sueldo='';
$comision='';

$html = '<span style="text-align:justify;">Por medio de la presente hacemos constar que '.$ciudadano.', <u>'.$row['nombres']."</u>, titular de la cedula de identidad N&#176; <u>".$row['cedula'].'</u>, Presta sus servicios en esta empresa, ocupando el cargo de <u>'.$row['cargo'].'</u>, desde la fecha '.$row['fecha_ingreso'].', ';

if ($row['comision']=='t' && $row['ente_adscrito']!='')
  $comision='bajo la figura de Comision de Servicio, siendo su organismo de origen '.$row['ente_adscrito'].', ';


if ($row['tipo']=='Salario Basico' || $row['tipo']=='Basico + Integral')
	if ($row['tipo']=='Salario Basico')
		$sueldo = 'percibiendo un salario b&aacute;sico de '.$row['bsenletras'].' (Bs. '.number_format($row['bsennumero'],2,',','.').') en el mes de '.$row['mes'].', ';
	else
		$sueldo = 'percibiendo un salario b&aacute;sico de '.$row['bsenletras'].' (Bs. '.number_format($row['bsennumero'],2,',','.').') en el mes de '.$row['mes'].' y un salario integral de '.$row['bsintenletras'].' (Bs. '.number_format($row['bsintennumeros'],2,',','.').'). ';

if ($row['tipo']=='Salario Integral')
	$sueldo = 'percibiendo un salario integral de '.$row['bsintenletras'].' (Bs. '.number_format($row['bsintennumeros'],2,',','.').') en el mes de '.$row['mes'].'. ';

$html .= $comision.$sueldo.'En el entendido que el salario integral varia mes a mes segun el regimen de trabajo e incorpora la cuota parte de utilidades y bono vacacional. La cual es cancelada por Matesi, Materiales Siderúrgicos, S.A.</span>';

// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='<span style="text-align:justify;">Constancia que emite la empresa a solicitud de la parte interesada.</span>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='<span style="text-align:justify;">Por  MATESI, MATERIALES SIDERURGICOS, S.A.</span>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->setJPEGQuality(75);

$html='<table style="text-align:center;"><tr><td><IMG SRC="images/firma.jpg" width="250px" height="100px"></td></tr>';
$html.='<tr><td>_______________________________</td></tr>';
$html.='<tr><td>ERNESTO DE LEON</td></tr>';
$html.='<tr><td>GERENTE DE TALENTO HUMANO</td></tr></table>';

$pdf->writeHTML($html, true, 0, true, true);

// reset pointer to the last page
$pdf->lastPage();
$i++;
}
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('constancia_'.$fec.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+	
?>
