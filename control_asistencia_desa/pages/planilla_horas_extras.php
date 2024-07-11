<?php
$trabajador= isset($_GET["trabajador"])?$_GET["trabajador"]:"NULL";
$ciclo= isset($_GET["ciclo"])?$_GET["ciclo"]:"NULL";

// Contando el numero de input seleccionados "checked" checkboxes.
$checked_contador = count($_POST['autorizado']);



//echo formato_vacaciones_htlm($trabajador,$ciclo);
echo formato_vacaciones_htlm('12564688','20202021',$checked_contador);




function formato_vacaciones_htlm($trabajador,$ciclo,$checked_contador){
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

$gerencia=nombre_gerente($TRABAJADOR);

if (isset($gerencia[0]))
	if (trim($gerencia[0])==$TRABAJADOR)
		$gerencia[1]='';
$gerente=(isset($gerencia[1]) && $gerencia[3]!=44) ? $gerencia[1] : '';


if (strtotime($FECHA_ALTA) < strtotime($FECHA_INI_PER_VAC))
	$disposicion="";

$html='';

$html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>	
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>		
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;width:750px}
.tg2  {border: 0px solid white; border-collapse:collapse; border-spacing:0; margin:0px auto; width:750px}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:12px; overflow:hidden;padding:3px 3px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:12px; font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-0l74{background-color:#c0c0c0;border-color:inherit;font-size:8px;text-align:left;vertical-align:top}
.tg .tg-nrw1{font-size:8px;text-align:center;vertical-align:top}
.tg .tg-doeh{border-color:inherit;font-size:8px;font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-9wq8{border-color:inherit;text-align:center;vertical-align:middle}
.tg .tg-c4ww{background-color:#cbcefb;border-color:inherit;text-align:center;vertical-align:top; border-top: 0px solid #000000}
.tg .tg-pnpa{background-color:#c0c0c0;border-color:inherit;font-size:26px;text-align:center;vertical-align:top}
.tg .tg-uxaa{background-color:#c0c0c0;border-color:inherit;font-size:8px;text-align:center;vertical-align:top}
.tg .tg-j4xs{background-color:#cbcefb;border-color:inherit;text-align:center;vertical-align:middle}
.tg .tg-91w8{border-color:inherit;font-size:8px;text-align:center;vertical-align:top}
.tg .tg-qdpa{border-color:inherit;font-size:8px;text-align:center;vertical-align:bottom}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-fmch{background-color:#c0c0c0;border-color:inherit;font-size:12px;text-align:center;vertical-align:top}
.tg .tg-l6li{border-color:inherit;font-size:8px;text-align:center;vertical-align:top}
.tg .tg-llyw{background-color:#c0c0c0;border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-azew{border-color:inherit;font-size:8px;text-align:center;vertical-align:middle}
.tg .tg-gzo9{border-color:inherit;font-size:12px;text-align:center;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-3qkg{background-color:#c0c0c0;border-color:inherit;font-size:12px;text-align:center;vertical-align:top}
.tg .tg-4k6h{border-color:inherit;font-size:10px;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-l64y{font-size:8px;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-y6fn{background-color:#c0c0c0;text-align:left;vertical-align:top}
.tg .tg-3j8g{font-size:8px;font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-yva3{font-size:8px;text-align:center;vertical-align:bottom}
.tg .tg-jpc1{font-size:8px;text-align:left;vertical-align:top}
.justify{
  text-align: justify;
}
@media print {
    .pagebreak {
        clear: both;
        page-break-after: always;
    }
} 
.texto-justificado{
    text-align: justify;
}
header, footer, nav, aside {
  display: none;
}
</style>	
</head>
<body>
<table class="tg">
  <tr>
    <td colspan="8">
      <font color="#000000"><img src="images/logo_CSV.jpg" width=100 height=40 ></font>
	  <font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font>
      <table width=100%>
        <tr> 
    		<td class="tg-c3ow"><span style="font-weight:bold">SOLICITUD DE HORAS EXTRAS Y TRABAJO EN DIA LIBRE '.$checked_contador.'</span></td>
    	</tr>
      </table>		
    </td>  
  </tr>
  <tr>
    <td class="tg-3qkg" colspan="8"><span style="font-weight:bold">DATOS GERENCIA SOLICITANTE</span></td>
  </tr>
  <tr>
    <td class="tg-azew"><span style="font-weight:bold">NOMBRE Y APELLIDO DEL </span><br><span style="font-weight:bold">SOLICITANTE</span></td>
    <td class="tg-91w8" colspan="7">AREVALO YUDISAY</td>
  </tr>
  <tr>
    <td class="tg-azew"><span style="font-weight:bold">No. DE CEDULA</span><br></td>
    <td class="tg-c3ow" colspan="5">V-14509326 </td>
    <td class="tg-91w8"><span style="font-weight:bold">FECHA SOLICITUD</span></td>
    <td class="tg-0l74">MIERCOLES 31/08/2022</td>
  </tr>
  <tr>
    <td class="tg-azew"><span style="font-weight:bold">GERENCIA</span></td>
    <td class="tg-91w8" colspan="6">MANTENIMIENTO  </td>
    <td class="tg-0pky" rowspan="3"></td>
  </tr>
  <tr>
    <td class="tg-azew"><span style="font-weight:bold">CARGO</span></td>
    <td class="tg-91w8" colspan="6">GERENTE&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td class="tg-9wq8" rowspan="2"><span style="font-weight:bold">Indique en la lista despregable el nombre del Gerente General </span><br><span style="font-weight:bold">Correspondiente.</span><br></td>
    <td class="tg-91w8" colspan="6">AREVALO YUDISAY&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td class="tg-91w8" colspan="6">GERENTE DE MANTENIMIENTO&nbsp;&nbsp;</td>
    <td class="tg-l6li">Indicar Ce.Co</td>
  </tr>
  <tr>
    <td class="tg-azew" rowspan="2"><span style="font-weight:bold">TIPO DE AUTORIZACION</span></td>
    <td class="tg-azew" colspan="2"><span style="font-weight:bold">HORAS EXTRAORDINARIAS</span><br><span style="font-weight:bold">(indicar con una "x")</span></td>
    <td class="tg-91w8"></td>
    <td class="tg-91w8" colspan="3"><span style="font-weight:bold">TOTAL cantidad de horas estimadas previstas contando la totalidad de trabajadores</span></td>
    <td class="tg-pnpa">32</td>
  </tr>
  <tr>
    <td class="tg-azew" colspan="2">TRABAJO EN DIA LIBRE<br><span style="font-weight:bold">(indicar con una "x")</span></td>
    <td class="tg-91w8"></td>
    <td class="tg-91w8" colspan="3"><span style="font-weight:bold">TOTAL cantidad de horas estimadas (con base a los dias) previstas contando la totalidad de trabajadores</span></td>
    <td class="tg-pnpa">0</td>
  </tr>
  <tr>
    <td class="tg-3qkg" colspan="8"><span style="font-weight:bold">RELACION DE HORAS POR TRABAJADOR</span></td>
  </tr>
  <tr>
    <td class="tg-3qkg" colspan="8"><span style="font-weight:bold">PARA CALCULOS DE HORAS EXTRAS</span></td>
  </tr>
  <tr>
    <td class="tg-uxaa" colspan="2"><span style="font-weight:bold">TRABAJADOR - NOMBRE Y APELLIDO</span></td>
    <td class="tg-uxaa">Validar </td>
    <td class="tg-uxaa"><span style="font-weight:bold">FECHA</span><br></td>
    <td class="tg-uxaa" colspan="2"><span style="font-weight:bold">No. HORAS</span></td>
    <td class="tg-uxaa"><span style="font-weight:bold">C.I.</span></td>
    <td class="tg-uxaa"><span style="font-weight:bold">CARGO</span><br></td>
  </tr>
  <tr>
    <td class="tg-4k6h" colspan="2">BARCELO JULIO</td>
    <td class="tg-llyw"></td>
    <td class="tg-4k6h">22/08/22</td>
    <td class="tg-doeh" colspan="2">4</td>
    <td class="tg-qdpa">12.360.812</td>
    <td class="tg-doeh">MECANICO</td>
  </tr>
  <tr>
    <td class="tg-l64y" colspan="2">ARTEAGA DOMINGO</td>
    <td class="tg-y6fn"></td>
    <td class="tg-l64y">22/08/22</td>
    <td class="tg-3j8g" colspan="2">4</td>
    <td class="tg-yva3">20.806.992</td>
    <td class="tg-3j8g">MECANICO/SOLDADOR</td>
  </tr>
  <tr>
    <td class="tg-jpc1" colspan="2">PACHECO JUAN</td>
    <td class="tg-y6fn"></td>
    <td class="tg-l64y">22/08/22</td>
    <td class="tg-3j8g" colspan="2">4</td>
    <td class="tg-nrw1">12.556.479</td>
    <td class="tg-nrw1">MECANICO</td>
  </tr>
  <tr>
    <td class="tg-jpc1" colspan="2">PAEZ PEDRO</td>
    <td class="tg-y6fn"></td>
    <td class="tg-l64y">22/08/22</td>
    <td class="tg-3j8g" colspan="2">4</td>
    <td class="tg-nrw1">17.431.835</td>
    <td class="tg-nrw1">MECANICO/SOLDADOR</td>
  </tr>
  <tr>
    <td class="tg-91w8" colspan="8"><span style="font-weight:bold">PARA CALCULO DE TRABAJO DE DIA LIBRE</span></td>
  </tr>
  <tr>
    <td class="tg-uxaa" colspan="2"><span style="font-weight:bold">TRABAJADOR - NOMBRE Y APELLIDO</span></td>
    <td class="tg-uxaa">Validar</td>
    <td class="tg-uxaa"><span style="font-weight:bold">FECHA</span></td>
    <td class="tg-uxaa"><span style="font-weight:bold">DIA</span></td>
    <td class="tg-uxaa"><span style="font-weight:bold">HORAS</span></td>
    <td class="tg-uxaa"><span style="font-weight:bold">C.I.</span><br></td>
    <td class="tg-uxaa"><span style="font-weight:bold">CARGO</span></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="2"></td>
    <td class="tg-llyw"></td>
    <td class="tg-0pky"></td>
    <td class="tg-0pky"></td>
    <td class="tg-0pky"></td>
    <td class="tg-0pky"></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-uxaa" colspan="8"><span style="font-weight:bold">NOMBRE DEL TRABAJO A REALIZAR</span></td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="8"></td>
  </tr>
  <tr>
    <td class="tg-uxaa" colspan="8"><span style="font-weight:bold">INFORMACION DEL TRABAJO REALIZADO (Indicar con detalle el trabajo realizado)</span></td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="8"></td>
  </tr>
  <tr>
    <td class="tg-fmch" colspan="2"><span style="font-weight:bold">SOICITADO POR</span></td>
    <td class="tg-fmch" colspan="4"><span style="font-weight:bold">REVISADO POR</span></td>
    <td class="tg-fmch" colspan="2"><span style="font-weight:bold">AUTRORIZADO</span></td>
  </tr>
  <tr>
    <td class="tg-gzo9" colspan="2"><br><br><br><br><span style="font-weight:bold">AREVALO YUDISAY V- 14509326</span><br><span style="font-weight:bold">Gerente e Mantenimiento</span></td>
    <td class="tg-gzo9" colspan="4"><br><br><br><br><span style="font-weight:bold">LUIS VERENZUELA C.I.: 14.576.991</span><br><span style="font-weight:bold">VICEPRESIDENTE</span></td>
    <td class="tg-gzo9" colspan="2"><br><br><br><br><br><span style="font-weight:bold">Gerente de Talento Humano</span></td>
  </tr>
  <tr>
    <td class="tg-j4xs" colspan="6" rowspan="2"><span style="font-weight:bold">Contando con las autorizaciones correspondientes se informa a la unidad de </span><br><span style="font-weight:bold">"Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) </span><br><span style="font-weight:bold">indicado en esta planilla.</span></td>
    <td class="tg-0pky" style="border-bottom: 0px solid #000000;" colspan="2"><br><br><br></td>
  </tr>
  <tr>
    <td class="tg-c4ww" style="border-top: 0px solid #000000; " colspan="2"><span style="font-weight:bold">Gerente de Talento Humano</span></td>
  </tr>
</table

<div class="pagebreak"> <br></div>
<div class="pagebreak"> <br></div>

<table class="tg">
<tbody>
  <tr>
    <td colspan="8">
      <font color="#000000"><img src="images/logo_CSV.jpg" width=100 height=40 ></font>
	  <font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font>
    </td>  
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="4"><span style="font-weight:bold">CERTIFICACION DE HORAS EXTRAS Y TRABAJO EN DIA LIBRE</span></td>
  </tr>
  <tr>
    <td class="tg-9wq8" colspan="4"><span style="font-weight:bold">FECHA</span>: MIERCOLES 31/08/2022</td>
  </tr>
  <tr>
    <td class="tg-uxaa" colspan="4"><span style="font-weight:bold">TRABAJO A REALIZAR</span></td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="4">TRABAJADORES SE ENCUENTRAN REALIZANDO TRABAJOS EXTRAORDINARIOS EN LA <br>EMPRESA HERMANA BRIQUETERAS DEL ORINOCO</td>
  </tr>
  <tr>
    <td class="tg-9wq8" colspan="4" style="text-align: center;">Certificamos que la prolongación excepcional de jornada conforme a la LOTTT en su <br>Artículo 179, de este trabajo particular, es de carácter eventual o accidental y es solicitado <br>para atender imprevistos o trabajos de emergencia en Matesi S.A / Briqven y que de manera <br>"excepcional", solicitamos se pueda prolongar la duración normal de la jornada de trabajo<br> según la siguiente(s) situación (es):</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="4">Indique con una "X" la razón o razones correspondiente a este caso.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">a) Trabajos preparatorios o complementarios que deban ejecutarse necesariamente <br>fuera de los límites señalados al trabajo general de la entidad de trabajo.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">b) Trabajos que por razones técnicas no pueden interrumpirse a voluntad, o <br>tienen que llevarse a cabo para evitar el deterioro de las materias o de los <br>productos o comprometer el resultado del trabajo.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">c) Trabajos indispensables para coordinar la labor de dos equipos que se <br>relevan.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">d) Trabajos exigidos por la elaboración de inventarios y balances, vencimientos, <br>liquidaciones, finiquitos y cuentas.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">e) Trabajos extraordinarios debido a circunstancias particulares, tales como la de <br>terminación o ejecución de una obra urgente, o atender necesidades de la <br>población en ciertas épocas del año.</td>
  </tr>
  <tr>
    <td class="tg-c3ow"></td>
    <td class="tg-0pky" colspan="3">f) Trabajos "especiales y excepcionales" como reparaciones, modificaciones o <br>instalaciones de maquinarias nuevas, canalizaciones de agua o gas, líneas o <br>conductores de energía eléctrica o telecomunicaciones.</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="4">Estando entendido que la prolongación de la jornada de trabajo no podrá exceder del límite <br>establecido en los reglamentos de la LOTTT o en las resoluciones del Ministerio del Poder Popular para la <br>Protección del Proceso Social del Trabajo. La duración efectiva del trabajo, incluidas las <br>horas extraordinarias, no podrá exceder de diez (10) horas diarias, lo que quiere decir, que al ser <br>el límite diario de la jornada ordinaria de ocho (8) horas, sólo se permitirá trabajar dos (2) <br>horas extras al día semanalmente con lo cual no se podrá laborar más de diez (10) horas extraordinarias <br>y anualmente, no se podrá trabajar más de cien (100) horas extraordinarias. Sin embargo, se <br>establece que cuando se trate de situaciones imprevistas y urgentes conforme al artículo 180 de la <br>LOTTT, se podrá trabajar horas adicionales sin la previa autorización de la Inspectoría, con la <br>condición de que tales motivos sean debidamente comprobados y notificados por Matesi, S.A. / <br>Briqven al día hábil siguiente.</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="2">Solicitado</td>
    <td class="tg-c3ow">Revisado</td>
    <td class="tg-c3ow">Autorizado</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="2"><br><br><br>Freddy Barrios CI: 8.943.842<br>Gerente de mantenimiento</td>
    <td class="tg-c3ow"><br><br><br>Luis Verenzuela CI: 14.576.991 <br>Vicepresidente</td>
    <td class="tg-c3ow"><br><br><br><br>Gerente de Talento Humano<br></td>
  </tr>
</tbody>
</table>

<div class="pagebreak"> <br></div>


<table class="tg2">
<tbody>
  <tr>
    <td colspan="8">
      <font color="#000000"><img src="images/logo_CSV.jpg" width=100 height=40 ></font>
	  <font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font>
    </td>  
  </tr>
  <tr>
    <td  class="tg-c3ow">Puerto Ordaz, MIERCOLES 31/08/2022<br><br>Señores:<br>Inspectoria del Trabajo de Puerto Ordaz Alfredo ManeiroCoordinación del Ministerio del Trabajo del Estado Bolívar.<br>Presente.<br><br>Me dirijo a usted en la oportunidad de solicitar autorización para laborar horas extraordinarias y/o trabajo en día libre, totalizando la cantidad de horas siguientes:<br><br>Labor prevista para los siguientes trabajadores distribuidos en horas extraoridinarias y/o trabajos en día libre como sigue:<br><br>HORAS EXTRAORDINARIAS<br>
    </td>
 </tr>   
 <tr>
    <td>
    <table  class="tg">
		<tbody>
		  <tr>
		    <td class="tg-4k6h" colspan="2">Trabajador(es)</td>
		    <td class="tg-4k6h">C.I.</td>
		    <td class="tg-4k6h">Cargo</td>
		    <td class="tg-4k6h">Fecha</td>
		    <td class="tg-4k6h">Horas</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">BARCELO JULIO</td>
		    <td class="tg-4k6h">12.360.812</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">ARTEAGA DOMINGO</td>
		    <td class="tg-4k6h">20.806.992</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">PACHECO JUAN</td>
		    <td class="tg-4k6h">12.556.479</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">PAEZ PEDRO</td>
		    <td class="tg-4k6h">17.431.835</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">HERNANDEZ LEONARDO</td>
		    <td class="tg-4k6h">12.191.259</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">DIMAS SOTILLO</td>
		    <td class="tg-4k6h">12.359.403</td>
		    <td class="tg-4k6h">MECANICO/ANDAMIERO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">TRINIDAD JUAN</td>
		    <td class="tg-4k6h">15.137.076</td>
		    <td class="tg-4k6h">MECANICO/ANDAMIERO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">BERNANRDO BRIZUELA</td>
		    <td class="tg-4k6h">15.635.220</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		</tbody>
		</table>
    </td>
  </tr>
  <tr>
   <td>  
    <br>TRABAJOS EN DIA LIBRE<br>
</td>
 </tr>   
 <tr>
    <td>
    <table  class="tg">
		<tbody>
		  <tr>
		    <td class="tg-4k6h" colspan="2">Trabajador(es)</td>
		    <td class="tg-4k6h">C.I.</td>
		    <td class="tg-4k6h">Cargo</td>
		    <td class="tg-4k6h">Fecha</td>
		    <td class="tg-4k6h">Horas</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">BARCELO JULIO</td>
		    <td class="tg-4k6h">12.360.812</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">ARTEAGA DOMINGO</td>
		    <td class="tg-4k6h">20.806.992</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">PACHECO JUAN</td>
		    <td class="tg-4k6h">12.556.479</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">PAEZ PEDRO</td>
		    <td class="tg-4k6h">17.431.835</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">HERNANDEZ LEONARDO</td>
		    <td class="tg-4k6h">12.191.259</td>
		    <td class="tg-4k6h">MECANICO/SOLDADOR</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">DIMAS SOTILLO</td>
		    <td class="tg-4k6h">12.359.403</td>
		    <td class="tg-4k6h">MECANICO/ANDAMIERO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">TRINIDAD JUAN</td>
		    <td class="tg-4k6h">15.137.076</td>
		    <td class="tg-4k6h">MECANICO/ANDAMIERO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		  <tr>
		    <td class="tg-4k6h" colspan="2">BERNANRDO BRIZUELA</td>
		    <td class="tg-4k6h">15.635.220</td>
		    <td class="tg-4k6h">MECANICO</td>
		    <td class="tg-4k6h">22/08/2022</td>
		    <td class="tg-4k6h">4</td>
		  </tr>
		</tbody>
		</table>
    </td>
  </tr>
  <tr>
    <td class="tg-c3ow">      
       <br>El motivo que justifican estas horas extraordinarias y/o trabajo en día libre es de carácter eventual o accidental para atender imprevistos o trabajos de emergencia en Matesi, S.A. / Briqven, conforme al artículo 179 de la LOTTT, que se anexan a esta comunicación certificando las razones pertinentes de ley.<br><br>Solicitud que hago a usted en conformidad con lo dispuesto en el articulo 182 de la Ley Orgánica del Trabajo, los trabajadores y las trabajadoras,  de manera de cumplir con el requisito de autorización previa por parte de la Inspectoría del Trabajo y en concordancia con el Articulo 87 de su Reglamento.<br>
      </td>
  </tr>  
  <tr>
    <td align="center">
    <br>
      <table class="tg2" border= 0px align="center">
		<tbody>
		  <tr>
		    <td class="tg-c3ow" colspan="2" align="center">Atentamente,</td>
		  </tr>
		  <tr>
		    <td class="tg-c3ow" colspan="2" align="center"><br><br><br>Jefe de Relaciones Laborales</td>
		  </tr>
		</tbody>
	  </table>       
    </td>
  </tr>
</tbody>
</table>

</html>';

	return $html;
}
?>