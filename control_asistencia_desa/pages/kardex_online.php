<?php
session_start();
require_once('funciones_var.php');
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

buscar($trabajador,$turno, $finicio, $ffin);     
       
function buscar($trabajador,$turno, $finicio, $ffin) {
      include("../BD/conexion.php");
      $cn=Conectarse_sitt();
      //$stmt1 = $cn->prepare("EXEC dbo.SP_genera_kardex ?, ?, ?, ?");
      $cedula=null;
      $turnistica=null;

      $sql = "EXEC dbo.SP_genera_kardex ";
      $sql .= $trabajador==="NULL" ? "NULL" : $cn->quote($trabajador);
      $sql .= ", ";
      $sql .= $turno==="NULL" ? "NULL" : $cn->quote($turno);
      $sql .= ", ";
      $sql .= $finicio==="NULL" ? "NULL" : $cn->quote($finicio);
      $sql .= ", ";
      $sql .= $ffin==="NULL" ? "NULL" : $cn->quote($ffin);

      $stmt1 = $cn->prepare($sql);
      $stmt1->execute();
        //$contar = $stmt1->columnCount(); 
       $contar=1;        
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
                <th width="5%" class="info">Motivo Aus.</th>
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
                    $inpt .='<td>'.$row['DESC_COD_HORA'].'</td>'; 
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody></table>';
              $stmt1=null;
              $cn=null;
        }
echo $inpt;
//print_r($row);
}         
?>
