<?php
 session_start();
include("../BD/conexion.php");
include("funciones_var.php");
$finicio      = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin         = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador   = isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
$tmedida       = isset($_POST["cbotmedida"])?trim($_POST["cbotmedida"]):"NULL";
$cbodireccion = isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
$conFoto      = isset($_POST["conFoto"])?$_POST["conFoto"]:false;

$qry1="SELECT     
dbo.SW_suspensiones_disciplinarias.cedula, 
dbo.ADAM_DATOS_PERSONALES.NOMBRES, 
dbo.SW_suspensiones_disciplinarias.Numero_suspension, 
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.inicio_suspension, 103) 
AS inicio_suspension, 
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fin_suspension, 103) AS fin_suspension, 
dbo.SW_suspensiones_disciplinarias.autorizante, 
ADAM_DATOS_PERSONALES_1.NOMBRES AS NombreAut, 
dbo.SW_suspensiones_disciplinarias.CentroCosto, 
dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO,
dbo.SW_suspensiones_disciplinarias.Cod_cargo, 
dbo.SW_suspensiones_disciplinarias.Desc_cargo, 
dbo.SW_suspensiones_disciplinarias.Relacion_Laboral, 
dbo.SW_suspensiones_disciplinarias.Cuadrilla, 
dbo.SW_suspensiones_disciplinarias.Clasenom, 
dbo.SW_suspensiones_disciplinarias.Estado, 
dbo.SW_suspensiones_disciplinarias.Turno, 
dbo.SW_suspensiones_disciplinarias.cod_suspension, 
dbo.SW_causas_suspensiones_dis.Desc_suspension, 
dbo.SW_suspensiones_disciplinarias.Observaciones ,
dbo.SW_suspensiones_disciplinarias.Fecha_aplicacion, 
datediff(d ,  dbo.SW_suspensiones_disciplinarias.inicio_suspension, dbo.SW_suspensiones_disciplinarias.fin_suspension ) +1 as dias,
8* (datediff(d,dbo.SW_suspensiones_disciplinarias.inicio_suspension,dbo.SW_suspensiones_disciplinarias.fin_suspension) +1) as horas,
dbo.SW_suspensiones_disciplinarias.tipo,CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.f_proceso,103) as f_proceso,
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.Fecha_aplicacion,103) as Fecha_aplicacion, 
dbo.SW_suspensiones_disciplinarias.firmo_medida ,
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fecha_laborales,103) as fecha_laborales	
FROM dbo.SW_suspensiones_disciplinarias 
INNER JOIN dbo.ADAM_DATOS_PERSONALES ON dbo.SW_suspensiones_disciplinarias.cedula = dbo.ADAM_DATOS_PERSONALES.Trabajador 
INNER JOIN dbo.SW_causas_suspensiones_dis ON dbo.SW_suspensiones_disciplinarias.cod_suspension = dbo.SW_causas_suspensiones_dis.cod_suspension 
LEFT OUTER JOIN dbo.ADAM_DATOS_PERSONALES ADAM_DATOS_PERSONALES_1 ON dbo.SW_suspensiones_disciplinarias.autorizante = ADAM_DATOS_PERSONALES_1.Trabajador
WHERE  (CAST(inicio_suspension as datetime) BETWEEN '".$finicio."' AND '".$ffin."' 
OR CAST(inicio_suspension as datetime) < '".$finicio."' AND CAST(fin_suspension as datetime) > '".$ffin."'
OR CAST(fin_suspension as datetime) BETWEEN '".$finicio."' AND '".$ffin."')";

$qry2="SELECT     dbo.SW_suspensiones_disciplinarias.cedula, 
dbo.ADAM_DATOS_PERSONALES.NOMBRES, 
dbo.SW_suspensiones_disciplinarias.Numero_suspension, 
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.inicio_suspension, 103) AS inicio_suspension, 
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fin_suspension, 103) AS fin_suspension, 
dbo.SW_suspensiones_disciplinarias.autorizante, 
ADAM_DATOS_PERSONALES_1.NOMBRES AS NombreAut, 
dbo.SW_suspensiones_disciplinarias.CentroCosto, 
dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO,
dbo.SW_suspensiones_disciplinarias.Cod_cargo,
dbo.SW_suspensiones_disciplinarias.Desc_cargo, 
dbo.SW_suspensiones_disciplinarias.Relacion_Laboral, 
dbo.SW_suspensiones_disciplinarias.Cuadrilla, 
dbo.SW_suspensiones_disciplinarias.Clasenom, 
dbo.SW_suspensiones_disciplinarias.Estado, 
dbo.SW_suspensiones_disciplinarias.Turno, 
dbo.SW_suspensiones_disciplinarias.cod_suspension, 
dbo.SW_causas_suspensiones_dis.Desc_suspension, 
dbo.SW_suspensiones_disciplinarias.Observaciones ,
dbo.SW_suspensiones_disciplinarias.Fecha_aplicacion, 
datediff(d ,  dbo.SW_suspensiones_disciplinarias.inicio_suspension, dbo.SW_suspensiones_disciplinarias.fin_suspension ) +1 as dias,
8* (datediff(d,dbo.SW_suspensiones_disciplinarias.inicio_suspension,dbo.SW_suspensiones_disciplinarias.fin_suspension) +1) as horas,dbo.SW_suspensiones_disciplinarias.tipo,CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.f_proceso,103) as f_proceso,
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.Fecha_aplicacion,103) as Fecha_aplicacion, 
dbo.SW_suspensiones_disciplinarias.firmo_medida ,
CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fecha_laborales,103) as fecha_laborales	
FROM         dbo.SW_suspensiones_disciplinarias INNER JOIN
                      dbo.ADAM_DATOS_PERSONALES ON dbo.SW_suspensiones_disciplinarias.cedula = dbo.ADAM_DATOS_PERSONALES.Trabajador INNER JOIN
                      dbo.SW_causas_suspensiones_dis ON 
                      dbo.SW_suspensiones_disciplinarias.cod_suspension = dbo.SW_causas_suspensiones_dis.cod_suspension LEFT OUTER JOIN
                      dbo.ADAM_DATOS_PERSONALES ADAM_DATOS_PERSONALES_1 ON 
                      dbo.SW_suspensiones_disciplinarias.autorizante = ADAM_DATOS_PERSONALES_1.Trabajador
where CAST(inicio_suspension as datetime) = '1900/01/01' and  CAST(Fecha_aplicacion as datetime) BETWEEN '".$finicio."' AND '".$ffin."' 
"; 


/*
AND ( pv.fecha_ini_per_vac BETWEEN '01-may-2019' AND '31-may-2019'
OR ( pv.fecha_ini_per_vac < '01-may-2019' AND pv.fecha_fin_per_vac > '31-may-2019' )
OR pv.fecha_fin_per_vac BETWEEN '01-may-2019' AND '31-may-2019'
OR pv.fecha_pago_vac BETWEEN '01-may-2019' AND '31-may-2019' )

*/



if ($tmedida!='' && $tmedida!='NULL'){
  $qry1.=" and tipo= '".$tmedida."'";
  $qry2.=" and tipo= '".$tmedida."'";
}  

if ($trabajador!='NULL' && $trabajador!=''){
    $qry1.=" and cedula=".$trabajador."";
    $qry2.=" and cedula=".$trabajador."";
}
//$qry1.=" order by Numero_suspension desc";
$qry2.=" order by Numero_suspension desc";


$qry=$qry1." union  ".$qry2;
//print $qry;

buscar($qry,$conFoto);    
       
function buscar($b, $conFoto) {
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
              $inpt = '<table width="100%" border="0" class="table table-striped" id="dataTables-example">';
              $inpt = $inpt.'<thead>
                    <tr>';
                        if ($conFoto!=false)
                            $inpt = $inpt.'<th class = "info">Foto</th>';

                        $inpt = $inpt.'<th class = "info"><h6>Num.</h6></th>
                        <th class = "info"><h6>Cedula</h6></th>
                        <th class = "info"><h6>Nombre</h6></th>
                        <th class = "info"><h6>Cargo</h6></th>
                        <th class = "info"><h6>C.Costo</h6></th>
                        <th class = "info"><h6>Tipo</h6></th>
                        <th class = "info"><h6>Desde</h6></th>
                        <th class = "info"><h6>Hasta</h6></th>
                        <th class = "info"><h6>Dias</h6></th>
                        <th class = "info"><h6>Horas</h6></th>
                        <th class = "info"><h6>Descripcion</h6></th>
                        <th class = "info"><h6>Motivo</h6></th>
                        <th class = "info"><h6>Firmo</h6></th>
                    </tr>
                </thead>

        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    $inpt .='<tr class="gradeA">';
                    if ($conFoto!=false){
                      $url_img="../../rrhh/fotcarmat_new/".$row['cedula'].".bmp";
                      if (!is_file( $url_img ))
                        $url_img="images/user.png";

                      $inpt .='<td><img width="50px" height="50px" style="border-radius: 50%;" src="'.$url_img.'" /></td>'; 
                    }
                    $inpt .='<td><h6>'.$row['Numero_suspension'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['cedula'].'</h6></td>';                    
                    $inpt .='<td><h6>'.ucwords(strtolower($row['NOMBRES'])).'</h6></td>';
                    $inpt .='<td><h6>'.ucwords(strtolower($row['Desc_cargo'])).'</h6></td>';
                    $inpt .='<td><h6>'.$row['DESC_CCOSTO'].'</h6></td>';
                    $inpt .='<td><h6>'.nombre_tipo_suspension($row['tipo']).'</h6></td>';
                    if ($row['inicio_suspension']!='01/01/1900' ){
                        $inpt .='<td><h6>'.$row['inicio_suspension'].'</h6></td>';
                        $inpt .='<td><h6>'.$row['fin_suspension'].'</h6></td>';
                    }else{
                      $inpt .='<td><h6>'.$row['Fecha_aplicacion'].'</h6></td>';
                      $inpt .='<td><h6>'.$row['Fecha_aplicacion'].'</h6></td>';                    
                    }
                    $inpt .='<td><h6>'.$row['dias'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['horas'].'</h6></td>';
                    $inpt .='<td><h6>'.utf8_encode($row['Desc_suspension']).'</h6></td>';
                    $inpt .='<td><h6>'.utf8_encode($row['Observaciones']).'</h6></td>';
                    $inpt .='<td><h6>'.$row['firmo_medida'].'</h6></td>';
                    $inpt .='</tr>';                        
              } 
              $cn=null;
              $inpt .=' </tbody>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>
