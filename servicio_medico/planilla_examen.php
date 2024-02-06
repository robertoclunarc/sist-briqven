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

$qmorb = "SELECT * FROM v_examen_ocupacional where uid=" . $planilla;
$resultmorb = pg_query($conexion, $qmorb) or die("Error en la Consulta SQL: ".$qmorb);
$filamorb=pg_fetch_array($resultmorb);
$numReg = pg_num_rows($resultmorb);
$ci=$filamorb['ci'];
$idpaciente=$filamorb['uid_paciente'];
$fecha=substr($filamorb['fecha'],0,16);
$fechacorta=substr($filamorb['fecha'],0,10);
$nombrespaciente=$filamorb['paciente'];
$edad=$filamorb['edad'];
$antiguedad=$filamorb['antiguedad'];
$fecha_ing=$filamorb['fecha_ingreso'];
$fecha_nac=$filamorb['fechanac'];
$cargo=$filamorb['cargo'];
$depart=$filamorb['departamento'];
$medico=$filamorb['medico'];
pg_free_result($resultmorb);

$Qrysignos=pg_query($conexion,"SELECT cedula, fresp, pulso, temper, tart, fecha, fcard FROM tbl_signos_vitales WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
$Regsignos = pg_fetch_array($Qrysignos, null, PGSQL_ASSOC);

$Qrydatos_ant=pg_query($conexion,"SELECT cedula, talla, peso, imc, fecha FROM tbl_datos_antropometricos WHERE cedula = '".$ci."' AND to_char(fecha,'YYYY-MM-DD HH24:MI') = '".$fecha."'");
$Regdatos_ant = pg_fetch_array($Qrydatos_ant, null, PGSQL_ASSOC);

$pulso=$Regsignos['pulso'];
$fcard=$Regsignos['fcard'];
$tart=$Regsignos['tart'];
$talla=$Regdatos_ant['talla'];
$peso=$Regdatos_ant['peso'];

pg_free_result($Qrysignos);
pg_free_result($Qrydatos_ant);

$query = "SELECT descripcion || ' - ' || datos_requeridos || ' Tiempo Exp.: ' || tiempo_exposicion as antecedente_laboral, resp FROM v_riesgos where cedula='".$ci."'";
$rs_ant_lab = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);

$query = "SELECT uid_est_fisico, cedula, descripcion, observacion, fecha_examen FROM v_examen_fisico WHERE uid_est_fisico = 56 AND cedula='".$ci."' GROUP BY uid_est_fisico, cedula, descripcion, observacion, fecha_examen having fecha_examen = max('".$fechacorta."'::date) ORDER BY fecha_examen DESC";
$rs_quirurj = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);

$query = "SELECT uid_est_fisico, cedula, descripcion, observacion, fecha_examen FROM  tbl_estudios_fisicos, tbl_examen_fisico WHERE uid_est_fisico = fk_fisico AND cedula='".$ci."' GROUP BY uid_est_fisico, cedula, descripcion, observacion, fecha_examen having fecha_examen = max('".$fechacorta."'::date) ORDER BY uid_est_fisico";
$rs_exam_fis = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);

$query = "SELECT fk_paciente, descripcion, paterentezco, estatus_familiar FROM v_antecedentes_famil WHERE fk_paciente=".$idpaciente;
$rs_ant_fam = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);

//

$salida="";
$campo='';

if($numReg>0){  

    $salida = $salida . '';
    $salida = $salida . '<TABLE width="80%" border="0" cellspacing="0" cellpadding="0">' ;
    $salida = $salida . '<TR><TD style="vertical-align:middle; text-align:center" WIDTH="20%"><img align="center" width="119px" height="70px" src="images/logo.jpg"></TD><TD WIDTH="90%">' ;
    $salida = $salida . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
    $salida = $salida . '<TR><TD align="center">GERENCIA DE TALENTO HUMANO</TD></TR>' ;
    $salida = $salida . '<TR><TD align="center">Depto. de Higiene y Seguridad Industrial</TD></TR>' ;
    $salida = $salida . '<TR><TD align="center"><h1>Examen Medico Ocupacional</h1></TD></TR>' ;
    $salida = $salida . '</TABLE></TD></TR></TABLE><p>&nbsp;</p>';
    
    $salida = $salida . '<TABLE width="80%" BORDER="0">';    

    $salida = $salida . '<tr>
    <td width="20%">Puerto Ordaz;&nbsp;</td>
    <td width="20%">'.$fecha.'</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';

    $salida = $salida . '<tr>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';

    $salida = $salida . '<tr>
    <td NOWRAP>Nombre y Apellidos:&nbsp;</td>
    <td NOWRAP>' .  $nombrespaciente . '&nbsp;</td>
    <td>Cedula:&nbsp;</td>
    <td>&nbsp;' .  $ci . '</td>
    </tr>';

    $salida = $salida . '<tr>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';

    $salida = $salida . '<tr>
    <td>Fecha Nac.:&nbsp;</td>
    <td>' .  $fecha_nac . '&nbsp;</td>
    <td>Edad:&nbsp;</td>
    <td NOWRAP>' .  $edad . '&nbsp;</td>
    </tr>';

    $salida = $salida . '<tr>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';
    
    $salida = $salida . '<tr>
    <td NOWRAP>Tiempo en la Empresa:&nbsp;</td>
    <td NOWRAP>' . $antiguedad . '&nbsp;</td>
    <td>Fecha Ingreso:</td>
    <td>' .  $fecha_ing . '&nbsp;</td>
    </tr>';

    $salida = $salida . '<tr>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';
  
    $salida = $salida . '<tr>
    <td>Cargo:&nbsp;</td>
    <td NOWRAP>' . $cargo . '</td>
    <td>Departamento:&nbsp;</td>
    <td NOWRAP>' .  $depart . '</td>
    </tr>';

    $salida = $salida . '<tr>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    </tr>';
        while ($record_ant_lab=pg_fetch_array($rs_ant_lab)) {
          if ($record_ant_lab['resp']!='')    
              $campo=$campo.$record_ant_lab['antecedente_laboral'].' - '.$record_ant_lab['resp'].'<br>';
          else
              $campo=$campo.$record_ant_lab['antecedente_laboral'].'<br>';  
        }
        pg_free_result($rs_ant_lab);
        if ($campo!=''){ 
            $salida = $salida . '<tr>
            <td>Antecedentes Laborales:&nbsp;</td>
            <td>' . $campo . '</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>';
        }  
        $campo=''; 
        while ($record_ant_fam=pg_fetch_array($rs_ant_fam)) {
          if ($record_ant_fam['descripcion']!='')    
              $campo=$campo.$record_ant_fam['descripcion'].' - '.$record_ant_fam['paterentezco'].'<br>';   
        }     
        pg_free_result($rs_ant_fam);
        if ($campo!=''){
            $salida = $salida . '<tr>
            <td>Antecedentes Familiares:&nbsp;</td>
            <td>' . $campo . '</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>';
        }
        $campo=''; 
        while ($record_quirurj=pg_fetch_array($rs_quirurj)) {
          if ($record_quirurj['descripcion']!='')    
              $campo=$campo.$record_quirurj['descripcion'].' - '.$record_quirurj['observacion'].'<br>';   
        }     
        pg_free_result($rs_quirurj);
        if ($campo!=''){
            $salida = $salida . '<tr>
            <td>Antecedentes Quirurgicos:&nbsp;</td>
            <td>' . $campo . '</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr></TABLE>';
        }
        ////datos signos vitales y datos antropometricos
            $salida = $salida . '<p>&nbsp;</p><TABLE width="80%" BORDER="0">';   
            $salida = $salida . '<tr>
            <td NOWRAP >EXAMEN FISICO:&nbsp;</td>
            <td>&nbsp;FC:&nbsp;</td>
            <td>' .  $fcard . '</td>
            <td>&nbsp;TA:&nbsp;</td>
            <td>' .  $tart . '</td>
            <td>&nbsp;Pulso:&nbsp;</td>
            <td>' .  $pulso . '</td>
            </tr><tr>    
            <td>&nbsp;</td>
            <td>&nbsp;Talla:&nbsp;</td>
            <td>' .  $talla . '</td>
            <td>&nbsp;Peso:&nbsp;</td>
            <td>' .  $peso . '</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr></TABLE>';
        /// tabla examen fisicos

            $salida = $salida . '<p>&nbsp;</p><TABLE width="80%" BORDER="0">';
            while ($record_exam_fis=pg_fetch_array($rs_exam_fis)) {  
                $salida = $salida . '<tr>';
                $salida = $salida . '<td width="20%">' .  $record_exam_fis['descripcion'] . ':</td>';
                $salida = $salida . '<td width="1%">&nbsp;</td>';
                $salida = $salida . '<td width="59%">' .  $record_exam_fis['observacion'] . '</td> ';
                //$salida = $salida . '<td width="10%">' .  $record_exam_fis['fecha_examen'] . '</td> ';  
                $salida = $salida . '</tr>';
            }
        pg_free_result($rs_exam_fis);     
        $salida = $salida . '</TABLE>'; 
        ///firma del trabajador y medico a cargo
            $salida = $salida . '<p>&nbsp;</p><TABLE width="100%" BORDER="0">';
            $salida = $salida . '<tr><td width="30%">MÉDICO</td><td width="40%">&nbsp;</td><td width="30%">TRABAJADOR</td></tr>';
            
            $salida = $salida . '<tr><td>'.$medico.'</td><td>&nbsp;</td><td>'.$nombrespaciente.'</td></tr>';

            $salida = $salida . '<tr><td><font color="#F0ECEC">FIRMA MEDICO</font></td><td>&nbsp;</td><td><font color="#F0ECEC">FIRMA TRABAJADOR</font></td></tr>';   

            $salida = $salida . '</TABLE>';
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
        $this->Cell(0, 10, 'Pg. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Matesi');
$pdf->SetTitle('Examen Médico');
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