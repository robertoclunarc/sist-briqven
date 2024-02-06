<?php
include("../constancias/BD/conexion.php");
$conn=Conex_oramprd();
            $sql = "SELECT
    COMPANIAS.NOMBRE_CIA, COMPANIAS.DOMICILIO, COMPANIAS.DOMICILIO2, COMPANIAS.POBLACION, COMPANIAS.ESTADO_PROVINCIA, COMPANIAS.CODIGO_POSTAL, COMPANIAS.REGISTRO_FISCAL, COMPANIAS.TELEFONO,
    DSSC_PORCENTAJE_ARC.PORCEN_MES_01, DSSC_PORCENTAJE_ARC.PORCEN_MES_02, DSSC_PORCENTAJE_ARC.PORCEN_MES_03, DSSC_PORCENTAJE_ARC.PORCEN_MES_04, DSSC_PORCENTAJE_ARC.PORCEN_MES_05, DSSC_PORCENTAJE_ARC.PORCEN_MES_06, DSSC_PORCENTAJE_ARC.PORCEN_MES_07, DSSC_PORCENTAJE_ARC.PORCEN_MES_08, DSSC_PORCENTAJE_ARC.PORCEN_MES_09, DSSC_PORCENTAJE_ARC.PORCEN_MES_10, DSSC_PORCENTAJE_ARC.PORCEN_MES_11, DSSC_PORCENTAJE_ARC.PORCEN_MES_12,
    DSSC_RESULT_ARC.TRABAJADOR, DSSC_RESULT_ARC.NACIONALIDAD, DSSC_RESULT_ARC.NOMBRE, DSSC_RESULT_ARC.DESCSEDE, DSSC_RESULT_ARC.PERDESDE, DSSC_RESULT_ARC.PERHASTA, DSSC_RESULT_ARC.REM_MES01, DSSC_RESULT_ARC.REM_MES02, DSSC_RESULT_ARC.REM_MES03, DSSC_RESULT_ARC.REM_MES04, DSSC_RESULT_ARC.REM_MES05, DSSC_RESULT_ARC.REM_MES06, DSSC_RESULT_ARC.REM_MES07, DSSC_RESULT_ARC.REM_MES08, DSSC_RESULT_ARC.REM_MES09, DSSC_RESULT_ARC.REM_MES10, DSSC_RESULT_ARC.REM_MES11, DSSC_RESULT_ARC.REM_MES12, DSSC_RESULT_ARC.IMP_RET01, DSSC_RESULT_ARC.IMP_RET02, DSSC_RESULT_ARC.IMP_RET03, DSSC_RESULT_ARC.IMP_RET04, DSSC_RESULT_ARC.IMP_RET05, DSSC_RESULT_ARC.IMP_RET06, DSSC_RESULT_ARC.IMP_RET07, DSSC_RESULT_ARC.IMP_RET08, DSSC_RESULT_ARC.IMP_RET09, DSSC_RESULT_ARC.IMP_RET10, DSSC_RESULT_ARC.IMP_RET11, DSSC_RESULT_ARC.IMP_RET12, DSSC_RESULT_ARC.VAR_CAR_12, DSSC_RESULT_ARC.CENTRO_COSTO
FROM
    ADAM.COMPANIAS COMPANIAS ,  ADAM.DSSC_PORCENTAJE_ARC DSSC_PORCENTAJE_ARC 
  , ADAM.DSSC_RESULT_ARC 
WHERE
    COMPANIAS.COMPANIA = DSSC_PORCENTAJE_ARC.COMPANIA
    AND   DSSC_PORCENTAJE_ARC.COMPANIA = DSSC_RESULT_ARC.COMPANIA 
    AND   DSSC_PORCENTAJE_ARC.TRABAJADOR = DSSC_RESULT_ARC.TRABAJADOR (+)
    AND  FN_VALIDA_TN_MAS( DSSC_RESULT_ARC.TRABAJADOR) = 1
    and DSSC_RESULT_ARC.TRABAJADOR not in (  12360516,    9949859)
    group by COMPANIAS.NOMBRE_CIA, COMPANIAS.DOMICILIO, COMPANIAS.DOMICILIO2, COMPANIAS.POBLACION, COMPANIAS.ESTADO_PROVINCIA, COMPANIAS.CODIGO_POSTAL, COMPANIAS.REGISTRO_FISCAL, COMPANIAS.TELEFONO,
    DSSC_PORCENTAJE_ARC.PORCEN_MES_01, DSSC_PORCENTAJE_ARC.PORCEN_MES_02, DSSC_PORCENTAJE_ARC.PORCEN_MES_03, DSSC_PORCENTAJE_ARC.PORCEN_MES_04, DSSC_PORCENTAJE_ARC.PORCEN_MES_05, DSSC_PORCENTAJE_ARC.PORCEN_MES_06, DSSC_PORCENTAJE_ARC.PORCEN_MES_07, DSSC_PORCENTAJE_ARC.PORCEN_MES_08, DSSC_PORCENTAJE_ARC.PORCEN_MES_09, DSSC_PORCENTAJE_ARC.PORCEN_MES_10, DSSC_PORCENTAJE_ARC.PORCEN_MES_11, DSSC_PORCENTAJE_ARC.PORCEN_MES_12,
    DSSC_RESULT_ARC.TRABAJADOR, DSSC_RESULT_ARC.NACIONALIDAD, DSSC_RESULT_ARC.NOMBRE, DSSC_RESULT_ARC.DESCSEDE, DSSC_RESULT_ARC.PERDESDE, DSSC_RESULT_ARC.PERHASTA, DSSC_RESULT_ARC.REM_MES01, DSSC_RESULT_ARC.REM_MES02, DSSC_RESULT_ARC.REM_MES03, DSSC_RESULT_ARC.REM_MES04, DSSC_RESULT_ARC.REM_MES05, DSSC_RESULT_ARC.REM_MES06, DSSC_RESULT_ARC.REM_MES07, DSSC_RESULT_ARC.REM_MES08, DSSC_RESULT_ARC.REM_MES09, DSSC_RESULT_ARC.REM_MES10, DSSC_RESULT_ARC.REM_MES11, DSSC_RESULT_ARC.REM_MES12, DSSC_RESULT_ARC.IMP_RET01, DSSC_RESULT_ARC.IMP_RET02, DSSC_RESULT_ARC.IMP_RET03, DSSC_RESULT_ARC.IMP_RET04, DSSC_RESULT_ARC.IMP_RET05, DSSC_RESULT_ARC.IMP_RET06, DSSC_RESULT_ARC.IMP_RET07, DSSC_RESULT_ARC.IMP_RET08, DSSC_RESULT_ARC.IMP_RET09, DSSC_RESULT_ARC.IMP_RET10, DSSC_RESULT_ARC.IMP_RET11, DSSC_RESULT_ARC.IMP_RET12, DSSC_RESULT_ARC.VAR_CAR_12, DSSC_RESULT_ARC.CENTRO_COSTO
    
ORDER BY
    DSSC_RESULT_ARC.VAR_CAR_12 ASC,
    DSSC_RESULT_ARC.CENTRO_COSTO ASC,
    DSSC_RESULT_ARC.TRABAJADOR ASC,
    DSSC_RESULT_ARC.NOMBRE ASC";

            $stid = oci_parse($conn, $sql);
            oci_execute($stid);
            $cedulas= array();
            while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){  
               $ccosto= substr($row['CENTRO_COSTO'], 0, 5);
               $trabjador=trim($row['TRABAJADOR']) ;          
               array_push($cedulas, $ccosto.'_'.$trabjador);
            }

function obtener_estructura_directorios($ruta){
    // Se comprueba que realmente sea la ruta de un directorio

    if (is_dir($ruta)){
        // Abre un gestor de directorios para la ruta indicada
        $gestor = opendir($ruta);
        echo "<ul>";
        $arc= array();
        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {
                
            $ruta_completa = $ruta . "/" . $archivo;

            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
                // Si es un directorio se recorre recursivamente
                if (is_dir($ruta_completa)) {
                    echo "<li>" . $archivo . "/</li>";
                    obtener_estructura_directorios($ruta_completa);
                } else {
                	$pos = strpos($archivo, '-');
                    $tam= (strlen($archivo)-$pos-1)*(-1);
                    $cant=strlen($archivo)-9-4-1;
                    $rest = substr($archivo, $tam, $cant);
                    array_push($arc, $rest);
                    echo "<li>" . $archivo . "*".$rest."</li>";
                    
                }
            }
        }
        
        // Cierra el gestor de directorios
        closedir($gestor);
        echo "</ul>";
        asort($arc);
        $arc2= array();
        foreach ($arc as $key => $val) {
    		array_push($arc2, $val);
		}
        return $arc2;
    } else {
        echo "No es una ruta de directorio valida<br/>";
    }
} 

function getNameOrderedAsc($files, $ci) {

	foreach ($files as $key => $val) {
    		copy("arc2022pdf/arc2022_1-".$val.".pdf", "arc2022xCedula/".$ci[$key].".pdf");
	}
    
   // copy("arc2022pdf/arc2022_1-1.pdf", "arc2022xCedula/prueba.pdf");
  }           
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>leer pdf</title>
</head>
<body>
<?php
$arc1=obtener_estructura_directorios('arc2022pdf');
getNameOrderedAsc($arc1, $cedulas);
print_r($arc1);
print_r($cedulas);
?>
</body>
</html>