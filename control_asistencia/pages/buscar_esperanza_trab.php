<?php
session_start();

$cedula= isset($_GET['ci'])?$_GET['ci']:'NULL';
$fecinper= isset($_GET['finicio'])?$_GET['finicio']:'NULL';
$fecfinper= isset($_GET['ffin'])?$_GET['ffin']:'NULL';
$codper= isset($_GET['codigoper'])?$_GET['codigoper']:0;
$sh= isset($_GET['t'])?$_GET['t']:0;

$finix = date_create($fecinper);
$fini1 = date_format($finix, 'Y-m-d');

$ffinx = date_create($fecfinper);
$ffin2 = date_format($ffinx, 'Y-m-d');


$qry="select fecha, entrada_esperada1, entrada_esperada2, salida_esperada1, salida_esperada2 FROM sw_hoja_de_tiempo_real WHERE cedula=".$cedula." and fecha between '".$fini1."' and '".$ffin2."' order by 1";

if ($codper!=0 && $codper!='' && $sh!=0 && $sh!='' && $cedula!='NULL' && strtotime($ffin2)>=strtotime($fini1))
    buscar($qry,$codper,$sh);
else
   echo 'horas'.'-'.date("G:i").'-'.date("G:i");          
       
function buscar($b,$codper,$sh){
   //echo $b;
   include("../BD/conexion.php");
   require_once('funciones_var.php');
   $cn1=Conectarse_sitt();
    
    $stmt12 = $cn1->query($b);
    //$stmt12 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    //$stmt12->execute();
    $contar = $stmt12->columnCount(); 
   //$contar=1;
    $inpt='';
    if($contar == 0){
          $inpt = "No se han encontrado resultados!";
          
    }else{            
            $h1 = date("G:i");            
            $h2 = date("G:i");
            while($row = $stmt12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
              $ee1=$row['entrada_esperada1'];
              $se1=$row['salida_esperada1'];
              $ee2=$row['entrada_esperada2'];
              $se2=$row['salida_esperada2'];
              $fecha=substr($row['fecha'], 0,10);
              $os = array("PP:PP", "VV:VV", "FF:FF", "RR:RR", "LL:LL", "SD:SD");
              $np = array(32, 33, 34, 35, 36, 42, 43);
              if (in_array($ee1, $os)) {
                  
                  switch ($ee1) {
                    case "PP:PP": $inpt.=' Permiso duplicado para el dia '.$fecha.' <br>';
                      break;
                    case "VV:VV": $inpt.=' El trabajador tiene Vacacion para el dia '.$fecha.' <br>';
                      break;                        
                    case "FF:FF": $inpt.= in_array($codper, $np)?'':' Dia feriado para el dia '.$fecha.' <br>';
                      break;
                    case "RR:RR": $inpt.=' El trabajador esta de Reposo para el dia '.$fecha.' <br>';
                      break;
                    case "LL:LL": $inpt.= in_array($codper, $np)?'':' El trabajador esta libre para el dia '.$fecha.' <br>';
                      break;
                    case "SD:SD": $inpt.=' El trabajador esta suspendido para el dia '.$fecha.' <br>';
                      break;
                                                
                  }
                  
              }
              else{
                    
                   $h1=$ee1;
                   if ($sh==13)
                      $h2=$se2;
                   else
                      $h2=$se1;                    
              }
            }
            if ($inpt=='')
                $inpt= 'horas'.'-'.$h1.'-'.$h2;
              

        }
  
    echo $inpt;
    //print_r($row);
}         
?>