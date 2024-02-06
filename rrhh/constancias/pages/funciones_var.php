<?php
function fecha_actual($forma,$tam, $link){

if ($forma=="MES")
    $sql = "SELECT extract(MONTH FROM now())  AS fecha_hoy;";
elseif ($forma=='LARGO')    
    $sql = "SELECT now() AS fecha_hoy;";
else
    $sql = "SELECT to_char(CURRENT_DATE, '".$tam."')  AS fecha_hoy;";

$result = pg_query($link,$sql);
$row=pg_fetch_array($result);                 
$hoy=$row['fecha_hoy'];

if ($forma=="MES")
   return mes_espanol($hoy);
else
   return $hoy;
}
//----------------------------------------------------------------------
function mes_espanol($hoy){
   switch ($hoy) {
        case 1:
            $vMes = "ENERO";
            break;
        case 2:
            $vMes = "FEBRERO";
            break;
        case 3:
            $vMes = "MARZO";
            break;
        case 4:
            $vMes = "ABRIL";
            break;
        case 5:
            $vMes = "MAYO";
            break;
        case 6:
            $vMes = "JUNIO";
            break;
        case 7:
            $vMes = "JULIO";
            break;
        case 8:
            $vMes = "AGOSTO";
            break;
        case 9:
            $vMes = "SEPTIEMBRE";
            break;
        case 10:
            $vMes = "OCTUBRE";
            break;
        case 11:
            $vMes = "NOVIEMBRE";
            break;
        case 
            $vMes = "DICIEMBRE";
            break;
    }
    return $vMes; 
}
//--------------------------------------------------------------------------
function ProcesarCedula($ced, $tipo, $estatus){
//if (!class_exists("CifrasEnLetras"))
//{
  require_once 'numeros_aletras.php';
  $transfor_monto = new CifrasEnLetras; 
//}
$conn=Conex_oramprd();
            $sql = "select agrupacion, dato from rel_trab_agr where compania = 'MAS1' and trabajador = '" .$ced. "' ";
            $sql .=  "and (agrupacion = 'COMISION' or   agrupacion = 'ENTEADSCRI') ";
            $sql .=  "and trabajador in (select trabajador from trabajadores_grales where sit_trabajador = 1)";

            $stid = oci_parse($conn, $sql);
            oci_execute($stid);
            $comision=0;
            $ENTEADSCRI="";
            while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){              
              if (isset($row['AGRUPACION']) && trim($row['AGRUPACION'])=='COMISION')
                  $comision=1;
              if (isset($row['AGRUPACION']) && trim($row['AGRUPACION'])=='ENTEADSCRI')
                  $ENTEADSCRI=trim($row['DATO']);
            }
///////////////////////////////////////////////////////////////////////////////////////
              $sql = "SELECT TO_CHAR(fecha_ingreso, 'DD/MM/YYYY') AS FECHA_ING, TO_CHAR(FECHA_BAJA, 'DD/MM/YYYY') AS FECHA_BAJA, CLASE_NOMINA, decode(sexo,1, 'M','F') sexo";
              $sql .= " FROM TRABAJADORES_GRALES, trabajadores WHERE ";
              $sql .= " TRABAJADORES_GRALES.TRABAJADOR = '" .$ced. "'";
              $sql .= " AND TRABAJADORES_GRALES.TRABAJADOR = trabajadores.trabajador";

              $stid = oci_parse($conn, $sql);
              oci_execute($stid);
              $FECHA_ING="";
              $FECHA_BAJA="";
              $CLASE_NOMINA="";
              $SEXO="";
              while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                $FECHA_ING=$row['FECHA_ING'];
                $FECHA_BAJA=isset($row['FECHA_BAJA'])?$row['FECHA_BAJA']:"";
                $CLASE_NOMINA=isset($row['CLASE_NOMINA'])?$row['CLASE_NOMINA']:"";
                $SEXO=isset($row['SEXO'])?$row['SEXO']:"";
              }
              if ($estatus == 'ACTIVO')
                $TBINGRESO = $FECHA_ING;
              else
                $TBINGRESO = $FECHA_ING. " hasta el ". $FECHA_BAJA;

              $sql = "select descripcion from datos_agr_trab where agrupacion = 'TRACOST' AND DATO IN (SELECT DATO FROM REL_TRAB_AGR WHERE AGRUPACION = 'TRACOST' AND TRABAJADOR ='" .$ced. "')";
              $stid = oci_parse($conn, $sql);
              oci_execute($stid);
              $tbSitioDeTrabajo="";
              while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                $tbSitioDeTrabajo=trim($row['DESCRIPCION']);                
              }
////////////////////////////////////////////////////////////////////              
              $SUELDOS=0.0;
              $BsEnLetras="";
              $vAnoAcumula="";
              $vMes="";
              $BsEnLetrasSIN=0.0;
              $BsEnNumerosSIN="";

              if ($tipo=='Salario Basico' || $CLASE_NOMINA=='PA'){
                  $sql = "select to_char(fu_val_dimporte(trabajador,salario), 'fm999999990.00') AS SUELDOS from sueldos where trabajador = '" .$ced. "'";
                  $stid = oci_parse($conn, $sql);
                  oci_execute($stid);
                  while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                    $SUELDOS=isset($row['SUELDOS'])?$row['SUELDOS']:0.0;
                    $SUELDOS = (float)$SUELDOS;
                    $BsEnLetras=$transfor_monto->convertirEurosEnLetras($SUELDOS);
                  }

                  $sql = "select MES_ACUMULAR from acum_concepto ";
                  $sql .= " where trabajador = '" .$ced. "'";
                  $sql .= " and concepto = 9900";
                  $sql .= " and MES_ACUMULAR = TO_CHAR(add_months(sysdate,-1),'MM') ";
                  $sql .= " and ANIO = TO_CHAR(add_months(sysdate,-1),'YYYY') ";
                  $sql .= " GROUP BY Anio, MES_ACUMULAR ";
                  $sql .= " ORDER BY Anio, MES_ACUMULAR ";                  
                  
                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);
                    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false)                      
                      $vMes=mes_espanol($row['MES_ACUMULAR']);
              }
              elseif ($tipo=='Salario Integral' && $CLASE_NOMINA=='ME'){
                  $sql = "select ";
                  $sql .= " ANIO, ";
                  $sql .= " MES_ACUMULAR,";
                  $sql .= " to_char(SUM(importe * 30), 'fm999999990.00') as SIN ";
                  $sql .= " from acum_concepto ";
                  $sql .= " where trabajador = '" .$ced. "'";
                  $sql .= " and concepto = 9900 ";
                  $sql .= " and MES_ACUMULAR = TO_CHAR(add_months(sysdate,-1),'MM') ";
                  $sql .= " and ANIO = TO_CHAR(add_months(sysdate,-1),'YYYY') ";
                  $sql .= " GROUP BY Anio, MES_ACUMULAR";
                  $sql .= " ORDER BY Anio, MES_ACUMULAR";
                
                  $stid = oci_parse($conn, $sql);
                  oci_execute($stid);
                  while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                    $BsEnNumerosSIN=isset($row['SIN'])?$row['SIN']:0.0;
                    $BsEnNumerosSIN = (float)$BsEnNumerosSIN;
                    $vAnoAcumula=$row['ANIO'];
                    $BsEnLetrasSIN=$transfor_monto->convertirEurosEnLetras($BsEnNumerosSIN);
                    $vMes=mes_espanol($row['MES_ACUMULAR']);
                  }
              }elseif ($tipo=='Basico + Integral' && $CLASE_NOMINA=='ME'){
                  $sql = "select to_char(fu_val_dimporte(trabajador,salario), 'fm999999990.00') AS SUELDOS from sueldos where trabajador = '" .$ced. "'";
                  $stid = oci_parse($conn, $sql);
                  oci_execute($stid);
                  while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                    $SUELDOS=isset($row['SUELDOS'])?$row['SUELDOS']:0.0;
                    $SUELDOS = (float)$SUELDOS;
                    $BsEnLetras=$transfor_monto->convertirEurosEnLetras($SUELDOS);
                  }
                  $sql = "select ";
                  $sql .= " ANIO, ";
                  $sql .= " MES_ACUMULAR,";
                  $sql .= " to_char(SUM(importe * 30), 'fm999999990.00') as SIN ";
                  $sql .= " from acum_concepto ";
                  $sql .= " where trabajador = '" .$ced. "'";
                  $sql .= " and concepto = 9900 ";
                  $sql .= " and MES_ACUMULAR = TO_CHAR(add_months(sysdate,-1),'MM') ";
                  $sql .= " and ANIO = TO_CHAR(add_months(sysdate,-1),'YYYY') ";
                  $sql .= " GROUP BY Anio, MES_ACUMULAR";
                  $sql .= " ORDER BY Anio, MES_ACUMULAR";
                
                  $stid = oci_parse($conn, $sql);
                  oci_execute($stid);
                  while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                    $BsEnNumerosSIN=isset($row['SIN'])?$row['SIN']:0.0;
                    $BsEnNumerosSIN = (float)$BsEnNumerosSIN;
                    $vAnoAcumula=$row['ANIO'];
                    $BsEnLetrasSIN=$transfor_monto->convertirEurosEnLetras($BsEnNumerosSIN);
                    $vMes=mes_espanol($row['MES_ACUMULAR']);
                  }
              } 
/////////////////////////////////////////////////////////////////////////////////////////              
              $TBCARGO="";
              $sql = "select p.descripcion";
              $sql .=  "    From";
              $sql .=  "        puesto_real_trab_sid prts,";
              $sql .=  "        puestos p";
              $sql .=  "    Where";
              $sql .=  "        prts.puesto = p.puesto";
              $sql .=  "        and prts.trabajador = '" .$ced. "'";
              
              $stid = oci_parse($conn, $sql);
              oci_execute($stid);
              while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
                  $TBCARGO = trim($row['DESCRIPCION']);
              }
///////////////////////////////////////////////////////////////////////////////////////              
              $datos_orampdr = array('fecha_ingreso' => $TBINGRESO, 'cargo' => $TBCARGO, 'bsennumero' => $SUELDOS, 'bsenletras' => $BsEnLetras, 'mes' => $vMes, 'bsintennumeros' => $BsEnNumerosSIN, 'bsintenletras' => $BsEnLetrasSIN, 'comision' => $comision, 'SitioDeTrabajo' => $tbSitioDeTrabajo, 'clase_nomina' => $CLASE_NOMINA, 'SEXO' => $SEXO, 'ENTEADSCRI' => $ENTEADSCRI);

   return $datos_orampdr;         
}
function auditar($operacion, $user,$lag){    
    $idaud=0; 
    $insertarAud="INSERT INTO tbl_auditorias (operacion,login,fecha) VALUES ('".$operacion."', '".$user."', NOW())";
    $result = pg_query($lag,$insertarAud);
    $idaud=pg_affected_rows($result);
       
    return $idaud;
}

function tablaHistoricosPuestos($cedula){
    $qry="SELECT DISTINCT    
    hprts.DESC_PUESTO, ccps.DESCRIPCION, min(desde) as desde, max(hasta) as hasta,
    min(TO_CHAR(FECHA, 'DD/MM/YYYY')) as fecha_cambio,    
    substr(min(TO_CHAR(desde, 'DD/MM/YYYY')), 7, 4) as anio, 
    substr(min(TO_CHAR(desde, 'DD/MM/YYYY')), 4, 2) as mes
    FROM HIS_PUESTO_REAL_TRAB_SID hprts  
    INNER JOIN CAUSAS_CAM_PUESTOS_SID ccps ON hprts.CAUSA_CAMBIO = ccps.CAUSA_CAMBIO 
    WHERE ACCION = 'UPDATE NEW' AND TRABAJADOR=".$cedula."
    GROUP BY hprts.DESC_PUESTO,ccps.DESCRIPCION
   ORDER BY anio desc, mes desc";
   
   /*
   $qry="select DESC_PUESTO, desde, hasta, causa_cambio as DESCRIPCION from  HIS_PUESTO_REAL_TRAB_TMP where trabajador =".$cedula." order by desde desc, hasta";
    */
   //echo $qry;

   $cn_adam = oci_connect('ADAM', 'PENDER1507', '10.50.188.65/mprd.briqven.com.ve');//Conex_oramprd();
   $stid = oci_parse($cn_adam, $qry);
   oci_execute($stid);
   $i=0;
   $inpt = '<table width="95%" border="0" >';              
   $inpt = $inpt.'<thead>
            <tr>
                <th width="3%"></th>
                <th width="50%"><h6>Puesto(s)</h6></th>
                <th width="10%"><h6>Desde</h6></th>
                <td width="2%"></td>
                <th width="10%"><h6>Hasta</h6></th> 
                <td width="2%"></td>
                <th width="30%"><h6>Causa Cambio</h6></th>            
            </tr>
        </thead>
      <tbody>';
     while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
            $i++;
            $DESC_PUESTO=isset($row['DESC_PUESTO'])?TRIM($row['DESC_PUESTO']):'-';
            $desde=isset($row['DESDE'])?TRIM($row['DESDE']):'-';
            $hasta=isset($row['HASTA'])?TRIM($row['HASTA']):'-';
            $causa=isset($row['DESCRIPCION'])?TRIM($row['DESCRIPCION']):'-';
            $inpt .='<tr>';
        $inpt .='<td width="3%"><h6>'.$i.'</h6></td>';    
        $inpt .='<td width="50%"><h6>'.$DESC_PUESTO.'</h6></td>';        
        $inpt .='<td width="10%"><h6>'.$desde.'</h6></td>';
        $inpt .='<td width="2%"></td>';
        $inpt .='<td width="10%"><h6>'.$hasta.'</h6></td>';
        $inpt .='<td width="2%"></td>';
        $inpt .='<td width="30%"><h6>'.$causa.'</h6></td>';    
        $inpt .='</tr>';
     }
     $inpt .='<tr>';
        $inpt .='<td width="3%"></td>';    
        $inpt .='<td width="50%"></td>';
        $inpt .='<td width="10%"></td>';
        $inpt .='<td width="2%"></td>';
        $inpt .='<td width="10%"></td>';
        $inpt .='<td width="2%"></td>';
        $inpt .='<td width="30%"></td>';    
        $inpt .='</tr>';
     $inpt .='</tbody></table>';
     $row=null;
   $cn_adam=null;
   return $inpt;
}
?>