<?PHP
require_once('libs/conexion.php');
session_start();

//$hoy = getdate();
$hoy=date("Ymd");


$fecha_actual = date("d-m-Y");
//sumo 1 día
//echo date("d-m-Y",strtotime($fecha_actual."+ 1 days")); 
//resto 1 día
// ***********************LA LINEA QUE SIGUE ES LA QUE DEBE ESTAR ACTIVA  *************************************//
//$fecha_inicio= date("Y-m-d",strtotime($fecha_actual."- 7 days")); 
$fecha_inicio= '2019-08-08'; 
//print "Fecha de Inicio:".$fecha_inicio;

$cod_linea= isset($_POST["cod_linea"])?$_POST["cod_linea"]:"NULL";   //
$fecha= isset($_POST["txtFecha"])?$_POST["txtFecha"]:"NULL";   //

$apertura_archivo= isset($_POST["apertura_archivo"])?$_POST["apertura_archivo"]:"NULL";   //

$tn_real		= isset($_POST["tn_real"])?$_POST["tn_real"]:"NULL";   //
$tn_prog		= isset($_POST["tn_prog"])?$_POST["tn_prog"]:"NULL";   //
$tn_desvio		= isset($_POST["tn_desvio"])?$_POST["tn_desvio"]:"NULL";   //
$tn_real_acum		= isset($_POST["tn_real_acum"])?$_POST["tn_real_acum"]:"NULL";   //
$tn_prog_acum		= isset($_POST["tn_prog_acum"])?$_POST["tn_prog_acum"]:"NULL";   //
$tn_desvio_acum		= isset($_POST["tn_desvio_acum"])?$_POST["tn_desvio_acum"]:"NULL";   //
$tn_proy		= isset($_POST["tn_proy"])?$_POST["tn_proy"]:"NULL";   //
$tn_prog_orig		= isset($_POST["tn_prog_orig"])?$_POST["tn_prog_orig"]:"NULL";   //
$tn_plan_mes		= isset($_POST["tn_plan_mes"])?$_POST["tn_plan_mes"]:"NULL";   //
$tn_var_anual		= isset($_POST["tn_var_anual"])?$_POST["tn_var_anual"]:"NULL";   //
$tn_inv_inicial		= isset($_POST["tn_inv_inicial"])?$_POST["tn_inv_inicial"]:"NULL";   //
$tn_inv_real		= isset($_POST["tn_inv_real"])?$_POST["tn_inv_real"]:"NULL";   //
$tn_inv_plan_anual	= isset($_POST["tn_inv_plan_anual"])?$_POST["tn_inv_plan_anual"]:"NULL";   //
$tn_desp_real		= isset($_POST["tn_desp_real"])?$_POST["tn_desp_real"]:"NULL";   //
$tn_desp_mes		= isset($_POST["tn_desp_mes"])?$_POST["tn_desp_mes"]:"NULL";   //
$tn_desp_plan_mes	= isset($_POST["tn_desp_plan_mes"])?$_POST["tn_desp_plan_mes"]:"NULL";   //
$tn_desp_var_mes	= isset($_POST["tn_desp_var_mes"])?$_POST["tn_desp_var_mes"]:"NULL";   //
$tn_estim_cierre	= isset($_POST["tn_estim_cierre"])?$_POST["tn_estim_cierre"]:"NULL";   //
$tn_original_plan	= isset($_POST["tn_original_plan"])?$_POST["tn_original_plan"]:"NULL";   //
$tn_var_cierre		= isset($_POST["tn_var_cierre"])?$_POST["tn_var_cierre"]:"NULL";   //
$tn_desp_real_acum	= isset($_POST["tn_desp_real_acum"])?$_POST["tn_desp_real_acum"]:"NULL";   //
$tn_desp_plan_acum	= isset($_POST["tn_desp_plan_acum"])?$_POST["tn_desp_plan_acum"]:"NULL";   //
$tn_desp_var_acum	= isset($_POST["tn_desp_var_acum"])?$_POST["tn_desp_var_acum"]:"NULL";   //
$tn_desp_pea		= isset($_POST["tn_desp_pea"])?$_POST["tn_desp_pea"]:"NULL";   //
$accion			= isset($_POST["accion"])?$_POST["accion"]:"NULL";   //

$mercado= isset($_POST["mercado"])?$_POST["mercado"]:"NULL";   //
$producto= isset($_POST["producto"])?$_POST["producto"]:"NULL";   //

$fecha_prd = date("Ymd", strtotime($fecha));


function llenar_txt($cn,$fecha,$hoy,$fecha2,$ape_arch){
 	     $cn=$cn;
             $fecha_inicio=$fecha;
             $hoy=$hoy;
             $fecha_prd=$fecha2;
             $apertura_archivo=$ape_arch;
            //   RECORREMOS LA TABLA DE REGISTRO DIARIO Y EXTRAEMOS LA ULTIMA SEMANA
            $query_prd="select * from prd_real_plan where fecha_produccion >= '".$fecha_inicio."' order by fecha_produccion";
            $resultado_prd = pg_query($cn, $query_prd) or die("Error en la Consulta SQL:" . $query_prd);
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
                         $ar_prd_real_plan=fopen("archivos_txt/brv_prd_real_plan.txt","a") or die ("Error al crear el archivo prd_real_plan");
                         $ar_prd_real_plan_h=fopen("historico_txt/brv_prd_real_plan".$hoy.".txt","a") or die ("Error al crear el archivo prd_real_plan");
                         fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd1);
                         fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd2);
                         fclose($ar_prd_real_plan);
                         fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd1);
                         fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd2);
                         fclose($ar_prd_real_plan_h);
                     }else{
		         if ($num_reg==pg_num_rows($resultado_prd)){
                             $ar_prd_real_plan=fopen("archivos_txt/brv_prd_real_plan.txt","w+") or die ("Error al crear el archivo prd_real_plan");
                             $ar_prd_real_plan_h=fopen("historico_txt/brv_prd_real_plan".$hoy.".txt","w+") or die ("Error al crear el archivo prd_real_plan");
                             fwrite($ar_prd_real_plan,$linea_prd1);
                             fwrite($ar_prd_real_plan,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan);
                             fwrite($ar_prd_real_plan_h,$linea_prd1);
                             fwrite($ar_prd_real_plan_h,PHP_EOL.$linea_prd2);
                             fclose($ar_prd_real_plan_h);
                         }else{
                             $ar_prd_real_plan=fopen("archivos_txt/brv_prd_real_plan.txt","a+") or die ("Error al crear el archivo prd_real_plan");
                             $ar_prd_real_plan_h=fopen("historico_txt/brv_prd_real_plan".$hoy.".txt","a+") or die ("Error al crear el archivo prd_real_plan");
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
                         $ar_inv_real_plan=fopen("archivos_txt/brv_inv_real_plan.txt","a") or die ("Error al crear el archivo inv_real_plan");
                         $ar_inv_real_plan_h=fopen("historico_txt/brv_inv_real_plan".$hoy.".txt","a") or die ("Error al crear el archivo inv_real_plan");
                         fwrite($ar_inv_real_plan,PHP_EOL.$linea_inv);
                         fclose($ar_inv_real_plan);
                         fwrite($ar_inv_real_plan_h,PHP_EOL.$linea_inv);
                         fclose($ar_inv_real_plan_h);
                    }else{
 	      		 if ($num_reg==pg_num_rows($resultado_inv)){
			 	$ar_inv_real_plan=fopen("archivos_txt/brv_inv_real_plan.txt","w+") or die ("Error al crear el archivo inv_real_plan");
                         	$ar_inv_real_plan_h=fopen("historico_txt/brv_inv_real_plan".$hoy.".txt","w+") or die ("Error al crear el archivo inv_real_plan");
                         fwrite($ar_inv_real_plan,$linea_inv);
                         fclose($ar_inv_real_plan);
                         fwrite($ar_inv_real_plan_h,$linea_inv);
                         fclose($ar_inv_real_plan_h);
                         }else{ 
			 	$ar_inv_real_plan=fopen("archivos_txt/brv_inv_real_plan.txt","a+") or die ("Error al crear el archivo inv_real_plan");
                         	$ar_inv_real_plan_h=fopen("historico_txt/brv_inv_real_plan".$hoy.".txt","a+") or die ("Error al crear el archivo inv_real_plan");
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
                          $ar_desp_real_plan=fopen("archivos_txt/brv_desp_real_plan.txt","a") or die ("Error al crear el archivo desp_real_plan");
                          $ar_desp_real_plan_h=fopen("historico_txt/brv_desp_real_plan".$hoy.".txt","a") or die ("Error al crear el archivo desp_real_plan");
                          fwrite($ar_desp_real_plan,PHP_EOL.$linea_desp);
                          fclose($ar_desp_real_plan);
                          fwrite($ar_desp_real_plan_h,PHP_EOL.$linea_desp);
                          fclose($ar_desp_real_plan_h);
                      }else{
                          if ($num_reg==pg_num_rows($resultado_desp)){
			  	$ar_desp_real_plan=fopen("archivos_txt/brv_desp_real_plan.txt","w+") or die ("Error al crear el archivo desp_real_plan");
                          	$ar_desp_real_plan_h=fopen("historico_txt/brv_desp_real_plan".$hoy.".txt","w+") or die ("Error al crear el archivo desp_real_plan");
                          fwrite($ar_desp_real_plan,$linea_desp);
                          fclose($ar_desp_real_plan);
                          fwrite($ar_desp_real_plan_h,$linea_desp);
                          fclose($ar_desp_real_plan_h);
                          }else{
			  	$ar_desp_real_plan=fopen("archivos_txt/brv_desp_real_plan.txt","a+") or die ("Error al crear el archivo desp_real_plan");
                          	$ar_desp_real_plan_h=fopen("historico_txt/brv_desp_real_plan".$hoy.".txt","a+") or die ("Error al crear el archivo desp_real_plan");
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
}


$cn=  Conectarse();
if (isset($_SESSION['user_session_sio'])) {
	//$cn=  Conectarse();
        if ($accion=='I'){
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
	    $queryy .= "NOW(), ";		
	    $queryy .= "'" . $fecha."', ";
	    $queryy .= "'".$fecha_prd."', ";
	    $queryy .= "'".$cod_linea."', ";
	    $queryy .= "'" . $tn_real."', ";
	    $queryy .= "'" . $tn_prog."', ";
	    $queryy .= "'" . $tn_desvio."', ";
	    $queryy .= "'" . $tn_real_acum."', ";
	    $queryy .= "'" . $tn_prog_acum."', ";
	    $queryy .= "'" . $tn_desvio_acum."', ";
	    $queryy .= "'" . $tn_proy."', ";
	    $queryy .= "'" . $tn_prog_orig."', ";
	    $queryy .= "'" . $tn_plan_mes."', ";
	    $queryy .= "'" . $tn_var_anual."' ";							
	    $queryy .= ");";
		
	    $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
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
		$queryy .= "'".$fecha."', ";
		$queryy .= "'".$fecha_prd."', ";
		$queryy .= "'".$mercado."', ";
		$queryy .= "'".$producto."',";
		$queryy .= "'".$tn_inv_inicial."', ";
		$queryy .= "'".$tn_inv_real."', ";
		$queryy .= "'".$tn_inv_plan_anual."'";
		$queryy .= ");";
		$resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
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
		     $queryy .= "'".$fecha."', ";
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
		     $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
		     $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
		     if (!$resultado) {
		          echo "0";
		          die("Ocurrió un error.\n ");
		     }else{
		          $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Nuevo Registro de Despacho ".$fecha_prd."')");
		         echo "1";
		     }
                }
            }

   	   llenar_txt($cn,$fecha_inicio,$hoy,$fecha_prd,$apertura_archivo);
        
	}else{  // SI EL VALOR DE LA VARIABLE "ACCION" ES "U" DE UPDATE
            
	    $queryy = "UPDATE prd_real_plan SET ";
            $queryy .= "fecha_reg= NOW(), ";
            $queryy .= "tn_real='" . $tn_real."', ";
            $queryy .= "tn_prog='" . $tn_prog."', ";
            $queryy .= "tn_desvio='" . $tn_desvio."', ";
            $queryy .= "tn_real_acum='" . $tn_real_acum."', ";
            $queryy .= "tn_prog_acum='" . $tn_prog_acum."', ";
            $queryy .= "tn_desvio_acum='" . $tn_desvio_acum."', ";
            $queryy .= "tn_proy='" . $tn_proy."', ";
            $queryy .= "tn_prog_orig='" . $tn_prog_orig."', ";
            $queryy .= "tn_plan_mes='" . $tn_plan_mes."', ";
            $queryy .= "tn_var_anual='" . $tn_var_anual."' ";
            $queryy .= " WHERE ";
            $queryy .= "fecha_produccion= '".$fecha."' AND fecha_prd='".$fecha_prd."' AND cod_linea='".$cod_linea."' ";

            $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
            $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
            if (!$resultado) {
                echo "0";
                die("Ocurrió un error.\n ");
            }else{
                $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Modificacion del Registro de Produccion ".$fecha_prd."')");
                $queryy = "UPDATE inv_real_plan SET ";
                $queryy .= "fecha_reg=NOW(),";
                $queryy .= "tn_inv_inicial= '".$tn_inv_inicial."',";
                $queryy .= "tn_inv_real='".$tn_inv_real."', ";
                $queryy .= "tn_inv_plan_anual='".$tn_inv_plan_anual."'";
                $queryy .= " WHERE ";
                $queryy .= " fecha_produccion='".$fecha."' AND fecha_inventario='".$fecha_prd."' AND mercado='".$mercado."' AND producto='".$producto."'";
                
                $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
                $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
                if (!$resultado) {
                     echo "0";
                     die("Ocurrió un error.\n ");
                }else{
                     $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Modificar Registro de Inventario ".$fecha_prd."')");
                     $queryy = "UPDATE desp_real_plan SET ";
                     $queryy .= "fecha_reg=NOW(), ";
                     $queryy .= "tn_desp_real='".$tn_desp_real."', ";
                     $queryy .= "tn_desp_mes='".$tn_desp_mes."', ";
                     $queryy .= "tn_desp_plan_mes='".$tn_desp_plan_mes."', ";
                     $queryy .= "tn_desp_var_mes='".$tn_desp_var_mes."', ";
                     $queryy .= "tn_estim_cierre='".$tn_estim_cierre."', ";
                     $queryy .= "tn_original_plan='".$tn_original_plan."', ";
                     $queryy .= "tn_var_cierre='".$tn_var_cierre."', ";
                     $queryy .= "tn_desp_real_acum='".$tn_desp_real_acum."',";
                     $queryy .= "tn_desp_plan_acum='".$tn_desp_plan_acum."', ";
                     $queryy .= "tn_desp_var_acum='".$tn_desp_var_acum."',";
                     $queryy .= "tn_desp_pea='".$tn_desp_pea."'";
                     $queryy .= " WHERE ";
                     $queryy .= " fecha_produccion='".$fecha_prd."' AND fecha_despacho='".$fecha_prd."' AND mercado='".$mercado."' AND producto='".$producto."'";


                    $resultado = pg_query($cn, $queryy) or die("Error en la Consulta SQL:" . $queryy);
                     $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
                     if (!$resultado) {
                          echo "0";
                          die("Ocurrió un error.\n ");
                     }else{
                          $auditoria = pg_query($cn,"INSERT INTO auditorias (login, fecha , operacion) VALUES ('".$_SESSION['user_session_sio']."', NOW(), 'Modifica Registro de Despacho ".$fecha_prd."')");
                         echo "1";
                     }
                }
            }
   	   llenar_txt($cn,$fecha_inicio,$hoy,$fecha_prd,$apertura_archivo);

        } //FIN DE LA CONDOICIONAL "ACCION"
		pg_close($cn);
}else
    echo "Su session esta cerrada";
?>
