<?php
$trabajador= isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
$ciclo= isset($_GET["ciclo"])?$_GET["ciclo"]:"NULL";

echo formato_vacaciones_htlm($trabajador,$ciclo);

function formato_vacaciones_htlm($trabajador,$ciclo){
include("../BD/conexion.php");
require_once('funciones_var.php');
$cn=Conectarse_sitt();
$qry="SELECT a.TRABAJADOR, b.NOMBRES, a.CICLO_LABORAL, a.FECHA_INI_PER_VAC, a.FECHA_FIN_PER_VAC, a.FECHA_PAGO_VAC, a.FECHA_ALTA, 
b.CENTRO_COSTO, b.SISTEMA_HORARIO, b.DESC_PUESTO, b.GERENCIA, b.FECHA_INGRESO, a.TIEMPO_PROG_VAC, b.TRABAJADOR_SUP, c.NOMBRES as jefe, d.vacpendiente,
a.situacion_programa, a.disposicion, a.condicion, a.dias_pendientes, a.motivo_interrupcion, a.fecha_ini_interrupcion, a.fecha_fin_interrupcion, a.dias_pendientes, a.FECHA_MOD, a.USUARIO_MOD, GETDATE() as hoy
FROM dbo.adam_programacion_vacaciones a 
INNER JOIN dbo.ADAM_DATOS_PERSONALES b ON CAST(a.TRABAJADOR AS int)=b.Trabajador 
left join dbo.ADAM_DATOS_PERSONALES c on CAST(b.TRABAJADOR_SUP AS int)=c.Trabajador
left join (select aux.TRABAJADOR, COUNT(*) as vacpendiente 
			from dbo.adam_programacion_vacaciones aux 
			where aux.TRABAJADOR=".$trabajador." 
			and  aux.CICLO_LABORAL<>".$ciclo." 
			and aux.situacion_programa<>3 and (aux.condicion<>'Finalizada' or  aux.condicion is null )
			group by aux.TRABAJADOR
			) d on d.TRABAJADOR = b.Trabajador 
WHERE 1=1 ";

$qry.=" AND b.TRABAJADOR=".$trabajador."";  
$qry.=" AND a.CICLO_LABORAL=".$ciclo."";

$stmt1 = $cn->query($qry);

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

while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
	$TRABAJADOR=trim($row['TRABAJADOR']);
	$NOMBRES=$row['NOMBRES'];
	$CICLO_LABORAL=$row['CICLO_LABORAL'];
	$FECHA_INI_PER_VAC=substr($row['FECHA_INI_PER_VAC'], 0, 10);
	$FECHA_FIN_PER_VAC=substr($row['FECHA_FIN_PER_VAC'], 0, 10);
	$FECHA_PAGO_VAC=substr($row['FECHA_PAGO_VAC'], 0, 10);
	$CENTRO_COSTO=$row['CENTRO_COSTO'];
	$SISTEMA_HORARIO=$row['SISTEMA_HORARIO'];
	$DESC_PUESTO=$row['DESC_PUESTO'];
	$GERENCIA=$row['GERENCIA'];
	$FECHA_INGRESO=substr($row['FECHA_INGRESO'], 0, 10);
	$TIEMPO_PROG_VAC=$row['TIEMPO_PROG_VAC'];
	$TRABAJADOR_SUP=$row['TRABAJADOR_SUP'];
	$jefe=$row['jefe'];
	$vacpendiente=$row['vacpendiente'];
	$FECHA_ALTA=substr($row['FECHA_ALTA'], 0, 10);
	$motivo_interrupcion=$row['motivo_interrupcion'];
	$disposicion=$row['disposicion'];
	$FECHA_MOD=$row['FECHA_MOD'];
	$USUARIO_MOD=$row['USUARIO_MOD'];
	$hoy=$row['hoy'];
	$dia_examen=date("Y-m-d",strtotime($FECHA_INI_PER_VAC." -1 days"));

	$fecha_reintegro=date("Y-m-d",strtotime($FECHA_FIN_PER_VAC." 1 days"));
}
$stmt1=null;
$cn=null;

$gerencia=nombre_gerente($TRABAJADOR, $CENTRO_COSTO);
//print_r($gerencia);
if (isset($gerencia[0]))
	if (trim($gerencia[0])==$TRABAJADOR)
		$gerencia[1]='';

if (isset($gerencia[1])){
	if ($gerencia[3]==44)
		$gerente=$gerencia[1];
	else
		$gerente=$gerencia[1];
}
//$gerente=(isset($gerencia[1]) && $gerencia[3]!=44) ? $gerencia[1] : '';


if (strtotime($FECHA_ALTA) < strtotime($FECHA_INI_PER_VAC))
	$disposicion="";

$html='';
if ($TRABAJADOR!=""){
	if ($SISTEMA_HORARIO==13 || $SISTEMA_HORARIO==19)
		$SISTEMA_HORARIO='ADMINISTRATIVO';
	elseif ($SISTEMA_HORARIO>=1 && $SISTEMA_HORARIO<=4) 
		$SISTEMA_HORARIO='ROTATIVO';
	else
		$SISTEMA_HORARIO='MIXTO';
$html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>	
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>		
	<style type="text/css">
		body,table,thead,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 

		@media print {
    .pagebreak {
        clear: both;
        page-break-after: always;
    }
} 
}
	</style>	
</head>
<body>
<table align="left" cellspacing="0" border="0">
	<colgroup span="2" width="142"></colgroup>
	<colgroup width="148"></colgroup>
	<colgroup span="3" width="142"></colgroup>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000"  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/CVG.gif" width=128 height=54 ></font></td>
		<td style="border-top: 1px solid #000000" colspan=4  align="center" valign=top bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/MATESI_logo_1.png" width=128 height=54 >
		</font></td>
		<td style="border-top: 1px solid #000000; border-right: 1px solid"  align="right" valign=top bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/logo_1.png" width=128 height=54 >
		</font></td>
	</tr>
	
	<tr>
		<td style="border-left: 1px solid #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000;  solid #000000; border-left: 1px solid #000000;" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">Fecha de Solicitud:</font></b></td>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;DD/MM/YYYY;@"><font size=3 color="#000000">'.$FECHA_ALTA.'</font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="21" align="center" valign=middle bgcolor="#C0C0C0"><b><font size=3 color="#000000">SOLICITUD DE VACACIONES</font></b></td>
		</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" height="30" align="center" valign=middle bgcolor="#C0C0C0"><b><font  color="#000000">Centro de Costo</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;"><b><font >'.$CENTRO_COSTO.'</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">Sistema Horario</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><font color="#000000">'.$SISTEMA_HORARIO.'</font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 height="30" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">NOMBRE y APELLIDO</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">CEDULA DE IDENTIDAD</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">CARGO</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">GERENCIA DE ADSCRIPCION</font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 height="47" align="center" valign=middle><font size=3 color="#000000">'.$NOMBRES.'</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle><font size=3 color="#000000">'.$TRABAJADOR.'</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle><b><font >'.$DESC_PUESTO.'</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><font color="#000000">'.$GERENCIA.'</font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" height="30" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">FECHA DE INGRESO</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">FECHA PROGRAMADA DE DISFRUTE VACACIONES</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">FECHA DE PAGO</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">FECHA EFECTIVA DE DISFRUTE A PARTIR DE:</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">DIAS DE VACACIONES A DISFRUTAR</font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" height="36" align="center" valign=middle sdnum="1033;0;M/D/YYYY"><font color="#000000">'.$FECHA_INGRESO.'</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle sdnum="1033;0;M/D/YYYY"><font color="#000000">'.$FECHA_PAGO_VAC.'</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" align="center" valign=middle sdnum="1033;0;M/D/YYYY"><font color="#000000">'.$FECHA_PAGO_VAC.'</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;" colspan=2 align="center" valign=middle sdnum="1033;0;DD/MM/YYYY;@"><b><font >'.$FECHA_INI_PER_VAC.'</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;0"><font size=3 color="#000000">'.$TIEMPO_PROG_VAC.'</font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; " colspan=2 height="30" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">DIAS ADICIONALES DE VACACIONES A DISFRUTAR (Art. 176 L.O.T.T.T)</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; " align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">FECHA DE REINTEGRO</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; " colspan=2 align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">VACACIONES PENDIENTES POR DISFRUTAR</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">PERIODO VACACIONAL A DISFRUTAR</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 1px solid #000000; " colspan=2 height="36" align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;0"><font size=3 color="#000000">0<br></font></td>
		<td style="border-left: 1px solid #000000; " align="center" valign=middle sdnum="1033;0;M/D/YYYY"><b><font color="#000000">'.$fecha_reintegro.'<br></font></b></td>
		<td style="border-left: 1px solid #000000; " colspan=2 align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;M/D/YYYY"><font size=3 color="#000000">'.$vacpendiente.'<br></font></td>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><font color="#000000">'.$CICLO_LABORAL.'</font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" height="36" align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;0"><b><font size=3 color="#000000">NOTA: </font>'.$disposicion.'</b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" colspan=5 align="left" valign=middle><b><font color="#000000"><br></font></b></td>
		</tr>
	<tr>
		<td style="border-left: 1px solid #000000; " colspan=4 height="90" align="center" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;0"><font size=2 color="#000000">Informacion Validada por la Gerencia de Talento Humano, Departamento de Relaciones Laborales.</font></td>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#C0C0C0">Sello</font></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="38" align="center" valign=middle bgcolor="#FFFFFF"><b><font size=4 color="#000000">Ley Organica del Trabajo para los Trabajadores y las Trabajadoras (L.O.T.T.T)</font></b></td>
		</tr>
	<tr>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="40" align="left" valign=middle><b><font face="Tahoma" color="#000000">Artículo 190. Cuando el trabajador o la trabajadora cumpla un año de trabajo ininterrumpido para un patrono o una patrona, disfrutará de un período de vacaciones remuneradas. . . (Omisis)</font></b></td>
		</tr>
	<tr>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="40" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Tahoma" color="#000000">Artículo 197. El trabajador o la trabajadora deberá disfrutar las vacaciones  de  manera efectiva y obligatoria, esta misma obligación existe para el patrono o la patrona de concederlas. . . (Omisis)</font></b></td>
		</tr>
	<tr>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="40" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Tahoma" color="#000000">Artículo 200. La época en que el trabajador o la trabajadora deban tomar sus  vacaciones  anuales será fijada por convenio entre el trabajador o la trabajadora y el patrono o la patrona. Si no llegasen a un acuerdo, el Inspector o Inspectora del Trabajo hará la fijación . . . (Omisis)</font></b></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; " colspan=2 rowspan=4 height="80" align="left" valign=bottom><b><font color="#000000">Trabajador: '.$NOMBRES.'</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=4 align="left" valign=bottom><b><font color="#000000">'.$gerencia[2].': '.$gerente.'</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=4 align="left" valign=bottom><b><font color="#000000">Supervisor: '.$jefe.'</font></b></td>
		</tr>
	<tr>
		</tr>
	<tr>
		</tr>
	<tr>
		</tr>
	
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="29" align="center" valign=middle bgcolor="#C0C0C0"><b><font size=2 color="#000000">UNICAMENTE PARA SER LLENADO POR LA GERENCIA DE TALENTO HUMANO </font></b></td>
		</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; " colspan=3 height="20" align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">DEPARTAMENTO DE RELACIONES LABORALES</font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle bgcolor="#C0C0C0"><b><font color="#000000">COORDINACION DE NOMINA</font></b></td>
		</tr>
	
	<tr>
		<td style="border-left: 1px solid #000000" height="20" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-left: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px solid #000000" height="20" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-left: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" height="20" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000; " align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; " colspan=3 height="30" align="left" valign=bottom bgcolor="#FFFFFF"><font color="#000000">Recibido Por:                                                                                                (Nombre y Apellido, Firma y Fecha)</font></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom bgcolor="#FFFFFF"><font color="#000000">Recibido Por:                                                                                            (Nombre y Apellido, Firma y Fecha)</font></td>
		</tr>
	<tr>
		<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="29" align="left" valign=middle bgcolor="#FFFFFF"><b><font color="#000000">OBSERVACIONES:</br>'.$motivo_interrupcion.'</font></td>
		</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 rowspan=2 height="40" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		</tr>
	<tr>
		</tr>
</table>

<div class="pagebreak"> </div>

<table cellspacing="0" border="0">
	<colgroup width="163"></colgroup>
	<colgroup span="2" width="141"></colgroup>
	<colgroup width="216"></colgroup>
	<colgroup width="103"></colgroup>
	<colgroup width="153"></colgroup>
	<colgroup width="35"></colgroup>
	<tr>
		<td height="21" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td colspan=2 style="border-top: 1px solid #000000; border-left: 1px solid #000000"   align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/CVG.gif" width=128 height=54 ></font></td>
		<td style="border-top: 1px solid #000000" colspan=2  align="center" valign=top bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/MATESI_logo_1.png" width=128 height=54 >
		</font></td>
		<td colspan=2 style="border-top: 1px solid #000000; border-right: 1px solid #000000"  align="right" valign=top bgcolor="#FFFFFF"><font color="#000000"><br><img src="images/logo_1.png" width=127 height=54 >
		</font></td>
		<td  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	
	<tr>
		<td style="border-left: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	<tr>
		<td style="border-left: 1px double #000000" height="25" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td align="center" valign=bottom bgcolor="#FFFFFF"><b><font size=4 color="#000000"><br></font></b></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Puerto Ordaz, </font></b></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000">'.$FECHA_ALTA.'</font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">Atencion</font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000">Doctor a Cargo</font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" colspan=3 height="32" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">Servicio  Medico  de  Matesi, S.A/Briqven</font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="left" valign=bottom bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td colspan="6" style="border-left: 1px double #000000; border-right: 1px double #000000" height="30" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000">Me    dirijo    a    usted     en     la    oportunidad    de    solicitar   la    realizacion    del   examen    Fisico     Pre-Vacacional al          trabajador <strong>'.$NOMBRES.'</strong> Titular   de    la    Cedula  de  identidad   <strong>Nº  '.$TRABAJADOR.'</strong>, exactamente  el  día ___/___/_____ ante   el  Servicio  Medico  de  Matesi, S.A/Briqven.</font></td>
		
		<td  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	
	<tr>
		<td style="border-left: 1px double #000000" height="29" align="left" valign=middle bgcolor="#FFFFFF" sdnum="1033;0;0"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td colspan="6" style="border-left: 1px double #000000; border-right: 1px double #000000" height="30" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000">El  trabajador  debe  dejar  en  el  Servicio  Médico,  esta  notificación  la  cual  posterior  a  la  evaluación  Post-Vacacional será entregada a la Gerencia de Talento Humano.</font></td>
		
		<td  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	
	<tr>
		<td style="border-left: 1px double #000000" height="29" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td colspan="6" style="border-left: 1px double #000000; border-right: 1px double #000000" height="30" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000">Asimismo,  le  notificamos que el inicio efectivo  de  su periodo vacacional esta programada para el dia '.$FECHA_INI_PER_VAC.'.</font></td>
		
		<td  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	<tr>
		<td style="border-left: 1px double #000000" height="29" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td colspan="6" style="border-left: 1px double #000000; border-right: 1px double #000000" height="30" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000">Finalmente,    la    reincorporacion    del     (la)    trabajador    (a)     debe     ser    el    dia '.$fecha_reintegro.', es    necesario destacar    que    al   momento   de   la  reincorporación  el   trabajador  debera  <strong>OBLIGATORIAMENTE</strong>   realizarse   el examen   POST-VACACIONAL.</font></td>
		
		<td  align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	<tr>
		<td style="border-left: 1px double #000000" height="30" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000; border-right: 1px double #000000" colspan=6 height="30" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size="3" color="#000000">EN   CASO  DE  QUE  EL   TRABAJADOR   NO  SE   REALICE   EL   EXAMEN POST- VACACIONAL   INDICADO  NO  PODRA SER  ACTIVADO  EN  EL  SISTEMA  DE  NÓMINA.</font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="20" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	<tr>
		<td style="border-left: 1px double #000000" height="20" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="20" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">Atentamente</font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>	
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">Lcda. Yenny May</font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">Jefe de Relaciones Laborales</font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="20" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="34" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">FECHA</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">CERTIFICADO MÉDICO</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">FIRMA DEL MÉDICO</font></b></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" color="#000000">PRE-VACACIONAL </font></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle  ></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=top bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=top bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" height="21" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" color="#000000">POST-VACACIONAL </font></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle  ></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=top bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
		<td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=top bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" colspan=2 height="28" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" colspan=2 align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-left: 1px double #000000" colspan=2 height="28" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000"><br></font></b></td>
		<td colspan=2 align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	
	
	<tr>
		<td style="border-bottom: 1px double #000000; border-left: 1px double #000000" height="21" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-bottom: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-bottom: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-bottom: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-bottom: 1px double #000000" align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td style="border-bottom: 1px double #000000; border-right: 1px double #000000" align="center" valign=middle bgcolor="#FFFFFF"><font face="Arial" size=3 color="#000000"><br></font></td>
		<td align="center" valign=middle bgcolor="#FFFFFF"><font color="#000000"><br></font></td>
	</tr>
	
</table>

</body>

</html>';
}
	return $html;
}
?>