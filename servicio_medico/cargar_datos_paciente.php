<?PHP

require("include_conex.php");

$cedula= isset($_GET["cedula"])?$_GET["cedula"]:"";            //Condicion 
 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

//$query1 = "select ci from v_historias where ci='" . $cedula . "'";

//$resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query1);
//if (pg_num_rows($resultado1)==0) {

	$query = "select * from v_pacientes where ci='" . $cedula . "' ";	

	$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);

	$numReg = pg_num_rows($resultado);

	if($numReg>0){

		while ($fila=pg_fetch_array($resultado)) {
			echo "$(\"#txtId\").val(\"" . $fila["uid_paciente"] . "\");\n" ;
			echo "$(\"#txtCi\").val(\"" . $fila["ci"] . "\");\n" ;
			echo "$(\"#ci\").val(\"" . $fila["ci"] . "\");\n" ;
			echo "$(\"#txtNombre\").val(\"" . $fila["nombre_completo"] . "\");\n" ;
			echo "$(\"#txtFechaNac\").val(\"" . $fila["fechanac"] . "\");\n" ;
			//echo "$(\"#txtFechaNac\").val(\"" . substr($fila["fechanac"],8,2) . "/" . substr($fila["fechanac"],5,2) . "/". substr($fila["fechanac"],0,4) . "\");\n";		
			echo "$(\"#txtDepartamento\").val(\"" . $fila["departamento"] . "\");\n" ;
			echo "$(\"#txtGerencia\").val(\"" . $fila["gcia"] . "\");\n" ;
			echo "$(\"#txtContratista\").val(\"" . $fila["contratista"] . "\");\n" ;
			echo "$(\"#txtCargo\").val(\"" . $fila["cargo"] . "\");\n" ;
			echo "$(\"#txttipo_sangre\").val(\"" . $fila["tipo_sangre"] . "\");\n" ;		
			echo "$(\"#cbomano_dominante\").val(\"" . $fila["mano_dominante"] . "\");\n" ;
			echo "$(\"#txtSexo\").val(\"" . $fila["sexo"] . "\");\n" ;
			echo "$(\"#txttelefono\").val(\"" . $fila["telefono"] . "\");\n" ;
			echo "$(\"#txtEdocivil\").val(\"" . $fila["edo_civil"] . "\");\n" ;
			echo "$(\"#tipo_disca\").val(\"" . $fila["tipo_discapacidad"] . "\");\n" ;
			echo "$(\"#discapacidad\").val(\"" . $fila["desc_discapacidad"] . "\");\n" ;
			echo "$(\"#alergia\").val(\"" . $fila["alergia"] . "\");\n" ;
			echo "$(\"#estado_paciente\").val(\"" . $fila["estado_paciente"] . "\");\n" ;
			
		}
		//pg_free_result($resultado);
	}
 	else
		echo "0";
pg_free_result($resultado);
pg_close($conexion);
?>
