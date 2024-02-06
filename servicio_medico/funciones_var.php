<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function construir_pdf_enviar($idcons, $cuerpo, $asunto, $email, $motiv){
	require_once('tcpdf/tcpdf.php');
	require_once('formato_entrada_salida_1.php');
	//require_once('enviodecorreos.php');

	class MYPDF extends TCPDF {
	public function Header() {
	      $this->SetFont('courier', 'B', 8);
	 }
	public function Footer() {         
	          $this->SetY(-15);         
	          $this->SetFont('courier', 'I', 8);         
	          $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	      }
	}	
	$pdf = crear_pdf ();  
	$pdf->AddPage();
	$txt=utf8_encode($cuerpo);   
	$pdf->writeHTML($txt, false, 0, true, true, '');
	$pdf->Output();
	$dirpdf=dirname(__FILE__)."/planillas/consult_".$idcons."_".date("YMdGHs").".pdf";
	$pdf->Output($dirpdf, 'F');
	require_once('enviodecorreos.php');
	return ENVIAR_CORREO($cuerpo,$asunto,$dirpdf,$email,$motiv);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function construir_pdf($idcons, $cuerpo){
	require_once('tcpdf/tcpdf.php');	
	class MYPDF extends TCPDF {
	public function Header() {
	      $this->SetFont('courier', 'B', 8);
	 }
	public function Footer() {         
	          $this->SetY(-15);         
	          $this->SetFont('courier', 'I', 8);         
	          $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	      }
	}	
	$pdf = crear_pdf ();  
	$pdf->AddPage();
	$txt=utf8_encode($cuerpo);   
	$pdf->writeHTML($txt, false, 0, true, true, '');
	//$pdf->Output();
	$dirpdf=dirname(__FILE__)."/planillas/consult_".$idcons."_".date("YMdGHs").".pdf";
	$pdf->Output($dirpdf, 'F');	
	return $dirpdf;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cuerpo_correo($idmov, $tipomov, $stamov, $email, $cc, $nombre){
	require_once('formato_entrada_salida_2.php');
	require_once('enviodecorreos.php');
	$asunto="Sist. Control de Acceso. Tiene una ".$tipomov." en ".$stamov."(S). Nro.: ".$idmov;
	$cuerpo="<p>&nbsp;</p><strong>Realizado por ".$nombre."</strong>";
	$cuerpo.="<br>Para Acceder al Sistema Presione en Siguiente Link: <A href='http://10.50.188.48/control_acceso/index.php'>Sist. Control de Acceso</A>";
	$cuerpo.=planilla_entrada_salida_html2 ($idmov);
	return ENVIAR_CORREO($cuerpo,$asunto,'',$email,$cc);
}
function nota_examen($id_consulta){
	require("include_conex.php");
	$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());	
$query="SELECT m.ci, m.fecha, m.motivo, c.idmotivo, m.cargo, m.departamento, m.paramedico, m.medico, m.nombre_completo, m.nombres_jefe, c.condicion, m.sexo, m.reposo FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
  $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
  $rowmorb = pg_fetch_array($resultmorb);

$desc_mot=$rowmorb['motivo'];
$motivo=$rowmorb['idmotivo'];
$nombre_completo=$rowmorb['nombre_completo'];
$mor_sex=$rowmorb['sexo'];
$mor_ci=$rowmorb['ci'];
$nom_paramedico=$rowmorb['paramedico'];
$nom_medico=$rowmorb['medico'];
$mor_cond=$rowmorb['condicion'];
$mor_fecha=substr($rowmorb['fecha'],0,16);
$mor_depar=$rowmorb['departamento'];
$mor_cargo=$rowmorb['cargo'];
$mor_nomjefe=$rowmorb['nombres_jefe'];
$mor_reposo=$rowmorb['reposo'];
pg_free_result($resultmorb);

if ($mor_sex=='M')
		$enc='<p>&nbsp;</p>Se le notifica que el Sr. ';
	else
		$enc='<p>&nbsp;</p>Se le notifica que la Sra. ';
	
	$cuerpo=$enc.'<strong>'.$nombre_completo.'</strong>, con Cedula de Identidad <strong>'.$mor_ci.'</strong>, se realiz&oacute; el examen correspondiente a <strong>'.$desc_mot.'</strong>.<br>';	
	
	if ($nom_medico == '')
		$atendio='param&eacute;dico ocupante <strong>'.$nom_paramedico.'</strong>';
	else
		$atendio='m&eacute;dico ocupante <strong>'.$nom_medico.'</strong>';
	
	if ($mor_cond=="APTO")
    $colorcondicion='<font color="green"><strong>'.$mor_cond.'</strong></font>';
  else
    $colorcondicion='<font color="red"><strong>'.$mor_cond.'</strong></font>';
  
  $cuerpo .= 'El cual est&aacute; en condici&oacute;n: '.$colorcondicion.' seg&uacute;n lo considerado en la consulta m&eacute;dica registrada en la fecha: <strong>'.$mor_fecha.'</strong> por el '.$atendio.'.<br>';

	if ($mor_sex=='M')
		$cuerpo .= 'Otros datos de inter&eacute;s sobre el trabajador:<br>';
	else	
		$cuerpo .= 'Otros datos de inter&eacute;s sobre la trabajadora:<br>';
	
	$cuerpo .= '<table><thead><tr><th>Departamento:</th><th>'.$mor_depar.'</th></tr>';
	$cuerpo .= '<tr><th>Cargo:</th><th>'.$mor_cargo.'</th></tr>';
	$cuerpo .= '<tr><th>Supervisor:</th><th>'.$mor_nomjefe.'</th></tr></thead></table>';
	$cuerpo .= '<br>';
	$cuerpo .= '<br>';
	$cuerpo .= 'Para m&aacute;s informaci&oacute;n por favor debe comunicarse con el &aacute;rea de Servicio M&eacute;dico a la Ext. Nro. 259.<p>&nbsp;</p>';

  return $cuerpo;

}

function nota_reposo($id_consulta){
  	require("include_conex.php");
	$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
	$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());	
	$query="SELECT m.ci, m.fecha, m.motivo, c.idmotivo, m.cargo, m.departamento, m.paramedico, m.medico, m.nombre_completo, m.nombres_jefe, c.condicion, m.sexo, m.reposo, c.resultado_eva FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
    $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
    $rowmorb = pg_fetch_array($resultmorb);

	$desc_mot=$rowmorb['motivo'];
	$motivo=$rowmorb['idmotivo'];
	$nombre_completo=$rowmorb['nombre_completo'];
	$mor_sex=$rowmorb['sexo'];
	$mor_ci=$rowmorb['ci'];
	$nom_paramedico=$rowmorb['paramedico'];
	$nom_medico=$rowmorb['medico'];
	$mor_cond=$rowmorb['condicion'];
	$mor_fecha=substr($rowmorb['fecha'],0,16);
	$mor_depar=$rowmorb['departamento'];
	$mor_cargo=$rowmorb['cargo'];
	$mor_nomjefe=$rowmorb['nombres_jefe'];
	$mor_reposo=$rowmorb['reposo'];
	$resultado_eva=$rowmorb['resultado_eva'];
	pg_free_result($resultmorb);
  
  if ($mor_sex=='M')
    $enc='<p>&nbsp;</p>Se le notifica que el Sr. ';
  else
    $enc='<p>&nbsp;</p>Se le notifica que la Sra. ';
  
  $cuerpo=$enc.'<strong>'.$nombre_completo.'</strong>, con Cedula de Identidad <strong>'.$mor_ci.'</strong>, acudi&oacute; a consulta en medicina laboral por: <strong>'.$desc_mot.'</strong>.<br>'; 
  
  if ($nom_medico == '')
    $atendio='param&eacute;dico ocupante <strong>'.$nom_paramedico.'</strong>';
  else
    $atendio='m&eacute;dico ocupante <strong>'.$nom_medico.'</strong>';
  
  if ($mor_reposo!="N/A")
    $colorcondicion='<font color="red"><strong>REPOSO POR '.$mor_reposo.'</strong></font>';  
  
  $cuerpo .= 'El cual est&aacute; en condici&oacute;n de '.$colorcondicion.' seg&uacute;n lo considerado en la consulta m&eacute;dica registrada en la fecha: <strong>'.$mor_fecha.'</strong> por el '.$atendio.'.<br>Cuyo diagnostico fue: <strong>'.$resultado_eva.'</strong>.<br>';

  if ($mor_sex=='M')
    $cuerpo .= 'Otros datos de inter&eacute;s sobre el trabajador:<br>';
  else  
    $cuerpo .= 'Otros datos de inter&eacute;s sobre la trabajadora:<br>';
  
  $cuerpo .= '<table><thead><tr><th>Departamento:</th><th>'.$mor_depar.'</th></tr>';
  $cuerpo .= '<tr><th>Cargo:</th><th>'.$mor_cargo.'</th></tr>';
  $cuerpo .= '<tr><th>Supervisor:</th><th>'.$mor_nomjefe.'</th></tr></thead></table>';
  $cuerpo .= '<br>';
  $cuerpo .= '<br>';
  $cuerpo .= 'Para m&aacute;s informaci&oacute;n por favor debe comunicarse con el &aacute;rea de Servicio M&eacute;dico a la Ext. Nro. 259.<p>&nbsp;</p>';

  return $cuerpo;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//print_r(datos_usuarios('matara',61803));
//echo construir_pdf_enviar(9149, 'prueba', 'probando', 'matlux@sidor.com', '');
?>