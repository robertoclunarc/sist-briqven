<?php
include_once "CORS_Headers.php";
/*
$personas = [
	[
		"nombre"=>"Luis",
		"edad" => 22
	],
	[
		"nombre"=>"Fernando",
		"edad" => 50
	],
];
echo json_encode($personas);
*/
$file=$_GET["file"];
$img = 'rrhh/fotcarmat_new/'.$file;
if (file_exists($img)) {
	readfile($img);
}else{
	$error=[
		"message"=>"Not Found",
		"error" => 404
	];
	echo json_encode($error);
}
?>
