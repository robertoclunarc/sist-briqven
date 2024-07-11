<?PHP
  	require_once("../BD/conexion.php");  	
    require_once('funciones_var.php');
    $con=Conex_rrhh_pgsql();
    $link_sitt=Conectarse_sitt();
  	$cedula = isset($_GET["sustituido"])?$_GET["sustituido"]:"'1'";
    $puesto = isset($_GET["puesto"])?$_GET["puesto"]:"";
  	$query  = "select puesto, desc_puesto, sistema_horario from adam_vw_dotacion_briqven_02_mas where trabajador='".$cedula."'";
  	$result = ejecutar_query($con, $query) or die("Error en la Consulta SQL: ".$query);
    $row    = ejecutar_fetch_array($result);
    $id_puesto_actual = $row['puesto'];
    $desc_puesto_actual = $row['desc_puesto'];
    echo "$(\"#hddpuesto2\").val(\"" . $desc_puesto_actual . "\");\n" ;
 
    $option5= "" ;
    $qry5="select puesto, desc_puesto from ADAM_PUESTOS order by 1";
    $stmt5 = $link_sitt->query($qry5);
    $option5='<select name="cbopuesto2" id="cbopuesto2" data-width="100%" data-size="5"class="selectpicker">
                                        <option value="NULL">Seleccione el Puesto</option>';
    while($row5 = $stmt5->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
    {
       //print $puesto_actual."==".$row5['puesto']."<br>";
       if ($id_puesto_actual==$row5['puesto']){
           $option5.= "<option selected value='". $row5['puesto']."'>" ;
       }else 
           $option5.= "<option value='". $row5['puesto']."'>" ;

       $option5.= $row5['puesto']." - ".$row5['desc_puesto']. "</option>"; 
    }    
    $option5.="</select>";
    pg_close($con);
  	pg_free_result($result);
	
    echo $option5;
    
    
?>