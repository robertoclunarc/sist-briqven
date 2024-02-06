<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $conFoto     = isset($_POST["conFoto"])?$_POST["conFoto"]:false;

    $qry="select i.trabajador, b.nombre,i.dato_09 as empresa, i.dato_10 as cargo, to_date(i.dato_21 , 'DD/MM/YY') as inicio, to_date(i.dato_22 , 'DD/MM/YY') as fin
from inf_soc_trabajador i inner join vw_dotacion_briqven_02_mas b on i.trabajador=b.trabajador and i.indice_inf_soc='EXPERIENCI' 
";
    if ($trabajador!='NULL')
    {
      $qry.=" WHERE b.TRABAJADOR =".$trabajador."";
    }  

    $qry.=" order by i.trabajador, to_date(i.dato_21 , 'DD/MM/YY')";


    //print $qry;
    buscar($qry);     
}else
      echo "Debe Iniciar Sesion";       

function buscar($b) {
          include("../BD/conexion.php");
          $cn=Conex_oramprd();
          $stid = oci_parse($cn, $b);
          oci_execute($stid);
          $contar=1;        
            if($contar == 0){
                  $inpt = "No se han encontrado resultados!";
                  
            }else{
              $inpt = '<b>Experiencia Laboral</b><table width="100%" class="table table-striped" id="dataTables-example01" border="0" >';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>#.</h6></th>
            <th class = "info"><h6>Empresa</h6></th>
            <th class = "info"><h6>Cargo</h6></th>
            <th class = "info"><h6>Desde</h6></th>
            <th class = "info"><h6>Hasta</h6></th>
            <th class = "info"><h6>Tiempo de Servicio</h6></th>
            </tr>
        </thead>
        <tbody>';
                  
                $contar=0;
                while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {   
                  //$fecha_nacimiento = str_replace('/', '-', $row['FECHA_NAC']);
                  $contar++;
                  $inpt .='<tr>';
                  $inpt .='<td><h6>'.$contar.'</h6></td>';                    
                  $inpt .='<td><h6>'.$row['EMPRESA'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['CARGO'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['INICIO'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['FIN'].'</h6></td>';
                  $inpt .='<td><h6>'.antiguedad($row['INICIO'],$row['FIN']).'</h6></td>';
                  $inpt .='</tr>';    
                  } 
                
                  $inpt .=' </tbody>

                    </table>';

            }
    echo $inpt;
    //print_r($row);
}         


?>
