<?php
session_start();
include("/var/www/html/control_asistencia/pages/conexion.php");
include("/var/www/html/control_asistencia/pages/funciones_var.php");
require("/var/www/html/control_asistencia/pages/enviodecorreos.php");
$hoy= date("Y") . "-" . date("m") . "-" . date("d");


$sqlCtrlAcceso="SELECT  a.cedula::int, to_char(fecha_acceso, 'hh:mi:ss') as hora_acceso, a.nombres, a.cargo, a.departamento, a.jefe_inmediato, a.turno, v.fkunidad
	FROM public.acceso_personal_propio a inner join v_trabajadores v on v.trabajador=a.cedula WHERE a.tipo_personal='PROPIO' AND a.direccion='ENTRADA' AND to_char(fecha_acceso, 'YYYY-mm-dd hh:mi:ss') BETWEEN ";

$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
$cbodireccion=isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
$turno= isset($_GET['turno'])?$_GET['turno']:"2";

if ($turno==1){
	$complemento = 'and CAST(fecha as datetime) =\''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' and hora>\'21:15:00\'	or CAST(fecha as datetime) ='.$hoy.' and hora<=\'08:00:00\'';
	$sqlCtrlAcceso.= "'".$hoy." 21:15:00' AND '".$hoy." 08:00:00' ";
	$subtitulo= ', <br>que ingresaron entre las 21:15:00 de '.date("Y-m-d",strtotime($hoy."- 1 days")).' y las 08:00:00 ';
}else if ($turno==2){
	$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'
	and hora > \'05:15:00\' and hora  <=\'16:00:00\'';
	$sqlCtrlAcceso.= "'".$hoy." 05:15:00' AND '".$hoy." 16:00:00' ";
	$subtitulo= ', <br>que ingresaron entre las 05:15:00 y 16:00:00 ';
}else if ($turno==3){
	$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'
	and hora > \'13:15:00\' and hora  <=\'23:59:00\'';
	$sqlCtrlAcceso.= "'".$hoy." 13:15:00' AND '".$hoy." 23:59:00' ";
	$subtitulo= ', <br>que ingresaron entre las 13:15:00 y 23:59:00 ';
}

$sqlCtrlAcceso.= " ORDER BY 5,2;";
//print $contCtrlAcceso;

$cnCtrlAcceso=Conectarse();
$resCtrlAcceso = pg_query($cnCtrlAcceso,$sqlCtrlAcceso) or die("Error en la Consulta SQL: ".$sqlCtrlAcceso);
$contCtrlAcceso = pg_num_rows($resCtrlAcceso);

$qry="SELECT Cedula, ccure_fichadas.Nombre, Cargo, TipodePersonal, Fecha_Inicidencia,CONVERT(datetime,Fecha) AS fecha, Hora,        
CASE   
      WHEN Dia='Monday' THEN 'Lunes'
      WHEN Dia='Tuesday' THEN 'Martes'
      WHEN Dia='Wednesday' THEN 'Miercoles'
      WHEN Dia='Thursday' THEN 'Jueves'
      WHEN Dia='Friday' THEN 'Viernes'
      WHEN Dia='Saturday' THEN 'Sabado'
      ELSE  'Domingo'
END as dia_semana,
CASE   
      WHEN ccure_fichadas.Direccion='InDirection' THEN 'ENTRADA'
      ELSE  'SALIDA'
END as direction,
dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO,
dbo.ADAM_DATOS_PERSONALES.GERENCIA,
CASE   
      WHEN dbo.ADAM_DATOS_PERSONALES.GERGRAL='Presidencia' THEN '1'
      WHEN dbo.ADAM_DATOS_PERSONALES.GERGRAL='Gerencia General Industrial' THEN '2'
      WHEN dbo.ADAM_DATOS_PERSONALES.GERGRAL='Gerencia de Calidad y Logística' THEN '2'
      WHEN dbo.ADAM_DATOS_PERSONALES.GERGRAL='Gcia Seg Patrimonial' THEN '3'
      ELSE  '4'
END as GERGRAL 
FROM ccure_fichadas, dbo.ADAM_DATOS_PERSONALES
WHERE dbo.ccure_fichadas.Cedula=dbo.ADAM_DATOS_PERSONALES.Trabajador 
AND ccure_fichadas.Direccion='InDirection' ".$complemento."
order by GERGRAL,dbo.ADAM_DATOS_PERSONALES.GERENCIA, dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO, Fecha_Inicidencia desc";

$b= $qry; //buscar($qry);     
echo $b;
       $cn=Conectarse_sitt();
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
        $contador=0;        $PrimeraVez='Si';    $total=0;  $inpt='';
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
        }else{
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
	   	if ($PrimeraVez=='Si'){
	        $trabajador_anterior=$row['Cedula'];
	 	    $anterior=$row['GERENCIA'];    $PrimeraVez='No';
        	$inpt = '<table width="100%" border="1">';
            $inpt = $inpt.'<thead>
		    <tr>
			<td colspan="5" align="center">
			    <h2><b>Reporte de entrada del personal del turno: '.$turno.', del '.$row['dia_semana']." ".date("d-m-Y", strtotime($hoy)).'</b></h2>
			</td>
		    </tr>
			</thead>
				</table>
			<br><br>';
        	$inpt .= '<table width="100%" border="1" style="font-size:12px">';
            $inpt .= '
		    <tr style="background-color:#c8190d;color:#ffffff;">
			<th colspan="5" align="center"><b>'.$row['GERENCIA'].'</b></th>
		    </tr>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Cedula</th>
                        <th width="30%">Nombre</th>
                        <th width="45%">Cargo</th>
                        <th width="10%">Hora</th>
                    </tr>';
            $inpt .=' 
        	<tbody>';
 			$contador++;
			if ($contador%2==0){
			    $inpt .='<tr>';  // echo "El $numero es par";
			}else{
                $inpt .='<tr style="background-color:#dadae4;">';  //echo "El $numero es impar";
			}
            $inpt .='<td>'.$contador.'</td>';                    
            $inpt .='<td>'.$row['Cedula'].'</td>';                    
            $inpt .='<td>'.$row['Nombre'].'</td>';
            $inpt .='<td>'.$row['Cargo'].'</td>';
            $inpt .='<td>'.$row['Hora'].'</td>';
            $inpt .='</tr>';                        
            $total=$total+1;
 		}  //if ($PrimeraVez=='Si'){
		if ($trabajador_anterior!=$row['Cedula'] ){
			$trabajador_anterior=$row['Cedula'];
			if ($anterior==$row['GERENCIA']){
        	    $contador++;
			    if ($contador%2==0){
			        $inpt .='<tr>';
	  		    }else{
                    $inpt .='<tr style="background-color:#dadae4;">';
			    }
                //$inpt .='<tr>';
	            $inpt .='<td width="5%">'.$contador.'</td>';                    
        	    $inpt .='<td width="10%">'.$row['Cedula'].'</td>';                    
			    $inpt .='<td width="30%">'.$row['Nombre'].'</td>';
	            $inpt .='<td width="45%">'.$row['Cargo'].'</td>';
        	    $inpt .='<td width="10%">'.$row['Hora'].'</td>';
                $inpt .='</tr>';                        
			    $total=$total+1;
			}else{
				$inpt .='<tr><td colspan="5"><b>Total por '.$anterior.': '.$contador.'</b></td></tr>';
				$inpt .=' </tbody>
		               		 </table><br><br>';
				$inpt .= '<table width="100%" border="1" style="font-size:12px">';
				$inpt.='<thead>
        		            <tr style="background-color:#c8190d;color:#ffffff;">
		                        <td colspan="5" align="center"><b>'.$row['GERENCIA'].'</b></td>
                		    </tr>
		                    <tr>                	        	
                                <th width="5%">#</th>
                        		<th width="10%">Cedula</th>
	        	                <th width="30%">Nombre</th>
        		                <th width="45%">Cargo</th>
	                	        <th width="10%">Hora</th>
		                   </tr>
                		</thead>
		                <tbody>';
				$cedula_anterior = $row['Cedula'];
				$nombre_anterior = $row['Nombre'];
				$cargo_anterior  = $row['Cargo'];
				$hora_anterior   = $row['Hora'];
				$anterior        = $row['GERENCIA'];
				$contador=0;
				$total=$total+1;
				$contador++;
				$inpt .='<tr style="background-color:#dadae4;">';
	        	$inpt .='<td width="5%">'.$contador.'</td>';
        	    $inpt .='<td width="10%">'.$cedula_anterior.'</td>';
	            $inpt .='<td width="30%">'.$nombre_anterior.'</td>';
		        $inpt .='<td width="45%">'.$cargo_anterior.'</td>';
        		$inpt .='<td width="10%">'.$hora_anterior.'</td>';
                $inpt .='</tr>';
				$contador=1;	
			} //if ($anterior==$row['GERENCIA'])
	      }
	}//FIN while

			if ($contador==0 && isset($cedula_anterior)){
	            $inpt .='<td width="5%">1</td>';
        	    $inpt .='<td width="10%">'.$cedula_anterior.'</td>';
                $inpt .='<td width="30%">'.$nombre_anterior.'</td>';
	            $inpt .='<td width="45%">'.$cargo_anterior.'</td>';
        	    $inpt .='<td width="10%">'.$hora_anterior.'</td>';
                $inpt .='</tr>';
			    $inpt .='<tr><td colspan="5"><b>Total por '.$anterior.': 1</b></td></tr>';
			    $total=$total+1;
			}else{
				$inpt .='<tr><td colspan="5"><b>Total por '.$anterior.': '.$contador.'</b></td></tr>';
			}
//	}//FIN while
        	$inpt .=' </tbody></table><br><br>';

///////////////////CONTROL DE ACCESO////////////////////////////
$inptCtrlAcceso='';
if ($resCtrlAcceso>0){	
	$inptCtrlAcceso.= '<table width="100%" border="1" style="font-size:12px">';
	$inptCtrlAcceso.='<tr style="background-color:#c8190d;color:#ffffff;"><th colspan="6" align="center">
			    Reporte de entrada del personal Por Control de Acceso '.date("d-m-Y", strtotime($hoy)).'</th></tr>';
	$inptCtrlAcceso.= '<tr>
                <th width="5%">#</th>
                <th width="10%">Cedula</th>
                <th width="22%">Nombre</th>
                <th width="30%">Cargo</th>
                <th width="23%">Departamento</th>
                <th width="10%">Hora</th>
            </tr>
            <tbody>';           

	$i=1;            
	while($row=pg_fetch_array($resCtrlAcceso)){
	    if($i%2==0)           
			$inptCtrlAcceso.= '<tr>';
		else
			$inptCtrlAcceso .='<tr style="background-color:#dadae4;">';
		$inptCtrlAcceso.= '<td>'.$i.'</td>';
		$inptCtrlAcceso.= '<td>'.$row['cedula'].'</td>';
		$inptCtrlAcceso.= '<td>'.$row['nombres'].'</td>';
		$inptCtrlAcceso.= '<td>'.$row['cargo'].'</td>';
		$inptCtrlAcceso.= '<td>'.$row['departamento'].'</td>';
		$inptCtrlAcceso.= '<td>'.$row['hora_acceso'].'</td>';
		$inptCtrlAcceso.= '</tr>';
		$i++;
	}

	$inptCtrlAcceso.= '</tbody><tr><th align="left" colspan="6">Trabajadores que ingresaron a la empresa por Control de Acceso: '.$contCtrlAcceso.'</th></tr></table><br><br>';
}// FIN DE CONTRO DE ACCESO
/////////////////////FIN CONTRO DE ACCESO//////////////////////////////////////
	$total=$total + $contCtrlAcceso;		
			$inpt .=$inptCtrlAcceso.'<table width="100%" border="1" style="font-size:12px">
                                    <tr>
                                        <td colspan="5" align="center"><b>Total de trabajadores que ingresaron  a la empresa: '.$total.'</b></td>
                                    </tr>
                          </table>';
} //FIN if($contar == 0)
	
pg_free_result($resCtrlAcceso);
pg_close($cnCtrlAcceso);
$stmt1 = NULL;

echo $inpt;

$asunto="Reporte diario de asistencia del: ".date("d-m-Y", strtotime($hoy)).", Turno:".$turno;
$cuerpo=$inpt;

$resp1=ENVIAR_CORREO($cuerpo,$asunto,"","","tecnologiaeinformacionbriqven@gmail.com","informatico12@gmail.com");
$resp2=ENVIAR_CORREO($cuerpo,$asunto,"","","matfih@briqven.com.ve","");
$resp3=ENVIAR_CORREO($cuerpo,$asunto,"","","brilci@briqven.com.ve","yen.may08@gmail.com");
//$resp3=ENVIAR_CORREO($cuerpo.'<br>script: 10.50.188.48/control_asistencia/pages/cron/reporte_diario_por_turno_ccure.php',$asunto,"","","matlux@briqven.com.ve","");

//if ($turno==4){
//	$resp4=ENVIAR_CORREO($cuerpo,$asunto,"","","matmma@briqven.com.ve","matsos@briqven.com.ve");
//}

//if ($turno==2 || $turno==4){
//    $resp2=ENVIAR_CORREO($cuerpo,$asunto,"","","carolinabrito84@gmail.com","matmab@briqven.com.ve");
//   $dpf=construir_pdf($cuerpo);
 //  $resp5=ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.',$asunto,$dpf,"","","tecnologiaeinformacionbriqven@gmail.com");
//   $resp5=ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.',$asunto,$dpf,"","informatico12@gmail.com","");
 //  unlink($dpf);
//}

//print_r($row);
//}         
?>
