<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
    $ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
    $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $ciclo      = isset($_POST["cbociclolaboral"])?$_POST["cbociclolaboral"]:"NULL";


    $qry="SELECT NumReposo, Inicio, Fin,  datediff(d ,  Inicio, Fin ) +1 as dias,  Cod_Adam, desc_cod_hora, Observaciones from SW_Reposos_Historico srh  inner Join ADAM_CODIGO_HORA on Cod_Adam=concepto";

    if ($trabajador!='NULL' && $trabajador!=''){
      $qry.=" where Cedula=".$trabajador."";
    }else{
      $qry.=" where Cedula=0";
    }


    $qry.=" order by NumReposo desc";
    //print $qry;
    buscar($qry);     
}      

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
              $inpt = '<b>Hist&oacute;rico de Reposos</b><table width="100%" class="table table-striped" id="dataTables-example2" border="0" >';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>Num.</h6></th>
            <th class = "info" width="80px"><h6>Inicio</h6></th>
            <th class = "info" width="80px"><h6>Fin</h6></th>
            <th class = "info"><h6>Nro. d&iacute;s</h6></th>
            <th class = "info"><h6>C&oacute;digo</h6></th>
            <th class = "info"><h6>Causa</h6></th>
            <th class = "info"><h6>Observaciones</h6></th>
            </tr>
        </thead>
        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    $inpt .='<tr>';
                    $inpt .='<td><h6>'.$row['NumReposo'].'</h6></td>';                    
                    $inpt .='<td><h6>'.formato_fecha(substr($row['Inicio'],0,10),'-').'</h6></td>';
                    $inpt .='<td><h6>'.formato_fecha(substr($row['Fin'],0,10),'-').'</h6></td>';
                    $inpt .='<td><h6>'.$row['dias'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['Cod_Adam'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['desc_cod_hora'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['Observaciones'].'</h6></td>';
                    $inpt .='</tr>';                                               
              } 
             
              $inpt .=' </tbody>

                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
