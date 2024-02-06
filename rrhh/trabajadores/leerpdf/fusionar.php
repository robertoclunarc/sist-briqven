<?php

	require_once('fpdf.php');
	require_once('fpdi.php');
	require('PdfParser.php');
	//include("conexion/conexion2.php");
    
        set_time_limit(300);

	/* OPCIÓN DE LEER EL FICHER EN FORMATO TXT
	function leer_pdf($filenametxt){
	   //abrimos el archivo de texto y obtenemos el identificador
	   $fichero_texto = fopen ($filename, "r");
	   //obtenemos de una sola vez todo el contenido del fichero
	   //OJO! Debido a filesize(), sólo funcionará con archivos de texto
	   $pdf = fread($fichero_texto, filesize($nombre_fichero));
	   return $pdf;
	}*/

	function leer_pdf($filename){
		$parser = new PdfParser();
		$pdf  = $parser->parseFile($filename);	
		return $pdf;
	}

	function leer_dni($pdf){
		$lastPos = strpos($pdf, "NIF: ",0);
		$nif=substr($pdf, $lastPos+5,10);
                $nif=trim($nif);
		return $nif;
	}

	function renombrar_fichero($new_filename,$dni){
		$nuevo = substr($new_filename, 0, strlen($new_filename)-5).$dni.".pdf";
		rename($new_filename, $nuevo);
		return $nuevo; 
	}

	 function leer_nombre($pdf,$inicio,$fin){
                $r = explode($inicio, $pdf);	   
                if (isset($r[1])){
                    $r = explode($fin, $r[1]);
                    return $r[0];
                }
                return '';
	}

        $filename="NOMINASENERO.pdf";
	//SUBIMOS EL ARCHIVO QUE QUEREMOS ENVIAR POR EMAIL
	/*$filename = $_FILES['archivo1']['name'];
	$ruta = $_FILES['archivo1']['tmp_name'];
	$destino = "envios/".$filename;


	if(move_uploaded_file($ruta, $destino)){

	    echo '<script language="JavaScript"> 
	                alert("archvio subido correctamente"); 
	                </script>';
	}

	else{

	    echo '<script language="JavaScript"> 
	                alert("ATENCIÓN. No se ha adjuntado ningún archivo. Elije archivo a enviar"); 
	                </script>';
	    echo "<script language='javascript'>window.location='entrada.php'</script>";
	}*/
	
	//$tabla=$_POST['tabla'];

	///SE CREA EL DIRECTORIO SPLIT, DÓNDE SE GUARDARÁN LOS FICHEROS O PÁGINAS PDF QUE SE SACARÁN DEL ORIGINAL
	$end_directory = './split/';
	$new_path = preg_replace('/[\/]+/', '/', $end_directory.'/'.substr($filename, 0, strrpos($filename, '/')));
	
	if (!is_dir($new_path)){
		// Will make directories under end directory that don't exist
		// Provided that end directory exists and has the right permissions
		mkdir($new_path, 0777, true);
	}

	$pdf = new FPDI();
	$pagecount = $pdf->setSourceFile($filename); // How many pages?

	// Split each page into a new PDF

	for ($i = 1; $i <= $pagecount; $i++) {
		///dividmos pdf en 1 hoja
		$new_pdf = new FPDI();
		$new_pdf->AddPage();
		$new_pdf->setSourceFile($filename);
		$new_pdf->useTemplate($new_pdf->importPage($i));
		
		try {
			$new_filename = $end_directory.str_replace('.pdf', '', $filename).'_'.$i.".pdf";
			$new_pdf->Output($new_filename, "F");
			//echo "Page ".$i." split into ".$new_filename."<br />\n";
			
			//Leemos la hoja
                       /* if ($i==36){
                            echo "hola";
                        }*/
			$pdf = leer_pdf($new_filename);
			$dni = leer_dni($pdf,$i);
			$nombre = leer_nombre($pdf,'Treballador/a','NIF');
			$nuevo = renombrar_fichero($new_filename,$dni);

			$sql="INSERT INTO nominas2 (nif) VALUES('$dni')";
			$res=mysql_query($sql);

			/*$sql2="SELECT * FROM nominas2";
			$res2=mysql_query($sql2);

			$fila=mysql_fetch_array($res2);
			$dni2=$fila['nif'];*/

			echo "nombre: ".$nombre." DNI: ".$dni." Archivo:".$nuevo."<br />\n";
			
			//$correo = buscar_correo($dni);
			//$sql2="SELECT nominas.nif, nominas.email, nominas2.nif FROM nominas INNER JOIN nominas2 ON nominas.nif=nominas2.nif
			//WHERE nominas.nif=nominas2.nif";

			//enviar_correo($correo,$dni, $nombre,$nuevo);

		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

	}
	
	//echo "<script language='javascript'>window.location='errores_envio.php'</script>";
	
// Create and check permissions on end directory!

?>