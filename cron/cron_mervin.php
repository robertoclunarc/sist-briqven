<?php
///////////////////SERVICIO MEDICO///////////////////////////////////////////////////
/*--------MIGRAR DESDE ADAM NUEVO INGRESO--------------------------------------------*/
function migrar_pacientes($cn_serviciomedico, $cn_adam){
try {
    if (!$cn_adam) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $cedulas='(';
    $sqlp = "select ci from tbl_pacientes";
    $resp = pg_query($cn_serviciomedico,$sqlp);
    while ($rowp = pg_fetch_array($resp)){
       $cedulas=$cedulas.$rowp['ci'].',';   
    }
    $cedulas=$cedulas.'0)';

    $stid = oci_parse($cn_adam, "select ltrim(t.trabajador) as ci, substr(t.nombre,instr(t.nombre,'/',1,2)+1) as  nombre,  translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/','. ') as  apellido , to_char(t.fecha_nacimiento, 'yyyy-mm-dd') as fecha_nacimiento,
     decode(t.sexo,1,'M','F') as sexo, v.desc_puesto as cargo, g.turno, to_char(g.fecha_ingreso, 'yyyy-mm-dd') as antiguedad, to_char(g.fecha_ingreso, 'yyyy-mm-dd') as fecha_ingreso,
     i.des_documento_01 as tipo_sangre, i.lugar_nacimiento, decode(rtrim(r.dato),'SOL', 'Solter@', 'CAS','Casad@' ,'DIV','Divorciad@','VIU','Viud@','Soltera@') as edo_civil,
     i.nacionalidad,  g.telefono_oficina as telefono,
    substr(domicilio || ' ' || domicilio2 || ' ' || poblacion || ' ' || estado_provincia,1,100) as direccion_hab, rtrim(v.centro_costo) as centro_costo, ltrim(v.trabajador_sup) as supervisor
    from VW_DATOS_TRAB_SITTWEB_SID v, trabajadores t, inf_complementaria i,rel_trab_agr r,trabajadores_grales g where r.agrupacion='EDOCIVIL' and g.sit_trabajador=1 and v.trabajador=t.trabajador and t.trabajador=i.trabajador and t.trabajador=r.trabajador
    and t.trabajador=g.trabajador and to_number(t.trabajador) not in ".$cedulas);
    oci_execute($stid);

    $sql1 = "select uid, ccosto from tbl_departamentos";
    $res1 = pg_query($cn_serviciomedico,$sql1);
    $i=0;
    $cc = array();
    $de = array();
    while ($row1 = pg_fetch_array($res1)){
       $cc[$i]=$row1['uid'];
       $de[$i]=$row1['ccosto'];
       $i++;
    }
    $entro=false;
    $cont1=0;
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
        $cont1++;
        $entro=true;    
        $p=buscar($de,$row['CENTRO_COSTO']);
        if (isset($row['TELEFONO']))      
            $telefono="'".$row['TELEFONO']."'";
        else
            $telefono="NULL";
        $sql = "INSERT INTO tbl_pacientes(ci ,
      nombre, 
      apellido,
      id_departamento,
      es_contratista,  
      fechanac,
      sexo, 
      cargo,
      turno,   
      antiguedad_puesto ,  
      fecha_ingreso,
      tipo_sangre,
      lugar_nac,
      edo_civil,
      nacionalidad,
      telefono,
      direccion_hab) VALUES ('".$row['CI']."', '".$row['NOMBRE']."', '".$row['APELLIDO']."',".$cc[$p].", FALSE,'".$row['FECHA_NACIMIENTO']."','".$row['SEXO']."','".$row['CARGO']."','".$row['TURNO']."','".$row['ANTIGUEDAD']."','".$row['FECHA_INGRESO']."','".$row['TIPO_SANGRE']."','".$row['LUGAR_NACIMIENTO']."','".$row['EDO_CIVIL']."','".$row['NACIONALIDAD']."',".$telefono.",'".$row['DIRECCION_HAB']."');";
        $res = pg_query($cn_serviciomedico,$sql);
        if (!$res){     
          exit;
        }
    }
    if ($entro){
     $sqli = "INSERT INTO tbl_historia_medica(fecha_apertura, fk_medico, ha_sufrido_accidentes,ha_padecido_enfermeda,uid_paciente) select fecha_ingreso , 4, 'NO','NO', uid_paciente from tbl_pacientes where ci::numeric not in ".$cedulas;
     $resi = pg_query($cn_serviciomedico,$sqli);     
    }
    $reslt = "Finalizado Con Exito! Cantidad Migrados: ".$cont1;
} catch(Exception $e) {
        $reslt = "Error: " . $e->getMessage();
}

return $reslt;

}
/*------------------Actualizar datos de pacientes------------------------*/
function actualizar_pacientes($cn_serviciomedico, $cn_adam){
/*
require_once('../servicio_medico/libs/conexion_2.php');
$cn=  Conectarse2_postgres(); 
$conn = oci_connect('ADAM', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
*/
try{
  if (!$cn_adam) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$cedulas='(';
$sqlp = "select trabajador from v_trabajadores_activos";
$resp = pg_query($cn_serviciomedico,$sqlp);
while ($rowp = pg_fetch_array($resp)){
   $cedulas=$cedulas.$rowp['trabajador'].',';   
}
$cedulas = substr($cedulas, 0, -1);
$cedulas=$cedulas.')';

$stid = oci_parse($cn_adam, "select ltrim(t.trabajador) as ci,  
substr(t.nombre,instr(t.nombre,'/',1,2)+1) as  nombre ,
 translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/','. ') as  apellido ,
 to_char(t.fecha_nacimiento, 'yyyy-mm-dd') as fecha_nacimiento,
 decode(t.sexo,1,'M','F') as sexo,
 v.desc_puesto as cargo,
 g.turno,
to_char(g.fecha_ingreso, 'yyyy-mm-dd') as antiguedad,
 to_char(g.fecha_ingreso, 'yyyy-mm-dd') as fecha_ingreso,
 i.des_documento_01 as tipo_sangre,
 i.lugar_nacimiento,
 decode(rtrim(r.dato),'SOL', 'Solter@', 'CAS','Casad@' ,'DIV','Divorciad@','VIU','Viud@','Soltera@') as edo_civil,
 i.nacionalidad,
 g.telefono_oficina as telefono,
substr(domicilio || ' ' || domicilio2 || ' ' || poblacion || ' ' || estado_provincia,1,100) as direccion_hab,
rtrim(v.centro_costo) as centro_costo
from VW_DATOS_TRAB_SITTWEB_SID v, trabajadores t, inf_complementaria i,rel_trab_agr r,trabajadores_grales g
where 
 r.agrupacion='EDOCIVIL'
and g.sit_trabajador=1
and v.trabajador=t.trabajador
and t.trabajador=i.trabajador
and t.trabajador=r.trabajador
and t.trabajador=g.trabajador and to_number(t.trabajador) in ".$cedulas." order by g.fecha_ingreso");
oci_execute($stid);

$sql1 = "select uid, ccosto from tbl_departamentos";
$res1 = pg_query($cn_serviciomedico,$sql1);
$i=0;
while ($row1 = pg_fetch_array($res1)){
   $cc[$i]=$row1['uid'];
   $de[$i]=$row1['ccosto'];
   $i++;
}
$cont1=0;
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
  $cont1++;

  $p=buscar($de,$row['CENTRO_COSTO']);
  
  if (isset($row['TELEFONO']))      
        $telefono="'".$row['TELEFONO']."'";
   else
        $telefono="NULL";
  $sql = "UPDATE tbl_pacientes SET 
  nombre = '".$row['NOMBRE']."', 
  apellido = '".$row['APELLIDO']."',
  id_departamento = ".$cc[$p].",
  es_contratista = FALSE,  
  fechanac = '".$row['FECHA_NACIMIENTO']."',
  sexo = '".$row['SEXO']."', 
  cargo = '".$row['CARGO']."',
  turno = '".$row['TURNO']."',   
  antiguedad_puesto = '".$row['ANTIGUEDAD']."',  
  fecha_ingreso = '".$row['FECHA_INGRESO']."',
  tipo_sangre = '".$row['TIPO_SANGRE']."',
  lugar_nac = '".$row['LUGAR_NACIMIENTO']."',
  edo_civil = '".$row['EDO_CIVIL']."',
  nacionalidad = '".$row['NACIONALIDAD']."',
  telefono = ".$telefono.",
  direccion_hab = '".$row['DIRECCION_HAB']."' WHERE ci = '".$row['CI']."';";
  $res = pg_query($cn_serviciomedico,$sql);
  if (!$res){       
      exit;
  }
    
}

$reslt =  "Finalizado Con Exito! Cantidad Pacientes Actualizados: ".$cont1;  
} catch(Exception $e) {
        $reslt = "Error: " . $e->getMessage();
}
return $reslt;
}
/*----------BUSCA CCOSTO EN ARREGLO------------------------------------*/
function buscar($ar,$busc){
$j=0;
  while ($j<count($ar)){
    if ($busc==$ar[$j])
      return $j;
    else
      $j++;
  }
}
///////////////////////INTRANET/////////////////////////////////////////////////////
/* --------------Envia correos a todo el pesonal del cumpleanero del dia actual------------- */
function notificar_cumpleaneros_hoy($cn){
    require_once('../intranet/cumples/conexion.php');
    $cn=Conectarse();
    $sql1 = "SELECT  trabajador, nombrecompl, cargo, correo FROM vista_cumples WHERE cuando = 'HOY'";
    $res1 = pg_query($cn,$sql1);
    $cont1=pg_num_rows($res1);
    if ($cont1>0){
        $cedula = array();
        $nombres = array();
        $cargos = array();      
        require_once('enviodecorreos.php');
        while ($row1 = pg_fetch_array($res1)){
            array_push($cedula, $row1['trabajador']);
            array_push($nombres, $row1['nombrecompl']);
            array_push($cargos, $row1['cargo']);            
            ENVIAR_CORREO($cuerpo,'Muchas Felicidades','',$row1['correo'],'', 'intranet@briqven.com.ve', 'matesi.11');
        }
        pg_free_result($res1);
        $sql2 = "SELECT correo FROM personal_activo_con_correo";
        $res2 = pg_query($cn,$sql2);
        while ($row2 = pg_fetch_array($res2)){
            ENVIAR_CORREO($cuerpo,'Cumpleañero(s) de Hoy','',$row2['correo'],'', 'intranet@briqven.com.ve', 'matesi.11');            
        }
        pg_free_result($res2);        
    }
    pg_close($cn);    
    return  "Finalizado Con Exito! Cumples del dia: ".$cont1;
}
/* --------------------------------------------------------------------------------------- */
/////////////////////////////////RRHH/////////////////////////////////////////////
/*-----------------migra los datos del sistema adam a postgresql--------------------------*/
function migrar_trabajadores_adam($cn_rrhh, $cn_adam){
try {
    $sql = "SELECT trabajador FROM trabajadores";
    $res = pg_query($cn_rrhh,$sql);
    $rows3 = pg_num_rows($res);
    $i=0;
    $trabajador=array();
    while ($row = pg_fetch_array($res)){
        $trabajador[$i]=$row['trabajador'];
        $i++;
    }
    pg_free_result($res);

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

    //$conn = oci_connect('ADAM', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
    if (!$cn_adam) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $stid = oci_parse($cn_adam, $queryTrabajadores);
    oci_execute($stid);

    $dar_alta=array();
    $altas=array();
    $cant_altas=0;
    $sqlp = "delete from temp_trabajadores";
    $delp = pg_query($cn_rrhh,$sqlp);

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
    $trab_temp = pg_query($cn_rrhh,$insertTrabajador_temp) or die("Error en la Consulta SQL: ".$insertTrabajador_temp);
    }

    $sqlp = "select trabajador from temp_trabajadores";
    $resp = pg_query($cn_rrhh,$sqlp);
    $i=0;
    while ($rowp = pg_fetch_array($resp)){     

          $CEDULA[$i]=$rowp['trabajador'];
            
            if (!in_array($rowp['trabajador'],$trabajador)){
                $dar_alta[$cant_altas]=$i;
                $altas[$cant_altas]=$rowp['trabajador'];
                $cant_altas++;        
            }
            $i++;       
    }

    $dtid = oci_parse($cn_adam, $queryTrabajadoresGRALES);
    oci_execute($dtid);
    $i=0;
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

               if (!in_array($CEDULA_GRALES[$i],$altas)){            
                    $query_actualizar="UPDATE trabajadores_grales SET fecha_ingreso='".$FECHA_INGRESO[$i]."', fecha_antiguedad=".$FECHA_ANTIGUEDAD[$i].", fecha_baja=".$FECHA_BAJA[$i].", fecha_vto_contrato=".$FECHA_VTO_CONTRATO[$i].", causa_baja='".$CAUSA_BAJA[$i]."', relacion_laboral='".$RELACION_LABORAL[$i]."', telefono_oficina='".$TELEFONO_OFICINA[$i]."', extension_telefonica=".$EXTENSION_TELEFONICA[$i].", clase_nomina='".$CLASE_NOMINA[$i]."', sistema_antiguedad=".$SISTEMA_ANTIGUEDAD[$i].", sistema_horario=".$SISTEMA_HORARIO[$i].", turno=".$TURNO[$i].", forma_pago=".$FORMA_PAGO[$i].", sit_trabajador=".$SIT_TRABAJADOR[$i].", grupo_sanguinio='".$TIPO_SANGRE[$i]."', cargo='".$DESC_PUESTO[$i]."', ctadeposito='".$CUENTA_DEPOSITO[$i]."' WHERE trabajador='".$CEDULA_GRALES[$i]."';";
                    $res_update = pg_query($cn_rrhh, $query_actualizar);                
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
        
        $ptid = oci_parse($cn_adam, $Querybajas);
        oci_execute($ptid);
        while (($row = oci_fetch_array($ptid, OCI_BOTH)) != false) { 
            $trab_baja=$row['CEDULA'];
            $fec_baja=isset($row['FECHA_BAJA'])?"'".$row['FECHA_BAJA']."'":"NULL";
            $cau_baja=isset($row['CAUSA_BAJA'])?"'".$row['CAUSA_BAJA']."'":"NULL";
            
            $query_dar_baja="UPDATE trabajadores_grales SET sit_trabajador = 2, fecha_baja=".$fec_baja.", causa_baja=".$cau_baja." WHERE trabajador = '".$trab_baja."'";
            
            $res_dar_baja = pg_query($cn_rrhh,$query_dar_baja);
            pg_free_result($res_dar_baja);        
        }
       
        oci_free_statement($ptid);
    }

    if ($cant_altas>0)
      for ($i=0; $i<=$cant_altas-1; $i++){
          
           $j = array_search($CEDULA[$dar_alta[$i]], $CEDULA_GRALES);

           $insertTrab_Grales="INSERT INTO trabajadores_grales(trabajador, fecha_ingreso, fecha_antiguedad, fecha_baja, fecha_vto_contrato, 
                    causa_baja, relacion_laboral, telefono_oficina, extension_telefonica, 
                    clase_nomina, sistema_antiguedad, sistema_horario, turno, forma_pago, 
                    sit_trabajador, grupo_sanguinio, cargo, ctadeposito) VALUES ('".$CEDULA_GRALES[$j]."', '".$FECHA_INGRESO[$j]."', ".$FECHA_ANTIGUEDAD[$j].", ".$FECHA_BAJA[$j].", ".$FECHA_VTO_CONTRATO[$j].", '".$CAUSA_BAJA[$j]."', '".$RELACION_LABORAL[$j]."', '".$TELEFONO_OFICINA[$j]."', ".$EXTENSION_TELEFONICA[$j].", '".$CLASE_NOMINA[$j]."', ".$SISTEMA_ANTIGUEDAD[$j].", ".$SISTEMA_HORARIO[$j].", ".$TURNO[$j].", ".$FORMA_PAGO[$j].", ".$SIT_TRABAJADOR[$j].", '".$TIPO_SANGRE[$j]."', '".$DESC_PUESTO[$j]."', '".$CUENTA_DEPOSITO[$j]."');";
            
            $dar_alta_grales = pg_query($cn_rrhh, $insertTrab_Grales);       
            pg_free_result($dar_alta_grales);               
    }

    $reslt = "Finalizado Con Exito! Cantidad de Altas: ".$cant_altas;
} catch(Exception $e) {
    $reslt = "Error: " . $e->getMessage();
}
oci_free_statement($stid);
oci_free_statement($dtid);
return $reslt;   

}
/* --------------------actualiza los jefes de departamentos--------------- */
function actualizar_jefes($cn_rrhh, $cn_adam){
try{
    $sql = "DELETE FROM trabajadores_supervisores_1";
    $res = pg_query($cn_rrhh,$sql);

    $sql = "DELETE FROM trabajadores_supervisores";
    $res = pg_query($cn_rrhh,$sql); 
    $cont1=pg_affected_rows ($res);

    //$conn = oci_connect('matlux', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');
    if (!$cn_adam) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $queryTrabajadores="select TO_NUMBER(v.TRABAJADOR) CEDULA, v.CCOSTO, ltrim(rtrim(v.trabajador_sup)) SUPERIOR from vw_dotacion_briqven_02_mas v";
    $stid = oci_parse($cn_adam, $queryTrabajadores);
    oci_execute($stid);
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
        $CEDULA=isset($row['CEDULA'])?$row['CEDULA']:"";
    $CCOSTO=isset($row['CCOSTO'])?$row['CCOSTO']:"NULL";
        $SUPERIOR=isset($row['SUPERIOR'])?$row['SUPERIOR']:"";
        $insertTrabajador="INSERT INTO trabajadores_supervisores(trabajador, supervisor)
        VALUES ('".$CEDULA."','".$SUPERIOR."');";
        $trab_temp = pg_query($cn_rrhh,$insertTrabajador);

    $insertTrabajador="INSERT INTO trabajadores_supervisores_1(trabajador, ccosto, supervisor)
        VALUES ('".$CEDULA."', ".$CCOSTO.",'".$SUPERIOR."');";
    $trab_temp = pg_query($cn_rrhh,$insertTrabajador);
    }    
    
    $reslt = "Finalizado Con Exito! Se Actualizaron: ".$cont1;
} catch(Exception $e) {
    $reslt = "Error: " . $e->getMessage();
}    
 oci_free_statement($stid);
 return $reslt;
}
//////////////////////SISTEMA CONTROL DE ACCESO////////////////////////////////////
/* --------------BORRA LOS MOVIMIENTOS QUE TENGANS MAS DE 1 DIA SIN ESTATUS-------- */
function delete_movimientos()
{
    require_once('../control_acceso/libs/conexion.php');
    $cn=Conectarse();
    $sql1 = "DELETE FROM movimientos WHERE EXTRACT(DAY FROM age(now(),date(fecha_hora))) >= 1  AND estatus = 'PENDIENTE'";
    $res1 = pg_query($cn,$sql1);
    $cont1=pg_affected_rows ($res1);
    
    $sql2 = "select fkmovimiento_part from usuarios_movimientos where operacion = 'SOLICITADO' and fkmovimiento_part in (select fkmovimiento_part from usuarios_movimientos where estatus isNULL or estatus='' and operacion = 'CONFORMADO') and EXTRACT(DAY FROM age(now(),date(fecha_hora_acceso))) >= 1";
    $res2 = pg_query($cn,$sql2);
    $cont2=pg_num_rows($res2);
    if ($cont2>0){
        $cont2=0;
        while ($row = pg_fetch_array($res2)){
            $resdel2 = pg_query($cn,"DELETE FROM movimientos WHERE idmovimiento=".$row['fkmovimiento_part']);
            $cont2=pg_affected_rows ($resdel2) + $cont2; 
        }
    }

    $sql3 = "select fkmovimiento_part from usuarios_movimientos where operacion = 'CONFORMADO' and fkmovimiento_part in (select fkmovimiento_part from usuarios_movimientos where estatus isNULL or estatus='' and operacion = 'AUTORIZADO') and EXTRACT(DAY FROM age(now(),date(fecha_hora_acceso))) >= 1";
    $res3 = pg_query($cn,$sql3);
    $cont3=pg_num_rows($res3);
    if ($cont3>0){
        $cont3=0;
        while ($row = pg_fetch_array($res3)){
            $resdel3 = pg_query($cn,"DELETE FROM movimientos WHERE idmovimiento=".$row['fkmovimiento_part']);
            $cont3=pg_affected_rows ($resdel3) + $cont3; 
        }
    }
    pg_free_result($res2);
    pg_free_result($res3);    
    pg_close($cn);
    $eliminados=$cont2;
    return  "Finalizado Con Exito! Se Eliminaron: ".$eliminados;
 }
/*----------Notifica los Movimientos CERRADOS----------------------- */
function notificar_completados(){
    require_once('../control_acceso/libs/conexion.php');
    $cn=Conectarse();
    $sql1 = "SELECT a.*, b.tipo_movimiento FROM movimientos_cerrados_notif a, movimientos b WHERE enviado=FALSE AND a.fkmovimiento=b.idmovimiento";
    $res1 = pg_query($cn,$sql1);
    $cont1=pg_num_rows($res1);
    if ($cont1>0){
        require_once('../control_acceso/funciones_var.php');
        $ids='(';
        while ($row = pg_fetch_array($res1)){
            enviar_nota($row['fkmovimiento'], $row['tipo_movimiento'], 'COMPLETADO', '', 'SISTEMA AUTOMATICO', '', 'notificacion');
            $ids.=$row['fkmovimiento'].',';
        }
        $ids = trim($ids, ',');
        $ids .=')';
        $sql2 = "UPDATE movimientos_cerrados_notif SET enviado=TRUE, fecha_notif=NOW() WHERE fkmovimiento IN ".$ids;
        $res2 = pg_query($cn,$sql2);
    }
    pg_free_result($res1);
    return  "Finalizado Con Exito! Fueron Notificados: ".$cont1;
}
/*----------Notifica los Movimientos NO RETORNADOS----------------------- */
function notificar_mov_no_return(){
    require_once('../control_acceso/libs/conexion.php');
    $cn=Conectarse();
    $sql1 = "SELECT   idmovimiento,   fecha_hora,   retorna,   fecha_retorno,   nombre_contacto,   estatus,   ciclo,  tipo_movimiento FROM movimientos 
WHERE   estatus = 'VALIDADO' AND retorna = 'SI' AND fecha_retorno <= now() AND
  ciclo in ('ENTRADA PEND RETORNO', 'SALIDA PEND RETORNO')";
    $res1 = pg_query($cn,$sql1);
    $cont1=pg_num_rows($res1);

    $sql2 = "SELECT email FROM  usuarios WHERE nivel = 7 AND fkunidad = 61606 AND estatus = 'ACTIVO';";
    $res2 = pg_query($cn,$sql2);
    $cont2=pg_num_rows($res2);    

    if ($cont1>0){
        require_once('../control_acceso/funciones_var.php');
        $tabla='<table>
              <tr>
                <th>ID.MOV.</th>
                <th>Fecha Mov.</th>
                <th>Fecha Retorno</thd>
                <th>Tipo Mov.</th>
                <th>Nombre Contacto</th>
              </tr>';
        while ($row = pg_fetch_array($res1)){
            $tabla.="<tr>
                <td>".$row['idmovimiento']."</td>
                <td>".$row['fecha_hora']."</td>
                <td>".$row['fecha_retorno']."</td>
                <td>".$row['tipo_movimiento']."</td>
                <td>".$row['nombre_contacto']."</td>
              </tr>";
            
        }
        $tabla='</table>';      
        $cont1=notificar_noretornado($tabla);
    }
    pg_free_result($res1);    
    return  "Finalizado Con Exito! Fueron Notificados: ".$cont1;
}
/*---------------------------------------------------------------------------------------------- */
/*----------Migrar fichadas del control de acceso a SITT----------------------- */
function migrar_fichadas_al_sitt($cn_ctrl_planta, $conn_sitt){
    
    /*include_once('../control_acceso/libs/conexion.php');
    $cn_ctrl_planta=Conectarse();*/

    $sql1 = "select cedula, nombres,to_char(fecha_entrada, 'YYYY-mm-dd') entrada, to_char(fecha_entrada, 'HH24:MI') as hora_entrada, 
to_char(fecha_salida, 'YYYY-mm-dd') salida, to_char(fecha_salida, 'HH24:MI') hora_salida , 
round((to_char(fecha_salida -fecha_entrada , 'SSSS')::numeric/60)/60,1) as tiempo , 
CASE
     WHEN round((to_char(fecha_salida -fecha_entrada , 'SSSS')::numeric/60)/60,1) IS NULL  
     THEN 'exec poner_fichada_y_codigo_ausencia *' || to_char(fecha_entrada, 'YYYY-mm-dd') || '*, ' || cedula || ', 20, -1;' 
     ELSE 'exec poner_fichada_codausencia_horas *' || to_char(fecha_entrada, 'YYYY-mm-dd') || '*, ' || cedula || ', 20, *' || to_char(fecha_entrada, 'HH24:MI') || '*, *' || to_char(fecha_salida, 'HH24:MI') || '*,  -1, **, **;' 
END
   as store_proc_sitt_1 
 from tiempo_trabajado 
 where 
to_char(fecha_entrada, 'YYYY-mm-dd') = to_char(now() - interval '1 day', 'YYYY-mm-dd') 
 and tipo_personal='PROPIO' 
group by  cedula, nombres, fecha_entrada, fecha_salida order by fecha_entrada, cedula, fecha_salida desc;";

    try {
        $res1 = pg_query($cn_ctrl_planta,$sql1);
        $cont1=pg_num_rows($res1);        
        if ($cont1>0){
           // if (!function_exists('Conectarse_sitt'))   { 
             //   $conn_sitt = Conectarse_sitt();
            //}            
            $conn_sitt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            while ($row = pg_fetch_array($res1)){
                $resultado="";
                $resultado = str_replace("*", "'", $row['store_proc_sitt_1']);               
                $stmt = $conn_sitt->prepare($resultado);
                $stmt->execute();
            }       
        }
        $reslt="Finalizado Con Exito! Fueron Migrados: ".$cont1." Fichadas. ";
    } catch(Exception $e) {
        $reslt = "Error: " . $e->getMessage();
    }
    $conn_sitt=null;
    $stmt=null;
    //pg_close($cn_ctrl_planta);
    pg_free_result($res1);
    return $reslt;
}

/*----------Migrar fichadas del control de acceso a SITT----------------------- */
function migrar_norequeridos_al_sitt($cn_ctrl_planta, $conn_sitt){

    $sql1 = "SELECT id, cedula, to_char(now() - interval '1 day', 'YYYY-mm-dd') as ayer, 'exec poner_fichada_y_codigo_ausencia *' || to_char(now() - interval '1 day', 'YYYY-mm-dd') || '*, ' || cedula || ', 75, -1;' as store_proc_sitt_1 FROM personal_bloqueado  where status='ACTIVO' and  motivo='No Requerido' and (to_char(now() - interval '1 day', 'YYYY-mm-dd') between to_char(fecha_desde, 'YYYY-mm-dd') and to_char(fecha_hasta, 'YYYY-mm-dd') or (fecha_hasta is null))";
    try {
        $res1 = pg_query($cn_ctrl_planta,$sql1);
        $cont1=pg_num_rows($res1);        
        if ($cont1>0){                   
            $conn_sitt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            while ($row = pg_fetch_array($res1)){
                $resultado="";
                $resultado = str_replace("*", "'", $row['store_proc_sitt_1']);               
                $stmt = $conn_sitt->prepare($resultado);
                $stmt->execute();
            }       
        }
        $reslt="Finalizado Con Exito! Fueron Migrados: ".$cont1." No Requeridos. ";
    } catch(Exception $e) {
        $reslt = "Error: " . $e->getMessage();
    }
    $conn_sitt=null;
    $stmt=null;
    
    pg_free_result($res1);
    return $reslt;
}

/*----------Migrar fichadas del control de acceso a SITT----------------------- */
function cambia_norequeridos_afijodedia($cn_ctrl_planta, $conn_sitt){

    $sql1 = "SELECT id, cedula, to_char(now() - interval '1 day', 'YYYY-mm-dd') as ayer FROM personal_bloqueado  where status='ACTIVO' and  motivo='No Requerido' and (to_char(now() - interval '1 day', 'YYYY-mm-dd') between to_char(fecha_desde, 'YYYY-mm-dd') and to_char(fecha_hasta, 'YYYY-mm-dd') or (fecha_hasta is null))";

    $sql2 = "SELECT sistema_horario FROM ADAM_DATOS_PERSONALES where sistema_horario in (1,2,3,4,12) and trabajador = ";
    try {
        $res1 = pg_query($cn_ctrl_planta,$sql1);
        $cont1=pg_num_rows($res1);
        if ($cont1>0){                   
            $conn_sitt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $cont1=0;
            while ($row = pg_fetch_array($res1)){
                $stmt1  = $conn_sitt->query($sql2.$row['cedula']);                
                $row1    = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
                if ($row1['sistema_horario']==1 or  $row1['sistema_horario']==2 or  $row1['sistema_horario']==3 or  $row1['sistema_horario']==4 or  $row1['sistema_horario']==12){
                    $cont1++;
                    $sql3="exec PROC_ACTUALIZAR_ESPERANZA_PERSONA_SH ".$row['cedula'].", '".$row['ayer']."', 13";
                    $stmt2 = $conn_sitt->prepare($sql3);
                    $stmt2->execute();
                }                
            }       
        }
        $reslt="Finalizado Con Exito! Fueron Cambiados de esperanza: ".$cont1." No Requeridos. ";
    } catch(Exception $e) {
        $reslt = "Error: " . $e->getMessage();
    }
    $conn_sitt=null;
    $stmt1=null;
    $stmt2=null;
    pg_free_result($res1);
    return $reslt;
}


/*----------migrar_trabajadores_rrh   h_al_control_asistencia----------------------- */
function migrar_trabajadores_rrhh_al_control_asistencia(){
    include_once('../control_asistencia/BD/conexion.php');
    $cn=Conex_control_asistencia();
    $sql1 = "insert into trabajadores 
select rr.*  from v_trabajadores_rrhh rr  inner join v_trabajadores_activos ac
on rr.trabajador=ac.trabajador
where ac.trabajador not in (SELECT trabajador
    FROM public.trabajadores)";

    $res1 = pg_query($cn,$sql1);
    $cont1=pg_num_rows($res1);    
    pg_free_result($res1);
    return  "Finalizado Con Exito! Fueron Migrados: ".$cont1." trabajadores. ";
}
/*----------------------------------------------------------------------------------------------
/*---------------------------------------------------------------------------------------------- */
/*----------Migrar de adam vista vw_dotacion_briqven_02_mas a postgresql----------------------- */
function migrar_dotacion( $cn_rrhh, $cn_asistencia_laboral, $cn_adam){

try {
    if (!$cn_adam) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$DeletePgSql = pg_query($cn_rrhh, "DELETE FROM adam_vw_dotacion_briqven_02_mas") or die("Error en Delete SQL adam_vw_dotacion_briqven_02_mas");

$countRecord="SELECT COUNT(*) AS CANTREG FROM vw_dotacion_briqven_02_mas";
$stic = oci_parse($cn_adam, $countRecord);
oci_execute($stic);

$row_c = oci_fetch_array($stic, OCI_BOTH);
$count = $row_c['CANTREG'];

$adam_Trabajadores="SELECT LTRIM(RTRIM(trabajador)) as trabajador, 
       nombre, sexo,   Fecha_Ingreso,  Fecha_Nacimiento,   relacion_laboral,
       sistema_horario, talla_camisa,  talla_pantalon,     talla_zapatos,
       LTRIM(RTRIM(codigo_carnet)) as codigo_carnet,  LTRIM(RTRIM(serial_carnet)) as serial_carnet,
       procedencia, trabajador_ONAPRE, contratacion_ONAPRE,  grado_trab,
       rango_trab,   Condicion,   Tipo_Discapacidad, salario,   ccosto, 
       detalle_ccosto,  direccion, gergral, gerencia, depto, coordina, 
       puesto, desc_puesto,  nivel_jerarquico,  desc_nivel_jerarquico,
       grupo,  area,  subarea,  detalle_subarea,  Encuadre_Puesto,
       Encuadre_ONAPRE,  Clasificacion_ONAPRE,  Encuadre2_ONAPRE, puesto_superior,
       desc_psuperior,   trabajador_sup,  nombre_sup,  grado_instruccion,
       titulo_profesional, registro_fiscal, siglado, email
  FROM vw_dotacion_briqven_02_mas";

$stid = oci_parse($cn_adam, $adam_Trabajadores);
oci_execute($stid);
$i=1;
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {

$TRABAJADOR=isset($row['TRABAJADOR'])?"'".TRIM($row['TRABAJADOR'])."'":'NULL';
$NOMBRE=isset($row['NOMBRE'])?"'".TRIM($row['NOMBRE'])."'":'NULL';
$SEXO=isset($row['SEXO'])?"'".TRIM($row['SEXO'])."'":'NULL';
$FECHA_INGRESO=isset($row['FECHA_INGRESO'])?"'".TRIM($row['FECHA_INGRESO'])."'":'NULL';
$FECHA_NACIMIENTO=isset($row['FECHA_NACIMIENTO'])?"'".TRIM($row['FECHA_NACIMIENTO'])."'":'NULL';
$RELACION_LABORAL=isset($row['RELACION_LABORAL'])?"'".TRIM($row['RELACION_LABORAL'])."'":'NULL';
$SISTEMA_HORARIO=isset($row['SISTEMA_HORARIO'])?$row['SISTEMA_HORARIO']:'NULL';
$TALLA_CAMISA=isset($row['TALLA_CAMISA'])?"'".TRIM($row['TALLA_CAMISA'])."'":'NULL';
$TALLA_PANTALON=isset($row['TALLA_PANTALON'])?"'".TRIM($row['TALLA_PANTALON'])."'":'NULL';
$TALLA_ZAPATOS=isset($row['TALLA_ZAPATOS'])?"'".TRIM($row['TALLA_ZAPATOS'])."'":'NULL';
$CODIGO_CARNET=isset($row['CODIGO_CARNET'])?"'".TRIM($row['CODIGO_CARNET'])."'":'NULL';
$SERIAL_CARNET=isset($row['SERIAL_CARNET'])?"'".TRIM($row['SERIAL_CARNET'])."'":'NULL';
$PROCEDENCIA=isset($row['PROCEDENCIA'])?"'".TRIM($row['PROCEDENCIA'])."'":'NULL';
$TRABAJADOR_ONAPRE=isset($row['TRABAJADOR_ONAPRE'])?"'".TRIM($row['TRABAJADOR_ONAPRE'])."'":'NULL';
$CONTRATACION_ONAPRE=isset($row['CONTRATACION_ONAPRE'])?"'".TRIM($row['CONTRATACION_ONAPRE'])."'":'NULL';
$GRADO_TRAB=isset($row['GRADO_TRAB'])?"'".TRIM($row['GRADO_TRAB'])."'":'NULL';
$RANGO_TRAB=isset($row['RANGO_TRAB'])?"'".TRIM($row['RANGO_TRAB'])."'":'NULL';
$CONDICION=isset($row['CONDICION'])?"'".TRIM($row['CONDICION'])."'":'NULL';
$TIPO_DISCAPACIDAD=isset($row['TIPO_DISCAPACIDAD'])?"'".TRIM($row['TIPO_DISCAPACIDAD'])."'":'NULL';
$SALARIO=isset($row['SALARIO'])?"'".TRIM($row['SALARIO'])."'":'NULL';
$CCOSTO=isset($row['CCOSTO'])?"'".TRIM($row['CCOSTO'])."'":'NULL';
$DETALLE_CCOSTO=isset($row['DETALLE_CCOSTO'])?"'".TRIM($row['DETALLE_CCOSTO'])."'":'NULL';
$DIRECCION=isset($row['DIRECCION'])?"'".TRIM($row['DIRECCION'])."'":'NULL';
$GERGRAL=isset($row['GERGRAL'])?"'".TRIM($row['GERGRAL'])."'":'NULL';
$GERENCIA=isset($row['GERENCIA'])?"'".TRIM($row['GERENCIA'])."'":'NULL';
$DEPTO=isset($row['DEPTO'])?"'".TRIM($row['DEPTO'])."'":'NULL';
$COORDINA=isset($row['COORDINA'])?"'".TRIM($row['COORDINA'])."'":'NULL';
$PUESTO=isset($row['PUESTO'])?"'".TRIM($row['PUESTO'])."'":'NULL';
$DESC_PUESTO=isset($row['DESC_PUESTO'])?"'".TRIM($row['DESC_PUESTO'])."'":'NULL';
$NIVEL_JERARQUICO=isset($row['NIVEL_JERARQUICO'])?$row['NIVEL_JERARQUICO']:'NULL';
$DESC_NIVEL_JERARQUICO=isset($row['DESC_NIVEL_JERARQUICO'])?"'".TRIM($row['DESC_NIVEL_JERARQUICO'])."'":'NULL';
$GRUPO=isset($row['GRUPO'])?"'".TRIM($row['GRUPO'])."'":'NULL';
$AREA=isset($row['AREA'])?"'".TRIM($row['AREA'])."'":'NULL';
$SUBAREA=isset($row['SUBAREA'])?"'".TRIM($row['SUBAREA'])."'":'NULL';
$DETALLE_SUBAREA=isset($row['DETALLE_SUBAREA'])?"'".TRIM($row['DETALLE_SUBAREA'])."'":'NULL';
$ENCUADRE_PUESTO=isset($row['ENCUADRE_PUESTO'])?"'".TRIM($row['ENCUADRE_PUESTO'])."'":'NULL';
$ENCUADRE_ONAPRE=isset($row['ENCUADRE_ONAPRE'])?"'".TRIM($row['ENCUADRE_ONAPRE'])."'":'NULL';
$CLASIFICACION_ONAPRE=isset($row['CLASIFICACION_ONAPRE'])?"'".TRIM($row['CLASIFICACION_ONAPRE'])."'":'NULL';
$ENCUADRE2_ONAPRE=isset($row['ENCUADRE2_ONAPRE'])?"'".TRIM($row['ENCUADRE2_ONAPRE'])."'":'NULL';
$PUESTO_SUPERIOR=isset($row['PUESTO_SUPERIOR'])?"'".TRIM($row['PUESTO_SUPERIOR'])."'":'NULL';
$DESC_PSUPERIOR=isset($row['DESC_PSUPERIOR'])?"'".TRIM($row['DESC_PSUPERIOR'])."'":'NULL';
$TRABAJADOR_SUP=isset($row['TRABAJADOR_SUP'])?"'".TRIM($row['TRABAJADOR_SUP'])."'":'NULL';
$NOMBRE_SUP=isset($row['NOMBRE_SUP'])?"'".TRIM($row['NOMBRE_SUP'])."'":'NULL';
$GRADO_INSTRUCCION=isset($row['GRADO_INSTRUCCION'])?"'".TRIM($row['GRADO_INSTRUCCION'])."'":'NULL';
$TITULO_PROFESIONAL=isset($row['TITULO_PROFESIONAL'])?"'".TRIM($row['TITULO_PROFESIONAL'])."'":'NULL';
$SIGLADO=isset($row['SIGLADO'])?"'".TRIM($row['SIGLADO'])."'":'NULL';
$EMAIL=isset($row['EMAIL'])?"'".TRIM($row['EMAIL'])."'":'NULL';

$insertTrabajador_Postgres="INSERT INTO adam_vw_dotacion_briqven_02_mas (
                trabajador, nombre, sexo, fecha_ingreso, fecha_nacimiento, relacion_laboral, 
       sistema_horario, talla_camisa, talla_pantalon, talla_zapatos, 
       codigo_carnet, serial_carnet, procedencia, trabajador_onapre, 
       contratacion_onapre, grado_trab, rango_trab, condicion, tipo_discapacidad, 
       salario, ccosto, detalle_ccosto, direccion, gergral, gerencia, 
       depto, coordina, puesto, desc_puesto, nivel_jerarquico, desc_nivel_jerarquico, 
       grupo, area, subarea, detalle_subarea, encuadre_puesto, encuadre_onapre, 
       clasificacion_onapre, encuadre2_onapre, puesto_superior, desc_psuperior, 
       trabajador_sup, nombre_sup, grado_instruccion, titulo_profesional, siglado, email)
        VALUES (".trim($TRABAJADOR).", ".trim($NOMBRE).", ".trim($SEXO).", ".trim($FECHA_INGRESO).", ".trim($FECHA_NACIMIENTO).", ".trim($RELACION_LABORAL).", ".trim($SISTEMA_HORARIO).", ".trim($TALLA_CAMISA).", ".trim($TALLA_PANTALON).", ".trim($TALLA_ZAPATOS).", ".trim($CODIGO_CARNET).", ".trim($SERIAL_CARNET).", ".trim($PROCEDENCIA).", ".trim($TRABAJADOR_ONAPRE).", ".trim($CONTRATACION_ONAPRE).", ".trim($GRADO_TRAB).", ".trim($RANGO_TRAB).", ".trim($CONDICION).", ".trim($TIPO_DISCAPACIDAD).", ".trim($SALARIO).", ".trim($CCOSTO).", ".trim($DETALLE_CCOSTO).", ".trim($DIRECCION).", ".trim($GERGRAL).", ".trim($GERENCIA).", ".trim($DEPTO).", ".trim($COORDINA).", ".trim($PUESTO).", ".trim($DESC_PUESTO).", ".trim($NIVEL_JERARQUICO).", ".trim($DESC_NIVEL_JERARQUICO).", ".trim($GRUPO).", ".trim($AREA).", ".trim($SUBAREA).", ".trim($DETALLE_SUBAREA).", ".trim($ENCUADRE_PUESTO).", ".trim($ENCUADRE_ONAPRE).", ".trim($CLASIFICACION_ONAPRE).", ".trim($ENCUADRE2_ONAPRE).", ".trim($PUESTO_SUPERIOR).", ".trim($DESC_PSUPERIOR).", ".trim($TRABAJADOR_SUP).", ".trim($NOMBRE_SUP).", ".trim($GRADO_INSTRUCCION).", ".trim($TITULO_PROFESIONAL).",".$SIGLADO.",".$EMAIL.");";

$insert_PgSql = pg_query($cn_rrhh,$insertTrabajador_Postgres) or die("Error en la Consulta SQL: ".$insertTrabajador_Postgres);

$i++;
}

$consulTrabPgSql = pg_query($cn_asistencia_laboral, "SELECT * FROM adam_vw_dotacion_briqven_02_tmp") or die("Error en Insert SQL adam_vw_dotacion_briqven_02_tmp");

$cont1=pg_num_rows($consulTrabPgSql);
if ($cont1>0){
    while ($row = pg_fetch_array($consulTrabPgSql)){
                $insertTrabajador_Postgres="INSERT INTO adam_vw_dotacion_briqven_02_mas (
                    trabajador, nombre, sexo, fecha_ingreso, fecha_nacimiento, relacion_laboral, 
           sistema_horario, talla_camisa, talla_pantalon, talla_zapatos, 
           codigo_carnet, serial_carnet, procedencia, trabajador_onapre, 
           contratacion_onapre, grado_trab, rango_trab, condicion, tipo_discapacidad, 
           salario, ccosto, detalle_ccosto, direccion, gergral, gerencia, 
           depto, coordina, puesto, desc_puesto, nivel_jerarquico, desc_nivel_jerarquico, 
           grupo, area, subarea, detalle_subarea, encuadre_puesto, encuadre_onapre, 
           clasificacion_onapre, encuadre2_onapre, puesto_superior, desc_psuperior, 
           trabajador_sup, nombre_sup, grado_instruccion, titulo_profesional)
            VALUES ('".$row['trabajador']."', '".$row['nombre']."', '".$row['sexo']."', '".$row['fecha_ingreso']."', '".$row['fecha_nacimiento']."', '".$row['relacion_laboral']."', ".$row['sistema_horario'].", '".$row['talla_camisa']."', '".$row['talla_pantalon']."', '".$row['talla_zapatos']."', '".$row['codigo_carnet']."', '".$row['serial_carnet']."', '".$row['procedencia']."', '".$row['trabajador_onapre']."', '".$row['contratacion_onapre']."', '".$row['grado_trab']."', '".$row['rango_trab']."', '".$row['condicion']."', '".$row['tipo_discapacidad']."', '".$row['salario']."', '".$row['ccosto']."', '".$row['detalle_ccosto']."', '".$row['direccion']."', '".$row['gergral']."', '".$row['gerencia']."', '".$row['depto']."', '".$row['coordina']."', '".$row['puesto']."', '".$row['desc_puesto']."', ".$row['nivel_jerarquico'].", '".$row['desc_nivel_jerarquico']."', '".$row['grupo']."', '".$row['area']."', '".$row['subarea']."', '".$row['detalle_subarea']."', '".$row['encuadre_puesto']."', '".$row['encuadre_onapre']."', '".$row['clasificacion_onapre']."', '".$row['encuadre2_onapre']."', '".$row['puesto_superior']."', '".$row['desc_psuperior']."', '".$row['trabajador_sup']."', '".$row['nombre_sup']."', '".$row['grado_instruccion']."', '".$row['titulo_profesional']."');";

                $insert_PgSql = pg_query($cn_rrhh,$insertTrabajador_Postgres) or die("Error en la Consulta SQL: ".$insertTrabajador_Postgres);

                $i++;
                pg_free_result($insert_PgSql);
    }
}
pg_free_result($consulTrabPgSql);
$rslt = "Finalizado Con Exito! Fueron Migrados: ".$i." Registro. ";
} catch(Exception $e) {
    $rslt = "Error: " . $e->getMessage();
}
return $rslt;

}

function respaldar_base_datos(){
   
    $salida = shell_exec("./respaldar_DB.sh");
    return $salida;
}

/*----------------------------------------------------------------------------------------------*/
/*--- ACTUALIZA LA ESPERANZE EN EL CASO DE PRESENTAR CAMBIO DE TURNO EN CONTROL DE ASISTECIA ---*/
/*----------------------------------------------------------------------------------------------*/
function cambio_cuadrilla( $cn_sitt, $cn_asistencia_laboral, $fecha){
try{
    $fecha_ultima_generacion=0;
    $hoy=date('Y-m-d');
    #CONSULTAMOS HASTA QUE FECHA ESTA CREADA LA ULTIMA HOJA DE TIEMPO
    $query_variables_sistema="select Ultima_Generacion_ht from SW_Variables_sistema svs ";
    $stmt1 = $cn_sitt->query($query_variables_sistema);
    $contar = $stmt1->columnCount();
    if($contar > 0){
       while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
          $fecha_ultima_generacion = $row['Ultima_Generacion_ht'];  
       }       
    }
    $fecha_a_consultar=$fecha_ultima_generacion;   // date("Y-m-d",strtotime($fecha_ultima_generacion."+ 1 days"));
    $fecha_a_consultar=$fecha;
    //print $fecha_a_consultar;
    //$fecha_a_consultar=date("Y-m-d",strtotime($fecha_ultima_generacion."- 2 days"));

    #SE CONSULTA LA TABLA CAMBIO DE CUADRILLA PARA VER SI ESISTE REGISTRO QUE SE DEBA CAMBIAR
    $cn=$cn_asistencia_laboral; 
    //$sql1 = "select * from cambio_cuadrilla where '".$fecha_a_consultar."' between fecha_ini and fecha_fin";
    $sql1 = "select cc.*, u.trabajador as responsable from cambio_cuadrilla cc inner join usuarios u on cc.login_registrado  = u.login_username where '".$fecha_a_consultar."' between fecha_ini and fecha_fin";
    $result = pg_query($cn,$sql1);
    $cont1=pg_num_rows($result);  
    $cantidad_update=0;
    if($cont1>0){
      while ($fila=pg_fetch_array($result))
      { 
          $SH= $fila['cuadrilla'];
          $trabajador = $fila['trabajador'];
          #CONSULTAMOS LA ESPERANZA DE ALGUN TRABAJADOR QUE ESTE EN EL SISTEMA HORARIO SELECCIONADO, AGRUPANDOOS POR LOS 
          #CAMPOS a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, Y EL QUE TENGA MAYOR NUMERO DE 
          #VECES REPETIDAS (AGRUPADOS) SE TOMA COMO LA ESPERANZA REAL 
          $query1="SELECT top 1 a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, 
          CASE
                    WHEN a.Entrada_Esperada2 =NULL THEN  NULL else a.Entrada_Esperada2
                 END  as Entrada_Esperada2, 
                 CASE
                    WHEN a.Salida_Esperada2 =NULL THEN  NULL  ELSE a.Salida_Esperada2
                 END  as Salida_Esperada2,  COUNT(*) 
                   from sw_hoja_de_tiempo_real a 
                   inner join ADAM_DATOS_PERSONALES b on cast(b.Trabajador as integer) = a.cedula 
                   where a.fecha = '".$fecha_a_consultar."' 
                   AND cast(sistema_horario as integer) = ".$SH." and (Entrada_Esperada1!='VV:VV' and Entrada_Esperada1!='RR:RR' and Entrada_Esperada1!='PP:PP' and Entrada_Esperada1!='CS:CS' and Entrada_Esperada1!='SD:SD') GROUP BY a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2  order by COUNT(*) DESC"; 


          $stmt1      = $cn_sitt->query($query1);
          $row        = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
          $entEsp1    = $row['Entrada_Esperada1'];
          $salEsp1    = $row['Salida_Esperada1'];
          //$trabajador = $row['trabajador'];
          if($contar > 0){
            if ($row['Entrada_Esperada2'] != NULL) $Entrada_Esperada2 = "'".$row['Entrada_Esperada2']."'"; else $Entrada_Esperada2 = 'NULL';
            if ($row['Salida_Esperada2']  != NULL) $Salida_Esperada2  = "'".$row['Salida_Esperada2']."'";  else $Salida_Esperada2  = 'NULL';          
     
            #ACTUALIZAMOS LA ESPERANZA A LA QUE SE ENCUENTRA EN LA TABLA CAMBIO DE CUADRILLA DE CONTROL DE ASISTENCIA
            $query_update="UPDATE sw_hoja_de_tiempo_real 
                              SET Entrada_Esperada1='".$entEsp1."', 
                                  Salida_Esperada1='".$salEsp1."',  
                                  Entrada_Esperada2=".$Entrada_Esperada2.", 
                                  Salida_Esperada2=".$Salida_Esperada2.",  
                                      turno=".$SH." 
                                  WHERE cedula=".$trabajador." and fecha='".$fecha_a_consultar."' and (Entrada_Esperada1!='VV:VV' and Entrada_Esperada1!='RR:RR' and Entrada_Esperada1!='PP:PP' and Entrada_Esperada1!='CS:CS' and Entrada_Esperada1!='SD:SD')";
            //000 print "<br>".$query_update;     
            $stmt2  = $cn_sitt->query($query_update);
            
            //$query_consulta="SELECT * FROM SW_Cambios_de_Esperanza WHERE cedula= ".$trabajador." AND fecha='".$fecha_a_consultar."'";
            //$stmt3  = $cn_sitt->query($query_consulta);

            //$query_insert="INSERT SW_Cambios_de_Esperanza (cedula,fecha,EntradaE1, SalidaE1,EntradaE2, SalidaE2,autorizado) VALUES (".$trabajador.",'".$fecha_a_consultar."','".$entEsp1."','".$salEsp1."',".$Entrada_Esperada2.",".$Salida_Esperada2.",".$fila['responsable'].")";
            //print "<br>".$query_insert;

            //$stmt3  = $cn_sitt->query($query_insert);              
              $cantidad_update++;
        }
      }
    }
    $rslt="Finalizado Con Exito! el cambio de cuadrilla de: ".$cantidad_update." registros, para el :".$fecha_a_consultar;
    
} catch(Exception $e) {
    $rslt = "Error: " . $e->getMessage(); 
}
  pg_free_result($result);
  return $rslt;  
}


/*----------------------------------------------------------------------------------------------
/*---------------------------------------------------------------------------------------------- */
/*----------Migrar de adam vista vw_dotacion_briqven_02_mas a postgresql----------------------- */
function agregar_sustituciones($cn_sitt, $cn_asistencia_laboral){
try {
    $fecha_ultima_generacion=0;
    $hoy=date('Y-m-d'); 
    #CONSULTAMOS HASTA QUE FECHA ESTA CREADA LA ULTIMA HOJA DE TIEMPO
    $query_variables_sistema="select Ultima_Generacion_ht from SW_Variables_sistema svs ";
    $stmt1 = $cn_sitt->query($query_variables_sistema);
    $contar = $stmt1->columnCount();
    if($contar > 0){
       while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
          $fecha_ultima_generacion = $row['Ultima_Generacion_ht'];  
       }       
    }
    $fecha_a_consultar=$fecha_ultima_generacion;

    #SE CONSULTA LA TABLA CAMBIO DE CUADRILLA PARA VER SI EXISTE REGISTRO QUE SE DEBA CAMBIAR
    $cn=$cn_asistencia_laboral; 
    $sql1 = "select s.*, u.trabajador as responsable_carga from sustituciones s 
inner join usuarios u on s.login_registrado = u.login_username  
where '".$fecha_a_consultar."' between fecha_ini and fecha_fin";

    $result = pg_query($cn,$sql1);
    $cont1=pg_num_rows($result);
    $cantidad_update=0; 
    if($cont1>0){
      while ($fila=pg_fetch_array($result))
      { 
          $SH= $fila['cuadrilla_nueva'];
          $trabajador = $fila['sustituto'];
          #CONSULTAMOS LA ESPERANZA DE ALGUN TRABAJADOR QUE ESTE EN EL SISTEMA HORARIO SELECCIONADO, AGRUPANDOOS POR LOS 
          #CAMPOS a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, Y EL QUE TENGA MAYOR NUMERO DE 
          #VECES REPETIDAS (AGRUPADOS) SE TOMA COMO LA ESPERANZA REAL 
          $query1="SELECT top 1 a.Entrada_Esperada1 as Entrada_Esperada1, a.Salida_Esperada1 as Salida_Esperada1, 
          CASE
                    WHEN a.Entrada_Esperada2 =NULL THEN  NULL else a.Entrada_Esperada2
                 END  as Entrada_Esperada2, 
                 CASE
                    WHEN a.Salida_Esperada2 =NULL THEN  NULL  ELSE a.Salida_Esperada2
                 END  as Salida_Esperada2,  COUNT(*) 
                   from sw_hoja_de_tiempo_real a 
                   inner join ADAM_DATOS_PERSONALES b on cast(b.Trabajador as integer) = a.cedula 
                   where a.fecha = '".$fecha_a_consultar."' 
                   AND cast(sistema_horario as integer) = ".$SH." and (Entrada_Esperada1!='VV:VV' and Entrada_Esperada1!='RR:RR' and Entrada_Esperada1!='PP:PP' and Entrada_Esperada1!='CS:CS' and Entrada_Esperada1!='SD:SD') GROUP BY a.Entrada_Esperada1, a.Salida_Esperada1, a.Entrada_Esperada2, a.Salida_Esperada2  order by COUNT(*) DESC";

          $stmt1      = $cn_sitt->query($query1);
          $row        = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
          $entEsp1    = $row['Entrada_Esperada1'];
          $salEsp1    = $row['Salida_Esperada1'];
          //$trabajador = $row['trabajador'];
          if($contar > 0){
            if ($row['Entrada_Esperada2'] != NULL) $Entrada_Esperada2 = "'".$row['Entrada_Esperada2']."'"; else $Entrada_Esperada2 = 'NULL';
            if ($row['Salida_Esperada2']  != NULL) $Salida_Esperada2  = "'".$row['Salida_Esperada2']."'";  else $Salida_Esperada2  = 'NULL';          
     
            #ACTUALIZAMOS LA ESPERANZA A LA QUE SE ENCUENTRA EN LA TABLA CAMBIO DE CUADRILLA DE CONTROL DE ASISTENCIA
            $query_update="UPDATE sw_hoja_de_tiempo_real 
                              SET Entrada_Esperada1='".$entEsp1."', 
                                  Salida_Esperada1='".$salEsp1."',  
                                  Entrada_Esperada2=".$Entrada_Esperada2.", 
                                  Salida_Esperada2=".$Salida_Esperada2.",  
                                      turno=".$SH." 
                                  WHERE cedula=".$trabajador." and fecha='".$fecha_a_consultar."' and (Entrada_Esperada1!='VV:VV' and Entrada_Esperada1!='RR:RR' and Entrada_Esperada1!='PP:PP' and Entrada_Esperada1!='CS:CS' and Entrada_Esperada1!='SD:SD')";
              $stmt2  = $cn_sitt->query($query_update);
              $cantidad_update++;
        }
      }
    }
    $rslt="Finalizado Con Exito! el cambio de esperanza por sustituciones de: ".$cantidad_update." registros. ";
} catch(Exception $e) {
    $rslt = "Error: " . $e->getMessage();
}
pg_free_result($result);
return $rslt;
      
}



/*-------------------------------------------------------------------------------- */
require_once('BD/conexiones.php');
$fecha = $_GET['f'];
$cn_serviciomedico=  pg_conex_bdmatserviciomedico();
$cn_rrhh=  pg_conex_bdmatrrhh();
$cn_ctrl_planta=pg_conex_bdmat_ctrl_planta();
$cn_asistencia_laboral=pg_conex_bdmatasistencia_laboral();
$cn_adam = Conex_oramprd();
$cn_sitt = sqlserv_conex_sitt();

/////////////////////////////////////////////////////////////////////////////
$msj1="*-------".date("d m Y H:m:s");
$msj1.= " SE PROCESARA 1 TAREAS:-------------------------------". "\n";
guardar_log($msj1, true);
guardar_log('BEGIN', true);
//1
/*
$msj1= "Ejecutando Funcion: 1. RRHH: Migrando los Datos del Sistema Adam a Postgresql". "\n";
guardar_log($msj1, false);
$msj1= migrar_trabajadores_adam($cn_rrhh, $cn_adam). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);
//2
$msj1= "Ejecutando Funcion: 2. RRHH: Migrando desde ADAM nuevo ingreso". "\n";
guardar_log($msj1, false);
$msj1= migrar_pacientes($cn_serviciomedico, $cn_adam). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);
//3
$msj1= "Ejecutando Funcion: 3. Sistema Medico: Actualizar datos de pacientes". "\n";
guardar_log($msj1, false);
$msj1= actualizar_pacientes($cn_serviciomedico, $cn_adam). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

//4---------------------desabilitado el 15/06/2022-------------------
/*$msj1= "4. Sistema Control de Acceso: Borrando los movimientos que tienen mas de un 1 dia sin estatus!...". "\n";
guardar_log($msj1, false);
$msj1= delete_movimientos(). "\n";
guardar_log($msj1);
//5
$msj1= "5. Sistema Control de Acceso: Notifica los Movimientos CERRADOS!...". "\n";
guardar_log($msj1, false);
$msj1= notificar_completados(). "\n";
guardar_log($msj1);
*/
//6----------------------------------------------
/*
$msj1= "Ejecutando Funcion: 4. Sistema Control de Acceso: Actualizar los Jefes de Departamentos". "\n";
guardar_log($msj1, false);
$msj1= actualizar_jefes($cn_rrhh, $cn_adam). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

$msj1= "Ejecutando Funcion: 5. Sistema Control de Acceso: Migrar Fichadas a SITT". "\n";
guardar_log($msj1, false);
$msj1= migrar_fichadas_al_sitt($cn_ctrl_planta, $cn_sitt). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

$msj1= "Ejecutando Funcion: 6. Cambio de esperanza a fijo de dia a personal No Requeridos". "\n";
guardar_log($msj1, false);
$msj1=cambia_norequeridos_afijodedia($cn_ctrl_planta, $cn_sitt). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

$msj1= "Ejecutando Funcion: 7. Sistema Control de Acceso: Migrar No reequeridos a SITT". "\n";
guardar_log($msj1, false);
$msj1= migrar_norequeridos_al_sitt($cn_ctrl_planta, $cn_sitt). "\n";
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

$msj1= "Ejecutando Funcion: 8. Respaldar Base de Datos". "\n";
guardar_log($msj1, false);
$msj1=respaldar_base_datos();
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

$msj1= "Ejecutando Funcion: 9. Migrar Dotacion Briqven". "\n";
guardar_log($msj1, false);
$msj1=migrar_dotacion($cn_rrhh, $cn_asistencia_laboral, $cn_adam);
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);
*/
$msj1= "Ejecutando Funcion: 10. Cambio de cuadrilla". "\n";
guardar_log($msj1, false);
$msj1=cambio_cuadrilla($cn_sitt,$cn_asistencia_laboral,$fecha);
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);

/*$msj1= "Ejecutando Funcion: 11. Sist. Control de Acceso: Notificar Movimientos NO Retornados". "\n";
guardar_log($msj1, false);
$msj1=notificar_mov_no_return();
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);*/
/*
$msj1= "Ejecutando Funcion: 11. Cambio de esperanza por sustituciones". "\n";
guardar_log($msj1, false);
$msj1=agregar_sustituciones($cn_sitt,$cn_asistencia_laboral);
$msj1 = "Resultado: ".$msj1;
guardar_log($msj1, true);
*/
guardar_log('END;', false);

function guardar_log($msj, $salto){
$nombre_archivo = "logs.txt"; 
$html='<body bgcolor="black"><font color="white" face="Courier New,arial">';
if($archivo = fopen($nombre_archivo, "a"))
    {
        if(fwrite($archivo, $msj. "\n"))
        {
          if ($salto)
            echo $html." ".$msj. "\n<br><br>";
          else
            echo $html." ".$msj. "\n<br>";
        }
        else
        {
          echo $html."Ha habido un problema al crear el archivo ".$nombre_archivo."\n<br>";
        }
        echo '</font></body>';
        fclose($archivo);
    }
}

pg_close($cn_serviciomedico);
pg_close($cn_ctrl_planta);
pg_close($cn_asistencia_laboral);
pg_close($cn_rrhh);
oci_close($cn_adam);
$cn_adam=null;
$cn_sitt=null;
?>
