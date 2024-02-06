<?php
//require_once('libs/conexion.php');
require_once('carga_de_datos_del_dia.php');
function flush_buffers(){
/*    ob_end_flush();
   // ob_flush();
    flush();
    ob_start();
*/
ob_start();
    flush();
// ob_flush();//
ob_end_flush();

}

echo "\n\n[".date("Y-m-d, H:i:s")."] Iniciando...";
flush_buffers();
// DATOS DE CONEXION
$servidor_ftp = "201.249.174.211";
$ftp_usuario = "user_briqven";
$ftp_clave = "%9RCzaSU";

// ESTABLECEMOS LA CONEXION CON EL SERVIDOR
$conexion_id = ftp_ssl_connect($servidor_ftp,"3016") or die("No se pudo conectar a $servidor_ftp"); 

// SELECCIONAMOS LA RUTA DE ORIGEN DE LOS ARCHIVOS
//$ftp_carpeta_local =  $_SERVER['DOCUMENT_ROOT'] . "/site_gestion_desa/archivos_txt/";
$ftp_carpeta_local =  "/var/www/html/site_gestion/archivos_txt/";

// NOS LOGUEAMOS AL SERVIDOR CON NUSTRAS CREDENCIALES
$resultado_login = ftp_login($conexion_id, $ftp_usuario, $ftp_clave);

//echo "<br>ftp_pwd:".ftp_pwd($conexion_id)."<br>";
//echo "<br>ftp_cdup:".ftp_cdup($conexion_id)."<br>";
//echo "ftp_pwd:".ftp_pwd($conexion_id)."<br>";

// UBICAMOS LA CARPETA DESTINO
$ftp_remote_path= ftp_pwd($conexion_id)."/";

// ARMAMOS EL NOMBRE DEL ARCHIVO 
$mi_nombredearchivo1="brv_prd_real_plan.txt";
$mi_nombredearchivo2="brv_inv_real_plan.txt";
$mi_nombredearchivo3="brv_desp_real_plan.txt";

// LO CANCATENAMOS CON LA RUTA LOCAL
$nombre_archivo1 = $ftp_carpeta_local.$mi_nombredearchivo1;
$nombre_archivo2 = $ftp_carpeta_local.$mi_nombredearchivo2;
$nombre_archivo3 = $ftp_carpeta_local.$mi_nombredearchivo3;
$archivo_destino1 = $ftp_remote_path.$mi_nombredearchivo1;
$archivo_destino2 = $ftp_remote_path.$mi_nombredearchivo2;
$archivo_destino3 = $ftp_remote_path.$mi_nombredearchivo3;
//$archivo_destino = $ftp_carpeta_remota;

// SELECCIONAMOS EL MODO PASIVO
ftp_pasv($conexion_id, true);
$mens="";
//$resultado_login = ftp_login($conexion_id, $ftp_usuario, $ftp_clave);
if ((!$conexion_id) || (!$resultado_login)) {
       echo  "\n[".date("Y-m-d, H:i:s")."] La conexion ha fallado! al conectar con $servidor_ftp para usuario $ftp_usuario. ";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] La conexion ha fallado! al conectar con $servidor_ftp para usuario $ftp_usuario. ";
       exit;
   } else {
       echo "\n[".date("Y-m-d, H:i:s")."] Conectado con $servidor_ftp, para usuario $ftp_usuario.";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Conectado con $servidor_ftp, para usuario $ftp_usuario.";
   }
//   echo "<br>".$conexion_id."<br>".$archivo_destino1."<br>".$nombre_archivo1;
   flush_buffers();
   $upload = ftp_put($conexion_id, $archivo_destino1, $nombre_archivo1, FTP_BINARY);
   if (!$upload) {
       echo "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo1.";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo1.";
   } else {
       echo "\n[".date("Y-m-d, H:i:s")."] Subido el archivo '$mi_nombredearchivo1' al servidor '$servidor_ftp'.";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Subido el archivo $mi_nombredearchivo1 al servidor $servidor_ftp.";
   }
   flush_buffers();
   $upload = ftp_put($conexion_id, $archivo_destino2, $nombre_archivo2, FTP_BINARY);
   if (!$upload) {
       echo "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo2. ";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo2. ";
   } else {
       echo "\n[".date("Y-m-d, H:i:s")."] Subido el archivo '$mi_nombredearchivo2' al servidor '$servidor_ftp'.";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Subido $mi_nombredearchivo2 a $servidor_ftp as $mi_nombredearchivo2.";
   }
   flush_buffers();
   $upload = ftp_put($conexion_id, $archivo_destino3, $nombre_archivo3, FTP_BINARY);
   if (!$upload) {
       echo "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo3.";
       $mens.= "\n[".date("Y-m-d, H:i:s")."] Ha ocurrido un error al subir el archivo: $mi_nombredearchivo3.";
   } else {
       echo "\n[".date("Y-m-d, H:i:s")."] Subido el archivo '$mi_nombredearchivo3' al servidor '$servidor_ftp'.";
       $mens.=  "\n[".date("Y-m-d, H:i:s")."] Subido el archivo $mi_nombredearchivo3 al servidor $servidor_ftp.";
   }
   flush_buffers();

ftp_close($conexion_id);

echo "\n[".date("Y-m-d, H:i:s")."]...Culminado";
//flush_buffers();

//************ GUARDAMOS EN LA DB ******************************//
$log=fopen("/var/www/html/site_gestion/log/envio_data_sidor.txt","a") or die ("\nError al crear el archivo envio_data_sidor.txt");
fwrite($log,PHP_EOL.$mens);
fclose($log);

//************ GUARDAMOS EN LA DB ******************************//
$cn=  Conectarse();

$query="insert into log_envios (fecha,nota) values ('".date("Y-m-d, H:i:s")."','".$mens."')";
//echo "<br>".$query;
$resultado = pg_query($cn, $query) or die("\n[Error en la Consulta SQL:" . $query);
$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
if (!$reg) {
    echo "<br>\nSe Guard&oacute; la informaci&oacute;n satisfactoriamente en la tabla log";
    //die("Ocurrió un error.\n ");
		
}else{
echo "<br>\nError. No se pudo  guardar la informacion en la tabla log";


}
?>

