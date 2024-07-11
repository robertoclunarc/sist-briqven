<?php
session_start();
include("/var/www/html/control_asistencia/pages/conexion.php");
include("/var/www/html/control_asistencia/pages/funciones_var.php");
require("/var/www/html/control_asistencia/pages/enviodecorreos.php");


$gerencias= ['Gerencia de Mantenimiento','Gcia. de Operaciones y Procesos','Gerencia General Industrial','Gerencia de Seguridad y Salud Laboral','Gerencia de Calidad y Log�stica','Gerencia de Abastecimiento','Gerencia de Talento Humano', 'Gcia de Planif Gestion e ing Ind','Gerencia Proyectos Ing. de Planta','Gcia Relaciones Instit y Desarrollo Endo']; 

$responsables= ['matbab','brizan','matblx','matmme','matcom','matson', 'matzag','matpud','matpud','matedr'];	

$coresponsables= ['mantenimientogerencia75@gmail.com','lizgerenciaoperaciones@gmail.com','matgas@briqven.com.ve', '', '', '','', '','',''];

for ($i=0; $i<=15; $i++){
	echo consultar_fichada($gerencias[$i],$responsables[$i],$coresponsables[$i]);
}

function consultar_fichada($gerencia, $responsable,$coresponsables){
		$hoy= date("Y") . "-" . date("m") . "-" . date("d");
		$hoy='2024-04-25';
		$sqlCtrlAcceso="SELECT  a.cedula::int, to_char(fecha_acceso, 'hh:mi:ss') as hora_acceso, a.nombres, a.cargo, a.departamento, a.jefe_inmediato, a.turno, v.fkunidad
		FROM public.acceso_personal_propio a inner join v_trabajadores v on v.trabajador=a.cedula WHERE a.tipo_personal='PROPIO' AND a.direccion='ENTRADA' AND departamento = '".$gerencia."'AND to_char(fecha_acceso, 'YYYY-mm-dd hh:mi:ss') BETWEEN ";

		$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
		$cbodireccion=isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
		$turno= isset($_GET['turno'])?$_GET['turno']:"2";

		if ($turno==1){
			$complemento = 'and (CAST(fecha as datetime) =\''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' and hora>\'21:15:00\' or CAST(fecha as datetime) ='.$hoy.' and hora<=\'00:00:00\')';
			$sqlCtrlAcceso.= "'".$hoy." 21:15:00' AND '".$hoy." 23:59:59' ";
			$subtitulo= 'que ingresaron entre las 21:15:00 y 23:59:59 ';
		}else if ($turno==2){
			$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'';
			//and hora > \'05:15:00\' and hora  <=\'13:00:00\'';
			$sqlCtrlAcceso.= "'".$hoy." 05:15:00' AND '".$hoy." 13:00:00' ";
			$subtitulo= 'que ingresaron entre las 05:15:00 y 13:00:00 ';
		}else if ($turno==3){
			$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'
			and hora > \'13:15:00\' and hora  <=\'21:00:00\'';
			$sqlCtrlAcceso.= "'".$hoy." 13:15:00' AND '".$hoy." 21:00:00' ";
			$subtitulo= 'que ingresaron entre las 13:15:00 y 21:00:00 ';
		}

		$sqlCtrlAcceso.= " ORDER BY 5,2;";
		//print $sqlCtrlAcceso;

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
		order by GERGRAL desc,dbo.ADAM_DATOS_PERSONALES.GERENCIA, dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO, CEDULA, ccure_fichadas.Direccion ASC ";

		$b= $qry; //buscar($qry);     
		echo "<br>".$b;
	  $cn=Conectarse_sitt();
	  $stmt1 = $cn->query($b);
	  $contar = $stmt1->columnCount(); 
	  //$contar = $stmt1->rowCount();
	  $contador=0;        $PrimeraVez='Si';    $total=0;  $inpt='';
	  $inpt = '<table width="100%" border="1">';
		$inpt = $inpt.'<thead>
				    <tr>
					<td colspan="6" align="center">
					    <h2><b>Reporte de entrada del personal del turno: '.$turno.', del '.date("d-m-Y", strtotime($hoy)).' de la Gerencia: '.$gerencia.', <br>'.$subtitulo.'</b></h2>
					</td>
				    </tr>
					</thead>
						</table>
					';
		        	      
    if($contar == 0){
       $inpt = "No se han encontrado resultados!";
    }else{
	    $direccion_anterior="";       $trabajador_anterior="";
	    while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
	      	//print "<br>".$row['Cedula']."=>".$trabajador_anterior."=>".$row['Hora']."=>".$direccion_anterior."!=".$row['direction'];
	      	//echo '<table border="1"><tr><td>'.$total.'</td><td>'.$row['Cedula'].'</td><td>'.$row['Nombre'].'</td><td>'.$row['Cargo'].'</td><td>'.$row['direction'].'</td><td>'.$row['Hora'].'</td></tr></table>';
	      	if (($row['direction']== 'ENTRADA' and $row['Hora']>='05:15:00' and $row['Hora']<='13:00:00') 
	      	 or ($row['direction']== 'SALIDA' and $row['Hora']>='05:15:00' and $row['Hora']<='16:15:00')){
	      	 	//print "<br>----------".$trabajador_anterior."=".$row['Cedula']."=>".$row['Hora']."=>".$direccion_anterior."!=".$row['direction'];
				if ($PrimeraVez=='Si'){
					  $trabajador_anterior=$row['Cedula'];
					  $direccion_anterior =$row['direction'];
					  $anterior=$row['GERENCIA'];    $PrimeraVez='No';
					  $inpt .= '<br><br><table width="100%" border="1" style="font-size:12px">';
					  $inpt .= '
							    		<tr style="background-color:#c8190d;color:#ffffff;">
										<th colspan="6" align="center"><b>'.$row['GERENCIA'].'</b></th>
							    		</tr>
					                    <tr>
					                        <th width="5%">#</th>
					                        <th width="10%">Cedula</th>
					                        <th width="25%">Nombre</th>
					                        <th width="40%">Cargo</th>
					                        <th width="10%">Hora Entrada</th>
					                        <th width="10%">Hora Salida</th>
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
				    if($row['direction']=='ENTRADA'){
				       $inpt .='<td>0-'.$row['Hora'].'</td>';
				    }else{
				    	$inpt .='<td>0.1-</td><td>'.$row['Hora'].'</td>';
				    }
				                            
				    $total=$total+1;
				}  //if ($PrimeraVez=='Si'){
				//print "<br>".$row['Cedula']."=>".$direccion_anterior."!=".$row['direction'];

				if ($trabajador_anterior==$row['Cedula'] and $direccion_anterior !=$row['direction']){
					//print "<br>--------------".$trabajador_anterior."=".$row['Cedula']."=>".$row['Hora']."=>".$direccion_anterior."!=".$row['direction'];
					if($direccion_anterior=='ENTRADA' and $row['direction']=='SALIDA'){
					   $inpt .='<td>+'.$row['Hora'].'+</td></tr>';
					}else{
					    $inpt .='<td>0</td></tr>'; 
					}
				}

				if ($trabajador_anterior!=$row['Cedula']){

					$trabajador_anterior=$row['Cedula'];
					if ($anterior==$row['GERENCIA']){
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
						    /*if($row['direction']=='ENTRADA'){
						       $inpt .='<td>'.$row['Hora'].'</td>';
						    }*/

							if($direccion_anterior=='SALIDA' and $row['direction']=='SALIDA'){
							   $inpt .='<td>1-</td><td>'.$row['Hora'].'</td></tr>';
							}elseif($direccion_anterior=='SALIDA' and $row['direction']=='ENTRADA'){
							    $inpt .='<td>2-'.$row['Hora'].'</td>';
							}elseif($direccion_anterior=='ENTRADA' and $row['direction']=='SALIDA'){
							    $inpt .='<td>3-</td><td>'.$row['Hora'].'</td></tr>';
							}else
							    $inpt .='<td>4-'.$row['Hora'].'</td>';
					                            
						    $total=$total+1;
							$trabajador_anterior=$row['Cedula'];
							$direccion_anterior =$row['direction'];						    

					}else{


								$inpt .='<tr><td colspan="6"><b>Total por '.$anterior.': '.$contador.'</b></td></tr>';
								$inpt .=' </tbody>
									               		 </table><br><br>';
								$inpt .= '<table width="100%" border="1" style="font-size:12px">';
								$inpt.='<thead>
							        		            <tr style="background-color:#c8190d;color:#ffffff;">
									                        <td colspan="6" align="center"><b>'.$row['GERENCIA'].'</b></td>
							                		    </tr>
									                    <tr>                	        	
							                                <th width="5%">#</th>
							                        		<th width="10%">Cedula</th>
								        	                <th width="25%">Nombre</th>
							        		                <th width="40%">Cargo</th>
								                	        <th width="10%">Hora Entrada</th>
								                	        <th width="10%">Hora Salida</th>
									                   </tr>
							                		</thead>
									                <tbody>';
								$cedula_anterior = $row['Cedula'];
								$nombre_anterior = $row['Nombre'];
								$cargo_anterior  = $row['Cargo'];
								$hora_anterior   = $row['Hora'];
								$anterior        = $row['GERENCIA'];
								$trabajador_anterior=$row['Cedula'];
							    $direccion_anterior =$row['direction'];	
								$contador=0;
								$total=$total+1;
								$contador++;
								$inpt .='<tr style="background-color:#dadae4;">';
								$inpt .='<td width="5%">'.$contador.'</td>';
							  $inpt .='<td width="10%">'.$cedula_anterior.'</td>';
								$inpt .='<td width="30%">'.$nombre_anterior.'</td>';
								$inpt .='<td width="45%">'.$cargo_anterior.'</td>';

							  //$inpt .='<td width="10%">'.$hora_anterior.'</td>';
							  	if($direccion_anterior=='ENTRADA' and $row['direction']=='SALIDA'){
								    $inpt .='<td>5-'.$row['Hora'].'</td></tr>';
								}elseif ($direccion_anterior=='SALIDA' and $row['direction']=='SALIDA'){
									$inpt .='<td></td><td>6-'.$row['Hora'].'</td></tr>';
								}
							 // $inpt .='</tr>';
								$contador=1;	
						} //if ($anterior==$row['GERENCIA'])
				}/*else{
					if($direccion_anterior=='ENTRADA' and $row['direction']=='SALIDA'){
						    $inpt .='<td>7-'.$row['Hora'].'</td></tr>';
					}
				}*/
				//$direccion_anterior=$row['direction'];
	        }/*else{
			  	//if($direccion_anterior=='ENTRADA' and $row['direccion']=='SALIDA'){
						    $inpt .='<td>7-'.$row['Hora'].'</td></tr>';
				//}
	        }*/
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
			//$inpt .='<tr><td colspan="5"><b>Total por '.$gerencia.': '.$contador.'</b></td></tr>';
			//}
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
				   	if ($i%2==0)           
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

		$asunto="Reporte de asistencia por Gerencia del: ".date("d-m-Y", strtotime($hoy)).", Turno:".$turno;
		$cuerpo=$inpt;
/*
    ENVIAR_CORREO($cuerpo,$asunto,"","", "",$responsable."@briqven.com.ve",$coresponsables);
//    ENVIAR_CORREO($cuerpo,$asunto,"","", "","matmye@briqven.com.ve","");
    ENVIAR_CORREO($cuerpo,$asunto,"","", "","tecnologiaeinformacionbriqven@gmail.com","informatico12@gmail.com");


	/*	$dpf=construir_pdf($cuerpo);
		
		// CORREO ENVIADO A
		// LOS RESPONSABLES A SU CUENTA INSTITUCIONAL Y CUENTA ALTERNA DE CADA GERENTE 
		ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.',$asunto,$dpf,"","",$responsable."@briqven.com.ve",$coresponsables);

		// YENNY MAY
		ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.',$asunto,$dpf,"","","matmye@briqven.com.ve","");

		//CORREO ENVIADO A LOS CORREOS GMAIL DE MARIA CAROLINA Y MERVIN ZERPA
		ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.',$asunto,$dpf,"","","tecnologiaeinformacionbriqven@gmail.com","informatico12@gmail.com");
		
		unlink($dpf);
		*/
}      
?>
