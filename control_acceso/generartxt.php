<?php
require_once('libs/conexion.php');
$cn=  Conectarse();
function nombremes($mes){
 switch ($mes) {
    case '01':
        return 'ENERO';
        break;
     case '02':
        return 'FEBRERO';
        break;
     case '03':
        return 'MARZO';
        break;
 case '04':
        return 'ABRIL';
        break; 
 case '05':
        return 'MAYO';
        break;
 case '06':
        return 'JUNIO';
        break;
 case '07':
        return 'JULIO';
        break;
 case '08':
        return 'AGOSTO';
        break;
 case '09':
        return 'SEPTIEMBRE';
        break;
 case '10':
        return 'OCTUBRE';
        break;
 case '11':
        return 'NOVIEMBRE';
        break;
 case '12':
        return 'DICIEMBRE';
        break;
} 
 
} 
$listado=  pg_query($cn,"SELECT a.formato FROM formatotxt a  WHERE mesp='".$_GET['me']."' and anhop='".$_GET['an']."'");

$archivo = 'CESTATICKETSBICENT'.nombremes($_GET['me']).'20'.$_GET['an'].'.txt';
	// forzar la descarga
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=\"$archivo\"");
	while($row = pg_fetch_array($listado, null, PGSQL_ASSOC)) {
            print $row['formato']. "\r\n";	
        }     
        die;
pg_free_result($listado);
?>
