<?PHP
require("libs/conexion.php");
$fecha = isset($_GET["fecha"])?$_GET["fecha"]:"-1";
$linea = isset($_GET["linea"])?$_GET["linea"]:"-1";
$conn = Conectarse_sio();
$conex_siteges = Conectarse();
$ano=substr($fecha, 0, 4);
$mes=substr($fecha, 5, 2);
$date0 = new DateTime($fecha);
$date0->modify('first day of this month');
$inicio_mes= $date0->format('Y-m-d');
//$inicio_mes = date("Y-m-01");
$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

$tn_real 		= 0;
$tn_prog 		= 0;
$tn_desvio 		= 0;
$tn_real_acum 		= 0;
$tn_prog_acum	 	= 0;
$tn_desvio_acum 	= 0;
$tn_proy		= 0;
$tn_prog_orig		= 0;
$tn_plan_mes 		= 0;
$tn_var_anual		= 0;

$tn_inv_inicial 	= 0;
$tn_inv_real 		= 0;
$tn_inv_plan_anual 	= 0;
$tn_inv_acum_dia_anterior=0;

$tn_desp_real  		= 0;
$tn_desp_mes  		= 0;
$tn_desp_plan_mes  	= 0;
$tn_desp_var_mes  	= 0;
$tn_estim_cierre  	= 0;
$tn_original_plan  	= 0;
$tn_var_cierre  	= 0;
$tn_desp_real_acum  	= 0;
$tn_desp_plan_acum   	= 0;
$tn_desp_var_acum  	= 0;
$tn_desp_pea 		= 0;
$tn_var_anual_ant	= 0;
$tn_desp_mes_ant	= 0;
$accion			= '';
$tn_inv_dia_anterior	= 0;

$query1 = "SELECT * from prd_real_plan where fecha_produccion='".$fecha."' AND cod_linea='".$linea."'";
//print $query1;
$resultado1 = pg_query($conex_siteges, $query1) or die("Error en la Consulta SQL:".$query1);
$numReg1 = pg_num_rows($resultado1);
if ($numReg1>0){
	$accion		= 'U';
	$fila1		= pg_fetch_array($resultado1);
	$tn_real 	= $fila1['tn_real'];
	$tn_prog 	= $fila1['tn_prog'];
	$tn_desvio 	= $fila1['tn_desvio'];
	$tn_real_acum 	= $fila1['tn_real_acum'];
	$tn_prog_acum	= $fila1['tn_prog_acum'];
	$tn_desvio_acum = $fila1['tn_desvio_acum'];
	$tn_proy	= $fila1['tn_proy'];
	$tn_prog_orig	= $fila1['tn_prog_orig'];
	$tn_plan_mes 	= $fila1['tn_plan_mes'];
	$tn_var_anual	= $fila1['tn_var_anual'];
	pg_free_result($resultado1);
	
	///////INVENTARIO///////////////////////////////////
	$query2 	= "SELECT * from inv_real_plan where fecha_produccion='".$fecha."'";
	$resultado2 	= pg_query($conex_siteges, $query2) or die("Error en la Consulta SQL:".$query2);
	$numReg2 	= pg_num_rows($resultado2);
	if ($numReg2>0){
	    $fila2		= pg_fetch_array($resultado2);
	    $tn_inv_inicial 	= $fila2['tn_inv_inicial'];
	    $tn_inv_real 	= $fila2['tn_inv_real'];
	    $tn_inv_plan_anual 	= $fila2['tn_inv_plan_anual'];
	}else{
	//    $fecha 	= date('Y-m-d');
	//    $nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
	//    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

	    $query3 	= "SELECT * from inv_real_plan where fecha_produccion='".$nuevafecha."'";
	    $resultado3 = pg_query($conex_siteges, $query3) or die("Error en la Consulta SQL:".$query3);
	    $numReg3 	= pg_num_rows($resultado3);
 	    if ($numReg3>0){ 
	 	$fila3			= pg_fetch_array($resultado3);
	  	$tn_inv_inicial 	= $fila3['tn_inv_inicial'];
	  	$tn_inv_real 		= $fila3['tn_inv_real'];
	  	$tn_inv_plan_anual 	= $fila3['tn_inv_plan_anual'];
            }else{
	  	$tn_inv_inicial 	= 0;
	  	$tn_inv_real 		= 0;
	  	$tn_inv_plan_anual 	= 0;
	    }
        }
	///////////DESPACHO/////////////////////////////
	$query4 	= "SELECT * from desp_real_plan where fecha_produccion='".$fecha."'";
	$resultado4 	= pg_query($conex_siteges, $query4) or die("Error en la Consulta SQL:".$query4);
	$numReg4 	= pg_num_rows($resultado4);
	if ($numReg4>0){
	    $fila4		= pg_fetch_array($resultado4);
  	    $tn_desp_real  	= $fila4['tn_desp_real'];
	    $tn_desp_mes 	= $fila4['tn_desp_mes'];
	    $tn_desp_plan_mes  	= $fila4['tn_desp_plan_mes'];
	    $tn_desp_var_mes 	= $fila4['tn_desp_var_mes'];
	    $tn_estim_cierre  	= $fila4['tn_estim_cierre'];
	    $tn_original_plan  	= $fila4['tn_original_plan'];
	    $tn_var_cierre  	= $fila4['tn_var_cierre'];
	    $tn_desp_real_acum  = $fila4['tn_desp_real_acum'];
	    $tn_desp_plan_acum  = $fila4['tn_desp_plan_acum'];
	    $tn_desp_var_acum  	= $fila4['tn_desp_var_acum'];
	    $tn_desp_pea 	= $fila4['tn_desp_pea'];
	}else{
//	    $fecha 	= date('Y-m-d');
//  	    $nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
//	    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

	    $query5 	= "SELECT * from desp_real_plan where fecha_produccion='".$nuevafecha."'";
	    $resultado5 = pg_query($conex_siteges, $query5) or die("Error en la Consulta SQL:".$query5);
	    $numReg5 	= pg_num_rows($resultado5);
	    if ($numReg5>0){ 
		$fila5			= pg_fetch_array($resultado5);	  	
	  	$tn_desp_real  		= $fila5['tn_desp_real'];
		$tn_desp_mes  		= $fila5['tn_desp_mes'];
		$tn_desp_plan_mes  	= $fila5['tn_desp_plan_mes'];
		$tn_desp_var_mes  	= $fila5['tn_desp_var_mes'];
		$tn_estim_cierre  	= $fila5['tn_estim_cierre'];
		$tn_original_plan  	= $fila5['tn_original_plan'];
		$tn_var_cierre  	= $fila5['tn_var_cierre'];
		$tn_desp_real_acum  	= $fila5['tn_desp_real_acum'];
		$tn_desp_plan_acum  	= $fila5['tn_desp_plan_acum'];
		$tn_desp_var_acum  	= $fila5['tn_desp_var_acum'];
		$tn_desp_pea 		= $fila5['tn_desp_pea'];
            }else{
	  	$tn_desp_real  		= 0;
		$tn_desp_mes  		= 0;
		$tn_desp_plan_mes  	= 0;
		$tn_desp_var_mes  	= 0;
		$tn_estim_cierre  	= 0;
		$tn_original_plan  	= 0;
		$tn_var_cierre  	= 0;
		$tn_desp_real_acum  	= 0;
		$tn_desp_plan_acum  	= 0;
		$tn_desp_var_acum  	= 0;
		$tn_desp_pea 		= 0;

	    } 
        }

}else{
//	$mes_ant	=date('Y-m-d',strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));
	$date2 = new DateTime($fecha); 
	$date2->modify('last day of -1 month'); 
	$mes_ant= $date2->format('Y-m-d');
	$dia_ant	=date("Y-m-d", strtotime("$fecha -1 day"));
	
	$query0 	= "SELECT * from programacion where ano='".$ano."' AND mes='".$mes."' AND idreactor='".$linea."'";
	$resultado0	= pg_query($conex_siteges, $query0) or die("Error en la Consulta SQL:".$query0);
	$numReg0 	= pg_num_rows($resultado0);
	if ($numReg0>0){
	        $fila0			= pg_fetch_array($resultado0);
        	$tn_proy                = $fila0['tn_ajus_prog_orig_mes'];
	        $tn_prog_orig           = $fila0['tn_prog_orig'];
        	$tn_plan_mes            = $fila0['tn_plan_anu_mes'];
	        $tn_inv_plan_anual      = $fila0['tn_inv_plan_anual'];
        	$tn_estim_cierre        = $fila0['tn_esti_cierre_mes'];
	        $tn_original_plan       = $fila0['tn_plan_orig_mes'];
        	$tn_desp_pea            = $fila0['tn_presu_anual_desp'];
        	$tn_desp_plan_mes       = $fila0['tn_desp_plan_mes'];
		$tn_desp_plan_acum	= $fila0['tn_desp_plan_acum'];
	}else{
                $tn_proy                = 0;
                $tn_prog_orig           = 0;
                $tn_plan_mes            = 0;
                $tn_inv_plan_anual      = 0;
                $tn_estim_cierre        = 0;
                $tn_original_plan       = 0;
                $tn_desp_pea            = 0;
		$tn_desp_plan_mes 	= 0;
		$tn_desp_plan_acum 	= 0;
	}


	if ($linea=='BRV')
		   $sql = "SELECT real_1  ,plan_1, acu_real_1, acu_plan_1, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$fecha."'";
	else
		   $sql = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$fecha."'"; 

	 $stmt	 		= $conn->query($sql);
	 $col 			= $stmt->columnCount();	
	 $row 			= $stmt->fetch();
 	 $accion		= 'I';
 	 $tn_real		= $row[0];
	 $tn_prog		= $row[1];
	 $tn_desvio 		= $tn_real - $tn_prog;
	 $tn_real_acum		= $row[2];
	 $tn_prog_acum		= $row[3];
//print "<br>tn_real=".$tn_real."<br>tn_prog=".$tn_prog."<br>tn_real_acum=".$tn_real_acum."<br>tn_prog_acum=".$tn_prog_acum;
//print "Despacho:".$row[5];

	 //if ($row[5]!='null' && $row[5]!=''  ) $tn_desp_real = $row[5];	else $tn_desp_real=0;

	 /*if ($tn_real_acum=='null' || $tn_real_acum=='' || $tn_prog_acum=='null' || $tn_prog_acum=='' || $tn_real_acum==0 || $tn_prog_acum==0){
	        if ($linea=='BRV')
                   $sql_ant = "SELECT real_1  ,plan_1, acu_real_1, acu_plan_1, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
        	else
                   $sql_ant = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
//		$tn_real_acum= $sql_ant;
		$stmt_ant              = $conn->query($sql_ant);
		$col_ant               = $stmt_ant->columnCount();
		$row_ant               = $stmt_ant->fetch();
	        $tn_real_acum          = $row_ant[2]+$tn_real+$sql_ant;
	        $tn_prog_acum          = $row_ant[3]+$tn_prog;
	 }
	 $tn_desvio_acum	= $tn_real_acum-$tn_prog_acum;
*/
         ########## 
       	 //$inv_mes_ant = "SELECT * from inv_real_plan where fecha_produccion='".$mes_ant."'";
       	 $inv_mes_ant = "SELECT * from inv_real_plan where fecha_produccion='".$inicio_mes."'";
	 $resultado1 = pg_query($conex_siteges, $inv_mes_ant) or die("Error en la Consulta SQL:".$inv_mes_ant);
       	 $numReg1 = pg_num_rows($resultado1);
         if ($numReg1>0){
		$row1=pg_fetch_array($resultado1);
	 	//$tn_inv_inicial		= $row1['tn_inv_inicial']; 
	 	$tn_inv_inicial		= $row1['tn_inv_real']; 
	 }else{
		$tn_inv_mes_ant=0;
		$tn_inv_inicial=0;
         }
	 if ($inicio_mes!=$fecha){        ########### NO ES INICIO DE MES?
	       	 $inv_mes_ant = "SELECT * from inv_real_plan where fecha_produccion='".$inicio_mes."'";
		 $resultado1 = pg_query($conex_siteges, $inv_mes_ant) or die("Error en la Consulta SQL:".$inv_mes_ant);
       		 $numReg1 = pg_num_rows($resultado1);
	         if ($numReg1>0){
			$row1=pg_fetch_array($resultado1);
		 //	$tn_inv_inicial		= $row1['tn_inv_real']; 
	          	$tn_inv_inicial		= $row1['tn_inv_inicial'];//." ".$inv_mes_ant; 
		 }else{
			$tn_inv_mes_ant=0;
			$tn_inv_inicial=0;
	         }

	 	 if ($tn_real_acum=='null' || $tn_real_acum=='' || $tn_prog_acum=='null' || $tn_prog_acum=='' || $tn_real_acum==0 || $tn_prog_acum==0){
	       	 	if ($linea=='BRV')
                   		$sql_ant = "SELECT real_1  ,plan_1, acu_real_1, acu_plan_1, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
        		else
                   		$sql_ant = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
	//		$tn_real_acum= $sql_ant;
			$stmt_ant              = $conn->query($sql_ant);
			$col_ant               = $stmt_ant->columnCount();
			$row_ant               = $stmt_ant->fetch();
		        $tn_real_acum          = $row_ant[2]+$tn_real+$sql_ant;
	        	$tn_prog_acum          = $row_ant[3]+$tn_prog;
	 	 }
	 	 $tn_desvio_acum	= $tn_real_acum-$tn_prog_acum;
		 $prd_mes_ant = "SELECT * from prd_real_plan where fecha_produccion='".$mes_ant."' and cod_linea='".$linea."'";
		 if ($stmt2= $conn->query($prd_mes_ant))
			 if ($stmt2->fetchColumn() > 0){ 
	 			 $col2 			= $stmt2->columnCount();	
	 			 $row2			= $stmt2->fetch();
				 $tn_var_anual_ant	= $row2['tn_var_anual']; 
			 }else
				$tn_var_anual_ant =0;
	 

        	 $inv_dia_ant = "SELECT * from inv_real_plan where fecha_produccion='".$dia_ant."'";
		 $resultado2 = pg_query($conex_siteges, $inv_dia_ant) or die("Error en la Consulta SQL:".$inv_dia_ant);
        	 $numReg2 = pg_num_rows($resultado2);
	          if ($numReg2>0){
			$row2=pg_fetch_array($resultado2);
		 		$tn_inv_dia_anterior		= $row2['tn_inv_real']; 
		 		$tn_inv_acum_dia_anterior	= $row2['tn_inv_real']; 
		}else{
 			$tn_inv_dia_anterior=0;
			$tn_inv_acum_dia_anterior=0;
		}
		
		$desp_mes_ant = "SELECT * from desp_real_plan where fecha_produccion='".$dia_ant."'";
       		$resultado6 = pg_query($conex_siteges, $desp_mes_ant) or die("Error en la Consulta SQL:".$desp_mes_ant);
	       	$numReg6 = pg_num_rows($resultado6);
       		if ($numReg6>0){
	               	$row6=pg_fetch_array($resultado6);
 			$tn_desp_real_acum		= $row6['tn_desp_real_acum']; 
			$tn_desp_mes_ant                = $row6['tn_desp_mes'];
		}else{
			$tn_desp_real_acum =0;	
			$tn_desp_mes_ant =0;
		}
		$tn_desp_mes=$tn_desp_mes_ant +$tn_desp_real ;
		$tn_desp_real_acum=$tn_desp_real_acum+$tn_desp_real;
        }else{  ######### SINO ES INICIO DE MES
	     $inv_mes_ant = "SELECT * from inv_real_plan where fecha_produccion='".$mes_ant."'";
	     $resultado1 = pg_query($conex_siteges, $inv_mes_ant) or die("Error en la Consulta SQL:".$inv_mes_ant);
             $numReg1 = pg_num_rows($resultado1);
             if ($numReg1>0){
		 $row1=pg_fetch_array($resultado1);
	 	 $tn_inv_inicial		= $row1['tn_inv_real']; 
	     }else{
		 $tn_inv_mes_ant=0;
		 $tn_inv_inicial=0;
            }

            $tn_desp_mes	= 0;
            $tn_desp_real_acum	= 0;   	    
	    $tn_real_acum	= $tn_real;	    
	    $tn_desvio_acum 	= $tn_real_acum - $tn_prog_acum;
	    $tn_inv_dia_anterior= $tn_inv_inicial;
	}      ##########  FIN DE ES INICIO DE MES
//        if ($inicio_mes==$fecha) $tn_desp_mes=0; else $tn_desp_mes=$tn_desp_mes_ant +$tn_desp_real ;
//	$tn_desp_real_acum=$tn_desp_real_acum+$tn_desp_real;
	
	
	
				
	if ($tn_var_anual_ant=='null') $tn_var_anual_ant=0;
	if ($tn_plan_mes>0)
		$tn_var_anual = $tn_real_acum -(($tn_prog_acum/$tn_plan_mes )*$tn_prog_orig)+$tn_var_anual_ant;
	else
		$tn_var_anual=0;

//	print $tn_var_anual ."=". $tn_real_acum ."-((".$tn_prog_acum."/".$tn_plan_mes." )*".$tn_prog_orig.")+".$tn_var_anual_ant."\r";

}
	
if ($tn_inv_real==0)	 $tn_inv_real	=$tn_inv_dia_anterior+$tn_real-$tn_desp_real;
//if ($tn_inv_real==0)	 $tn_inv_real=$tn_inv_acum_dia_anterior+$tn_real-$tn_desp_real;
if ($tn_desp_var_mes==0) $tn_desp_var_mes=$tn_desp_mes-$tn_desp_plan_mes;
//$tn_desp_real_acum=$tn_desp_real_acum+$tn_desp_real;
$tn_desp_var_acum=	$tn_desp_plan_acum-$tn_desp_real_acum;

	echo "$(\"#tn_real\").val(\"" . $tn_real . "\");\n" ;
	echo "$(\"#tn_prog\").val(\"" . $tn_prog . "\");\n" ;
//	echo "$(\"#tn_real\").val(\"" . $inv_mes_ant . "\");\n" ;
//	echo "$(\"#tn_prog\").val(\"" . $inv_dia_ant . "\");\n" ;
	echo "$(\"#tn_desvio\").val(\"" . $tn_desvio . "\");\n" ;
	echo "$(\"#tn_real_acum\").val(\"" . $tn_real_acum . "\");\n" ;
	echo "$(\"#tn_prog_acum\").val(\"" . $tn_prog_acum . "\");\n" ;
	echo "$(\"#tn_desvio_acum\").val(\"" . $tn_desvio_acum . "\");\n" ;
	echo "$(\"#tn_proy\").val(\"" . $tn_proy . "\");\n" ;
	echo "$(\"#tn_prog_orig\").val(\"" . $tn_prog_orig . "\");\n" ;
	echo "$(\"#tn_plan_mes\").val(\"" . $tn_plan_mes . "\");\n" ;
	echo "$(\"#tn_var_anual\").val(\"" . $tn_var_anual . "\");\n" ;

	echo "$(\"#tn_inv_inicial\").val(\"" . $tn_inv_inicial . "\");\n" ;
	echo "$(\"#tn_inv_real\").val(\"" . $tn_inv_real ."\");\n" ;
	echo "$(\"#tn_inv_real_mes\").val(\"" . $tn_inv_real ."\");\n" ;
	//echo "$(\"#tn_inv_real\").val(\"" . $tn_inv_real ."=".$tn_inv_dia_anterior."+".$tn_real."-".$tn_desp_real."\");\n" ;
	echo "$(\"#tn_inv_plan_anual\").val(\"" . $tn_inv_plan_anual . "\");\n" ;

	//echo "$(\"#tn_desp_real\").val(\"" . $tn_desp_real ." - ".$sql_ant. "\");\n" ;
	echo "$(\"#tn_desp_real\").val(\"" . $tn_desp_real . "\");\n" ;
	echo "$(\"#tn_desp_mes\").val(\"" . $tn_desp_mes . "\");\n" ;
	echo "$(\"#tn_desp_real_mes\").val(\"" . $tn_desp_mes . "\");\n" ;
	echo "$(\"#tn_desp_plan_mes\").val(\"" . $tn_desp_plan_mes . "\");\n" ;
	echo "$(\"#tn_desp_var_mes\").val(\"" . $tn_desp_var_mes . "\");\n" ;
	echo "$(\"#tn_estim_cierre\").val(\"" . $tn_estim_cierre . "\");\n" ;
	echo "$(\"#tn_original_plan\").val(\"" . $tn_original_plan . "\");\n" ;
	echo "$(\"#tn_var_cierre\").val(\"" . $tn_var_cierre . "\");\n" ;
	echo "$(\"#tn_desp_real_acum\").val(\"" . $tn_desp_real_acum . "\");\n" ;
	echo "$(\"#tn_desp_plan_acum\").val(\"" . $tn_desp_plan_acum . "\");\n" ;
	echo "$(\"#tn_desp_var_acum\").val(\"" . $tn_desp_var_acum . "\");\n" ;
	echo "$(\"#tn_desp_pea\").val(\"" . $tn_desp_pea . "\");\n" ;
	echo "$(\"#accion\").val(\"" . $accion . "\");\n" ;
		
pg_close($conex_siteges);
?>
