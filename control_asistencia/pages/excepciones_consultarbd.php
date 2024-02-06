<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');	

	$link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'updatevacation.php', $_SESSION['user_session_const']);
	pg_close($link);

	if ($acceso){	
	      $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
	            <tr>
	                
	                <th width="5%" class="info">Cedula</th>             
	                <th width="15%" class="info">Nombres</th>                       
	                <th width="8%"class="info">Fecha Inicio</th>
	                <th width="8%" class="info">Fecha Fin</th>	                
	                <th width="20%" class="info">Motivo</th>
	            </tr>
	        </thead>
	        <tbody>';
	      $query="select b.*, c.nombre from tb_excepcion_baja_sid b inner join trabajadores c on c.trabajador=b.trabajador order by inicio";

	      $conn=Conex_oramprd();
	      $stid = oci_parse($conn, $query);	      
	      oci_execute($stid);	      
	      
	      while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
	             
	             $inpt .='<tr>';
                    
                    $inpt .='<td>'.$row['TRABAJADOR'].'</td>';          
                    $inpt .='<td>'.$row['NOMBRE'].'</td>';
                    $inpt .='<td>'.$row['INICIO'].'</td>';
                    $inpt .='<td>'.$row['FIN'].'</td>';
                    $inpt .='<td>'.$row['MOTIVO'].'</td>';                    
                    
                    $inpt .='</tr>';
	      }
	      $inpt .=' </tbody></table>';
	      echo $inpt;
		
	}
	else{
		echo "No tiene privilegio para esta operacion";
	}		
}	
else
	echo "Debe Iniciar Sesion";
?>
