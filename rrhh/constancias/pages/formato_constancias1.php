<?php
include("../BD/conexion.php");
require('funciones_var.php');

$fec= isset($_GET["fec"])?$_GET["fec"]:""; 
$link=Conex_Contancia_pgsql();  
$query="SELECT tbl_constacias.*, to_char(fecha, 'DD') as dia, to_char(fecha, 'MM') as mess, to_char(fecha, 'YYYY') as year, length(fecha_ingreso) as baja  FROM tbl_constacias WHERE fecha='".$fec."'";
$result = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);

$query2="SELECT nombres, cedula , cargo FROM tbl_firma_autorizada WHERE estatus='ACTIVO'";
$result2 = pg_query($link, $query2) or die("Error en la Consulta SQL: ".$query2);
$row2 = pg_fetch_array($result2);
// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'image_demo.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 051');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

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
$i=1;
while ($row = pg_fetch_array($result))
{
// add a page
$pdf->AddPage();

// -- set new background ---

// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
$img_file = 'images/fondo.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();
// ---------------------------------------------------------

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

$pdf->Write(0, $row['destinatario'], '', 0, 'C', true, 0, false, false, 0);
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

$baja= $row['baja']==10?FALSE:TRUE;

if ($baja)
    $presta='Prest&oacute;';
else
    $presta='Presta';

$html = '<span style="text-align:justify;">Por medio de la presente hacemos constar que '.$ciudadano.', <u>'.$row['nombres']."</u>, titular de la cedula de identidad N&#176; <u>".$row['cedula'].'</u>, '.$presta.' sus servicios en esta empresa, ocupando el cargo de <u>'.$row['cargo'].'</u>, desde la fecha '.$row['fecha_ingreso'].'; ';

if ($row['comision']=='t' && $row['ente_adscrito']!='')
  $comision='bajo la figura de Comision de Servicio, siendo su organismo de origen '.$row['ente_adscrito'].', ';


if ($row['tipo']=='Salario Basico' || $row['tipo']=='Basico + Integral')
    if ($row['tipo']=='Salario Basico')
        $sueldo = 'percibiendo un salario b&aacute;sico de '.$row['bsenletras'].' (Bs. '.number_format($row['bsennumero'],2,',','.').') en el mes de '.$row['mes'].', ';
    else
        $sueldo = 'percibiendo un salario b&aacute;sico de '.$row['bsenletras'].' (Bs. '.number_format($row['bsennumero'],2,',','.').') en el mes de '.$row['mes'].' y un salario integral de '.$row['bsintenletras'].' (Bs. '.number_format($row['bsintennumeros'],2,',','.').'). ';

if ($row['tipo']=='Salario Integral')
    $sueldo = 'percibiendo un salario integral de '.$row['bsintenletras'].' (Bs. '.number_format($row['bsintennumeros'],2,',','.').') en el mes de '.$row['mes'].'. ';

if ($baja){
    $html .= $comision.$sueldo;
    if ($sueldo!='')
        $html .= 'La cual fue cancelada por Matesi, Materiales Siderúrgicos, S.A.</span>';
    else
        $html .= '</span>';
}
else
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

$firma = str_replace(".", "", $row2['cedula']);

$html='<table style="text-align:center;"><tr><td><IMG SRC="images/'.$firma.'.jpg" width="250px" height="100px"></td></tr>';
$html.='<tr><td>_______________________________</td></tr>';
$html.='<tr><td>'.$row2['nombres'].'</td></tr>';
$html.='<tr><td>'.$row2['cargo'].'</td></tr></table>';

$pdf->writeHTML($html, true, 0, true, true);

// reset pointer to the last page
$pdf->lastPage();
$i++;
}
pg_free_result($result);
pg_free_result($result2);
pg_close($link);
//Close and output PDF document

$pdf->Output('constancia_'.$fec.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+