<?php
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
$ruta = 'archivos_asistencias/'; 

//Decalaramos una variable con la ruta en donde almacenaremos los archivos
$mensage = '';//Declaramos una variable mensaje quue almacenara el resultado de las operaciones.
foreach ($_FILES as $key) //Iteramos el arreglo de archivos
{
	if($key['error'] == UPLOAD_ERR_OK )//Si el archivo se paso correctamente Ccontinuamos 
	{
		$NombreOriginal = $key['name'];//Obtenemos el nombre original del archivo
		$temporal = $key['tmp_name']; //Obtenemos la ruta Original del archivo
		$Destino = $ruta.$NombreOriginal;	//Creamos una ruta de destino con la variable ruta y el nombre original del archivo	
		
		move_uploaded_file($temporal, $Destino); //Movemos el archivo temporal a la ruta especificada		
	}
 
	if ($key['error']=='') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
	{
		$mensage = 'Correcto';			
	}
	if ($key['error']!='')//Si existio algún error retornamos un el error por cada archivo.
	{
		$mensage = 'Erroneo'; 
	}	
}
$inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
$inpt .='<thead>
            <tr>
                <th width="10%" class="info">Cedula</th>             
                <th width="10%" class="info">Fecha</th>  
                <th width="10%"class="info">Codigo</th>
                <th width="10%"class="info">Horas</th>
            </tr>
        </thead>
        <tbody>';
if ($mensage=='Correcto')
{	
	error_reporting(E_ALL ^ E_NOTICE);
	require_once '../libs/excel_reader2.php';
	include("../BD/conexion.php");
	$link=Conex_Contancia_pgsql();
	$conn = Conectarse_sitt();
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$data = new Spreadsheet_Excel_Reader($Destino);
	$filas=$data->sheets[0]['numRows'];
	$insertAuditoria="";
	for ($i=1; $i<=$filas; $i++)
	{
			$cedula=trim($data->sheets[0]['cells'][$i][1]);
			$fecha=trim($data->sheets[0]['cells'][$i][2]);
			$codigo=trim($data->sheets[0]['cells'][$i][3]);
			$horas=trim($data->sheets[0]['cells'][$i][4]);

			$finix = date_create($fecha);
			$fini1 = date_format($finix, 'Y-m-d');
			
			if ($codigo==72)
			{	
				$stmt = $conn->prepare("EXEC dbo.colocar_codigo_72_xhoras ?, ?, ?, ?");
				$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);
				$stmt->bindParam(2, $cedula,  PDO::PARAM_INT,10);			
				$stmt->bindParam(3, $horas,  PDO::PARAM_INT,10);
				$stmt->bindParam(4, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10);
			}
			else
			{
				$stmt = $conn->prepare("EXEC dbo.poner_fichada_y_codigo_ausencia ?, ?, ?, ?");
				$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);
				$stmt->bindParam(2, $cedula,  PDO::PARAM_INT,10);			
				$stmt->bindParam(3, $codigo,  PDO::PARAM_INT,10);
				$stmt->bindParam(4, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10);
			}
			$stmt->execute();	
			
			$inpt .='<td>'.$cedula.'</td>';
			$inpt .='<td>'.$fini1.'</td>';
			$inpt .='<td>'.$codigo.'</td>';
			$inpt .='<td>'.$horas.'</td>';
			$inpt .='</tr>';
				
			$insertAuditoria.="INSERT INTO tbl_auditorias (";
            $insertAuditoria.="fecha,";
            $insertAuditoria.="operacion,";
            $insertAuditoria.="login";                    
            $insertAuditoria.=") VALUES (";
            $insertAuditoria.="now(),";
            $insertAuditoria.="'Carga de Archivo: ".$NombreOriginal." | ".$cedula." | ".$fini1."',";
            $insertAuditoria.="'".$_SESSION['user_session_const']."'";
            $insertAuditoria.="); ";
        				
	}
	if ($insertAuditoria!="")
		$result = pg_query($link,$insertAuditoria);
	pg_close($link);		
}
$inpt .=' </tbody></table>';
if ($mensage=='Correcto' && $filas>0)
	echo $inpt;	
else
	echo "El archivo subio ".$mensage;
}		
?>