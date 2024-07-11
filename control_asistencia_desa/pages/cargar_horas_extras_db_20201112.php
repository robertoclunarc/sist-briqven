<?php
include("../BD/conexion.php");
require('funciones_var.php');
//require_once 'enviodecorreos.php';
session_start();

/*function RestarHoras($horaini,$horafin)
{

    $f1 = new DateTime($horaini);

    $f2 = new DateTime($horafin);

    $d = $f1->diff($f2);

    return $d->format('%H:%I:%S');

}
*/
if (isset($_SESSION['user_session_const']))
{   

    	$link=Conex_Contancia_pgsql();
     
    	$nroreg             = isset($_POST["hddcontador"])?$_POST["hddcontador"]:0; 
    	$fecha_fichada      = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:date("Y")."-".date("m")."-".date("d");
    	$cbotrabajador      = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"null";
       	$hinicio1           = isset($_POST["txthoraini1"])?$_POST["txthoraini1"]:"NULL";
    	$hfinal1            = isset($_POST["txthorafin1"])?$_POST["txthorafin1"]:"NULL";
    	$INST1		    = $hinicio1;
    	$FINST1  	    = $hfinal1;
    	$INST2		    = "NULL";
    	$FINST2 	    = "NULL";
    	$observacion        = isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";
    	$entrada_esperada1  = isset($_POST["entrada_esperada1"])?$_POST["entrada_esperada1"]:"NULL";
    	$salida_esperada1   = isset($_POST["salida_esperada1"])?$_POST["salida_esperada1"]:"NULL";
    	$entrada_real1      = isset($_POST["entrada_real1"])?$_POST["entrada_real1"]:"NULL";
    	$salida_real1       = isset($_POST["salida_real1"])?$_POST["salida_real1"]:"NULL";

	$cedula 	= "'".trim($cbotrabajador)."'";
	$fecha		= "'".$fecha_fichada."'";
	$INST1		= "'".$hinicio1."'";
	$FINST1         = "'".$hfinal1."'"; 
	$INST2          = "NULL";
        $FINST2         = "NULL";
	$COD1           = "08";
	$COD2 		= "0";
	$CS 		= "'".$_SESSION['cedula_session_const']."'";
	$OBS 		= "'".$observacion."'";
        $inicio_st2 	= "00:00";
        $fin_st2 	= "00:00";
        $codigo1 	= "08";
        $codigo2 	= "0";
        
//	echo "<br>cedula: ".$cedula.", fecha: ".$fecha.", INST1: ".$INST1.", FINST1: ".$FINST1.", INST2: ".$INST2.", FINST2: ".$FINST2.", COD1: ".$COD1.", COD2: ".$COD2.", CS: ".$CS.", obs: ".$OBS;
	$conn = Conectarse_sitt();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("EXEC dbo.SW_VALIDA_ST_CEDULA ".$cedula.", ".$fecha.", ".$INST1.", ".$FINST1.", NULL, NULL, ".$COD1.", ".$COD2.", ".$CS.", ".$OBS);
    /*    $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
        $stmt->bindParam(2, $fecha,  PDO::PARAM_STR,10);
        $stmt->bindParam(3, $INST1,  PDO::PARAM_STR,5);
        $stmt->bindParam(4, $FINST1,  PDO::PARAM_STR,5);
        $stmt->bindParam(5, $INST2,  PDO::PARAM_STR,5);
        $stmt->bindParam(6, $FINST2,  PDO::PARAM_STR,5);
        $stmt->bindParam(7, $COD1,  PDO::PARAM_STR,2);
        $stmt->bindParam(8, $COD2,  PDO::PARAM_STR,2);
        $stmt->bindParam(9, $CS, PDO::PARAM_INT,10);
        $stmt->bindParam(10, $OBS,  PDO::PARAM_STR,255);
*/
        $stmt->execute();
        $result_set = $stmt->fetchAll();
//        var_dump($result_set);
        /*Get the current error mode of PDO*/
        $current_error_mode = $conn->getAttribute(PDO::ATTR_ERRMODE);

//echo var_dump;

//print_r($result_set);

//echo "<br>Resultado:".$result_set[0][1]."<br>";


/*echo $current_error_mode["Error"];
*/



/*    
    // CONSULTAMOS LOS VALORES REALES EN SITT
    $sql_sitt="Select entrada_real1, entrada_real2, entrada_real3, entrada_real4, entrada_real5, entrada_real6,
	 	entrada_real7, entrada_real8, entrada_real9, entrada_real10, entrada_real11, entrada_real12,
	 	salida_real1, salida_real2, salida_real3, salida_real4, salida_real5, salida_real6,
	 	salida_real7, salida_real8, salida_real9, salida_real10, salida_real11, salida_real12,
		entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2
    FROM		dbo.SW_Hoja_de_Tiempo_Real
    WHERE		CEDULA='".$cbotrabajador."' AND FECHA='".$fecha_fichada."'";
    
    	$ERROR="";
	$cn=Conectarse_sitt2();
        $stmt1 = $cn->query($sql_sitt);
        $contar = $stmt1->columnCount();
echo $sql_sitt;
//echo "$(\"#txtobservacion\").val(\"" . $sql_sitt . "\");\n" ;
       if($contar == 0){
              $inpt = "No se han encontrado resultados!";

        }else{
		$Error='OK';  $st=0; echo "PASO2";
		while($row_sitt = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
			echo "PASO3";
			$E1=$row_sitt["entrada_real1"];
			$E2=$row_sitt["entrada_real2"];
			$E3=$row_sitt["entrada_real3"];
			$E4=$row_sitt["entrada_real4"];
			$E5=$row_sitt["entrada_real5"];
			$E6=$row_sitt["entrada_real6"];
			$E7=$row_sitt["entrada_real7"];
			$E8=$row_sitt["entrada_real8"];
			$E9=$row_sitt["entrada_real9"];
			$E10=$row_sitt["entrada_real10"];
			$E11=$row_sitt["entrada_real11"];
			$E12=$row_sitt["entrada_real12"];

			$S1=$row_sitt["salida_real1"];
			$S2=$row_sitt["salida_real2"];
			$S3=$row_sitt["salida_real3"];
			$S4=$row_sitt["salida_real4"];
			$S5=$row_sitt["salida_real5"];
			$S6=$row_sitt["salida_real6"];
			$S7=$row_sitt["salida_real7"];
			$S8=$row_sitt["salida_real8"];
			$S9=$row_sitt["salida_real9"];
			$S10=$row_sitt["salida_real10"];
			$S11=$row_sitt["salida_real11"];
			$S12=$row_sitt["salida_real12"];
			
			$ES1=$row_sitt["entrada_esperada1"];
			$ES2=$row_sitt["entrada_esperada2"];
			$SS1=$row_sitt["salida_esperada1"];
			$SS2=$row_sitt["salida_esperada2"];

			if (($ES1 == 'VV:VV') or ($ES1 == 'CS:CS') or ($ES1 == 'SD:SD') or ($ES1  == 'RR:RR')){
				$st=1;
				$Error="Tipo de Esperanza ".$ES1." No Admite Sobretiempo";
			}
			if  (($ES1 == 'LL:LL')  or ($ES1=='PP:PP') OR ($ES1 == 'FF:FF')){ 
	 			$st=2;
			}
/*			if (($INST2  !=  $FINST2 )  AND ($st==0)){
				//IF  ((cast (DateDiff(N, @INST1, @FINST1 ) as decimal (10,2))) < 0){         strtotime ( '+18 minute' , $NuevaFecha ) ;
				IF  (diferencia_hora($INST1,$FINST1) < 0){
					$H=strtotime ( '-1 minute',$FINST1);
					IF (($INST2 >= $INST1 and $INST2 <='23:59') or ($INST2 >= '00:00' and $INST2 <= $H)){
						$ERROR='Coincidencia de Sobretiempos';
						$st=1;
					}ELSE{
						//IF (convert(datetime,@FINST2,108) between convert(datetime,@INST1,108) and convert(datetime,'23:59',108)) or (convert(datetime,@FINST2,108) between convert(datetime,'00:00',108) and dateadd(n,-1,convert(datetime,@FINST1,108)) ){
						IF (($FINST2 >= $INST1 and $FINST2 <= '23:59') or ($FINST2 >='00:00' and $FINST2 <= strtotime ( '-1 minute',$FINST1))){
							$ERROR='Coincidencia de Sobretiempos';
							$st=1;
						}
					}
				}ELSE{
					IF ($INST2 >= $INST1 and $INST2<=$FINST1){
						$ERROR='Coincidencia de Sobretiempos';
						$st=1;
					}ELSE{
						IF ($FINST2 >= $INST1 and $FINST2 <= $FINST1){
							$ERROR='Coincidencia de Sobretiempos';
							$st=1;
						}
					}
				}	
				IF ($st=0){
					IF ($ES2!=''){
						IF  ((diferencia_hora($ES2,$SS2)) < 0){
							IF (((strtotime ('1 minute',$INST2) >= $ES2) and strtotime ('1 minute',$INST2)<= '23:59') or (($INST2 >= '00:00') and ($INST2 <= strtotime ('1 minute',$INST2))) ){
								$ERROR='Sobretiempo 2 Dentro de Esperanza';
								$st=1;
							}
							IF (((strtotime ('1 minute',$FINST2) >= $ES2) and (strtotime ('1 minute',$FINST2) >= '23:59')) or (($FINST2 >= '00:00') and ($FINST2 <= strtotime ('1 minute',$SS2))) ){
								$ERROR='Sobretiempo 2 Dentro de Esperanza';
								$st=1;
							}
						}ELSE{
							//IF (dateadd(n,1,convert(datetime,@INST2,108)) between convert(datetime,@ES2,108) and dateadd(n,-1,convert(datetime,@SS2,108)) ){
							//	SELECT @ERROR='Sobretiempo 2 Dentro de Esperanza'
							//	Select @st=1
							//}
							//IF (dateadd(n,-1,convert(datetime,@FINST2,108)) between convert(datetime,@ES2,108) and dateadd(n,-1,convert(datetime,@SS2,108)) ){
							//	SELECT @ERROR='Sobretiempo 2 Dentro de Esperanza'
							//	Select @st=1
							//}
						}
					}
					IF  (diferencia_hora($ES1,$SS1) < 0){
						IF (((strtotime ('1 minute',$INST2) >= $ES1) and (strtotime ('1 minute',$INST2) <= '23:59')) or (($INST2 >= '00:00') and ($INST2 <= strtotime ('-1 minute',$SS1)))){
							$ERROR='Sobretiempo 2 Dentro de Esperanza';
							$st=1;
						}
						IF (((strtotime ('-1 minute',$FINST2) >= $ES1) and (strtotime ('-1 minute',$FINST2) <= '23:59')) or ($FINST2 >= '00:00' and $FINST2 <= strtotime ('-1 minute',$SS1))){
							$ERROR='Sobretiempo 2 Dentro de Esperanza';
							$st=1;
						}
					}ELSE{
						IF ((strtotime ('1 minute',$INST2) >= $ES1) and (strtotime ('1 minute',$INST2) <= $SS1)){
							$ERROR='Sobretiempo 2 Dentro de Esperanza';
							$st=1;
						}
						IF ((strtotime ('-1 minute',$FINST2) >= $ES1) and (strtotime ('-1 minute',$FINST2) <= trtotime ('-1 minute',$SS1))){
							$ERROR='Sobretiempo 2 Dentro de Esperanza';
							$st=1;
						}
					}
				}



			}

		//}

	echo "<br>".$st;

	IF ($ES2!='')
	{
		IF  (diferencia_hora($ES2, $SS2 ) < 0)	{
			IF ((strtotime ('1 minute',$INST2) >= $ES2  and strtotime ('1 minute',$INST2) <='23:59') or ($INST1 >=  '00:00' and $INST1 <= $SS2))	{
				$st=1;
			}
			IF ((strtotime ('1 minute',$FINST1) >=  $ES2 and strtotime ('-1 minute',$FINST1) <= '23:59') or ($FINST1 >= '00:00' and strtotime ('-1 minute',$SS2)) )	{
				$st=1;
			}
		}ELSE{
			IF (strtotime ('1 minute',$INST1) >= $ES2 and  strtotime ('1 minute',$INST1) <=  strtotime ('-1 minute',$SS2) )	{
				$st=1;
			}
			IF ((strtotime ('-1 minute',$FINST1) >= $ES2) and (strtotime ('-1 minute',$FINST1) <= strtotime ('-1 minute',$SS2) ) )	{
				$st=1;
			}
		}
	}
	IF  (diferencia_hora($ES1, $SS1) < 0){
		IF (((strtotime ('1 minute',$INST1) >= $ES1) and (strtotime ('1 minute',$INST1) <= '23:59')) or ((strtotime ('1 minute',$INST1) >=  '00:00') and strtotime ('-1 minute',$SS1))) {
			$st=1;
		}
		IF (((strtotime ('-1 minute',$FINST1) >= $ES1) and (strtotime ('-1 minute',$FINST1) <= '23:59')) or (($FINST1 >=  '00:00') and ($FINST1 <= strtotime ('-1 minute',$SS1))))	{
			$st=1;
		}
	}ELSE{
		IF ((strtotime ('1 minute',$INST1) >= $ES1) and (strtotime ('1 minute',$INST1) <= ($FINST1 <= strtotime ('-1 minute',$SS1))) )	{
			$st=1;
		}
		IF ((strtotime ('-1 minute',$FINST1)  >= $ES1) and (strtotime ('-1 minute',$FINST1) <= ($FINST1 <= strtotime ('-1 minute',$SS1)))){
			$st=1;
		}
	}


//************************************************************************************************************************************* /
	IF ($E12 !='NULL' AND  $S12!='NULL'){
		IF  (diferencia_hora ($E12, $S12) < 0){
			IF ((($INST2 >= $E12) and ($INST2 <='23:59')) or (($INST2 >= '00:00')  and ($INST2 <= $S12)))	{
				IF (($FINST2 >= $E12 and $FINST2<='23:59') or ($FINST2 >= '00:00'  and $FINST2 <= $S12)) {
					$st=3;
				}
			}
		}ELSE{
			IF (($INST2 >= $E12) and ($INST2>=$S12)) {
				IF (($FINST2 >= $E12) and ($FINST2 <= $S12)) {
					$st=3;
				}
			}
		}
	}

    } //FIN DEL WHILE
	
   } //FIN CONSULTA DE SITT


echo "ST:".$st;


     //echo $hinicio1."<=".$salida_esperada1. " && ".$hfinal1.">".$entrada_esperada1; 

    $sms='';
    if (($hinicio1 < $salida_esperada1) && ($hfinal1 > $entrada_esperada1)){
	echo "PASO1";
        $sms="Error: La hora de inicio debe se mayor que la hora de salida esperada";
    }elseif (($hfinal1>$entrada_esperada1) && ($hfinal1<$salida_esperada1)){
	echo "PASO2";
    	$sms="Error: La hora fin debe ser menor que la entrada Esperada";
    }elseif ($entrada_esperada1=="VV:VV" || $entrada_esperada1=="PP:PP" || $entrada_esperada1=="RR:RR" || $entrada_esperada1=="LL:LL" || $entrada_esperada1=="FF:FF" || $entrada_esperada1=="SD:SD" || $entrada_esperada1=="CS:CS"){
	echo "PASO3";
    	$sms="Error: La Esperanza del trabajador no permite cargarle horas extras";
    }elseif (RestarHoras($hinicio1,$hfinal1)>8){
	echo "PASO4";
    	$sms="Error: No puede tener mas de 8 horas de horas extras";
    }
*/

    $sms=$result_set[0][1];
//echo $sms;
 //   $sms="PRUEBA";
    if ($sms=="OK"){
	    $hoy= date("Y") . "-" . date("m") . "-" . date("d");
		$ced="";
		$query="select * from registro_diario where trabajador in ('".trim($cbotrabajador)."') and fecha='".$fecha_fichada."'";
	//print $query."-".$hinicio1."-".$hfinal1 ;
		$result = ejecutar_query($link, $query) or die("Error en la Consulta SQL: ".$query);
		$numReg = ejecutar_num_rows($result);
		if($numReg==0){
			    $insertConst="INSERT INTO registro_diario (";
			    $insertConst.="trabajador,";
			    $insertConst.="fecha,";
			    $insertConst.="asistio,";
			    $insertConst.="sobre_tiempo,";
			    $insertConst.="cambio_turno,";
			    $insertConst.="comision,";
			    $insertConst.="inasistencia,";
			    $insertConst.="observacion_te,";
			    $insertConst.="fecha_reg,";
			    $insertConst.="trabajador_reg, ";
			    $insertConst.="entrada_te, ";
			    $insertConst.="salida_te, ";
			    $insertConst.="entrada_real1, ";
			    $insertConst.="salida_real1, ";
			    $insertConst.="entrada_esperada1, ";
			    $insertConst.="salida_esperada1, ";
			    $insertConst.="bloqueado ";
			    $insertConst.=") VALUES (";
			    $insertConst.="'".trim($cbotrabajador)."',";
			    $insertConst.="'".$fecha_fichada."',";
			    $insertConst.="'S', ";
			    $insertConst.="'S', ";
			    $insertConst.="'N', ";
			    $insertConst.="'N', ";
			    $insertConst.="0, ";
			    $insertConst.="'".$observacion."', ";
			    $insertConst.="'".$hoy."', ";
			    $insertConst.="'".$_SESSION['user_session_const']."', ";
	                    $insertConst.="'".$hinicio1."',";
	                    $insertConst.="'".$hfinal1."',";
	                    $insertConst.="'".$entrada_real1."',";
	                    $insertConst.="'".$salida_real1."',";
	                    $insertConst.="'".$entrada_esperada1."',";
	                    $insertConst.="'".$salida_esperada1."',";
			    $insertConst.="'0'";
			    $insertConst.=")";
	  	            $mens="Se registraron las horas extras";  
	     }else{
		$insertConst="UPDATE registro_diario SET ";
		$insertConst.="entrada_real1='".$entrada_real1."', ";
		$insertConst.="salida_real1='".$salida_real1."', ";
		$insertConst.="entrada_te='".$hinicio1."', ";
		$insertConst.="salida_te='".$hfinal1."'," ;
		$insertConst.="salida_esperada1='".$salida_esperada1."'," ;
		$insertConst.="entrada_esperada1='".$entrada_esperada1."'," ;
		$insertConst.="observacion_te='".$observacion."' ";
	        $insertConst.="where trabajador = '".trim($cbotrabajador)."' ";
	        $insertConst.="and fecha= '".$fecha_fichada."'";
	        $mens="Se actualizaron las horas extras";  
	      }	
	//	echo $insertConst;
		$result = pg_query($link,$insertConst);
	        if (pg_affected_rows($result)==0)
	        {
	            echo("ERROR");
	            die(" Consulta SQL: ".$insertConst);
	        }
	        $ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
	        pg_close($link);
	        echo $mens;  

	        require("enviodecorreos.php");
	        $asunto="Carga de Horas Extras";
	        $cuerpo="Saludos, Se le cargaron horas extras al trabajador: ".nombre_trabajadores(trim($cbotrabajador)).", portador de la c&eacute;dula de indentidad:".trim($cbotrabajador).", el dia: ".$fecha_fichada.", por concepto de: ".trim($observacion).".";
	        $resp=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmoa@briqven.com.ve","matvxl@briqven.com.ve");
	        $resp2=ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matzem@briqven.com.ve");
		header('Location: cargar_horas_extras.php');;
	}else{

		echo $sms;
	} 
		
}



function diferencia_hora($inicio,$fin){
$date1 = new DateTime($inicio);
$date2 = new DateTime($fin);
$diff = $date1->diff($date2);
// 38 minutes to go [number is variable]
echo ( ($diff->days * 24 ) * 60 ) + ( $diff->i ) . ' minutes';
// passed means if its negative and to go means if its positive
echo ($diff->invert == 1 ) ? ' passed ' : ' to go ';
}




?>
