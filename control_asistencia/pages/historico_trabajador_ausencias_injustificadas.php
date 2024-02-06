<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

    $qry="select cedula, anio, sum(horaausencia) as horaausencia, sum(cantidadausencia) as cantidadausencia 
    FROM SW_historico_ausencias";

    if ($trabajador!='NULL' && $trabajador!=''){
      $qry.=" WHERE cedula=".$trabajador."";
    }else{
      $qry.=" WHERE cedula=0";
    }

    $qry.=" group by cedula, anio ORDER BY anio desc";
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
              $inpt = '<b>Hist&oacute;rico de Ausencias Injustificadas</b><table width="100%" class="table table-striped" id="dataTables-example4">';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>A&ntilde;o.</h6></th>
            <th class = "info"><h6>Total horas ausencias </h6></th>
            <th class = "info"><h6>Total dias ausencias </h6></th>
            </tr>
        </thead>
        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    $inpt .='<tr>';
                    $inpt .='<td><h6>'.$row['anio'].'</h6></td>';                    
                    //$inpt .='<td><h6>'.ucwords(strtolower($row['Desc_cargo'])).'</h6></td>';
                    //$inpt .='<td><h6>'.ucwords(strtolower($row['DESC_CCOSTO'])).'</h6></td>';
                    //$inpt .='<td><h6>'.nombre_tipo_suspension($row['tipo']).'</h6></td>';
                    $inpt .='<td align="center"><h6>'.$row['horaausencia'].'</h6></td>';
                    $inpt .='<td align="center"><h6>'.$row['cantidadausencia'].'</h6></td>';
                    $inpt .='</tr>';                                               
              } 
             
              $inpt .=' </tbody>

                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
