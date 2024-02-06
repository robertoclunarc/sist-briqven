<?php
function formato_historia_htlm($idh,$cd,$idp)
{
  $idhistoria=$idh;
  $ced="'".$cd."'"; 
  $idpac=$idp;
  require("include_conex.php");
  $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
  $cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
  $Qryhistoria=pg_query($cn,"SELECT * FROM v_historias WHERE uid_historia=".$idhistoria);
  $QrycargoAnt=pg_query($cn,"SELECT * FROM tbl_cargos_anteriores WHERE fk_paciente=".$idpac);
  $QrycargoOtr=pg_query($cn,"SELECT * FROM tbl_cargos_anteriores_otras WHERE fk_paciente=".$idpac);
  $QryAntFamil=pg_query($cn,"SELECT * FROM v_antecedentes_famil WHERE fk_paciente=".$idpac);
  $QryRiesgExp=pg_query($cn,"SELECT * FROM v_riesgos WHERE cedula=".$ced);
  $QryExamFun1=pg_query($cn,"SELECT tipo FROM v_examen_funcional WHERE cedula=".$ced);
  $QryExamFun2=pg_query($cn,"SELECT * FROM v_examen_funcional WHERE cedula=".$ced);
  $QryHabitoPa=pg_query($cn,"SELECT * FROM v_habitos WHERE cedula=".$ced);
  $QryAnalPsic=pg_query($cn,"SELECT * FROM v_analisis_psico WHERE cedula=".$ced);
  $QryDatAntro=pg_query($cn,"SELECT * FROM tbl_datos_antropometricos WHERE cedula=".$ced." ORDER BY fecha DESC");
  $QrySignVita=pg_query($cn,"SELECT * FROM tbl_signos_vitales WHERE cedula=".$ced." ORDER BY fecha DESC");
  $QryExamFisi=pg_query($cn,"SELECT * FROM v_examen_fisico WHERE cedula=".$ced);
  $recrd='--';

  $cdgHTML='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Documento sin título</title>
  <style>
  p.saltodepagina
  {
  page-break-after: always;
  }
  </style>
  </head>

  <body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%" rowspan="3"><img src="images/logo.jpg" width="119px" height="70px" /></td>
    <td width="85%">&nbsp;</td>
  </tr>
  <tr>
    <td style="vertical-align:middle; text-align:center" width="85%"><strong>HISTORIA MEDICA OCUPACIONAL</strong></td>
  </tr>
  <tr>
    <td width="85%">&nbsp;</td>
  </tr>
</table> 
   <table width="100%" border="1" cellspacing="0" cellpadding="0"> 
    <tr>
      <td style="border-bottom:none; border-right:none">Empresa:</td>
      <td style="border-bottom:none; border-right:none; border-left:none"><strong>BRIQVEN</strong></td>
      <td align="right" style="border-bottom:none; border-right:none; border-left:none">Rif.:</td>
      <td align="right" nowrap="nowrap" style="border-bottom:none; border-left:none"><strong>G-200113111</strong></td>
    </tr>
    <tr>
      <td style="border-top:none; border-right:none">Direcci&oacute;n:</td>
      <td style="border-top:none; border-right:none; border-left:none"><strong>Zona Ind. Matanza, Sector Punta Cuchillo.</strong> <br><strong>Pto. Ordaz, Edo. Bol&iacute;var.</strong></td>
      <td align="right" nowrap="nowrap" style="border-top:none; border-left:none; border-right:none">Actividad Econ&oacute;mica:</td>
      <td align="right" style="border-top:none; border-left:none"><strong>Sider&uacute;rgica</strong></td>
    </tr>
    <tr>
      <td colspan="4" align="center" bgcolor="#CCCCCC"><strong>Datos del Servicio de Seguridad y Salud en el Trabajo</strong></td>
    </tr>
    ';
    $Reghistoria = pg_fetch_array($Qryhistoria, null, PGSQL_ASSOC);
    $cdgHTML=$cdgHTML.'
    <tr>
      <td>Nombre del SSST:</td>
      <td><strong>'.$Reghistoria['nombre_ssst'].'</strong></td>
      <td>Tipo de SSST:</td>
      <td><strong>'.$Reghistoria['tipo_ssst'].'</strong></td>
    </tr>
    <tr>
      <td colspan="4" align="center" bgcolor="#CCCCCC"><strong>Datos de la Historia</strong></td>
    </tr>
    <tr>
      <td>Fecha:</td>
      <td><strong>'.substr($Reghistoria['fecha_apertura'],0,19).'</strong></td>
      <td>N&uacute;mero:</td>
      <td><strong>'.$Reghistoria['uid_historia'].'</strong></td>
    </tr>
    <tr>
      <td>Nombre del medico que apertura la historia:</td>
      <td><strong>'.$Reghistoria['medico'].'</strong></td>
      <td>C&eacute;dula:</td>
      <td><strong>'.$Reghistoria['ci_medico'].'</strong></td>
    </tr>
    <tr>
      <td>N&uacute;mero del colegio de m&eacute;dico:</td>
      <td><strong>'.$Reghistoria['nro_colegiado'].'</strong></td>
      <td>M.P.P.S.:</td>
      <td><strong>'.$Reghistoria['id_ss'].'</strong></td>
    </tr>
    <tr>
      <td colspan="4" align="center" bgcolor="#CCCCCC"><strong>Datos del Trabajador</strong></td>
    </tr>
    <tr>
      <td>Nombre y Apellido:</td>
      <td><strong>'.$Reghistoria['nombre_completo'].'</strong></td>
      <td>C&eacute;dula:</td>
      <td><strong>'.$Reghistoria['ci'].'</strong></td>
    </tr>
    <tr>
      <td>Fecha Nacimiento:</td>
      <td><strong>'.$Reghistoria['fechanac'].'</strong></td>
      <td>Edad:</td>
      <td><strong>'.$Reghistoria['edad'].'</strong></td>
    </tr>
    <tr>
      <td>Sexo:</td>
      <td><strong>'.$Reghistoria['sexo'].'</strong></td>
      <td>Estado Civil:</td>
      <td><strong>'.$Reghistoria['edo_civil'].'</strong></td>
    </tr>
    <tr>
      <td>Lugar de Nacimiento:</td>
      <td><strong>'.$Reghistoria['lugar_nac'].'</strong></td>
      <td>Nacionalidad:</td>
      <td><strong>'.$Reghistoria['nacionalidad'].'</strong></td>
    </tr>
    <tr>
      <td>Direcci&oacute;n Habitaci&oacute;n:</td>
      <td><strong>'.$Reghistoria['direccion_hab'].'</strong></td>
      <td>Tel&eacute;fono:</td>
      <td><strong>'.$Reghistoria['telefono'].'</strong></td>
    </tr>
    <tr>
      <td>Nivel Educativo:</td>
      <td><strong>'.$Reghistoria['nivel_educativo'].'</strong></td>
      <td>Mano Dominante.</td>
      <td><strong>'.$Reghistoria['mano_dominante'].'</strong></td>
    </tr>
    <tr>
      <td>Fecha Ingreso:</td>
      <td><strong>'.$Reghistoria['fecha_ingreso'].'</strong></td>
      <td>Turno de Trabajo:</td>
      <td><strong>'.$Reghistoria['turno'].'</strong></td>
    </tr>
    <tr>
      <td>Frecuencia de Rotaci&oacute;n</td>
      <td><strong>'.$Reghistoria['frecuencia_rotacion'].'</strong></td>
      <td>Antiguedad en el Puesto:</td>
      <td><strong>'.$Reghistoria['antiguedad_puesto'].'</strong></td>
    </tr>
    <tr>
      <td>Cargo y Puesto de Trabajo Actual:</td>
      <td><strong>'.$Reghistoria['cargo'].'</strong></td>
      <td>Tipo de Vivienda que Habita:</td>
      <td><strong>'.$Reghistoria['tipo_vivienda'].'</strong></td>
    </tr>
    <tr>
      <td>Su Vivienda es Propia?:</td>
      <td><strong>'.$Reghistoria['vivienda_propia'].'</strong></td>
      <td>Medio de Transporte que Utiliza para Ir al Trabajo:</td>
      <td><strong>'.$Reghistoria['medio_transp_trabajo'].'</strong></td>
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="5" align="center" bgcolor="#CCCCCC"><strong>Cargos Anteriores en la Misma Empresa</strong></td>
          </tr>
          <tr>
            <td><strong>Cargo</strong></td>
            <td><strong>Actividad laboral y/o &aacute;rea donde la desempeñaba</strong></td>
            <td><strong>Desde</strong></td>
            <td><strong>Hasta</strong></td>
            <td><strong>Riesgos o procesos peligrosos expuestos</strong></td>
          </tr>';
  while ($recordCargoAnt=pg_fetch_array($QrycargoAnt)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoAnt['cargo'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoAnt['actividad_laboral'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoAnt['desde'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoAnt['hasta'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoAnt['riesgos'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="5" align="center" bgcolor="#CCCCCC"><strong>Trabajos Anteriores</strong></td>
          </tr>
          <tr>
            <td><strong>Empresa</strong></td>
            <td><strong>Actividad laboral</strong></td>
            <td><strong>Desde</strong></td>
            <td><strong>Hasta</strong></td>
            <td><strong>Riesgos o procesos peligrosos expuestos</strong></td>
          </tr>';
  while ($recordCargoOtr=pg_fetch_array($QrycargoOtr)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoOtr['empresa'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoOtr['actividad_laboral'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoOtr['desde'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoOtr['hasta'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordCargoOtr['riesgos'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  ////////////Salto de Pagina///////////////////////////
  $cdgHTML=$cdgHTML.'<p class="saltodepagina" />';
  ////////////////////////////////////////////////////  
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Antecedentes Familiares del Trabajador</strong></td>
    </tr>
    <tr>
      <td width="50%"><strong>Enfermedad</strong></td>
      <td width="50%"><strong>Familiar que la padece</strong></td>
    </tr>';  
    while ($recordAntFamil=pg_fetch_array($QryAntFamil)) {
     $cdgHTML=$cdgHTML.' <tr>';
     $cdgHTML=$cdgHTML.' <td>'.$recordAntFamil['descripcion'].'</td>';
     $cdgHTML=$cdgHTML.' <td>'.$recordAntFamil['paterentezco'].'</td>';
     $cdgHTML=$cdgHTML.' </tr>';
    }
  $cdgHTML=$cdgHTML.'</table>';
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" align="center" bgcolor="#CCCCCC"><strong>Antecedentes Ocupacionales</strong></td>
    </tr>
    <tr>
      <td><strong>Agentes</strong></td>
      <td><strong>Tipo</strong></td>
      <td><strong>Descripci&oacute;n de la exposici&oacute;n</strong></td>
      <td><strong>Tiempo de exposici&oacute;n</strong></td>
    </tr>';  
  while ($recordRiesgExp=pg_fetch_array($QryRiesgExp)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordRiesgExp['agente'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordRiesgExp['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordRiesgExp['resp'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordRiesgExp['tiempo_exposicion'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" align="center" bgcolor="#CCCCCC"><strong>Accidentes Ocupacionales</strong></td>
    </tr>
    <tr>
      <td width="40%">¿Ha Sufrido Accidentes te de Trabajo?</td>
      <td width="10%"><strong>'.$Reghistoria['ha_sufrido_accidentes'].'</strong></td>
      <td width="40%">¿Que Parte del Cuerpo se Lesion&oacute;?</td>
      <td width="10%"><strong>'.$Reghistoria['partes_cuerpo_lesionados'].'</strong></td>
    </tr>
    <tr>
      <td width="40%">Fecha del Accidente:</td>
      <td width="10%"><strong>'.$Reghistoria['fecha_accidente'].'</strong></td>
      <td width="40%">¿Dej&oacute; Alguna Secuela?</td>
      <td width="10%"><strong>'.$Reghistoria['dejo_secuelas'].'</strong></td>
    </tr>
    <tr>
      <td width="40%">¿Fue Certificada por el INPSASEL?</td>
      <td width="10%"><strong>'.$Reghistoria['fue_certif_inpsasel'].'</strong></td>
      <td width="40%">Cambia de Trabajo con Frecuencia</td>
      <td width="10%"><strong>'.$Reghistoria['cambia_trab_frecuente'].'</strong></td>
    </tr>
    <tr>
      <td width="40%">¿Ha padecido de alguna enfermada d Ocupacional?</td>
      <td width="10%"><strong>'.$Reghistoria['ha_padecido_enfermeda'].'</strong></td>
      <td width="40%">&nbsp;</td>
      <td width="10%">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Ex&aacute;men Funcional</strong></td>
    </tr>
    <tr>
      <td><strong>Zona Corporal</strong></td>
      <td><strong>Enfermedad</strong></td>
      <td><strong>Observaci&oacute;n</strong></td>
    </tr>';
   $i=0; 
   while ($recordRiesgExp1=pg_fetch_array($QryExamFun1)){
      $arreglo2[$i]=$recordRiesgExp1['tipo'];
      $i++; 
   }
   if ($i>0){
	   $rep="";
	   $i=1;
	   $mult=array(); 
	   foreach ($arreglo2 as &$valor) 
		 if ($valor==$rep){
			$i++;
			$mult[$valor] = $i;   
		  }
		 else{
			$i=1;
			$mult[$valor]=$i;
			$rep=$valor;      
		  }
	  $band=true;
	  while ($recordRiesgExp2=pg_fetch_array($QryExamFun2)){
		$idexm=$recordRiesgExp2['tipo'];
		if ($mult[$idexm]>1){
		  if ($band){
			$j=1;  
	  $cdgHTML=$cdgHTML.'<tr>  
		  <td rowspan="'.$mult[$idexm].'">'.$idexm.'</td>
		  <td>'.$recordRiesgExp2['descripcion'].'</td>
		  <td>'.$recordRiesgExp2['observacion'].'</td>
		</tr>'; 
		$band=false; } else { 
		$cdgHTML=$cdgHTML.'  
		<tr>
		  <td>'.$recordRiesgExp2['descripcion'].'</td>
		  <td>'.$recordRiesgExp2['observacion'].'</td>
		</tr>'; 
	   }  
		if ($mult[$idexm]==$j)
		  $band=true;
		$j++;
		}
	   else{
	  $cdgHTML=$cdgHTML.'
		<tr>
		  <td>'.$idexm.'</td>
		  <td>'.$recordRiesgExp2['descripcion'].'</td>
		  <td>'.$recordRiesgExp2['observacion'].'</td>
		</tr>';
	   
		 }
	  }
  }
  $cdgHTML=$cdgHTML.'
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>H&aacute;bitos</strong></td>
    </tr>
    <tr>
      <td><strong>Descripci&oacute;n</strong></td>
      <td><strong>Resp.</strong></td>
      <td><strong>Observaci&oacute;n</strong></td>
    </tr>';
  while ($recordHabitoPa=pg_fetch_array($QryHabitoPa)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordHabitoPa['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordHabitoPa['resp'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordHabitoPa['observacion'].'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  ////////////Salto de Pagina///////////////////////////
  $cdgHTML=$cdgHTML.'<p class="saltodepagina" />';
  //////////////////////////////////////////////////// 
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Anamnesis Psic&oacute;logico: Aspecto del Trabajo</strong></td>
    </tr>
    <tr>
      <td width="50%"><strong>Descripci&oacute;n</strong></td>
      <td width="50%"><strong>Resp./Observaci&oacute;n</strong></td>
    </tr>';  
  while ($recordAnalPsic=pg_fetch_array($QryAnalPsic)) { 
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td width="50%">'.$recordAnalPsic['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="50%">'.$recordAnalPsic['observacion'].'</td>'; 
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  $cdgHTML=$cdgHTML.'  
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Ex&aacute;men F&iacute;sico</strong></td>
    </tr>
    <tr>
      <td width="50%"><strong>Datos Antopometr&iacute;cos</strong></td>
      <td width="50%"><strong>Signos Vitales</strong></td>   
    </tr>
    <tr>
      <td width="50%"><table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td><strong>Talla</strong></td>
          <td><strong>Peso</strong></td>
          <td><strong>IMC</strong></td>
          <td><strong>Fecha Ex&aacute;men</strong></td>
        </tr>';
  while ($recordDatAntro=pg_fetch_array($QryDatAntro)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordDatAntro['talla'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordDatAntro['peso'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordDatAntro['imc'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.substr($recordDatAntro['fecha'],0,19).'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table></td>';
  $cdgHTML=$cdgHTML.'<td width="50%"><table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td><strong>F. Resp.</strong></td>
          <td><strong>Pulso</strong></td>
          <td><strong>Temp.</strong></td>
          <td><strong>T. Art.</strong></td>
          <td><strong>Fecha Ex&aacute;men</strong></td>
        </tr>';
  while ($recordSignVita=pg_fetch_array($QrySignVita)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td>'.$recordSignVita['fresp'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordSignVita['pulso'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordSignVita['temper'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.$recordSignVita['tart'].'</td>';
   $cdgHTML=$cdgHTML.' <td>'.substr($recordSignVita['fecha'],0,19).'</td>';
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table></td>';      
  $cdgHTML=$cdgHTML.'</tr>';
  $cdgHTML=$cdgHTML.'</table>';
  ////////////Salto de Pagina///////////////////////////
  $cdgHTML=$cdgHTML.'<p class="saltodepagina" />';
  //////////////////////////////////////////////////// 
  $cdgHTML=$cdgHTML.'<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Complemento</strong></td>
    </tr>
    <tr>
      <td width="38%"><strong>Descripci&oacute;n</strong></td>
      <td width="50%"><strong>Observaci&oacute;n</strong></td>
      <td width="12%"><strong>Fecha</strong></td>
    </tr>';
  while ($recordExamFisi=pg_fetch_array($QryExamFisi)) {
   $cdgHTML=$cdgHTML.' <tr>';
   $cdgHTML=$cdgHTML.' <td width="38%">'.$recordExamFisi['descripcion'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="50%">'.$recordExamFisi['observacion'].'</td>';
   $cdgHTML=$cdgHTML.' <td width="12%">'.$recordExamFisi['fecha_examen'].'</td>'; 
   $cdgHTML=$cdgHTML.' </tr>';
  }
  $cdgHTML=$cdgHTML.'</table>';
  $cdgHTML=$cdgHTML.'</body></html>';
  pg_free_result($Qryhistoria);
  pg_free_result($QrycargoAnt);
  pg_free_result($QrycargoOtr);
  pg_free_result($QryAntFamil);
  pg_free_result($QryRiesgExp);
  pg_free_result($QryExamFun1);
  pg_free_result($QryExamFun2);
  pg_free_result($QryHabitoPa);
  pg_free_result($QryAnalPsic);
  pg_free_result($QryDatAntro);
  pg_free_result($QrySignVita);
  pg_close($cn);
  return $cdgHTML;
}
?>