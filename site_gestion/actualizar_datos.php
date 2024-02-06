<?PHP
require("libs/conexion.php");
session_start();
//$fecha = isset($_GET["fecha"])?$_GET["fecha"]:"-1";
//$linea = isset($_GET["linea"])?$_GET["linea"]:"-1";
if (!isset($_SESSION['user_session_sio'])) $_SESSION['user_session_sio']='root';
$fecha_inicio   = '2019-11-01';         // FECHA A PARTIR DE DONDE EL SISTEMA DE SITE DE GESTION CONSULTARA EL SISTEMA SIO PARA ARMAR EL ARCHIVO TXT
$fecha_tratada  = $fecha_inicio;
$fecha_hoy 	    = '2019-11-30';//date('Y-m-d');		// FECHA ACTUAL
$hoy 		    = $fecha_hoy;
$fecha 		    = date ( 'Y-m-d' ,strtotime ( '-1 day' , strtotime ( $fecha_hoy)));    // FECHA DE AYER PARA TOMAR DEL SIO LA INFORMACION
$linea          = "BRV1";
$cod_linea      = $linea;
$mercado        = "V1";   //
$producto       = "112";   //
$apertura_archivo=2;

$conn = Conectarse_sio();
$conex_siteges = Conectarse();
$cn=  Conectarse();

$fecha_busqueda=$fecha_inicio;
$inv_actual=0;
print "<br>****************************************************************<br>1.-".$fecha_tratada."<br>";

$tn_inv_inicial 	= 0;
while ($fecha_tratada < $fecha_hoy) {
//print "<br>".$fecha_tratada."<br>";
print "<br>2.-while ".$fecha_tratada." = ".$fecha_hoy."<br>";
print "<br>3.-".$fecha_tratada."<br>";
$fecha_prd 	    = date("Ymd", strtotime($fecha_tratada)); //FECHA DE PRODUCCION CON QUE SE GUARDARAN LOS REGISTROS EN LA DB LOCAL, ES LA MISMA A LA FECHA ACTUAL
$ano=substr($fecha_tratada, 0, 4);
$mes=substr($fecha_tratada, 5, 2);
//$inicio_mes = date("Y-m-01");
$inicio_mes = '2019-'.$mes."-01"; //date("Y-m-01");

print "<br>4.-".$fecha_tratada."<br>";
$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha_tratada ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
print "<br>5.-".$fecha_tratada."<br>";
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

$tn_inv_real 		= 0;
$tn_inv_plan_anual 	= 0;

$tn_desp_real  		= 0;
$tn_desp_mes  		= 0;
$tn_desp_plan_mes  	= 0;
$tn_desp_var_mes  	= 0;
$tn_estim_cierre  	= 0;
$tn_original_plan  	= 0;
$tn_var_cierre  	= 0;
$tn_desp_real_acum      = 0;
$tn_desp_plan_acum      = 0;
$tn_desp_var_acum  	= 0;
$tn_desp_pea 		= 0;
$tn_var_anual_ant	= 0;
$tn_desp_mes_ant	= 0;
$accion			= '';


/*
// CONSULTAMNOS PARA VER SI YA FUE CARGADO EN EL DB LOCAL
// SI YA FUE CARGADO LO BORRAMOS
$query1 = "SELECT * from prd_real_plan where fecha_produccion='".$fecha_busqueda."' AND cod_linea='".$linea."'";
//print $query1;
$resultado1 = pg_query($conex_siteges, $query1) or die("Error en la Consulta SQL:".$query1);
$numReg1 = pg_num_rows($resultado1);
if ($numReg1>0){
	$accion		= 'U';
	// BORRAMOS EL REGISTRO DE LA TABLA PRD_REAL_PLAN
	$query1 = "delete from prd_real_plan where fecha_produccion='".$fecha_busqueda."' AND cod_linea='".$linea."'";
	$resultado1 = pg_query($conex_siteges, $query1) or die("Error en la Consulta SQL:".$query1);
	$numReg1 = pg_num_rows($resultado1);
        $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Se elimino por sistema el registro de Produccion ".$fecha_prd." de la tabla prd_real_plan')");

	//BORRAMOS EL REGISTRO DE LA TABLA INV_REAL_PLAN
	$query2 	= "delete from inv_real_plan where fecha_produccion='".$fecha."'";
	$resultado2 	= pg_query($conex_siteges, $query2) or die("Error en la Consulta SQL:".$query2);
	$numReg2 	= pg_num_rows($resultado2);
        $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Se elimino por sistema el registro de Produccion ".$fecha_prd." de la tabla inv_real_plan')");
	//BORRAOS EL REGISTRO DE LA TABLA DESP_REAL_PLAN
	$query4 	= "delete from desp_real_plan where fecha_produccion='".$fecha."'";
	$resultado4 	= pg_query($conex_siteges, $query4) or die("Error en la Consulta SQL:".$query4);
	$numReg4 	= pg_num_rows($resultado4);
            $sql_sms="Se elimino por sistema el registro de Produccion ".$fecha_prd." de la tabla desp_real_plan";
	    $sql="INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), '".$sql_sms."')";

            $auditoria = pg_query($cn,$sql);
}
*/
// CONSULTAMOS LOS DATOS DE LA PROGRAMACION MENSUAL	
print "<br>6.-".$fecha_tratada."<br>";
$mes_ant	=date('Y-m-d',strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));
$dia_ant=date("Y-m-d", strtotime("$fecha_tratada -1 day"));
print "<br>7.-".$fecha_tratada."<br>";
$query0 	= "SELECT * from programacion where ano='".$ano."' AND mes='".$mes."' AND idreactor='".$linea."'";
print "<br>".$query0."<br>";
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
	    $tn_desp_plan_mes 	    = 0;
	    $tn_desp_plan_acum 	    = 0;
}

////////////////////////////////////////////////////////////////
//             CONSULTAMOS LOS DATOS DEL SIO                  //
////////////////////////////////////////////////////////////////
if ($linea=='BRV')
		   $sql = "SELECT real_1  ,plan_1, acu_real_1, acu_plan_1, real_dia, despacho_hbc_total,fecha FROM Tab_Reporte_Diario where fecha='".$fecha_tratada."'";
else
		   $sql = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total,fecha FROM Tab_Reporte_Diario where fecha='".$fecha_tratada."'"; 
		   //$sql = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total,fecha FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='2019-09-30'"; 
//		   $sql = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total,fecha FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$fecha_busqueda."'"; 
print "<br>".$sql."<br>";
//$stmt	 	= $conn->query($sql);
//$col 		= $stmt->columnCount();	
//$row 		= $stmt->fetch();
$resultado0 = pg_query($conex_siteges, $sql) or die("Error en la Consulta SQL:".$sql);
$numReg0    = pg_num_rows($resultado0);
$row     = pg_fetch_array($resultado0);



$accion		= 'I';
$tn_real	= $row[0];
$tn_prog	= $row[1];
$tn_desvio 	= $tn_real - $tn_prog;
$tn_real_acum	= $row[2];
$tn_prog_acum	= $row[3];
$tn_desp_real	=0;
$inv_actual	=$inv_actual+$tn_real;
//IF (($row[5]!='NULL') && ($row[5]!=''))  $tn_desp_real = $row[5];	ELSE $tn_desp_real=0;

if ($tn_real_acum=='null' || $tn_real_acum=='' || $tn_prog_acum=='null' || $tn_prog_acum=='' || $tn_real_acum==0 || $tn_prog_acum==0){
print "No hay valores en los acumulados<br>";
    if ($linea=='BRV')
        $sql_ant = "SELECT real_1  ,plan_1, acu_real_1, acu_plan_1, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
    else
        $sql_ant = "SELECT real_2  ,plan_2, acu_real_2, acu_plan_2, real_dia, despacho_hbc_total FROM DBSio.matcoy.Tab_Reporte_Diario where fecha='".$nuevafecha."'";
print $sql_ant."<br>"; 
//    $resultado0 = pg_query($conn, $sql_ant) or die("Error en la Consulta SQL:".$sql_ant);
//    $numReg0    = pg_num_rows($resultado0);
    $stmt_ant              = $conn->query($sql_ant);
    $col_ant               = $stmt_ant->columnCount();
    $row_ant               = $stmt_ant->fetch();
    if ($col_ant>0){
        $tn_real_acum          = $row_ant[2]+$tn_real+$sql_ant;
        $tn_prog_acum          = $row_ant[3]+$tn_prog;
    }else{
        $tn_real_acum          = 0;
        $tn_prog_acum          = 0;
    }    
}

$tn_desvio_acum	= $tn_real_acum-$tn_prog_acum;

/////////////////////////////////////////////////////////////////////////////
//   VERIFICAMOS QUE YA HAYAN CARGADO EL REGISTRO DEL DIA EN LA DB LOCAL   //
/////////////////////////////////////////////////////////////////////////////
print "<br>if $row[6]=".$row[6]."<br>";
if ($row[6]!=""){
	//CONSULTAMOS LOS DATOS DEL "MES" ANTERIOR DE LA TABLA PRD_REAL_PLAN
	$prd_mes_ant 	= "SELECT * from prd_real_plan where fecha_produccion='".$mes_ant."' and cod_linea='".$linea."'";
print "<br>CONSULTAMOS LA PRODUCCION DEL MES ANTERIOR<br>".$prd_mes_ant."<br>";
	if ($stmt2	= $conn->query($prd_mes_ant))
	if ($stmt2->fetchColumn() > 0){ 
		$col2 		  = $stmt2->columnCount();	
		$row2		  = $stmt2->fetch();
		$tn_var_anual_ant = $row2['tn_var_anual']; 
	 }else
		$tn_var_anual_ant = 0;

	//CONSULTAMNOS LOS DATOS DEL "MES" ANTERIOR DE LA TABLA INV_REAL_PLAN	 
	$inv_mes_ant 	= "SELECT * from inv_real_plan where fecha_produccion='".$mes_ant."'";
print "<br>Consultamos los datos mes anterior (INV):".$inv_mes_ant."<br>";
	$resultado1 	= pg_query($conex_siteges, $inv_mes_ant) or die("Error en la Consulta SQL:".$inv_mes_ant."\n");
	$numReg1 	= pg_num_rows($resultado1);
	if ($numReg1>0){
		$row1		=pg_fetch_array($resultado1);
//		if ($inicio_mes==$fecha_tratada){
	 		$tn_inv_inicial	= $row1['tn_inv_real']; 
//		}else{

//	 		$tn_inv_inicial	= $row1['tn_inv_real']; 
//		}
	}else{
		$tn_inv_mes_ant = 0;
//		$tn_inv_inicial = 0;
	}

	//CONSULTAMOS LOS DATOS DEL "DIA" ANTERIOR DE LA TABLA DESP_REAL_PLAN
	$desp_mes_ant 	= "SELECT * from desp_real_plan where fecha_produccion='".$dia_ant."'";
	$resultado6 	= pg_query($conex_siteges, $desp_mes_ant) or die("Error en la Consulta SQL:".$desp_mes_ant."\n");
	$numReg6 	= pg_num_rows($resultado6);
	if ($numReg6>0){
	       	$row6=pg_fetch_array($resultado6);
		$tn_desp_real_acum		= $row6['tn_desp_real_acum']; 
		$tn_desp_mes_ant                = $row6['tn_desp_mes'];
	}else{
		$tn_desp_real_acum =0;	
		$tn_desp_mes_ant =0;
	}

	//CONSULTAMOS LOS DATOS DEL "DIA" ANTERIOR DE LA TABLA INV_REAL_PLAN
	$inv_dia_ant 	= "SELECT * from inv_real_plan where fecha_produccion='".$dia_ant."'";
print "<br>Consultamos los datos del DIA anterior (INV):".$inv_dia_ant."<br>";
	$resultado2 	= pg_query($conex_siteges, $inv_dia_ant) or die("Error en la Consulta SQL:".$inv_dia_ant."\n");
	$numReg2 	= pg_num_rows($resultado2);
	if ($numReg2>0){
		$row2=pg_fetch_array($resultado2);
		$tn_inv_dia_anterior		= $row2['tn_inv_real']; 
		$tn_inv_acum_dia_anterior	= $row2['tn_inv_real']; 
	}else{
		$tn_inv_dia_anterior=0;
		$tn_inv_acum_dia_anterior=0;
	}

	//CALCULAMOS ALGUNAS VARIABLES 
print $inicio_mes."=".$fecha_tratada;	
	if ($inicio_mes==$fecha_tratada){
print "<br>##############################<br>";
	    $tn_desp_mes	= 0; 
	    $tn_inv_inicial	= $inv_actual-$tn_real; 
print "Invetntario Inicial;".$tn_inv_inicial ."<br>##############################<br>";
	}else{
	    $tn_desp_mes=$tn_desp_mes_ant +$tn_desp_real ;
	}
	$tn_desp_real_acum 		= $tn_desp_real_acum+$tn_desp_real;
	if ($tn_var_anual_ant=='null')  $tn_var_anual_ant=0;
	if ($tn_plan_mes>0)
		$tn_var_anual = $tn_real_acum -(($tn_prog_acum/$tn_plan_mes )*$tn_prog_orig)+$tn_var_anual_ant;
	else
		$tn_var_anual=0;
	
	if ($tn_inv_real==0)	 $tn_inv_real=$tn_inv_acum_dia_anterior+$tn_real-$tn_desp_real;
print "<br>tn_inv_acum_dia_anterior + tn_real - tn_desp_real<br>";
print "<br>".$tn_inv_acum_dia_anterior." + ".$tn_real." - ".$tn_desp_real."<br>";

	if ($tn_desp_var_mes==0) $tn_desp_var_mes=$tn_desp_mes-$tn_desp_plan_mes;
	$tn_desp_var_acum=$tn_desp_plan_acum-$tn_desp_real_acum;
        if ($mes=='08') $tn_inv_inicial =26;


print "NO HAY REGISTROS EN PRD_REAL_PLAN";
	//INSERTAMOS EN LA TABLA DE LAS DB LOCAL
	$queryy = "INSERT INTO prd_real_plan (";
            $queryy .= "fecha_reg, ";
            $queryy .= "fecha_produccion, ";
            $queryy .= "fecha_prd,";
            $queryy .= "cod_linea, ";
            $queryy .="tn_real, ";
            $queryy .= "tn_prog, ";
            $queryy .= "tn_desvio, ";
            $queryy .= "tn_real_acum, ";
            $queryy .= "tn_prog_acum, ";
            $queryy .= "tn_desvio_acum, ";
            $queryy .= "tn_proy, ";
            $queryy .= "tn_prog_orig, ";
            $queryy .= "tn_plan_mes, ";
            $queryy .= "tn_var_anual ";
            $queryy .= ") VALUES (";
            $queryy .= "Now(),";
            $queryy .= "'" . $fecha_tratada."', ";
            $queryy .= "'" . $fecha_prd."', ";
            $queryy .= "'" . $cod_linea."', ";
            $queryy .= "'" . $tn_real."', ";
            $queryy .= "'" . $tn_prog."', ";
            $queryy .= "'" . round($tn_desvio,2)."', ";
            $queryy .= "'" . $tn_real_acum."', ";
            $queryy .= "'" . $tn_prog_acum."', ";
            $queryy .= "'" . $tn_desvio_acum."', ";
            $queryy .= "'" . $tn_proy."', ";
            $queryy .= "'" . $tn_prog_orig."', ";
            $queryy .= "'" . $tn_plan_mes."', ";
            $queryy .= "'" . $tn_var_anual."' ";
            $queryy .= ");";
print "<br>".$queryy."<br>";
//	$fila 		   = $stmt->rowCount();	
	$resultado     = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
	$reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
	if (!$resultado) {
                echo "0";
                die("Ocurrió un error.\n ");
	}else{
                $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Nuevo Registro de Produccion ".$fecha_prd."')");
                $queryy = "INSERT INTO inv_real_plan(";
                $queryy .= "fecha_reg,";
                $queryy .= "fecha_produccion, ";
                $queryy .= "fecha_inventario, ";
                $queryy .= "mercado, ";
                $queryy .= "producto,";
                $queryy .= "tn_inv_inicial, ";
                $queryy .= "tn_inv_real, ";
                $queryy .= "tn_inv_plan_anual";
                $queryy .= ") VALUES (";
                $queryy .= "NOW(), ";
                $queryy .= "'".$fecha_tratada."', ";
                $queryy .= "'".$fecha_prd."', ";
                $queryy .= "'".$mercado."', ";
                $queryy .= "'".$producto."',";
                $queryy .= "'".$tn_inv_inicial."', ";
//                $queryy .= "'".$tn_inv_real."', ";
                $queryy .= "'".$inv_actual."', ";
                $queryy .= "'".$tn_inv_plan_anual."'";
                $queryy .= ");";

print "<br>".$queryy."<br>";
                $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy."\n");
                $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
		if (!$resultado) {
                     echo "0";
                     die("Ocurrió un error.\n ");
                }else{
                     $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Nuevo Registro de Inventario ".$fecha_prd."')");
                     $queryy = "INSERT INTO desp_real_plan(";
                     $queryy .= "fecha_reg, ";
                     $queryy .= "fecha_produccion,";
                     $queryy .= "fecha_despacho,";
                     $queryy .= "mercado, ";
                     $queryy .= "producto,";
                     $queryy .= "tn_desp_real, ";
                     $queryy .= "tn_desp_mes, ";
                     $queryy .= "tn_desp_plan_mes, ";
                     $queryy .= "tn_desp_var_mes,";
                     $queryy .= "tn_estim_cierre, ";
                     $queryy .= "tn_original_plan, ";
                     $queryy .= "tn_var_cierre, ";
                     $queryy .= "tn_desp_real_acum,";
                     $queryy .= "tn_desp_plan_acum, ";
                     $queryy .= "tn_desp_var_acum,";
                     $queryy .= "tn_desp_pea";
                     $queryy .= ") VALUES (";
                     $queryy .= "NOW(), ";
                     $queryy .= "'".$fecha_tratada."', ";
                     $queryy .= "'".$fecha_prd."', ";
                     $queryy .= "'".$mercado."',";
                     $queryy .= "'".$producto."',";
                     $queryy .= "'".$tn_desp_real."',";
                     $queryy .= "'".$tn_desp_mes."',";
                     $queryy .= "'".$tn_desp_plan_mes."',";
                     $queryy .= "'".$tn_desp_var_mes."',";
                     $queryy .= "'".$tn_estim_cierre."',";
                     $queryy .= "'".$tn_original_plan."',";
                     $queryy .= "'".$tn_var_cierre."',";
                     $queryy .= "'".$tn_desp_real_acum."',";
                     $queryy .= "'".$tn_desp_plan_acum."',";
                     $queryy .= "'".$tn_desp_var_acum."',";
                     $queryy .= "'".$tn_desp_pea."'";
                     $queryy .= ");";
                     $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy."\n");
print "<br>".$queryy."<br>";
                     $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
	    	     if (!$resultado) {
                          //echo "0";
                          die("Ocurrió un error.\n ");
                     }else{
                          $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Nuevo Registro de Despacho ".$fecha_prd."')");
                         //echo "1";
                     }
print "<br>fecha tratada andes de incrementar 1: ".$fecha_tratada."<br>------------------------------------------------------------------------------------------------------------------<br>" ;
$fecha_tratada= date ( 'Y-m-d' ,strtotime ( '1 day' , strtotime ( $fecha_tratada)));
print "<br>FEEEEEEEEEEEEEEEEEEEEEEEEEECHA:".$fecha_tratada."<br>------------------------------------------------------------------------------------------------------------------<br>" ;
                }
    	}	 // FIN DEL ELSE
//    }    

} // FIN DEL if ($row[7]!=""){
} // FIN DEL WHILE ($fecha_inicio <= $fecha_hoy) {
print "<br>LISTO ".$fecha_tratada."<br>------------------------------------------------------------------------------------------------------------------<br>" ;
	    ///////////////////////////////////////////////////////////////
	    //                   CREAMOS EL ARCHIVO TXT                  //
	    ///////////////////////////////////////////////////////////////
	     //$fecha_inicio=$fecha;
            //   RECORREMOS LA TABLA DE REGISTRO DIARIO Y EXTRAEMOS LA ULTIMA SEMANA
            $query_prd="select * from prd_real_plan where fecha_produccion >= '".$fecha_inicio."' order by fecha_produccion";
            $resultado_prd = pg_query($cn, $query_prd) or die("Error en la Consulta SQL:" . $query_prd."\n");
            if ($resultado_prd) {
                  $num_reg=pg_num_rows($resultado_prd);
                  while($reg = pg_fetch_array($resultado_prd)) //, null, PGSQL_ASSOC))
                  {
                     $linea_prd1  =$reg['fecha_prd'].";";
                     $linea_prd1 .=$reg['cod_linea'].";";
                     $linea_prd1 .=$reg['tn_real'].";";
                     $linea_prd1 .=$reg['tn_prog'].";";
                     $linea_prd1 .=$reg['tn_desvio'].";";
                     $linea_prd1 .=$reg['tn_real_acum'].";";
                     $linea_prd1 .=$reg['tn_prog_acum'].";";
                     $linea_prd1 .=$reg['tn_desvio_acum'].";";
                     $linea_prd1 .=$reg['tn_proy'].";";
                     $linea_prd1 .=$reg['tn_prog_orig'].";";
                     $linea_prd1 .=$reg['tn_plan_mes'].";";
                     $linea_prd1 .=$reg['tn_var_anual']."*";
                     $linea_prd2=$reg['fecha_prd'].";BRV;0;0;0;0;0;0;0;0;0;0*";
                     if ($apertura_archivo==1){
                         $ar_prd_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_prd_real_plan.txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_prd_real_plan1");
                         $ar_prd_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_prd_real_plan".date("Ymd H:i:s").".txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_prd_real_plan");
                         fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd1);
                         fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd2);
                         fclose($ar_prd_real_plan);
                         fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd1);
                         fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd2);
                         fclose($ar_prd_real_plan_h);
                     }else{
                         if ($num_reg==pg_num_rows($resultado_prd)){
                             $ar_prd_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_prd_real_plan.txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_prd_real_plan2");
                             $ar_prd_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_prd_real_plan".date("Ymd H:i:s").".txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo historico_txt/brv_prd_real_plan");
                             fwrite($ar_prd_real_plan,$linea_prd1);
                             fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan);
                             fwrite($ar_prd_real_plan_h,$linea_prd1);
                             fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan_h);
                         }else{
                             $ar_prd_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_prd_real_plan.txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_prd_real_plan3");
                             $ar_prd_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_prd_real_plan".date("Ymd H:i:s").".txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_prd_real_plan");
                             fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd1);
                             fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan);
                             fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd1);
                             fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan_h);
                         }
                    }
                     $num_reg--;
                  }
            }
            ########################################################################
            $query_inv="select * from inv_real_plan where fecha_produccion>='".$fecha_inicio."' order by fecha_inventario";
            $resultado_inv = pg_query($cn, $query_inv) or die("Error en la Consulta SQL:" . $query_inv);
            if ($resultado_inv) {
                  $num_reg=pg_num_rows($resultado_inv);
                  while($reg = pg_fetch_array($resultado_inv, null, PGSQL_ASSOC))
                  {
                     $linea_inv=$reg['fecha_inventario'].";";
                     $linea_inv.=$reg['mercado'].";";
                     $linea_inv.=$reg['producto'].";";
                     $linea_inv.=$reg['tn_inv_inicial'].";";
                     $linea_inv.=$reg['tn_inv_real'].";";
                     $linea_inv.=$reg['tn_inv_plan_anual']."*";
                    if ($apertura_archivo=="1"){
                        $ar_inv_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_inv_real_plan.txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_inv_real_plan4");
                        $ar_inv_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_inv_real_plan".date("Ymd H:i:s").".txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_inv_real_plan");
                        fwrite($ar_inv_real_plan,PHP_EOL.$linea_inv);
                        fclose($ar_inv_real_plan);
                        fwrite($ar_inv_real_plan_h,PHP_EOL.$linea_inv);
                        fclose($ar_inv_real_plan_h);
                    }else{
                         if ($num_reg==pg_num_rows($resultado_inv)){
                                $ar_inv_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_inv_real_plan.txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_inv_real_plan5");
                                $ar_inv_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_inv_real_plan".date("Ymd H:i:s").".txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo inv_real_plan");
                         	fwrite($ar_inv_real_plan,$linea_inv);
                         	fclose($ar_inv_real_plan);
                         	fwrite($ar_inv_real_plan_h,$linea_inv);
                         	fclose($ar_inv_real_plan_h);
                         }else{
                                $ar_inv_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_inv_real_plan.txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_inv_real_plan6");
                                $ar_inv_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_inv_real_plan".date("Ymd H:i:s").".txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_inv_real_plan");
                        	fwrite($ar_inv_real_plan,PHP_EOL.$linea_inv);
                	        fclose($ar_inv_real_plan);
        	                fwrite($ar_inv_real_plan_h,PHP_EOL.$linea_inv);
      	                   	fclose($ar_inv_real_plan_h);
                         }
                    }
                     $num_reg--;
                }
            }
             #######################################################################
            $query_desp="select * from desp_real_plan where fecha_produccion>='".$fecha_inicio."' order by fecha_despacho";
            $resultado_desp = pg_query($cn, $query_desp) or die("Error en la Consulta SQL:" . $query_desp);
            if ($resultado_desp) {
                  $num_reg=pg_num_rows($resultado_desp);
                  while($reg = pg_fetch_array($resultado_desp, null, PGSQL_ASSOC))
                  {
                      $linea_desp=$reg['fecha_despacho'].";";
                      $linea_desp.=$reg['mercado'].";";
                      $linea_desp.=$reg['producto'].";";
                      $linea_desp.=$reg['tn_desp_real'].";";
                      $linea_desp.=$reg['tn_desp_mes'].";";
                      $linea_desp.=$reg['tn_desp_plan_mes'].";";
                      $linea_desp.=$reg['tn_desp_var_mes'].";";
                      $linea_desp.=$reg['tn_estim_cierre'].";";
                      $linea_desp.=$reg['tn_original_plan'].";";
                      $linea_desp.=$reg['tn_var_cierre'].";";
                      $linea_desp.=$reg['tn_desp_real_acum'].";";
                      $linea_desp.=$reg['tn_desp_plan_acum'].";";
                      $linea_desp.=$reg['tn_desp_var_acum'].";";
                      $linea_desp.=$reg['tn_desp_pea']."*";
                      if ($apertura_archivo==1){
                          $ar_desp_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_desp_real_plan.txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan7");
                          $ar_desp_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_desp_real_plan".date("Ymd H:i:s").".txt","a") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan");
                          fwrite($ar_desp_real_plan,PHP_EOL.$linea_desp);
                          fclose($ar_desp_real_plan);
                          fwrite($ar_desp_real_plan_h,PHP_EOL.$linea_desp);
                          fclose($ar_desp_real_plan_h);
                      }else{
                          if ($num_reg==pg_num_rows($resultado_desp)){
                                $ar_desp_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_desp_real_plan.txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan8");
                                $ar_desp_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_desp_real_plan".date("Ymd H:i:s").".txt","w+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan");
                          	fwrite($ar_desp_real_plan,$linea_desp);
                          	fclose($ar_desp_real_plan);
                          	fwrite($ar_desp_real_plan_h,$linea_desp);
                          	fclose($ar_desp_real_plan_h);
                          }else{
                                $ar_desp_real_plan=fopen("/var/www/html/site_gestion/archivos_txt/brv_desp_real_plan.txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan9");
                                $ar_desp_real_plan_h=fopen("/var/www/html/site_gestion/historico_txt/brv_desp_real_plan".date("Ymd H:i:s").".txt","a+") or die ("\n[".date("Y-m-d H:i:s")."]Error al crear el archivo brv_desp_real_plan");
                                fwrite($ar_desp_real_plan,PHP_EOL.$linea_desp);
                          	fclose($ar_desp_real_plan);
                          	fwrite($ar_desp_real_plan_h,PHP_EOL.$linea_desp);
                          	fclose($ar_desp_real_plan_h);
                          }
                       }
                     $num_reg--;
                  }
           }
           #######################################################################

		
pg_close($conex_siteges);
//pg_close($conn);
pg_close($cn);
?>
