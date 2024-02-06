<?php
 session_start();

$periodo= isset($_POST["cboperiodo"])?$_POST["cboperiodo"]:"NULL";
$SH= isset($_POST["cboSH"])?$_POST["cboSH"]:NULL;
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:NULL;
$ccosto= isset($_POST["cboccosto"])?$_POST["cboccosto"]:NULL;


buscar($periodo, $SH, $trabajador, $ccosto);     
       
function buscar($periodo, $SH, $trabajador, $ccosto) {
       require_once('funciones_var.php');
       include("../BD/conexion.php");
       $mbd=Conectarse_sitt();

       $ciclo=explode("|", $periodo);
       $fechaIni = $ciclo[0];
       $fechaFin = $ciclo[1];
       $dias=dias_transcurridos3($fechaIni,$fechaFin);
       /*if (count($ciclo)==2){
        $dias= $ciclo[1];
        $dias=$dias*7;
       } */

       if (is_null($ccosto) || $ccosto=='NULL')
           $ccosto=NULL;

       if (is_null($trabajador) || $trabajador=='NULL')
           $trabajador=NULL;
           
       if (is_null($SH) || $SH=='NULL')
           $SH=NULL;        

        $stmt = $mbd->prepare("EXEC SW_TIEMPO_BOLSA ?, ?, ?, ?");
        $stmt->bindParam(1, $fechaIni, PDO::PARAM_STR,10);
        $stmt->bindParam(2, $ccosto, PDO::PARAM_STR);
        $stmt->bindParam(3, $SH, PDO::PARAM_INT);
        $stmt->bindParam(4, $trabajador, PDO::PARAM_INT);            
        
        $stmt->execute();
        $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info"><h6><strong>Cedula</strong></h6></th>             
                <th width="15%" class="info"><h6><strong>Nombres</strong></h6></th>  
                <th width="4%"class="info"><h6><strong>CCosto</strong></h6></th>        
                <th width="10%"class="info"><h6><strong>Puesto</strong></h6></th>
                <th width="2%" class="info"><h6><strong>SH</strong></h6></th>
             ';
             //date("d-m-Y",strtotime($finicio."+ 1 days"))
        for ($i=0;  $i<=$dias ; $i++ ) //for ($i=0;  $i<=$dias-1 ; $i++ )
        {
          $inpt = $inpt.'<th width="5%" class="info"><h6><strong>'.date("d-m-Y",strtotime($fechaIni."+ ".$i." days")).'</strong></h6></th>';
        }                
        $inpt = $inpt.'</tr>
        </thead>
        <tbody>';        
        
        $condicion = array('VV:VV','CS:CS');
        
        while ($fila = $stmt->fetch()) {
          $inpt_x ='';          
          for ($i=0;  $i<=$dias ; $i++ ){   //for ($i=0;  $i<=$dias-1 ; $i++ ){  
              $esperanza='';
              $clase="info";
              $codError='';
              $d_x=explode("|", $fila['D'.$i]);
              if (count($d_x)==3){
                //echo $i.'*'.count($d_x).'<br>';
              ////////////////////ver cod ausencia///////////////////////
                    $codError=$d_x[2];
                    if ($codError==99)
                    {
                        $clase="danger";                  
                    }    
                    elseif ($codError==100)
                    { 
                      $clase="success";                
                    }
                    else{
                        $clase="warning";
                    }

              ///////////////////ver esperazza//////////////////////////////////////
                    
                    $key = array_search($d_x[1], $condicion);
                    if (false !== $key)
                    {
                        
                        $esperanza=$d_x[1];
                        $esperanza = $esperanza=='VV:VV'?'Vac':'Com';
                    }
              }                   
              ////////////////////////////////////////////////////////////////
              $fdx=date("Y-m-d", strtotime($d_x[0]));
              $param = $fila["cedula"].",'".$fdx."', 'E'";

              $inpt_x .='<td class="'.$clase.'"><span class="label label-'.$clase.'">'.$esperanza.'</span><br>
    <button type="button" class="btn btn-'.$clase.' btn-xs" data-toggle="modal" onclick="ver_fichadas('.$param.')" data-target="#exampleModalCenter">'.$codError.'</button>
              </td>';
          }
            $inpt .='<tr>';
            /*$inpt .='<td><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>'; 
            */                   
            $inpt .='<td><h6>'.$fila['cedula'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['NOMBRES'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['centro_costo'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['DESC_PUESTO'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['sistema_horario'].'</h6></td>';

            $inpt .=$inpt_x;
                            
            $inpt .='</tr>';                        
        } 
             
              $inpt .=' </tbody>
              
                </table>';
              $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    

    '; 
        
echo $inpt;
//print_r($row);
}         
?>
