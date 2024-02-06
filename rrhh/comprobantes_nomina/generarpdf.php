<?PHP
//session_start();
require_once('tcpdf/tcpdf.php');
require_once('libs/conexion_2.php');
//require_once('contador_reg.php');
$cnx_oracle= Conectarse_oracle();

$asunto = isset($_POST["asunto"])?$_POST["asunto"]:"NULL";    //
$cboTnomina= isset($_POST["cboTnomina"])?$_POST["cboTnomina"]:"";         //
$cboCnomina= isset($_POST["cboCnomina"])?$_POST["cboCnomina"]:"NULL";   // 
$cboMes= isset($_POST["cboMes"])?$_POST["cboMes"]:"";               // 
$cboAnho= isset($_POST["cboAnho"])?$_POST["cboAnho"]:"NULL";   // 
$txtdestino= isset($_POST["txtdestino"])?$_POST["txtdestino"]:"";
$cboTrabajador= isset($_POST["cboTrabajador"])?$_POST["cboTrabajador"]:"NULL";     // 
$txtTrabajador= isset($_POST["txtTrabajador"])?$_POST["txtTrabajador"]:"NULL";   //
$cboRlaboral= isset($_POST["cboRlaboral"])?$_POST["cboRlaboral"]:"NULL";   //
$txtexcepcion= isset($_POST["txtexcepcion"])?$_POST["txtexcepcion"]:"NULL";   //
$cboCcosto= isset($_POST["cboCcosto"])?$_POST["cboCcosto"]:"NULL";   //
$entregar= isset($_POST["entrega"])?$_POST["entrega"]:"NULL";
$clave= isset($_POST["clave"])?$_POST["clave"]:2;
$tienecorreo = isset($_POST["correo"])?$_POST["correo"]:3;


function conv_Dec($exp)
{ 
 $Cadena="";
 $exp = trim($exp);
 $Cadena = str_replace(".", "-",$exp);
 $Cadena = str_replace(",", ".",$Cadena);
 return str_replace("-", ",",$Cadena);
}

function conv_num($exp)
{ 
 $exp = trim($exp);
 $Cadena = str_replace(",", "" ,$exp);
 return $Cadena;
 //$Cadena = number_format($exp, 2, '.', '');
}

function dorso ($fil,$col)
{
$atras='<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">';
  for ($i=1; $i<=$fil; $i++)
    {   
        $atras=$atras.'<tr>';
        for ($j=1; $j<=$col; $j++)
            $atras=$atras.'<td><img src="images/MATESI_logo.jpg" width="90px" height="40px" /></td>';
        $atras=$atras.'</tr>';
    }
$atras=$atras.'</TABLE>';
return $atras;
}

function valida_email ($correo)
{
$correo=strtolower($correo);
if ((strpos($correo, 'briqven')!== false) || (strpos($correo, 'sidor.com')!== false))
    if (strpos($correo, 'briqven') !== false)
        $mail=$correo.".com.ve";
    else 
        $mail=$correo;
else
      $mail="";  
return $mail;
}

function formatear_fecha ($ddmmyy)
{
$dia=substr ($ddmmyy,0,2);  
$mes=substr ($ddmmyy,3,3);
$yy=substr ($ddmmyy,7,2);
switch ($mes) {
    case 'JAN':
        $mes = "Enero";
        break;
    case 'FEB':
        $mes = "Febrero";
        break;
    case 'MAR':
        $mes = "Marzo";
        break;
    case 'APR':
        $mes = "Abril";
        break;
    case 'MAY':
        $mes = "Mayo";
        break;                
    case 'JUN':
        $mes = "Jun";
        break;
    case 'JUL':
        $mes = "Julio";
        break;
    case 'AUG':
        $mes = "Agosto";
        break;
    case 'SEP':
        $mes = "Septiembre";
        break;
    case 'OCT':
        $mes = "Octubre";
        break;
    case 'NOV':
        $mes = "Noviembre";
        break;
    case 'DEC':
        $mes = "Diciembre";
        break;                        
}

return $dia.'-'.$mes.'-20'.$yy;
}


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
          //$this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
      }
  }


  function crear_pdf (){
    // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Matesi');
  $pdf->SetTitle('Comprobante de Pago');
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
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  // set some language-dependent strings (optional)
  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
  }
  //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetMargins(10, 8, 10, true);
  // set font
  $pdf->SetFont('courier', 'I', 7);

  return $pdf;
  }



  //////////-------------------------------
  /////////////----------------------------

$TB_ENC=$cboTnomina.$cboCnomina.$cboMes.$cboAnho;
$archivopdf="ATTACHMENT/".$TB_ENC."_".date('j_F_Y_H:i:s').".PDF";
//$TB_ENC="LMME122017";

    $query = "SELECT DISTINCT";
    $query = $query . "   E.TRABAJADOR TRABAJADOR,";  
    $query = $query . "   RTRIM(LTRIM(SUBSTR(E.NOMBRE,1,22))) NOMBRE,";
    $query = $query . "   E.CENTRO_COSTO CENTRO_COSTO,";
    $query = $query . "   E.RELACION_LABORAL RELACION_LABORAL,";
    $query = $query . "   E.TIPO_NOMINA TIPO_NOMINA,";
    $query = $query . "   E.UBICGEO UBICGEO,";
    $query = $query . "   TO_CHAR(E.RATA_HORA, '99999999999.99') RATA_HORA,";
    $query = $query . "   TO_CHAR(E.SUELDO_MENSUAL,'9999999999.99') SUELDO_MENSUAL,";
    $query = $query . "   E.INSTITUCION_DEPOSITO INSTITUCION_DEPOSITO,";
    $query = $query . "   E.CUENTA_DEPOSITO CUENTA_DEPOSITO,";
    $query = $query . "   E.PERIODO ,";
    $query = $query . "   E.FECHA,";
    $query = $query . "   E.NRO_CORRELATIVO,";
    $query = $query . "   E.DIRECCION,";
    $query = $query . "   E.GERENCIA,";
    $query = $query . "   E.COD_GER,";
    $query = $query . "   E.COD_SUP,";
    $query = $query . "   E.COD_GER_GRAL,";
    $query = $query . "   E.OFICINA,";
    $query = $query . "   T.E_MAIL, ";
    $query = $query . "   E.TIPO_NOMINA || ' ' || TO_CHAR(E.PERIODO,'00') || ' ' || TO_CHAR(FECHA, 'YYYY') || '-' || E.RELACION_LABORAL  REFERENCIA,";
    $query = $query . "   'ATTACHMENT/' || LTRIM(RTRIM(SUBSTR(T.E_MAIL, 1, INSTR(T.E_MAIL,'@', 1) -1))) || '-" . strtoupper(TRIM($TB_ENC)) . ".PDF' PDF,";
    $query = $query . "   LTRIM(RTRIM(SUBSTR(T.E_MAIL, 1, INSTR(T.E_MAIL,'@', 1) -1))) SIGLADO";
    $query = $query . "   ,PWD.DATO AS PWD";
    $query = $query . "   ,TO_CHAR(SYSDATE, 'DDMMYYYYHH24MISS') FECHA_HOY";
    $query = $query . "   ,G.FECHA_INGRESO";
    $query = $query . " FROM ";
    $query = $query . "     ENC_" . $TB_ENC . "  E ";
    $query = $query . "   , TRABAJADORES   T";
    $query = $query . "   , DET_" . $TB_ENC . " D";
    $query = $query . "   , TP_COMP_CORREO_SID PWD";
    $query = $query . "   , TRABAJADORES_GRALES G";
    $query = $query . " WHERE ";  
    $query = $query . " T.TRABAJADOR = E.TRABAJADOR ";
    $query = $query . "  AND E.TRABAJADOR = D.TRABAJADOR ";
    $query = $query . "  AND E.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND T.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = PWD.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = T.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = T.TRABAJADOR ";
    $query = $query . "  AND D.TRABAJADOR = G.TRABAJADOR ";
    //$query = $query . "  AND D.TRABAJADOR = '  16395343' ";
    
    // filtrar listado excluyendo algunos especificados en el campo de excepcion
    if (isset($_POST['chkexcepcion']))
        $query = $query . "   and TO_NUMBER(T.TRABAJADOR) NOT IN (" . $txtexcepcion . ")";    

    if ($tienecorreo < 3)
        if ($tienecorreo == 2){
       //para imprimir los comprobantes de trabajadores sin correos excluyendo a los q si tienen
           $query = $query . "  and (instr(ltrim(rtrim(t.e_mail)),'.com') = 0 ";
           $query = $query . "  and instr(ltrim(rtrim(t.e_mail)),'briqven') = 0 )";

        }   
        else {
       //para enviar imprimir los comprobantes de trabajadores con correos excluyendo a los que no tienen
           $query = $query . "  and (instr(ltrim(rtrim(t.e_mail)),'.com') <> 0 ";
           $query = $query . "  or instr(ltrim(rtrim(t.e_mail)),'briqven') <> 0 )";
       }
    
    //filtrar por centro de costo
    if (isset($_POST['chkCcosto']))
    {
      $query = $query . " and E.CENTRO_COSTO = " . $cboCcosto;
      $archivopdf="ATTACHMENT/".$TB_ENC.$_POST['cboCcosto']."_".date('j_F_Y_H:i:s').".PDF";
    } 

    //filtrar por trabajadores
    if (isset($_POST['chkTrabajador']))    
    {
        $query = $query . "  and TO_NUMBER(t.trabajador)  IN (" . $txtTrabajador . ")"; 
        
    }    
    
    if (isset($_POST['chkRlaboral']))   
    {
      $query = $query . "  and e.relacion_laboral = '" . $cboRlaboral . "'";
      $archivopdf="ATTACHMENT/".$TB_ENC.$_POST['cboRlaboral']."_".date('j_F_Y_H:i:s').".PDF";
    }
    
$stid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
oci_execute($stid);
$query1 = $query;
$query2 = "";
$count_reg=0;
/////////////////////////////////////////////////////////////
// create new PDF document  
$pdf = crear_pdf ();  
/////////////////////////////////////////////////////////////
//$totalregistros=contador_registros($query);
while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false) {   

   $count_reg++; // contador de registros 
   $cedtrabajador = $fila['TRABAJADOR'];

    if (isset($_POST['chkTrabajador']))    
    {
        $pos = strrpos($txtTrabajador, ",");
        if ($pos === false)
          $archivopdf=$fila['PDF'];
        else          
          $archivopdf="ATTACHMENT/".$TB_ENC."_".date('j_F_Y_H:i:s').".PDF";
        // Imprime algo como: 8_August_2005_15:12:46                 
    }  

   $rel_lab_trabajador="";
   if (trim($fila['RELACION_LABORAL'])=="B")
        $rel_lab_trabajador="CONVENIO";
   elseif (trim($fila['RELACION_LABORAL'])=="W")
        $rel_lab_trabajador="CONDUCCION";
   else
      $rel_lab_trabajador="CONTRATADO"; 

    $query = "select tipo_nomina || ' ' || descripcion as TIPO_NOM from tipos_nomina where tipo_nomina='".$fila['TIPO_NOMINA']."'";
    $qtid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
  oci_execute($qtid);
  $tipnom = oci_fetch_array($qtid, OCI_BOTH);

  ////////////Encriptar archivo////////////////////////////////////////////////
  if (($clave==1) && ($entregar==1))
    $pdf->SetProtection(array('modify','copy'), $fila['PWD'], null, 0, null);
  ////////////////////////////////////////////////////////////////////////////

   $nombre=$fila['NOMBRE'];
   $ci=$fila['TRABAJADOR'];
   $fecha=formatear_fecha($fila['FECHA']);
   $ccosto=trim($fila['CENTRO_COSTO']);   
   $periodo=trim($fila['PERIODO']);
   $TIPO_NOMINA=trim($tipnom['TIPO_NOM']);
   $mail=valida_email($fila['E_MAIL']);

   $cuerpo="<p>&nbsp;</p>Sr(a). ".$nombre." 
   Esta recibiendo en el adjunto, su comprobante de pago del ".$fecha.".
   Para observar su contenido debe ingresar el password que Ud. conoce.
   La confidencialidad de la informaci&oacute;n que por este medio reciba depende exclusivamente de Ud.  
   Si tiene alguna duda, problema, comentario o sugerencia, con gusto estaremos dispuestos para 
   atenderla, comunicandose con el Sr. Blas Gonzalez ext. 297.";      

    $salida = "";   

    $salida = $salida . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
    $salida = $salida . '<TR><TD style="vertical-align:middle; text-align:center" WIDTH="20%"><img align="center" width="90px" height="40px" src="images/MATESI_logo.jpg"><br>Rif.: G-200113111</TD><TD WIDTH="60%">' ;
    $salida = $salida . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
    $salida = $salida . '<TR><TD align="center">&nbsp;</TD></TR>' ;
    $salida = $salida . '<TR><TD align="center"><B>COMPROBANTE DE PAGO</B></TD></TR>' ;
    $salida = $salida . '<TR><TD align="center">&nbsp;</TD></TR>' ;
    $salida = $salida . '</TABLE></TD>' ;   
    
    $salida = $salida . '<TD WIDTH="20%">';

    $salida = $salida . '<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">' ;
    $salida = $salida . '<TR><TD align="center">&nbsp;</TD></TR>' ;
    $salida = $salida . '<TR><TD align="center">(Confidencial)</TD></TR>' ;
    $salida = $salida . '<TR><TD align="center">&nbsp;</TD></TR>' ;
    $salida = $salida . '</TABLE>' ;

    $salida = $salida . '</TD>' ;
    $salida = $salida . '</TR>' ;
    $salida = $salida . '</TABLE>' ;
    $salida = $salida . '<TABLE width="100%" BORDER="1">' ;
    
    $salida = $salida . '<tr bgcolor="#F0ECEC"><td width="15%"><B>Cedula</B></td><td width="30%"><B>Trabajador</B></td><td width="22%"><B>Email</B></td><td width="33%"><B>Fecha Ingreso</B></td></tr>';

    $salida = $salida . '<tr><td height="15px">'.trim($fila['TRABAJADOR']).'</td><td>'.$fila['NOMBRE'].'</td><td>'.$mail.'</td><td>'.formatear_fecha($fila['FECHA_INGRESO']).'</td></tr>' ;

    $salida = $salida . '<tr bgcolor="#F0ECEC"><td><B>Centro Costo</B></td><td><B>Rel. Laboral</B></td><td><B>Nomina</B></td><td><B>Fecha Pago</B></td></tr>';

    $salida = $salida . '<tr><td height="15px">'.$fila['CENTRO_COSTO'].'</td><td>'.$rel_lab_trabajador.'</td><td>'.$TIPO_NOMINA.'</td><td>'.$fecha.'</td></tr>' ;
  
    $salida = $salida . '<tr bgcolor="#F0ECEC"><td><B>Periodo</B></td><td><B>Cta. Bancaria</B></td><td><B>Sal./Dia</B></td><td><B>Tabulador</B></td></tr>';

    $salida = $salida . '<tr><td height="15px">'.$fila['PERIODO'].'</td><td>'.$fila['CUENTA_DEPOSITO'].'</td><td>'.number_format($fila['SUELDO_MENSUAL'], 2, ',', '.').'</td><td>'.number_format($fila['RATA_HORA'], 2, ',', '.').'</td></tr>' ;

    $salida = $salida . '</TABLE>' ;      

///////////TABLA DE DETALLES//////////////////////////////////////////////////////////////////////
    //$salida = $salida . '<p>&nbsp;</p>';
    $salida = $salida . '<br><br>';

   $salida = $salida . '<TABLE width="100%" BORDER="1">';
  
    $salida = $salida . '<tr bgcolor="#F0ECEC"><td width="5%"><B>Cptos.</B></td><td width="4%"><B>H/Dia</B></td><td width="6%"><B>Mes/Sem</B></td><td width="30%"><B>Descripcion Concepto</B></td><td width="11%"><B>Saldo</B></td><td width="11%"><B>Ingreso</B></td><td width="11%"><B>Apte. Emp.</B></td><td width="11%"><B>Ded. Ley</B></td><td width="11%"><B>Ded. Prnal.</B></td></tr>'; 

  $query = "SELECT";
  $query = $query . " REF.CONCEPTO,";
  $query = $query . " NRO_DIAS_HORAS,";
  $query = $query . " CANTIDAD,";
  $query = $query . " REF.DESCRIPCION,";
  $query = $query . " decode(rtrim(ltrim(to_char(saldo,'999,999,990.00'))),'0.00','',to_char(saldo,'999,999,990.00')) as SALDO,";
  $query = $query . " decode(rtrim(ltrim(to_char(decode(d.tipo_concepto,1,monto_asig,decode(d.tipo_concepto, 1, MONTO_ASIG, 0)),'999,999,990.00'))),'0.00','',to_char(decode(d.tipo_concepto,1,monto_asig,decode(d.tipo_concepto, 1, monto_asig, 0)),'999,999,990.00')) ASIG, ";
  $query = $query . " decode(rtrim(ltrim(to_char(monto_aporte,'999,999,990.00'))),'0.00','',to_char(monto_aporte,'999,999,990.00')) APORTE, ";
  $query = $query . " decode(rtrim(ltrim(to_char(decode(d.tipo_concepto,3, monto_asig,0),'999,999,990.00'))),'0.00','',to_char(decode(d.tipo_concepto,3, monto_asig,0),'999,999,990.00')) DEDUC_LEY, ";
  $query = $query . " decode(rtrim(ltrim(to_char(decode(d.tipo_concepto,4, monto_asig,0),'999,999,990.00'))),'0.00','',to_char(decode(d.tipo_concepto,4, monto_asig,0),'999,999,990.00')) DEDUC_PERSONAL, ";
  $query = $query . " TIPO_TRANSACCION,";
  $query = $query . " d.INDICADOR_MENSEM,";
  $query = $query . " d.TIPO_CONCEPTO";
  $query = $query . " FROM ";
  $query = $query . " DET_" . $TB_ENC . " REF";
  $query = $query . ", CONCEPTOS C";
  $query = $query . ", conceptos_comprobantes_sid d";
  $query = $query . " Where ";
  $query = $query . " trabajador = '" . $cedtrabajador . "'";
  $query = $query . " and c.concepto = ref.concepto ";
  $query = $query . " and c.concepto = d.concepto ";
  $query = $query . " Order By d.tipo_concepto,concepto, to_number(decode(length(rtrim(ltrim(cantidad))),0,'0',1,substr(cantidad,1),substr(cantidad,2))) ";

  $ptid = oci_parse($cnx_oracle,$query) or die("Error en la Consulta SQL:".$query);
  oci_execute($ptid);  
  $query2 = $query2."\n ".$query;
    $vTotalIngreso = 0;
        $vTotalEmpresa = 0;
        $vTotalDeduccionl = 0;
        $vTotalDeduccion = 0;
        $vMesSemana = " " ;       
        $contar_concepto = 0;

/*$cod_intepres="";
$desc_intepres="";
$saldo_pres = "";*/

  while (($fila2 = oci_fetch_array($ptid, OCI_BOTH)) != false) {

    $INDICADOR_MENSEM=trim($fila2['INDICADOR_MENSEM']);
    $TIPO_CONCEPTO=$fila2['TIPO_CONCEPTO'];
    //$CANTIDAD=trim($fila2['CANTIDAD']);
    $CANTIDAD=isset($fila2['CANTIDAD'])?$fila2['CANTIDAD']:NULL; 
    
    $SALDO=isset($fila2['SALDO'])?$fila2['SALDO']:NULL; 
    $ASIG=isset($fila2['ASIG'])?$fila2['ASIG']:NULL; 
    $APORTE=isset($fila2['APORTE'])?$fila2['APORTE']:NULL; 
    $DEDUC_LEY=isset($fila2['DEDUC_LEY'])?$fila2['DEDUC_LEY']:NULL; 
    $DEDUC_PERSONAL=isset($fila2['DEDUC_PERSONAL'])?$fila2['DEDUC_PERSONAL']:NULL; 

       if ($INDICADOR_MENSEM == "S")
              if (substr($CANTIDAD, 0, 1) == "A")
                  $vMesSemana = "01" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "B") 
                  $vMesSemana = "02" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "C")
                  $vMesSemana = "03" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "D") 
                  $vMesSemana = "04" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "E")
                  $vMesSemana = "05" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "F") 
                  $vMesSemana = "06" . "/". substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "G") 
                  $vMesSemana = "07" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "H") 
                  $vMesSemana = "08" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "I") 
                  $vMesSemana = "09" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "J") 
                  $vMesSemana = "10" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "K" )
                  $vMesSemana = "11" . "/" . substr($CANTIDAD, 1, 2);
               elseif (substr($CANTIDAD, 0, 1) == "L") 
                  $vMesSemana = "12" . "/" . substr($CANTIDAD, 1, 2);
               else
                  $vMesSemana = "&nbsp;";
               
         elseif ($INDICADOR_MENSEM == "M") 
                  if ((str_pad(trim($CANTIDAD), "00") == "01") && (($TIPO_CONCEPTO != 5) && ($TIPO_CONCEPTO != 0)))
                     $vMesSemana = "01";                  
                elseif (str_pad($CANTIDAD, "00") == "02") 
                   $vMesSemana = "02";
                elseif (str_pad($CANTIDAD, "00") == "03") 
                   $vMesSemana = "03";
                elseif (str_pad($CANTIDAD, "00") == "04") 
                   $vMesSemana = "04";
                elseif (str_pad($CANTIDAD, "00") == "05") 
                   $vMesSemana = "05";
                elseif (str_pad($CANTIDAD, "00") == "06") 
                   $vMesSemana = "06";
                elseif (str_pad($CANTIDAD, "00") == "07") 
                   $vMesSemana = "07";
                elseif (str_pad($CANTIDAD, "00") == "08" )
                   $vMesSemana = "08";
                elseif (str_pad($CANTIDAD, "00") == "09") 
                   $vMesSemana = "09";
                elseif (str_pad($CANTIDAD, "00") == "10") 
                   $vMesSemana = "10";
                elseif (str_pad($CANTIDAD, "00") == "11") 
                   $vMesSemana = "11";
                elseif (str_pad($CANTIDAD, "00") == "12" )
                   $vMesSemana = "12";
                else
                   $vMesSemana = " ";
          

        $rsA = "";
        $rsB = "";
        $rsC= "";
        $rsD = "";
        $rsE = "";

        $rsAi = 0;
        $rsBi = 0;
        $rsCi= 0;
        $rsDi = 0;
        $rsEi = 0;

        
        if (!is_null($SALDO))               
          {    $rsAi = conv_num($SALDO);
               $rsA = conv_Dec($SALDO);
           }        
        
        if (!is_null($ASIG))           
          {  $rsBi = conv_num($ASIG);
             $rsB = conv_Dec($ASIG);
           }        
       
        if (!is_null($APORTE))
           {
            $rsCi = conv_num($APORTE);
            $rsC = conv_Dec($APORTE);
           }        
       
        if (!is_null($DEDUC_LEY)) 
            {
              $rsDi = conv_num($DEDUC_LEY);
              $rsD = conv_Dec($DEDUC_LEY);
          }        
       
        if (!is_null($DEDUC_PERSONAL))             
            {    $rsEi = conv_num($DEDUC_PERSONAL);
                 $rsE = conv_Dec($DEDUC_PERSONAL);
             }
      /*if ($fila2['CONCEPTO']=="9092"){
        $cod_intepres=$fila2['CONCEPTO'];
        $desc_intepres=$fila2['DESCRIPCION'];
        $saldo_pres = $rsA;  
      }*/


      $salida = $salida . "<tr>";
      $salida = $salida . "<TD height='15px'>".$fila2['CONCEPTO']."</TD>";
      $salida = $salida . "<TD>".$fila2['NRO_DIAS_HORAS']."</TD>";
      $salida = $salida . "<TD>".$vMesSemana."</TD>";
      $salida = $salida . "<TD nowrap>".trim($fila2['DESCRIPCION'])."</TD>";
      $salida = $salida . "<TD>".$rsA."</TD>";
      $salida = $salida . "<TD>".$rsB."</TD>";
      $salida = $salida . "<TD>".$rsC."</TD>";
      $salida = $salida . "<TD>".$rsD."</TD>";
      $salida = $salida . "<TD>".$rsE."</TD>";
      $salida = $salida . "</tr>";
  
        $vTotalIngreso = $vTotalIngreso + $rsBi;
        $vTotalEmpresa = $vTotalEmpresa + $rsCi;
        $vTotalDeduccionl = $vTotalDeduccionl + $rsDi;
        $vTotalDeduccion = $vTotalDeduccion + $rsEi;
        $contar_concepto++;

    }

    $salida = $salida .' <tr bgcolor="#F0ECEC">';
    $salida = $salida .'<td ALIGN="RIGHT" colspan="5"><B>TOTALES:</B></td>';
    $salida = $salida .'<td><B>'.number_format($vTotalIngreso, 2, ',', '.').'</B></td>';
    $salida = $salida .'<td><B>'.number_format($vTotalEmpresa, 2, ',', '.').'</B></td>';
    $salida = $salida .'<td><B>'.number_format($vTotalDeduccionl, 2, ',', '.').'</B></td>';
    $salida = $salida .'<td><B>'.number_format($vTotalDeduccion, 2, ',', '.').'</B></td>';
    $salida = $salida .'</tr>';
    $salida = $salida .'<tr bgcolor="#F0ECEC">';
    $salida = $salida .'<td ALIGN="RIGHT" colspan="8"><B>TOTAL:</B></td>';
    $salida = $salida .'<td><B>'.number_format($vTotalIngreso - ($vTotalDeduccionl + $vTotalDeduccion), 2, ',', '.').'</B></td>';
    $salida = $salida .'</tr>';

    /*if ($cod_intepres!=""){
        $salida = $salida .'<tr bgcolor="#F0ECEC">';
        $salida = $salida .'<td ALIGN="RIGHT" colspan="8"><B>'.$desc_intepres.':</B></td>';
        $salida = $salida .'<td><B>'.$saldo_pres.'</B></td>';
        $salida = $salida .'</tr>';
    }*/
    $salida = $salida . "</table>";

    
   $salida = $salida . "<!-- NEW PAGE -->";
  //  $salida = $salida . "</body>";
  //  $salida = $salida . "</HTML>";
  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////CONVERTIR A PDF////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  // add a page
  $pdf->AddPage();

  // set some text to print

  $txt=utf8_encode($salida);   

  // print a block of text using Write()
  //$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

  // output the HTML content
  //$pdf->writeHTML($txt, true, 0, true, 0);
  $pdf->writeHTML($txt, false, 0, true, true, '');

  // ---------------------------------------------------------

  $atras=dorso (4,9);
  $atras=$atras.'<br>';
  $atras=$atras.'<br>';
  $atras=$atras.'<TABLE width="100%" border="1" cellspacing="0" cellpadding="0">';
  $atras=$atras.'<tr>';
  $atras=$atras.'<td colspan="4">';
  $atras=$atras.'<div align="center">';
  $atras=$atras.'<p><B>COMPROBANTE DE PAGO</B></p>';
  $atras=$atras.'<p><B>(CONFIDECIAL)</B></p>';
  $atras=$atras.'</div>';
  $atras=$atras.'</td>';
  $atras=$atras.'</tr>';
  $atras=$atras.'<tr>';
  $atras=$atras.'<td>Trabajador: <br><B>'.$nombre.'</B></td>';
  $atras=$atras.'<td>Cedula: <br><B>'.$ci.'</B></td>';
  $atras=$atras.'<td>Fecha Pago: <br><B>'.$fecha.'</B></td>';
  $atras=$atras.'<td>Centro Costo: <br><B>'.$ccosto.'</B></td>';
  $atras=$atras.'</tr>';
  $atras=$atras.'<tr>';
  $atras=$atras.'<td>Relacion Laboral: <br><B>'.$rel_lab_trabajador.'</B></td>';
  $atras=$atras.'<td>Nomina: <br><B>'.$TIPO_NOMINA.'</B></td>';
  $atras=$atras.'<td>Periodo: <br><B>'.$periodo.'</B></td>';
  $atras=$atras.'<td>&nbsp;</td>';
  $atras=$atras.'</tr>';
  $atras=$atras.'</TABLE>';
  $atras=$atras.'<br>';
  $atras=$atras.'<br>';
  $atras=$atras.dorso (18,9);

$pdf->AddPage();
$txt=utf8_encode($atras);
$pdf->writeHTML($txt, false, 0, true, true, '');

  //Close and output PDF document

  //============================================================+
  // END OF FILE
  //============================================================+
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////FIN DE CONVERTIR PDF//////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
 
}

$pdf->Output(dirname(__FILE__)."/".$archivopdf, 'F');

//pg_close($conexion);

$filetxt = fopen(dirname(__FILE__)."/ATTACHMENT/ultimo_query.txt", "w");
fwrite($filetxt, $query1 . PHP_EOL);
fwrite($filetxt, $query2 . PHP_EOL);
fclose($filetxt);

echo $count_reg.",".$archivopdf;
?>