<?php
session_start();
require_once('funciones_var.php');
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

/*$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
$qry="SELECT cedula, NOMBRES, FECHA, CONCEPTO, DESC_COD_HORA, TIEMPO, REFERENCIA_04, DIRECCION, GERGRAL, GERENCIA, SUPTCIA, REFERENCIA_01, REFERENCIA_03, CAUSA_SUSTITUCION, SUSTITUIDO
FROM matesisitt.dbo.VW_kardex_temporal where fecha BETWEEN '".$finicio."' AND '".$ffin."'"; // ORDER BY cedula,fecha";*/

$qry="SELECT
dbo.SW_Hoja_de_Tiempo_Real.cedula,
dbo.ADAM_DATOS_PERSONALES.NOMBRES,
dbo.ADAM_DATOS_PERSONALES.RELACION_LABORAL,
dbo.ADAM_DATOS_PERSONALES.CLASE_NOMINA,
dbo.ADAM_DATOS_PERSONALES.TURNO,
dbo.ADAM_DATOS_PERSONALES.SISTEMA_HORARIO,
dbo.ADAM_DATOS_PERSONALES.CENTRO_COSTO,
dbo.SW_Hoja_de_Tiempo_Real.fecha,
ENTRADA_REAL1,
ENTRADA_REAL2,
salida_REAL1,
salida_REAL2,
dbo.SW_Hoja_de_Tiempo_Real.Entrada_Esperada1,
dbo.SW_Hoja_de_Tiempo_Real.Salida_Esperada1,
dbo.SW_Hoja_de_Tiempo_Real.Entrada_Esperada2,
dbo.SW_Hoja_de_Tiempo_Real.Salida_Esperada2,
dbo.SW_Hoja_de_Tiempo_Real.Inicio_ST1,
dbo.SW_Hoja_de_Tiempo_Real.Fin_St1,
dbo.SW_Hoja_de_Tiempo_Real.Causal_ST1,
dbo.SW_Hoja_de_Tiempo_Real.Inicio_ausencia,
dbo.SW_Hoja_de_Tiempo_Real.Fin_ausencia,
dbo.SW_Hoja_de_Tiempo_Real.HorasNetaPresencia,
dbo.SW_Hoja_de_Tiempo_Real.HorasNetaAusencia,
dbo.SW_Hoja_de_Tiempo_Real.Horas_ST,
dbo.SW_Hoja_de_Tiempo_Real.Inicio_DLT1,
dbo.SW_Hoja_de_Tiempo_Real.Fin_DLT1,
dbo.SW_Hoja_de_Tiempo_Real.Causa_DLT1,
dbo.SW_Hoja_de_Tiempo_Real.Cod_ausencia,
dbo.SW_Hoja_de_Tiempo_Real.Cedula_Sustituido,
dbo.SW_Hoja_de_Tiempo_Real.Puesto_Sustituido,
dbo.SW_Hoja_de_Tiempo_Real.Inicio_Sustitucion,
dbo.SW_Hoja_de_Tiempo_Real.Fin_Sustitucion,
dbo.SW_Hoja_de_Tiempo_Real.Causal_Sustitucion
FROM
dbo.ADAM_DATOS_PERSONALES
INNER JOIN dbo.SW_Hoja_de_Tiempo_Real ON dbo.SW_Hoja_de_Tiempo_Real.cedula = dbo.ADAM_DATOS_PERSONALES.Trabajador
WHERE
dbo.SW_Hoja_de_Tiempo_Real.fecha BETWEEN '".$finicio."' AND '".$ffin."'
AND dbo.ADAM_DATOS_PERSONALES.CLASE_NOMINA = 'ME'";
if ($turno!='')
  $qry.=" AND dbo.ADAM_DATOS_PERSONALES.TURNO ".$turno."9";

if ($trabajador!='NULL')
  if ($turno!='')
    $qry.=" and dbo.SW_Hoja_de_Tiempo_Real.cedula=".$trabajador."";
  else
    $qry.=" and dbo.SW_Hoja_de_Tiempo_Real.cedula=".$trabajador."";
$qry.=" ORDER BY
dbo.SW_Hoja_de_Tiempo_Real.cedula ASC,
dbo.SW_Hoja_de_Tiempo_Real.fecha ASC";


/*if ($trabajador!='NULL')
    $qry.="and cedula=".$trabajador;

$qry.=" ORDER BY cedula,FECHA";*/

//print $qry;

buscar($qry);     
       
function buscar($b) {
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
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info"></th>             
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombre</th>  
                <th width="15%" class="info">FECHA</th>
                <th width="5%" class="info">CONCEPTO</th>
                <th width="5%" class="info">DESCRIPCION DEL CONCEPTO</th>
                <th width="5%" class="info">HORAS</th>
                <th width="5%" class="info">JORNADA</th>
                <th width="5%" class="info">CENTRO DE COSTO</th>
                <th width="5%" class="info">DESCRIPCION CENTRO DE COSTO</th>
                <th width="5%" class="info">TIEMPO</th>
                <th width="5%" class="info">COD. SUSTITUCION</th>
                <th width="5%" class="info">SUSTITUIDO</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;
              $limite=75;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
  /*                  $resultado1=mostrar_esperanza($row['Entrada_Esperada1']);
                    $resultado2=mostrar_esperanza($row['Salida_Esperada1']);
                    $resultado3=mostrar_esperanza($row['Entrada_Esperada2']);
                    $resultado4=mostrar_esperanza($row['Salida_Esperada2']);
*/
  /*                  $porc=round($row['porc_presencia'],2);
                    if ($porc<$limite)
                    {
                        $clase="label label-danger";
                        $menor_a++;
                    }    
                    else
                    { 
                      $clase="label label-info";
                      $mayor_a++;
                    }

*/
                    
                    
                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';                    
                    $inpt .='<td>'.$row['cedula'].'</td>';                    
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.substr($row['FECHA'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['CONCEPTO'].'</td>'; 
                    $inpt .='<td>'.$row['DESC_COD_HORA'].'</td>'; 
                    $inpt .='<td>'.$row['TIEMPO'].'</td>'; 
                    $inpt .='<td>'.$row['REFERENCIA_04'].'</td>'; 
                    $inpt .='<td>'.$row['REFERENCIA_01'].'</td>'; 
                    $inpt .='<td>'.$row['SUPTCIA'].'</td>'; 
                    $inpt .='<td>'.$row['REFERENCIA_03'].'</td>'; 
                    $inpt .='<td>'.$row['CAUSA_SUSTITUCION'].'</td>'; 
                    $inpt .='<td>'.$row['SUSTITUIDO'].'</td>'; 
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
<!--              <tfoot>
                    <tr>
                        <th><span class="label label-success">Cant. Reg. Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th><span class="label label-info">Cant. Mayor a '.$limite.'%</span></th>
                        <th><span class="label label-info">'.$mayor_a.'</span></th>
                        <th><span class="label label-danger">Resto:</span></th>
                        <th><span class="label label-danger">'.$menor_a.'</span></th>
                        <th></th>
                        <th></th>
                        <th></th> 
                        <th></th> 
                        <th></th>                                                
                    </tr>
                </tfoot>
-->
                </table>
                <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
                <script>
    $(document).ready(function() {
        $("#dataTables-example").DataTable({
            responsive: true, "bFilter": false,
            "order": [[ 1, "desc" ]]
        });
    });
    </script>';
        }
echo $inpt;
//print_r($row);
}         
?>
