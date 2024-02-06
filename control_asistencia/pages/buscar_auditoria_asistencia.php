

<?php
 session_start();

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
//$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
//$turno= isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";

$qry="select idauditoria, fecha, operacion, login from tbl_auditorias where operacion like 'Carga de Archivo:%' and fecha between '".$finicio."' and CAST('".$ffin."' AS DATE) + CAST('1 days' AS INTERVAL) order by fecha, login, idauditoria";
// echo $qry;

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
                <th width="5%" class="info">Fecha Carga</th>             
                <th width="15%" class="info">Cedula</th>
                <th width="15%" class="info">Fecha</th>
                <th width="15%" class="info">Archivo</th> 
                <th width="5%"class="info">Usuario</th>                
            </tr>
        </thead>
        <tbody>';
              $contar=0;
              
            while($row = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    
                    $contar++;
                    $pizza=$row['operacion'];                    
                    $porciones = explode("|", $pizza);
                    $archivo=explode(":", $porciones[0]);
                    $xls=trim($archivo[1]);                    

                    $inpt .='<tr>';
                    $inpt .='<td>'.substr($row['fecha'], 0, 16).'</td>';                    
                    $inpt .='<td>'.$porciones[1].'</td>';
                    $inpt .='<td>'.$porciones[2].'</td>';
                    $inpt .='<td>'.$xls.'</td>';
                    $inpt .='<td>'.$row['login'].'</td>'; 
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