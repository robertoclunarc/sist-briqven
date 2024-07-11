<?php
  session_start();
  include("../BD/conexion.php");
  require_once('funciones_var.php');
  /********************************/
  //require_once('tcpdf/tcpdf.php');
  //header('Content-type: application/pdf');
/*
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 12);
  $obj_pdf->AddPage();

*/

/*************************************/
  $query      = isset($_GET["query"])?$_GET["query"]:"NULL";

  $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
  $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
  $nombre     = isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
  $tiporeporte= isset($_POST["tiporeporte"])?$_POST["tiporeporte"]:"NULL";
  $cboccosto  = isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";
  //print $nombre;

  if ($trabajador!='NULL') 
    $trabajador=" and a.cedula=".$trabajador;
  else
    $trabajador=" ";

  if ($cboccosto!='NULL') 
    $cboccosto=" and b.GERENCIA='".$cboccosto."'";
  else
    $cboccosto=" ";
  

  switch ($tiporeporte) {
      case 1:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/
          $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
          $query="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
          break;
      case 2:
          /**********************************************************************************************/
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /**********************************************************************************************/  
          $nombre="Reporte de Horas extras y dias libres trabajados, resumido por trabajador"; 
          $query="select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto."  group by a.cedula, b.nombres, b.GERENCIA,b.centro_costo, b.desc_ccosto having sum(Horas_ST)>0 UNION  select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." group by a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto having sum(Horas_DLT)>0";
          break;   
  }    

/*
  //print $query;

//  echo formato_vacaciones_htlm($query);

//function formato_vacaciones_htlm($query){

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Oficina de Tecnologia e informacion');
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

// set font
$pdf->SetFont('times', 'BI', 9);

// add a page
$pdf->AddPage('L', 'LETTER');


*/

    $cn=Conectarse_sitt();
    //print $query;
    $stmt1 = $cn->query($query);
    $TRABAJADOR='';
    $NOMBRES='';
    $CICLO_LABORAL='';
    $FECHA_INI_PER_VAC='';
    $FECHA_FIN_PER_VAC='';
    $FECHA_PAGO_VAC='';
    $CENTRO_COSTO='';
    $SISTEMA_HORARIO='';
    $DESC_PUESTO='';
    $GERENCIA='';
    $FECHA_INGRESO='';
    $TIEMPO_PROG_VAC='';
    $TRABAJADOR_SUP='';
    $jefe='';
    $vacpendiente='NO';
    $hoy  = date('d-m-Y');

    $tabla='<tr>';
    $columna='1';
    $contador=1;
    $total_st1=0;
    $total_dlt1=0;
    $total_st2=0;
    $total_dlt2=0;

    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
    	$TRABAJADOR=trim($row['cedula']);
    	$NOMBRES=$row['nombres'];
      $fecha = substr($row['fecha'], 0,10); 
      $fecha=formato_fecha($fecha,'-');
      
      //print '<br>columna:'.$columna.', '.$TRABAJADOR.', '.$NOMBRES ;

      $tabla.='
        <td class="tg-9wq8">'.$TRABAJADOR.'</td>
        <td class="tg-lboi">'.$NOMBRES.'</td>
        <td class="tg-lboi">'.$fecha.'</td>
        <td class="tg-9wq8">'.$row['Horas_ST'].'</td>
        <td class="tg-9wq8">'.$row['Horas_Dlt'].'</td>
        <td class="tg-lboi">'.$row['observaciones'].'</td>
      ';

      if ($columna=='2'){
        $tabla.='</tr><tr>';
        $columna='1';
        $total_st1=$total_st1 + $row['Horas_ST'];
        $total_dlt1=$total_dlt1 + $row['Horas_Dlt'];
        //print "#<br>";
      }elseif ($columna=='1'){
        $total_st2=$total_st2 + $row['Horas_ST'];
        $total_dlt2=$total_dlt2 + $row['Horas_Dlt'];
        $columna='2';
      }
      $contador++;
    //print $tabla;
      

    //	$FECHA_INI_PER_VAC=substr($row['FECHA_INI_PER_VAC'], 0, 10);
    //	$FECHA_FIN_PER_VAC=substr($row['FECHA_FIN_PER_VAC'], 0, 10);
    //	$FECHA_PAGO_VAC=substr($row['FECHA_PAGO_VAC'], 0, 10);
    	$CENTRO_COSTO=$row['centro_costo'];
    //	$SISTEMA_HORARIO=$row['SISTEMA_HORARIO'];
    //	$DESC_PUESTO=$row['DESC_PUESTO'];
    	$GERENCIA=$row['GERENCIA'];
    //	$FECHA_INGRESO=substr($row['FECHA_INGRESO'], 0, 10);
    //	$TIEMPO_PROG_VAC=$row['TIEMPO_PROG_VAC'];
    	$TRABAJADOR_SUP=$row['TRABAJADOR_SUP'];
    //	$jefe=$row['jefe'];
    //	$vacpendiente=$row['vacpendiente'];
    //	$FECHA_ALTA=substr($row['FECHA_ALTA'], 0, 10);
    //	$motivo_interrupcion=$row['motivo_interrupcion'];
    //	$disposicion=$row['disposicion'];
    //	$FECHA_MOD=$row['FECHA_MOD'];
    //	$USUARIO_MOD=$row['USUARIO_MOD'];
    //	$hoy=$row['hoy'];
    //	$dia_examen=date("Y-m-d",strtotime($FECHA_INI_PER_VAC." -1 days"));

    //	$fecha_reintegro=date("Y-m-d",strtotime($FECHA_FIN_PER_VAC." 1 days"));
      
    }
    if ($columna=='2'){
      $tabla.='
        <td class="tg-9wq8"></td>
        <td class="tg-lboi"></td>
        <td class="tg-lboi"></td>
        <td class="tg-9wq8"></td>
        <td class="tg-9wq8"></td>
        <td class="tg-lboi"></td>
        </tr>';
    } 
    $total_st  = $total_st1 + $total_st2;
    $total_dlt = $total_dlt1 + $total_dlt2; 
    $stmt1=null;
    $cn=null;

    $gerencia=nombre_gerente($TRABAJADOR);

    if (isset($gerencia[0]))
    	if (trim($gerencia[0])==$TRABAJADOR)
    		$gerencia[1]='';
    $gerente=(isset($gerencia[1]) && $gerencia[3]!=44) ? $gerencia[1] : '';


    //if (strtotime($FECHA_ALTA) < strtotime($FECHA_INI_PER_VAC))
    //	$disposicion="";

    $html='';

    $html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>	
    	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    	<title></title>		
    <style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-pb0m{border-color:inherit;text-align:center;vertical-align:bottom}
    .tg .tg-lboi{border-color:inherit;text-align:left;vertical-align:middle}
    .tg .tg-9wq8{border-color:inherit;text-align:center;vertical-align:middle} 
    .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
    .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
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
    <table class="tg">
    <thead>
      <tr>
        <th class="tg-0pky" colspan="2"><font color="#000000"><img src="images/logo_CSV.jpg" width=80 height=40 ></font></th>
        <th class="tg-c3ow" colspan="8">Solicitud de horas extras y trabajo en dia libre</th>
        <th class="tg-0pky"colspan="2"><font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-c3ow" colspan="12">Datos del solicitante</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Nombre y apellido <br>del solicitante</td>
        <td class="tg-0pky" colspan="4"></td>
        <td class="tg-lboi" colspan="2">No. Cedula</td>
        <td class="tg-lboi" colspan="2"></td>
        <td class="tg-0pky">Fecha</td>
        <td class="tg-0pky">'.$hoy.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia</td>
        <td class="tg-0pky" colspan="4"></td>
        <td class="tg-0pky" colspan="2">Cargo</td>
        <td class="tg-0pky" colspan="4"></td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia General</td>
        <td class="tg-0pky" colspan="4">'.$GERENCIA.'</td>
        <td class="tg-0pky" colspan="2">Gerente General</td>
        <td class="tg-0pky" colspan="4">'.$gerente.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Tipo de autorizacion</td>
        <td class="tg-0pky" colspan="3">Horas extras extraordinarias</td>
        <td class="tg-9wq8">'.$total_st.'</td>
        <td class="tg-0pky" colspan="2">Trabajo de dias libre</td>
        <td class="tg-9wq8" colspan="4">'.$total_dlt .'</td>
      </tr>
      <tr>
        <td class="tg-9wq8">Cedula<br></td>
        <td class="tg-9wq8">Nombre</td>
        <td class="tg-9wq8">Fecha<br></td>
        <td class="tg-9wq8">Horas<br>Extras</td>
        <td class="tg-9wq8">Horas de <br>DLT</td>
        <td class="tg-9wq8">Motivo</td>
        <td class="tg-9wq8">Cedula</td>
        <td class="tg-9wq8">Nombre</td>
        <td class="tg-9wq8">Fecha</td>
        <td class="tg-9wq8">Horas<br>Extras</td>
        <td class="tg-9wq8">Horas<br>de DLT<br></td>
        <td class="tg-9wq8">Motivo</td>
      </tr>
      '.$tabla.'
      <tr>
        <td class="tg-0pky" colspan="3">Subtotal</td>
        <td class="tg-9wq8">'.$total_st1.'</td>
        <td class="tg-9wq8">'.$total_dlt1.'</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky" colspan="3">Subtotal</td>
        <td class="tg-9wq8">'.$total_st2.'</td>
        <td class="tg-9wq8">'.$total_dlt2.'</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="4">SOLICITADO POR</td>
        <td class="tg-c3ow" colspan="4">REVISADO</td>
        <td class="tg-c3ow" colspan="4">AUTORIZADO</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="4"><br><br><br>AREVALO YUDISAY V-14509326<br>Gerente de mantenimiento</td>
        <td class="tg-pb0m" colspan="4"><br><br><br>Luis Verenzuela CI: 14.576.991<br>vicepresidente</td>
        <td class="tg-pb0m" colspan="4"><br><br><br><br>Gerente de Talento Humano</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="8" rowspan="2">Contando con las autorizaciones correspondientes se informa a la unidad <br>de "Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) indicado en esta planilla.</td>
        <td class="tg-0pky" colspan="4"></td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="4">Gerente de Talento Humano</td>
      </tr>
    </tbody>
    </table>

    </html>';





    $pdf_html='
     <table class="tg">
    <thead>
      <tr>
        <th class="tg-0pky" colspan="2"><font color="#000000"><img src="images/logo_CSV.jpg" width=80 height=40 ></font></th>
        <th class="tg-c3ow" colspan="8">Solicitud de horas extras y trabajo en dia libre</th>
        <th class="tg-0pky"colspan="2"><font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-c3ow" colspan="12">Datos del solicitante</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Nombre y apellido <br>del solicitante</td>
        <td class="tg-0pky" colspan="4"></td>
        <td class="tg-lboi" colspan="2">No. Cedula</td>
        <td class="tg-lboi" colspan="2"></td>
        <td class="tg-0pky">Fecha</td>
        <td class="tg-0pky">'.$hoy.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia</td>
        <td class="tg-0pky" colspan="4"></td>
        <td class="tg-0pky" colspan="2">Cargo</td>
        <td class="tg-0pky" colspan="4"></td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia General</td>
        <td class="tg-0pky" colspan="4">'.$GERENCIA.'</td>
        <td class="tg-0pky" colspan="2">Gerente General</td>
        <td class="tg-0pky" colspan="4">'.$gerente.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Tipo de autorizacion</td>
        <td class="tg-0pky" colspan="3">Horas extras extraordinarias</td>
        <td class="tg-9wq8">'.$total_st.'</td>
        <td class="tg-0pky" colspan="2">Trabajo de dias libre</td>
        <td class="tg-9wq8" colspan="4">'.$total_dlt .'</td>
      </tr>
      <tr>
        <td class="tg-9wq8">Cedula<br></td>
        <td class="tg-9wq8">Nombre</td>
        <td class="tg-9wq8">Fecha<br></td>
        <td class="tg-9wq8">Horas<br>Extras</td>
        <td class="tg-9wq8">Horas de <br>DLT</td>
        <td class="tg-9wq8">Motivo</td>
        <td class="tg-9wq8">Cedula</td>
        <td class="tg-9wq8">Nombre</td>
        <td class="tg-9wq8">Fecha</td>
        <td class="tg-9wq8">Horas<br>Extras</td>
        <td class="tg-9wq8">Horas<br>de DLT<br></td>
        <td class="tg-9wq8">Motivo</td>
      </tr>
      '.$tabla.'
      <tr>
        <td class="tg-0pky" colspan="3">Subtotal</td>
        <td class="tg-9wq8">'.$total_st1.'</td>
        <td class="tg-9wq8">'.$total_dlt1.'</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky" colspan="3">Subtotal</td>
        <td class="tg-9wq8">'.$total_st2.'</td>
        <td class="tg-9wq8">'.$total_dlt2.'</td>
        <td class="tg-0pky"></td> 
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="4">SOLICITADO POR</td>
        <td class="tg-c3ow" colspan="4">REVISADO</td>
        <td class="tg-c3ow" colspan="4">AUTORIZADO</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="4"><br><br><br>AREVALO YUDISAY V-14509326<br>Gerente de mantenimiento</td>
        <td class="tg-pb0m" colspan="4"><br><br><br>Luis Verenzuela CI: 14.576.991<br>vicepresidente</td>
        <td class="tg-pb0m" colspan="4"><br><br><br><br>Gerente de Talento Humano</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="8" rowspan="2">Contando con las autorizaciones correspondientes se informa a la unidad <br>de "Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) indicado en esta planilla.</td>
        <td class="tg-0pky" colspan="4"></td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="4">Gerente de Talento Humano</td>
      </tr>
      ';

echo $html;     
echo '<INPUT id="cmdGuardar" type="button" value="Planilla EXCEL" class="btn btn-success" onclick="exportar_excel_cuadro();"/> ';
/*
// output the HTML content
$pdf->writeHTML($pdf_html, true, 0, true, 0);



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_004.pdf', 'I');
    	return $html;
//}
*/
/*
  $content = 'Prueba';

  $obj_pdf->writeHTML($content);
  $nombre = 'Archivo_'.date("d-m-Y H-i-s").'.pdf';
  $obj_pdf->Output($nombre, 'D');
  */
?>