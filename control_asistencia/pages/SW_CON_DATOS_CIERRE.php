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
    $fechaFinx=formato_fecha(strval($fila['finper']),'/');
    $finper=$fila['finper'];
    $sem_ante=$fila['sem_ante']; 
    $anio=$fila['anio'];
    $max_sem=$fila['max_sem'];
    $mes_acum = $fila['mes_acum'];
    $anio_acum =$fila['anio_acum'];
    $min_sem=$fila['min_sem'];
    $min_anio=$fila['min_anio'];    
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

   //$fechaInic=date("Y-m-d", strtotime( $iniper));
   //$fechaFin =date("Y-m-d",strtotime($fechaInic." +6 days"));

  
  $fechaInicio=strtotime($iniper);
  $fechaFinx  =strtotime($fechaFinx);

  $ms=$min_sem;	
	$i=$fechaInicio;
	$arrayFechaIni=array();
	$arrayfechaFin=array();
	$arraySem=array();
	$arrayAnio=array();
	while( $i<=$fechaFinx ){
			if ($ms>$max_sem){							  
					$ms=1;
					$min_anio=$min_anio+1;
			}
			$j=date("d-m-Y",strtotime(date("d-m-Y", $i)." +6 days"));
	    //echo date("d-m-Y", $i)."%". $j.'('.$ms."<br>";
	    array_push($arrayFechaIni, date("d-m-Y", $i));
	    array_push($arrayfechaFin, $j);
	    array_push($arraySem, $ms);
	    array_push($arrayAnio, $min_anio); 
	    $i+=86400*7;
	    $ms++;

	}
	
   
   foreach ($arrayFechaIni as $clave => $valor) {

   	    $estatus='Abierto';
   	    if ($sem_ante>=$min_sem)
   	    	if ($sem_ante>=$i)
   	    		$estatus='Cerrado';
	    $inpt .='<tr>';                    
	    $inpt .='<td>';
	    $i=$arraySem[$clave];
	    $fechaInic=$valor;
	    $fechaFin=$arrayfechaFin[$clave];
	    $anio_acum=$arrayAnio[$clave];
	    if ($pcerrar){                      
	      
	      $inpt .='<div id="boton_'.$i.'">
	                <button type="button" title="Cerrar"
	                  onclick="cerrar_periodo('.$i.', '.$anio_acum.')" 
	                  data-toggle="modal" 
	                  data-target="#exampleModalCenter" 
	                  class="btn btn-primary btn-circle">
	                  <i class="fa fa-list"></i>
	                </button><div id="'.$i.'"></div>
                  <input type="hidden" id="hddprogreso_'.$i.'" value="-1" name="progreso_'.$i.'" >
	              </div>';
	    }
	    $inpt .='</td>';
	    $inpt .='<td>'.$i.'</td>';	    
	    $inpt .='<td>'.$anio_acum.'</td>';
	    $inpt .='<td>'.$fechaInic.'</td>';                                        
	    $inpt .='<td>'.$fechaFin.'</td>';                    
	    $inpt .='<td>'.$estatus.'</td>';	    
	    
	    $inpt .='</tr>';
	    
	} 

	$inpt .=' </tbody></table>';   
  
	echo $inpt;
   	
}	
else
	echo "Debe Iniciar Sesion"; 
?>
