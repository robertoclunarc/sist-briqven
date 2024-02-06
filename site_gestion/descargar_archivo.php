<?php

$archivos=array("brv_desp_real_plan.txt","brv_inv_real_plan.txt","brv_prd_real_plan.txt");
$longitud = count($archivos);
//Recorro todos los elementos
for($i=0; $i<$longitud; $i++)
{
//echo "<br>".$archivos[$i];
	$fileName = basename($archivos[$i]);
//echo "<br>".$fileName;
	$filePath = 'archivos_txt/'.$fileName;
//echo "<br>".$filePath;
	if(!empty($fileName) && file_exists($filePath)){
	    // Define headers
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$fileName");
	    header("Content-Type: application/txt");
	    header("Content-Transfer-Encoding: binary");
    
	    // Read the file
	    readfile($filePath);
	    exit;
	}else{
	    echo 'The file does not exist.';
	}

}

?>
