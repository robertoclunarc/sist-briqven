<?php
error_reporting(E_ERROR | E_PARSE);
include("../BD/conexion.php");
require('funciones_var.php');

$fec= isset($_GET["fec"])?$_GET["fec"]:""; 

$finicio    = isset($_GET["i"])?$_GET["i"]:"NULL";         //
$ffin       = isset($_GET["f"])?$_GET["f"]:"NULL";
$trabajador = isset($_GET["t"])?$_GET["t"]:"NULL";
$cboccosto  = isset($_GET["c"])?$_GET["c"]:"NULL";
$nombre     = isset($_GET["n"])?$_GET["n"]:"NULL";

if ($trabajador!="NULL") 
    $trabajador=" and a.cedula=".$trabajador;
else
    $trabajador=" ";

if ($cboccosto!="NULL") 
    $cboccosto=" and b.GERENCIA ='".$cboccosto."' COLLATE Latin1_General_CI_AS";
else
    $cboccosto=" ";
/**************************************************************************************/  

$query_gerente_RRHH="select trabajador, nombre, gerencia, desc_puesto from adam_VW_DOTACION_BRIQVEN_02_MAS where nivel_jerarquico::int = (select min(nivel_jerarquico::int) from adam_VW_DOTACION_BRIQVEN_02_MAS where GERENCIA='Gerencia de Talento Humano' ) and GERENCIA='Gerencia de Talento Humano'";
$link                = Conex_Contancia_pgsql();
$result_RRHH         = ejecutar_query($link, $query_gerente_RRHH) or die("Error en la Consulta SQL: ".$query_gerente_RRHH);
$contar_RRHH         = ejecutar_num_rows($result_RRHH);
$row_RRHH            = ejecutar_fetch_array($result_RRHH);
//print_r($row_RRHH);
$cedula_gerente_RRHH = '';
$nombre_gerente_RRHH = '';
$puesto_gerente_RRHH = '';
if ($contar_RRHH>0){
  $cedula_gerente_RRHH = $row_RRHH['trabajador'];
  $nombre_gerente_RRHH = $row_RRHH['nombre'];
  $puesto_gerente_RRHH = $row_RRHH['desc_puesto'];
  $pos = stripos($puesto_gerente_RRHH, '(E)');
  //print $pos;
  if ($pos !== false) {
      $puesto_gerente_RRHH = substr(ucwords(strtolower($puesto_gerente_RRHH)), 0,$pos).' (E)';
  }
}

$nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador"; 
$qry="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 order by a.cedula, b.nombres, a.fecha";
$qry = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $qry);

$cn=Conectarse_sitt();
//print $qry;
        $stmt1 = $cn->query($qry);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;   
       //print "Cantidad Regitros:".$contar;     
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" background="images/fondo_documento_briqven.jpg">
              <tr>
                <td>
                  <font face="nunito,Arial,verdana" SIZE=2><br><br>
                      <b>Sres.</b><br>
                      <b>Inspector&iacute;a del Trabajo de Puerto Ordaz Alfredo Maneiro.</b><br>
                      Coordinaci&oacute;n del Ministerio de trabajo del Edo. Bol&iacute;var.<br>
                      <br>
                      <b>Presente.</b><br>
                      <b>Asunto:</b> entrega del reporte de hora extra generados por los trabajadores.<br>
                      <p style="text-align:justify;">
                      Tengo el honor de dirigirme a usted, en la oportunidad de expresarle un respetuoso saludo institucional, Bolivariana, revolucionario, socialista y chavista, en el nombre de los hombres y mujeres que integran la empresa socialista <b>MATESI, S.A</b>, materiales sider&uacute;rgicas, para la conformaci&oacute;n de la empresa <b>BRIQVEN</b>, briquetera de Venezuela. El presente escrito tiene como finalidad, de hacer entrega el reporte de horas extraordinarias, solicitud que hago a usted en conformidad con los dispuesto en el art&iacute;culo <b>Nº 182</b> de la  Ley Org&aacute;nica del Trabajo, los Trabajadores y las Trabajadoras, de manera de cumplir con el requisito  de autorizaci&oacute;n previa por parte de la inspector&iacute;a de trabajo y en concordancia con el art&iacute;culo de su reglamento. En tal sentido, se detallan a continuaci&oacute;n: 
                      </p>
                  </font>
                 </td>
               </tr>
               </table>';
               $inpt.='<tr><td><table>
                      <tr>
                        <td width="5%" style="text-align:center">#</td>
                        <td width="50%">Nombre del Trabajador</td>
                        <td width="20%">Cédula</td>
                        <td width="20%">Fecha</td>
                        <td width="5%">Horas ST</td>              
                      </tr>
                      <table></td></tr>';
               $P='<tr>
                 <td>
                    <table width="80%" border="1">
                        <thead>
                    <tr>
                        <td colspan="5" style="text-align:center" >'.$nombre.'</td>
                    </tr>  
                    <tr>
                        <td width="5%" style="text-align:center">#</td>
                        <td width="50%">Nombre del Trabajador</td>
                        <td width="20%">Cédula</td>
                        <td width="20%">Fecha</td>
                        <td width="5%">Horas ST</td>              
                    </tr>
                </thead>
                <tbody>';
        //print $inpt;
              $contar    = 0;
              $total_ST  = 0;
              $total_DLT = 0;
              $tabla     = '<table border="1"><tr>
                <td width="5%" style="text-align:center">#</td>
                <td width="45%" style="text-align:center">Nombre del Trabajador</td>
                <td width="20%" style="text-align:center">Cédula</td>
                <td width="15%" style="text-align:center">Fecha</td>
                <td width="15%" style="text-align:center">Horas ST</td>              
            </tr>';
              $libres    = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
              while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $total_ST  = $total_ST + $row['Horas_ST'];
                    $total_DLT = $total_DLT + $row['Horas_Dlt'];
                    if (isset($row['fecha'])){
                      $fecha     = substr($row['fecha'], 0,10);
                      $separador = '-';
                      $fecha     = formato_fecha($fecha,$separador);
                    }else{
                      $fecha='';
                    } 
                     
                    if (isset($row['observaciones']))
                        $observaciones=$row['observaciones'];
                    else  
                        $observaciones='';

                    $tabla .='<tr>';                     

                    // print ucfirst(strtolower($row['nombres']));
                    $tabla .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                    $tabla .='<td width="45%">&nbsp;&nbsp;'.ucwords(strtolower($row['nombres'])).'</td>';
                    $tabla .='<td width="20%" style="text-align:right"><font face="nunito,Arial,verdana" SIZE=2>'.$row['cedula'].'</font>&nbsp;&nbsp;</td>';                        
                    $tabla .='<td width="15%" style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2>'.$fecha.'  </font></td>';
                    $tabla .='<td width="15%" style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2>'.$row['Horas_ST'].'</font></td>';
                    $tabla .='</tr>';                        
              } 
              $tabla .='<tr>
                        <td colspan="4" style="text-align:right"><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>Total:&nbsp;&nbsp;&nbsp;&nbsp;</font></span></td>
                        <td style="text-align:center"><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>'.$total_ST.'</font></span></td>
                    </tr></table>';
              /*$inpt_foot='<table>
                    <tr>
                        <td colspan="2"><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>Total:</font></span></td>
                        <td><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>'.$total_ST.'</font></span></td>
                    </tr></table>
                    ';*/
              //$inpt_foot .='                </table>';
$inpt_foot .='<br>Sin m&aacute;s a que hacer referencia, me despido de usted.<br><br>



                <table border="0" width="100%">
                  <tr>
                    <td style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2><b>Atentamente</b></font></td>
                  </tr>                
                  <tr>
                    <td style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2><b></b></font></td>
                  </tr>    
                  <tr>
                    <td style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2><b></b></font></td>
                  </tr>                                        
                  <tr>
                    <td style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2>'.ucwords(strtolower($nombre_gerente_RRHH)).'</font></td>
                  </tr>
                  <tr>
                    <td style="text-align:center"><font face="nunito,Arial,verdana" SIZE=1>'.$puesto_gerente_RRHH.'</font></td>
                  </tr>
                </table>';
        }

//$inpt ="hola mundo";

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
$pdf->SetTitle('Planilla de ST y DLT');
$pdf->SetSubject('Control Asistencia');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

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
$img_file = 'images/fondo_documento_briqven.jpg';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();

// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$fecha='Puerto Ordaz, '.date('d').' de '.ucwords(strtolower(mes_espanol(date('n')))).' del '.date('Y');
$pdf->Ln();
$pdf->Write(0, $fecha, '', 0, 'R', true, 0, false, false, 0);
$pdf->writeHTML($inpt, true, 0, true, true);
$pdf->writeHTML($tabla, true, 0, true, true);
$pdf->Ln();
$pdf->writeHTML($inpt_foot, true, 0, true, true);

// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document

$pdf->Output('Planilla_STDLT.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+