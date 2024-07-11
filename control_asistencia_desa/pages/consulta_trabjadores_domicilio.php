<?php
 session_start();
include("../BD/conexion.php");
include("funciones_var.php");
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";


$qry="select cedula , v.nombre, cargo, count(*) cant_dias, sum(horas_asist) horas_total,
(ESTADO_PROVINCIA + ' ' +POBLACION + ' ' + DOMICILIO)  as ubicacion, TELEFONO_PARTICULAR, ENTEADSCRI
from VW_hoja_de_tiempo_salida v, dbo.VW_trabajadores_01 t
  WHERE v.cedula=t.trabajador and CAST(fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."'";
  
  // tg.*, t.*, d.*
  
  $qry="SELECT TRABAJADOR, Nombre, sexo, FECHA_NACIMIENTO, DOMICILIO, DOMICILIO2, POBLACION, ESTADO_PROVINCIA, PAIS, CODIGO_POSTAL, CALLES_ALEDANAS, TELEFONO_PARTICULAR, REG_SEGURO_SOCIAL, DOMICILIO3, E_MAIL, ENTEADSCRI, INSTRUCC
  FROM matesisitt.dbo.VW_trabajadores_01";


if ($trabajador!='NULL' and $trabajador!='')
    $qry.=" and TRABAJADOR=".$trabajador."";

//$qry.=" group by trabajador , tg.nombre,  ESTADO_PROVINCIA + ' ' +POBLACION + ' ' + DOMICILIO), TELEFONO_PARTICULAR, ENTEADSCRI";
$qry.=" order by trabajador";


buscar($qry);     
       
function buscar($b) {
       $cn=Conectarse_sitt();
        $stmt1 = $cn->query($b);
        $stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" border="0" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
                    <tr>
                        <th>Nro.</th>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Telefono</th>
                        <th>Ubicacion</th>
                        <th>CDS-Origen</th>
                        <th>Cant. Dias</th>
                        <th>Cant. Horas</th>
                    </tr>
                </thead>
        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';
                    $inpt .='<td>'.$row['cedula'].'</td>';		    
                    $inpt .='<td>'.$row['nombre'].'</td>';
                    $inpt .='<td>'.$row['cargo'].'</td>';
                    $inpt .='<td>'.$row['TELEFONO_PARTICULAR'].'</td>';
                    $inpt .='<td>'.$row['ubicacion'].'</td>';
                    $inpt .='<td>'.$row['ENTEADSCRI'].'</td>';
                    $inpt .='<td>'.$row['cant_dias'].'</td>';
                    $inpt .='<td>'.$row['horas_total'].'</td>';
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>