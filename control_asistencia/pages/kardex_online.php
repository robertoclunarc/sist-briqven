<?php
session_start();
require_once('funciones_var.php');
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

//$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
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
                <th width="5%"class="info">RL</th>        
                <th width="5%"class="info">CN</th>
                <th width="5%" class="info">Turno</th>
                <th width="5%" class="info">SH</th>
                <th width="5%" class="info">CCOSTO</th>
                <th width="5%" class="info">FECHA</th>
                <th width="5%" class="info">ENT REAL1</th>
                <th width="5%" class="info">ENT REAL2</th>
                <th width="5%" class="info">SAL REAL1</th>
                <th width="5%" class="info">SAL REAL2</th>
                <th width="5%" class="info">ENT ESP1</th>
                <th width="5%" class="info">SAL ESP1</th>
                <th width="5%" class="info">ENT ESP2</th>
                <th width="5%" class="info">SAL ESP2</th>
                <th width="5%" class="info">Inicio ST1</th>
                <th width="5%" class="info">Fin St1</th>
                <th width="5%" class="info">Causal ST1</th>
                <th width="5%" class="info">Ini Ausencia</th>
                <th width="5%" class="info">Fin Ausencia</th>
                <th width="5%" class="info">Horas Neta Presencia</th>
                <th width="5%" class="info">Horas Neta Ausencia</th>
                <th width="5%" class="info">Horas ST</th>
                <th width="5%" class="info">Inicio DLT1</th>
                <th width="5%" class="info">Fin DLT1</th>
                <th width="5%" class="info">Causa DLT1</th>
                <th width="5%" class="info">Cod. Aus</th>
                <th width="5%" class="info">Ced Sustituido</th>
                <th width="5%" class="info">Puesto Sustituido</th>
                <th width="5%" class="info">Ini Sustit</th>
                <th width="5%" class="info">Fin Sustit</th>
                <th width="5%" class="info">Causal Sustit</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;
              $limite=75;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $resultado1=mostrar_esperanza($row['Entrada_Esperada1']);
                    $resultado2=mostrar_esperanza($row['Salida_Esperada1']);
                    $resultado3=mostrar_esperanza($row['Entrada_Esperada2']);
                    $resultado4=mostrar_esperanza($row['Salida_Esperada2']);
                    
                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';                    
                    $inpt .='<td>'.$row['cedula'].'</td>';                    
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.$row['RELACION_LABORAL'].'</td>';
                    $inpt .='<td>'.$row['CLASE_NOMINA'].'</td>';
                    $inpt .='<td>'.$row['TURNO'].'</td>';
                    $inpt .='<td>'.$row['SISTEMA_HORARIO'].'</td>';
                    $inpt .='<td>'.$row['CENTRO_COSTO'].'</td>';
                    $inpt .='<td>'.substr($row['fecha'], 0, 10).'</td>';
                    $inpt .='<td>'.$row['ENTRADA_REAL1'].'</td>'; 
                    $inpt .='<td>'.$row['ENTRADA_REAL2'].'</td>'; 
                    $inpt .='<td>'.$row['salida_REAL1'].'</td>'; 
                    $inpt .='<td>'.$row['salida_REAL2'].'</td>'; 
                    $inpt .='<td><span class="'.$resultado1[0].'">'.$resultado1[1].'</span></td>';
                    $inpt .='<td><span class="'.$resultado2[0].'">'.$resultado2[1].'</span></td>';
                    $inpt .='<td><span class="'.$resultado3[0].'">'.$resultado3[1].'</span></td>';
                    $inpt .='<td><span class="'.$resultado4[0].'">'.$resultado4[1].'</span></td>';
                    $inpt .='<td>'.$row['Inicio_ST1'].'</td>'; 
                    $inpt .='<td>'.$row['Fin_St1'].'</td>'; 
                    $inpt .='<td>'.$row['Causal_ST1'].'</td>'; 
                    $inpt .='<td>'.$row['Inicio_ausencia'].'</td>'; 
                    $inpt .='<td>'.$row['Fin_ausencia'].'</td>'; 
                    $inpt .='<td>'.$row['HorasNetaPresencia'].'</td>'; 
                    $inpt .='<td>'.$row['HorasNetaAusencia'].'</td>'; 
                    $inpt .='<td>'.$row['Horas_ST'].'</td>'; 
                    $inpt .='<td>'.$row['Inicio_DLT1'].'</td>'; 
                    $inpt .='<td>'.$row['Fin_DLT1'].'</td>'; 
                    $inpt .='<td>'.$row['Causa_DLT1'].'</td>'; 
                    $inpt .='<td>'.$row['Cod_ausencia'].'</td>'; 
                    $inpt .='<td>'.$row['Cedula_Sustituido'].'</td>'; 
                    $inpt .='<td>'.$row['Puesto_Sustituido'].'</td>'; 
                    $inpt .='<td>'.$row['Inicio_Sustitucion'].'</td>'; 
                    $inpt .='<td>'.$row['Fin_Sustitucion'].'</td>'; 
                    $inpt .='<td>'.$row['Causal_Sustitucion'].'</td>'; 
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody></table>';
        }
echo $inpt;
//print_r($row);
}         
?>
