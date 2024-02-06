<script  language="javascript">

//(*--------------------------------------------*)
function check_uncheckAll(cb) {
	if($(cb).is(":checked"))
    {
        checkAll();
    }else{
        uncheckAll();
    }
    
  }

//(*--------------------------------------------*)
function checkAll() {
    document.querySelectorAll('#formulario input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });

}

//(*--------------------------------------------*)
function uncheckAll() {
    document.querySelectorAll('#formulario input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = false;
    });
}




</script>

<?php
if (isset($_SESSION['user_session_const'])){
	echo "<body>
	<script type='text/javascript'>
	window.location='../index.php';
	</script>
	</body>";
}	
include("../BD/conexion.php");
require_once('funciones_var.php'); 
session_start();
	$status     = isset($_POST["status"])?$_POST["status"]:"NULL";         //
	$nivel      = isset($_POST["nivel"])?$_POST["nivel"]:"NULL";
	$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$acceso     = isset($_POST["acceso"])?$_POST["acceso"]:"NULL";
//print_r($_POST);
	if ($_SESSION['nivel_const']==1){
		if ($acceso=='NULL')
		    $qry="SELECT DISTINCT u.*, t.nombre, cast (t.trabajador AS integer) FROM usuarios u INNER JOIN trabajadores t ON u.trabajador = t.trabajador LEFT JOIN accesos_usuarios au ON u.login_username = au.login WHERE 1=1 ";
		else
			$qry="select u.*, t.nombre, au.operacion, a.idacceso, a.descripcion from usuarios u inner join trabajadores t on u.trabajador = t.trabajador left join accesos_usuarios au on u.login_username = au.login left join accesos a on au.fkacceso = a.idacceso WHERE 1=1 ";
        if ($status!='NULL')
            $qry.=" and u.estatus = '".$status."'";
        if ($nivel!='NULL')
            $qry.=" and u.nivel = '".$nivel."'";
        if ($trabajador!='NULL')
            $qry.=" and u.trabajador = '".$trabajador."'";
        if ($acceso!='NULL')
            $qry.=" and a.idacceso = '".$acceso."'";
        
		 $qry.=" order by cast (t.trabajador AS integer)";

	    buscar($qry);     
    }else{
	    //header('Location: /login/index.php');
		echo "<body>
		<script type='text/javascript'>
		window.location='../index.php';
		</script>
		</body>";
	} 	

	function buscar($b) {
		$link=Conex_Contancia_pgsql();
		//print $b;
		$result = ejecutar_query($link, $b) or die("Error en la Consulta SQL: ".$b);
		$contar = ejecutar_num_rows($result);
        
	        if($contar == 0){
	              $inpt = "No se han encontrado resultados!";
	              
	        }else{
	              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
	              
	              $inpt = $inpt.'<thead>
	            <tr>
	                <th class="info"></th>             
	                <th class="info"><h6>C&eacute;dula</h6></th>             
	                <th class="info"><h6>Nombre</h6></th>  
	                <th class="info"><h6>Status</h6></th>
	                <th class="info"><h6>Nivel</h6></th>
	                <th class="info"><h6>Acción</h6></th>
	                <th class="info"><h6>Acción2</h6></th>
	            </tr>
	        </thead>
	        <tbody>';
              $contar=0;
		      while ($row=ejecutar_fetch_array($result)){
		      	        $separador='-';
		      	        //$fecha = formato_fecha(substr($row['fecha'], 0,10),$separador);
		      	        //$param = $row["trabajador"].",'".$row['fecha']."', 'E'";
	                    $contar++;
	                    $inpt .='<tr>';
	                    $inpt .='<td style="text-align: right"><h6>'.$contar.'</h6></td>';    
			            $inpt .='<td><h6><input type="hidden" name="trabajador[]" value="'.$row['trabajador'].'"></input><button type="button" data-toggle="modal" onclick="ver_fichadas('.$row["trabajador"].')" data-target="#exampleModalCenter">'.$row["trabajador"].'</button></h6></td>';                    
	                    $inpt .='<td><h6><input type="hidden" name="nombre[]" value="'.$row['nombre'].'"></input>'.$row['nombre'].'</h6></td>';
	                    $inpt .='<td><h6><input type="hidden" name="estatus[]" value="'.$row['estatus'].'"></input>'.$row['estatus'].'</h6></td>';
	                    $inpt .='<td><h6><input type="hidden" name="nivel[]" value="'.$row['nivel'].'"></input>'.$row['nivel'].'</h6></td>'; 
	                    $inpt .='<td><h6><button type="button" data-toggle="modal" onclick="updateUser('.$row["trabajador"].')" data-target="#exampleModalCenter_updateUser"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></button></h6></td>';
	                    //$inpt .='<td><h6><i class="fa fa-pencil fa-fw" aria-hidden="true" onclick="updateUser('.$row["trabajador"].')"></i></h6></td>'; 
	                    if ($row['estatus']=="ACTIVO"){
	                    	$parametros=$row["trabajador"].' || lock';
	                    	print $parametros;
	                    	$inpt .='<td><h6><i class="fa fa-lock fa-2x" aria-hidden="true" onclick="updateUser('.$parametros.')"></i></h6></td>'; 
	                    }else {
	                    	$parametros=$row["trabajador"].' || unlock';
	                    	print $parametros;
                        	$inpt .='<td><h6><i class="fa fa-unlock-alt fa-2x" aria-hidden="true" onclick="updateUser('.$parametros.')"></i></h6></td>'; 
                        }
	                    $inpt .='</tr>';                        

	              } 
	            $inpt .='</table>
	            <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    ';   
	        }
		echo $inpt;//."-".$b;
		//print_r($row);
	}        

?>
