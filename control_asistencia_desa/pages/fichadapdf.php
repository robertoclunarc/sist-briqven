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
session_start();
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

//Conexion a la Base de Datos
//require("include_conex.php");
include("../BD/conexion.php");
require_once('funciones_var.php');
// RECIBIMOS LOS DATOS ENVIADOS 
//$cedulas              = $_POST['cedulas'];        
//$nombres              = $_POST['nombres'];
$desde    		= $_GET['d'];
$hasta     		= $_GET['h'];
$trabajador            	= $_GET['t'];
$asistio          	= $_GET['a'];
$permiso          	= $_GET['p'];
$comision             	= $_GET['c'];
$sobretiempo          	= $_GET['s'];
$cambioturno            = $_GET['ct'];

$link=Conex_Contancia_pgsql();


    $desde_1    = strtotime($desde);
    $hasta_1    = strtotime($hasta);
    $titulo	="";
    if(($desde_1 > $hasta_1)) echo "La fecha Desde ".$desde." es mayor que la fecha hasta ".$hasta.", se obviara este filtro";
    $complemento="";
    if(($desde_1 < $hasta_1) || ($desde_1 == $hasta_1)){ $complemento.= "and fecha between '".$desde."' and '".$hasta."'"; $titulo="desde: ".$desde.", hasta: ".$hasta; }
    if ($trabajador!="" && $trabajador>0){ $complemento.=" and a.trabajador='". $trabajador."'";  $titulo="del trabajador: ".$trabajador; }
    if ($asistio=="true"){                 $complemento.=" and asistio='S'"; if ($titulo=="") $titulo= "del personal que asistio entre el ".$desde.", hasta=".$hasta; }
    if ($comision=="true"){                $complemento.=" and comision='S'";}
    if ($sobretiempo=="true"){             $complemento.=" and sobre_tiempo='S'";}
    if ($cambioturno=="true"){             $complemento.=" and cambio_turno='S'";}
    if ($permiso>1){                       $complemento.=" and inasistencia=".$permiso;}
    if ($_SESSION['nivel_const']==3){ $complemento.=" and supervisor='".$_SESSION['cedula_session_const']."'"; $titulo="de la oficina: ".nombre_gerencia($_SESSION['cedula_session_const']);}

    $query="SELECT a.*,b.nombres,b.apellidos FROM registro_diario a, trabajadores_activos_con_jefes_1 b where a.trabajador=b.trabajador ".$complemento." ORDER BY fecha DESC";
//print $query;  
//  $link=Conex_Contancia_pgsql();
    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
    $numReg = ejecutar_num_rows($result);
    $i=0; $tabla="";
    if($numReg>0){
    	$tabla = $tabla . '<TABLE width="80%" border="0" cellspacing="0" cellpadding="0">' ;
    	$tabla = $tabla . '<TR><TD style="vertical-align:middle; text-align:center" WIDTH="20%"><img align="center" width="119px" height="70px" src="images/logo.jpg"></TD><TD WIDTH="90%">' ;
 	$tabla = $tabla . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
    	$tabla = $tabla . '<TR><TD align="center">GERENCIA DE TALENTO HUMANO</TD></TR>' ;
//    $tabla = $tabla . '<TR><TD align="center">Depto. de Higiene y Seguridad Industrial</TD></TR>' ;
	 $tabla = $tabla . '<TR><TD align="center"><h1>Listado de Control de Asistencia</h1></TD></TR>' ;
	 if ($titulo!="") $tabla = $tabla . '<TR><TD align="center">'.$titulo.'</TD></TR>' ;
         $tabla = $tabla . '</TABLE></TD></TR></TABLE><p>&nbsp;</p>';

          $tabla.='<table width="100%" class="table table-striped table-bordered table-hover" border="1">';
          $tabla.='<thead>';
          $tabla.='<tr bgcolor="#F0ECEC">';
          $tabla.='<th width="20px"></th>';
          $tabla.='<th width="60px">Fecha</th>';
          $tabla.='<th width="60px">C&eacute;dula</th>';
          $tabla.='<th width="250px">Nombre</th>';
          $tabla.='<th width="100px">Asisti&oacute;</th>';
          $tabla.='<th width="100px">Sobre Tiempo</th>';
          $tabla.='<th width="100px">Comisi&oacute;n</th>';
          $tabla.='<th width="100px">Cambio Turno</th>';
          $tabla.='<th width="100px">Permiso</th>';
          $tabla.='<th width="100px">Observaci&oacute;n</th>';
          $tabla.='</tr>';
          $tabla.='</thead>';

//          $tabla.='<tbody>';
          $i++; $conta=0;$contc=0;$contsb=0;$contct=0;
          while ($reg = ejecutar_fetch_array($result)) {
                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td width="20px" align="center">'.$i.'</td>';
                  $tabla.='<td width="60px" align="center">'.$reg['fecha'].'</td>';
                  $tabla.='<td width="60px" align="center">'.$reg['trabajador'].'</td>';
                  $tabla.='<td width="250px">'.$reg['nombres'].' '.$reg['apellidos'].'</td>';
                  $tabla.='<td width="100px" align="center">'.cambiar_S_X($reg['asistio']).'</td>';
                  $tabla.='<td width="100px" align="center">'.cambiar_S_X($reg['sobre_tiempo']).'</td>';
                  $tabla.='<td width="100px" align="center">'.cambiar_S_X($reg['comision']).'</td>';
                  $tabla.='<td width="100px" align="center">'.cambiar_S_X($reg['cambio_turno']).'</td>';
                  $tabla.='<td width="100px">'.nombre_inasistencia($reg['inasistencia']).'</td>';
		  if ($reg['cambio_turno']=="S") 
	                  $tabla.='<td width="100px">Vino en el turno: '.$reg['turno'].'<br>'.$reg['observacion'].'</td>';
		  else
	                  $tabla.='<td width="100px">'.$reg['observacion'].'</td>';
                  $tabla.='</tr>';
                  $i++;
                  if ($reg['asistio']=='S') $conta++;
                  if ($reg['sobre_tiempo']=="S") $contsb++;
                  if ($reg['comision']=="S") $contc++;
                  if ($reg['cambio_turno']=="S") $contct++;
            }
            //$tabla.='</tbody>';
            if ($trabajador>0){
             //   $tabla.='<thead>';
                $tabla.='<tr>';
                $tabla.='<td width="390px"><b>Total </b></td>';
                $tabla.='<td align="center" width="100px"><b>'.$conta.'</b></td>';
                $tabla.='<td align="center" width="100px"><b>'.$contsb.'</b></td> ';
                $tabla.='<td align="center" width="100px"><b>'.$contc.'</b></td>';
                $tabla.='<td align="center" width="100px"><b>'.$contct.'</b></td> ';
                $tabla.='<td>&nbsp;</th>';
                $tabla.='<td>&nbsp;</th> ';
                $tabla.='</tr>';
             //   $tabla.='</thead>';
            }
  //          $tabla.='</tbody>';
          $tabla.='</table>';
	    ejecutar_free_result($result);
            ejecutar_close($link);
   
  //echo $tabla;
    }else{
  $tabla = $tabla . '';
        $tabla = $tabla . '<TABLE width="80%" border="0" cellspacing="0" cellpadding="0">' ;
        $tabla = $tabla . '<TR><TD style="vertical-align:middle; text-align:center" WIDTH="20%"><img align="center" width="119px" height="70px" src="images/logo.jpg"></TD><TD WIDTH="90%">' ;
        $tabla = $tabla . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
        $tabla = $tabla . '<TR><TD align="center">GERENCIA DE TALENTO HUMANO</TD></TR>' ;
//    $tabla = $tabla . '<TR><TD align="center">Depto. de Higiene y Seguridad Industrial</TD></TR>' ;
         $tabla = $tabla . '<TR><TD align="center"><h1>Listado de Control de Asistencia</h1></TD></TR>' ;
         if ($titulo!="") $tabla = $tabla . '<TR><TD align="center">'.$titulo.'</TD></TR>' ;
         $tabla = $tabla . '</TABLE></TD></TR></TABLE><p>&nbsp;</p>';

          $tabla.='<table width="100%" class="table table-striped table-bordered table-hover" border="1">';
          $tabla.='<thead>';
          $tabla.='<tr bgcolor="#F0ECEC">';
          $tabla.='<th width="20px"></th>';
          $tabla.='<th width="60px">Fecha</th>';
          $tabla.='<th width="60px">C&eacute;dula</th>';
          $tabla.='<th width="250px">Nombre</th>';
          $tabla.='<th width="100px">Asisti&oacute;</th>';
          $tabla.='<th width="100px">Sobre Tiempo</th>';
          $tabla.='<th width="100px">Comisi&oacute;n</th>';
          $tabla.='<th width="100px">Cambio Turno</th>';
          $tabla.='<th width="100px">Permiso</th>';
          $tabla.='<th width="100px">Observaci&oacute;n</th>';
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='</table>';
          $tabla.='<table width="100%" class="table table-striped table-bordered table-hover" border="1">';
          $tabla.='<tr>';
          $tabla.='<td align="center">No hay registtos que mostrar</td></tr>';
//          $tabla.= "No hay registros que mostrar";
	  $tabla.='</table>';
    }








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
        
    
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pg. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Matesi');
$pdf->SetTitle('Listado de Control de Asistencia');
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
$pdf->SetFont('times', 'BI', 8);

// add a page
$pdf->AddPage('L');
//$pdf->writeHTML("Portrait 1"); 

// set some text to print
//$txt = <<<EOD
//PLANILLA DE CONSULTA MEDICA
//EOD;
//echo $salida;

//$txt=$salida; 
$txt=$tabla; 

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





