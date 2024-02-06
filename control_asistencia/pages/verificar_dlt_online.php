<script  language="javascript">

//(*--------------------------------------------*)

function check_uncheckAll(cb) {
	if($(cb).is(":checked"))
    {
        checkAll();
    }else{
        uncheckAll();
    }
    
  }


//(*--------------------------------------------*)
function checkAll() {
    document.querySelectorAll('#formulario input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = true;
    });

}

//(*--------------------------------------------*)

function uncheckAll() {
    document.querySelectorAll('#formulario input[type=checkbox]').forEach(function(checkElement) {
    	console.log(checkElement);

        checkElement.checked = false;
    });
}


</script>

<?php
if (isset($_SESSION['user_session_const'])){
	echo "<body>
	<script type='text/javascript'>
	window.location='../index.php';
	</script>
	</body>";
}	
include("../BD/conexion.php");
require_once('funciones_var.php'); 
//print "Fecha: ".date("Y-m-d H:i:s");
session_start();
	$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	$turno      = isset($_POST["cboturno"])?$_POST["cboturno"]:"NULL";
	$cbostatus  = isset($_POST["cbostatus"])?$_POST["cbostatus"]:"NULL";

	if ($_SESSION['nivel_const']==2 || $_SESSION['nivel_const']==1){
		$qry="select r.*, a.nombre, f.Observaciones from sw_hoja_de_tiempo_real r inner join adam_vw_dotacion_briqven_02_mas a on r.cedula=cast(a.trabajador as integer) left join SW_OBSERVACIONES_STDLT f on r.cedula=f.cedula and r.fecha=f.fecha  where r.cedula=cast(a.trabajador as integer) and cast(horas_dlt as float)>0 ";
                if ($trabajador!='0' and $trabajador!='NULL')
                        if ($turno!='')
                                $qry.=" and r.cedula='".$trabajador."'";
	}elseif ($_SESSION['nivel_const']==3){
		$qry="select r.*, a.nombre, f.Observaciones from sw_hoja_de_tiempo_real r inner join adam_vw_dotacion_briqven_02_mas a on r.cedula=cast(a.trabajador as integer) left join SW_OBSERVACIONES_STDLT f on r.cedula=f.cedula and r.fecha=f.fecha  where r.cedula=cast(a.trabajador as integer) and cast(horas_dlt as float)>0 ";
		if ($trabajador!='0' and $trabajador!='NULL'){
            		if ($turno!=''){
                		$qry.=" and r.cedula='".$trabajador."'";
			}
		}else{
			$qry.=" and r.cedula in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(r.cedula) in (".llenar_lista_trabajadores_del_supervisor().")) and horas_dlt>0";
		}
	}
//print $cbostatus;
	if ($cbostatus==1)
        $qry.="  and validado_stdlt !=0 ";
    elseif ($cbostatus==2)
        $qry.=" and ((validado_stdlt =0 and rechazado_stdlt =0) or (validado_stdlt =0 and autorizado1 is null))"; //$qry.="  and validado_stdlt =0 and rechazado_stdlt =0 ";
    elseif ($cbostatus==3)
        $qry.="  and rechazado_stdlt !=0 ";
    $qry.="   and r.fecha between '".$finicio."' and '".$ffin."'";
	$qry.=" ORDER BY r.cedula, r.fecha";

//print $qry;

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
	                <th width="3%" class="info"></th>             
	                <!--<th width="5%" class="info" style="text-align: center"><h6><input type="checkbox" name="marcar_desmarcar" id="marcar_desmarcar" value="" onclick="check_uncheckAll(this)"></input></h6></th>  -->             
	                <th width="5%" class="info"><h6>C&eacute;dula</h6></th>             
	                <th width="15%" class="info"><h6>Nombre</h6></th>  
	                <th width="8%" class="info"><h6>Fecha</h6></th>
	                <th width="5%" class="info"><h6>Entrada Real1</h6></th>
	                <th width="5%" class="info"><h6>Salida Real1</h6></th>
	                <th width="5%" class="info"><h6>Entrada Real2</h6></th>
	                <th width="5%" class="info"><h6>Salida Real2</h6></th>
	                <th width="5%" class="info"><h6>Entrada Esperada1</h6></th>
	                <th width="5%" class="info"><h6>Salida Esperada1</h6></th>
	                <th width="5%" class="info"><h6>Inicio DLT1</h6></th>
	                <th width="5%" class="info"><h6>Fin DLT1</h6></th>
	                <th width="5%" class="info"><h6>Horas DLT</h6></th>
	                <th width="22%" class="info"><h6>Motivo</h6></th>        
	                <th width="5%" class="info"><h6>Status</h6></th>  
	                <th width="5%" class="info"><h6>Responsable<br>de la Carga</h6></th>    
	            </tr>
	        </thead>
	        <tbody>'; 
	              $contar=0;
	             //while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
		      while ($row=ejecutar_fetch_array($result)){
	                    //$horas_te=RestarHoras($row['inicio_st1'],$row['fin_st1']);
		      	        if ($row["autorizado1"]!=''){
		      	        	$disable  = 'disabled';
		      	        	$check    = 'checked';
		      	        }else{
		      	            $disable='';
		      	            $check   = '';
		      	        }    

						$responsbale_carga = ''; 
                        if ($row['ced_dlt'] != NULL)
                            $responsbale_carga = nombre_trabajadores($row['ced_dlt']);

		      	        $horas=$row['horas_dlt'];
		      	        $separador='-';
		      	        $fecha = formato_fecha(substr($row['fecha'], 0,10),$separador);
		      	        $param = $row["cedula"].",'".$row['fecha']."', 'V'";
	                    $contar++;
	                    $inpt .='<tr>';
	                    $inpt .='<td width="3%" style="text-align: right"><h6>'.$contar.' </h6></td>'; 
	                    /*if ($row["autorizado1"]=='')
							$inpt .='<td width="5%" style="text-align: center"><input type="checkbox" name="autorizado[]" id="autorizado[]" '.$check.' value="'.$row['cedula']."*".$row['nombre']."*".$row['fecha']."*".$row['entrada_real1']."*".$row['entrada_real2']."*".$row['salida_real1']."*".$row['salida_real2']."*".$row['entrada_esperada1']."*".$row['salida_esperada1']."*".$row['inicio_dlt1']."*".$row['fin_dlt1']."*".$horas."*".$row['observaciones'].'" '.$disable.'></input></td>'; 
						else
							$inpt .='<td width="5%" style="text-align: center"></td>'; 
						*/
			            $inpt .='<td width="5%"><h6><input type="hidden" name="cedula[]" value="'.$row['cedula'].'"></input></input><button type="button" data-toggle="modal" onclick="ver_fichadas('.$param.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></h6></td>';                    
	                    $inpt .='<td width="15%"><h6><input type="hidden" name="NOMBRES[]" value="'.$row['nombre'].'"></input>'.$row['nombre'].'</h6></td>';
	                    $inpt .='<td width="8%"><h6><input type="hidden" name="fecha[]" value="'.$row['fecha'].'"></input>'.$fecha.'</h6></td>';
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="ENTRADA_REAL1[]" value="'.$row['entrada_real1'].'"></input>'.$row['entrada_real1'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="salida_REAL1[]" value="'.$row['salida_real1'].'"></input>'.$row['salida_real1'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="ENTRADA_REAL2[]" value="'.$row['entrada_real2'].'"></input>'.$row['entrada_real2'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="salida_REAL2[]" value="'.$row['salida_real2'].'"></input>'.$row['salida_real2'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="Entrada_Esperada1[]" value="'.$row['entrada_esperada1'].'"></input>'.$row['entrada_esperada1'].'</h6></td>';
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="Salida_Esperada1[]" value="'.$row['salida_esperada1'].'"></input>'.$row['salida_esperada1'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="Inicio_ST1[]" value="'.$row['inicio_dlt1'].'"></input>'.$row['inicio_dlt1'].'</h6></td>'; 
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="Fin_St1[]" value="'.$row['fin_dlt1'].'"></input>'.$row['fin_dlt1'].'</h6></td>'; 
	                    $inpt .='<td width="5%" style="text-align: right"><h6><input type="hidden" name="Horas_DLT[]" value="'.$horas.'"></input>'.$horas.'</h6></td>'; 
	                    $inpt .='<td width="25%"><h6><input type="hidden" name="observaciones[]" value="'.$row['observaciones'].'"></input>'.$row['observaciones'].'</h6></td>'; 
	                    /*if ($row["validado_stdlt"]>0){
							$inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="validado_stdlt[]" value="'.$row['validado_stdlt'].'"></input><i class="fa fa-check" aria-hidden="true"></i>*</h6></td>'; 
			 			}elseif ($row["rechazado_stdlt"]>0){
			 					$inpt .='<td width="5%" align="center"><h6><input type="hidden" name="validado_stdlt[]" value="'.$row['rechazado_stdlt'].'"></input><b>X</b></h6></td>'; 
			 			}else
			 			{
							$inpt .='<td width="5%" align="center"><h6><input type="hidden" name="validado_stdlt[]" value="'.$row['validado_stdlt'].'"></input></h6></td>'; 
						}*/
						if ($row["autorizado1"]!=''){
	                    	$inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input>Aprobado</h6></td>'; 
	                    }else{
	                    	if ($row["validado_stdlt"]!='' && $row["validado_stdlt"]!='NULL' && $row["validado_stdlt"]!='0' ){
	                    	    $inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input>Validado</h6></td>'; 
	                    	}else{
	                    		if($row["rechazado_stdlt"]!=0){
									$inpt .='<td width="5%" style="text-align: center"><h6>Rechazado</h6></td>';
	                    		}else{
									if ($row["autorizado2"]!=''){
										//$inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input><i class="fa fa-check" aria-hidden="true"></i></h6></td>'; 
										$inpt .='<td width="5%" style="text-align: center"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input>Autorizado</h6></td>'; 
						 			}else{
										$inpt .='<td width="5%"><h6><input type="hidden" name="autorizado2[]" value="'.$row['autorizado2'].'"></input></h6>&nbsp;</td>'; 
									}	  
								}                  		
	                    	}	
	                    }	
	                    $inpt .='<td width="5%"><h6><input type="hidden" name="responsable_carga[]" value="'.$responsbale_carga.'"></input>'.$responsbale_carga.'</h6></td>'; 
	                    $inpt .='</tr>';                        

	              } 
        	      if ($contar>0){ 
		              $inpt .=' </tbody>
		               <!--<tfoot>
		                    <tr>
		                       <td colspan="18" align="center"> <INPUT id="cmdGuardar" type="button" value="Verificar DLT"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
				    </tr>
		  	      </tfoot> -->
               	 ';
	            }
				$inpt .='</table>
				<!-- DataTables JavaScript -->
				    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
				    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
				    ';	               
	        }
		echo $inpt;// ."-".$b;
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
