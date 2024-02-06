<?PHP
session_start();
if (isset($_SESSION['userid']) && isset($_SESSION['userid'])){
    require("include_conex.php");
    $paciente = isset($_POST["txtId"])?$_POST["txtId"]:"-1";    //
    //$fecha= isset($_POST["txtFecha"])?$_POST["txtFecha"]:"";         //
    $motivo= isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";   // 
    $sintomas= isset($_POST["txtSintomas"])?$_POST["txtSintomas"]:"";               // 
    $medico= isset($_POST["cboMedico"])?$_POST["cboMedico"]:"NULL";   // 
    $observaciones= isset($_POST["txtObservaciones"])?$_POST["txtObservaciones"]:"";     
    $diagnostico= isset($_POST["txtDiagnostico"])?$_POST["txtDiagnostico"]:"";   //
    $paramedico= isset($_POST["cboParaMedico"])?$_POST["cboParaMedico"]:"NULL";     // 
    $area= isset($_POST["cboArea"])?$_POST["cboArea"]:"NULL";   //
    $patologia= isset($_POST["cboPatologias2"])?$_POST["cboPatologias2"]:"NULL";   //
    $remitido= isset($_POST["cboRemitido"])?$_POST["cboRemitido"]:"NULL";   //
    $reposo= isset($_POST["cboReposo"])?$_POST["cboReposo"]:"NULL";   //
    $turno= isset($_POST["txtTurno"])?$_POST["txtTurno"]:"NULL";    //
    $observacionMed= isset($_POST["txtObservacionMed"])?$_POST["txtObservacionMed"]:"";  
    $indicaciones= isset($_POST["txtIndicaciones"])?$_POST["txtIndicaciones"]:"";   //
    $fechaProxCita= isset($_POST["txtFechaProxCita"])?$_POST["txtFechaProxCita"]:"";   //
    $referencia= isset($_POST["txtreferencia"])?$_POST["txtreferencia"]:"";   //
    $condicion= isset($_POST["cbocondicion"])?$_POST["cbocondicion"]:"";   //
    $afeccion= isset($_POST["cboAfecciones"])?$_POST["cboAfecciones"]:"NULL";   //
    $autorizacion= isset($_POST["autorizacion"])?$_POST["autorizacion"]:"NO";   // 
    $tipoconsulta= isset($_POST["cboTipoConsulta"])?$_POST["cboTipoConsulta"]:"NULL";

    $fresp= isset($_POST["txtfresp"])?$_POST["txtfresp"]:"";
    $pulso= isset($_POST["txtpulso"])?$_POST["txtpulso"]:"";
    $temper= isset($_POST["txttemper"])?$_POST["txttemper"]:"";
    $tart= isset($_POST["txttart"])?$_POST["txttart"]:"";
    $fcard= isset($_POST["txtfcard"])?$_POST["txtfcard"]:"";

    $talla= isset($_POST["txttalla"])?$_POST["txttalla"]:"";
    $peso= isset($_POST["txtpeso"])?$_POST["txtpeso"]:"";
    $imc= isset($_POST["txtimc"])?$_POST["txtimc"]:"";

    if ($turno=="") $turno="NULL";

    if ($fechaProxCita=="")
        $fechaProxCita="NULL";
    else
        $fechaProxCita="'" . $fechaProxCita . "'";

    //$codRemedios= isset($_POST["codRemedios"])?implode(", ", noescape($_POST["codRemedios"])):null;   //
    //$cantidades= isset($_POST["cantidades"])?implode(", ", noescape($_POST["cantidades"])):null;   //

    //echo("Arreglo:" . $_POST["codRemedios"]); 
      
    if (($talla!='') && ($talla!='0') && ($talla>0) && ($peso!='') && ($peso!='0') && ($peso>0) && ($imc!='') ){
      $imc=round($talla/$peso,2);
    }
     
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
    //echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

    //buscar la cedula
    //
    $query="Select ci, now() as hoy from tbl_pacientes where uid_paciente=" . $paciente;
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);
    $numReg = pg_num_rows($resultado);
    if($numReg==0){
      //el registro no existe
      echo("1");
      echo($query);
      pg_close($conexion);
      die("");
    }else{
      $rowpaciente = pg_fetch_array($resultado);
      $cedula = $rowpaciente['ci'];
      $fecha = $rowpaciente['hoy'];
    }

    $queryy = "INSERT INTO tbl_consulta (";
    $queryy .= "id_paciente, "; 
    $queryy .= " fecha, ";
    $queryy .= " id_motivo, ";
    $queryy .= " sintomas, ";
    $queryy .= " id_medico, ";
    $queryy .= " observaciones, ";
    $queryy .= " id_paramedico, ";
    $queryy .= " id_area, ";
    $queryy .= " id_patologia, ";
    $queryy .= " id_remitido, ";
    $queryy .= " id_reposo, ";
    $queryy .= " turno, ";
    $queryy .= " observacion_medicamentos, ";
    $queryy .= " indicaciones_comp, ";
    $queryy .= " referencia_medica, ";
    $queryy .= " fecha_prox_cita, ";
    $queryy .= " condicion, ";
    $queryy .= " fkafeccion, ";
    $queryy .= " resultado_eva, ";
    $queryy .= " autorizacion,"; 
    $queryy .= " fktipoconsulta"; 
    $queryy .= ") values (";
    $queryy .= $paciente.", ";
    $queryy .= "'".$fecha."', ";
    $queryy .= $motivo .", ";
    $queryy .= "'" . $sintomas . "',";
    $queryy .= $medico . ",";
    $queryy .= "'" .$observaciones . "', ";
    $queryy .= $paramedico . ", ";
    $queryy .= $area . ", ";
    $queryy .= $patologia . ", ";
    $queryy .= $remitido . ", ";
    $queryy .= $reposo . ", ";
    $queryy .= $turno . ", ";
    $queryy .= "'" . $observacionMed . "', ";
    $queryy .= "'" . $indicaciones . "', ";
    $queryy .= "'" . $referencia . "',";
    $queryy .= $fechaProxCita . ", ";
    $queryy .= "'" . $condicion."', ";
    $queryy .= $afeccion.", ";
    $queryy .= "'" . $diagnostico."',";
    $queryy .= "'" . $autorizacion."',";
    $queryy .= $tipoconsulta;
    $queryy .= ") returning uid; ";

    $resultado = pg_query($conexion, $queryy) or die("Error en la Consulta SQL:" . $queryy);
    $rowconsulta = pg_fetch_array($resultado);
    $id_consulta = $rowconsulta['uid'];

    $queryhistoria="SELECT uid_historia FROM tbl_historia_medica where uid_paciente=".$paciente;
    $resultadohistoria = pg_query($conexion, $queryhistoria) or die("Error en la Consulta SQL: " . $queryhistoria);
    $idhistoria = 'NULL';
    if (pg_num_rows($resultadohistoria)>0){
      $rowhistoria = pg_fetch_array($resultadohistoria);
      $idhistoria = $rowhistoria['uid_historia'];
    }

    if ($medico=="NULL")
       $medico=$paramedico;

     if ($motivo==9 && $idhistoria=='NULL'){
        $queryInsertHist = "INSERT INTO tbl_historia_medica(fecha_apertura, fk_medico, uid_paciente)";
        $queryInsertHist = $queryInsertHist." VALUES (NOW(), ".$medico.", ".$paciente.") returning uid_historia;";
        $resultado = pg_query($conexion, $queryInsertHist) or die("Error en la Consulta SQL: " . $queryInsertHist);
        if (pg_num_rows($resultado)>0){
          $reg = pg_fetch_array($resultado, null, PGSQL_ASSOC);
          $idhistoria=$reg['uid_historia'];
        }  
     }

    if ($idhistoria!='NULL'){
        $queryUpdateHistpac = "INSERT INTO tbl_historia_paciente(fk_historia, fecha_historia, indice, motivo_historia, fk_medico, observacion, fk_consulta) VALUES (";
        $queryUpdateHistpac=$queryUpdateHistpac.$idhistoria.", ";
        $queryUpdateHistpac=$queryUpdateHistpac."'".$fecha."', ";
        $queryUpdateHistpac=$queryUpdateHistpac."(select COALESCE(max(indice),0) + 1 from tbl_historia_paciente where fk_historia=".$idhistoria."), ";
        $queryUpdateHistpac=$queryUpdateHistpac."(SELECT descripcion FROM tbl_motivos WHERE uid=".$motivo."), ";
        $queryUpdateHistpac=$queryUpdateHistpac.$medico.", ";
        $queryUpdateHistpac=$queryUpdateHistpac."'".$observaciones."',";
        $queryUpdateHistpac=$queryUpdateHistpac.$id_consulta.");";

        $resultado = pg_query($conexion, $queryUpdateHistpac) or die("Error en la Consulta SQL: " . $queryUpdateHistpac);
    }
    //Inserta signos vitales
    if (($fresp!="") || ($fcard!="") || ($fresp!="") || ($pulso!="") || ($pulso!="") || ($tart!=""))
      { 
        $querySigvitales = "INSERT INTO tbl_signos_vitales(cedula, fresp, fcard, pulso, temper, tart, fecha) VALUES ('";
        $querySigvitales = $querySigvitales.$cedula."', '".$fresp."', '".$fcard."', '".$pulso."', '".$temper."', '".$tart."', '".$fecha."') ;";
        $resultado = pg_query($conexion, $querySigvitales) or die("Error en la Consulta SQL: " . $querySigvitales);
      }

    //Inserta datos antopometricos
    if (($talla!="") || ($peso!="") || ($imc!=""))
    {
        $queryDatosAntop = "INSERT INTO tbl_datos_antropometricos(cedula, talla, peso, imc, fecha) VALUES ('";
        $queryDatosAntop = $queryDatosAntop.$cedula."', '".$talla."', '".$peso."', '".$imc."', '".$fecha."') ;";
        $resultado = pg_query($conexion, $queryDatosAntop) or die("Error en la Consulta SQL: " . $queryDatosAntop);

        $queryDatosAntop = "UPDATE tbl_datos_antropometricos SET imc=ROUND(cast (peso as numeric)/(cast (talla as numeric) * cast (talla as numeric)) ,2)::varchar WHERE talla <> '' and cast (talla as numeric)>0";
        $queryDatosAntop .= "AND peso <> '' AND cast (talla as numeric)>0 AND talla IS NOT NULL AND peso IS NOT NULL AND imc='' AND fecha='".$fecha."' ;";
        $resultado = pg_query($conexion, $queryDatosAntop) or die("Error en la Consulta SQL: " . $queryDatosAntop);
    }
    //Inserta el registro de Medicamentos
    //
    $i=0;
    if(array_key_exists('codRemedios',$_POST))
    {
      $codRemedios = $_POST['codRemedios'];  
      //echo($codRemedios);
      //die();  
      $cantidades = $_POST['cantidades'];
      $medidas =  $_POST['medida'];
      
      foreach ($codRemedios as &$remedio)
      {
        $cantidad = $cantidades[$i];
        $md=$medidas[$i];
        $query = "insert into tbl_medicamentos_consulta (id_consulta, id_medicamento, cantidad, medidas) values (";
        $query = $query . $id_consulta . "," . $remedio . "," . $cantidad . ", '".$md."'); ";  
        //echo($query);
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:".$query);  
        $i++;
      }
    }
    /// Envio de correo al area de administracion de personal en caso de ser vacacion o egreso//

    $query="SELECT m.motivo, c.idmotivo, m.nombre_completo, m.reposo FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;

    $resultmorb = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
    $rowmorb = pg_fetch_array($resultmorb);

    $desc_mot=$rowmorb['motivo'];
    $motivo=$rowmorb['idmotivo'];
    $nombre_completo=$rowmorb['nombre_completo'];
    $mor_reposo=$rowmorb['reposo'];
    pg_free_result($resultmorb);

    require("enviodecorreos.php");
    require("funciones_var.php");

    if (($motivo==7) || ($motivo==8) || ($motivo==9) || ($motivo==10))
    {	
    	$asunto=$desc_mot." ".$nombre_completo;
      $resp=ENVIAR_CORREO(nota_examen($id_consulta),$asunto,"",$_SESSION['userid'], $desc_mot);
      //  $resp=ENVIAR_CORREO(nota_examen($id_consulta),$asunto,"",$_SESSION['userid'], "PRUEBA");
    }

    if (($motivo==7) || ($motivo==8) || ($motivo==9) || ($motivo==10)  || ($motivo==13))
    {
      require("planilla_certificado.php");
      $dpf=construir_pdf($id_consulta, planilla_certificado($id_consulta));
      $resp1=ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.' ,"Certificado Medico ".$nombre_completo, $dpf, $_SESSION['userid'], $desc_mot);
      //$resp1=ENVIAR_CORREO('<p>&nbsp;</p>Favor Revisar Archivo Adjunto.' ,"Certificado Medico ".$nombre_completo, $dpf, $_SESSION['userid'], "PRUEBA");
    }

    $os = array("NULL", "0", "N/A", "null", "");
    if (!in_array($mor_reposo, $os)){
      $asunto="REPOSO ".$nombre_completo;
      $resp=ENVIAR_CORREO(nota_reposo($id_consulta), $asunto, "", $_SESSION['userid'], "REPOSO");
      //  $resp=ENVIAR_CORREO(nota_reposo($id_consulta), $asunto, "", $_SESSION['userid'], "PRUEBA");
    }

    pg_free_result($resultado);
    pg_close($conexion);
    echo ($id_consulta); //OK
}
else{
  echo 'Su sesion a Expirado, por favor cierre el sistema y vuelva a loguerse';
}
?>