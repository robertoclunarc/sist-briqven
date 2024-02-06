<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

$qry="select fecha, entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2, entrada_real1, entrada_real2, salida_real1, salida_real2 from sw_hoja_de_tiempo_real where fecha between '".$finicio."' and '".$ffin."' and cedula=".$trabajador;
$qry.=" order by fecha desc";

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
              $inpt = '<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="10%">Fecha</th>
                <th width="10%">Ent. Esp. 1</th>                 
                <th width="10%">Sal. Esp. 1</th>
                <th width="10%">Ent. Esp. 2</th>
                <th width="10%">Sal. Esp. 2</th>
                <th width="10%">Ent. Real 1</th>
                <th width="10%">Sal. Real 1</th>
                <th width="10%">Ent. Real 2</th>
                <th width="10%">Sal. Real 2</th>
            </tr>
        </thead>
        <tbody>';                            
              $limite=array('LL:LL','RR:RR','VV:VV','CS:CS','SD:SD','PP:PP','FF:FF');
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){                    
                    $rest = substr($row['fecha'], 0,10);
                    $porc=$row['entrada_esperada1'];
                    if (in_array($porc, $limite))
                    {
                        $clase="label label-danger";                        
                    }    
                    else
                    { 
                      $clase="label label-info";                     
                    }

                    $inpt .='<tr>';
                    $inpt .='<td><span class="'.$clase.'">'.$rest.'</span></td>';
                    $inpt .='<td>'.$row['entrada_esperada1'].'</td>';
                    $inpt .='<td>'.$row['salida_esperada1'].'</td>';
                    $inpt .='<td>'.$row['entrada_esperada2'].'</td>';
                    $inpt .='<td>'.$row['salida_esperada2'].'</td>';
                    $inpt .='<td>'.$row['entrada_real1'].'</td>';
                    $inpt .='<td>'.$row['salida_real1'].'</td>';
                    $inpt .='<td>'.$row['entrada_real2'].'</td>';
                    $inpt .='<td>'.$row['salida_real2'].'</td>';
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>              
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>