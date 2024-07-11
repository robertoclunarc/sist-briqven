<?php
session_start();
if (isset($_SESSION['user_session_sio'])){
$ruta = 'archivos_txt/'; 

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
            	<th width="10%" class="info">Item</th> 
            	<th width="10%" class="info">Reactor</th>                         
                <th width="10%" class="info">Fecha</th>
                <th width="10%"class="info">Tiempo (col.F)</th> 
                <th width="10%"class="info">Produccion (col.J)(f3)</th>   
                <th width="10%"class="info">Productividad (col.G)(Prog)</th> 
                <th width="10%"class="info">Real</th> 

            </tr>
        </thead>
        <tbody>';
if ($mensage=='Correcto')
{	
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'libs/PHPExcel-1.8/Classes/PHPExcel.php';
	include("libs/conexion.php");
	
	$connSIO = Conectarse_sio();
	$connSIO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$coneSI = Conectarse_SI();
	$coneSI->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$archivo = $Destino;
	$inputFileType = PHPExcel_IOFactory::identify($archivo);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($archivo);
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();

	$filas = 28;
	echo 'Empezando desde la fecha: '.$sheet->getCell("B".$filas)->getValue();
	$fecha = new DateTime($sheet->getCell("B".$filas)->getValue());
	$fecha->modify('last day of this month');
	$fechaFin= $fecha->format('Y-m-d');
	
	$reactor1=1;
	$reactor2=2;
	$id_instalacion1='HBC1 ';
	$id_instalacion2='HBC2 ';
	$capacidad_instalada=0;
	$real=0;

	$fini1='';
	$num=0;

	for ($i=$filas; ($filas <= $highestRow-1 && $fechaFin!=$fini1); $i++){ 
			$num++;

			$fecha=trim($sheet->getCell("B".$i)->getValue());		
			$finix = date_create($fecha);
			$fini1 = date_format($finix, 'Y-m-d');
			$validarFecha=explode('-', $fini1);

			$tiempo=0;
			$tiempo2=trim($sheet->getCell("F".$i)->getValue());

			$plan=0;
			$plan2=trim($sheet->getCell("J".$i)->getValue());

			$productividad=0;
			$productividad2=trim($sheet->getCell("G".$i)->getValue());

			if (count($validarFecha)==3){

				$stmt1 = $connSIO->query("select count(*) as nrores from [dbo].[Tab_Produccion] where [fecha]='".$fini1."'");
				$row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
				$nrores=$row['nrores'];

				if ($nrores>=1){
					$stmt = $connSIO->prepare("delete from [dbo].[Tab_Produccion] where [fecha] = ? and [reactor] in ('1','2')");
					$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);					
					$stmt->execute();
				
				}	
				$stmt = $connSIO->prepare("insert into [dbo].[Tab_Produccion] ([fecha],[reactor],[plan],[real]) values ( ?, ?, ?, ?)");
				$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);
				$stmt->bindParam(2, $reactor1,  PDO::PARAM_STR,1);			
				$stmt->bindParam(3, $plan,  PDO::PARAM_STR,10);
				$stmt->bindParam(4, $real,  PDO::PARAM_STR,10);
				$stmt->execute();
				$stmt = $connSIO->prepare("insert into [dbo].[Tab_Produccion] ([fecha],[reactor],[plan],[real]) values ( ?, ?, ?, ?)");
				//print "<br>".$fini1." * ".$reactor2." * ".$plan2." * ".$real;
				$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);
				$stmt->bindParam(2, $reactor2,  PDO::PARAM_STR,1);			
				$stmt->bindParam(3, $plan2,  PDO::PARAM_STR,10);
				$stmt->bindParam(4, $real,  PDO::PARAM_STR,10);
				$stmt->execute();

				$stmt1 = $coneSI->query("select count(*) as nrores from [dbo].[mat_si_prod_programada] where [fecha_hora_inicio_vigencia]='".$fini1."'");
				$row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
				$nrores=$row['nrores'];
				
				if ($nrores>=1){
					$stmt = $coneSI->prepare("delete from [dbo].[mat_si_prod_programada] where [fecha_hora_inicio_vigencia] = ? and id_instalacion in ('HBC1 ', 'HBC2 ')");
					$stmt->bindParam(1, $fini1, PDO::PARAM_STR,10);					
					$stmt->execute();
				
				}							

				$stme = $coneSI->prepare("insert into [dbo].[mat_si_prod_programada] (id_instalacion, fecha_hora_inicio_vigencia, prod_programada, id_usuario, capacidad_instalada, tiempo_efectivo) values (?, ?, ?, ?, ?, ?)");
				$stme->bindParam(1, $id_instalacion1, PDO::PARAM_STR,5);
				$stme->bindParam(2, $fini1,  PDO::PARAM_STR,10);			
				$stme->bindParam(3, $productividad,  PDO::PARAM_STR,10);
				$stme->bindParam(4, $_SESSION['user_session_sio'],  PDO::PARAM_STR,10);
				$stme->bindParam(5, $capacidad_instalada,  PDO::PARAM_STR,10);
				$stme->bindParam(6, $tiempo,  PDO::PARAM_STR,10);				
				$stme->execute();
				//print '<br>'.$id_instalacion2.' '.$fini1.' '.$productividad2.' '.$_SESSION['user_session_sio'].' '.$capacidad_instalada.' '.$tiempo2;
				$stme = $coneSI->prepare("insert into [dbo].[mat_si_prod_programada] (id_instalacion, fecha_hora_inicio_vigencia, prod_programada, id_usuario, capacidad_instalada, tiempo_efectivo) values (?, ?, ?, ?, ?, ?)");
				$stme->bindParam(1, $id_instalacion2, PDO::PARAM_STR,5);
				$stme->bindParam(2, $fini1,  PDO::PARAM_STR,10);			
				$stme->bindParam(3, $productividad2,  PDO::PARAM_STR,10);
				$stme->bindParam(4, $_SESSION['user_session_sio'],  PDO::PARAM_STR,10);
				$stme->bindParam(5, $capacidad_instalada,  PDO::PARAM_STR,10);
				$stme->bindParam(6, $tiempo2,  PDO::PARAM_STR,10);				
				$stme->execute();
 
		       	$inpt .='<tr>';
		       	$inpt .='<th scope="row">'.$num.'</th>';
		       	$inpt .='<td>'.$reactor2.'</td>';
		        $inpt .='<td>'.$fini1.'</td>';
				$inpt .='<td>'.$tiempo2.'</td>';
				$inpt .='<td>'.$plan2.'</td>';
				$inpt .='<td>'.$productividad2.'</td>';			
				$inpt .='<td>'.$real.'</td>';						
				$inpt .='</tr>';
			}	
	   
	} 				
	
	$stmt = null;
	$stme = null;
	$connSIO = null;
	$coneSI = null;	
			
}
$inpt .=' </tbody></table><br><strong>Total Filas en Excel: </strong>'.$highestRow;
if ($mensage=='Correcto' && $num>0)
	echo $inpt;	
else
	echo "El archivo ".$Destino." subio ".$mensage;
}		
?>