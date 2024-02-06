<?php
//============================================================+
// File name   : planilla_referencia.php
// Begin       : 2017-09-05
// Last Update : 2017-09-05
//
// Description : Planilla de referencia
//               en pdf
//
// Author: roberto lunar
//

//============================================================+

/**
 * Creates PDF document using TCPDF
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

//Conexion a la Base de Datos
require("include_conex.php");

$planilla= isset($_GET["uid"])?$_GET["uid"]:"";            //Condicion 

$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

$query = "select * from v_consulta where uid=" . $planilla;

//echo $query;


$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

$numReg = pg_num_rows($resultado);

$salida="";

if($numReg>0){

	while ($fila=pg_fetch_array($resultado)) {
		$medico=$fila["medico"];
		$ci_medico=$fila["ci_medico"];
		$id_ss=$fila["id_ss"];
		$fecha=substr($fila['fecha'],0,10);
		$nombre_completo=$fila['nombre_completo'];
		$ci=$fila["ci"];
		$referencia_medica=$fila["referencia_medica"];
	}
}

$arr1 = array();
$arr1 = explode(">>", $referencia_medica);
$cant = count($arr1);
$ref = array();
$esp = array();
for ($i=1; $i <= $cant-1; $i++){
	$ref = explode(":", $arr1[$i]);	
	$cantr = count($ref);
	$esp[$i] = $ref[0];
	if ($cantr > 2)
		for ($j=1; $j < $cantr; $j++)
			$desespc[$i] = $desespc[$i]." ".$ref[$j];
	else
		$desespc[$i] = $ref[1];	

}

for ($i=1; $i <= $cant-1; $i++){
	    $salida="";
		$salida = $salida . '<table width="45%" border="0" cellspacing="0" cellpadding="0">' ;
		$salida = $salida . '<tr>' ;
		$salida = $salida . '<th width="20%" rowspan="2"><img align="center" width="100px" height="51px" src="images/logo.jpg"></th>' ;
		$salida = $salida . '<th style="vertical-align:middle; text-align:center" width="80%">GERENCIA DE TALENTO HUMANO</th>' ;
		$salida = $salida . '</tr>' ;
		$salida = $salida . '<tr>' ;
		$salida = $salida . '<td style="vertical-align:middle; text-align:center" width="80%">Referencia Medica</td>' ;
		$salida = $salida . '</tr>' ;
		$salida = $salida . '</table>' ;

		$salida = $salida . '<table width="45%" border="0" cellspacing="0" cellpadding="0">' ;
		$salida = $salida . '<tr>' ;
		$salida = $salida . '<th>Medico:</th>' ;
		$salida = $salida . '<th>'.$medico.'</th>' ;
		$salida = $salida . '<th>Cedula:</th>' ;
		$salida = $salida . '<th>'.$ci_medico.'</th>' ;
		$salida = $salida . '</tr>';
		$salida = $salida . '<tr>';
		$salida = $salida . '<td>M.P.P.S.:</td>' ;
		$salida = $salida . '<td>'.$id_ss.'</td>' ;
		$salida = $salida . '<td>Fecha Emision:</td>' ;
		$salida = $salida . '<td>'.$fecha.'</td>' ;
		$salida = $salida . '</tr>' ;
		$salida = $salida . '<tr>' ;
		$salida = $salida . '<td>Paciente:</td>' ;
		$salida = $salida . '<td>'.$nombre_completo.'</td>' ;
		$salida = $salida . '<td>Cedula:</td>' ;
		$salida = $salida . '<td>'.$ci.'</td>' ;
		$salida = $salida . '</tr>' ;
		$salida = $salida . '</table>' ;
		$salida = $salida . '<p>&nbsp;</p>' ;		
		

		$salida = $salida . '<table width="45%" border="0" cellspacing="0" cellpadding="0">';			

			$salida = $salida . '<tr>' ;
			$salida = $salida . '<td><u>Informe Medico:</u></td>' ;	
			$salida = $salida . '</tr>' ;	
			$salida = $salida . '<tr>' ;	
			$salida = $salida . '<td>Paciente es referido a la especialidad: <ins>'.$esp[$i].'</ins><br>'.$desespc[$i].'</td>' ;	
			$salida = $salida . '</tr>';
				
		$salida = $salida . '</table>' ;

		$html[$i]=$salida;			
}	


pg_close($conexion);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'images/logo.jpg';
        //$this->Image($image_file, 15, 10, 55, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
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
        $this->Cell(0, 10, 'Firma/sello:___________________________ ', 0, false, 'L', 0, '', 0, false, 'T', 'M');        
        
        
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Matesi');
$pdf->SetTitle('Referencia');
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
$pdf->SetFont('times', 'BI', 9);

// add a page
for ($i=1; $i <= $cant-1; $i++){
	$pdf->AddPage('L', 'LETTER');
	// set some text to print
	//$txt = <<<EOD
	//PLANILLA DE CONSULTA MEDICA
	//EOD;
	

	$txt=$html[$i]; 

	// print a block of text using Write()
	//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

	// output the HTML content
	$pdf->writeHTML($txt, true, 0, true, 0);

}


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_005.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

function formatear_indicaciones($indicacion){
	$arr1 = explode(": ",$indicacion);
	$cont=count($arr1);

}