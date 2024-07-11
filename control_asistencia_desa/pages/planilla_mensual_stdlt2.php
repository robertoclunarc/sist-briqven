<?php
  session_start();
  include("../BD/conexion.php");
  require_once('funciones_var.php');

  if ($_GET['method']=='1'){
    //print "<br>Paso1";
      $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
      $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
      $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
      $cboccosto  = isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";
      $nombre     = isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
  }else{
    //print "<br>Paso2";
      $finicio    = isset($_GET["i"])?$_GET["i"]:"NULL";         //
      $ffin       = isset($_GET["f"])?$_GET["f"]:"NULL";
      $trabajador = isset($_GET["t"])?$_GET["t"]:"NULL";
      $cboccosto  = isset($_GET["c"])?$_GET["c"]:"NULL";
      $nombre     = isset($_GET["n"])?$_GET["n"]:"NULL";
  }  
      
  //print $nombre;

  if ($trabajador!='NULL') 
    $trabajador=" and a.cedula=".$trabajador;
  else
    $trabajador=" ";

  if ($cboccosto!='NULL') 
    $ccosto=" and b.GERENCIA ='".$cboccosto."'";
  else
    $ccosto=" ";
  
          $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador";
          $query="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$ccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$ccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
         // $query_gerente="select trabajador, nombre, gerencia from adam_VW_DOTACION_BRIQVEN_02_MAS where grado_trab = '41' ".$cboccosto;
          $query = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $query);

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

    $tabla             = '<tr>';
    $columna           = '1';
    $contador          = 1;
    $total_st1         = 0;
    $total_dlt1        = 0;
    $total_st2         = 0;
    $total_dlt2        = 0;

    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
    	$TRABAJADOR = trim($row['cedula']);
    	$NOMBRES    = $row['nombres'];
      $fecha      = substr($row['fecha'], 0,10); 
      $fecha      = formato_fecha($fecha,'-');
      

      $tabla.='
        <td class="tg-9wq8">'.$TRABAJADOR.'</td>
        <td class="tg-lboi" colspan="2">'.$NOMBRES.'</td>
        <td class="tg-lboi">'.$fecha.'</td>
        <td class="tg-9wq8">'.$row['Horas_ST'].'</td>
        <td class="tg-9wq8">'.$row['Horas_Dlt'].'</td>
        <td class="tg-9wq8"></td>
        <td class="tg-lboi" colspan="4">'.$row['observaciones'].'</td>
      ';

      //if ($columna=='2'){
        $tabla     .='</tr><tr>';
      //  $columna    = '1';
        $total_st1  = $total_st1  + $row['Horas_ST'];
        $total_dlt1 = $total_dlt1 + $row['Horas_Dlt'];
      //}elseif ($columna=='1'){
      //  $total_st2  = $total_st2  + $row['Horas_ST'];
      //  $total_dlt2 = $total_dlt2 + $row['Horas_Dlt'];
      //  $columna    = '2';
      //}

      $contador++;
    	$CENTRO_COSTO   = $row['centro_costo'];
    	$GERENCIA       = $row['GERENCIA'];
    	$TRABAJADOR_SUP = $row['TRABAJADOR_SUP'];
    }
    /*if ($columna=='2'){
      $tabla.='
        <td class="tg-9wq8"></td>
        <td class="tg-lboi"></td>
        <td class="tg-lboi"></td>
        <td class="tg-9wq8"></td>
        <td class="tg-9wq8"></td>
        <td class="tg-lboi"></td>
        </tr>';
    } */
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
        <th class="tg-c3ow" colspan="7">Solicitud de horas extras y trabajo en dia libre</th>
        <th class="tg-0pky"colspan="2"><font color="#000000"><img src="images/logo_BRIQVEN.jpg" align="right" width=100 height=40 ></font></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-c3ow" colspan="11">Datos del solicitante</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Nombre y apellido del solicitante</td>
        <td class="tg-0pky" colspan="3">'.
          $nombre_gerente.'</td>
        <td class="tg-lboi" colspan="2">No. Cedula</td>
        <td class="tg-lboi" colspan="2">'.$cedula_gerente.'</td>
        <td class="tg-0pky">Fecha</td>
        <td class="tg-0pky">'.$hoy.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia</td>
        <td class="tg-0pky" colspan="4">'.$nombre_gerencia.'</td>
        <td class="tg-0pky" colspan="2">Cargo</td>
        <td class="tg-0pky" colspan="3">'.$desc_puesto.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Gerencia General</td>
        <td class="tg-0pky" colspan="4">'.mb_convert_encoding($GERENCIA, 'UTF-8', 'Windows-1252').'</td>
        <td class="tg-0pky" colspan="2">Gerente General</td>
        <td class="tg-0pky" colspan="3">'.$gerente.'</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Tipo de autorizacion</td>
        <td class="tg-0pky" colspan="3">Horas extras extraordinarias</td>
        <td class="tg-9wq8">'.$total_st.'</td>
        <td class="tg-0pky" colspan="2">Trabajo de dias libre</td>
        <td class="tg-9wq8" colspan="3">'.$total_dlt .'</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="11">CERTIFICACION DE HORAS EXTRAS Y TRABAJO EN DIA LIBRE</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="11">Razones correspondiente a Horas Extras y DLT</td>
      </tr>      
      <tr>
        <td class="tg-0pky" colspan="11">A.- Trabajos preparatorios o complementarios que deban ejecutarse necesariamente fuera de los límites señalados al trabajo general de la entidad de Trabajo.</td>
      </tr>      
      <tr>
        <td class="tg-0pky" colspan="11">B.- Trabajos que por razones técnicas no pueden interrumpirse a voluntad, o tienen que llevarse a cabo para evitar el deterioro de las materias o de los productos o comprometer el resultado del trabajo.</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="11">C.- Trabajos indispensables para coordinar la labor de dos equipos que se relevan.</td>
      </tr> 
      <tr>
        <td class="tg-0pky" colspan="11">D.- Trabajos exigidos por la elaboración de inventarios y balances, vencimientos, liquidaciones, finiquitos y cuentas.</td>
      </tr>  
      <tr>
        <td class="tg-0pky" colspan="11">E.- Trabajos extraordinarios debido a circunstancias particulares, tales como la de terminación o ejecución de una obra urgente, o atender necesidades de la población en ciertas épocas del año.</td>
      </tr>  
      <tr>
        <td class="tg-0pky" colspan="11">F.- Trabajos "especiales y excepcionales" como reparaciones, modificaciones o instalaciones de maquinarias nuevas, canalizaciones de agua o gas, Líneas o conductores de energía eléctrica o telecomunicaciones.</td>
      </tr>         
      <tr>
        <td class="tg-9wq8">Cedula<br></td>
        <td class="tg-9wq8" colspan="2">Nombre</td>
        <td class="tg-9wq8">Fecha<br></td>
        <td class="tg-9wq8">Horas<br>Extras</td>
        <td class="tg-9wq8">Horas de <br>DLT</td>
        <td class="tg-9wq8">Raz&oacute;n</td>
        <td class="tg-9wq8" colspan="4">Motivo</td>
      </tr>
      '.$tabla.'
      <tr>
        <td class="tg-9wq8" colspan="4">Subtotal</td>
        <td class="tg-9wq8">'.$total_st1.'</td>
        <td class="tg-9wq8">'.$total_dlt1.'</td>
        <td class="tg-0pky" colspan="5"></td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="5" width="33">SOLICITADO POR</td>
        <td class="tg-c3ow" colspan="4" width="33">REVISADO</td>
        <td class="tg-c3ow" colspan="2" width="33">AUTORIZADO</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="5" width="33"><br><br><br>'.$nombre_gerente.', C.I.: '.$cedula_gerente.'<br>'.$nombre_gerencia.'</td>
        <td class="tg-pb0m" colspan="4" width="33"><br><br><br>Luis Verenzuela CI: 14.576.991<br>Vicepresidente</td>
        <td class="tg-pb0m" colspan="2" width="33"><br><br><br>'.$nombre_gerente_RRHH.', C.I.: '.$cedula_gerente_RRHH.'<br>Gerente de Talento Humano</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="10" rowspan="2">Contando con las autorizaciones correspondientes se informa a la unidad <br>de "Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) indicado en esta planilla.</td>
        <td class="tg-0pky" colspan="1"></td>
      </tr>
      <tr>
        
        <td class="tg-pb0m" colspan="1">Gerente de Talento Humano</td>
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
        <td class="tg-0pky" colspan="5">Subtotal:</td>
        <td class="tg-9wq8">'.$total_st1.'</td>
        <td class="tg-9wq8">'.$total_dlt1.'</td>
        <td class="tg-0pky" colspan="4"></td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="2">SOLICITADO POR</td>
        <td class="tg-c3ow" colspan="2">REVISADO</td>
        <td class="tg-c3ow" colspan="2">AUTORIZADO</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="2"><br><br><br>AREVALO YUDISAY V-14509326<br>Gerente de mantenimiento</td>
        <td class="tg-pb0m" colspan="2"><br><br><br>Luis Verenzuela CI: 14.576.991<br>vicepresidente</td>
        <td class="tg-pb0m" colspan="2"><br><br><br><br>Gerente de Talento Humano</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="4" rowspan="2">Contando con las autorizaciones correspondientes se informa a la unidad <br>de "Nominas" a proceder con el pago de horas extras o día libre trabajado (DLT) indicado en esta planilla.</td>
        <td class="tg-0pky" colspan="2"></td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="2">Gerente de Talento Humano</td>
      </tr>
      ';

echo $html;     
//echo '<INPUT id="cmdGuardar" type="button" value="Planilla EXCEL" class="btn btn-success" onclick="exportar_excel_cuadro();"/> ';
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