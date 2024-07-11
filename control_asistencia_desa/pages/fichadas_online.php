<?php
//session_start();
//print_r($_SESSION);
//print "_SESSION['nivel_const']:".$_SESSION['nivel_const'];
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){

  echo "<body>
  <script type='text/javascript'>
  window.location='../index.php';
  </script>
  </body>";
}    
include("../BD/conexion.php");
include("funciones_var.php");
session_start();

//$periodo= isset($_POST["cboperiodo"])?$_POST["cboperiodo"]:"NULL";
$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$SH= isset($_POST["cboSH"])?$_POST["cboSH"]:NULL;
$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:NULL;
if ($_SESSION['nivel_const']==2 || $_SESSION['nivel_const']==1){
    $connexion=Conex_rrhh_pgsql();
    if ($trabajador=='NULL')   $trabajador=llenar_lista_trabajadoresTodos($connexion);
    pg_close($connexion);
}else
   if ($trabajador=='NULL')   $trabajador=llenar_lista_trabajadores_del_supervisor_sin_comilla();


//buscar($periodo, $SH, $trabajador); 
buscar($finicio, $ffin, $SH, $trabajador);         
        
//function buscar($periodo, $SH, $trabajador) {
function buscar($finicio, $ffin, $SH, $trabajador) {
       //include("../BD/conexion.php");
       $mbd=Conectarse_sitt();
       $link_CONSULTAR_STDLT_LOCAL   = Conex_Contancia_pgsql();

      /* $ciclo=explode("|", $periodo);
       $fechaIni = $ciclo[0];
       $dias=35;
       if (count($ciclo)==2){
        $dias= $ciclo[1];
        $dias=$dias*7;
       } */
      //print  $finicio.','. $ffin;
      $dias=dias_transcurridos2($finicio, $ffin);
      if ($dias>60)
          $dias = 60;
      //print "<br>".$dias;
       /*if (is_null($ccosto) || $ccosto=='NULL')
           $ccosto=NULL;

       if (is_null($trabajador) || $trabajador=='NULL')
           $trabajador=NULL;*/
           
       if (is_null($SH) || $SH=='NULL')
           $SH=NULL;        

        //$stmt = $mbd->prepare("EXEC SW_Fichadas ?, ?, ?, ?");  //ORIGINAL, ESTA ES LA QUE FUNCIONA
        $stmt = $mbd->prepare("EXEC SW_Fichadas_dias ?, ?, ?, ?");
        $stmt->bindParam(1, $finicio, PDO::PARAM_STR,10);
        $stmt->bindParam(2, $ccosto, PDO::PARAM_STR);
        $stmt->bindParam(3, $SH, PDO::PARAM_INT);
        $stmt->bindParam(4, $trabajador, PDO::PARAM_INT);            
        //$stmt->bindParam(5, $dias, PDO::PARAM_INT); 
        
        $stmt->execute();
        $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info"><h6><strong></strong></h6></th> 
                <th width="5%" class="info"><h6><strong>Cedula</strong></h6></th>             
                <th width="15%" class="info"><h6><strong>Nombres</strong></h6></th>  
                <th width="4%"class="info"><h6><strong>CCosto</strong></h6></th>        
                <th width="10%"class="info"><h6><strong>Puesto</strong></h6></th>
                <th width="2%" class="info"><h6><strong>SH</strong></h6></th>
             ';
             //date("d-m-Y",strtotime($finicio."+ 1 days"))
        for ($i=0;  $i<=$dias-1 ; $i++ )
        {
          $inpt = $inpt.'<th width="5%" class="info"><h6><strong>'.date("d-m-Y",strtotime($finicio."+ ".$i." days")).'</strong></h6></th>';
        }                
        $inpt = $inpt.'</tr>
        </thead>
        <tbody>';        
        
        $condicion = array('VV:VV','CS:CS');
        $contador=1;
        while ($fila = $stmt->fetch()) {
          $inpt_x ='';   
          //print_r($fila); 
          //print "<br>---------------------------------------------------------------------------------";        
          for ($i=0;  $i<=$dias-1 ; $i++ ){ 
              $esperanza='';
              $clase="info";
              $codError='';
              $d_x=explode("|", $fila['D'.$i]);
              //print "<br>Dia:".$i; print_r($d_x);
              if (count($d_x)>=3){
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
                    $boton = "&nbsp";
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
              /***** INCLUIDO PARA CONSULTAR POR CADA TRABAJADOR SI TIENE HORAS EXTRAS CARGADAS EN CONTROL DE ASISTENCIA ******/
              $result = CONSULTAR_STDLT_LOCAL($fila["cedula"],$fdx, $link_CONSULTAR_STDLT_LOCAL); 
              if ($result=='0'){
                  $clase = "danger";
                  $boton = "<b>R</b>";
              }elseif ($result=='1'){
                  $clase = "danger";
                  $boton = "<b>C</b>";
              }elseif ($result=='2'){
                  $clase = "warning";
                  $boton = "<b>A</b>";
              }elseif ($result=='3'){
                  $clase = "success";
                  $boton = '<b>V</b>';
              }elseif ($result=='4'){
                  $clase = "success";
                  $boton = '<b><i class="fa fa-check" aria-hidden="true"></i></b>';
              }
              /*****************************************************************************************************************/  
              $param = $fila["cedula"].",'".$fdx."', 'C'";

              $inpt_x .='<td class="'.$clase.'"><span class="label label-'.$clase.'">'.$esperanza.'</span><br>
    <button type="button" class="btn btn-'.$clase.' btn-xs" data-toggle="modal" onclick="ver_fichadas('.$param.')" data-target="#exampleModalCenter">'.$boton.'</button>
              </td>';
          }
            $inpt .='<tr>';
            /*$inpt .='<td><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>'; 
            */      
            $inpt .='<td><h6>'.$contador.'</h6></td>';             
            $inpt .='<td><h6>'.$fila['cedula'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['NOMBRES'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['centro_costo'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['DESC_PUESTO'].'</h6></td>';
            $inpt .='<td><h6>'.$fila['sistema_horario'].'</h6></td>';

            $inpt .=$inpt_x;
                            
            $inpt .='</tr>';  
            $contador++;                      
        } 
             
              $inpt .=' </tbody>
              
                </table>';
              $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    '; 
        
echo $inpt;
//print_r($row); 
pg_close($link_CONSULTAR_STDLT_LOCAL);
}         
?>
