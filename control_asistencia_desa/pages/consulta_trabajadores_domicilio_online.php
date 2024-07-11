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
  
  $qry="SELECT vw.TRABAJADOR, vw.Nombre, sexo, FECHA_NACIMIENTO, DOMICILIO, DOMICILIO2, POBLACION, ESTADO_PROVINCIA, PAIS, vw.CODIGO_POSTAL, CALLES_ALEDANAS, TELEFONO_PARTICULAR, REG_SEGURO_SOCIAL, DOMICILIO3, E_MAIL, ENTEADSCRI, INSTRUCC, DESC_PUESTO
  FROM matesisitt.dbo.VW_trabajadores_01 vw
  inner join ADAM_DATOS_PERSONALES adp on vw.TRABAJADOR =adp.Trabajador";

if ($trabajador!='NULL' and $trabajador!='')
    $qry.=" and vw.TRABAJADOR=".$trabajador."";

$qry.=" order by vw.trabajador";
//print $qry;
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
                        <th>Estado</th>
                        <th>Poblaci&oacute;n</th>
                        <th>Telefono</th>
                        <th>CDS-Origen</th>
                        <th>Grado de Instrucci&oacute;n</th>
                    </tr>
                </thead>
        <tbody>';
             $contar=0;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    $contar++;
                    $inpt .='<tr>';
                    $inpt .='<td>'.$contar.'</td>';
                    $inpt .='<td>'.$row['TRABAJADOR'].'</td>';		    
                    $inpt .='<td>'.$row['Nombre'].'</td>';
                    $inpt .='<td>'.$row['DESC_PUESTO'].'</td>';
                    $inpt .='<td>'.$row['ESTADO_PROVINCIA'].'</td>';
                    $inpt .='<td>'.$row['POBLACION'].'</td>';
                    $inpt .='<td>'.$row['TELEFONO_PARTICULAR'].'</td>';
                    $inpt .='<td>'.$row['ENTEADSCRI'].'</td>';
                    $inpt .='<td>'.$row['INSTRUCC'].'</td>';
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>