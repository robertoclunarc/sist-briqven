<?PHP
require_once('tcpdf/tcpdf.php');
require_once('formato_entrada_salida_1.php');
$idmov=isset($_GET["idm"])?$_GET["idm"]:"NULL";

//require_once('enviodecorreos.php');
class MYPDF extends TCPDF {
      //Page header
      public function Header() {
          // Logo
         // $image_file = 'images/MATESI_logo.jpg';
        //  $this->Image($image_file, 10, 10, 20, 10, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);          
          // Set font
          $this->SetFont('courier', 'B', 8);
          // Title          
    //  $this->Cell(0, 10, 'COMPROBANTE DE PAGO', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    //  $this->Cell(0, 10, '(Confidencial)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    //  $this->SetXY(20, 22);
    /// $this->Cell(10, 10, 'Rif.: G-200113111', 0, false, 'C', 0, '', 0, false, 'M', 'M');      
      }
      // Page footer
      public function Footer() {
          // Position at 15 mm from bottom
          $this->SetY(-15);
          // Set font
          $this->SetFont('courier', 'I', 8);
          // Page number
          $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
      }
  }

  function crear_pdf (){
    // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Matesi');
  $pdf->SetTitle('Control de Acceso');
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
  //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  // set image scale factor
  $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 255)));

  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  // set some language-dependent strings (optional)
  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
  }
  //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetMargins(8, 8, 8, true);
  // set font
  $pdf->SetFont('courier', 'I', 7);

  return $pdf;
  }
/////////////////////////////////////////////////////////////
// create new PDF document  
$pdf = crear_pdf ();  
/////////////////////////////////////////////////////////////    	
//$pdf->SetProtection(array('modify','copy'), 12345, null, 0, null);  
////////////////////////////////////////////////////////////////////////////
//$salida = $salida . "<!-- NEW PAGE -->";	  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////CONVERTIR A PDF////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  // add a page
		  $pdf->AddPage();
		  // set some text to print
      $txt=utf8_encode(planilla_entrada_salida_html($idmov));      
      
		  // print a block of text using Write()
		  //$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
		  // output the HTML content
		  //$pdf->writeHTML($txt, true, 0, true, 0);
		  $pdf->writeHTML($txt, false, 0, true, true, '');
      
		  // --------------------------------------------------------- 
 //$pdf->Output();
  $pdf->Output('Control_Salida.pdf', 'I');
?>