<?php
session_start();
include("../BD/conexion.php");
include("funciones_var.php");

if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
	/* ORIGINAL ROBERTO LUNAR
	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
	*/
	/* MODIFICACION MERVIN ZERPA, PARA SER UTILIZADO EN LA CARGA DE HORAS EXTRAS*/
	$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:$finicio;
	$trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

	$qry="select fecha, entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2, entrada_real1, entrada_real2, salida_real1, salida_real2,inicio_st1,fin_st1,inicio_dlt1,fin_dlt1 from sw_hoja_de_tiempo_real where fecha between '".$finicio."' and '".$ffin."' and cedula=".$trabajador;
	$qry.=" order by fecha desc";
//	print $qry;

	$query="select * from registro_diario where fecha='".$finicio."' and trabajador='".trim($trabajador)."'";
	
	$fecha=explode("-",$finicio);
	$anio_nomina=$fecha[0];
	$mes_nomina= $fecha[1];
/*	$abierto=periodo_abierto2($anio_nomina,$mes_nomina);
	//print "Periodo Cerrado, no acepta modificaciones";
	if ($abierto=="f"){
    		echo "Periodo Cerrado, no acepta modificaciones";
    		exit;
	}
	//print $abierto;
*/
	buscar($qry,$query);     
}
else
  echo "Debe Iniciar Sesion"; 
         
function buscar($b,$c) {
//       include("../BD/conexion.php");
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '	<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt2 = '<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt3 = '<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt = $inpt.'<thead>
            <tr>
<!--                <th width="10%">Fecha</th> -->
                <th width="10%">Entreada Esperada 1</th>                 
                <th width="10%">Salida Esperada 1</th>
                <th width="10%">Entrada Esperada 2</th>
                <th width="10%">Salida Esperada 2</th>
            </tr>
        </thead>
        <tbody>';
              $inpt2 = $inpt2.'<thead>
                <th width="10%">Entrada Real 1</th>
                <th width="10%">Salida Real 1</th>
                <th width="10%">Entrada Real 2</th>
                <th width="10%">Salida Real 2</th>
            </tr>
        </thead>
        <tbody>';                            
              $inpt3 = $inpt3.'<thead>
                <th width="10%">Entrada ST</th>
                <th width="10%">Salida ST</th>
                <th width="10%">Entrada DLT</th>
                <th width="10%">Salida DLT</th>
            </tr>
        </thead>
        <tbody>';                            
              $limite=array('LL:LL','RR:RR','VV:VV','CS:CS','SD:SD','PP:PP','FF:FF');
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){                    
                    $rest = substr($row['fecha'], 0,10);
                    $porc=$row['entrada_esperada1'];
                    if (in_array($porc, $limite))
                        $clase="label label-danger";                        
                    else
                      $clase="label label-info";                     
                    $inpt .='<tr>';
                    $inpt .='<td><input type="hidden" id="entrada_esperada1" name="entrada_esperada1" value="'.$row['entrada_esperada1'].'"></input>'.$row['entrada_esperada1'].'</td>';
                    $inpt .='<td><input type="hidden" id="salida_esperada1"  name="salida_esperada1"  value="'.$row['salida_esperada1'].'"></input>'.$row['salida_esperada1'].'</td>';
                    $inpt .='<td><input type="hidden" id="entrada_esperada2" name="entrada_esperada2" value="'.$row['entrada_esperada2'].'"></input>'.$row['entrada_esperada2'].'</td>';
                    $inpt .='<td><input type="hidden" id="salida_esperada2"  name="salida_esperada2"  value="'.$row['salida_esperada2'].'"></input>'.$row['salida_esperada2'].'</td>';
                    $inpt .='</tr>';
                    $inpt2 .='<tr>';
                    $inpt2 .='<td><input type="hidden" id="entrada_real1" name="entrada_real1" value="'.$row['entrada_real1'].'"></input>'.$row['entrada_real1'].'</td>';
                    $inpt2 .='<td><input type="hidden" id="salida_real1"  name="salida_real1"  value="'.$row['salida_real1'].'"></input>'.$row['salida_real1'].'</td>';
                    $inpt2 .='<td><input type="hidden" id="entrada_real2" name="entrada_real2" value="'.$row['entrada_real2'].'"></input>'.$row['entrada_real2'].'</td>';
                    $inpt2 .='<td><input type="hidden" id="salida_real2"  name="salida_real2"  value="'.$row['salida_real2'].'"></input>'.$row['salida_real2'].'</td>';
                    $inpt2 .='</tr>';                        
                    $inpt3 .='<tr>';
                    $inpt3 .='<td>'.$row['inicio_st1'].'</td>';
                    $inpt3 .='<td>'.$row['fin_st1'].'</td>';
                    $inpt3 .='<td>'.$row['inicio_dlt1'].'</td>';
                    $inpt3 .='<td>'.$row['fin_dlt1'].'</td>';
                    $inpt3 .='</tr>';                        
	  } 
          $inpt .=' </tbody>              
                </table>';
          $inpt2 .=' </tbody>              
                </table>';
          $inpt3 .=' </tbody>              
                </table>';
       // }
	echo $inpt;
	echo $inpt2;
	echo $inpt3;
	
	//print_r($row);
	$link=Conex_Contancia_pgsql();
	//$query="select * from registro_diario where fecha'".$finicio."' and trabajador=".$trabajador;
//	echo $c;
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
                    	$inpt4 .='<td><input type="hidden" id="entrada_st_interno" name="entrada_st_interno" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>';
                    	$inpt4 .='<td><input type="hidden" id="salida_st_interno"  name="salida_st_interno"  value="'.$row['salida_te'].'"></input>'.$row['salida_te'].'</td>';
			if ($row['autorizado_te']!='')
		    		$inpt4 .='<td>*</td>';
			else
		   		$inpt4 .='<td></td>';	
                        $inpt4 .='</tr>';
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
			if ($row["turno"]==1) $turno='23:00 a 07:00';
			if ($row["turno"]==2) $turno='07:00 a 15:00';
			if ($row["turno"]==3) $turno='15:00 a 23:00';
                    	$inpt5 .='<td><input type="hidden" id="entrada_st_interno" name="entrada_ct_interno" value="'.$row['entrada_te'].'"></input>'.$row['entrada_te'].'</td>';
			if ($row['autorizado_ct']!='')
		    		$inpt5 .='<td>*</td>';
			else
		   		$inpt5 .='<td></td>';	
                        $inpt5 .='</tr>';
		   }
		//}
   	        $inpt5 .=' </tbody>              
               		</table>';
                $encabezado_cp=0;
		if ($row['cambio_permanencia']=='S'){
                    if ($encabezado_cp==0){ 
                        $inpt6 = '<b>Cambio de Permanencia</b><br> <table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
                        $inpt6 = $inpt6.'<thead>
                                    <tr>
                                        <th width="10%">Entrada Real</th>                 
                                        <th width="10%">Salida Real</th>
                                        <th width="10%" align="center">Aprobado</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        $inpt6 .='<tr>';
                        $encabezado_cp=1;
                    }
                        $inpt6 .='<td><input type="hidden" id="entrada_st_interno" name="entrada_cp_interno" value="'.$row['entrada_real1'].'"></input>'.$row['entrada_real1'].'</td>';
                        $inpt6 .='<td><input type="hidden" id="salida_st_interno"  name="salida_cp_interno"  value="'.$row['salida_real1'].'"></input>'.$row['salida_real1'].'</td>';
                        if ($row['autorizado_cp']!='')
                                $inpt6 .='<td>*</td>';
                        else
                                $inpt6 .='<td></td>';
                        $inpt6 .='</tr>';
                 }
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
