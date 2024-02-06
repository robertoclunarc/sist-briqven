<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$sustituto= isset($_POST["cbosutituto"])?$_POST["cbosutituto"]:"NULL";
$sustituido= isset($_POST["cbosustituido"])?$_POST["cbosustituido"]:"NULL";
  
$qry="select cc.*, c.desc_causa from cambio_cuadrilla cc 
left join causas_st_dlt c on cc.causal =c.cod_causa 
where  trabajador ='".$sustituto."'";

/*if ($sustituto!="NULL")
  $qry.=" and sustituto='".$sustituto."'";
elseif ($sustituido!="NULL") {
  $qry.=" and sustituido='".$sustituido."'";
}
*/

/*if ($finicio!= null){
   "and fecha_ini >= '".$finicio."' and fecha_fin <= '".$ffin."'"; 
}
*/
$qry.=" order by fecha_registro DESC";
//echo $qry;

buscar($qry);      
       
function buscar($b) {
       include("../BD/conexion.php");
       $link=Conex_Contancia_pgsql();
       $result = pg_query($link,$b);
       $f=pg_num_rows($result);        
        if($f == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="10%"class="info">Fecha Ini.</th>
                <th width="10%"class="info">Fecha Fin</th>
                <th width="10%"class="info">Causal</th> 
                <th width="10%"class="info">Cuadrilla Actual</th>  
                <th width="10%"class="info">Cuadrilla Anterior</th> 
                <!-- <th width="10%"class="info">Login Registro</th> -->
                <th width="50%"class="info" align="center">Motivo</th> 
                <th width="10%"class="info">F. Registro</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              
            while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    
                    $contar++;                                      

                    $inpt .='<tr>';
                    $inpt .='<td>'.$row['fecha_ini'].'</td>';
                    $inpt .='<td>'.$row['fecha_fin'].'</td>';
                    $inpt .='<td>'.$row['causal'].'</td>';
                    $inpt .='<td>'.$row['cuadrilla'].'</td>';
                    $inpt .='<td>'.$row['cuadrilla_anterior'].'</td>';
                    //$inpt .='<td>'.$row['login_registrado'].'</td>';
                    $inpt .='<td>'.$row['motivo'].'</td>';             
                    $inpt .='<td>'.substr($row['fecha_registro'], 0, 16).'</td>';
                    $inpt .='</tr>';                        
            } 
             pg_free_result($result);
             pg_close($link);

              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th><span class="label label-success">Cant. Reg. Total:</span></th>
                        <th><span class="label label-success">'.$contar.'</span></th>
                        <th></th>
                        <th></th>
                        <th></th>                  
                    </tr>
                </tfoot>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>
