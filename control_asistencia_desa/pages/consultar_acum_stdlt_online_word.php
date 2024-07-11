<?php
 session_start();
print_r($_POST);

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
    $cboccosto=" and b.GERENCIA='".$cboccosto."' COLLATE Latin1_General_CI_AS";
  else
    $cboccosto=" ";
  


  $nombre="Reporte de Hotas extras y dias libres trabajados detallado por Trabajador"; 
  $qry="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
  $qry = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $qry);
  /* 
  switch ($tiporeporte) {
      case 1:
          /********************************************************************************************** /
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /********************************************************************************************** / 
          $nombre="Reporte de Horas extras y dias libres trabajados detallado por Trabajador";
          $qry="select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a  INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_ST)>0 union  select a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, Horas_ST + Horas_Dlt as total, b.TRABAJADOR_SUP  from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador left join SW_Causas_STDLT d on d.cod_causa=a.Causal_Sustitucion left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha =f.fecha where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, a.fecha, b.centro_costo, b.desc_ccosto, b.GERENCIA, b.GERGRAL, Horas_ST, Horas_Dlt, f.observaciones, b.TRABAJADOR_SUP  having sum(Horas_Dlt)>0 order by a.cedula, b.nombres, a.fecha";
          break;
      case 2:
          /********************************************************************************************** /
          /*  TOTAL DE HORAS EXTRAS Y DLT POR TRABAJADOR ENTRE UN RANGO DE FECHA 
          /********************************************************************************************** /  
          $nombre="Reporte de Horas extras y dias libres trabajados, resumido por trabajador"; 
          $qry="select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto."  group by a.cedula, b.nombres, b.GERENCIA,b.centro_costo, b.desc_ccosto having sum(Horas_ST)>0 UNION  select a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto, sum(Horas_ST) as Horas_ST, SUM(Horas_Dlt) as Horas_Dlt from sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b on a.cedula = b.trabajador where a.fecha BETWEEN  '".$finicio."' and '".$ffin."'".$trabajador." ".$cboccosto." group by a.cedula, b.nombres, b.GERENCIA, b.centro_costo, b.desc_ccosto having sum(Horas_DLT)>0";
          break;   
  }        
  */
 print "<br>query:".$qry;

buscar($qry, $nombre,$tiporeporte);     
       
function buscar($b, $nombre,$tiporeporte) {
   require_once('funciones_var.php');
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();

        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" background="images/fondo_documento_briqven.jpg">
              <tr>
                <td>
              <font face="nunito,Arial,verdana" SIZE=2><br><br>

Sres.<br>
Inspector&iacute;a del Trabajo de Puerto Ordaz Alfredo Maneiro.<br>
Coordinaci&oacute;n del Ministerio de trabajo del Edo. Bol&iacute;var.<br>
<br>
Presente.<br>
Asunto: entrega del reporte de hora extra generados por los trabajadores.<br>
<br>
<p style="text-align:justify;">
Tengo el honor de dirigirme a usted, en la oportunidad de expresarle un respetuoso saludo institucional, Bolivariana, revolucionario, socialista y chavista, en el nombre de los hombres y mujeres que integran la empresa socialista MATESI, S.A, materiales sider&uacute;rgicas, para la conformaci&oacute;n de la empresa BRIQVEN, briquetera de Venezuela. El presente escrito tiene como finalidad, de hacer entrega el reporte de horas extraordinarias, solicitud que hago a usted en conformidad con los dispuesto en el art&iacute;culo Nº 182 de la  Ley Org&aacute;nica del Trabajo, los Trabajadores y las Trabajadoras, de manera de cumplir con el requisito  de autorizaci&oacute;n previa por parte de la inspector&iacute;a de trabajo y en concordancia con el art&iacute;culo de su reglamento. En tal sentido, se detallan a continuaci&oacute;n: 
</p><br>
                 </td>
               </tr>
               <tr>
                 <td align="center">  
              <table width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example" border="1">';
                       //<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">
              
             /* $inpt = $inpt.'<thead>
            <tr>
                <th colspan="5" style="text-align:center" >'.$nombre.'</th>
            </tr>  
            <tr>
                ';*/

                $colspan=5;
                $inpt.='
                <thead>
            <tr>
                <th colspan="'.$colspan.'" style="text-align:center" >'.$nombre.'</th>
            </tr>  
            <tr>
                <th width="5%" class="info" style="text-align:center">#</th>
                <th width="25%" class="info">Nombre del Trabajador</th>
                <th width="10%" class="info">Cédula</th>
                <th width="10%" class="info">Fecha</th>';

            $inpt.='<th width="5%" class="info">Horas ST</th>              
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $total_ST=0;
              $total_DLT=0;
              //$limite=75;
              $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $total_ST  = $total_ST + $row['Horas_ST'];
                    $total_DLT = $total_DLT + $row['Horas_Dlt'];
                    $clase     = "label label-info";
                    if (isset($row['fecha'])){
                      $fecha     = substr($row['fecha'], 0,10);
                      $separador = '-';
                      $fecha     = formato_fecha($fecha,$separador);
                    }else{
                      $fecha='';
                    } 
                     
                    if (isset($row['observaciones']))
                        $observaciones=$row['observaciones'];
                    else  
                        $observaciones='';

                    $inpt .='<tr>';                     

                    
                        $inpt .='<td width="5%" style="text-align:center">'.$contar.'</td>'; 
                        $inpt .='<td width="35%"><font face="nunito,Arial,verdana" SIZE=2>'.ucfirst($row['nombres']).'</font></td>';
                        $inpt .='<td width="10%"><button type="button"  data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].',\''.$row['fecha'].'\')" data-target="#exampleModalCenter"><font face="nunito,Arial,verdana" SIZE=2>'.$row['cedula'].'</font></button></td>';                        
                        $inpt .='<td width="20%"><font face="nunito,Arial,verdana" SIZE=2>'.$fecha.'</font></td>';

                    $inpt .='<td width="5%" style="text-align:center"><font face="nunito,Arial,verdana" SIZE=2>'.$row['Horas_ST'].'</font></td>';
                  //  $inpt .='<td width="20%"><font face="nunito,Arial,verdana" SIZE=2>'.eliminar_acentos($observaciones).'</font></td>';
                    $inpt .='</tr>';                        
              } 
             $colspan=$colspan-1;
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="'.$colspan.'"><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>Total:</font></span></th>
                        <th><span class="label label-success"><font face="nunito,Arial,verdana" SIZE=2>'.$total_ST.'</font></span></th>
                    </tr>
                    ';
              $inpt .='
                </tfoot>
                </table>';
$inpt .='<br>Sin m&aacute;s a que hacer referencia, me despido de usted.<br><br><br><br>


Atentamente
                    </td>
                  </tr>
                </table>';



                $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    

    '; 
        }
echo $inpt;
//print_r($row);
}         
?>