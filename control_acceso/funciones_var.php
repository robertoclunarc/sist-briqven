<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function datos_usuarios($login, $ccosto, $cn)
{
//require_once('libs/conexion.php');
//$cn=  Conectarse();
$firma=array();				
	$sql1 = "SELECT * FROM v_items_autorizados_gerencias WHERE permiso='S' AND login='".$login."'";
	$res1 = pg_query($cn,$sql1);
	$row1 = pg_fetch_array($res1, null, PGSQL_ASSOC);

	if (pg_num_rows($res1)>=1){	
		$firma['nombre_sol']=$row1['nombres'];
		$firma['ci_sol']=$row1['cedula'];
		$firma['cargo_sol']=$row1['cargo'];
		$firma['ccosto_sol']=$row1['fkunidad'];
		$firma['email_sol']=$row1['email'];
		$firma['login_sol']=$row1['login'];
		$firma['desccosto_sol']=$row1['descripcion_gerencia'];
		$firma['operacion_sol']='SOLICITADO';
		pg_free_result($res1);

	} else {

		$sql2 = "SELECT * FROM v_items_autorizados_ccostos WHERE ccosto=".$ccosto;
		$res2 = pg_query($cn,$sql2);
		$row2 = pg_fetch_array($res2, null, PGSQL_ASSOC);
		$firma['nombre_sol']=$row2['nombres'];
		$firma['ci_sol']=$row2['cedula'];
		$firma['cargo_sol']=$row2['cargo'];
		$firma['ccosto_sol']=$row2['gerencia'];
		$firma['email_sol']=$row2['email'];
		$firma['login_sol']=$row2['login'];
		$firma['desccosto_sol']=$row2['descripcion_gerencia'];
		$firma['operacion_sol']='SOLICITADO';
		pg_free_result($res2);
	}

	if ($login!=$firma['login_sol']){

		$sql3 = "SELECT * FROM v_usuarios_unidades WHERE login='".$login."'";
		$res3 = pg_query($cn,$sql3);
		$row3 = pg_fetch_array($res3, null, PGSQL_ASSOC);

		$firma['nombre_pen']=$row3['nombres'];
		$firma['ci_pen']=$row3['cedula'];
		$firma['cargo_pen']=$row3['cargo'];
		$firma['ccosto_pen']=$row3['fkunidad'];
		$firma['email_pen']=$row3['email'];
		$firma['login_pen']=$row3['login'];
		$firma['desccosto_pen']=$row3['descripcion_unidad'];
		$firma['operacion_pen']='PENDIENTE';
		pg_free_result($res3);
	}

	$sql4 = "SELECT * FROM v_items_autorizados_gcia_grales WHERE permiso='C' AND dependientes=".$firma['ccosto_sol'];
	$res4 = pg_query($cn,$sql4);
	$row4 = pg_fetch_array($res4, null, PGSQL_ASSOC);	

	$firma['nombre_conf']=$row4['nombres'];
	$firma['ci_conf']=$row4['cedula'];
	$firma['cargo_conf']=$row4['cargo'];
	$firma['ccosto_conf']=$row4['ccosto_gral'];
	$firma['email_conf']=$row4['email'];
	$firma['login_conf']=$row4['login'];
	$firma['desccosto_conf']=$row4['descripcion_ggral'];
	$firma['operacion_conf']='CONFORMADO';
	pg_free_result($res4);

	return $firma;
}
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
function construir_pdf_enviar($idmov, $cuerpo, $asunto, $email, $cc){
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
	$txt=utf8_encode(planilla_entrada_salida_html($idmov));   
	$pdf->writeHTML($txt, false, 0, true, true, '');
	//$pdf->Output();
	$dirpdf=dirname(__FILE__)."/planillas/movimiento_".$idmov."_".date("YMdGHs").".pdf";
	$pdf->Output($dirpdf, 'F');
	require_once('enviodecorreos.php');
	return ENVIAR_CORREO($cuerpo,$asunto,$dirpdf,$email,$cc);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cuerpo_correo($idmov, $tipomov, $stamov, $email, $cc, $nombre){
	require_once('formato_entrada_salida_2.php');
	require_once('enviodecorreos.php');
	$asunto="Sist. Control de Acceso. Tiene un Movimiento ".$tipomov." en ".$stamov."(S). Nro.: ".$idmov;
	$cuerpo="<p>&nbsp;</p><strong>Realizado por ".$nombre."</strong>";
	$cuerpo.="<br>Para Acceder al Sistema Presione en Siguiente Link: <A href='http://10.50.188.48/control_acceso/index.php'>Sist. Control de Acceso</A>";
	$cuerpo.=planilla_entrada_salida_html2 ($idmov);
	return ENVIAR_CORREO($cuerpo, $asunto, '', $email, $cc);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function enviar_alerta($titulo, $cuerpocomp, $email, $cc, $nombre){	
	require_once('enviodecorreos.php');
	$asunto="Sist. Control de Acceso. ".$titulo;
	$cuerpo="<p>&nbsp;</p><strong>Realizado por ".$nombre."</strong><br>";
	$cuerpo.=$cuerpocomp;
	$cuerpo.="<br>Para Acceder al Sistema Presione en Siguiente Link: <A href='http://10.50.188.48/control_acceso/index.php'>Sist. Control de Acceso</A>";	
	return ENVIAR_CORREO($cuerpo, $asunto, '', $email, $cc);
}
////////////////////////////////////////////////////////////////////////////////////////
function notificar_estatus($idmov, $tipomov, $stamov, $email, $cc, $nombre){
	
	require_once('enviodecorreos.php');
	$asunto="Sist. Control de Acceso. Cambio de Estatus del Movimiento ".$tipomov." Nro.: ".$idmov;
	$cuerpo="<p>&nbsp;</p><strong>Se le notifica que el Movimiento de ".$tipomov." Nro. ".$idmov;
	$cuerpo.=" fue ".$stamov." por ".$nombre.".</strong>";
	$cuerpo.="<br>Para Acceder al Sistema Presione en Siguiente Link: <A href='http://10.50.188.48/control_acceso/index.php'>Sist. Control de Acceso</A>";	
	return ENVIAR_CORREO($cuerpo, $asunto, '', $email, $cc);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function notificar_noretornado($movim){	
	require_once('enviodecorreos.php');
	require_once('libs/conexion.php');
	$conn=Conectarse();
	$asunto="Sist. Control de Acceso. Materiales y/o Suministros NO Retornados";
	$cuerpo="<p>&nbsp;</p><strong>Se le notifica que los Movimientos con condicion de Retorno aun no han sido devueltos a sus destinos: <br>".$movim;	
	$cuerpo.="<br>Para Acceder al Sistema Presione en Siguiente Link: <A href='http://10.50.188.48/control_acceso/index.php'>Sist. Control de Acceso</A>";	
	$query="SELECT email
  FROM usuarios_movimientos
where fkmovimiento_part in (SELECT   idmovimiento
FROM movimientos
WHERE   estatus = 'VALIDADO' AND 
  retorna = 'SI' AND 
  fecha_retorno <= now() AND
  ciclo in ('ENTRADA PEND RETORNO', 'SALIDA PEND RETORNO'))
union all
SELECT email FROM  usuarios WHERE nivel = 7 AND fkunidad = 61606 AND estatus = 'ACTIVO';";
	$result1 = pg_query($conn, $query) or die("Error en la Consulta SQL: " . $query);
	$cont=pg_num_rows($result1);
	while ($fila1=pg_fetch_array($result1, null, PGSQL_ASSOC)){
		$env= ENVIAR_CORREO($cuerpo, $asunto, '', $fila1['email'], '');		
	}
	return $cont;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function enviar_nota($idmov, $tipomov, $stamov, $cc, $nombre, $operacion, $formato){
	require_once('libs/conexion.php');
	$conn=Conectarse();	
	$query="SELECT login_participante, email, nombre, operacion, estatus FROM usuarios_movimientos WHERE operacion='".$operacion."' AND fkmovimiento_part= ".$idmov;
	if ($operacion=='')
		$query="SELECT login_participante, email, nombre, operacion, estatus FROM usuarios_movimientos WHERE  fkmovimiento_part= ".$idmov;
	elseif ($operacion=='nota_patrimonial')
		$query="SELECT email FROM  usuarios WHERE nivel = 7 AND fkunidad = 61606 AND estatus = 'ACTIVO';";
	$result1 = pg_query($conn, $query) or die("Error en la Consulta SQL: " . $query);
	if (pg_num_rows($result1)>0)
		while ($fila1=pg_fetch_array($result1, null, PGSQL_ASSOC)){
			if ($formato=='planilla')
				cuerpo_correo($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
			else
				notificar_estatus($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
		}
	pg_free_result($result1);
	//pg_close($conn);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function notif_vigilantes($idmov, $tipomov, $stamov, $cc, $nombre, $formato){
	require_once('libs/conexion.php');
	$conn=Conectarse();	
	$query="SELECT login, email, nombres, estatus, cedula, cargo,  fkunidad FROM usuarios WHERE fkunidad = 61606 AND estatus='ACTIVO'";
	$result1 = pg_query($conn, $query) or die("Error en la Consulta SQL:" . $query);
	if (pg_num_rows($result1)>0)
		while ($fila1=pg_fetch_array($result1)){
			if ($formato=='planilla')
				cuerpo_correo($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
			else
				notificar_estatus($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
			//sleep(3);
		}
	pg_free_result($result1);
	//pg_close($conn);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function anular_mov($idmov, $tipomov, $stamov, $cc, $nombre, $formato){
	require_once('libs/conexion.php');
	$conn=Conectarse();
	$query="SELECT email FROM usuarios_movimientos WHERE estatus<>'' AND fkmovimiento_part = ".$idmov;	
	$result1 = pg_query($conn, $query) or die("Error en la Consulta SQL:" . $query);
	if (pg_num_rows($result1)>0)
		while ($fila1=pg_fetch_array($result1))
			if ($formato=='planilla')
				cuerpo_correo($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
			else
				notificar_estatus($idmov, $tipomov, $stamov, $fila1['email'], $cc, $nombre);
	pg_free_result($result1);
	//pg_close($conn);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function fecha_max_retorno_material($idmov, $material, $cn)
{
	$sql1 = "SELECT MAX(fecha_retorno) AS fechare FROM detalles_movimientos_retornos WHERE cantidad::numeric>0 AND fkmovimiento=".$idmov." AND descripcion='".$material."'";
	$res1 = pg_query($cn,$sql1);
	$row1 = pg_fetch_array($res1, null, PGSQL_ASSOC);
	$fecha=$row1['fechare'];
return $fecha;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function exist_usuario($idmov, $usuario, $cn)
{
	$sql1 = "SELECT operacion, estatus FROM usuarios_movimientos WHERE operacion='SOLICITADO' AND fkmovimiento_part=".$idmov." AND login_participante='".$usuario."'";
	$res1 = pg_query($cn,$sql1);	
	$rows = pg_num_rows($res1);
	if ($rows>0)
		return TRUE;
	else
		return FALSE;
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//print_r(datos_usuarios('matara',61803));
//echo construir_pdf_enviar(9149, 'prueba', 'probando', 'matlux@sidor.com', '');
?>