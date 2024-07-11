<?PHP
  	require_once("../BD/conexion.php");  	
    require_once('funciones_var.php');
    $con=Conex_rrhh_pgsql();
  	$cedula = isset($_GET["trabajador"])?$_GET["trabajador"]:"'1'";
    $puesto = isset($_GET["puesto"])?$_GET["puesto"]:"";
  	$query="select puesto, desc_puesto, sistema_horario from adam_vw_dotacion_briqven_02_mas where trabajador='".$cedula."'";
  	$result = ejecutar_query($con, $query) or die("Error en la Consulta SQL: ".$query);

    $dato1='';
    $dato2='';
    $dato3='';
  	    	    
    while ($fila=ejecutar_fetch_array($result))
    {
        $dato1=$fila['puesto'];
        $dato2=$fila['desc_puesto'];
        $dato3=$fila['sistema_horario'];
    }

    pg_close($con);
  	pg_free_result($result);
//print $puesto;			
	echo "$(\"#txtpuesto".$puesto."\").val(\"" . $dato1." - ".$dato2 . "\");\n" ;
	echo "$(\"#hddpuesto".$puesto."\").val(\"" . $dato1 . "\");\n" ;
    echo "$(\"#hddcuadrilla\").val(\"" . $dato3 . "\");\n" ; 
    echo "$(\"#cboSH".$puesto."\").val(\"" . $dato3 . "\");\n" ; 
?>