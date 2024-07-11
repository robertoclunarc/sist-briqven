<?php
session_start();
include("conexion.php");
include("funciones_var.php");
require("enviodecorreos.php");
$hoy= date("Y") . "-" . date("m") . "-" . date("d");

$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
$cbodireccion=isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
$turno= $_GET['turno'];
if ($turno==1){
	$complemento = 'and CAST(fecha as datetime) =\''.date("Y-m-d",strtotime($hoy."- 1 days")).'\' and hora>\'22:00:00\'
	or CAST(fecha as datetime) ='.$hoy.' and hora<=\'00:00:00\'';
}else if ($turno==2){
	$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'
	and hora > \'06:00:00\' and hora  <=\'08:00:00\'';
}else if ($turno==3){
	$complemento='and CAST(fecha as datetime) BETWEEN \''.$hoy.'\' AND \''.$hoy.'\'
	and hora > \'14:00:00\' and hora  <=\'16:00:00\'';
}

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
dbo.ADAM_DATOS_PERSONALES.GERENCIA 
FROM ccure_fichadas, dbo.ADAM_DATOS_PERSONALES
WHERE dbo.ccure_fichadas.Cedula=dbo.ADAM_DATOS_PERSONALES.Trabajador 
AND ccure_fichadas.Direccion='InDirection' ".$complemento."
order by dbo.ADAM_DATOS_PERSONALES.GERENCIA, dbo.ADAM_DATOS_PERSONALES.DESC_CCOSTO, Fecha_Inicidencia desc";

$b= $qry; //buscar($qry);     
//echo $b;
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
        	      $inpt = '<table width="100%" border="1" class="table table-striped table-bordered table-hover" id="dataTables-example">';
                      $inpt = $inpt.'<thead>
		    <tr>
			<td colspan=9 align="center"><b>Reporte de entrada del personal el dia turno: '.$turno.', el dia '.$row['dia_semana'].", ".date("d-m-Y", strtotime($hoy)).'</b></td>
		    </tr></thead></table>';
        	      $inpt .= '<table width="100%" border="1" class="table table-striped table-bordered table-hover" id="dataTables-example">';
                      $inpt .= '<thead>
		    <tr>
			<td colspan=9 aligth="center">'.eliminar_acentos($row['GERENCIA']).'</td>
		    </tr>
                    <tr>
                        <th></th>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Hora</th>
                    </tr>';
			    $contador++;
                            $inpt .='<tr>';
                            $inpt .='<td width="10">'.$contador.'</td>';                    
                            $inpt .='<td width="20">'.$row['Cedula'].'</td>';                    
                            $inpt .='<td width="100">'.eliminar_acentos($row['Nombre']).'</td>';
                            $inpt .='<td width="100">'.$row['Cargo'].'</td>';
                            $inpt .='<td width="10">'.$row['Hora'].'</td>';
                            $inpt .='</tr>';                        
                            $total=$total+1;

               $inpt .=' </thead>
        	<tbody>';
 		}  //if ($PrimeraVez=='S'){
//		echo $trabajador_anterior."==".$row['Cedula']."<br>";
		if ($trabajador_anterior!=$row['Cedula'] ){
			$trabajador_anterior=$row['Cedula'];
			if ($anterior==$row['GERENCIA']){
        	            $contador++;
                	    $inpt .='<tr>';
	                    $inpt .='<td width="10">'.$contador.'</td>';                    
        	            $inpt .='<td width="20">'.$row['Cedula'].'</td>';                    
			    $inpt .='<td width="100">'.eliminar_acentos($row['Nombre']).'</td>';
	                    $inpt .='<td width="100">'.$row['Cargo'].'</td>';
        	            $inpt .='<td width="10">'.$row['Hora'].'</td>';
                	    $inpt .='</tr>';                        
			    $total=$total+1;
			}else{
				$inpt .='<tr><td colspan=5>Total por '.$anterior.': '.$contador.'</td></tr>';
//				$inpt .=' </tbody>
//		               		 </table><br>';
//				$inpt .= '<table width="100%" border="1" class="table table-striped table-bordered table-hover" id="dataTables-example">';
//				$inpt.='<thead>';
				$inpt.='
        		            <tr>
		                        <td colspan=5>'.$row['GERENCIA'].'</td>
                		    </tr>
		                    <tr>
                	        	<th></th>
                        		<th>Cedula</th>
	        	                <th>Nombre</th>
        		                <th>Cargo</th>
	                	        <th>Hora</th>
		                   </tr>
                		</thead>
		                <tbody>';
				$cedula_anterior = $row['Cedula'];
				$nombre_anterior = $row['Nombre'];
				$cargo_anterior  = $row['Cargo'];
				$hora_anterior   = $row['Hora'];
				$anterior        = $row['GERENCIA'];
				$contador	 = 0;
			        $total		 = $total+1;
			        $contador++;
                	        $inpt .='<tr>';
	        	        $inpt .='<td width="10">'.$contador.'</td>';
        	                $inpt .='<td width="20">'.$cedula_anterior.'</td>';
	                	$inpt .='<td width="100">'.eliminar_acentos($nombre_anterior).'</td>';
		                $inpt .='<td width="100">'.$cargo_anterior.'</td>';
        		        $inpt .='<td width="10">'.$hora_anterior.'</td>';
                		$inpt .='</tr>';
				$contador=1;	
			} //if ($anterior==$row['GERENCIA'])
	      }
	}//FIN while

			if ($contador==0 && isset($cedula_anterior)){
                	    $inpt .='<tr>';
	                    $inpt .='<td width="10">1</td>';
        	            $inpt .='<td width="20">'.$cedula_anterior.'</td>';
                	    $inpt .='<td width="100">'.eliminar_acentos($nombre_anterior).'</td>';
	                    $inpt .='<td width="100">'.$cargo_anterior.'</td>';
        	            $inpt .='<td width="10">'.$hora_anterior.'</td>';
                	    $inpt .='</tr>';
			    $inpt .='<tr><td colspan=5>Total por '.$anterior.': 1</td></tr>';
			    $total=$total+1;
			}
//	}//FIN while
        	        $inpt .=' </tbody>
	                  </table>';
			$inpt .=' <table width="100%" border="1" class="table table-striped table-bordered table-hover" id="dataTables-example"><thead>
                                    <tr>
                                        <td colspan=5 align="center"><b>Total de trabajadores que ingresaron  a la empresa: '.$total.'</b></td>
                                    </tr>
                         </thead> </table>';
} //FIN if($contar == 0)
echo $inpt;
//require("enviodecorreos.php");
$asunto="Reporte diario de asistencia del: ".date("d-m-Y", strtotime($hoy)).", Turno:".$turno;
$cuerpo=$inpt;
///  $resp=ENVIAR_CORREO($cuerpo,$asunto,"","", "","matzem@briqven.com.ve","matmab@briqven.com.ve");
//$resp=ENVIAR_CORREO($cuerpo,$asunto,"","", "","matzem@briqven.com.ve","matvxl@briqven.com.ve");

//print_r($row);
//}         
?>
