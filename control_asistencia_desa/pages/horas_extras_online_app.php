<?php
include("../BD/conexion.php");
 session_start();

$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno      = isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";
$qry_comp   = "";
    if ($_SESSION['nivel_const']==2 )
        $query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS";
    else{
        $query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS WHERE trim(TRABAJADOR_SUP)='".$_SESSION['cedula_session_const']."'";
        $query.=" order by nombre";
        $conn=Conex_oramprd();
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $option=''; $trabajadores='';
        while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false){
	        $trabajadores.=$fila['TRABAJADOR'].", ";      
        }
        $lista_trabajadores=substr($trabajadores,0,-2);
        $qry_comp= " AND CEDULA IN (".$lista_trabajadores.")";
    }
//$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
$qry="SELECT
dbo.SW_Hoja_de_Tiempo_Real.cedula,
dbo.ADAM_DATOS_PERSONALES.NOMBRES,
dbo.ADAM_DATOS_PERSONALES.RELACION_LABORAL,
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
AND dbo.ADAM_DATOS_PERSONALES.CLASE_NOMINA = 'ME'
and Entrada_Esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','FF:FF')";
$qry.=$qry_comp;
//if ($turno!='')
//  $qry.=" AND dbo.ADAM_DATOS_PERSONALES.TURNO ".$turno."9";
if ($trabajador!='NULL')
  if ($turno!='')
    $qry.=" and dbo.SW_Hoja_de_Tiempo_Real.cedula=".$trabajador."";
  else
    $qry.=" and dbo.SW_Hoja_de_Tiempo_Real.cedula=".$trabajador."";

//$qry.=" AND (Inicio_ST1 !='NULL' || Inicio_ST1!='')";
$qry.=" AND (Horas_ST>0)";
$qry.=" ORDER BY
dbo.SW_Hoja_de_Tiempo_Real.cedula ASC,
dbo.SW_Hoja_de_Tiempo_Real.fecha ASC";

buscar($qry);     
       
function buscar($b) {
//       include("../BD/conexion.php");
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
                <th width="5%" class="info"></th>             
                <th width="5%" class="info">CEDULA</th>             
                <th width="15%" class="info">NOMBRE</th>  
                <th width="5%" class="info">FECHA</th>
                <th width="5%" class="info">ENTRADA REAL1</th>
                <th width="5%" class="info">ENTRADA REAL2</th>
                <th width="5%" class="info">SALIDA REAL1</th>
                <th width="5%" class="info">SALIDA REAL2</th>
                <th width="5%" class="info">Entrada Esperada1</th>
                <th width="5%" class="info">Salida Esperada1</th>
                <th width="5%" class="info">Entrada Esperada2</th>
                <th width="5%" class="info">Salida Esperada2</th>
                <th width="5%" class="info">Inicio ST1</th>
                <th width="5%" class="info">Fin St1</th>
                <th width="5%" class="info">Causal ST1</th>
                <th width="5%" class="info">Horas ST</th>
                <th width="5%" class="info">Cod_ausencia</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;
              $limite=75;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

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
                    $inpt .='<td><input type="checkbox" name="autorizado[]" value="'.$row['cedula'].'"></input></td>';                    
                    $inpt .='<td><input type="hidden" name="cedula[]" value="'.$row['cedula'].'"></input>'.$row['cedula'].'</td>';                    
                    $inpt .='<td><input type="hidden" name="NOMBRES[]" value="'.$row['NOMBRES'].'"></input>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td><input type="hidden" name="fecha[]" value="'.$row['fecha'].'"></input>'.$row['fecha'].'</td>';
                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL1[]" value="'.$row['ENTRADA_REAL1'].'"></input>'.$row['ENTRADA_REAL1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL2[]" value="'.$row['ENTRADA_REAL2'].'"></input>'.$row['ENTRADA_REAL2'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="salida_REAL1[]" value="'.$row['salida_REAL1'].'"></input>'.$row['salida_REAL1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="salida_REAL2[]" value="'.$row['salida_REAL2'].'"></input>'.$row['salida_REAL2'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Entrada_Esperada1[]" value="'.$row['Entrada_Esperada1'].'"></input>'.$row['Entrada_Esperada1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Salida_Esperada1[]" value="'.$row['Salida_Esperada1'].'"></input>'.$row['Salida_Esperada1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Entrada_Esperada2[]" value="'.$row['Entrada_Esperada2'].'"></input>'.$row['Entrada_Esperada2'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Salida_Esperada2[]" value="'.$row['Salida_Esperada2'].'"></input>'.$row['Salida_Esperada2'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Inicio_ST1[]" value="'.$row['Inicio_ST1'].'"></input>'.$row['Inicio_ST1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Fin_St1[]" value="'.$row['Fin_St1'].'"></input>'.$row['Fin_St1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Causal_ST1[]" value="'.$row['Causal_ST1'].'"></input>'.$row['Causal_ST1'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Horas_ST[]" value="'.$row['Horas_ST'].'"></input>'.$row['Horas_ST'].'</td>'; 
                    $inpt .='<td><input type="hidden" name="Cod_ausenia[]" value="'.$row['Cod_ausencia'].'"></input>'.$row['Cod_ausencia'].'</td>'; 
                    $inpt .='</tr>';                        
              } 
              if ($contar>0){ 
              $inpt .=' </tbody>
               <tfoot>
                    <tr>
                        <td colspan="18" align="center"> <INPUT id="cmdGuardar" type="button" value="Autorizar Horas extras"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
 <!--                       <th><span class="label label-success">'.$contar.'</span></th>
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
                </table>';
               }
        }
echo $inpt."-".$b;
//print_r($row);
}         
?>
