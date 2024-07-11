<?php 
session_start();

if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
  include("../BD/conexion.php");	
  $mbd=Conectarse_sitt();
  $link=Conex_Contancia_pgsql();	
  require_once('funciones_var.php');
  $stmt = $mbd->prepare("SW_CON_DATOS_CIERRE");  
  $stmt->execute();

  while ($fila = $stmt->fetch()) {
    $iniper=formato_fecha(strval($fila['iniper']),'/');
    $finper=$fila['finper'];
    $sem_ante=$fila['sem_ante']; 
    $anio=$fila['anio'];
    $max_sem=$fila['max_sem'];
    $mes_acum = $fila['mes_acum'];
    $anio_acum =$fila['anio_acum'];
    $min_sem=$fila['min_sem'];
    $min_anio=$fila['min_anio'];
    $max_sem=$fila['max_sem'];
    $max_anio=$fila['max_anio'];
  }
 
  $inpt = '<table width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
  $inpt = $inpt.'<thead>             
            <tr>                
                <th width="3%" class="info">Oper.</th>
                <th width="3%" class="info">Per.</th>
                <th width="5%" class="info">A&ntildeo</th>
                <th width="10%" class="info">Inicio</th>                            
                <th width="10%" class="info">Fin</th>
                <th width="5%" class="info">Estado</th>                
            </tr>
        </thead>
        <tbody>';

   $pcerrar=permiso_usuario($link, 'CERRAR', 'periodos_nom.php', $_SESSION['user_session_const']);

    $fechaInic=date("Y-m-d", strtotime( $iniper));
    $fechaFin =date("Y-m-d",strtotime($fechaInic." +6 days"));
    $j=1;
    $fechaIni1='';
    $fechaFin1='';
    $fechaIni2='';
    $fechaFin2='';
    $fechaIni3='';
    $fechaFin3='';
    $fechaIni4='';
    $fechaFin4='';
    $fechaIni5='';
    $fechaFin5='';
    for ($i=$min_sem; $i<=$max_sem; $i++){
   	    $estatus='Abierto';
   	    if ($sem_ante>=$min_sem)
   	    	if ($sem_ante>=$i)
   	    		$estatus='Cerrado y Enviado';
         
	    $inpt .='<tr>';                    
	    $inpt .='<td>';
	    if ($pcerrar){                      
	      
	      $inpt .='<div id="boton_'.$i.'">
	                <button type="button" title="Cerrar"
	                  onclick="cerrar_periodo('.$i.', '.$anio_acum.')" 
	                  data-toggle="modal" 
	                  data-target="#exampleModalCenter" 
	                  class="btn btn-primary btn-circle">
	                  <i class="fa fa-list"></i>
	                </button><div id="'.$i.'"></div>
	              </div>';
	    }
	    $inpt .='</td>';
	    $inpt .='<td>'.$i.'</td>';	    
	    $inpt .='<td>'.$anio.'</td>';
	    $inpt .='<td>'.$fechaInic.'</td>';                                        
	    $inpt .='<td>'.$fechaFin.'</td>';                    
	    $inpt .='<td>'.$estatus.'</td>';	    
	    $inpt .='</tr>';

      switch ($j) {        
        case 1:
          $fechaIni1=$fechaInic;
          $fechaFin1=$fechaFin;
          break;        
        case 2:
          $fechaIni2=$fechaInic;
          $fechaFin2=$fechaFin;
          break;
        case 3:
          $fechaIni3=$fechaInic;
          $fechaFin3=$fechaFin;
          break;
        case 4:
          $fechaIni4=$fechaInic;
          $fechaFin4=$fechaFin;
          break;
        case 5:
          $fechaIni5=$fechaInic;
          $fechaFin5=$fechaFin;
          break;      
      }

      $j++;
	    $fechaInic =date("Y-m-d",strtotime($fechaFin." +1 days"));
	    $fechaFin =date("Y-m-d",strtotime($fechaInic." +6 days"));
	  } 

	  $inpt .=' </tbody></table>';

    if ($fechaIni5==''){
      $fechaIni5='1900-01-01';
      $fechaFin5='1900-01-01';
    }

    $stmt = $mbd->prepare("EXEC SW_Cierre_a_ADAM ?,?,?,?,?,?,?,?,?,?");
    $stmt->bindParam(1, $fechaIni1, PDO::PARAM_STR,10);
    $stmt->bindParam(2, $fechaFin1, PDO::PARAM_STR,10);
    $stmt->bindParam(3, $fechaIni2, PDO::PARAM_STR,10);
    $stmt->bindParam(4, $fechaFin2, PDO::PARAM_STR,10);
    $stmt->bindParam(5, $fechaIni3, PDO::PARAM_STR,10);
    $stmt->bindParam(6, $fechaFin3, PDO::PARAM_STR,10);
    $stmt->bindParam(7, $fechaIni4, PDO::PARAM_STR,10);
    $stmt->bindParam(8, $fechaFin4, PDO::PARAM_STR,10);
    $stmt->bindParam(9, $fechaIni5, PDO::PARAM_STR,10);
    $stmt->bindParam(10,$fechaFin5, PDO::PARAM_STR,10);
    $stmt->execute();
	  echo $inpt;
   	
}	
else
	echo "Debe Iniciar Sesion"; 
?>
