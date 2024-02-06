<?PHP
$tabla = isset($_GET["tabla"])?$_GET["tabla"]:"";
$campo1 = isset($_GET["campo1"])?$_GET["campo1"]:"";        //Campo de la tabla que contiene el códigoo valor del elemento
$campo2 = isset($_GET["campo2"])?$_GET["campo2"]:"";		//campo que cintiene la descripcion del elemento
$selected = isset($_GET["selected"])?$_GET["selected"]:"";  //valor (campo1) seleccionado
$orderby= isset($_GET["orderby"])?$_GET["orderby"]:"";      //Campo(s) de ordenamiento, separados por ","
$where= isset($_GET["where"])?$_GET["where"]:"";
$vwhere= isset($_GET["vwhere"])?$_GET["vwhere"]:"";             //Condicion 
$first= isset($_GET["first"])?$_GET["first"]:"";            //Primer Valor del 1er Elem opcional) NO DB. Ejm null 
$firsttext= isset($_GET["firsttext"])?$_GET["firsttext"]:"";    //Texto del 1er Elem (opcional) NO DB. Ejm <ninguno> <

include("../BD/conexion.php");
$link=Conex_Contancia_pgsql();
if ($campo2 != "") {
		$query = "select ". $campo1 . ", " . $campo2 . " from " . $tabla;

		if ($where!=""){
			$query=$query . " where " . $where . "='". $vwhere."'";  
		}

		if ($orderby!=""){
			$query=$query . " order by " . $orderby;  
		}


		//Agrego el Primer elemento de Cabecera. Sino hay
		if ($first=="") $first="null";
		if ($firsttext!="")
		{
			echo "<option value=". $first . "" ;
			echo ">". $firsttext. "</option>"; 
		}
} else{
		$query = "select distinct ". $campo1 . " from " . $tabla;

		if ($where!=""){
			$query=$query . " where " . $where . "=". $vwhere; 
		}
		if ($orderby!=""){
			$query=$query . " order by " . $orderby;  
		}

		//Agrego el Primer elemento de Cabecera. Sino hay
		if ($first=="") $first="null";
		if ($firsttext!="")
		{
			echo "<option value=". $first . "" ;
			echo ">". $firsttext. "</option>"; 
		}
}	
//echo $query;	
$result = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);
$numReg = pg_num_rows($result);
//echo $numReg;

if($numReg>0){
	
	//echo "<SELECT>";
      switch ($tabla) {
        case "baremo":
		//echo  "<option selected value=\"0\">Seleccione la Puntuaci&oacute;n</option>";
		while ($fila=pg_fetch_array($result)) 
		{
			echo "<option value='". $fila['puntuacion']."'";
			switch ($fila['puntuacion']) {
			        case 0:
//			            echo " data-content=\"<span class='badge badge-success'>".$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
				   echo " data-content=\"<span class='badge badge-success'>Relish</span>\"";
			            break;
			        case 1:
			            echo  " data-content=\"<span class='label label-warning'>".$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
			            break;
			        case 2:
			            echo " data-content=\"<span class='label label-success'>".$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
			            break;
			        case 3:
			            echo " data-content=\"<span class='label label-primary'>".$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
			            break;
			        case 4:
			            echo " data-content=\"<span class='label label-info'>".$fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
			            break;
 			     }
			      //echo $fila['puntuacion']." => ". $fila['porcentaje']."%</span>\"";
			      echo ">". $fila['puntuacion']." - ". $fila['porcentaje']."%</option>";
		}
            	break;
	default:
		while ($fila=pg_fetch_array($result)) 
		{
			echo "<option value='". $fila[$campo1]."'" ;
			if ($selected == $fila[$campo1])
			  echo " selected='selected'" ;
			if ($campo2 != "")
				echo ">". $fila[1]. "</option>";
			else	 
				echo ">". $fila[0]. "</option>";
		}
            break;
        }
}

//echo $query; 
pg_free_result($result);
pg_close($link);

?>
