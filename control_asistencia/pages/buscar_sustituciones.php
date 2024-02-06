<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$sustituto= isset($_POST["cbosutituto"])?$_POST["cbosutituto"]:"NULL";
$sustituido= isset($_POST["cbosustituido"])?$_POST["cbosustituido"]:"NULL";
  
$qry="select s.*, c.desc_causa from sustituciones s, causas_st_dlt c where cod_causa=causal and fecha_ini >= '".$finicio."' and fecha_fin <= '".$ffin."' ";
if ($sustituto!="NULL")
  $qry.=" and sustituto='".$sustituto."'";
elseif ($sustituido!="NULL") {
  $qry.=" and sustituido='".$sustituido."'";
}
$qry.=" order by fecha_registro desc";
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
                <th width="5%" class="info">Sutituto</th>             
                <th width="15%" class="info">Nombre Sustituto</th>
                <th width="15%" class="info">Sustitido</th>
                <th width="15%" class="info">Nombre Sustituido</th> 
                <th width="5%"class="info">Fecha Ini.</th>
                <th width="5%"class="info">Fecha Fin</th>
                <th width="5%"class="info">Despricion Puesto</th>
                <th width="5%"class="info">Causal</th> 
                <th width="5%"class="info">Login Registro</th> 
                <th width="5%"class="info">F. Registro</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              
            while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    
                    $contar++;                                      

                    $inpt .='<tr>';
                    $inpt .='<td>'.$row['sustituto'].'</td>';
                    $inpt .='<td>'.$row['nombre_sustituto'].'</td>';
                    $inpt .='<td>'.$row['sustituido'].'</td>';
                    $inpt .='<td>'.$row['nombre_sustituido'].'</td>';
                    $inpt .='<td>'.$row['fecha_ini'].'</td>';
                    $inpt .='<td>'.$row['fecha_fin'].'</td>';
                    $inpt .='<td>'.$row['desc_puesto'].'</td>';
                    $inpt .='<td>'.$row['causal'].'</td>';
                    $inpt .='<td>'.$row['login_registrado'].'</td>';
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