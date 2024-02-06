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
//print "<br>cboccosto: ".$cboccosto.'#';
if ($trabajador!='NULL') 
    $trabajador=" and a.cedula=".$trabajador;
else
    $trabajador=" ";

if ($cboccosto!='NULL') 
    $ccosto=" and b.GERENCIA COLLATE Latin1_General_CI_AS ='".$cboccosto."' COLLATE Latin1_General_CI_AS";
else
    $ccosto=" ";
/**************************************************************************************/  
$nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
$query="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$ccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$ccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
// $query_gerente="select trabajador, nombre, gerencia from adam_VW_DOTACION_BRIQVEN_02_MAS where grado_trab = '41' ".$cboccosto;
//print $query;
//$query = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $query);
$qry2 = iconv("UTF-8", "ASCII//TRANSLIT", $query);
//print $qry2;
//$qry = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $qry);  //ESTE ME FUNCIONA BIEN A ECEPCION DE LA 

$qry = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $qry2);


$query_gerente_solicitante="select trabajador, nombre, gerencia, desc_puesto from adam_VW_DOTACION_BRIQVEN_02_MAS where nivel_jerarquico::int = (select min(nivel_jerarquico::int) from adam_VW_DOTACION_BRIQVEN_02_MAS where GERENCIA  =trim('".eliminar_acentos($cboccosto)."') )  and GERENCIA =trim('".eliminar_acentos($cboccosto)."')";
$query_gerente_solicitante=mb_convert_encoding($query_gerente_solicitante, 'UTF-8', 'Windows-1252');
$query_gerente_solicitante = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $query_gerente_solicitante);

// print $query_gerente_solicitante;
$link            = Conex_Contancia_pgsql();
$result          = ejecutar_query($link, $query_gerente_solicitante) or die("Error en la Consulta SQL: ".$query_gerente_solicitante);
$contar          = ejecutar_num_rows($result);
$row             = ejecutar_fetch_array($result);
$cedula_gerente  ='';
$nombre_gerente  ='';
$nombre_gerencia ='';
$desc_puesto     ='';
// print "<br>Contar:".$contar."*";
if ($contar>0){
    $cedula_gerente  = $row['trabajador'];
    $nombre_gerente  = $row['nombre'];
    $nombre_gerencia = $row['gerencia'];
    $desc_puesto     = $row['desc_puesto']; 
}

$query_gerente_RRHH="select trabajador, nombre, gerencia from adam_VW_DOTACION_BRIQVEN_02_MAS where nivel_jerarquico::int = (select min(nivel_jerarquico::int) from adam_VW_DOTACION_BRIQVEN_02_MAS where GERENCIA='Gerencia de Talento Humano' ) and GERENCIA='Gerencia de Talento Humano'";

$result_RRHH  = ejecutar_query($link, $query_gerente_RRHH) or die("Error en la Consulta SQL: ".$query_gerente_RRHH);
$contar_RRHH  = ejecutar_num_rows($result_RRHH);
$row_RRHH     = ejecutar_fetch_array($result_RRHH);
$cedula_gerente_RRHH ='';
$nombre_gerente_RRHH ='';
if ($contar_RRHH>0){
    $cedula_gerente_RRHH  = $row_RRHH['trabajador'];
    $nombre_gerente_RRHH  = $row_RRHH['nombre'];
}


$cn=Conectarse_sitt();
    //print $query;
    $stmt1             = $cn->query($query);
    $TRABAJADOR        = '';
    $NOMBRES           = '';
    $CICLO_LABORAL     = '';
    $FECHA_INI_PER_VAC = '';
    $FECHA_FIN_PER_VAC = '';
    $FECHA_PAGO_VAC    = '';
    $CENTRO_COSTO      = '';
    $SISTEMA_HORARIO   = '';
    $DESC_PUESTO       = '';
    $GERENCIA          = '';
    $FECHA_INGRESO     = '';
    $TIEMPO_PROG_VAC   = '';
    $TRABAJADOR_SUP    = '';
    $jefe              = '';
    $vacpendiente      = 'NO';
    $hoy               = date('d-m-Y');

    $tabla             = '';
    $columna           = '1';
    $contador          = 1;
    $total_st1         = 0;
    $total_dlt1        = 0;
    $total_st2         = 0;
    $total_dlt2        = 0;
    $contador          = 0;

    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
      $TRABAJADOR = trim($row['cedula']);
      $NOMBRES    = $row['nombres'];
      $nombre_trabajador= iconv("ISO-8859-1", "ASCII//TRANSLIT", $row['nombres']);
      $nombre_trabajador= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $nombre_trabajador);
      $fecha      = substr($row['fecha'], 0,10); 
      $fecha      = formato_fecha($fecha,'-');
      $observaciones= iconv("ISO-8859-1", "ASCII//TRANSLIT", $row['observaciones']);
      $observaciones= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $observaciones);
      $contador++;
      $tabla.='<tr>
        <td class="tg-right" width="4%">'.$contador.'</td>
        <td class="tg-right" width="9%">'.$TRABAJADOR.'</td>
        <td class="tg-0pky" width="20%" colspan="2%">'.ucwords(strtolower($nombre_trabajador)).'</td>
        <td class="tg-9wq8" width="10%">'.$fecha.'</td>
        <td class="tg-9wq8" width="6%">'.$row['Horas_ST'].'</td>
        <td class="tg-9wq8" width="6%">'.$row['Horas_Dlt'].'</td>
        <td class="tg-9wq8" width="6%">&nbsp;&nbsp;</td>
        <td class="tg-0pky" width="39%">'.ucfirst(strtolower($observaciones)).'</td>
        </tr>';

      $total_st1  = $total_st1  + $row['Horas_ST'];
      $total_dlt1 = $total_dlt1 + $row['Horas_Dlt'];
      $CENTRO_COSTO   = $row['centro_costo'];
      $GERENCIA       = $row['GERENCIA'];
      $TRABAJADOR_SUP = $row['TRABAJADOR_SUP'];
    }

    $total_st  = $total_st1 + $total_st2;
    $total_dlt = $total_dlt1 + $total_dlt2; 
    $stmt1     = null;
    $cn        = null;

    $gerencia  = nombre_gerente($TRABAJADOR);

    if (isset($gerencia[0]))
        if (trim($gerencia[0])==$TRABAJADOR)
            $gerencia[1]='';
    $gerente=(isset($gerencia[1]) && $gerencia[3]!=44) ? $gerencia[1] : '';

$html='';

    $html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>  
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title></title>     
    <style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:1;border-style:solid;border-width:2px;width: 100%;}
    .tg td{border-color:black;border-style:solid;border-width:2px;font-family:Arial, sans-serif;font-size:10px;}
    .tg th{border-color:black;border-style:solid;border-width:2px;font-family:Arial, sans-serif;font-size:10px;}
    .tg .tg-pb0m{border-color:inherit;border: 1px solid #0000;text-align:center;vertical-align:bottom}
    .tg .tg-lboi{border-color:inherit;border: 1px solid #0000;text-align:left;vertical-align:middle}
    .tg .tg-9wq8{border-color:inherit;border: 1px solid #0000;text-align:center;vertical-align:middle} 
    .tg .tg-c3ow{border-color:inherit;border: 1px solid #0000;text-align:center;vertical-align:top}
    .tg .tg-0pky{border-color:inherit;border: 1px solid #0000;text-align:left;vertical-align:top}
    .tg .tg-right{border-color:inherit;border: 1px solid #0000;text-align:right;vertical-align:top}
    body {
      background: white; 
    }
    page {
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    page[size="A4"] {  
      width: 21cm;
      height: 29.7cm; 
    }
    page[size="A4"][layout="portrait"] {
      width: 29.7cm;
      height: 21cm;  
    }
    @media print {
      body, page {
        margin: 0;
        box-shadow: 0;
      }
    }
    </style>
    </head>
    <body>
    <div align="center"><font face="Arial, sans-serif" size="12">Solicitud de horas extras y trabajo en dia libre (DLT)</font></div>
    <br>
    <table class="tg">
    <thead>
      <tr>
        <th class="tg-c3ow" colspan="6">Datos del solicitante</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-0pky" colspan="1">Nombre y apellido del solicitante</td>
        <td class="tg-0pky" colspan="1">'.ucwords(strtolower($nombre_gerente)).'</td>
        <td class="tg-lboi" colspan="1">No. Cedula</td>
        <td class="tg-lboi" colspan="1">'.$cedula_gerente.'</td>
        <td class="tg-0pky">Fecha Solicitud</td>
        <td class="tg-0pky">'.$hoy.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="1">Gerencia</td>
        <td class="tg-0pky" colspan="2">'.ucwords(strtolower($nombre_gerencia)).'</td>
        <td class="tg-0pky" colspan="1">Cargo</td>
        <td class="tg-0pky" colspan="2">'.ucwords(strtolower($desc_puesto)).'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="1">Gerencia General</td>
        <td class="tg-0pky" colspan="2">'.mb_convert_encoding($GERENCIA, 'UTF-8', 'Windows-1252').'</td>
        <td class="tg-0pky" colspan="1">Gerente General</td>
        <td class="tg-0pky" colspan="2">'.ucwords(strtolower($gerente)).'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="1">Tipo de autorizacion</td>
        <td class="tg-0pky" colspan="1">Horas extraordinarias</td>
        <td class="tg-9wq8">'.$total_st.'</td>
        <td class="tg-0pky" colspan="1">Trabajo de dias libre</td>
        <td class="tg-9wq8" colspan="2">'.$total_dlt .'</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="6">CERTIFICACION DE HORAS EXTRAS Y TRABAJO EN DIA LIBRE</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="6">Razones correspondiente a Horas Extras y DLT</td>
      </tr>      
      <tr>
        <td class="tg-0pky" colspan="6">A.- Trabajos preparatorios o complementarios que deban ejecutarse necesariamente fuera de los límites señalados al trabajo general de la entidad de Trabajo.</td>
      </tr>      
      <tr>
        <td class="tg-0pky" colspan="6">B.- Trabajos que por razones técnicas no pueden interrumpirse a voluntad, o tienen que llevarse a cabo para evitar el deterioro de las materias o de los productos o comprometer el resultado del trabajo.</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="6">C.- Trabajos indispensables para coordinar la labor de dos equipos que se relevan.</td>
      </tr> 
      <tr>
        <td class="tg-0pky" colspan="6">D.- Trabajos exigidos por la elaboración de inventarios y balances, vencimientos, liquidaciones, finiquitos y cuentas.</td>
      </tr>  
      <tr>
        <td class="tg-0pky" colspan="6">E.- Trabajos extraordinarios debido a circunstancias particulares, tales como la de terminación o ejecución de una obra urgente, o atender necesidades de la población en ciertas épocas del año.</td>
      </tr>  
      <tr>
        <td class="tg-0pky" colspan="6">F.- Trabajos "especiales y excepcionales" como reparaciones, modificaciones o instalaciones de maquinarias nuevas, canalizaciones de agua o gas, Líneas o conductores de energía eléctrica o telecomunicaciones.</td>
      </tr> 
      </tbody>
      </table>
      <br><br>
      <table class="tg">  
        <thead>      
          <tr>
            <td class="tg-c3ow" width="4%"></td>
            <td class="tg-right" width="9%">Cedula</td>
            <td class="tg-9wq8" width="20%" colspan="2%">Nombre</td>
            <td class="tg-9wq8" width="10%">Fecha</td>
            <td class="tg-9wq8" width="6%">Horas<br>Extras</td>
            <td class="tg-9wq8" width="6%">Horas<br>DLT</td>
            <td class="tg-9wq8" width="6%">Raz&oacute;n</td>
            <td class="tg-9wq8" width="39%">Motivo</td>
          </tr>
        </thead>
        <tbody>
          '.$tabla.'
          <tr>
            <td class="tg-9wq8" colspan="5">Subtotal</td>
            <td class="tg-9wq8">'.$total_st1.'</td>
            <td class="tg-9wq8">'.$total_dlt1.'</td>
            <td class="tg-0pky" colspan="2"></td>
          </tr>
        </tbody>
      </table>
      <table class="tg">
      <tbody>
      <tr>
        <td class="tg-c3ow" colspan="5" width="33%">SOLICITADO POR</td>
        <td class="tg-c3ow" colspan="4" width="33%">REVISADO</td>
        <td class="tg-c3ow" colspan="2" width="33%">AUTORIZADO</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="5" width="33%"><br><br><br>'.$nombre_gerente.', C.I.: '.$cedula_gerente.'<br>'.$nombre_gerencia.'</td>
        <td class="tg-pb0m" colspan="4" width="33%"><br><br><br>Luis Verenzuela CI: 14.576.991<br>Vicepresidente</td>
        <td class="tg-pb0m" colspan="2" width="33%"><br><br><br>'.$nombre_gerente_RRHH.', C.I.: '.$cedula_gerente_RRHH.'<br>Gerente de Talento Humano</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="10" rowspan="2" >Contando con las autorizaciones correspondientes se informa a la unidad <br>de "Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) indicado en esta planilla.</td>
        <td class="tg-0pky" colspan="1"></td>
      </tr>
      <tr>
        
        <td class="tg-pb0m" colspan="1">Gerente de Talento Humano</td>
      </tr>
    </tbody>
    </table>

    </html>';

$tabla2='<table border="1">'.$tabla.'</table>';
//print $tabla2;
/**************************************************************************************/
    
/*
$link=Conex_Contancia_pgsql();  
$query="SELECT tbl_constacias.*, to_char(fecha, 'DD') as dia, to_char(fecha, 'MM') as mess, to_char(fecha, 'YYYY') as year, length(fecha_ingreso) as baja  FROM tbl_constacias WHERE fecha='".$fec."'";
$result = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);

$query2="SELECT nombres, cedula , cargo FROM tbl_firma_autorizada WHERE estatus='ACTIVO'";
$result2 = pg_query($link, $query2) or die("Error en la Consulta SQL: ".$query2);
$row2 = pg_fetch_array($result2);*/
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
        $img_file = 'images/fondo_documento_briqven.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-35);
        // Set font
        $this->SetFont('Helvetica', '', 6);
        // Page number
        $this->Cell(0, 10, ' - Solicitud de horas extras y trabajo en dia libre () -                 Pág. -'.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'-', 0, false, 'C', 0, '', 0, false, 'T', 'M');
       // $this->Cell(0, 10,'Solicitud de horas extras y trabajo en dia libre (DLT)', 0, false, 'L', 0, '', 0, false, 'T', 'M');
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
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', '20'));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(60);

// remove default footer
$pdf->setPrintFooter(true);
$pdf->setPrintHeader (true);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, '35');

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// add a page
$pdf->AddPage();
$i=1;
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
$pdf->SetFont('helvetica', '', 6);

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, true);
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();


// reset pointer to the last page
$pdf->lastPage();
$i++;

//Close and output PDF document

$pdf->Output('Planilla_STDLT.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
