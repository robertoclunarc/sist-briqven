<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const'])){
  $finicio    = isset($_GET["finicio"])?$_GET["finicio"]:$_POST["txtfinicio"];         //
  $ffin       = isset($_GeT["ffin"])?$_GET["ffin"]:$_POST["txtffin"];
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"1";
  $nro        = isset($_POST["hddnro"])?$_POST["hddnro"]:"NULL";

  $qry1="SELECT     dbo.SW_suspensiones_disciplinarias.cedula, dbo.ADAM_DATOS_PERSONALES.NOMBRES, 
  dbo.SW_suspensiones_disciplinarias.Numero_suspension, CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.inicio_suspension, 103) 
  AS inicio_suspension, CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fin_suspension, 103) AS fin_suspension, 
  dbo.SW_suspensiones_disciplinarias.autorizante, ADAM_DATOS_PERSONALES_1.NOMBRES AS NombreAut, 
  dbo.SW_suspensiones_disciplinarias.CentroCosto, dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO,dbo.SW_suspensiones_disciplinarias.Cod_cargo, 
  dbo.SW_suspensiones_disciplinarias.Desc_cargo, dbo.SW_suspensiones_disciplinarias.Relacion_Laboral, 
  dbo.SW_suspensiones_disciplinarias.Cuadrilla, dbo.SW_suspensiones_disciplinarias.Clasenom, dbo.SW_suspensiones_disciplinarias.Estado, 
  dbo.SW_suspensiones_disciplinarias.Turno, dbo.SW_suspensiones_disciplinarias.cod_suspension, 
  dbo.SW_causas_suspensiones_dis.Desc_suspension, dbo.SW_suspensiones_disciplinarias.Observaciones ,
  datediff(d ,  dbo.SW_suspensiones_disciplinarias.inicio_suspension, dbo.SW_suspensiones_disciplinarias.fin_suspension ) +1 as dias,
  8* (datediff(d,dbo.SW_suspensiones_disciplinarias.inicio_suspension,dbo.SW_suspensiones_disciplinarias.fin_suspension) +1) as horas,dbo.SW_suspensiones_disciplinarias.tipo,CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.f_proceso,103) as f_proceso,
  CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.Fecha_aplicacion,103) as Fecha_aplicacion, dbo.SW_suspensiones_disciplinarias.firmo_medida ,CONVERT(varchar(10), dbo.SW_suspensiones_disciplinarias.fecha_laborales,103) as fecha_laborales	
  FROM dbo.SW_suspensiones_disciplinarias 
  INNER JOIN dbo.ADAM_DATOS_PERSONALES ON dbo.SW_suspensiones_disciplinarias.cedula = dbo.ADAM_DATOS_PERSONALES.Trabajador 
  INNER JOIN dbo.SW_causas_suspensiones_dis ON dbo.SW_suspensiones_disciplinarias.cod_suspension = dbo.SW_causas_suspensiones_dis.cod_suspension 
  LEFT OUTER JOIN dbo.ADAM_DATOS_PERSONALES ADAM_DATOS_PERSONALES_1 ON dbo.SW_suspensiones_disciplinarias.autorizante = ADAM_DATOS_PERSONALES_1.Trabajador
  ";

  


//print "Trabajador:".$trabajador;

  if ($trabajador!='NULL' && $trabajador!=''){
    $qry1.=" where cedula=".$trabajador."";
  }else{
    $qry1.=" where cedula=0";
  }

  //if ($trabajador!='NULL')
  //    $qry.=" AND cs.cedula=".$trabajador." ";
  $qry1.=" order by   Numero_suspension desc";

  $qry=$qry1;

  //print $qry;

  buscar($qry, $nro);


}else{
  echo "Debe Iniciar Sesion para Consultar";
}       
       
function buscar($b, $nro) {
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();        
        $stmt1 = $cn->query($b);        
        $contar = $stmt1->columnCount(); 
             
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              
              $inpt = $inpt.'<thead>
              <tr>';
                  $inpt = $inpt.'<th class = "info">Num.</th>
                  <th class = "info">Cedula</th>
                  <th class = "info">Nombre</th>
                  <th class = "info">Cargo</th>
                  <th class = "info">C.Costo</th>
                  <th class = "info">Tipo</th>
                  <th class = "info">Desde</th>
                  <th class = "info">Hasta</th>
                  <th class = "info">Dias</th>
                  <th class = "info">Horas</th>
                  <th class = "info">Descripcion</th>
                  <th class = "info">Motivo</th>
                  <th class = "info">Firmo</th>
              </tr>
          </thead>
  <tbody>';
             $contar=0; 
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    $contar++;
                    $inpt .='<tr>';
                    $inpt .='<td>'.$row['Numero_suspension'].'</td>';                    
                    $inpt .='<td>'.$row['cedula'].'</td>';
                    $inpt .='<td>'.$row['NOMBRES'].'</td>';
                    $inpt .='<td>'.$row['Desc_cargo'].'</td>';
                    $inpt .='<td>'.$row['DESC_CCOSTO'].'</td>';
                    $inpt .='<td>'.nombre_tipo_suspension($row['tipo']).'</td>';
                    //$inpt .='<td>'.$row['TipodePersonal'].'</td>';
                    if ($row['inicio_suspension']!='01/01/1900' ){
                      $inpt .='<td>'.$row['inicio_suspension'].'</td>';
                      $inpt .='<td>'.$row['fin_suspension'].'</td>';
                  }else{
                    $inpt .='<td>'.$row['Fecha_aplicacion'].'</td>';
                    $inpt .='<td>'.$row['Fecha_aplicacion'].'</td>';                    
                  }
                    $inpt .='<td>'.$row['dias'].'</td>';
                    $inpt .='<td>'.$row['horas'].'</td>';
                    $inpt .='<td>'.$row['Desc_suspension'].'</td>';
                    $inpt .='<td>'.$row['Observaciones'].'</td>';
                    $inpt .='<td>'.$row['firmo_medida'].'</td>';
                    $inpt .='</tr>';                        
              } 
             
             /* $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th colspan="13" ><span class="label label-success">Cant. Reg. Total: '.$contar.'</span></th>
                        
                    </tr>
                </tfoot>
                </table>';
*/
                $inpt .=' </tbody></table>';
                

        }
echo $inpt;
//print_r($row);
}         
?>
