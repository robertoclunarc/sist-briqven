<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
if ($turno!='')
  $qry.=" where turno ".$turno."9";
if ($trabajador!='NULL')
  if ($turno!='')
    $qry.=" and cedula=".$trabajador."";
  else
    $qry.=" WHERE cedula=".$trabajador."";
$qry.=" order by 11 desc,1";

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
                <th width="5%" class="info">Cedula</th>             
                <th width="15%" class="info">Nombres</th>  
                <th width="5%"class="info">CCosto</th>        
                <th width="10%"class="info">Desc. CCosto</th>
                <th width="5%" class="info">Ger. Gral.</th>
                <th width="5%" class="info">Turno</th>
                <th width="5%" class="info">Horas Pres.</th>
                <th width="5%" class="info">Dias Pres.</th>
                <th width="5%" class="info">Jorn.</th>
                <th width="5%" class="info">Horas Total Jorn.</th>
                <th width="5%" class="info">%</th>
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              $mayor_a=0;
              $menor_a=0;
              $limite=95;
              $sumahoraspresencia=0;
              $sumahorastotaljornada =0;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    $sumahoraspresencia    = $sumahoraspresencia    + $row['horaspresencia'];
                    $sumahorastotaljornada = $sumahorastotaljornada + $row['horastotaljornada'];

                    $porc=round($row['porc_presencia'],2);
                    if ($porc<$limite)
                    {
                        $clase="label label-danger";
                        $menor_a++;
                    }else{ 
                      $clase="label label-info";
                      $mayor_a++;
                    }

                    $nombre="'".$row['nombres']."'";

                    $inpt .='<tr>';
                    $inpt .='<td><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';

                    if (is_float($row['horaspresencia']) || (strrpos($row['horaspresencia'], '.')) || (strrpos($row['horaspresencia'], ','))){
                        $horaspresencia = number_format($row['horaspresencia'],2,',','.');
                    }else{
                        $horaspresencia = $row['horaspresencia'];
                    } 

                    if (is_float($row['horastotaljornada']) || (strrpos($row['horastotaljornada'], '.')) || (strrpos($row['horastotaljornada'], ','))){
                        $horastotaljornada = number_format($row['horastotaljornada'],2,',','.');
                    }else{
                        $horastotaljornada = $row['horastotaljornada'];
                    } 

                    if (is_float($row['jornadas']) || (strrpos($row['jornadas'], '.')) || (strrpos($row['jornadas'], ','))){
                        $jornadas = number_format($row['jornadas'],2,',','.');
                    }else{
                        $jornadas = $row['jornadas'];
                    } 

                    $inpt .='<td>'.$row['nombres'].'</td>';
                    $inpt .='<td>'.$row['centro_costo'].'</td>';
                    $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                    $inpt .='<td>'.$row['gergral'].'</td>';
                    $inpt .='<td>'.$row['turno'].'</td>';
                    $inpt .='<td>'.$horaspresencia.'</td>';
                    $inpt .='<td>'.$row['diaspresencia'].'</td>';
                    $inpt .='<td>'.$jornadas.'</td>'; 
                    $inpt .='<td>'.$horastotaljornada.'</td>'; 
                    $inpt .='<td><span class="'.$clase.'">'.$porc.'</span></td>';                   
                    $inpt .='</tr>';                       
              } 
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th><span class="label label-success">Cant. Reg. Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th><span class="label label-info">Cant. Mayor a '.$limite.'%</span></th>
                        <th><span class="label label-info">'.$mayor_a.'</span></th>
                        <th><span class="label label-danger">Resto:</span></th>
                        <th><span class="label label-danger">'.$menor_a.'</span></th>
                        <th><span class="label label-info">'.number_format($sumahoraspresencia,2,',','.').'</span></th>
                        <th></th>
                        <th></th> 
                        <th><span class="label label-info">'.number_format($sumahorastotaljornada,2,',','.').'</span></th> 
                        <th></th>                                                
                    </tr>
                </tfoot>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>