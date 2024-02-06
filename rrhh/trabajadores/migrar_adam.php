<?php
require_once('libs/conexion.php');
$cn=  Conectarse_posgres();     
$sql = "SELECT trabajador FROM trabajadores";
$res = pg_query($cn,$sql);
$rows3 = pg_num_rows($res);
$i=0;
$trabajador=array();
while ($row = pg_fetch_array($res)){
    $trabajador[$i]=$row['trabajador'];
    $i++;
}
pg_free_result($res);

/*
$sql = "SELECT trabajador FROM trabajadores_grales";
$resg = pg_query($cn,$sql);
$rows4 = pg_num_rows($resg);
$i=0;
$trabajador_g=array();
while ($row = pg_fetch_array($resg)){
    $trabajador_g[$i]=$row['trabajador'];
    $i++;
}
pg_free_result($resg);
*/

$queryTrabajadores="SELECT TRIM(T.TRABAJADOR) as CEDULA, T.REGISTRO_FISCAL,  T.NOMBRE,  decode(T.SEXO,1,'M','F') as SEXO,
to_char(T.FECHA_NACIMIENTO, 'yyyy-mm-dd') as fecha_nacimiento, T.DOMICILIO, T.DOMICILIO2, T.POBLACION,
T.ESTADO_PROVINCIA, T.PAIS, T.CODIGO_POSTAL, T.CALLES_ALEDANAS as CALAL, T.TELEFONO_PARTICULAR,
T.REG_SEGURO_SOCIAL, T.DOMICILIO3, T.E_MAIL as CORREO, TRIM(V.CENTRO_COSTO) CENTRO_COSTO, 
decode(I.NACIONALIDAD,'VEN', 'V','VENEZOLANA','V','VN1', 'V','VN2', 'V' ,'E') as TIPO_DOCUMENTO,
V.NOMBRE NOMBRES, V.APELLIDOS,  decode(rtrim(R.DATO),'SOL', 'S', 'CAS','C' ,'DIV','D','VIU','V','S') as EDOCIVIL, ltrim(V.TRABAJADOR_SUP) as SUPERVISOR
FROM TRABAJADORES T 
INNER JOIN VW_DATOS_TRAB_SITTWEB_SID V ON T.TRABAJADOR = V.TRABAJADOR
INNER JOIN INF_COMPLEMENTARIA I ON T.TRABAJADOR=I.TRABAJADOR
LEFT OUTER JOIN REL_TRAB_AGR R ON (R.TRABAJADOR = T.TRABAJADOR AND R.AGRUPACION='EDOCIVIL') ORDER BY TO_NUMBER(T.TRABAJADOR)";

$queryTrabajadoresGRALES="SELECT TRIM(T.TRABAJADOR) CEDULA, to_char(G.FECHA_INGRESO, 'yyyy-mm-dd') as FECHA_INGRESO,  
to_char(G.FECHA_ANTIGUEDAD, 'yyyy-mm-dd') as FECHA_ANTIGUEDAD,
 to_char(G.FECHA_BAJA, 'yyyy-mm-dd') as FECHA_BAJA,  to_char(G.FECHA_VTO_CONTRATO, 'yyyy-mm-dd') as FECHA_VTO_CONTRATO,
 G.CAUSA_BAJA, G.RELACION_LABORAL,G.TELEFONO_OFICINA, G.EXTENSION_TELEFONICA, G.CLASE_NOMINA,G.SISTEMA_ANTIGUEDAD,
 G.SISTEMA_HORARIO, G.TURNO, G.FORMA_PAGO, G.SIT_TRABAJADOR, I.DES_DOCUMENTO_01 TIPO_SANGRE, TRIM(V.DESC_PUESTO) DESC_PUESTO,
 R.CUENTA_DEPOSITO, ltrim(V.TRABAJADOR_SUP) as SUPERVISOR
FROM TRABAJADORES T
INNER JOIN VW_DATOS_TRAB_SITTWEB_SID V ON T.TRABAJADOR= V.TRABAJADOR
INNER JOIN INF_COMPLEMENTARIA I ON T.TRABAJADOR=I.TRABAJADOR
INNER JOIN REL_TRAB_INS_DEP R ON (T.TRABAJADOR=R.TRABAJADOR AND R.INS_DEP_PRINCIPAL=1)
INNER JOIN TRABAJADORES_GRALES G ON T.TRABAJADOR=G.TRABAJADOR ORDER BY TO_NUMBER(T.TRABAJADOR)";

/*
$E_MAIL=array();

$REGISTRO_FISCAL=array();
$NOMBRE=array();
$SEXO=array();
$FECHA_NACIMIENTO=array();
$DOMICILIO=array();
$DOMICILIO2=array();
$POBLACION=array();
$ESTADO_PROVINCIA=array();
$PAIS=array();
$CODIGO_POSTAL=array();
$CALAL=array();
$TELEFONO_PARTICULAR=array();
$REG_SEGURO_SOCIAL=array();
$DOMICILIO3=array();

$CENTRO_COSTO=array();
$TIPO_DOCUMENTO=array();
$NOMBRES=array();
$APELLIDOS=array();
$EDOCIVIL=array();
*/
$CEDULA=array();
$CEDULA_GRALES=array();
$FECHA_INGRESO=array();
$FECHA_ANTIGUEDAD=array();
$FECHA_BAJA=array();
$FECHA_VTO_CONTRATO=array();
$CAUSA_BAJA=array();
$RELACION_LABORAL=array();
$TELEFONO_OFICINA=array();
$EXTENSION_TELEFONICA=array();
$CLASE_NOMINA=array();
$SISTEMA_ANTIGUEDAD=array();
$SISTEMA_HORARIO=array();
$TURNO=array();
$FORMA_PAGO=array();
$SIT_TRABAJADOR=array();
$TIPO_SANGRE=array();
$DESC_PUESTO=array();
$CUENTA_DEPOSITO=array();

$conn = oci_connect('matlux', 'lux1705', '10.50.188.65/mprd.briqven.com.ve');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, $queryTrabajadores);
oci_execute($stid);
$rows1=oci_num_rows($stid);
$dar_alta=array();
$altas=array();
$cant_altas=0;
$sqlp = "delete from temp_trabajadores";
$delp = pg_query($cn,$sqlp);
//while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {

$CALAL=isset($row['CALAL'])?$row['CALAL']:"";
$CODIGO_POSTAL=isset($row['CODIGO_POSTAL'])?$row['CODIGO_POSTAL']:"";
$TELEFONO_PARTICULAR=isset($row['TELEFONO_PARTICULAR'])?$row['TELEFONO_PARTICULAR']:"";
$REG_SEGURO_SOCIAL=isset($row['REG_SEGURO_SOCIAL'])?$row['REG_SEGURO_SOCIAL']:"";
$DOMICILIO3=isset($row['DOMICILIO3'])?$row['DOMICILIO3']:"";
$EDOCIVIL=isset($row['EDOCIVIL'])?$row['EDOCIVIL']:"";
$SUP=isset($row['SUPERVISOR'])?$row['SUPERVISOR']:""; 
$E_MAIL=isset($row['CORREO'])?$row['CORREO']:""; 
 
  $insertTrabajador_temp="INSERT INTO temp_trabajadores(
                trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, 
                domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, 
                calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, 
                e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil, supervisor)
        VALUES ('".$row['CEDULA']."','".$row['REGISTRO_FISCAL']."', '".$row['NOMBRE']."', '".$row['SEXO']."', '".$row['FECHA_NACIMIENTO']."', '".$row['DOMICILIO']."', '".$row['DOMICILIO2']."', '".$row['POBLACION']."', '".$row['ESTADO_PROVINCIA']."', '".$row['PAIS']."', '".$CODIGO_POSTAL."', 
                '".$CALAL."', '".$TELEFONO_PARTICULAR."', '".$REG_SEGURO_SOCIAL."', '".$DOMICILIO3."', '".$E_MAIL."', ".$row['CENTRO_COSTO'].", '".$row['TIPO_DOCUMENTO']."', '".$row['NOMBRES']."', '".$row['APELLIDOS']."', '".$EDOCIVIL."', '".$SUP."');";
$trab_temp = pg_query($cn,$insertTrabajador_temp) or die("Error en la Consulta SQL: ".$insertTrabajador_temp);
}
//-------------------------------------------------------------------------------------- 

$sqlp = "select trabajador from temp_trabajadores";
$resp = pg_query($cn,$sqlp);
$i=0;
while ($rowp = pg_fetch_array($resp)){     

      $CEDULA[$i]=$rowp['trabajador'];
      /* 
       $E_MAIL[$i]=$row['CORREO'];
       
       $REGISTRO_FISCAL[$i]=isset($row['REGISTRO_FISCAL'])?$row['REGISTRO_FISCAL']:"";
       $NOMBRE[$i]=$row['NOMBRE'];
       $SEXO[$i]=$row['SEXO'];
       $FECHA_NACIMIENTO[$i]=$row['FECHA_NACIMIENTO'];
       $DOMICILIO[$i]=$row['DOMICILIO'];
       $DOMICILIO2[$i]=$row['DOMICILIO2'];
       $POBLACION[$i]=$row['DOMICILIO2'];
       $ESTADO_PROVINCIA[$i]=$row['ESTADO_PROVINCIA'];
       $PAIS[$i]=$row['PAIS'];
       $CODIGO_POSTAL[$i]=isset($row['CODIGO_POSTAL'])?$row['CODIGO_POSTAL']:"";
       $CALAL[$i]=isset($row['CALAL'])?$row['CALAL']:"";
       $TELEFONO_PARTICULAR[$i]=isset($row['TELEFONO_PARTICULAR'])?$row['TELEFONO_PARTICULAR']:"";
       $REG_SEGURO_SOCIAL[$i]=isset($row['REG_SEGURO_SOCIAL'])?$row['REG_SEGURO_SOCIAL']:"";
       $DOMICILIO3[$i]=isset($row['DOMICILIO3'])?$row['DOMICILIO3']:"";       
       $CENTRO_COSTO[$i]=$row['CENTRO_COSTO'];
       $TIPO_DOCUMENTO[$i]=$row['TIPO_DOCUMENTO'];
       $NOMBRES[$i]=$row['NOMBRES'];
       $APELLIDOS[$i]=$row['APELLIDOS'];
       $EDOCIVIL[$i]=isset($row['EDOCIVIL'])?$row['EDOCIVIL']:"";
       $SUP[$i]= isset($row['SUPERVISOR'])?$row['SUPERVISOR']:"";     
       */      
        if (!in_array($rowp['trabajador'],$trabajador)){
            $dar_alta[$cant_altas]=$i;
            $altas[$cant_altas]=$rowp['trabajador'];
            $cant_altas++;        
        }
        $i++;       
}

$dtid = oci_parse($conn, $queryTrabajadoresGRALES);
oci_execute($dtid);
$rows2=oci_num_rows($dtid);
$i=0;
//while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
while (($row = oci_fetch_array($dtid, OCI_BOTH)) != false) {     
       $CEDULA_GRALES[$i]=$row['CEDULA'];
       $FECHA_INGRESO[$i]=$row['FECHA_INGRESO'];
       $FECHA_ANTIGUEDAD[$i]=isset($row['FECHA_ANTIGUEDAD'])?$row['FECHA_ANTIGUEDAD']:"";;
       if ($FECHA_ANTIGUEDAD[$i]=="")
            $FECHA_ANTIGUEDAD[$i]="NULL";
        else
            $FECHA_ANTIGUEDAD[$i]="'".$FECHA_ANTIGUEDAD[$i]."'";
       $FECHA_BAJA[$i]=isset($row['FECHA_BAJA'])?$row['FECHA_BAJA']:"";
       if ($FECHA_BAJA[$i]=="")
            $FECHA_BAJA[$i]="NULL";
        else
            $FECHA_BAJA[$i]="'".$FECHA_BAJA[$i]."'";
       $FECHA_VTO_CONTRATO[$i]=isset($row['FECHA_VTO_CONTRATO'])?$row['FECHA_VTO_CONTRATO']:"";
       if ($FECHA_VTO_CONTRATO[$i]=="")
            $FECHA_VTO_CONTRATO[$i]="NULL";
        else
            $FECHA_VTO_CONTRATO[$i]="'".$FECHA_VTO_CONTRATO[$i]."'";
       $CAUSA_BAJA[$i]=isset($row['CAUSA_BAJA'])?$row['CAUSA_BAJA']:"";;
       $RELACION_LABORAL[$i]=$row['RELACION_LABORAL'];
       $TELEFONO_OFICINA[$i]=isset($row['TELEFONO_OFICINA'])?$row['TELEFONO_OFICINA']:"";;
       $EXTENSION_TELEFONICA[$i]=isset($row['EXTENSION_TELEFONICA'])?$row['EXTENSION_TELEFONICA']:"NULL";;
       $CLASE_NOMINA[$i]=$row['CLASE_NOMINA'];
       $SISTEMA_ANTIGUEDAD[$i]=$row['SISTEMA_ANTIGUEDAD'];
       $SISTEMA_HORARIO[$i]=$row['SISTEMA_HORARIO'];
       $TURNO[$i]=$row['TURNO'];
       $FORMA_PAGO[$i]=$row['FORMA_PAGO'];
       $SIT_TRABAJADOR[$i]=$row['SIT_TRABAJADOR'];
       $TIPO_SANGRE[$i]=$row['TIPO_SANGRE'];
       $DESC_PUESTO[$i]=$row['DESC_PUESTO'];
       $CUENTA_DEPOSITO[$i]=$row['CUENTA_DEPOSITO'];
       
/*
       if (!in_array($CEDULA_GRALES[$i],$trabajador_g)){
            $insertTrab_Grales="INSERT INTO trabajadores_grales(trabajador, fecha_ingreso, fecha_antiguedad, fecha_baja, fecha_vto_contrato, 
                causa_baja, relacion_laboral, telefono_oficina, extension_telefonica, 
                clase_nomina, sistema_antiguedad, sistema_horario, turno, forma_pago, 
                sit_trabajador, grupo_sanguinio, cargo, ctadeposito) VALUES ('".$CEDULA_GRALES[$i]."', '".$FECHA_INGRESO[$i]."', ".$FECHA_ANTIGUEDAD[$i].", ".$FECHA_BAJA[$i].", ".$FECHA_VTO_CONTRATO[$i].", '".$CAUSA_BAJA[$i]."', '".$RELACION_LABORAL[$i]."', '".$TELEFONO_OFICINA[$i]."', ".$EXTENSION_TELEFONICA[$i].", '".$CLASE_NOMINA[$i]."', ".$SISTEMA_ANTIGUEDAD[$i].", ".$SISTEMA_HORARIO[$i].", ".$TURNO[$i].", ".$FORMA_PAGO[$i].", ".$SIT_TRABAJADOR[$i].", '".$TIPO_SANGRE[$i]."', '".$DESC_PUESTO[$i]."', '".$CUENTA_DEPOSITO[$i]."');";
            $dar_alta_grales = pg_query($cn,$insertTrab_Grales);
            pg_free_result($dar_alta_grales);
       }
*/       

           if (!in_array($CEDULA_GRALES[$i],$altas)){            
                $query_actualizar="UPDATE trabajadores_grales SET fecha_ingreso='".$FECHA_INGRESO[$i]."', fecha_antiguedad=".$FECHA_ANTIGUEDAD[$i].", fecha_baja=".$FECHA_BAJA[$i].", fecha_vto_contrato=".$FECHA_VTO_CONTRATO[$i].", causa_baja='".$CAUSA_BAJA[$i]."', relacion_laboral='".$RELACION_LABORAL[$i]."', telefono_oficina='".$TELEFONO_OFICINA[$i]."', extension_telefonica=".$EXTENSION_TELEFONICA[$i].", clase_nomina='".$CLASE_NOMINA[$i]."', sistema_antiguedad=".$SISTEMA_ANTIGUEDAD[$i].", sistema_horario=".$SISTEMA_HORARIO[$i].", turno=".$TURNO[$i].", forma_pago=".$FORMA_PAGO[$i].", sit_trabajador=".$SIT_TRABAJADOR[$i].", grupo_sanguinio='".$TIPO_SANGRE[$i]."', cargo='".$DESC_PUESTO[$i]."', ctadeposito='".$CUENTA_DEPOSITO[$i]."' WHERE trabajador='".$CEDULA_GRALES[$i]."';";
                $res_update = pg_query($cn,$query_actualizar);
                pg_free_result($res_update); 
           }

       $i++;
}

$dar_baja="('";
for ($i=0; $i<=$rows3-1; $i++){
    if (!in_array($trabajador[$i], $CEDULA))
        $dar_baja=$dar_baja.$trabajador[$i]."','";                
}

if ($dar_baja!="('"){
    $dar_baja = substr($dar_baja, 0, -2);
    $dar_baja=$dar_baja.")";
   
    
    $Querybajas="SELECT TRIM(TRABAJADOR) CEDULA, FECHA_BAJA, CAUSA_BAJA FROM TRABAJADORES_GRALES WHERE TRIM(TRABAJADOR) IN ".$dar_baja;
    
    $ptid = oci_parse($conn, $Querybajas);
    oci_execute($ptid);
    while (($row = oci_fetch_array($ptid, OCI_BOTH)) != false) { 
        $trab_baja=$row['CEDULA'];
        $fec_baja=isset($row['FECHA_BAJA'])?"'".$row['FECHA_BAJA']."'":"NULL";
        $cau_baja=isset($row['CAUSA_BAJA'])?"'".$row['CAUSA_BAJA']."'":"NULL";
        
        $query_dar_baja="UPDATE trabajadores_grales SET sit_trabajador = 2, fecha_baja=".$fec_baja.", causa_baja=".$cau_baja." WHERE trabajador = '".$trab_baja."'";
        
        $res_dar_baja = pg_query($cn,$query_dar_baja);
        pg_free_result($res_dar_baja);        
    }
    echo $dar_baja;
    oci_free_statement($ptid);
}

if ($cant_altas>0)
  for ($i=0; $i<=$cant_altas-1; $i++){
      /* $insertTrabajador="INSERT INTO trabajadores(
                trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, 
                domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, 
                calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, 
                e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil)
        VALUES ('".$CEDULA[$dar_alta[$i]]."','".$REGISTRO_FISCAL[$dar_alta[$i]]."', '".$NOMBRE[$dar_alta[$i]]."', '".$SEXO[$dar_alta[$i]]."', '".$FECHA_NACIMIENTO[$dar_alta[$i]]."', '".$DOMICILIO[$dar_alta[$i]]."', '".$DOMICILIO2[$dar_alta[$i]]."', '".$POBLACION[$dar_alta[$i]]."', '".$ESTADO_PROVINCIA[$dar_alta[$i]]."', '".$PAIS[$dar_alta[$i]]."', '".$CODIGO_POSTAL[$dar_alta[$i]]."', 
                '".$CALAL[$dar_alta[$i]]."', '".$TELEFONO_PARTICULAR[$dar_alta[$i]]."', '".$REG_SEGURO_SOCIAL[$dar_alta[$i]]."', '".$DOMICILIO3[$dar_alta[$i]]."', '".$E_MAIL[$dar_alta[$i]]."', ".$CENTRO_COSTO[$dar_alta[$i]].", '".$TIPO_DOCUMENTO[$dar_alta[$i]]."', '".$NOMBRES[$dar_alta[$i]]."', '".$APELLIDOS[$dar_alta[$i]]."', '".$EDOCIVIL[$dar_alta[$i]]."');";
        */        

       $j = array_search($CEDULA[$dar_alta[$i]], $CEDULA_GRALES);

       $insertTrab_Grales="INSERT INTO trabajadores_grales(trabajador, fecha_ingreso, fecha_antiguedad, fecha_baja, fecha_vto_contrato, 
                causa_baja, relacion_laboral, telefono_oficina, extension_telefonica, 
                clase_nomina, sistema_antiguedad, sistema_horario, turno, forma_pago, 
                sit_trabajador, grupo_sanguinio, cargo, ctadeposito) VALUES ('".$CEDULA_GRALES[$j]."', '".$FECHA_INGRESO[$j]."', ".$FECHA_ANTIGUEDAD[$j].", ".$FECHA_BAJA[$j].", ".$FECHA_VTO_CONTRATO[$j].", '".$CAUSA_BAJA[$j]."', '".$RELACION_LABORAL[$j]."', '".$TELEFONO_OFICINA[$j]."', ".$EXTENSION_TELEFONICA[$j].", '".$CLASE_NOMINA[$j]."', ".$SISTEMA_ANTIGUEDAD[$j].", ".$SISTEMA_HORARIO[$j].", ".$TURNO[$j].", ".$FORMA_PAGO[$j].", ".$SIT_TRABAJADOR[$j].", '".$TIPO_SANGRE[$j]."', '".$DESC_PUESTO[$j]."', '".$CUENTA_DEPOSITO[$j]."');"; 

        //$dar_alta_trab = pg_query($cn,$insertTrabajador);
        $dar_alta_grales = pg_query($cn,$insertTrab_Grales);
        //pg_free_result($dar_alta_trab);
        pg_free_result($dar_alta_grales);               
}

oci_free_statement($stid);
oci_free_statement($dtid);
oci_close($conn);
pg_close($cn);
?>