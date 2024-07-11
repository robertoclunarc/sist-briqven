<?php
session_start();
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";


    $qry="SELECT dbo.SW_suspensiones_disciplinarias.cedula, dbo.ADAM_DATOS_PERSONALES.NOMBRES, 
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
    LEFT OUTER JOIN dbo.ADAM_DATOS_PERSONALES ADAM_DATOS_PERSONALES_1 ON dbo.SW_suspensiones_disciplinarias.autorizante = ADAM_DATOS_PERSONALES_1.Trabajador";

    if ($trabajador!='NULL' && $trabajador!=''){
      $qry.=" where cedula=".$trabajador."";
    }else{
      $qry.=" where cedula=0";
    }


    $qry.=" order by Numero_suspension desc";
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
              $inpt = '<b>Hist&oacute;rico de Medidas Disciplinarias</b><table width="100%" class="table table-striped" id="dataTables-example1">';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>Num.</h6></th>
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

                    $inpt .='<tr>';
                    $inpt .='<td><h6>'.$row['Numero_suspension'].'</h6></td>';                    
                    $inpt .='<td><h6>'.ucwords(strtolower($row['Desc_cargo'])).'</h6></td>';
                    $inpt .='<td><h6>'.ucwords(strtolower($row['DESC_CCOSTO'])).'</h6></td>';
                    $inpt .='<td><h6>'.nombre_tipo_suspension($row['tipo']).'</h6></td>';
                    //$inpt .='<td>'.$row['TipodePersonal'].'</td>';
                    if ($row['inicio_suspension']!='01/01/1900' ){
                      $inpt .='<td><h6>'.$row['inicio_suspension'].'</h6></td>';
                      $inpt .='<td><h6>'.$row['fin_suspension'].'</h6></td>';
                  }else{
                    $inpt .='<td><h6>'.$row['Fecha_aplicacion'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['Fecha_aplicacion'].'</h6></td>';                    
                  }
                    $inpt .='<td><h6>'.$row['dias'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['horas'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['Desc_suspension'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['Observaciones'].'</h6></td>';
                    $inpt .='<td><h6>'.$row['firmo_medida'].'</h6></td>';
                    $inpt .='</tr>';                                               
              } 
             
              $inpt .=' </tbody>

                </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
