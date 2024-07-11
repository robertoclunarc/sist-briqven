<?php
include("../BD/conexion.php");
require_once('funciones_var.php'); 
session_start();
//if (isset($_SESSION['user_session_const'])){
	$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$turno      = isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";
	$qry_comp   = "";
    	if ($_SESSION['nivel_const']==2 )
       		$query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS";
    	else{
        	$query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS WHERE trim(TRABAJADOR_SUP)='".$_SESSION['cedula_session_const']."'";
        	$query.=" order by nombre";
        	$conn=Conex_oramprd();
        	$stid = oci_parse($conn, $query);
       	 	oci_execute($stid);
        	$option=''; $trabajadores='';
        	while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false){
	        	$trabajadores.=$fila['TRABAJADOR'].", ";      
        	}
        	$lista_trabajadores=substr($trabajadores,0,-2);
        	$qry_comp= " AND CEDULA IN (".$lista_trabajadores.")";
    	}
	//$qry="select * from dbo.FUN_Pocentaje_Tiempo_Real ('".$finicio."', '".$ffin."')";
	$qry="select r.*, a.nombre from registro_diario r, adam_vw_dotacion_briqven_02_mas a where r.trabajador=a.trabajador and sobre_tiempo='S' and validado_te is null";
	if ($finicio!=''){
		if ($ffin!='')
 			$qry.=" and fecha between '".$finicio."' and '".$ffin."'";
		else
			$qry.=" and fecha >'".$finicio."'";
	}else{
		if ($qry!=''){
			$qry.=" and fecha >'".$ffin."'";
		}
	}
	if ($trabajador!='0')
	  if ($turno!='')
	    $qry.=" and r.trabajador='".$trabajador."'";

	$qry.=" ORDER BY r.trabajador, fecha";
//echo $qry;
	buscar($qry);     
	       
	function buscar($b) {
	
		$link=Conex_Contancia_pgsql();
		$result = ejecutar_query($link, $b) or die("Error en la Consulta SQL: ".$b);
		$contar = ejecutar_num_rows($result);
        
	        if($contar == 0){
	              $inpt = "No se han encontrado resultados!";
	              
	        }else{
	              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
	              
	              $inpt = $inpt.'<thead>
	            <tr>
	                <th width="5%" class="info"></th>             
	                <th width="5%" class="info"></th>             
	                <th width="5%" class="info">CEDULA</th>             
	                <th width="15%" class="info">NOMBRE</th>  
	                <th width="5%" class="info">FECHA</th>
	                <th width="5%" class="info">ENTRADA REAL1</th>
	                <th width="5%" class="info">ENTRADA REAL2</th>
	                <th width="5%" class="info">SALIDA REAL1</th>
	                <th width="5%" class="info">SALIDA REAL2</th>
	                <th width="5%" class="info">Entrada Esperada1</th>
	                <th width="5%" class="info">Salida Esperada1</th>
<!--	                <th width="5%" class="info">Entrada Esperada2</th>
	                <th width="5%" class="info">Salida Esperada2</th>
-->
	                <th width="5%" class="info">Inicio ST1</th>
	                <th width="5%" class="info">Fin St1</th>
<!--	                <th width="5%" class="info">Causal ST1</th>          -->
	                <th width="5%" class="info">Horas ST</th>
	                <th width="5%" class="info">Motivo</th>        
	            </tr>
	        </thead>
	        <tbody>';
	              $contar=0;
	              $mayor_a=0;
	              $menor_a=0;
	              $limite=75;
	             //while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
		      while ($row=ejecutar_fetch_array($result)){
	                    $horas_te=RestarHoras($row['entrada_te'],$row['salida_te']);
	                    $contar++;
/*	                    $inpt .='<tr>';
	                    $inpt .='<td>'.$contar.'</td>';                    
	                    $inpt .='<td><input type="checkbox" name="autorizado[]" value="'.$row['cedula'].'"></input></td>';                    
	                    $inpt .='<td><input type="hidden" name="cedula[]" value="'.$row['cedula'].'"></input>'.$row['cedula'].'</td>';                    
	                    $inpt .='<td><input type="hidden" name="NOMBRES[]" value="'.$row['NOMBRES'].'"></input>'.$row['NOMBRES'].'</td>';
	                    $inpt .='<td><input type="hidden" name="fecha[]" value="'.$row['fecha'].'"></input>'.$row['fecha'].'</td>';
	                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL1[]" value="'.$row['ENTRADA_REAL1'].'"></input>'.$row['ENTRADA_REAL1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL2[]" value="'.$row['ENTRADA_REAL2'].'"></input>'.$row['ENTRADA_REAL2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="salida_REAL1[]" value="'.$row['salida_REAL1'].'"></input>'.$row['salida_REAL1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="salida_REAL2[]" value="'.$row['salida_REAL2'].'"></input>'.$row['salida_REAL2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Entrada_Esperada1[]" value="'.$row['Entrada_Esperada1'].'"></input>'.$row['Entrada_Esperada1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Salida_Esperada1[]" value="'.$row['Salida_Esperada1'].'"></input>'.$row['Salida_Esperada1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Entrada_Esperada2[]" value="'.$row['Entrada_Esperada2'].'"></input>'.$row['Entrada_Esperada2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Salida_Esperada2[]" value="'.$row['Salida_Esperada2'].'"></input>'.$row['Salida_Esperada2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Inicio_ST1[]" value="'.$row['Inicio_ST1'].'"></input>'.$row['Inicio_ST1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Fin_St1[]" value="'.$row['Fin_St1'].'"></input>'.$row['Fin_St1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Causal_ST1[]" value="'.$row['Causal_ST1'].'"></input>'.$row['Causal_ST1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Horas_ST[]" value="'.$row['Horas_ST'].'"></input>'.$row['Horas_ST'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Cod_ausenia[]" value="'.$row['Cod_ausencia'].'"></input>'.$row['Cod_ausencia'].'</td>'; 
	                    $inpt .='</tr>';                        
*/
	                    $inpt .='<tr>';
	                    $inpt .='<td>'.$contar.'</td>';                    
	                    $inpt .='<td><input type="checkbox" name="autorizado[]" id="autorizado[]" value="'.$row['trabajador']."*".$row['nombre']."*".$row['fecha']."*".$row['entrada_real1']."*".$row['entrada_real2']."*".$row['salida_real1']."*".$row['salida_real2']."*".$row['entrada_esperada1']."*".$row['salida_esperada1']."*".$row['entrada_te']."*".$row['salida_te']."*".$horas_te."*".$row['observacion_te'].'"></input></td>'; 
			    $inpt .='<td><input type="hidden" name="cedula[]" value="'.$row['trabajador'].'"></input>'.$row['trabajador'].'</td>';                    
	                    $inpt .='<td><input type="hidden" name="NOMBRES[]" value="'.$row['nombre'].'"></input>'.$row['nombre'].'</td>';
	                    $inpt .='<td><input type="hidden" name="fecha[]" value="'.$row['fecha'].'"></input>'.$row['fecha'].'</td>';
	                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL1[]" value="'.$row['entrada_real1'].'"></input>'.$row['entrada_real1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="ENTRADA_REAL2[]" value="'.$row['entrada_real2'].'"></input>'.$row['entrada_real2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="salida_REAL1[]" value="'.$row['salida_real1'].'"></input>'.$row['salida_real1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="salida_REAL2[]" value="'.$row['salida_real2'].'"></input>'.$row['salida_real2'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Entrada_Esperada1[]" value="'.$row['entrada_esperada1'].'"></input>'.$row['entrada_esperada1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Salida_Esperada1[]" value="'.$row['salida_esperada1'].'"></input>'.$row['salida_esperada1'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Inicio_ST1[]" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Fin_St1[]" value="'.$row['salida_te'].'"></input>'.$row['salida_te'].'</td>'; 
	                    $inpt .='<td><input type="hidden" name="Horas_ST[]" value="'.$horas_te.'"></input>'.$horas_te.'</td>'; 
	                    $inpt .='<td><input type="hidden" name="observacion_te[]" value="'.$row['observacion_te'].'"></input>'.$row['observacion_te'].'</td>'; 
	                    $inpt .='</tr>';                        

	              } 
        	      if ($contar>0){ 
		              $inpt .=' </tbody>
		               <tfoot>
		                    <tr>
		                       <td colspan="18" align="center"> <INPUT id="cmdGuardar" type="button" value="Autorizar Horas extras"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
				    </tr>
		  	      </tfoot>
               	 </table>';
	               }
	        }
		echo $inpt; //."-".$b;
		//print_r($row);
	}        
/*}else{
	echo "<body>
	<script type='text/javascript'>
	window.location='../index.php';
	</script>
	</body>";
} 
*/
?>
