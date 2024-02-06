<?php
require("include_conex.php");
require_once('tcpdf/tcpdf.php');

$id_consulta= isset($_GET["idconsulta"])?$_GET["idconsulta"]:"NULL"; 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());	
$query="SELECT uid, fecha,  ci, nombre_completo, departamento,  cargo FROM v_consulta WHERE uid=".$id_consulta;
$resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
$rowmorb = pg_fetch_array($resultmorb);
$fecha=substr($rowmorb['fecha'],0,16);

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML justification
 * @author Nicola Asuni
 * @since 2008-10-18
 */

// Include the main TCPDF library (search for installation path).

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Roberto Lunar');
$pdf->SetSubject('MATESI');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 039', PDF_HEADER_STRING);

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
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
    require_once(dirname(__FILE__).'/lang/spa.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Write(0, 'Puerto Ordaz, '. $fecha, '', 0, 'R', true, 0, false, false, 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Write(0, 'AUTORIZACIÓN', '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln();
$pdf->Ln();
// create some HTML content
$html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;Quien suscribe, <u>'.$rowmorb['nombre_completo']."</u>, venezolano (a), mayor de edad, civilmente h&aacute;bil, titular de la C&eacute;dula de Identidad N&#176; <u>".$rowmorb['ci'].'</u>, adscrito (a) a la Gerencia y/o Departamento de <u>'.$rowmorb['departamento'].'</u>, desempe&ntilde;ando actualmente el Cargo de <u>'.$rowmorb['cargo'].'</u>, y trabajador (a) de la Sociedad Mercantil MATESI, MATERIALES SIDER&Uacute;RGICOS, S.A., mediante el presente documento declaro que, de conformidad con lo previsto en el primer aparte del art&iacute;culo 27 del Reglamento Parcial de la Ley Org&aacute;nica de Prevenci&oacute;n, Condiciones y Medio Ambiente de Trabajo, <u><b>AUTORIZO DE MANERA VOLUNTARIA Y SUFICIENTE</b></u> al Departamento de Higiene, Seguridad y Salud Laboral, para que de forma excepcional tenga acceso a la informaci&oacute;n que reposa en m&iacute; expediente m&eacute;dico, as&iacute; como a los diagn&oacute;sticos y/o patolog&iacute;as que se deriven de las consultas o informes m&eacute;dicos realizados por ante el Servicio M&eacute;dico de la empresa, todo ello con el prop&oacute;sito de que la referida informaci&oacute;n sea utilizada exclusivamente y de manera restringida para fines administrativos internos de la empresa.</span>';

// set core font
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;Asimismo, dejo expresa constancia de que previamente fui debidamente notificado (a) por parte del Departamento de Higiene, Seguridad Industrial y Salud Laboral, sobre el alcance de la presente autorizaci&oacute;n y del uso reservado y restringido que se le dar&aacute; a la informaci&oacute;n contenida en m&iacute; expediente m&eacute;dico.</span>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;Finalmente, es de se&ntilde;alar que eximo a la empresa MATESI, MATERIALES SIDERURGICOS, S.A., as&iacute; como al M&eacute;dico que emita dichos informes, de toda responsabilidad legal que pudiese generarse de forma eventual por el manejo inadecuado de la informaci&oacute;n que se encuentra archivada en m&iacute; expediente m&eacute;dico laboral.</span>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='Sin otro particular al cual hacer referencia, se despide de Usted;';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

$html='Atentamente,';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$html='__________________________________';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

$html='Firma del Trabajador(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Huella dactilar';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

$html='Apellidos y Nombres: _________________';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

$html='C.I. _________________';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_039.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+	
?>
