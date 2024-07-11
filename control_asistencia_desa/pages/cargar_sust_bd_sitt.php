<?PHP
session_start();
if (isset($_SESSION['cedula_session_const']) && isset($_SESSION['nivel_const'])){
	include("../BD/conexion.php");
	$link=Conex_Contancia_pgsql();
	$conn = Conectarse_sitt();

	$sustituto= isset($_POST["cbosutituto"])?$_POST["cbosutituto"]:"NULL";
	$nombre_sustituto = isset($_POST["hddnombresustituto"])?$_POST["hddnombresustituto"]:"NULL";
	$sustituido= isset($_POST["cbosustituido"])?$_POST["cbosustituido"]:"NULL";	 
	$nombre_sustituido = isset($_POST["hddnombresustituido"])?$_POST["hddnombresustituido"]:"NULL";

	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$puesto= isset($_POST["hddpuesto"])?$_POST["hddpuesto"]:"NULL";
	$desc_puesto= isset($_POST["txtpuesto"])?$_POST["txtpuesto"]:"NULL";
	$causa= isset($_POST["cbocausa"])?$_POST["cbocausa"]:"NULL";

	$finix = date_create($finicio);
	$fini1 = date_format($finix, 'Y-m-d');

	$ffinx = date_create($ffin);
	$ffin1 = date_format($ffinx, 'Y-m-d');
	
	$nombre_sustituto=formato_nombre($nombre_sustituto);
	$nombre_sustituido=formato_nombre($nombre_sustituido);
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

	/*$query="SELECT desc_puesto FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador='".$sustituido."'";	
	$result1 = pg_query($link, $query) or die("Error en la Consulta SQL: ".$query);  
	$fila1=pg_fetch_array($result1);
	$desc_puesto = $fila1['desc_puesto'];*/
	/*execute sw_carga_masiva_sustitucion_mat 14089441,'2020-05-04','2020-05-17','06',8898107,'8004',17110005;*/
		
	$stmt = $conn->prepare("EXEC dbo.sw_carga_masiva_sustitucion_mat ?, ?, ?, ?, ?, ?, ?");
	$stmt->bindParam(1, $sustituto, PDO::PARAM_INT,10);	
	$stmt->bindParam(2, $fini1,  PDO::PARAM_STR,10);
	$stmt->bindParam(3, $ffin1,  PDO::PARAM_STR,10);
	$stmt->bindParam(4, $causa,  PDO::PARAM_STR,2);
	$stmt->bindParam(5, $sustituido,  PDO::PARAM_INT,10);
	$stmt->bindParam(6, $puesto,  PDO::PARAM_STR,4);
	$stmt->bindParam(7, $_SESSION['cedula_session_const'], PDO::PARAM_INT,10);
	$stmt->execute();

	$insertAuditoria="INSERT INTO sustituciones (";
	$insertAuditoria.="sustituido,";
	$insertAuditoria.="sustituto,";
	$insertAuditoria.="fecha_ini,";
	$insertAuditoria.="fecha_fin,";
	$insertAuditoria.="cod_puesto,";
	$insertAuditoria.="desc_puesto,";
	$insertAuditoria.="causal,";
	$insertAuditoria.="login_registrado,";
	$insertAuditoria.="fecha_registro,";
	$insertAuditoria.="nombre_sustituto,";
	$insertAuditoria.="nombre_sustituido";	
	$insertAuditoria.=") VALUES (";
            $insertAuditoria.="'".$sustituido."',";
            $insertAuditoria.="'".$sustituto."',";
            $insertAuditoria.="'".$fini1."',";
            $insertAuditoria.="'".$ffin1."',";
            $insertAuditoria.="'".$puesto."',";
            $insertAuditoria.="'".$desc_puesto."',";
            $insertAuditoria.="'".$causa."',";
            $insertAuditoria.="'".$_SESSION['user_session_const']."',";
            $insertAuditoria.="now(),"; 
            $insertAuditoria.="'".$nombre_sustituto."',";
            $insertAuditoria.="'".$nombre_sustituido."'";           
            $insertAuditoria.=")";
    
    $result = pg_query($link,$insertAuditoria) or die("Error en la Consulta SQL:" . $insertAuditoria);	
	pg_close($link); 
	pg_free_result($result);
	
	echo "Procesado";	
}	
else
	echo "De Iniciar Sesion";

function formato_nombre($nombre)
{
	$pos = strpos($nombre, '-');

	if ($pos === false) {
	    $nombre=trim($nombre);
	} else {
	    $nombre=trim(substr($nombre, $pos+1, strlen($nombre)-1));
	}

	return $nombre;
}
?>
