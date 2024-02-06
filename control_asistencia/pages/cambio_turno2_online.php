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
	$finicio    = isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
	$ffin       = isset($_POST["txtffin"])?$_POST["txtffin"]:$finicio;
	$trabajador = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

	$qry = "select fecha, entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2, entrada_real1, entrada_real2, salida_real1, salida_real2,inicio_st1,fin_st1,inicio_dlt1,fin_dlt1 from sw_hoja_de_tiempo_real where fecha between '".$finicio."' and '".$ffin."' and cedula=".$trabajador;
	$qry.=" order by fecha desc";
//	print $qry;

	$query       = "select * from registro_diario where fecha='".$finicio."' and trabajador='".trim($trabajador)."'";
	$fecha       = explode("-",$finicio);
	$anio_nomina = $fecha[0];
	$mes_nomina  = $fecha[1];
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
        $cn=Conectarse_sitt();
        $stmt1 = $cn->query($b);
        $contar = $stmt1->columnCount(); 
      
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '	<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt2 = '<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt3 = '<table width="90%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt = $inpt.'<thead>
            <tr>
<!--                <th width="10%">Fecha</th> -->
                <th width="10%">Entrada Esperada 1</th>                 
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
		                $resultado1=mostrar_esperanza($row['entrada_esperada1']);
		                $resultado2=mostrar_esperanza($row['salida_esperada1']);
                    $inpt .='<tr>';
                    $inpt .='<td><input type="hidden" id="entrada_esperada1" name="entrada_esperada1" value="'.$row['entrada_esperada1'].'"></input><span class="'.$resultado1[0].'">'.$resultado1[1].'</span></td>';
                    $inpt .='<td><input type="hidden" id="salida_esperada1"  name="salida_esperada1"  value="'.$row['salida_esperada1'].'"></input><span class="'.$resultado2[0].'">'.$resultado2[1].'</span></td>';
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
                    $inpt3 .='<td style="color:#27ae60">'.$row['inicio_st1'].'</td>';
                    $inpt3 .='<td style="color:#27ae60">'.$row['fin_st1'].'</td>';
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
	//echo $inpt3;
	
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
