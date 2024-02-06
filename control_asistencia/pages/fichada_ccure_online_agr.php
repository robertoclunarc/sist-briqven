<?php
session_start();
if (isset($_SESSION['user_session_const'])){
include("../BD/conexion.php");
include("funciones_var.php");
$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
$cbodireccion= isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
$ccosto= isset($_POST["cboccosto"])?trim($_POST["cboccosto"]):"NULL";

$link=Conex_Contancia_pgsql();
$acceso=permiso_usuario($link, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);

$qry="SELECT c.Cedula, c.Nombre, c.Cargo, a.desc_ccosto, x.dias_asitidos as cant_registros, s.total as diastotal,
(x.dias_asitidos*100/s.total) as porc , SUM(CASE WHEN DATEDIFF(hour, n.hmin, m.hmax)<0 THEN DATEDIFF(hour, n.hmin, m.hmax)+24 ELSE DATEDIFF(hour, n.hmin, m.hmax) END) as permanencia
FROM ccure_fichadas c, adam_datos_personales a, 
( select w.cedula, count(*) as total from sw_hoja_de_tiempo_real w 
where  w.fecha BETWEEN '".$finicio."' AND '".$ffin."' 
and w.entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') 
group by w.cedula ) s,
(SELECT mi.Cedula, mi.fecha, min(mi.Hora) as  hmin
FROM ccure_fichadas mi 
WHERE CAST(mi.fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."' and mi.Direccion= 'InDirection'
group by mi.Cedula, mi.fecha) n,
(SELECT mx.Cedula, mx.Fecha, max(mx.Hora)  as hmax
FROM ccure_fichadas mx 
WHERE CAST(mx.fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."' and mx.Direccion= 'OutDirection' 
group by mx.Cedula, mx.Fecha) m,
(select y.Cedula, count(*) dias_asitidos from 
(SELECT c.Cedula,c.fecha  FROM ccure_fichadas c WHERE CAST(fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."' and Direccion= 'InDirection' 
group by c.Cedula, fecha) y group by y.Cedula) x 
WHERE c.Cedula=s.cedula and c.Cedula=x.Cedula and c.Cedula=a.trabajador 
and c.Cedula=n.Cedula and n.Cedula=m.Cedula and c.Fecha=n.Fecha and  m.Fecha=n.Fecha 
and CAST(c.fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."' and c.Direccion= 'InDirection'";

if ($trabajador!='NULL' && $trabajador!=''){
    $qry.=" and c.Cedula=".$trabajador."";
}
else{
  
  if (isset($_SESSION['nivel_const'])){
    $os = array(3, 5);
    if (in_array($_SESSION['nivel_const'], $os)  || $acceso) {    
      $trabajador=implode(",", $_SESSION['arrayTrab']);
      $qry.=" and c.Cedula in (".$trabajador.")";
    }
  }else{
     $qry.=" and c.Cedula = NULL";
  }  
}

if ($ccosto!='' && $ccosto!='NULL'){
  $qry.=" and a.centro_costo = '".$ccosto."' ";
}/*else{
    $os = array(3,5);
    if (in_array($_SESSION['nivel_const'], $os)  && $acceso==false) {      
      $qry.=" and a.centro_costo= '".$_SESSION['ccosto']."'";
    }
}*/

$qry.=" group by c.Cedula, c.Nombre, c.Cargo , a.desc_ccosto, x.dias_asitidos, s.total ORDER BY c.Cedula, c.Nombre, c.Cargo, s.total";

buscar($qry); 

}else{

  echo '<div class="alert alert-danger">DEBE INICIAR SESION</div>';
}    
       
function buscar($b) {
  //echo $b;
       $cn=Conectarse_sitt();
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" border="0" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
                    <tr>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Dias Asistidos</th>                        
                        <th>Dias Transcurridos</th>
                        <th>H. Permanencia</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>

        <tbody>';
              $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;
                    if ($row['porc']>100)
                      $porc=100;
                    else
                      $porc=$row['porc'];

                    $inpt .='<tr>';
                    $inpt .='<td>'.$row['Cedula'].'</td>';                    
		                $inpt .='<td>'.$row['Nombre'].'</td>';
                    $inpt .='<td>'.$row['Cargo'].'</td>';
                    $inpt .='<td>'.$row['cant_registros'].'</td>';
                    $inpt .='<td>'.$row['diastotal'].'</td>';
                    $inpt .='<td>'.$row['permanencia'].'</td>';
                    $inpt .='<td>'.$porc.'</td>';                    
                    $inpt .='</tr>';                        
              } 
             
              $inpt .=' </tbody>
                </table>';
              $cn=null;
              $stmt1=null;  
        }
echo $inpt;
//print_r($row);
}         
?>
