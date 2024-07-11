<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $conFoto     = isset($_POST["conFoto"])?$_POST["conFoto"]:false;
    //hprts.CATSAL_TRAB,
    $qry="SELECT DISTINCT 
    hprts.PUESTO, 
  
    hprts.CAUSA_CAMBIO, 
    hprts.PUESTO_PROP, 
    hprts.DESC_PUESTO, 
    ccps.DESCRIPCION, 
    TO_CHAR(desde, 'DD/MM/YYYY') as fecha_desde, 
    TO_CHAR(hasta, 'DD/MM/YYYY') as fecha_hasta, 
    SUBSTR(desde,1,16) AS fecha_ab, 
    substr(desde, 8, 2) as anio, 
    substr(TO_CHAR(desde, 'DD/MM/YYYY'), 4, 2) as mes 
    FROM HIS_PUESTO_REAL_TRAB_SID hprts  
    INNER JOIN CAUSAS_CAM_PUESTOS_SID ccps ON hprts.CAUSA_CAMBIO = ccps.CAUSA_CAMBIO 
    WHERE ACCION = 'UPDATE NEW'"; 
    
    if ($trabajador!='NULL')
    {
      $qry.=" AND TRABAJADOR=".$trabajador;
    }  
 
    //$qry.=" order by t.trabajador, p.fecha_nacimiento"; 
    $qry.=" ORDER BY anio desc, mes desc";


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
              $inpt = '<b>Cargos Desempe&ntilde;ados</b><table width="100%" class="table table-striped" id="dataTables-example02" border="0" >';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>#.</h6></th>
            <th class = "info"><h6>Desde</h6></th>
            <th class = "info"><h6>Hasta</h6></th>
            <th class = "info"><h6>Puesto</h6></th>
            <th class = "info"><h6>Descripci&oacute;n dle Puesto</h6></th>
            <th class = "info"><h6>Cod. Causa</h6></th>
            <th class = "info"><h6>Descripci&oacute;n Causa</h6></th>
            </tr>
        </thead>
        <tbody>';
                  
                $contar=0;
                while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {  
                 // print "<br>anio: ".$row['ANIO'].", mes: ".$row['MES'];
                  $fecha_desde = str_replace('/', '-', $row['FECHA_DESDE']);
                  $fecha_hasta = str_replace('/', '-', $row['FECHA_HASTA']);
                  $contar++;
                  $inpt .='<tr>';
                  $inpt .='<td><h6>'.$contar.'</h6></td>';                    
                  $inpt .='<td><h6>'.$fecha_desde.'</h6></td>';
                  $inpt .='<td><h6>'.$fecha_hasta.'</h6></td>';
                  $inpt .='<td><h6>'.$row['PUESTO'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['DESC_PUESTO'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['CAUSA_CAMBIO'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['DESCRIPCION'].'</h6></td>';
                  $inpt .='</tr>';    
                  } 
                
                  $inpt .=' </tbody>

                    </table>';

            }
    echo $inpt;
    //print_r($row);
}         


?>
