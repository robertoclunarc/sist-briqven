<?php
session_start();
include("/var/www/html/control_asistencia/pages/conexion.php");
include("/var/www/html/control_asistencia/pages/funciones_var.php");
require("/var/www/html/control_asistencia/pages/enviodecorreos.php");


$gerencias= ['Gerencia de Mantenimiento','Gcia. de Operaciones y Procesos','Gerencia General Industrial','Gerencia de Seguridad y Salud Laboral','Gerencia de Abastecimiento','Gerencia de Talento Humano', 'Gcia de Planif Gestion e ing Ind','Gerencia Proyectos Ing. de Planta','Gcia Relaciones Instit y Desarrollo Endo','Gerencia de Calidad y Log','Gerencia General de Administraci','Gerencia de Administraci','']; 

$responsables= ['matbab','brizan','matblx','matmme','matcom','matson', 'matzag','matpud','matpud','matedr','brilci','brilci',''];	
$coresponsables= ['mantenimientogerencia75@gmail.com','lizgerenciaoperaciones@gmail.com','matgas@briqven.com.ve', '', '', '','', '','','','','',''];

for ($i=0; $i<count($gerencias); $i++){
	echo consultar_fichada($gerencias[$i],$responsables[$i],$coresponsables[$i]);
}


//echo consultar_fichada('Gerencia de Mantenimiento','matbab','mantenimientogerencia75@gmail.com');



function consultar_fichada($gerencia, $responsable,$coresponsables){
	$hoy= date("Y") . "-" . date("m") . "-" . date("d");
	$hoy='2024-07-10';
	$JefePlantaAsistio="";

	$jefePlanta = ['5082499','15279538','16944971','4028944'];
    $correoJefePlanta=['matvin','matped','matavr','matwis'];

	/*$sqlCtrlAcceso="SELECT  a.cedula::int, to_char(fecha_acceso, 'hh24:mi:ss') as hora_acceso, a.nombres, a.cargo, a.departamento, a.jefe_inmediato, a.turno, v.fkunidad, a.direccion
		FROM public.acceso_personal_propio a inner join v_trabajadores v on v.trabajador=a.cedula WHERE a.tipo_personal='PROPIO' AND departamento = '".$gerencia."' ";*/
    $sqlCtrlAcceso="SELECT a.cedula::int, to_char(fecha_acceso, 'hh24:mi:ss') as hora_acceso, a.nombres, a.cargo, a.departamento, a.jefe_inmediato, a.turno, v.fkunidad, b.gerencia, a.direccion
		FROM public.acceso_personal_propio a 
		inner join v_trabajadores v on v.trabajador=a.cedula 
		inner join adam_vw_dotacion_briqven_02_mas b on a.cedula = b.trabajador
		WHERE a.tipo_personal='PROPIO' AND  gerencia like '%".$gerencia."%'";

	$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
	$cbodireccion=isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
	$turno= isset($_GET['turno'])?$_GET['turno']:"2";

	if ($turno==1){
		$complemento = 'and ((ccure_fichadas.Direccion=\'InDirection\' and CAST(fecha as datetime) =\''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' and hora>\'21:15:00\' and hora <= \'23:59:59\') or (ccure_fichadas.Direccion=\'OutDirection\' and CAST(fecha as datetime)=\''.$hoy.'\' and hora > \'02:15:00\' and hora <=\'09:00:00\') )';

		$sqlCtrlAcceso.= " AND ((a.direccion='ENTRADA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".date("Y-m-d",strtotime($hoy."- 1 days"))." 21:15:00' AND '".date("Y-m-d",strtotime($hoy."- 1 days"))." 23:59:59') or (a.direccion='SALIDA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".$hoy." 02:15:00' AND '".$hoy." 09:00:00')) ORDER BY 3,2 desc;";
		$subtitulo= 'que ingresaron entre las 21:15:00 y 23:59:59, y salieron entre las 02:15:00 y 09:00:00 ';
		$fechaEfectivaDelReporte=$hoy;
	}else if ($turno==2){
		$complemento=' and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\' 
			and ((ccure_fichadas.Direccion=\'InDirection\' and  hora > \'05:15:00\'  and hora <=\'13:00:00\') or (ccure_fichadas.Direccion=\'OutDirection\' and  hora > \'09:00:00\'  and hora <=\'16:30:00\') )';
		$sqlCtrlAcceso.= " AND ((a.direccion='ENTRADA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".$hoy." 05:15:00' AND '".$hoy." 13:00:00') or (a.direccion='SALIDA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".$hoy." 09:00:00' AND '".$hoy." 16:30:00')) ORDER BY 3,2 desc;";
		$subtitulo= 'que ingresaron entre las 05:15:00 y 13:00:00, y salieron entre 09:00:00 y  16:30:00';
		$fechaEfectivaDelReporte=$hoy;
	}else if ($turno==3){
		$complemento=' and CAST(fecha as datetime) BETWEEN \''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' AND \''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' 
			and ((ccure_fichadas.Direccion=\'InDirection\' and  hora > \'13:15:00\'  and hora <=\'21:00:00\') or (ccure_fichadas.Direccion=\'OutDirection\' and  hora > \'17:00:00\'  and hora <=\'23:59:59\') )';
		$sqlCtrlAcceso.= " AND ((a.direccion='ENTRADA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".$hoy." 13:15' AND '".$hoy." 21:00:00') or (a.direccion='SALIDA' and to_char(fecha_acceso, 'YYYY-mm-dd hh24:mi:ss') BETWEEN '".$hoy." 17:00:00' AND '".$hoy." 23:59:59')) ORDER BY 3,2 desc;";
		$subtitulo= 'que ingresaron entre las 13:15 y 21:00:00, y salieron entre 17:00:00 y 23:59:59';
		$fechaEfectivaDelReporte=date("Y-m-d",strtotime($hoy."- 1 days"));
	}

	//$sqlCtrlAcceso.= " ORDER BY 5,2;";
	print $sqlCtrlAcceso;

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
		 ".$complemento."
		AND RTRIM(GERENCIA) like '%".$gerencia."%' COLLATE Latin1_General_CI_AI
		order by dbo.ADAM_DATOS_PERSONALES.GERENCIA, GERGRAL desc, Cedula, dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO, ccure_fichadas.Direccion ASC ";

	$b= $qry; ;   

	//echo "<br>".$b;
	$cn=Conectarse_sitt();
	$stmt1 = $cn->query($b);
	$contar = $stmt1->columnCount(); 
	//$contar = $stmt1->rowCount();
	$contador=0;        $PrimeraVez='Si';    $total=0;  $inpt=''; $cantidadVeces = 0;
	$inpt = '<table width="100%" border="1">';
	$inpt = $inpt.'<thead>
				    <tr>
					<td colspan="7" align="center">
					    <h2><b>Reporte de entrada del personal del turno: '.$turno.', del '.date("d-m-Y", strtotime($fechaEfectivaDelReporte)).' de la '.encabezadoTabla($gerencia).', <br>'.$subtitulo.'</b></h2>
					</td>
				    </tr>
					</thead>
						</table>
					';
		        	      
    if($contar == 0){
       $inpt = "No se han encontrado resultados!";
    }else{

        while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
        	$cantidadVeces++;
			/*******************************************************/
            /* verificamos si este trabajdor es el jefe de planta  */
            /*******************************************************/ 
            if (in_array($row['Cedula'], $jefePlanta)) {
				$posicion = array_search($row['Cedula'], $jefePlanta);
				if ($posicion !== false) {
				  $JefePlantaAsistio= $correoJefePlanta[$posicion];
				}
			}

			if ($PrimeraVez=='Si'){
				$inpt .= '<br><br><table width="100%" border="1" style="font-size:12px">';
				$inpt .= encabezado($row['GERENCIA']);		            

				$PrimeraVez          = 'No';		
						
				$trabajador_anterior = $row['Cedula'];
				$nombre_anterior     = $row['Nombre'];
				$gerencia_anterior   = $row['GERENCIA'];
				$cargo_anterior      = $row['Cargo'];
				$direccion_anterior  = $row['direction'];	

				if($row['direction']=='ENTRADA'){
				   $hora_entrada = $row['Hora'];
               $hora_salida  = '';
				}else{
					$hora_entrada = '';
				   $hora_salida  = $row['Hora'];
				}  
			}  //if ($PrimeraVez=='Si'){

         if ($trabajador_anterior!=$row['Cedula'] ){
				 $contador++;
				 if ($gerencia_anterior==$row['GERENCIA']){
					 if ($contador%2==0){
					  	  $inpt .='<tr>';  // echo "El $numero es par";
					 }else{
					    $inpt .='<tr style="background-color:#dadae4;">';  //echo "El $numero es impar";
					 }
					
					 $inpt .='<td>'.$contador.'</td>';                    
					 $inpt .='<td>'.$trabajador_anterior.'</td>';                    
					 $inpt .='<td>'.$nombre_anterior.'</td>';
					 $inpt .='<td>'.$cargo_anterior.'</td>';
					 $inpt .='<td>'.$hoy.'</td>';
	                 $inpt .='<td>'.$hora_entrada.'</td>';
	                 $inpt .='<td>'.$hora_salida.'</td></tr>';

					 $trabajador_anterior = $row['Cedula'];
					 $nombre_anterior     = $row['Nombre'];
					 $gerencia_anterior   = $row['GERENCIA'];
					 $cargo_anterior      = $row['Cargo'];
					 $direccion_anterior  = $row['direction'];	
                     
					 if($row['direction']=='ENTRADA'){
					    $hora_entrada = $row['Hora'];
	                    $hora_salida  = '';
					 }else{
					 	 $hora_entrada = '';
					    $hora_salida  = $row['Hora'];
					 }  
                     $total++;
				 }else{
				 	 if ($contador%2==0){
					  	  $inpt .='<tr>';  // echo "El $numero es par";
					 }else{
					    $inpt .='<tr style="background-color:#dadae4;">';  //echo "El $numero es impar";
					 }
					 
					 $inpt .='<td>'.$contador.'</td>';                    
					 $inpt .='<td>'.$trabajador_anterior.'</td>';                    
					 $inpt .='<td>'.$nombre_anterior.'</td>';
					 $inpt .='<td>'.$cargo_anterior.'</td>';
					 $inpt .='<td>'.$hoy.'</td>';
	                 $inpt .='<td>'.$hora_entrada.'</td>';
	                 $inpt .='<td>'.$hora_salida.'</td></tr>';

					$inpt .='<tr><td colspan="7"><b>Total por '.encabezadoTabla($gerencia_anterior).': '.$contador. '</b></td></tr>';
					$inpt .=' </tbody>
									               		 </table><br><br>';
					$inpt .= '<table width="100%" border="1" style="font-size:12px">';
					$inpt.=encabezado($row['GERENCIA']);

					 $trabajador_anterior = $row['Cedula'];
					 $nombre_anterior     = $row['Nombre'];
					 $gerencia_anterior   = $row['GERENCIA'];
					 $cargo_anterior      = $row['Cargo'];
					 $direccion_anterior  = $row['direction'];	
                     $contador            = 0;
                     
					 if($row['direction']=='ENTRADA'){
					    $hora_entrada = $row['Hora'];
	                    $hora_salida  = '';
					 }else{
					 	 $hora_entrada = '';
					    $hora_salida  = $row['Hora'];
					 } 					  									
                     $total++;
				 }

         }else{
         	if ($gerencia_anterior==$row['GERENCIA']){
               if ($direccion_anterior!=$row['direction']){
               	 $direccion_anterior=$row['direction'];
                   if ($row['direction']=='SALIDA'){
                   	  $hora_salida  = $row['Hora'];
                   }else{
                       $hora_entrada  = $row['Hora']; 
                   }
                   $hora_anterior  = $row['Hora'];
               }else{
                   if ($row['direction']=='SALIDA'){
                   	  $hora_salida  = $row['Hora'];
                   }
               }
         	}
         }
		}//FIN while
      
		if (isset($trabajador_anterior)){
			$contador++;
			$inpt .='<td>'.$contador.'</td>';
			$inpt .='<td>'.$trabajador_anterior.'</td>';
			$inpt .='<td>'.$nombre_anterior.'</td>';
			$inpt .='<td>'.$cargo_anterior.'</td>';
			$inpt .='<td>'.$hoy.'</td>';
			$inpt .='<td>'.$hora_entrada.'</td>';
			$inpt .='<td>'.$hora_salida.'</td>';
			$inpt .='</tr>';
			$inpt .='<tr><td colspan="7"><b>Total por '.encabezadoTabla($gerencia_anterior).': '.$contador. '</b></td></tr>';
			$total++;
		}else{
			$inpt .='<tr><td colspan="7"><b>Total por '.encabezadoTabla($gerencia_anterior).': '.$contador. '</b></td></tr>';
		}

		$inpt .=' </tbody></table><br><br>';
   	        
		///////////////////CONTROL DE ACCESO////////////////////////////
		$inptCtrlAcceso='';
		$trabajador_anterior = '';
		$nombre_anterior     = '';;
		$gerencia_anterior   = '';;
		$cargo_anterior      = '';;
		$direccion_anterior  = '';;
		if ($resCtrlAcceso>0){	
			$inptCtrlAcceso.= '<table width="100%" border="1" style="font-size:12px">';
			$inptCtrlAcceso.='<tr style="background-color:#c8190d;color:#ffffff;"><th colspan="8" align="center">
									    Reporte de entrada del personal Por Control de Acceso '.date("d-m-Y", strtotime($fechaEfectivaDelReporte)).'</th></tr>';
			$inptCtrlAcceso.= '<tr>
						                <th width="5%">#</th>
						                <th width="10%">Cedula</th>
						                <th width="20%">Nombre</th>
						                <th width="25%">Cargo</th>
						                <th width="20%">Departamento</th>
						                <th width="10%">Hora Entrada</th>
						                <th width="10%">Hora Salida</th>
						            </tr>
						            <tbody>';           

			$i=0;  $PrimeraVez='Si';          
			while($row=pg_fetch_array($resCtrlAcceso)){
				if ($PrimeraVez=='Si'){
 					 $PrimeraVez          = 'No';		
					 $trabajador_anterior = $row['cedula'];
					 $nombre_anterior     = $row['nombres'];
					 $gerencia_anterior   = $row['departamento'];
					 $cargo_anterior      = $row['cargo'];
					 $direccion_anterior  = $row['direccion'];	

					 if($row['direccion']=='ENTRADA'){
					    $hora_entrada = $row['hora_acceso'];
	                	$hora_salida  = '';
					 }else{
					 	$hora_entrada = '';
					    $hora_salida  = $row['hora_acceso'];
					 }  
				}  //if ($PrimeraVez=='Si'){

	         if ($trabajador_anterior!=$row['cedula'] ){
					 $i++;
					 if ($i%2==0){
					  	  $inptCtrlAcceso .='<tr>';  // echo "El $numero es par";
					 }else{
					    $inptCtrlAcceso .='<tr style="background-color:#dadae4;">';  //echo "El $numero es impar";
					 }
					
					 $inptCtrlAcceso .='<td>'.$i.'</td>';                    
					 $inptCtrlAcceso .='<td>'.$trabajador_anterior.'</td>';                    
					 $inptCtrlAcceso .='<td>'.$nombre_anterior.'</td>';
					 $inptCtrlAcceso .='<td>'.$cargo_anterior.'</td>';
					 $inptCtrlAcceso .='<td>'.$gerencia_anterior.'</td>';
	                 $inptCtrlAcceso .='<td>'.$hora_entrada.'</td>';
	                 $inptCtrlAcceso .='<td>'.$hora_salida.'</td></tr>';

					 $trabajador_anterior = $row['cedula'];
					 $nombre_anterior     = $row['nombres'];
					 $gerencia_anterior   = $row['gerencia'];
					 $cargo_anterior      = $row['cargo'];
					 $direccion_anterior  = $row['direccion'];	

					 if($row['direccion']=='ENTRADA'){
					    $hora_entrada = $row['hora_acceso'];
	                $hora_salida  = '';
					 }else{
					 	 $hora_entrada = '';
					    $hora_salida  = $row['hora_acceso'];
					 }  

	         }else{
	         	if ($gerencia_anterior==$row['gerencia']){
	               if ($direccion_anterior!=$row['direccion']){
	               	   $direccion_anterior=$row['direccion'];
	                   if ($row['direccion']=='SALIDA'){
	                   	  $hora_salida  = $row['hora_acceso'];
	                   }else{
	                       $hora_entrada  = $row['hora_acceso']; 
	                   }
	                   $hora_anterior  = $row['hora_acceso'];
	               }else{
	                   if ($row['direccion']=='SALIDA'){
	                   	  $hora_salida  = $row['hora_acceso'];
	                   }
	               }
	         	}
	         }

			}


		if (isset($trabajador_anterior) && ($trabajador_anterior)!=''){
			$i++;
			$inptCtrlAcceso .='<td>'.$i.'</td>';
			$inptCtrlAcceso .='<td>'.$trabajador_anterior.'</td>';
			$inptCtrlAcceso .='<td>'.$nombre_anterior.'</td>';
			$inptCtrlAcceso .='<td>'.$cargo_anterior.'</td>';
			$inptCtrlAcceso .='<td>'.$gerencia_anterior.'</td>';
			$inptCtrlAcceso .='<td>'.$hora_entrada.'</td>';
			$inptCtrlAcceso .='<td>'.$hora_salida.'</td>';
			$inptCtrlAcceso .='</tr>';

		}else{
			$inpt .='<tr><td colspan="7"><b>Total por '.encabezadoTabla($gerencia_anterior).': '.$contador. '</b></td></tr>';
		}

			$inptCtrlAcceso.= '</tbody><tr><th align="left" colspan="7">Trabajadores que ingresaron a la empresa por Control de Acceso: '.$contCtrlAcceso.'</th></tr></table><br><br>';
			}// FIN DE CONTRO DE ACCESO
			/////////////////////FIN CONTRO DE ACCESO//////////////////////////////////////
            if ($gerencia==''){ 
			    $totalIngresados=$contCtrlAcceso+$total;		
            }else{
				$totalIngresados=$contCtrlAcceso+$contador;		
            }
			$inpt .=$inptCtrlAcceso.'<table width="100%" border="1" style="font-size:12px">
					                                    <tr>
					                                        <td colspan="5" align="center"><b>Total de trabajadores que ingresaron a la empresa: '.$totalIngresados.'</b></td>
					                                    </tr>
					                          </table>';
		} //FIN if($contar == 0)
	
		pg_free_result($resCtrlAcceso);
		pg_close($cnCtrlAcceso);
		$stmt1 = NULL;

		echo $inpt;
/*
        if ($gerencia!='')
		   $asunto="Reporte de asistencia de la ".encabezadoTabla($gerencia)." del: ".date("d-m-Y", strtotime($hoy)).", Turno:".$turno;
		else
			$asunto="Reporte de asistencia General del: ".date("d-m-Y", strtotime($hoy)).", Turno:".$turno;

		$cuerpo=$inpt;
	    if ($total>0 && $responsable!=''){
	    	//ENVIAR_CORREO($cuerpo,$asunto,"","", "",$responsable."@briqven.com.ve",$coresponsables);
	    	//ENVIAR_CORREO($cuerpo,$asunto,"","", "","matzem@briqven.com.ve","");
	    	ENVIAR_CORREO("Esto es una prueba de envio de correo de las asistencia por gerencia, esto le deberia estar llegando a cada Gerente del Area, solo con el personal a su cargo".$cuerpo,$asunto,"","", "","tecnologiaeinformacionbriqven@gmail.com","informatico12@gmail.com");
	    }

        if ($gerencia==''){
        	ENVIAR_CORREO("Esto es una prueba de envio de correo de las asistencia general, del turno: ".$turno.", es decir, de todo el personal ".$subtitulo.", este correo NO esta validado por la autoridades de la empresa, se encuentra en modo 'Prueba'<br>".$cuerpo,$asunto,"","", "","yen.may08@gmail.com","matmab@briqven.com.ve"); 
        }

        if ($gerencia=='Gerencia de Mantenimiento'){
        	ENVIAR_CORREO($cuerpo,$asunto,"","", "",$responsable."@briqven.com.ve",$coresponsables);
        	//ENVIAR_CORREO($cuerpo,$asunto,"","", "","matzem@briqven.com.ve","informatico12@gmail.com"); 
        }        
	*/
}    

/********************************************************/
/*  FUNCION QUE  MUESTRA EN ENCABEZADO PARA COLOCAR  EL */
/*  NOMBRE DE TABLA POR GERENCIA Y TITULO DEL CORREO    */
/********************************************************/
function encabezadoTabla($gerencia){
	    switch($gerencia) {
		case "Gerencia General de Administraci":
			$gerenciaTitulo='Gerencia General de Administración';
			break;
		case "Gerencia de Administraci":
			$gerenciaTitulo= 'Gerencia de Administración y Finanzas   ';
			break;
		case "Gerencia de Calidad y Log":
			$gerenciaTitulo= 'Gerencia de Calidad y Logística';
			break;
		case "Consultoría Jur":
			$gerenciaTitulo= 'Consultoría Jurídica';
			break;
		default:
		    $gerenciaTitulo	= $gerencia;
		    break;
	}
	return $gerenciaTitulo;
}  

/***********************************************/
/*  FUNCION QUE  MUESTRA EN ENCABEZADO PARA    */
/*             TABLA POR GERENCIA              */
/***********************************************/
function encabezado($GERENCIA){

	$htmlencabezadotabal='<tr style="background-color:#c8190d;color:#ffffff;">
										<th colspan="7" align="center"><b>'.encabezadoTabla($GERENCIA).'</b></th>
							    		</tr>
					                    <tr>
											<th width="5%">#</th>
					                        <th width="10%">Cedula</th>
					                        <th width="20%">Nombre</th>
					                        <th width="35%">Cargo</th>
					                        <th width="10%">Fecha</th>
					                        <th width="10%">Hora Entrada</th>
					                        <th width="10%">Hora Salida</th>
					                    </tr>
					        	<tbody>';
	return $htmlencabezadotabal;
}
?>
