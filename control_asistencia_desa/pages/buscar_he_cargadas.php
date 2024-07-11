<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
//$nombre= isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
$ccosto= isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";

/*$qry="select cedula, nombres, fecha, entrada_real1, salida_real1,  entrada_real2, salida_real2 ,
 entrada_esperada1, salida_esperada1,  entrada_esperada2, salida_esperada2,
 horasnetapresencia, horasnetaausencia, b.centro_costo, b.desc_ccosto from sw_hoja_de_tiempo_real a, adam_datos_personales b
 where a.cedula=b.trabajador and fecha between '".$finicio."' and '".$ffin."'";
*/
 
 $qry="SELECT a.trabajador, b.nombre, b.ccosto, b.detalle_ccosto, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1 
FROM public.registro_diario a, adam_vw_dotacion_briqven_02_mas b 
where a.trabajador=b.trabajador and sobre_tiempo='N' and fecha  between '".$finicio."' and '".$ffin."'";
 
 if ($trabajador!="NULL")
  $qry.="and cedula=".$trabajador." ";

 if ($ccosto!="NULL")
   $qry.="and b.centro_costo=".$ccosto." ";

$qry.=" order by cedula,fecha";

echo $qry;

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
              $inpt = '<table border="1" width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
              
            <tr>
                <th width="5%" class="info">Cedula</th>
                <th width="5%" class="info">Nombre</th> 
                <th width="5%" class="info">CCosto</th>
                <th width="5%" class="info">Desc. CCosto</th>
                <th width="5%" class="info">Fecha</th> 
                <th width="5%" class="info">Ent. Real 1</th>              
                <th width="5%" class="info">Sal. Real 1</th>  
                <th width="5%" class="info">Ent. Real 2</th>              
                <th width="5%" class="info">Sal. Real 2</th>
                <th width="5%" class="info">Ent. Esp. 1</th>              
                <th width="5%" class="info">Sal. Esp. 1</th>
                <th width="5%" class="info">Ent. Esp. 2</th>              
                <th width="5%" class="info">Sal. Esp. 2</th>
                <th width="5%" class="info">H.P.</th>
                <th width="5%" class="info">H.A.</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;              
              $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $clase="label label-info";
                    $porc=$row['entrada_real1'];
                    $pord=$row['salida_real1'];
                    $fecha = substr($row['fecha'], 0,10);
                    if ($porc=='' || $porc=='NULL' || is_null($porc))
                    {
                        if (!in_array($row['entrada_esperada1'], $libres))
                        {  
                          $clase="label label-danger";
                          $menor_a++;
                          $porc="--";
                          $pord="--";
                        }  
                    }    
                    else
                    { 
                      $mayor_a++;
                    }

                    $inpt .='<tr>';                     
                    $inpt .='<td>'.$row['trabajador'].'</td>'; 
                    $inpt .='<td>'.$row['nombres'].'</td>';
                    $inpt .='<td>'.$row['centro_costo'].'</td>';
                    $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                    $inpt .='<td>'.$fecha.'</td>';
                    $inpt .='<td><span class="'.$clase.'">'.$porc.'</span></td>';
                    $inpt .='<td><span class="'.$clase.'">'.$pord.'</span></td>';
                    $inpt .='<td>'.$row['entrada_real2'].'</td>';
                    $inpt .='<td>'.$row['salida_real2'].'</td>';
                    $inpt .='<td>'.$row['entrada_esperada1'].'</td>';
                    $inpt .='<td>'.$row['salida_esperada1'].'</td>';
                    $inpt .='<td>'.$row['entrada_esperada2'].'</td>';
                    $inpt .='<td>'.$row['salida_esperada2'].'</td>';
                    $inpt .='<td>'.$row['horasnetapresencia'].'</td>';
                    $inpt .='<td>'.$row['horasnetaausencia'].'</td>';                   
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th><span class="label label-success">Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th><span class="label label-info">Asist.</span></th>
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
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>
