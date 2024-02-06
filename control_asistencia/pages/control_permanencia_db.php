<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
require('funciones_var.php');
/*	$conn = Conectarse_sitt();

	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";

	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

	$hinicio1= isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";  
	$hfinal1= isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL"; 

	$hinicio2= isset($_POST["txthoraini2"])?$_POST["txthoraini2"]:"NULL";  
	$hfinal2= isset($_POST["txthorafin2"])?$_POST["txthorafin2"]:"NULL"; 

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');

	$hinix = date_create($hinicio1);
	$hini1 = date_format($hinix, 'H:i');

	$hfinz = date_create($hfinal1);
	$hfin1 = date_format($hfinz, 'H:i');

	if ($hinicio2!="NULL" && $hfinal2!="NULL" && $hinicio2!="" && $hfinal2!="")
	{	
		$hinik = date_create($hinicio2);
		$hini2 = date_format($hinik, 'H:i');
		$hfinj = date_create($hfinal2);
		$hfin2 = date_format($hfinj, 'H:i');
	}
	else
	{
		$hini2 = '';	
		$hfin2 = '';
	}

	$tiempoInicio = strtotime($fini1);
	$tiempoFin = strtotime($ffin1);
	# 24 horas * 60 minutos por hora * 60 segundos por minuto
	$dia = 86400;
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	while($tiempoInicio <= $tiempoFin){
		# Podemos recuperar la fecha actual y formatearla	
		$fechaActual = date("Y-m-d", $tiempoInicio);
		//printf("Fecha dentro del ciclo: %s\n", $fechaActual);
		$stmt = $conn->prepare("EXEC dbo.modificar_fichada ?, ?, ?, ?, ?, ?");
		$stmt->bindParam(1, $fechaActual, PDO::PARAM_STR,10);
		$stmt->bindParam(2, $trabajador,  PDO::PARAM_INT,10);
		$stmt->bindParam(3, $hini1,  PDO::PARAM_STR,5);
		$stmt->bindParam(4, $hfin1,  PDO::PARAM_STR,5);
		$stmt->bindParam(5, $hini2,  PDO::PARAM_STR,5);
		$stmt->bindParam(6, $hfin2,  PDO::PARAM_STR,5); 
		$stmt->execute();
		# Sumar el incremento para que en algún momento termine el ciclo
		$tiempoInicio += $dia;
	}
	echo "Procesado";
*/

	$link=Conex_Contancia_pgsql();
	$fecha_fichada= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
	$cbotrabajador=isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
	$hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
        $hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
	$hinicio2           = isset($_POST["txthoraini2"])?$_POST["txthoraini2"]:"NULL";
        $hfinal2            = isset($_POST["txthorafin2"])?$_POST["txthorafin2"]:"NULL";
        $observacion        = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"";
        $hoy= date("Y") . "-" . date("m") . "-" . date("d");
	$query="select * from registro_diario where trabajador in ('".trim($cbotrabajador)."') and fecha='".$fecha_fichada."'";
//	print $query."-".$hinicio1."-".$hfinal1 ;
        $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
        $numReg = ejecutar_num_rows($result);
	if($numReg==0){
    $insertConst="INSERT INTO registro_diario (";
                    $insertConst.="trabajador,";
                    $insertConst.="fecha,";
                    $insertConst.="asistio,";
                    $insertConst.="sobre_tiempo,";
                    $insertConst.="cambio_turno,";
                    $insertConst.="comision,";
                    $insertConst.="inasistencia,";
                    $insertConst.="observacion,";
                    $insertConst.="fecha_reg,";
                    $insertConst.="trabajador_reg, ";
                    $insertConst.="entrada_real1, ";
                    $insertConst.="salida_real1, ";
                    $insertConst.="entrada_real2, ";
                    $insertConst.="salida_real2, ";
                    $insertConst.="bloqueado ";
                    $insertConst.=") VALUES (";
                    $insertConst.="'".trim($cbotrabajador)."',";
                    $insertConst.="'".$fecha_fichada."',";
                    $insertConst.="'S', ";
                    $insertConst.="'N', ";
                    $insertConst.="'N', ";
                    $insertConst.="'N', ";
                    $insertConst.="0, ";
                    $insertConst.="'".trim($observacion)."', ";
                    $insertConst.="'".$hoy."', ";
                    $insertConst.="'".$_SESSION['user_session_const']."', ";
                    $insertConst.="'".$hinicio1."',";
                    $insertConst.="'".$hfinal1."',";
                    $insertConst.="'".$hinicio2."',";
                    $insertConst.="'".$hfinal2."',";
                    $insertConst.="'0'";
                    $insertConst.=")";
		    $mens="Se actualizaron los registros de entrada";
     }else{
/*        while ($fila=ejecutar_fetch_array($result))
        {
                $observacionDB= trim($fila['observacion']);
        }
*/
        $insertConst="UPDATE registro_diario SET ";
        $insertConst.="entrada_real1='".$hinicio1."', ";
        $insertConst.="salida_real1='".$hfinal1."'," ;
        $insertConst.="entrada_real2='".$hinicio2."', ";
        $insertConst.="salida_real2='".$hfinal2."'," ;
        $insertConst.="observacion='".trim($observacion)."' ";
        $insertConst.="where trabajador = '".trim($cbotrabajador)."' ";
        $insertConst.="and fecha= '".$fecha_fichada."'";
        $mens="Se actualizaron los registros de entrada y salida reales";
      }

        $result = pg_query($link,$insertConst);
        if (pg_affected_rows($result)==0)
        {
            echo("ERROR");
            die(" Consulta SQL: ".$insertConst);
        }
        $ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
        pg_close($link);
        echo $mens;
       
	require("enviodecorreos.php");
	$asunto=$desc_mot." ".$nombre_completo;
	$cuerpo="Saludos, al trabajador: ".trim($cbotrabajador)." - ".nombre_trabajadores(trim($cbotrabajador)).", se le modific&oacute; la entrada y salida real, por ".trim($observacionDB)."\n".trim($observacion);
  	$resp=ENVIAR_CORREO($cuerpo),$asunto,"",$_SESSION['userid'], $desc_mot);


}	
else
	echo "De Iniciar Sesion";
?>
