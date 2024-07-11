<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    include("../BD/conexion.php");
    include("funciones_var.php");
    if(!empty($_POST['autorizado'])) {
//print_r($_POST);
      // Contando el numero de input seleccionados "checked" checkboxes.
       $checked_contador = count($_POST['autorizado']);
       //echo "<p>Has seleccionado los siguientes ".$checked_contador." trabajadores:</p> <br/>";
       // Bucle para almacenar y visualizar valores activados checkbox.
       foreach($_POST['autorizado'] as $seleccion) {
   	   $array = explode("*", $seleccion);
           //AQUI SE COLOCA LA INSTRUCCION PARA LLAMAR AL STORED POCEDURE
//	 echo "<br><p>Cedula: ".$array[0] .", Fecha: ".$array[1].", Nombre Trabajador:".$array[2].", Entrada Real1: ".$array[3].", Salida Real1: ".$array[4].", Entrada Real2: ".$array[5].", Salida Real2: ".$array[6].", Cambio Turno:".$array[7].", Turno:".$array[8].", Motivo:".$array[9].", Accion: ".$array[10].", Cedula_session_const: ".$_SESSION['cedula_session_const']."</p>";
	if ($array[10]==1){	
	 	switch ($array[8]) {
	    		case 1:
		   		$entrada1 ="23:00";	
		    		$salida1 	="07:00";
		    		$entrada2	="";
		    		$salida2	="";
	        		break;
	    		case 2:
		    		$entrada1 ="07:00";	
		    		$salida1 	="15:00";
		    		$entrada2	="";
		    		$salida2	="";
	        		break;
	    		case 3:
		    		$entrada1 ="15:00";	
		    		$salida1 	="23:00";
		    		$entrada2	="";
		    		$salida2	="";
	        		break;
	 	}
		$conn = Conectarse_sitt();
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               	$stmt = $conn->prepare("EXEC SW_GRABA_CAMBIO_ESPERANZA_CEDULA ?, ?, ?, ?, ?, ?, ?");
                $stmt->bindParam(1, $array[0],  PDO::PARAM_INT,10);
       	        $stmt->bindParam(2, $array[1],  PDO::PARAM_STR,10);
               	$stmt->bindParam(3, $entrada1,  PDO::PARAM_STR,5);
                $stmt->bindParam(4, $salida1,  PDO::PARAM_STR,5);
       	        $stmt->bindParam(5, $entrada2,  PDO::PARAM_STR,5);
               	$stmt->bindParam(6, $salida2,  PDO::PARAM_STR,5); 
	        $stmt->bindParam(7, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10); 
                $stmt->execute();

        	echo "Procesado";

	  }elseif ($array[10]==0){
		 
                $conn = Conectarse_sitt();
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("EXEC poner_fichada_completa ?, ?");
                $stmt->bindParam(1, $array[1],  PDO::PARAM_STR,10);
                $stmt->bindParam(2, $array[0],  PDO::PARAM_INT,10);
                $stmt->execute();
                echo "Procesado:".$array[0];
		
	  }

		$insertConst="UPDATE registro_diario SET ";
        	$insertConst.="bloqueado=1, ";
	        $insertConst.="autorizado_ct='".$_SESSION['cedula_session_const']."' ";
	        $insertConst.="where trabajador = '".trim($array[0])."' ";
        	$insertConst.="and fecha= '".$array[1]."'";
print $insertConst;	
		$link=Conex_Contancia_pgsql();
		$result = ejecutar_query($link, $insertConst) or die("Error en la Consulta SQL: ".$insertConst);	
		pg_close($link);
       }
    }else{
        echo "<p><b>Por favor seleccione al menos una opción.</b></p>";
    }


}	
else
	echo "De Iniciar Sesion";
?>
