<?php
session_start();
if (isset($_SESSION['user_session_const'])){
include("../BD/conexion.php");
include("funciones_var.php");
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

$link=Conex_Contancia_pgsql();
$acceso=permiso_usuario($link, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);

$qry="select cedula , v.nombre, cargo, count(*) cant_dias, sum(horas_asist) horas_total,
(ESTADO_PROVINCIA + ' ' +POBLACION + ' ' + DOMICILIO)  as ubicacion, TELEFONO_PARTICULAR, ENTEADSCRI
from VW_hoja_de_tiempo_salida v, dbo.VW_trabajadores_01 t
  WHERE v.cedula=t.trabajador and CAST(fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."'";

if ($trabajador!='NULL' && $trabajador!=''){
    $qry.=" and cedula=".$trabajador."";
}
else{
  
  if (isset($_SESSION['nivel_const'])){
    $os = array(3, 5);
    if (in_array($_SESSION['nivel_const'], $os) || $acceso==false) {    
      $trabajador=implode(",", $_SESSION['arrayTrab']);
      $qry.=" and cedula in (".$trabajador.")";
    }
  }else{
     $qry.=" and cedula = NULL";
  }  
}

$qry.=" group by cedula , v.nombre, cargo, (ESTADO_PROVINCIA + ' ' +POBLACION + ' ' + DOMICILIO), TELEFONO_PARTICULAR, ENTEADSCRI";
$qry.=" order by cedula";


buscar($qry);
} else{
   echo '<div class="alert alert-danger">DEBE INICIAR SESION</div>';
}     
       
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