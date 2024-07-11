<?php
session_start();
include("../BD/conexion.php");
include("funciones_var.php");

if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){

	/* MODIFICACION MERVIN ZERPA, PARA SER UTILIZADO EN LA CARGA DE HORAS EXTRAS*/
	$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:$finicio;
  $trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";


  $qry = "select fecha, cedula, nombres, entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2, entrada_real1, entrada_real2, salida_real1, salida_real2, inicio_st1, fin_st1, inicio_dlt1, fin_dlt1, HorasNetaPresencia, Horas_ST, HorasNetSicaSt, CodError from sw_hoja_de_tiempo_real inner join ADAM_DATOS_PERSONALES  on cedula =trabajador where fecha = '".$finicio."' and cedula in (".llenar_lista_trabajadores_del_supervisor().") order by nombres";
  
	//print $qry;

	$query       = "select * from registro_diario where fecha='".$finicio."' and trabajador in (".llenar_lista_trabajadores_del_supervisor().")";
	$fecha       = explode("-",$finicio);
	$anio_nomina = $fecha[0];
	$mes_nomina  = $fecha[1];

	buscar($qry,$query);     
}
else
  echo "Debe Iniciar Sesion"; 
         
function buscar($b,$c) {
//       include("../BD/conexion.php");
        $cn=Conectarse_sitt();
        $link=Conex_Contancia_pgsql();
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
          $inpt = ' <table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example"><tr><td align="center">';
          $inpt .= "No se han encontrado resultados!";
          $inpt .="</td></tr></table>";
       }else{
           $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              
              $inpt = $inpt.'<thead>
            <tr>
                <th width="5%" class="info"><h6></b>#</b></h6></th> 
                <th width="10%" class="info"><h6><b>Cedula</b></h6></th>
                <th width="10%" class="info"><h6><b>Nombres</b></h6></th>
                <th width="10%" class="info"><h6><b>Ent. Esp. 1</b></h6></th>                 
                <th width="10%" class="info"><h6><b>Sal Esp. 1</b></h6></th>
                <th width="10%" class="info"><h6><b>Ent. Esp. 2</b></h6></th>
                <th width="10%" class="info"><h6><b>Sal. Esp. 2</b></h6></th>
                <th width="10%" class="info"><h6><b>Ent. Real 1</b></h6></th>
                <th width="10%" class="info"><h6><b>Sal. Real 1</b></h6></th>
                <th width="10%" class="info"><h6><b>Ent. Real 2</b></h6></th>
                <th width="10%" class="info"><h6><b>Sal. Real 2</b></h6></h6></th>
                <th width="10%" class="info"><h6><b>Hora Permanencia</b></h6></th>
                <th width="10%" class="info"><h6><b>Inicio HE<br>Cargada</b></h6></th>
                <th width="10%" class="info"><h6><b>Fin HE ST<br>Cargada</b></h6></th>
                <th width="10%" class="info"><h6><b>Hora Extras (HE)</b></h6></th>
                <th width="10%" class="info"><h6><b>Cambio de Turno</b></h6></th>
                <th width="10%" class="info"><h6><b>DLT Cargada</b></h6></th>

            </tr>
        </thead>
        <tbody>';                           
            $limite=array('LL:LL','RR:RR','VV:VV','CS:CS','SD:SD','PP:PP','FF:FF');
            $i=1;
            while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){ 
                    $codError=$row['CodError'];
                    if ($codError==99)
                    {
                        $clase="danger";                  
                    }    
                    elseif ($codError==100)
                    { 
                      $clase="success";                
                    }
                    else{
                        $clase="warning";
                    }                   
		                $resultado1=mostrar_esperanza($row['entrada_esperada1']);
		                $resultado2=mostrar_esperanza($row['salida_esperada1']);
                    $resultado3=mostrar_esperanza($row['entrada_esperada2']);
                    $resultado4=mostrar_esperanza($row['salida_esperada2']);
                    $inpt .='<tr>';
                    $inpt .='<td align="center"><h6>'.$i.'</h6></td>';
                    $inpt .='<td><h6><input type="hidden" id="trabajador" name="trabajador" value="'.$row['cedula'].'"></input>'.$row['cedula'].'</h6></td>';
                    $inpt .='<td><h6><input type="hidden" id="trabajador" name="trabajador" value="'.$row['nombres'].'"></input>'.$row['nombres'].'</h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="entrada_esperada1" name="entrada_esperada1" value="'.$row['entrada_esperada1'].'"></input><span class="'.$resultado1[0].'">'.$resultado1[1].'</span></h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="salida_esperada1"  name="salida_esperada1"  value="'.$row['salida_esperada1'].'"></input><span class="'.$resultado2[0].'">'.$resultado2[1].'</span></h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="entrada_esperada2" name="entrada_esperada2" value="'.$row['entrada_esperada2'].'"></input><span class="'.$resultado1[0].'">'.$resultado3[1].'</span></h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="salida_esperada2"  name="salida_esperada2"  value="'.$row['salida_esperada2'].'"></input><span class="'.$resultado1[0].'">'.$resultado4[1].'</span></h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="entrada_real1" name="entrada_real1" value="'.$row['entrada_real1'].'"></input>'.$row['entrada_real1'].'</h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="salida_real1"  name="salida_real1"  value="'.$row['salida_real1'].'"></input>'.$row['salida_real1'].'</td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="entrada_real2" name="entrada_real2" value="'.$row['entrada_real2'].'"></input>'.$row['entrada_real2'].'</h6></td>';
                    $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="salida_real2"  name="salida_real2"  value="'.$row['salida_real2'].'"></input>'.$row['salida_real2'].'</td>';
                    $inpt .='<td class="'.$clase.'" align="center"><h6><input type="hidden" id="hora_st"  name="hora_st"  value="'.$row['HorasNetaPresencia'].'"></input>'.$row['HorasNetaPresencia'].'</h6></td>';
                   

                    
                    $query  = "select * from registro_diario where fecha='".$row['fecha']."' and trabajador in ('".$row['cedula']."')";
                    $result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$c);
                    $numReg = ejecutar_num_rows($result);
                     if($numReg>0){
                        while ($row2=ejecutar_fetch_array($result)){
                          if ($row2['sobre_tiempo']=='S'){
                             if ($row2['autorizado_te']!=''){
                                $aprobado_st ='*';
                                $color = '#cb4335';      
                             }else{
                                $aprobado_st ='';  
                                $color = '#cb4335'; 
                            } 
                             $inpt .='<td  class="'.$clase.'" style="color:'.$color.'"><input type="hidden" id="entrada_st_interno" name="entrada_st_interno" value="'.$row2['entrada_te'].'"></input>'.$row2['entrada_te'].'</td>';
                             $inpt .='<td  class="'.$clase.'" style="color:'.$color.'"><input type="hidden" id="entrada_st_interno" name="entrada_st_interno" value="'.$row2['salida_te'].'"></input>'.$row2['salida_te'].'</td>';
                          }else{
                            $inpt .='<td class="'.$clase.'"></td>';
                            $inpt .='<td class="'.$clase.'"></td>';
                          }


                          $param = $row["cedula"].",'".$row['fecha']."', 'E'";
                          //$inpt .='<td class="'.$clase.'" align="center"><h6><input type="hidden" id="hora_NP"  name="hora_NP"  value="'.$row['HorasNetSicaSt'].'"></input>'.$row['HorasNetSicaSt'].'</td></h6>';
                          $inpt .='<td class="'.$clase.'"><h6><input type="hidden" id="hora_NP"  name="hora_NP"  value="'.$row['HorasNetSicaSt'].'"></input><button type="button" class="btn btn-'.$clase.' btn-xs" data-toggle="modal" onclick="ver_fichadas('.$param.')" data-target="#exampleModalCenter">'.$row['HorasNetSicaSt'].'</button></td>';                          
                          

                          if ($row2['cambio_turno']=='S'){
                            if ($row2['autorizado_ct']!=''){
                                $aprobado_ct ='*';
                                $color = '#cb4335';      
                            }else{
                                $aprobado_ct ='';  
                                $color = '#cb4335'; 
                            }  
                            $inpt .='<td><input type="hidden" id="entrada_st_interno" name="cambio_turno" value="S"></input>*</td>';
                          }else{
                            $inpt .='<td class="'.$clase.'"></td>';
                          }

                          if ($row2['dlt']=='S'){
                            if ($row2['autorizado_dlt']!=''){
                                $aprobado_ct ='*';
                                $color = '#cb4335';      
                            }else{
                                $aprobado_ct ='';  
                                $color = '#cb4335'; 
                            }  
                            $inpt .='<td><input type="hidden" id="entrada_st_interno" name="dlt" value="S"></input>*</td>';
                          }else{
                            $inpt .='<td class="'.$clase.'"></td>';
                          }                            
                        }
                     }else{
                            $inpt .='<td class="'.$clase.'"></td>';
                            $inpt .='<td class="'.$clase.'"></td>';
                            $inpt .='<td class="'.$clase.'"></td>';                            
                            $inpt .='<td class="'.$clase.'"></td>';
                            $inpt .='<td class="'.$clase.'"></td>';
                    }

                    $inpt .='</tr>';                        
                    $inpt .='<tr>';
                    $i++;                   
	          } 
          $inpt .=' </tbody>              
                </table>';
$inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    

    '; 
       // }1
	echo $inpt;

	
	//print_r($row);

	//$query="select * from registro_diario where fecha'".$finicio."' and trabajador=".$trabajador;
	echo $c;
	$inpt4 =''; $inpt5=''; $inpt6='';
	$result = ejecutar_query($link, $c) or die("Error en la Consulta SQL: ".$c);
    	$numReg = ejecutar_num_rows($result);
	if($numReg>0){
		$encabezado_st=0;	
		while ($row=ejecutar_fetch_array($result)){
		   if ($row['sobre_tiempo']=='S'){
		      if ($encabezado_st==0){ 
              $inpt4 ='<b>Horas Extras</b><br>	<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
 	      		  $inpt4 = $inpt4.'<thead>
		        	    <tr>
		                	<th width="10%">Entrada ST</th>                 
	        		        <th width="10%">Salida ST</th>
		        	        <th width="10%" align="center">Aprobado</th>
			            </tr>
			        </thead>
			        <tbody>';
		    	    $inpt4 .='<tr>';
			        $encabezado_st=1;
		      }
          if ($row['autorizado_te']!=''){
              $aprobado_st ='*';
              $color = '#cb4335';      
          }else{
              $aprobado_st ='';  
              $color = '#cb4335'; 
          }    

          $inpt4 .='<td style="color:'.$color.'"><input type="hidden" id="entrada_st_interno" name="entrada_st_interno" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>';
          $inpt4 .='<td style="color:'.$color.'"><input type="hidden" id="salida_st_interno"  name="salida_st_interno"  value="'.$row['salida_te'].'"></input>'.$row['salida_te'].'</td>';
    			//if ($row['autorizado_te']!='')
    		  $inpt4 .='<td>'.$aprobado_st.'</td>';
    			//else
    		  // 		$inpt4 .='<td></td>';	
          //                  $inpt4 .='</tr>';
    		   }
    		   $encabezado_ct=0;
    		   if ($row['cambio_turno']=='S'){
    		      if ($encabezado_ct==0){ 
                  $inpt5 = '<b>Cambio de turno</b><br>  <table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
     	      		  $inpt5 = $inpt5.'<thead>
    		        	    <tr>
    		                	<th width="10%">Turno trabajado</th>                 
    		        	        <th width="10%" align="center">Aprobado</th>
    			            </tr>
    			        </thead>
    			        <tbody>';
    		    	    $inpt5 .='<tr>';
    			        $encabezado_ct=1;
    		      }
          		//	echo $row["turno"];
              if ($row['autorizado_ct']!=''){
                  $aprobado_ct ='*';
                  $color = '#cb4335';      
              }else{
                  $aprobado_ct ='';  
                  $color = '#cb4335'; 
              }   
			        if ($row["turno"]==1) $turno='23:00 a 07:00';
			        if ($row["turno"]==2) $turno='07:00 a 15:00';
			        if ($row["turno"]==3) $turno='15:00 a 23:00';
              $inpt5 .='<td><input type="hidden" id="entrada_st_interno" name="entrada_ct_interno" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>';
              $inpt5 .='<td>'.$aprobado_ct.'</td>';  
              $inpt5 .='</tr>';
      		   }
      		}
   	        $inpt5 .=' </tbody>              
               		</table>';
                $encabezado_cp=0;
		if ($row['cambio_permanencia']=='S'){
                    if ($encabezado_cp==0){ 
                        $inpt6 = '<b>Cambio de Permanencia</b><br> <table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
                        $inpt6 = $inpt6.'<thead>
                                    <tr>
                                        <th width="10%">Entrada ST</th>                 
                                        <th width="10%">Salida ST</th>
                                        <th width="10%" align="center">Aprobado</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        $inpt6 .='<tr>';
                        $encabezado_cp=1;
                    }
                        $inpt6 .='<td style="color:#cb4335"><input type="hidden" id="entrada_st_interno" name="entrada_cp_interno" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>';
                        $inpt6 .='<td style="color:#cb4335"><input type="hidden" id="salida_st_interno"  name="salida_cp_interno"  value="'.$row['salida_te'].'"></input>'.$row['salida_te'].'</td>';
                        if ($row['autorizado_cp']!='')
                                $inpt6 .='<td>*</td>';
                        else
                                $inpt6 .='<td></td>';
                        $inpt6 .='</tr>';
                 }
           }
                   $inpt6 .=' </tbody>              
                                </table>';
		echo $inpt4;	
		echo $inpt5;
		echo $inpt6;
	}
}         


function periodo_abierto2($anio,$mes) {
   $cn=Conex_rrhh_pgsql();
   $sql="select * from periodos_nomina where anio=".$anio." and mes=".$mes;
//print $sql;
   $result = ejecutar_query($cn, $sql) or die("Error en la Consulta SQL: ".$query);
   $numReg = ejecutar_num_rows($result);
   if($numReg>0){
          while ($reg = ejecutar_fetch_array($result)) {
                $status=$reg['abierto'];
		return $status;
//		return false;
         }
   } 
   pg_close($cn);
}


?>
