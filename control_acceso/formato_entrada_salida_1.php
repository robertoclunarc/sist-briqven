<?php
function planilla_entrada_salida_html ($idmov){

require_once('libs/conexion.php');
$cn=Conectarse();
////////Movimiento
$listado1=pg_query($cn,"SELECT m.*, i.descripcion_operacion FROM movimientos m, items_autorizados i where i.iditem=m.objetivo_movimiento and idmovimiento=".$idmov." ");
$reg1 = pg_fetch_array($listado1, null, PGSQL_ASSOC);
///////////usuario solicitante
$listado2=pg_query($cn,"SELECT * FROM usuarios_movimientos where operacion='SOLICITADO' and estatus='SOLICITADO' and fkmovimiento_part=".$idmov);
$reg2 = pg_fetch_array($listado2, null, PGSQL_ASSOC);
/////////////usuario conformante
$listado3=pg_query($cn,"SELECT * FROM usuarios_movimientos where operacion='CONFORMADO' and estatus='CONFORMADO' and fkmovimiento_part=".$idmov);
$reg3 = pg_fetch_array($listado3, null, PGSQL_ASSOC);
//////////////////////usuario autorizante
$listado4=pg_query($cn,"SELECT * FROM usuarios_movimientos where operacion='AUTORIZADO' and estatus='AUTORIZADO' and fkmovimiento_part=".$idmov);
$reg4 = pg_fetch_array($listado4, null, PGSQL_ASSOC);
//////////////////////usuario validador
$listado5=pg_query($cn,"SELECT * FROM usuarios_movimientos where operacion='VALIDADO' and estatus='VALIDADO' and fkmovimiento_part=".$idmov);
$reg5 = pg_fetch_array($listado5, null, PGSQL_ASSOC);
///////////////////detalles de movimiento
$listado6=pg_query($cn,"SELECT * FROM detalles_movimientos where fkmovimiento=".$idmov." order by items");
///////////////////detalles de movimiento retorno
$listado7=pg_query($cn,"SELECT * FROM detalles_movimientos_retornos where fkmovimiento=".$idmov." and cantidad::numeric > 0 order by fecha_retorno");
$rows7 = pg_num_rows($listado7);

$nombre4=$reg4['nombre'];
$cedula4=$reg4['cedula'];
$cargo4=$reg4['cargo'];
$nombre3=$reg3['nombre'];
$cedula3=$reg3['cedula'];
$cargo3=$reg3['cargo'];
$nombre2=$reg2['nombre'];
$cedula2=$reg2['cedula'];
$cargo2=$reg2['cargo'];
$nombre5=$reg5['nombre'];
$cedula5=$reg5['cedula'];
$cargo5=$reg5['cargo'];

$html='';
$html.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html.='<html xmlns="http://www.w3.org/1999/xhtml">';
$html.='<head>';
	$html.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$html.='<title>Documento sin título</title>';
$html.='</head>';
$html.='<body>';
	$html.='<table width="100%" border="0">';
		$html.='<tr>';
			$html.='<td width="25%" align="left" valign="middle"><img src="images/CVG.gif" width="100" height="50"/></td>';
			$html.='<td width="50%" align="center"><img src="images/encabezado.png" width="700" height="200"/></td>';
			$html.='<td width="25%" align="right" valign="middle"><img src="images/matesi_briqven.jpg" width="150" height="50" /></td>';
		$html.='</tr>';
	$html.='</table>';
	//$html.='<p>&nbsp;</p>';
	$html.='<table width="100%" border="0">';
		$html.='<tr>';
			$html.='<td colspan="3" align="center" valign="middle"><strong>CONTROL DE SALIDA E INGRESO DE MATERIALES Y SUMINISTROS</strong></td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td>&nbsp;</td>';
			$html.='<td>&nbsp;</td>';
			$html.='<td align="right"><strong>FECHA: '.substr ($reg1['fecha_hora'],0,10).'</strong></td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td>&nbsp;</td>';
			$html.='<td>&nbsp;</td>';
			$html.='<td align="right"><strong>CODIGO: '.$reg1['idmovimiento'].'</strong></td>';
		$html.='</tr>';
	$html.='</table>';
	$html.='<br>';
$html.='<table width="100%" border="1" cellspacing="0" bordercolor="#000000" cellpadding="0">';
		$html.='<tr>';
			$html.='<td width="50%" colspan="2" align="center" valign="middle" bgcolor="#999999"><strong>UNIDAD SOLICITANTE</strong></td>';
		    $html.='<td width="50%" colspan="2" align="center" valign="middle" bgcolor="#999999" ><strong>UNIDAD QUE AUTORIZA</strong></td>';
  $html.='</tr>';
		$html.='<tr>';
			$html.='<td  nowrap width="50%" colspan="2" align="left" valign="middle">'.$reg2['unidad'].'</td>';
			$html.='<td  nowrap width="50%" colspan="2" valign="middle">'.$reg3['unidad'].'</td>';
		$html.='</tr>';
    
    $html.='<tr>';
          $html.='<td bgcolor="#999999" align="center" valign="middle" ><strong>MOTIVO DE LA '.$reg1['tipo_movimiento'].'</strong></td>';
          $html.='<td bgcolor="#999999" align="center" valign="middle" ><strong>REGRESA</strong></td>';
          $html.='<td bgcolor="#999999" align="center" valign="middle" ><strong>NRO. ORDEN COMPRA</strong></td>';
          $html.='<td bgcolor="#999999" align="center" valign="middle" ><strong>FECHA RETORNO</strong></td>';
    $html.='</tr>';
    $html.='<tr>';
          $html.='<td align="left" valign="middle" >'.$reg1['descripcion_operacion'].'</td>';
          $html.='<td align="center" valign="middle" >'.$reg1['retorna'].'</td>';
          $html.='<td align="center" valign="middle" >'.$reg1['orden_compra'].'</td>';
          $html.='<td align="center" valign="middle" >'.$reg1['fecha_retorno'].'</td>';
    $html.='</tr>';
    
		$html.='<tr>';
			$html.='<td colspan="4" align="center" valign="middle" bgcolor="#999999"><strong>DESTINATARIO</strong></td>';
		$html.='</tr>';
		$html.='<tr>';
		  $html.='<td colspan="2" align="left" valign="middle">NOMBRE:<br>'.$reg1['nombre_destinatario'].'</td>';
		  $html.='<td colspan="2" align="left" valign="middle">DIRECCION:<br>'.$reg1['destino'].'</td>';
  $html.='</tr>';
		$html.='<tr>';
		  $html.='<td colspan="4" align="center" valign="middle" bgcolor="#999999" ><strong>PERSONA DE CONTACTO</strong></td>';
  $html.='</tr>';
		$html.='<tr bgcolor="#CCCCCC">';
		  $html.='<td align="center" valign="middle">Nombre y Apellido</td>';
		  $html.='<td align="center" valign="middle">C.I.</td>';
		  $html.='<td align="center" valign="middle">Telefono</td>';
		  $html.='<td align="center" valign="middle">Unidad de Adscripcion</td>';
  $html.='</tr>';  
		$html.='<tr>';
			    $html.='<td align="center" valign="middle" >'.$reg1['nombre_contacto'].'</td>';
	        $html.='<td align="center" valign="middle" >'.$reg1['cedula_contacto'].'</td>';
	        $html.='<td align="center" valign="middle" >'.$reg1['tlf_contacto'].'</td>';
	        $html.='<td align="center" valign="middle" >'.$reg1['unidad_adscripcion'].'</td>';
		$html.='</tr>';
$html.='</table>';
 
$html.='<table width="100%" border="1" cellspacing="0" bordercolor="#000000" cellpadding="0">';
		$html.='<tr>';
		  $html.='<td colspan="6" align="center" valign="middle" bgcolor="#999999"><strong>'.$reg1['tipo_movimiento'].'</strong></td>';
  $html.='</tr>';
		$html.='<tr>';
		  $html.='<td colspan="6" align="center" valign="middle" bgcolor="#CCCCCC"><strong>DATOS DEL VEHICULO QUE TRANSPORTA Y DEL CONDUCTOR</strong></td>';
  $html.='</tr>';
		$html.='<tr>';
		  $html.='<td width="16%" align="center" valign="middle">MARCA</td>';
		  $html.='<td width="16%" align="center" valign="middle">MODELO</td>';
		  $html.='<td width="16%" align="center" valign="middle">COLOR</td>';
		  $html.='<td width="16%" align="center" valign="middle">PLACA</td>';
		  $html.='<td width="20%" nowrap align="center" valign="middle">CONDUCTOR</td>';
		  $html.='<td width="16%" align="center" valign="middle">C.I.</td>';
  $html.='</tr>';
		$html.='<tr>';
			$html.='<td align="left" valign="middle">'.$reg1['marca'].'</td>';
			$html.='<td align="left" valign="middle">'.$reg1['modelo'].'</td>';
			$html.='<td align="left" valign="middle">'.$reg1['colores'].'</td>';
			$html.='<td align="left" valign="middle">'.$reg1['placa'].'</td>';
			$html.='<td nowrap align="left" valign="middle">'.$reg1['conductor'].'</td>';
			$html.='<td align="left" valign="middle">'.$reg1['cedula'].'</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="6" align="center" valign="middle" bgcolor="#999999"><strong>DESCRIPCION DE LOS BIENES, PIEZAS, MATERIALES, HERRAMIENTAS Y OTROS</strong></td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td width="10%" align="left" valign="middle"><strong>Item</strong></td>';
      $html.='<td width="10%" align="left" valign="middle"><strong>Cant.</strong></td>';
      $html.='<td width="10%" align="left" valign="middle"><strong>Und.</strong></td>';
      $html.='<td width="20%" align="left" valign="middle" nowrap="nowrap"><strong>Nro Vale Alm. o Serial</strong></td>';
      $html.='<td width="50%" colspan="2" align="left" valign="middle"><strong>Descripcion</strong></td>';
		$html.='</tr>';
    while ($reg6 = pg_fetch_array($listado6, null, PGSQL_ASSOC)){
		$html.='<tr>';
			$html.='<td style="border-right:none; border-top:none; border-bottom:none;" align="left" valign="middle">'.$reg6['items'].'</td>';
			$html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg6['cantidad'].'</td>';
			$html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg6['unidad_medicion'].'</td>';
			$html.='<td  style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg6['serial_nro_almacen'].'</td>';
			$html.='<td style="border-top:none; border-bottom:none; border-left:none" colspan="2" align="left" valign="middle">'.$reg6['descripcion'].'</td>';
		$html.='</tr>';
    }
		$html.='<tr>';
			$html.='<td colspan="6" align="left" valign="middle"><strong>Observaciones:<br></strong>'.$reg1['observaciones'].'</td>';
		$html.='</tr>';    
$html.='</table>';
if ($rows7>0){
  $tipo_retorno='SALIDA';
  if ($reg1['tipo_movimiento']=='SALIDA')
      $tipo_retorno='ENTRADA';
  $html.='<table width="100%" border="1" cellspacing="0" bordercolor="#000000" cellpadding="0">';
      $html.='<tr>';
        $html.='<td colspan="6" align="center" bgcolor="#999999"><strong>'.$tipo_retorno.'</strong></td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td colspan="6" align="center" bgcolor="#CCCCCC"><strong>DESCRIPCION DE LOS BIENES, PIEZAS, MATERIALES, HERRAMIENTAS Y OTROS</strong></td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td width="10%" align="left" valign="middle"><strong>Item</strong></td>';
      $html.='<td width="10%" align="left" valign="middle"><strong>Cant.</strong></td>';
      $html.='<td width="10%" align="left" valign="middle"><strong>Und.</strong></td>';
      $html.='<td width="20%" align="left" valign="middle" nowrap="nowrap"><strong>Nro Vale Alm. o Serial</strong></td>';      
      $html.='<td width="40%" align="left" valign="middle"><strong>Descripcion</strong></td>';
      $html.='<td width="10%" align="left" valign="middle"><strong>Fecha Retorno</strong></td>';
      $html.='</tr>';
       while ($reg7 = pg_fetch_array($listado7, null, PGSQL_ASSOC)){
    $html.='<tr>';
      $html.='<td style="border-right:none; border-top:none; border-bottom:none;" align="left" valign="middle">'.$reg7['items'].'</td>';
      $html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg7['cantidad'].'</td>';
      $html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg7['unidad_medicion'].'</td>';
      $html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg7['serial_nro_almacen'].'</td>';
      $html.='<td style="border-right:none; border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.$reg7['descripcion'].'</td>';
      $html.='<td style="border-top:none; border-bottom:none; border-left:none" align="left" valign="middle">'.substr ($reg7['fecha_retorno'],0, 16).'</td>';
    $html.='</tr>';
    }
      $html.='<tr>';
        $html.='<td colspan="6"><strong>Observaciones:</strong><br></td>';
      $html.='</tr>';
    $html.='</table>';
  }
	$html.='<table width="100%" border="1" cellspacing="0" bordercolor="#000000" cellpadding="0">';
      $html.='<tr align="center" bgcolor="#999999">';
        $html.='<td width="16%" style="border-bottom: none;" ><strong>AUTORIZADO</strong></td>';
        $html.='<td width="16%" style="border-bottom: none;" nowrap="nowrap"><strong>GERENCIA GENERAL</strong></td>';
        $html.='<td width="16%" style="border-bottom: none;"  nowrap="nowrap"><strong>UNIDAD SOLICITANTE</strong></td>';
        $html.='<td width="16%" style="border-bottom: none;"  nowrap="nowrap"><strong>BIENES PATRIMONIALES</strong></td>';
        $html.='<td width="16%" style="border-bottom: none;" nowrap="nowrap"><strong>PROTECCION DE PLANTA</strong></td>';
        $html.='<td width="20%"  style="border-bottom: none;" ><strong>RECIBE</strong></td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;'.$nombre4.'<br>';
        $html.='C.I.:&nbsp;'.$cedula4.'<br>';
        $html.='Cargo:&nbsp;'.$cargo4.'<br>';
        if ($cedula4!="")
          $html.='Firma:<br>&nbsp;<img src="images/firmas_autorizadas/'.$cedula4.'.png" width="110" height="100"/>';
        $html.='</td>';

        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;'.$nombre3.'<br>';
        $html.='C.I.:&nbsp;'.$cedula3.'<br>';
        $html.='Cargo:&nbsp;'.$cargo3.'<br>';
        if ($cedula3!="")
          $html.='Firma:<br>&nbsp;<img src="images/firmas_autorizadas/'.$cedula3.'.png" width="110" height="100"/>';
        $html.='</td>';

        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;'.$nombre2.'<br>';
        $html.='C.I.:&nbsp;'.$cedula2.'<br>';
        $html.='Cargo:&nbsp;'.$cargo2.'<br>';
        if ($cedula2!="")
          $html.='Firma:<br>&nbsp;<img src="images/firmas_autorizadas/'.$cedula2.'.png" width="110" height="100"/>';
        $html.='</td>';        

        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;<br>';
        $html.='C.I.:&nbsp;<br>';
        $html.='Cargo:&nbsp;<br>';
        $html.='Firma:<br>&nbsp;';
        $html.='</td>';

        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;'.$nombre5.'<br>';
        $html.='C.I.:&nbsp;'.$cedula5.'<br>';
        $html.='Cargo:&nbsp;'.$cargo5.'<br>';
        $html.='Firma:<br>&nbsp;';
        $html.='</td>';

        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">';
        $html.='Nombre:&nbsp;<br>';
        $html.='C.I.:&nbsp;<br>';
        $html.='Cargo:&nbsp;<br>';
        $html.='Firma:<br>&nbsp;';
        $html.='</td>';

        /*$html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Nombre:&nbsp;'.$reg3['nombre'].'</td>';  
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Nombre:&nbsp;'.$reg2['nombre'].'</td>';  
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Nombre:&nbsp;</td>';  
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Nombre:&nbsp;'.$reg5['nombre'].'</td>';  
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none;">Nombre:&nbsp;</td>'; 
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;'.$reg4['cedula'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;'.$reg3['cedula'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;'.$reg2['cedula'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;</td>';
        $html.='<td valign="top" valign="top" style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;'.$reg5['cedula'].'</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:&nbsp;</td>';
      $html.='</tr>'; 
      $html.='<tr>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;'.$reg4['cargo'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;'.$reg3['cargo'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;'.$reg2['cargo'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;'.$reg5['cargo'].'</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Cargo:&nbsp;</td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td valign="top" style="border-right:none; border-top:none; border-bottom:none">Firma:<img src="images/firmas_autorizadas/'.$reg4['cedula'].'.png" width="150" height="110"/></td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Firma:<img src="images/firmas_autorizadas/'.$reg3['cedula'].'.png" width="150" height="110"/></td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Firma:<img src="images/firmas_autorizadas/'.$reg2['cedula'].'.png" width="150" height="110"/></td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
        $html.='<td valign="top" style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';*/
      $html.='</tr>';
    $html.='</table>';
  
	/*$html.='<br>';
  $html.='<table width="100%" border="1" cellspacing="0" bordercolor="#000000" cellpadding="0">';
      $html.='<tr align="center" bgcolor="#999999">';
        $html.='<td style="border-bottom: none;" ><strong>AUTORIZADO</strong></td>';
        $html.='<td style="border-bottom: none;"  nowrap="nowrap"><strong>GERENCIA GENERAL</strong></td>';
        $html.='<td style="border-bottom: none;" nowrap="nowrap"><strong>UNIDAD SOLICITANTE</strong></td>';
        $html.='<td style="border-bottom: none;"  nowrap="nowrap"><strong>BIENES PATRIMONIALES</strong></td>';
        $html.='<td style="border-bottom: none;"  nowrap="nowrap"><strong>PROTECCION DE PLANTA</strong></td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Nombre:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Nombre:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Nombre:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Nombre:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Nombre:</td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">C.I.:</td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Cargo:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Cargo:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Cargo:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Cargo:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Cargo:</td>';
      $html.='</tr>';
      $html.='<tr>';
        $html.='<td style="border-right:none; border-top:none; border-bottom:none">Firma:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
        $html.='<td style="border-bottom: none; border-right:none; border-top:none">Firma:</td>';
      $html.='</tr>';
    $html.='</table>';*/
  $html.='</body>';
  $html.='</html>';
    pg_free_result($listado1);
    pg_free_result($listado2);
    pg_free_result($listado3);
    pg_free_result($listado4);
    pg_free_result($listado5);
    pg_free_result($listado6);
    pg_free_result($listado7);
    //pg_close($cn);
return $html;
}
//echo planilla_entrada_salida_html (9149);
?>