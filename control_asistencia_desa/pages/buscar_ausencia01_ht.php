<?php
 session_start();
include("../BD/conexion.php");
require_once('funciones_var.php');
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$fichada= isset($_POST["cbofichada"])?$_POST["cbofichada"]:"NULL";
$ccosto= isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";
$sistH= isset($_POST["cbosh"])?$_POST["cbosh"]:"NULL";


$ar_est = array("'LL:LL'","'VV:VV'","'RR:RR'","'PP:PP'","'CS:CS'","'SD:SD'","'FF:FF'", "'N/A'");

if(array_key_exists('chkausencia',$_POST))
{//$mater
  $entrada_esperada1 = $_POST['chkausencia'];  
  foreach ($entrada_esperada1 as &$ent)
  {
    foreach (array_keys($ar_est, $ent) as $key) 
    {
        unset($ar_est[$key]);
    }
  }  
}
$tipos_ausencia = implode(",", $ar_est);
$tipos_ausencia = "(".$tipos_ausencia.")";

$link2=Conex_Contancia_pgsql();
$acceso=permiso_usuario($link2, 'rep01', 'ausencias', $_SESSION['user_session_const']);

$query="select trabajador from trabajadores_excluidos where motivo_exclusion in ('SUSPENCION', 'COMISION', 'EGRESO', 'SINDICATO', 'MINTRASS', 'JUBILADOS') and (exclusion_hasta<now() or exclusion_hasta is null)";
      $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
      $numReg = ejecutar_num_rows($result);
      if($numReg>0){
        $exclusion='(';
        while ($fila=ejecutar_fetch_array($result)) 
        {
            $exclusion.= $fila['trabajador'].",";                      
        }
        $exclusion = substr($exclusion, 0, -1).")";
      }
      else
         $exclusion='(0)';

pg_close($link2);

$qry="select a.cedula, b.nombres, a.fecha, a.entrada_real1, a.salida_real1,   a.entrada_esperada1 as entrada, CASE WHEN b.sistema_horario in (13) THEN a.salida_esperada2  ELSE a.salida_esperada1 END as salida,
horasnetapresencia, CASE WHEN horasnetaausencia=0 and horasnetapresencia=0 THEN 8 else  horasnetaausencia end as horasnetaausencia,cod_ausencia, b.sistema_horario, b.desc_puesto, b.desc_ccosto
from sw_hoja_de_tiempo_real a, adam_datos_personales b
where a.cedula = b.trabajador
and b.trabajador not in ".$exclusion." 
and b.clase_nomina = 'ME'
and fecha between '".$finicio."' and '".$ffin."' 
and entrada_esperada1 not in ".$tipos_ausencia." 
and (horasnetaausencia > 0 or (Entrada_Real1 is null and salida_Real1 is null))
and (cod_ausencia not in (32,33,34,44,72) or cod_ausencia is null)";

 if (!$acceso){   
    $lista_trabajadores=lista_trabajadores('ct');
    $lista_trabajadores = str_replace("'", "", $lista_trabajadores); 
 }

 if ($trabajador!="NULL")
    $qry.="and cedula=".$trabajador." ";
 elseif (!$acceso) {
    $qry.=" and b.trabajador in (".$lista_trabajadores.") ";
 } 

 if ($ccosto!="NULL")
   $qry.=" and b.centro_costo=".$ccosto." ";
 elseif (!$acceso) {
    $qry.=" and b.centro_costo in (select distinct centro_costo from adam_datos_personales where trabajador_sup=".$_SESSION['cedula_session_const'].") ";
 }

 if ($sistH!="NULL")
    $qry.=" and sistema_horario=".$sistH." ";

 if ($fichada!="NULL")
    $qry.=" and ".$fichada; 
  

 $qry.=" order by a.cedula, a.fecha, b.sistema_horario";
//echo $qry;
 buscar($qry);     
       
function buscar($b) {
       //include("../BD/conexion.php");
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table border="1" width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
              
            <tr>
                <th width="5%" class="info">Cedula</th>
                <th width="5%" class="info">Nombre</th> 
                <th width="5%" class="info">Puesto</th>
                <th width="5%" class="info">Desc. CCosto</th>
                <th width="5%" class="info">Fecha</th> 
                <th width="5%" class="info">Ent. Real 1</th>              
                <th width="5%" class="info">Sal. Real 1</th>                
                <th width="5%" class="info">Ent. Esp. 1</th>              
                <th width="5%" class="info">Sal. Esp. 1</th>                
                <th width="5%" class="info">H.P.</th>
                <th width="5%" class="info">H.A.</th>
                <th width="5%" class="info">S.H.</th>
                <th width="5%" class="info">Cod. Ausencia</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $suma_aus=0;              
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    if ($row['horasnetaausencia']=='')
                      $ha=0;
                    else
                      if ($row['horasnetaausencia']==0 && $row['horasnetapresencia']==0)
                          $ha=8;
                      else
                        $ha=round($row['horasnetaausencia'],2);

                    $suma_aus=$suma_aus+$ha;
                    $porc=$row['entrada_real1'];
                    $pord=$row['salida_real1'];

                    $entrada_E=mostrar_esperanza($row['entrada']);
                    $salida_E=mostrar_esperanza($row['salida']);


                    $fecha = substr($row['fecha'], 0,10);

                    if ($porc=='NULL' || $porc=='null' || $porc=='')
                    {
                      $porc='--';
                      $clasec="label label-danger";                        
                    }    
                    else
                    { 
                      $clasec=""; //label label-info                    
                    } 
                    if ($pord=='NULL' || $pord=='null' || $pord=='')
                    {
                      $pord='--';
                      $clased="label label-danger";                        
                    }    
                    else
                    { 
                      $clased=""; //label label-info                    
                    }                    

                    $inpt .='<tr>';                     
                    $inpt .='<td>'.$row['cedula'].'</td>'; 
                    $inpt .='<td>'.$row['nombres'].'</td>';
                    $inpt .='<td>'.$row['desc_puesto'].'</td>';
                    $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                    $inpt .='<td>'.$fecha.'</td>';
                    $inpt .='<td><span class="'.$clasec.'">'.$porc.'</span></td>';
                    $inpt .='<td><span class="'.$clased.'">'.$pord.'</span></td>';
                    $inpt .='<td><span class="'.$entrada_E[0].'">'.$entrada_E[1].'</span></td>';
                    $inpt .='<td><span class="'.$salida_E[0].'">'.$salida_E[1].'</span></td>';                   
                    $inpt .='<td>'.$row['horasnetapresencia'].'</td>';
                    $inpt .='<td>'.$ha.'</td>';
                    $inpt .='<td>'.$row['sistema_horario'].'</td>';
                    $inpt .='<td>'.$row['cod_ausencia'].'</td>';                   
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th></th>
                        <th></th>
                        <th><span class="label label-danger"></span></th>
                        <th><span class="label label-danger"></span></th>
                        <th></th>
                        <th></th>
                        <th></th> 
                        <th><span class="label label-info">Total Horas Ausencia:</span></th> 
                        <th><span class="label label-danger">'.$suma_aus.'</span></th>
                        <th></th>
                        <th></th>               
                    </tr>
                </tfoot>
                </table>';
        }
echo $inpt;

}         
?>