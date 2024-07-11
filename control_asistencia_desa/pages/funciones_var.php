<?php
function ejecutar_query($link,$query){
  //print $query;
  $result_f = pg_query($link, $query);
  return $result_f;
} 

function ejecutar_fetch_array($result){
  $row_f = pg_fetch_array($result);
  return $row_f;
} 

function ejecutar_num_rows($result){
  $count_f = pg_num_rows($result);
  return $count_f;
} 

function ejecutar_free_result($result){
  $result_f =  pg_free_result($result);
  return $result_f;
} 

function ejecutar_close($link){
  $result_f =  pg_close($link);
  return $result_f;
} 

//----------------------------------------------
function cambiar_S_X($valor){
   if ($valor=='S') $resultado='X'; else $resultado="";
   return $resultado;
}
//---------------------------------------------
function permiso_usuario($link, $op, $modulo, $login){

$sql = "SELECT accesos_usuarios.estatus FROM accesos, accesos_usuarios WHERE accesos.idacceso = accesos_usuarios.fkacceso AND  accesos.descripcion = '".$modulo."' AND accesos_usuarios.login = '".$login."' AND accesos_usuarios.operacion = '".$op."' and estatus=true;";
//if ($_SESSION['nivel_const'] =='1'){
 //   print $sql;
//}    
$result = pg_query($link,$sql);
$row=pg_fetch_array($result);
$nreg=ejecutar_num_rows($result);
pg_free_result($result);
if ($nreg>0)
   return $row['estatus'];
else
   return false;
}
//---------------------------------------------
function ccosto_usuario($link, $cedula){

$sql = "SELECT ccosto FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador = '".$cedula."';";

$result = pg_query($link,$sql);
$row=pg_fetch_array($result);
$nreg=ejecutar_num_rows($result);
pg_free_result($result);
if ($nreg>0)
   return $row['ccosto'];
else
   return '';
}
//---------------------------------------------
function login_usuario($link, $cedula){

$sql = "SELECT login_username FROM usuarios WHERE trabajador = '".$cedula."';";

$result = pg_query($link,$sql);
$row=pg_fetch_array($result);
$nreg=ejecutar_num_rows($result);
pg_free_result($result);
if ($nreg>0)
   return $row['login_username'];
else
   return '';
}
//---------------------------------------------
function periodo_abierto($link, $idcal){

$sql = "SELECT abierto, tipo_nomina, mes, anio, fecha_pago FROM periodos_nomina WHERE id_calendario = ".$idcal.";";
$result = pg_query($link,$sql);
$row=pg_fetch_array($result);
$nreg=ejecutar_num_rows($result);
pg_free_result($result);
if ($nreg>0)
   /*if ($row['fecha_cierre']=="")
        return false;
   else */     
        return $row;
else
   return false;
}
//----------------------------------------------------------------------

function dadaFechaDevuelveIdCalendario($link, $fecha){

$sql = "SELECT abierto, tipo_nomina, mes, anio, fecha_pago, inicio, fin, id_calendario FROM periodos_nomina WHERE '$fecha' BETWEEN inicio and fin;";
$result = pg_query($link,$sql);
$row=pg_fetch_array($result);
$nreg=ejecutar_num_rows($result);
pg_free_result($result);
//print_r($row);
if ($nreg>0)
   /*if ($row['fecha_cierre']=="")
        return false;
   else */     
        return $row;
else
   return false;
}

//-----------------------------------------------------------------------------

function enviarPermisoSITT($sp, $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $CentroCosto, $cod_cargo, $DocEntregado){
    
    try {
        $mbd=Conectarse_sitt();

        /*echo "$sp $cedula,$fini1, $ffin2, $turno, $sh, $npermiso, $cod_adam , $cod_ubicacion, $clase_nomina, $relacion_laboral , $firma, $h1, $h2, $PeriodoPago, $autorizado, $autorizado2, $Obs, $estado, $CentroCosto, $cod_cargo";*/
        
        if ($sp=="SW_actualiza_datos_permiso"){
            $stmt = $mbd->prepare("EXEC $sp ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
        } else {
            $stmt = $mbd->prepare("EXEC $sp ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?");
        }
        $stmt->bindParam(1, $cedula, PDO::PARAM_INT,10);
        $stmt->bindParam(2, $fini1, PDO::PARAM_STR,10);
        $stmt->bindParam(3, $ffin2, PDO::PARAM_STR,10);
        $stmt->bindParam(4, $turno, PDO::PARAM_INT,10);
        $stmt->bindParam(5, $sh, PDO::PARAM_INT,10);
        $stmt->bindParam(6, $npermiso, PDO::PARAM_INT,10);
        $stmt->bindParam(7, $cod_adam , PDO::PARAM_INT,10);
        $stmt->bindParam(8, $cod_ubicacion, PDO::PARAM_STR,4);
        $stmt->bindParam(9, $clase_nomina, PDO::PARAM_STR,2);
        $stmt->bindParam(10, $relacion_laboral , PDO::PARAM_STR,1);
        $stmt->bindParam(11, $firma, PDO::PARAM_STR,12);
        $stmt->bindParam(12, $h1, PDO::PARAM_STR,5);
        $stmt->bindParam(13, $h2, PDO::PARAM_STR,5);
        $stmt->bindParam(14, $PeriodoPago, PDO::PARAM_INT,10);
        $stmt->bindParam(15, $autorizado, PDO::PARAM_INT,10);
        $stmt->bindParam(16, $autorizado2, PDO::PARAM_STR,10);
        $stmt->bindParam(17, $Obs, PDO::PARAM_STR,255); 
        if ($sp=="SW_actualiza_datos_permiso"){
            $stmt->bindParam(18, $estado, PDO::PARAM_STR,1);
            $stmt->bindParam(19, $CentroCosto, PDO::PARAM_STR,10);
            $stmt->bindParam(20, $cod_cargo, PDO::PARAM_STR,4);
            $stmt->bindParam(21, $DocEntregado, PDO::PARAM_STR,1);
        }   

        $stmt->execute();
         
        $datosPermisos = $stmt->fetch();
        
        $stmt=null;
        $mbd=null;
        return $datosPermisos;

    } catch (Exception $e) {
        echo $e;
        return $datosPermisos=[];
    }
}

//----------------------------------------------------------------------
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
function nombre_gerencia($cedula){
$link2=Conex_Contancia_pgsql();
$query="select descripcion_unidad  descripcion_unidad from unidades a, trabajadores b where CAST(centro_costo AS INTEGER)=CAST(b.fkunidad AS INTEGER) and b.trabajador='".$cedula."'";
$result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
    $numReg = ejecutar_num_rows($result);
    if($numReg>0){
       while ($fila=ejecutar_fetch_array($result))
       {
          return  $fila[0];
        }
     }
ejecutar_close($link2);
}

//----------------------------------------------------------------------
function llenar_combo_permiso(){
	//$permisos=array(10=>'Falla de Transporte',20=>'Asistencia Control de Acceso',30=>'Adiestramiento',40=>'Consulta Medico',50=>'Evento',60=>'Permiso Sindical',70=>'Compensatorio',72=>'Reposo u Otros',80=>'Disfrute de Vacaciones',85=>'No procede');
	$permisos=array(10=>'Examenes Pre Vacacional',20=>'Justificativo',30=>'Permiso',40=>'Reposo',50=>'Suspendido',60=>'Transporte',70=>'Vacaciones');

//	$option= "<select name=\"cbopermiso\" onchange=\"limpiar_hora();\" id=\"cbopermiso\" data-width=\"80%\" data-size=\"5\" class=\"selectpicker\" data-hide-disabled=\"false\" data-live-search=\"true\">";
//        $option= "<select id='cboesperanza_cambiada'> </select>";
        $option.= "<option selected value=\"0\">Seleccione el Tipo de Ausencia</option>";
	foreach ($permisos as $fila => $valor) {
		$option.= "<option value='". $fila."'" ;
	      if ($fila[0] != "")
	        $option.= ">". $valor. "</option>";
	      else
	        $option.= ">". $valor. "</option>";
	      
	}

	      $option.= "</select>";
echo $option;
}

function buscarValor($array, $propiedadBuscar, $valorBuscar, $propiedadObtener) {
    foreach ($array as $item) {
        if (isset($item[$propiedadBuscar]) && $item[$propiedadBuscar] == $valorBuscar) {
            return isset($item[$propiedadObtener]) ? $item[$propiedadObtener] : null;
        }
    }
    return null;
}

//-----------------------------------------------------------------------
function nombre_inasistencia($codigo){
   switch ($codigo) {
        case 10:
            $vMes = "Examen Pre vacacional";
            break;
        case 20:
            $vMes = "Justificativo";
            break;
        case 30:
            $vMes = "Permiso";
            break;
        case 40:
            $vMes = "Reposo";
            break;
        case 50:
            $vMes = "Suspendido";
            break;
        case 60:
            $vMes = "Transporte";
            break;
        case 70:
            $vMes = "Vacaciones";
            break;
        case 0:
            $vMes = "";
            break;
    }
    return $vMes; 
}
//--------------------------------------------------------------------------.lista_trabajadores_sin_espacio
function lista_trabajadores($p){
    switch ($p) {
        case 'ct':
           $sistema_horario="";
//           $sistema_horario = " AND sistema_horario not in (13,19)";
            $nivel_jerarquico = " AND nivel_jerarquico>=7";
            break;
        default:
           $sistema_horario="";
           $nivel_jerarquico = "";
    } 
    if ($_SESSION['nivel_const']==2 ){
        $query="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE  relacion_laboral!='W' ".$sistema_horario.$nivel_jerarquico;
        $query.=" UNION ";
        $query.=" SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE  trabajador_sup in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas)".$sistema_horario.$nivel_jerarquico;
    }else{
        $query="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' ".$sistema_horario.$nivel_jerarquico;
        $query.=" UNION ";
        $query.=" SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE  trabajador_sup in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' )".$sistema_horario.$nivel_jerarquico;
    }  
    $query.=" order by 1";
//echo "<br>".$query;
    $conn=Conex_rrhh_pgsql();
    $stid = ejecutar_query($conn, $query);        
    $lista='';
    while ($fila = ejecutar_fetch_array($stid)){
         $lista.=  "'".str_pad($fila['trabajador'] ,10, " ", STR_PAD_LEFT)."', ";
    }
    pg_close($conn);
    $lista = substr($lista, 0, -2);
    return $lista;
       
}
//--------------------------------------------------------------------------
function lista_trabajadores_sin_espacio($p){
    switch ($p) {
        case 'ct':
           $sistema_horario="";
//           $sistema_horario = " AND sistema_horario not in (13,19)";
            $nivel_jerarquico = " AND nivel_jerarquico>=7";
            break;
        default:
           $sistema_horario="";
           $nivel_jerarquico = "";
    }
    if ($_SESSION['nivel_const']==2 ){
        $query="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE  relacion_laboral!='W' ".$sistema_horario.$nivel_jerarquico;
        $query.=" UNION ";
        $query.=" SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE  trabajador_sup in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas)".$sistema_horario.$nivel_jerarquico;
    }else{
 	    $query="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' UNION SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' union SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup) in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."')) ".$sistema_horario.$nivel_jerarquico;
    }
    $query.=" order by 1";
//echo "<br>Listado==r_trabajadores_sin_espacio: ".$query."<br>";
    $conn=Conex_rrhh_pgsql();
    $stid = ejecutar_query($conn, $query);
    $lista='';
    while ($fila = ejecutar_fetch_array($stid)){
         $lista.=  "'".$fila['trabajador']."', ";
    }
    pg_close($conn);
    $lista = substr($lista, 0, -2);
    return $lista;

}
//--------------------------------------------------------------------------
function llenar_lista_trabajadores_del_supervisor(){     
    $query="SELECT trabajador from lista_trabajadores_del_supervisor('".$_SESSION['user_session_const']."')";
    $query.=" ORDER BY nombre";
    //echo $query;
    $conn=Conex_rrhh_pgsql();
    $stid = ejecutar_query($conn, $query);
    $lista='';
    while ($fila = ejecutar_fetch_array($stid)){
         $lista.=  "'".$fila['trabajador']."', ";
    }
    pg_close($conn);
    $lista = substr($lista, 0, -2);
    return $lista;
}

//--------------------------------------------------------------------------
function llenar_combo_trabajadores($p){
    switch ($p) {
        case 'ct':
//            $sistema_horario = " AND sistema_horario not in (13,19) ";
            $sistema_horario = " ";
            $nivel_jerarquico = " AND nivel_jerarquico>=7";
            break;
        default:
           $sistema_horario="";
           $nivel_jerarquico = "";
    }  
/*//print "<br>".$_SESSION['nivel_const']."<br>"; 
    if ($_SESSION['nivel_const']==1 ){
        $query="select trabajador, nombre, nivel_jerarquico from adam_vw_dotacion_briqven_02_mas where trabajador>'0' and relacion_laboral!='w' ".$sistema_horario.$nivel_jerarquico;
        $query.=" union ";
        $query.=" select trabajador, nombre, nivel_jerarquico  from adam_vw_dotacion_briqven_02_mas where  trabajador_sup in (select trabajador from adam_vw_dotacion_briqven_02_mas)".$sistema_horario.$nivel_jerarquico;
    }else{
    	$query="SELECT trabajador, nombre, nivel_jerarquico  FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' ".$sistema_horario.$nivel_jerarquico;
    	$query.=" UNION ";
    	$query="SELECT trabajador, nombre, nivel_jerarquico  FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."' union SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup) in (SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador_sup)='".$_SESSION['cedula_session_const']."')) ".$sistema_horario.$nivel_jerarquico;
    }  
        $query.=" order by nombre";
//print $query;
    $conn=Conex_rrhh_pgsql();
        $stid = ejecutar_query($conn, $query);        
        $option='';
        while ($fila = ejecutar_fetch_array($stid)){
//echo  ejecutar_num_rows($stid);
            $option.= "<option value='". $fila['trabajador']."'" ;
            $option.= ">". $fila['trabajador']." - ".$fila['nombre']."</option>";
        }
        pg_free_result($stid);
        pg_close($conn);
*/

        $link1=Conex_Contancia_pgsql();
        $acceso=permiso_usuario($link1, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);
        if ($_SESSION['nivel_const']==1 || $acceso){        

            $option1=llenar_combo_trabajadoresTodos($link1);
        }
        else{
            $option1=llenar_combo_trabajadores_del_supervisor();
        }  
      


      pg_close($link1);        
    return $option1;

}
//--------------------------------------------------------------------------
function llenar_combo_trabajadores_del_supervisor(){

    switch ($_SESSION['nivel_jerarquico']) {
        case 1:          
            $filtro = "direccion";            
            break;
        case 2:          
            $filtro = "direccion";            
            break;
        case 3:          
            $filtro = "gergral";            
            break;
        case 4:          
            $filtro = "gerencia";            
            break;
        case 5:          
            $filtro = "depto";            
            break;
        case 6:          
            $filtro = "coordina";            
            break;
        default:          
            $filtro = "coordina";
    }  

    
        $query="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."'";
        $query.=" UNION ";
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE trim(trabajador_sup) = '".$_SESSION['cedula_session_const']."'";
        $query.=" UNION ";
        $query.= "SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') 
                       AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."')";
        $query.=" UNION ";
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM supervisores_trabajadores 
        WHERE trabajador_sup = '".$_SESSION['cedula_session_const']."'";
        $query.=" ORDER BY nombre";
        //echo $query;
        $conn=Conex_rrhh_pgsql();
        $stid = ejecutar_query($conn, $query);        
        $option='';
        $arrayTrabajadores = array();
        while ($fila = ejecutar_fetch_array($stid)){
            array_push($arrayTrabajadores, $fila['trabajador']);
            $option.= "<option value='". $fila['trabajador']."'" ;
            $option.= ">". $fila['trabajador']." - ".$fila['nombre']."</option>";
        }
        $_SESSION['arrayTrab']=$arrayTrabajadores;
        //print_r($arrayTrabajadores) ;
        pg_free_result($stid);
        pg_close($conn);
         
    return $option;

}
//--------------------------------------------------------------------------
function llenar_combo_trabajadoresTodos($conn){
   
    $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";

    //$conn=Conex_Contancia_pgsql();
    $stid = ejecutar_query($conn, $query);        
    $option='';
    $arrayTrabajadores = array();
    while ($fila = ejecutar_fetch_array($stid)){
        array_push($arrayTrabajadores, $fila['trabajador']);
        $option.= "<option value='". $fila['trabajador']."'" ;
        $option.= ">". $fila['trabajador']." - ".$fila['nombre']."</option>";
    }
    $_SESSION['arrayTrab']=$arrayTrabajadores;
    pg_free_result($stid);
    //pg_close($conn);

    return $option;
}
//--------------------------------------------------------------------------
function nombre_trabajadores($trabajador){
        $query="SELECT TRABAJADOR, NOMBRE FROM VW_DOTACION_BRIQVEN_02_MAS WHERE trim(TRABAJADOR)='".trim($trabajador)."'";
        $query.=" order by nombre";
        //print $query;
        $conn=Conex_oramprd();
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $nombre='';
        while (($fila = oci_fetch_array($stid, OCI_BOTH)) != false){
             $nombre =  $fila['NOMBRE'];
        }
//        $trabajadores = substr($lista, 0, -2);
        if ($nombre==''){
            $query="SELECT TRABAJADOR, NOMBRE FROM adam_vw_dotacion_briqven_02_tmp WHERE trim(TRABAJADOR)='".trim($trabajador)."'";
            //print "<br>".$query;
            $conn=Conex_Contancia_pgsql();
            $stid = ejecutar_query($conn, $query);        
            while ($fila = ejecutar_fetch_array($stid)){
                $nombre =  $fila['nombre'];
            }
            pg_free_result($stid);
            pg_close($conn);
        }
    return $nombre;
}
//--------------------------------------------------------------------------

function nombre_gerente($trabajador, $ccosto){

    $query="select trabajador, nombre, desc_puesto, grado_trab from vw_dotacion_briqven_02_mas where grado_trab >= 38 and puesto in (select puesto from puestos_sid where catsal>=38) and gerencia= (select gerencia from vw_dotacion_briqven_02_mas where ltrim(trabajador)='".$trabajador."')  order by grado_trab desc";
       
    $conn=Conex_oramprd();
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $data = array();
    while ($fila = oci_fetch_array($stid, OCI_BOTH)) {
        $data=$fila;
        if ($fila[3]==44){
            unset($data);
            return  $data;
        }
        $pos = strpos($fila[2], "(E)");
        if ($pos!==false)
            return  $data;        
    }

    if (count($data)==0){
        $query="select v.trabajador, v.nombre, v.desc_puesto, v.grado_trab from vw_dotacion_briqven_02_mas v where v.grado_trab >= 41 and v.puesto in (select puesto from puestos_sid where to_number(catsal)>=41) and v.gergral= (select gergral from vw_dotacion_briqven_02_mas where ltrim(trabajador)='".$trabajador."')  and v.grado_trab = (select max(to_number(catsal)) from puestos_sid  where descripcion=v.desc_puesto) order by v.grado_trab asc";

        if ($ccosto==61606 || $ccosto==61608){
            $query="select v.trabajador, v.nombre, v.desc_puesto, v.grado_trab
                from vw_dotacion_briqven_02_mas v 
                where v.grado_trab >= 41 
                and v.puesto in (select puesto from puestos_sid where to_number(catsal)>=41) 
                and v.gergral= (select gergral from vw_dotacion_briqven_02_mas where ltrim(trabajador)='".$trabajador."')  
                and v.grado_trab = (select  max(v.grado_trab) as maix
                from vw_dotacion_briqven_02_mas v 
                where v.grado_trab >= 41 
                and v.puesto in (select puesto from puestos_sid where to_number(catsal)>=41) 
                and v.gergral= (select gergral from vw_dotacion_briqven_02_mas where ltrim(trabajador)='".$trabajador."')) 
                order by v.grado_trab asc";
        }

        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $i=0;
        while ($fila = oci_fetch_array($stid, OCI_BOTH)) {
            $data=$fila;
            $pos = strpos($fila[2], "(E)");
            if ($pos!==false){ 
                return  $data;
            }
            //if ($i==0){}
            $i++;
        }
    }            
        
    $conn = null;
    $stid = null ; 

    return  $data;
}

//--------------------------------------------------------------------------
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
function RestarHoras($horaini,$horafin)
{
    $f1 = new DateTime($horaini);
    $f2 = new DateTime($horafin);
    $d = $f1->diff($f2);
    return $d->format('%H:%I:%S');
}
//------------------------------------------------------------------------


function auditar($operacion, $user,$lag){    
    $idaud=0; 
    $insertarAud="INSERT INTO tbl_auditorias (operacion,login,fecha) VALUES ('".$operacion."', '".$user."', NOW())";
    $result = pg_query($lag,$insertarAud);
    $idaud=pg_affected_rows($result);
       
    return $idaud;
}

//--------------------------------------------------------------------------
function mostrar_esperanza($esperanza)
{
   switch ($esperanza) {
        case "LL:LL":
            $clase="label label-success";
            $nesperanza="LIBRE";
            break;
        case "VV:VV":
            $clase="label label-success";
            $nesperanza="VACACIONES";
            break;
        case "FF:FF":
            $clase="label label-success";
            $nesperanza="FERIADO";
            break;
        case "PP:PP":
            $clase="label label-warning";
            $nesperanza="PERMISO";
            break;
        case "RR:RR":
            $clase="label label-warning";
            $nesperanza="REPOSO";
            break;
        case "CS:CS":
            $clase="label label-info";
            $nesperanza="COMISION";
            break;
        case "SD:SD":
            $clase="label label-danger";
            $nesperanza="SUSPENCION";
            break;
        default:
           $clase="";
           $nesperanza=$esperanza;
    }
//    return $clase;
    return array($clase, $nesperanza);


}
//------------------------------------------------------------------------

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}

function formato_fecha($fecha, $separador){
    $array= explode($separador, $fecha);
    $newFecha = $array[2].'-'.$array[1].'-'.$array[0];
    return $newFecha;
}

//-------------------------------------------------------------------------
function crear_pdf (){
    // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Matesi');
  $pdf->SetTitle('Control de Acceso');
  $pdf->SetSubject('');
  $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
  // set default header data
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
  // set header and footer fonts
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  // set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  // set image scale factor
  $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 255)));

  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  // set some language-dependent strings (optional)
  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
  }
  //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetMargins(8, 8, 8, true);
  // set font
  $pdf->SetFont('courier', 'I', 7);

  return $pdf;
  }


  /////////////////////////////////////////////////////////////////////////////////////////////////////////////
function construir_pdf($cuerpo){
    require_once('tcpdf/tcpdf.php');
    class MYPDF extends TCPDF {
    public function Header() {
          $this->SetFont('courier', 'B', 8);
     }
    public function Footer() {
              $this->SetY(-15);
              $this->SetFont('courier', 'I', 8);
              $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
          }
    }
    $pdf = crear_pdf ();
    $pdf->AddPage();
    $txt=utf8_encode($cuerpo);
    $pdf->writeHTML($txt, false, 0, true, true, '');
    //$pdf->Output();
    $dirpdf=dirname(__FILE__)."/Reportes/".date("YMdGHs").".pdf";
    $pdf->Output($dirpdf, 'F');
    return $dirpdf;
}



//--------------------------------------------------------------------------
function nombre_tipo_suspension($id){
    $link2=Conex_Contancia_pgsql();
    $query="SELECT id_tipo_suspensiones, tipo_suspension FROM public.tipo_suspensiones WHERE id_tipo_suspensiones=".$id;
    $result = ejecutar_query($link2, $query) or die("Error en la Consulta SQL: ".$query);
        $numReg = ejecutar_num_rows($result);
        if($numReg>0){
           while ($fila=ejecutar_fetch_array($result))
           {
              return  $fila[1];
            }
         }
    ejecutar_close($link2);
}


//--------------------------------------------------------------------------
function antiguedad($fecha_inicio,$fecha_final)
{
    $date = str_replace('/', '-', $fecha_inicio);
    $date2 = str_replace('/', '-', $fecha_final);
    $f1 = new DateTime($date);
    $f2 = new DateTime($date2);
    //$d = date('d-m-Y').', '.$date;
    $d = $f1->diff($f2);
    return $d->format('%Y a&ntilde;os %M meses y %D días');
}

//--------------------------------------------------------------------------
function sexo($sexo)
{
    if ($sexo==1) return 'M';
    if ($sexo==2) return 'F';
}

//--------------------------------------------------------------------------
function calcular_edad($fecha)
{
    //print $fecha;
    list($dia,$mes,$ano) = explode("/",$fecha);
    $ano_diferencia  = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia   = date("d") - $dia;
    if ($mes_diferencia < 0)
      $ano_diferencia--;
    elseif ($mes_diferencia = 0 && $dia_diferencia < 0 )
      $ano_diferencia--;
/*
    if ($dia_diferencia < 0 || $mes_diferencia < 0)
      $ano_diferencia--;
*/
    return $ano_diferencia;  
}
//--------------------------------------------------------------------------
function llenar_lista_trabajadores_del_supervisor_sin_comilla(){

    switch ($_SESSION['nivel_jerarquico']) {
        case 1:          
            $filtro = "direccion";            
            break;
        case 2:          
            $filtro = "direccion";            
            break;
        case 3:          
            $filtro = "gergral";            
            break;
        case 4:          
            $filtro = "gerencia";            
            break;
        case 5:          
            $filtro = "depto";            
            break;
        case 6:          
            $filtro = "coordina";            
            break;
        default:          
            $filtro = "coordina";
    }  

    
        $query="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."'";
        $query.=" UNION ";
        $query.="SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE trim(trabajador_sup) = '".$_SESSION['cedula_session_const']."'";
        $query.=" UNION ";
        $query.= "SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') 
                       AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."')";
        $query.=" UNION ";
        $query.="SELECT trabajador FROM supervisores_trabajadores 
        WHERE trabajador_sup = '".$_SESSION['cedula_session_const']."'";
        //$query.=" ORDER BY nombre";
        //echo $query;
    $conn=Conex_rrhh_pgsql();
    $stid = ejecutar_query($conn, $query);
    $lista='';
    $fila = ejecutar_fetch_array($stid);
    while ($fila = ejecutar_fetch_array($stid)){
         $lista.=  $fila['trabajador'].", ";
    }
    pg_close($conn);
    $lista = substr($lista, 0, -2);
    return $lista;

}


//--------------------------------------------------------------------------
function SW_GRABA_ST_CEDULA($cedula,$fecha, $Inicio_ST1, $Fin_ST1, $st2, $st2, $causalST, $cod2, $CS, $obsSt,$razon)
{

    $qry_sitt="select a.cedula, a.fecha, a.turno, a.semana,  a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_real3, a.salida_real3, a.entrada_real4, a.salida_real4, a.entrada_real5, a.salida_real5, a.entrada_real6, a.salida_real6, a.entrada_real7, a.salida_real7, a.entrada_real8, a.salida_real8, a.entrada_real9, a.salida_real9, a.entrada_real10, a.salida_real10, a.entrada_real11, a.salida_real11, a.entrada_real12, a.salida_real12, a.comedorreal1, a.comedorreal2, a.comedorreal3, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, a.pagocomida, a.inicio_st1, a.fin_st1, a.causal_st1, a.inicio_st2, a.fin_st2, a.causal_st2, a.inicio_ausencia, a.fin_ausencia, a.horasnetapresencia, a.horasnetaausencia, a.horas_st, a.autorizado1, a.fecha_autor1, a.autorizado2, a.fecha_autor2, a.inicio_entrena1, a.fin_entrena1, a.inicio_entrena2, a.fin_entrena2, a.horas_entrenamiento, a.inicio_dlt1, a.fin_dlt1, a.inicio_dlt2, a.fin_dlt2, a.horas_dlt, a.causa_dlt1, a.causa_dlt2, a.cod_ausencia, a.tardio_bus, a.sustitucion, a.cedula_sustituido, a.puesto_sustituido, a.inicio_sustitucion, a.fin_sustitucion, a.causal_sustitucion, a.coderror, a.cam_des_u_cierre, a.cam_des_d_pago, a.horasnetsica, a.horasnetsicast, a.ced_dlt, a.admin_tbus, a.ced_sustitucion, a.ced_st, a.pagocomidast, a.ced_pagocomidast, a.horsustitucion, a.ced_ento
    from sw_hoja_de_tiempo_real a
    where a.fecha = '".$fecha."' and a.cedula=".$cedula;

    $cn     = Conectarse_sitt();
    $stmt1  = $cn->query($qry_sitt);
    $contar = $stmt1->columnCount(); 
    $row    = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT); 
 

    // CALCULAMOS EL TIEMPO DE HORAS EXTRAS
    /*$inicio = new datetime($Inicio_ST1);
    $fin    = new datetime($Fin_ST1);
    
    $diferencia = $inicio -> diff($fin);
    $resultado  = $diferencia->format("%h").",".$diferencia-> format("%i");
    */
    $resultado = calcular_horas_extras2($fecha,$Inicio_ST1,$Fin_ST1);

    if ($resultado<0)
        $resultado = 24 + $resultado;

    /***********************************************************************/
    /*          VERIFICAMOS QUE SI EXISTE O NO EN LA BD LOCAL              */
    /***********************************************************************/
    $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
    $link           = Conex_Contancia_pgsql();
    $result         = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
    $contar         = ejecutar_num_rows($result);
    if ($contar>0){
        $query_update="UPDATE sw_hoja_de_tiempo_real SET ";
        $query_update.="inicio_st1='".$Inicio_ST1."',";
        $query_update.="fin_st1='".$Fin_ST1."',";
        $query_update.="causal_st1= '".$causalST."',";
        $query_update.="horas_st='".$resultado."',";
        $query_update.="fecha_carga_st_dlt='".date("Y-m-d H:i:s")."',";
        $query_update.="ced_st=".$CS;
        $query_update.=" where cedula = '".$cedula."' ";
        $query_update.="and fecha= '".$fecha."'";
        //print "<br>".$quey_update;        
        $result = ejecutar_query($link, $query_update) or die("Error en la Consulta SQL: ".$query_update);
        if (pg_affected_rows($result)==0){
            $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
        }else{
            $query_update_observaciones_stdlt="UPDATE sw_observaciones_stdlt SET";
            $query_update_observaciones_stdlt.=" observaciones='".$obsSt."',";
            $query_update_observaciones_stdlt.=" razon='".$razon."'";
            $query_update_observaciones_stdlt.=" where cedula = '".$cedula."'";
            $query_update_observaciones_stdlt.=" and fecha= '".$fecha."'";
            $result = ejecutar_query($link, $query_update_observaciones_stdlt) or die("Error en la Consulta SQL: ".$query_update_observaciones_stdlt);
            if (pg_affected_rows($result)==0){
                $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla observaciones_stdlt";
            }else{
                $resultado='2';
            }
        }
    }else{
        /**********************************************************************************************/
        /* DE NO EXISTIR REGISTROS CARGADOS SE INSERTAR LOS DATOS EN LA TABLA SW_HOJA_DE_TIEMPO_REAL  */
        /**********************************************************************************************/
        $quey_insert = "INSERT INTO sw_hoja_de_tiempo_real (cedula, fecha, entrada_real1, salida_real1, entrada_real2, salida_real2, entrada_real3, salida_real3, entrada_real4, salida_real4, entrada_real5, salida_real5, entrada_real6, salida_real6, entrada_real7, salida_real7, entrada_real8, salida_real8, entrada_real9, salida_real9, entrada_real10, salida_real10, entrada_real11, salida_real11, entrada_real12, salida_real12, entrada_esperada1, salida_esperada1, entrada_esperada2, salida_esperada2, inicio_st1, fin_st1, causal_st1, horasnetapresencia, horasnetaausencia, horas_st, coderror, ced_st, fecha_carga_st_dlt) VALUES(".$row['cedula'].", '".$row['fecha']."', '".$row['entrada_real1']."', '".$row['salida_real1']."', '".$row['entrada_real2']."', '".$row['salida_real2']."', '".$row['entrada_real3']."', '".$row['salida_real3']."', '".$row['entrada_real4']."', '".$row['salida_real4']."', '".$row['entrada_real5']."', '".$row['salida_real5']."', '".$row['entrada_real6']."', '".$row['salida_real6']."', '".$row['entrada_real7']."', '".$row['salida_real7']."', '".$row['entrada_real8']."', '".$row['salida_real8']."', '".$row['entrada_real9']."', '".$row['salida_real9']."', '".$row['entrada_real10']."', '".$row['salida_real10']."', '".$row['entrada_real11']."', '".$row['salida_real11']."', '".$row['entrada_real12']."', '".$row['salida_real12']."' , '".$row['entrada_esperada1']."', '".$row['salida_esperada1']."', '".$row['entrada_esperada2']."', '".$row['salida_esperada2']."', '".$Inicio_ST1."', '".$Fin_ST1."', '".$causalST."', ".$row['horasnetapresencia'].", ".$row['horasnetaausencia'].", '".$resultado."', ".$row['coderror'].", ".$CS.",'".date("Y-m-d H:i:s")."')";

        //QUERY PARA INSERTAR LOS DATOS EN LA TABLA SW_OBSERVACIONES_STDLT
        $quey_insert2 = "insert into SW_OBSERVACIONES_STDLT values (".$row['cedula'].", '".$row['fecha']."', '".$obsSt."','".$razon."')";

        $link=Conex_Contancia_pgsql();
        $result = pg_query($link,$quey_insert);
        if (pg_affected_rows($result)==0)
        {
            echo("ERROR");
            die(" Consulta SQL: ".$quey_insert);
        }else{
            $result = pg_query($link,$quey_insert2);
            $resultado='1';            
        }
        //$ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
    }
    pg_close($link);

    //echo "$(\"#Horas_ST\").val(\"" . $resultado . "\");\n" ;


    return $resultado;

}

//--------------------------------------------------------------------------
function SW_BORRA_ST_CEDULA($cedula,$fecha)
{
        $link=Conex_Contancia_pgsql();

         
        /***************************************************************************************/
        /*             VERIFICAMOS SI TIENE OTRAS INCIDENCIAS CARGADAS                         */
        /***************************************************************************************/
        $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
       //print "<br>".$query_consulta;        
        //$result = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
        $result = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
        $contar = ejecutar_num_rows($result);
        if ($contar>0){
            $row    = ejecutar_fetch_array($result);
            if ($row['inicio_dlt1']=='' || $row['fin_dlt1']==NULL || $row['horas_dlt']==NULL || $row['causal_st1']==NULL || $row['ced_dlt']==NULL){
                $query_delete="DELETE from sw_hoja_de_tiempo_real ";
                $query_delete.="where cedula = '".$cedula."' ";
                $query_delete.="and fecha= '".$fecha."'";
                //print "<br>".$query_delete;
                $result = ejecutar_query($link, $query_delete) or die("Error en la Consulta SQL: ".$quey_update);
                if (pg_affected_rows($result)==1)
                {
                    //QUERY PARA BORRAR LOS DATOS EN LA TABLA SW_OBSERVACIONES_STDLT
                    $quey_delete2 = "delete from SW_OBSERVACIONES_STDLT WHERE cedula= ".$row['cedula']." and fecha = '".$row['fecha']."'";
                    //print "<br>".$quey_delete2;
                    $result = pg_query($link,$quey_delete2);
                    if (pg_affected_rows($result)==0)
                    {
                        $resultado="Ocurrio en error mientras de eliminaba el registro de la tabla SW_OBSERVACIONES_STDLT";
                    }else{
                        $resultado='Eliminacion del registro se efectuo de manera exitosa';
                    }
                }else{
                     $resultado="Ocurrio en error mientras de eliminaba el registro de la tabla sw_hoja_de_tiempo_real";
                }
            }else{
                $quey_update="UPDATE sw_hoja_de_tiempo_real SET ";
                $quey_update.="inicio_st1=NULL, ";
                $quey_update.="fin_st1=NULL,";
                $quey_update.="inicio_st2=NULL, ";
                $quey_update.="fin_st2=NULL,";
                $quey_update.="causal_st1='',";
                $quey_update.="horas_st=NULL,";
                $quey_update.="ced_st=NULL ";
                $quey_update.="where cedula = '".$cedula."' ";
                $quey_update.="and fecha= '".$fecha."'";
                //print "<br>".$quey_update;        

                $result = ejecutar_query($link, $quey_update) or die("Error en la Consulta SQL: ".$quey_update);
                if (pg_affected_rows($result)==0){
                    $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
                }else{
                    $resultado='Eliminacion del registro se efectuo de manera exitosa';
                }
            }

        }else{
            $resultado="";
        }

        pg_close($link);

    return $resultado;

}

//--------------------------------------------------------------------------
function CONSULTAR_STDLT_LOCAL($cedula, $fecha, $link_CONSULTAR_STDLT_LOCAL)
{
 
    $resultado='0';
    $qry="select a.cedula, a.fecha, a.coderror as codigo_error, a.cod_ausencia, a.horasnetapresencia, a.horasnetaausencia, b.sistema_horario, b.desc_puesto as descripcion_puesto, b.ccosto, b.detalle_ccosto as desc_ccosto, a.PagoComida,  a.Inicio_ST1 as st1_inicio,  a.Fin_St1 as st1_fin, a.horas_st as st_hora, a.Inicio_ausencia as ausencia_inicio,  a.Fin_ausencia as ausencia_fin, a.Autorizado1, a.Inicio_DLT1, a.Fin_DLT1, a.Horas_Dlt, a.Sustitucion, a.Cedula_Sustituido, a.Causal_Sustitucion,  a.autorizado1, a.autorizado2,  Fecha_autor1, Fecha_autor2, rechazado_stdlt, f.Observaciones as motivo_st, a.ced_dlt, a.ced_st, a.validado_stdlt from sw_hoja_de_tiempo_real a INNER JOIN adam_vw_dotacion_briqven_02_mas b on a.cedula = cast(b.trabajador as integer) left join SW_OBSERVACIONES_STDLT f on a.cedula=f.cedula and a.fecha=f.fecha where a.fecha = '".$fecha."' and a.cedula=".$cedula; 
//print "<br>".$qry;
    //$link_CONSULTAR_STDLT_LOCAL   = Conex_Contancia_pgsql();
    $result = ejecutar_query($link_CONSULTAR_STDLT_LOCAL, $qry) or die("Error en la Consulta SQL: ".$qry);
    $contar = ejecutar_num_rows($result);
if ($contar>0){
        $row    = ejecutar_fetch_array($result);
        if ($row['autorizado1']!='' && $row['autorizado1']!=NULL)
            $resultado='4';
        elseif ($row['validado_stdlt']!='' && $row['validado_stdlt']!=NULL && $row['validado_stdlt']!=0)
            $resultado='3';
        elseif($row['autorizado2']!='' && $row['autorizado2']!=NULL)
            $resultado='2';
        elseif($row['rechazado_stdlt']!='' && $row['rechazado_stdlt']!=NULL && $row['rechazado_stdlt']!=0 && $row['autorizado2']!='' && $row['autorizado2']!=NULL)
            $resultado='0';
        elseif(($row['rechazado_stdlt']!='' && $row['rechazado_stdlt']!=NULL && $row['rechazado_stdlt']!=0) && ($row['autorizado2']=='' || $row['autorizado2']==NULL))
            $resultado='5';
        else
           $resultado='1';  //CARGADO
    }else{
        $resultado='';
    }
    //pg_close($link_CONSULTAR_STDLT_LOCAL);
//print  "<br>$resultado:".$resultado;
    return $resultado;

}

//--------------------------------------------------------------------------
function llenar_lista_trabajadoresTodos($conn){
   
    $query="SELECT trabajador, regexp_replace(nombre, '/', ' ', 'g') as nombre FROM v_trabajadores_activos order by nombre";

    //$conn=Conex_Contancia_pgsql();
    $stid = ejecutar_query($conn, $query);        
    $lista='';
    $arrayTrabajadores = array();
    while ($fila = ejecutar_fetch_array($stid)){
          $lista.=  $fila['trabajador'].", ";
    }
    $_SESSION['arrayTrab']=$arrayTrabajadores;
    pg_free_result($stid);
    //pg_close($conn);

    return $lista;
}



//--------------------------------------------------------------------------
function SW_GRABA_CAMBIO_DLT_CEDULA($cedula,$fecha, $Inicio_DLT1, $Fin_DLT1, $st2, $st2, $causalDLT, $cod2, $CS, $obsSt, $razon)
{

    $qry_sitt="select a.cedula, a.fecha, a.turno, a.semana,  a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_real3, a.salida_real3, a.entrada_real4, a.salida_real4, a.entrada_real5, a.salida_real5, a.entrada_real6, a.salida_real6, a.entrada_real7, a.salida_real7, a.entrada_real8, a.salida_real8, a.entrada_real9, a.salida_real9, a.entrada_real10, a.salida_real10, a.entrada_real11, a.salida_real11, a.entrada_real12, a.salida_real12, a.comedorreal1, a.comedorreal2, a.comedorreal3, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, a.pagocomida, a.inicio_st1, a.fin_st1, a.causal_st1, a.inicio_st2, a.fin_st2, a.causal_st2, a.inicio_ausencia, a.fin_ausencia, a.horasnetapresencia, a.horasnetaausencia, a.horas_st, a.autorizado1, a.fecha_autor1, a.autorizado2, a.fecha_autor2, a.inicio_entrena1, a.fin_entrena1, a.inicio_entrena2, a.fin_entrena2, a.horas_entrenamiento, a.inicio_dlt1, a.fin_dlt1, a.inicio_dlt2, a.fin_dlt2, a.horas_dlt, a.causa_dlt1, a.causa_dlt2, a.cod_ausencia, a.tardio_bus, a.sustitucion, a.cedula_sustituido, a.puesto_sustituido, a.inicio_sustitucion, a.fin_sustitucion, a.causal_sustitucion, a.coderror, a.cam_des_u_cierre, a.cam_des_d_pago, a.horasnetsica, a.horasnetsicast, a.ced_dlt, a.admin_tbus, a.ced_sustitucion, a.ced_st, a.pagocomidast, a.ced_pagocomidast, a.horsustitucion, a.ced_ento
    from sw_hoja_de_tiempo_real a
    where a.fecha = '".$fecha."' and a.cedula=".$cedula;

    $cn     = Conectarse_sitt();
    $stmt1  = $cn->query($qry_sitt);
    $contar = $stmt1->columnCount(); 
    $row    = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT); 
 

    // CALCULAMOS EL TIEMO DE HORAS EXTRAS
 /*   $inicio = new datetime($Inicio_DLT1);
    $fin    = new datetime($Fin_DLT1);
    
    $diferencia = $inicio -> diff($fin);
    $resultado  = $diferencia->format("%h").".".$diferencia-> format("%i");
*/
    $resultado = calcular_horas_extras2($fecha,$Inicio_DLT1,$Fin_DLT1);

    if ($resultado<0)
        $resultado = 24 + $resultado;
//print "<br>resultado:".$resultado;
    if ($resultado>4)
        $resultado=8;
    /***********************************************************************/
    /*          VERIFICAMOS QUE SI EXISTE O NO EN LA BD LOCAL              */
    /***********************************************************************/
    $link=Conex_Contancia_pgsql();
    $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
    $result         = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
    $contar         = ejecutar_num_rows($result);
    if ($contar>0){
        $quey_update="UPDATE sw_hoja_de_tiempo_real SET ";
        $quey_update.="inicio_dlt1='".$Inicio_DLT1."',";
        $quey_update.="fin_dlt1='".$Fin_DLT1."',";
        $quey_update.="causa_dlt1= '".$causalDLT."',";
        $quey_update.="horas_dlt='".$resultado."',";
        $quey_update.="ced_dlt=".$CS." ";
        $quey_update.="where cedula = '".$cedula."' ";
        $quey_update.="and fecha= '".$fecha."'";
        //print "<br>".$quey_update;        
        $result = ejecutar_query($link, $quey_update) or die("Error en la Consulta SQL: ".$quey_update);
        if (pg_affected_rows($result)==0){
            $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
        }else{
            $quey_update_OBS="UPDATE SW_OBSERVACIONES_STDLT SET ";
            $quey_update_OBS.="observaciones='".$obsSt."', ";
            $quey_update_OBS.="razon='".$razon."' ";
            $quey_update_OBS.="where cedula = '".$cedula."' ";
            $quey_update_OBS.="and fecha= '".$fecha."'"; 
            //print  $quey_update_OBS;          
            $result = pg_query($link,$quey_update_OBS);
            if (pg_affected_rows($result)==0)
            {
                echo("ERROR");
                die(" Consulta SQL: ".$quey_update_OBS);
            }else{
                $resultado='2';
                //$result = pg_query($link,$quey_update_OBS);
            }            
            //$resultado='1';
        }
    }else{
        /**********************************************************************************************/
        /* DE NO EXISTIR REGISTROS CARGADOS SE INSERTAR LOS DATOS EN LA TABLA SW_HOJA_DE_TIEMPO_REAL  */
            /**********************************************************************************************/
        $quey_insert = "INSERT INTO sw_hoja_de_tiempo_real (cedula, fecha, entrada_real1, salida_real1, entrada_real2, salida_real2, entrada_real3, salida_real3, entrada_real4, salida_real4, entrada_real5, salida_real5, entrada_real6, salida_real6, entrada_real7, salida_real7, entrada_real8, salida_real8, entrada_real9, salida_real9, entrada_real10, salida_real10, entrada_real11, salida_real11, entrada_real12, salida_real12, entrada_esperada1, salida_esperada1, entrada_esperada2, salida_esperada2, inicio_dlt1, fin_dlt1, causa_dlt1, horasnetapresencia, horasnetaausencia, horas_dlt, coderror, ced_dlt, fecha_carga_st_dlt) VALUES(".$row['cedula'].", '".$row['fecha']."', '".$row['entrada_real1']."', '".$row['salida_real1']."', '".$row['entrada_real2']."', '".$row['salida_real2']."', '".$row['entrada_real3']."', '".$row['salida_real3']."', '".$row['entrada_real4']."', '".$row['salida_real4']."', '".$row['entrada_real5']."', '".$row['salida_real5']."', '".$row['entrada_real6']."', '".$row['salida_real6']."', '".$row['entrada_real7']."', '".$row['salida_real7']."', '".$row['entrada_real8']."', '".$row['salida_real8']."', '".$row['entrada_real9']."', '".$row['salida_real9']."', '".$row['entrada_real10']."', '".$row['salida_real10']."', '".$row['entrada_real11']."', '".$row['salida_real11']."', '".$row['entrada_real12']."', '".$row['salida_real12']."' , '".$row['entrada_esperada1']."', '".$row['salida_esperada1']."', '".$row['entrada_esperada2']."', '".$row['salida_esperada2']."', '".$Inicio_DLT1."', '".$Fin_DLT1."', '".$causalDLT."', ".$row['horasnetapresencia'].", ".$row['horasnetaausencia'].", '".$resultado."', ".$row['coderror'].", ".$CS.",'".date("Y-m-d H:i:s")."')";

        //QUERY PARA INSERTAR LOS DATOS EN LA TABLA SW_OBSERVACIONES_STDLT
        $quey_insert2 = "insert into SW_OBSERVACIONES_STDLT values (".$row['cedula'].", '".$row['fecha']."', '".$obsSt."','".$razon."')";

        $link=Conex_Contancia_pgsql();
        $result = pg_query($link,$quey_insert);
        if (pg_affected_rows($result)==0)
        {
            echo("ERROR");
            die(" Consulta SQL: ".$quey_insert);
        }else{
            $resultado='1';
            $result = pg_query($link,$quey_insert2);
        }
        //$ud=auditar("Registrar fichada de fecha=".$fecha_fichada, $_SESSION['user_session_const'],$link);
    }
    pg_close($link);

    //echo "$(\"#Horas_ST\").val(\"" . $resultado . "\");\n" ;


    return $resultado;

}


//--------------------------------------------------------------------------
function SW_BORRA_DLT_CEDULA($cedula,$fecha)
{
        $link=Conex_Contancia_pgsql();

         
        /***************************************************************************************/
        /*             VERIFICAMOS SI TIENE OTRAS INCIDENCIAS CARGADAS                         */
        /***************************************************************************************/
        $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
       //print "<br>".$query_consulta;        
        //$result = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
        $result = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
        $contar = ejecutar_num_rows($result);
        if ($contar>0){
            $row    = ejecutar_fetch_array($result);
            if ($row['inicio_st1']=='' || $row['fin_st1']==NULL || $row['horas_st']==NULL || $row['causal_s1']==NULL || $row['ced_st']==NULL){
                $query_delete="DELETE from sw_hoja_de_tiempo_real ";
                $query_delete.="where cedula = '".$cedula."' ";
                $query_delete.="and fecha= '".$fecha."'";
                //print "<br>".$query_delete;
                $result = ejecutar_query($link, $query_delete) or die("Error en la Consulta SQL: ".$quey_update);
                if (pg_affected_rows($result)==1)
                {
                    //QUERY PARA BORRAR LOS DATOS EN LA TABLA SW_OBSERVACIONES_STDLT
                    $quey_delete2 = "delete from SW_OBSERVACIONES_STDLT WHERE cedula= ".$row['cedula']." and fecha = '".$row['fecha']."'";
                    //print "<br>".$quey_delete2;
                    $result = pg_query($link,$quey_delete2);
                    if (pg_affected_rows($result)==0)
                    {
                        $resultado="Ocurrio en error mientras de eliminaba el registro de la tabla SW_OBSERVACIONES_STDLT";
                    }else{
                        $resultado='1';
                    }
                }else{
                     $resultado="Ocurrio en error mientras de eliminaba el registro de la tabla sw_hoja_de_tiempo_real";
                }
            }else{
                $quey_update="UPDATE sw_hoja_de_tiempo_real SET ";
                $quey_update.="inicio_dlt1=NULL, ";
                $quey_update.="fin_dlt=NULL";
                $quey_update.="causal_dlt1=''";
                $quey_update.="hora_dlt=''";
                $quey_update.="ced_dlt=NULL";
                $quey_update.="where cedula = '".$cedula."' ";
                $quey_update.="and fecha= '".$fecha."'";
                //print "<br>".$quey_update;        

                $result = ejecutar_query($link, $quey_update) or die("Error en la Consulta SQL: ".$quey_update);
                if (pg_affected_rows($result)==0){
                    $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
                }else{
                    $resultado='1';
                }
            }

        }else{
            $resultado="";
        }

        pg_close($link);

    return $resultado;

}
//--------------------------------------------------------------------------
function llenar_combo_ccosto_del_supervisor(){

    switch ($_SESSION['nivel_jerarquico']) {
        case 1:          
            $filtro = "direccion";            
            break;
        case 2:          
            $filtro = "direccion";            
            break;
        case 3:          
            $filtro = "gergral";            
            break;
        case 4:          
            $filtro = "gerencia";            
            break;
        case 5:          
            $filtro = "depto";            
            break;
        case 6:          
            $filtro = "coordina";            
            break;
        default:          
            $filtro = "coordina";
    }  

    
        $query="SELECT ccosto, detalle_ccosto FROM adam_vw_dotacion_briqven_02_mas 
        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."'";
        $query.=" UNION ";
        $query.="SELECT ccosto, detalle_ccosto FROM adam_vw_dotacion_briqven_02_mas 
        WHERE trim(trabajador_sup) = '".$_SESSION['cedula_session_const']."'";
        $query.=" UNION ";
        $query.= "SELECT ccosto, detalle_ccosto FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') 
                       AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."')";
        $query.=" UNION ";
        $query.="SELECT ccosto, detalle_ccosto FROM supervisores_trabajadores s inner join adam_vw_dotacion_briqven_02_mas v on v.trabajador=s.trabajador
        WHERE v.trabajador_sup = '".$_SESSION['cedula_session_const']."'";
        $query.=" ORDER BY ccosto";
        //echo $query;
        $conn=Conex_rrhh_pgsql();
        $stid = ejecutar_query($conn, $query);        
        $option='';
        
        while ($fila = ejecutar_fetch_array($stid)){
            
            $option.= "<option value='". $fila['ccosto']."'" ;
            $option.= ">". $fila['ccosto']." - ".$fila['detalle_ccosto']."</option>";
        }
        
        
        pg_free_result($stid);
        pg_close($conn);
         
    return $option;


}


//--------------------------------------------------------------------------
function llenar_combo_trabajadores_solo_del_supervisor(){

    switch ($_SESSION['nivel_jerarquico']) {
        case 1:          
            $filtro = "direccion";            
            break;
        case 2:          
            $filtro = "direccion";            
            break;
        case 3:          
            $filtro = "gergral";            
            break;
        case 4:          
            $filtro = "gerencia";            
            break;
        case 5:          
            $filtro = "depto";            
            break;
        case 6:          
            $filtro = "coordina";            
            break;
        default:          
            $filtro = "coordina";
    }  
 
    
        $query="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."'";
        $query.=" UNION ";
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE trim(trabajador_sup) = '".$_SESSION['cedula_session_const']."'";
        $query.=" UNION ";
        /*$query.= "SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador)='".$_SESSION['cedula_session_const']."') 
                       AND nivel_jerarquico::integer>='".$_SESSION['nivel_jerarquico']."')";
        $query.=" UNION ";*/
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM supervisores_trabajadores 
        WHERE trabajador_sup = '".$_SESSION['cedula_session_const']."'";
        $query.=" ORDER BY nombre";
        //echo $query;
        $conn=Conex_rrhh_pgsql();
        $stid = ejecutar_query($conn, $query);        
        $option='';
        $arrayTrabajadores = array();
        while ($fila = ejecutar_fetch_array($stid)){
            array_push($arrayTrabajadores, $fila['trabajador']);
            $option.= "<option value='". $fila['trabajador']."'" ;
            $option.= ">". $fila['trabajador']." - ".$fila['nombre']."</option>";
        }
        $_SESSION['arrayTrab']=$arrayTrabajadores;
        //print_r($arrayTrabajadores) ;
        pg_free_result($stid);
        pg_close($conn);
         
    return $option;

}

//--------------------------------------------------------------------------
function calcular_horas_extras($Inicio,$Fin){
    // CALCULAMOS EL TIEMO DE HORAS EXTRAS
    $inicio = new datetime($Inicio);
    $fin    = new datetime($Fin);
    
    $diferencia = $inicio -> diff($fin);
    $resultado  = $diferencia->format("%h").".".$diferencia-> format("%i");

    if ($resultado<0)
        $resultado = 24 + $resultado;

    return $resultado;
}   

//--------------------------------------------------------------------------
function VALIDAR_STDLT($cedula,$fecha, $CS, $observacion)
{
    $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
    $link           = Conex_Contancia_pgsql();
    $result         = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
    $contar         = ejecutar_num_rows($result);
    if ($contar>0){
        $query_update="UPDATE sw_hoja_de_tiempo_real SET ";
        $query_update.="validado_stdlt=".$CS.", ";
        $query_update.="rechazado_stdlt=0, ";
        $query_update.="fecha_validado_stdlt='".date("Y-m-d H:i:s")."' ";
        $query_update.=" where cedula = '".$cedula."' ";
        $query_update.="and fecha= '".$fecha."'";
        $result = ejecutar_query($link, $query_update) or die("Error en la Consulta SQL: ".$query_update);
        if (pg_affected_rows($result)==0){
          $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
        }else{
          $resultado='1';
        }
    }
    pg_close($link);
    return $resultado;
}

//--------------------------------------------------------------------------
function RECHAZAR_STDLT($cedula,$fecha, $CS, $observacion)
{
    $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$cedula." and fecha= '".$fecha."'";
    $link           = Conex_Contancia_pgsql();
    $result         = ejecutar_query($link, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
    $contar         = ejecutar_num_rows($result);
    if ($contar>0){
        $query_update="UPDATE sw_hoja_de_tiempo_real SET ";
        $query_update.="autorizado2=NULL, ";
        $query_update.="fecha_autor2=NULL, ";
        $query_update.="validado_stdlt=0, ";
        $query_update.="fecha_validado_stdlt='".date("Y-m-d H:i:s")."', ";
        $query_update.="rechazado_stdlt=".$CS;
        $query_update.=" where cedula = '".$cedula."' ";
        $query_update.="and fecha= '".$fecha."'";
        $result = ejecutar_query($link, $query_update) or die("Error en la Consulta SQL: ".$query_update);
        if (pg_affected_rows($result)==0){
            $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
        }else{

            $quey_insert="INSERT INTO SW_observaciones_rechazo_stdlt values (".$cedula.",'".date("Y-m-d H:i:s")."','".$observacion."',".$CS.",'".$fecha."')";
            $result = ejecutar_query($link, $quey_insert) or die("Error en la Consulta SQL: ".$quey_insert);
            if (pg_affected_rows($result)==0){
                $resultado="Ocurrio en error mientras de actualizaba el registro de la tabla sw_hoja_de_tiempo_real";
            }else{
                $resultado='1';
            }            
        }
    }
    pg_close($link);
    return $resultado;
}

//--------------------------------------------------------------------------
function dias_transcurridos($fecha_inicio,$fecha_final)
{
    $date = str_replace('/', '-', $fecha_inicio);
    $date2 = str_replace('/', '-', $fecha_final);
    $f1 = new DateTime($date);
    $f2 = new DateTime($date2);
    //$d = date('d-m-Y').', '.$date;
    $d = $f1->diff($f2);
    return $d->format('%D');
}

//-------------------------------------------------------------------------- 
function dias_transcurridos2($fecha_inicio,$fecha_final)
{
    /*$date = str_replace('/', '-', $fecha_inicio);
    $date2 = str_replace('/', '-', $fecha_final);
    $f1 = new DateTime($date);
    $f2 = new DateTime($date2);
    //$d = date('d-m-Y').', '.$date;  //days
    $d = $f1->diff($f2);
    return $d->format('%D');
    //return $d->format('%days');*/

    $dias = (strtotime($fecha_inicio)-strtotime($fecha_final))/86400;
    $dias = abs($dias); $dias = floor($dias);
    return $dias;
}

//--------------------------------------------------------------------------
function dias_transcurridos3($fecha_inicio,$fecha_final)
{
    $fecha1= new DateTime($fecha_inicio);
    $fecha2= new DateTime($fecha_final);
    $diff = $fecha1->diff($fecha2);    
    $dias = $diff->days;
    return $dias;
}

//--------------------------------------------------------------------------
function responsable_carga_registro_stdlt($link,$trabajador,$fecha,$tipoSTDLT)
{
 //print $link.','.$trabajador.','.$fecha.','.$tipoSTDLT;
    switch ($tipoSTDLT) {
        case 'ST':
            $campo = "ced_st as cedula_responsable ";
            break;
        case 'DLT':
            $campo = "ced_dlt as cedula_responsable ";
            break;
    }
    $query_het  = " SELECT $campo from SW_Hoja_de_Tiempo_Real a   WHERE cedula = '".trim($trabajador)."' and fecha= '".$fecha."'";

    $result_het = ejecutar_query($link, $query_het) or die("Error en la Consulta SQL: ".$query_het);
    $contar_het = ejecutar_num_rows($result_het);
    $row_het    = ejecutar_fetch_array($result_het);
    $quien_hizo_registro='';
    if ($contar_het>0){
        if ($row_het['cedula_responsable']=='14509326'){
            $quien_hizo_registro= 'matbab';
        }else{
          $query_select= " SELECT b.e_mail as siglado from trabajadores b   WHERE trabajador = '".trim($row_het['cedula_responsable'])."'";
          $result = ejecutar_query($link, $query_select) or die("Error en la Consulta SQL: ".$query_select);
          $contar = ejecutar_num_rows($result);
          $row    = ejecutar_fetch_array($result);
          if ($contar>0){
            $quien_hizo_registro= substr($row['siglado'], 0,6);
         }
        }
    }
    //print $quien_hizo_registro;
    return $quien_hizo_registro;
}
 
//--------------------------------------------------------------------------
function calcular_horas_extras2($fecha,$Inicio_ST1,$Fin_ST1){
    // CALCULAMOS EL TIEMO DE HORAS EXTRAS
    $inicio = new datetime($Inicio_ST1);
    $fin    = new datetime($Fin_ST1);
    $hora1 = strtotime( "18:00" );   
    $hora2 = strtotime( "19:00" );
    
    $fecha1=$fecha;
    $fecha2=date ( 'Y-m-d' , strtotime ( '+24 hour' , strtotime ($fecha1) )); ;

    $fecha11=$fecha1.' '.$Inicio_ST1;
    $fecha21=$fecha2.' '.$Fin_ST1;
 
    $horas = strtotime($fecha11) - strtotime($fecha21);
    $horas2=number_format($horas / 60 / 60,2) ;
    
    $horas2=str_replace(',','.',$horas2);

    $ciclo=explode(".", $horas2);
 
   if ($ciclo[1]){
       $ciclo2=round(($ciclo[1]*60)/100, 0);
       $ciclo3=$ciclo[0]*(-1);
   }else{
       $ciclo3=$ciclo[0];
   }

   if ($ciclo3>24)
       $ciclo3= $ciclo3-24;

   $cantidad_he = $ciclo3.".".$ciclo2;

   return $cantidad_he;
}   

//--------------------------------------------------------------------------
function llenar_combo_razon_stdlt($conn){
   
    $query="SELECT id, identificador, descripcion, feca_ini, fecha_fin FROM public.justificacion_stdlt where fecha_fin is null order by id;";

    //$conn=Conex_Contancia_pgsql();
    $stid = ejecutar_query($conn, $query);        
    $option='';
    $arrayTrabajadores = array();
    while ($fila = ejecutar_fetch_array($stid)){
       // array_push($arrayTrabajadores, $fila['trabajador']);
        $option.= "<option value='". $fila['id']."'" ;
        $option.= ">". $fila['identificador']." - ".$fila['descripcion']."</option>";
    }
    //$_SESSION['arrayTrab']=$arrayTrabajadores;
    pg_free_result($stid);
    //pg_close($conn);

    return $option;
}

//--------------------------------------------------------------------------
function llenar_checkbox_razon_stdlt($conn){
   
    $query="SELECT id, identificador, descripcion, fecha_ini, fecha_fin FROM public.justificacion_stdlt where fecha_fin is null order by id;";

    $conn=Conex_Contancia_pgsql();
    $stid = ejecutar_query($conn, $query) or die("Error en la Consulta SQL: ".$query);; 
    $numReg = ejecutar_num_rows($stid);
    $option='<table border=0>';
    while ($fila = ejecutar_fetch_array($stid)){
        $option.= "<tr style=\"font-family: Arial; font-size: 8pt;\"><td><input type=\"checkbox\" name=\"razon[]\" value=\"".$fila['id']."\"></td><td>&nbsp;&nbsp;".$fila['identificador']." - ".$fila['descripcion']."</td></tr>";
    }
    $option.='</table>';
    pg_free_result($stid);
    pg_close($conn);

    return $option;
}

function primer_dia_semana($fecha){
    $dia=date('N', strtotime($fecha));
    if ($dia=="1"){
         $week_start = $fecha;
     } else {
         $dia_restar = $dia -1;
         $week_start = date("Y-m-d", strtotime($fecha."- ".$dia_restar." days"));
     }
     return $week_start;
}      

//--------------------------------------------------------------------------
function trabajadores_del_supervisor($supervisor,$nivel_jerarquico){ 

    switch ($nivel_jerarquico) {
        case 1:          
            $filtro = "direccion";            
            break;
        case 2:          
            $filtro = "direccion";            
            break;
        case 3:          
            $filtro = "gergral";            
            break;
        case 4:          
            $filtro = "gerencia";            
            break;
        case 5:          
            $filtro = "depto";            
            break;
        case 6:          
            $filtro = "coordina";            
            break;
        default:          
            $filtro = "coordina";
    }  

    
        $query="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE ".$filtro." = (SELECT ".$filtro." FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)='".$supervisor."') AND nivel_jerarquico::integer>='".$nivel_jerarquico."'";
        $query.=" UNION ";
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
        WHERE trim(trabajador_sup) = '".$supervisor."'";
        $query.=" UNION ";
        $query.= "SELECT trabajador, nombre, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas WHERE trabajador_sup in (    
        SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
        WHERE depto = (SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador)='".$supervisor."') 
                       AND nivel_jerarquico::integer>='".$nivel_jerarquico."')";
        $query.=" UNION ";
        $query.="SELECT trabajador, nombre, nivel_jerarquico FROM supervisores_trabajadores 
        WHERE trabajador_sup = '".$supervisor."'";
        $query.=" ORDER BY nombre";
        //echo $query;
        $conn=Conex_rrhh_pgsql();
        $stid = ejecutar_query($conn, $query);        
        $option='';
        $arrayTrabajadores = array();
        while ($fila = ejecutar_fetch_array($stid)){
            array_push($arrayTrabajadores, $fila['trabajador']);
            $option.= "<option value='". $fila['trabajador']."'" ;
            $option.= ">". $fila['trabajador']." - ".$fila['nombre']."</option>";
        }
        $_SESSION['arrayTrab']=$arrayTrabajadores;
        //print_r($arrayTrabajadores) ;
        pg_free_result($stid);
        pg_close($conn);
         
    return $option;

}

//----------------------------------------------------------------------
function llenar_combo_movimiento(){
    //$tipo_movimiento=array(1=>'Apoyo',2=>'Cambio de Cuadrilla',3=>'Encargaduria',4=>'Sustitucion');
    $tipo_movimiento=array(2=>'Cambio de Cuadrilla',4=>'Sustitucion');

    $option='';
    foreach ($tipo_movimiento as $fila => $valor) {
        $option.= "<option value='". $fila."'" ;
          if ($fila[0] != "")
            $option.= ">". $valor. "</option>";
          else
            $option.= ">". $valor. "</option>";
          
    }

    $option.= "</select>";
    return  $option;
}