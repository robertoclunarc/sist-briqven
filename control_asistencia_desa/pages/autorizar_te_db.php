<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    include("../BD/conexion.php");
    include("funciones_var.php");
    require("enviodecorreos.php");
    $SP = isset($_GET["SP"])?$_GET["SP"]:"NULL";
    //print $SP;
    if ($SP=='ST')
        $stdlt = 'Horas Extras';
    elseif ($SP=='DLT');
        $stdlt = 'DLT';
    if(!empty($_POST['autorizado'])) {
       // Contando el numero de input seleccionados "checked" checkboxes.
       $checked_contador = count($_POST['autorizado']);
       //echo "<p>Has seleccionado los siguientes ".$checked_contador." trabajadores:</p> <br/>";
       // Bucle para almacenar y visualizar valores activados checkbox.
       $tabla = '<table border=1>
                    <th>Cedula</th>
                    <th>Trabajador</th>
                    <th>Fecha</th>
                    <th>Horas</th>
                    <th>Motivo</th>';
       $contador = 0;                    
       foreach($_POST['autorizado'] as $seleccion) {
		$array = explode("*", $seleccion);
		$cedula=$array[0];
		$nombre=$array[1];
		$fecha=$array[2];
        $horas=$array[11];
        /*****************************************************************************************************/
        /* ESTO QUEDA DESHABILITADO YA QUE HAY TRABAJADORES QUE NO ESTAN HACIENDO TRABAJO ESPECIAL EN OTRAS  */
        /*                    ENTRESAS HERMANAS Y SE VAN DIRECTO PARA DICHAS EMPRESAS                        */  
        /*****************************************************************************************************
//        echo "<br><p>Cedula: ".$cedula .", Nombre: ".$nombre.", Fecha:".$fecha.", Entrada Real1: ".$array[3].", Entrada Real2: ".$array[4].", Salida Real1: ".$array[5].", Salida Real2: ".$array[6].", Entrada Esperada1:".$array[7].", Salida Esperada1:".$array[8].", Hora inicio ST1:".$array[9].", Hora Fin ST1: ".$array[10].", Cedula_session_const: ".$_SESSION['cedula_session_const'].",Motivo:".$array[12]."</p>";i
		$inicio_st2="NULL";
		$fin_st2="NULL";
		$codigo1="08";
		$codigo2="0";
		$CS=$_SESSION['cedula_session_const'];


        
//echo "EXEC dbo.SW_GRABA_ST_CEDULA '".$cedula."', '".$fecha."', '".$array[9]."', '".$array[10]."', NULL, NULL, ".$codigo1.", ".$codigo2.", '".$CS."', '".$array[12]."'";
		$conn = Conectarse_sitt();
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("EXEC dbo.SW_GRABA_ST_CEDULA '".$cedula."', '".$fecha."', '".$array[9]."', '".$array[10]."', NULL, NULL, ".$codigo1.", ".$codigo2.", '".$CS."', '".$array[12]."'");
/*de aqui #1                $stmt = $conn->prepare("EXEC dbo.SW_GRABA_ST_CEDULA ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
                $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
                $stmt->bindParam(2, $fecha,  PDO::PARAM_STR,10);
                $stmt->bindParam(3, $array[9],  PDO::PARAM_STR,5); 
                $stmt->bindParam(4, $array[10],  PDO::PARAM_STR,5); 
                $stmt->bindParam(5, $inicio_st2,  PDO::PARAM_STR,5); 
                $stmt->bindParam(6, $fin_st2,  PDO::PARAM_STR,5); 
                $stmt->bindParam(7, $codigo1,  PDO::PARAM_STR,2); 
                $stmt->bindParam(8, $codigo2,  PDO::PARAM_STR,2); 
                $stmt->bindParam(9, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
  Hasta aqui #1              $stmt->bindParam(10, $array[12],  PDO::PARAM_STR,255); 
*/	
//#2	$stmt->execute();
//#2		$result_set = $stmt->fetchAll();
//#2		var_dump($result_set);      
//#2		 /*Get the current error mode of PDO*/
//#2		$current_error_mode = $conn->getAttribute(PDO::ATTR_ERRMODE);
//		echo "Value of PDO::ATTR_ERRMODE: ".$current_error_mode; 	
//#2		$sms=$result_set[0][1];
//#2		if ($sms=="OK"){
			$motivo=eliminar_acentos($array[12]);

			$insertConst="UPDATE sw_hoja_de_tiempo_real SET ";
                	$insertConst.="autorizado2='".$_SESSION['cedula_session_const']."', ";
                    $insertConst.="fecha_autor2='".date("Y-m-d H:i:s")."' ";
	                $insertConst.="where cedula = '".trim($array[0])."' ";
        	        $insertConst.="and fecha= '".$array[2]."'";
			$link=Conex_Contancia_pgsql();
	        $result = ejecutar_query($link, $insertConst) or die("Error en la Consulta SQL: ".$insertConst);
            if (pg_affected_rows($result)==0){
                $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
            }else{
                $contador=$contador+1;
                $tabla.='<tr><td>'.trim($array[0]).'</td><td>'.trim($array[1]).'</td><td>'.date("d-m-Y", strtotime($array[2])).'</td><td>'.trim($horas).'</td><td>'.trim($motivo).'</td></tr>';
            }
        	pg_close($link);


		//	echo "<script>alert('Se han guardado los cambios'); windows.location='consultar_te.php'</script>";
		}
        
        $tabla.='</table>';
        $asunto="Autorizacion de ".$stdlt;
        $cuerpo="Saludos, Se aprobaron los siguientes registros de ".$stdlt." :<br>".$tabla;
        //$resp1=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmoa@briqven.com.ve","matvxl@briqven.com.ve");
        //$resp2=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmav@briqven.com.ve","matgob@briqven.com.ve");
        //$resp3=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmca@briqven.com.ve","matagm@briqven.com.ve");
        if ($contador>0)
            $resp4=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","informatico12@gmail.com");
//#2	}
        echo "1";

    }else{
        echo "<p><b>Por favor seleccione al menos una opción.</b></p>";
    }

}	
else
	echo "De Iniciar Sesion";
?>
