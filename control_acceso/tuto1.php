<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
function movimientos()
{
    require_once('libs/conexion.php');
    $cn=Conectarse_rrhh();
    $sql1 = "select e_mail FROM trabajadores_activos_con_jefes WHERE supervisor='9158678'";
    $res1 = pg_query($cn,$sql1);
    
    
        while ($row = pg_fetch_array($res1)){
            echo $row['e_mail'].'<BR>';
        }

    
 }
 function sin_foto()
{
    require_once('libs/conexion.php');
    $cn=Conectarse_rrhh();
    $sql1 = "select * FROM trabajadores_activos_con_jefes";
    $res1 = pg_query($cn,$sql1);    
    
        while ($row = pg_fetch_array($res1)){            

            $nombre_fichero = '../intranet/briqven/sites/all/modules/cumples/img/fotcarmat_new/'.$row['trabajador'].'.bmp';

            if (file_exists($nombre_fichero)) {
                echo '<BR>';
            } else {
                echo $row['trabajador'].'|'.$row['e_mail'].'|'.$row['nombres'].' '.$row['apellidos'].'|'.$row['cargo'].'|'.$row['trabajador'].'|'.$row['nombres_jefe'].'|'.$row['descripcion_gerencia'].'<BR>';
            }
        }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
 movimientos();
 //sin_foto();
 */
require("libs/conexion.php");
$cedula = isset($_GET["cedula"])?$_GET["cedula"]:"-1";
$conexion = Conectarse_rrhh();
$conex_ctl_plt = Conectarse();
$query2 = "select a.* from acceso_personal_propio a where a.cedula='".$cedula."' and to_char(fecha_acceso,'YYYY/MM/DD') = to_char(CURRENT_DATE,'YYYY/MM/DD') and fecha_acceso = (select max(fecha_acceso) from acceso_personal_propio p where a.cedula=p.cedula)";
$resultado2 = pg_query($conex_ctl_plt, $query2) or die("Error en la Consulta SQL:".$query);
$numReg2 = pg_num_rows($resultado2);
if ($numReg2>0){
    $fila2=pg_fetch_array($resultado2);
    $direccion2=$fila2["direccion"];
    $trabajador=$fila2["cedula"];
    $cargo=$fila2["cargo"];
    $descripcion_gerencia=$fila2["departamento"];
    $nombres_jefe=$fila2["jefe_inmediato"];
    $nombres=$fila2["nombres"];
    $clase_nomina=$fila2["tipo_personal"];
    $fkmotivo=$fila2["fkmotivo"];
    pg_free_result($resultado2);
    $cont=$numReg2;
}else{
    $query1 = "select t.* from trabajadores_activos_con_jefes t where t.trabajador='" . $cedula . "'";
    $direccion2='ENTRADA';
    $resultado1 = pg_query($conexion, $query1) or die("Error en la Consulta SQL:".$query);
    $numReg1 = pg_num_rows($resultado1);
    $fila1=pg_fetch_array($resultado1);
    $trabajador=$fila1["trabajador"];
    $cargo=$fila1["cargo"];
    $descripcion_gerencia=$fila1["descripcion_gerencia"];
    $nombres_jefe=$fila1["nombres_jefe"];
    $nombres=$fila1["nombres"].' '.$fila1["apellidos"];
    $clase_nomina=$fila1["clase_nomina"];
    pg_free_result($resultado1);
    $cont=$numReg1;
}
$hora = localtime(time(),true);
$conn = Conectarse_sitt();
$sql = "select cedula, CONVERT(VARCHAR(10), fecha, 120) fecha_asist, turno from sw_hoja_de_tiempo_real where cedula=16395343 and CONVERT(VARCHAR(10), fecha, 120)=CONVERT(VARCHAR(10), GETDATE(), 120)";

$stmt = $conn->query($sql);
$col = $stmt->columnCount();
$ced='';
$fecha_asist='';
$turno='';  
while ($row = $stmt->fetch()) {     
     echo 'cedula:'.$row[0].'<br>';
     echo 'fecha:'.$row[1].'<br>';
     echo 'turno:'.$row[2].'.';        
}
?>