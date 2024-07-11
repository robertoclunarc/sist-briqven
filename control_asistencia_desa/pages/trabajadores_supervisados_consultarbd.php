<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	require_once('funciones_var.php');	

    $link=Conex_Contancia_pgsql();
	$acceso=permiso_usuario($link, 'UPDATE', 'trabajadores_supervisados', $_SESSION['user_session_const']);
    pg_close($link);

	//print_r($_POST);
	//print_r($_GET);

    $link=Conex_rrhh_pgsql();

	if ($acceso){	
	    $id         = isset($_GET["id"])?$_GET["id"]:'';
		$trabajador = isset($_POST["cbosupervisor"])?$_POST["cbosupervisor"]:"NULL";
		$inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
	              


	    if ($id=='2'){

			$query="SELECT nivel_jerarquico FROM adam_VW_DOTACION_BRIQVEN_02_MAS WHERE trabajador = '".$trabajador."'";
			  $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
			  $numReg = ejecutar_num_rows($result);
			  if($numReg>0){
			    $row    = ejecutar_fetch_array($result);
			    $nivel_jerarquico = $row['nivel_jerarquico'];
			  }


	    	//$nivel_jerarquico         = isset($_GET["id2"])?$_GET["id2"]:'';
	    	$inpt = $inpt.'<thead>
		            <tr>
		                
		                <th width="5%" class="info">Cedula</th>             
		                <th width="15%" class="info">Nombres</th>                       
		            </tr>
		        </thead>
		        <tbody>';	    	
	    	//$nivel_jerarquico = isset($_POST["txtnivel_jerarquico_supervisor"])?$_POST["txtnivel_jerarquico_supervisor"]:"NULL";
	    
		    //$option1=trabajadores_del_supervisor($trabajador,$nivel_jerarquico);

		    switch ($nivel_jerarquico) {
		        case 1:          
		            $filtro = "direccion";            
		            break;
		        case 2:          
		            $filtro = "direccion";            
		            break;
		        case 3:          
		            $filtro = "gergral";            
		            break;
		        case 4:          
		            $filtro = "gerencia";            
		            break;
		        case 5:          
		            $filtro = "depto";            
		            break;
		        case 6:          
		            $filtro = "coordina";            
		            break;
		        default:          
		            $filtro = "coordina";
		    }  

		    
		        $query="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
		        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$trabajador."') AND nivel_jerarquico::integer>=".$nivel_jerarquico."";
		        $query.=" UNION ";
		        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
		        WHERE trim(trabajador_sup) = '".$trabajador."'";
		        $query.=" UNION ";
		        $query.= "SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
		        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
		        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
		                       WHERE trim(trabajador)='".$trabajador."') 
		                       AND nivel_jerarquico::integer>=".$nivel_jerarquico.")";
		        $query.=" UNION ";
		        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM supervisores_trabajadores 
		        WHERE trabajador_sup = '".$trabajador."'";
		        $query.=" ORDER BY nombre";
		        //print $query;
        		$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
			    while ($fila = ejecutar_fetch_array($result)){
			             $inpt .='<tr>';
		                    
		                    $inpt .='<td>'.$fila['trabajador'].'</td>';          
		                    $inpt .='<td>'.$fila['nombre'].'</td>';
		                    $inpt .='</tr>';

				}
            
	    }elseif ($id=='1') {
	    	$inpt = $inpt.'<thead>
		            <tr>
		                
		                <th width="5%" class="info">Cedula</th>             
		                <th width="15%" class="info">Nombres</th>                       
		                <th width="15%" class="info"></th> 
		            </tr>
		        </thead>
		        <tbody>';
		    $query="select * from supervisores_trabajadores st where trabajador_sup ='".$trabajador."' order by nombre";
		    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
		    $numReg = ejecutar_num_rows($result);
		    if($numReg>0){
			    while ($fila = ejecutar_fetch_array($result)){
		             $inpt .='<tr>';
	                    
	                    $inpt .='<td>'.$fila['trabajador'].'</td>';          
	                    $inpt .='<td>'.$fila['nombre'].'</td>';
	                    $inpt .='<td><INPUT id="eliminar_'.$fila['trabajador'].'" type="button" value="Eliminar"  class="btn btn-success" onclick="eliminar('.$fila['trabajador'].');"/></td>';
	                    $inpt .='</tr>';

			    }
		     }

	         
        }    
	    pg_close($link);			
	    echo $inpt;
	}else{
		echo "No tiene privilegio para esta operacion";
	}	
	
}	
else
	echo "Debe Iniciar Sesion";
?>
