<?php
session_start();

$fecha      = isset($_POST["fecha"])?$_POST["fecha"]:NULL;
$trabajador = isset($_POST["cedula"])?$_POST["cedula"]:NULL;
$codigo     = isset($_POST["cod_ausencia"])?$_POST["cod_ausencia"]:NULL;
$entrada    = isset($_POST["entReal1"])?$_POST["entReal1"]:'';
$salida     = isset($_POST["salReal1"])?$_POST["salReal1"]:NULL;
$comida     = isset($_POST["optionsComida"])?$_POST["optionsComida"]:NULL;
$Inicio_ST1 = isset($_POST["Inicio_ST1"])?$_POST["Inicio_ST1"]:NULL;
$Fin_St1    = isset($_POST["Fin_St1"])?$_POST["Fin_St1"]:NULL;
$Horas_ST   = isset($_POST["Horas_ST"])?$_POST["Horas_ST"]:NULL;
$Inicio_DLT1= isset($_POST["Inicio_DLT1"])?$_POST["Inicio_DLT1"]:NULL;
$Fin_DLT1   = isset($_POST["Fin_DLT1"])?$_POST["Fin_DLT1"]:NULL;
$Horas_Dlt  = isset($_POST["Horas_Dlt"])?$_POST["Horas_Dlt"]:NULL;
$entEsp1    = isset($_POST["entEsp1"])?$_POST["entEsp1"]:NULL;
$salEsp1    = isset($_POST["salEsp1"])?$_POST["salEsp1"]:NULL;
$entrada2   = isset($_POST["entEsp2"])?$_POST["entEsp2"]:NULL;
$salida2    = isset($_POST["salEsp2"])?$_POST["salEsp2"]:NULL;
$entReal2    = isset($_POST["entReal2"])?$_POST["entReal2"]:NULL;
$salReal2    = isset($_POST["salReal2"])?$_POST["salReal2"]:NULL;

$motivo_ST  = isset($_POST["motivo_ST"])?$_POST["motivo_ST"]:NULL;
$motivo_DLT = isset($_POST["motivo_DLT"])?$_POST["motivo_DLT"]:NULL;
$observacion = isset($_POST["observacion_aprobar"])?$_POST["observacion_aprobar"]:NULL;
$Sp         = isset($_GET["SP"])?$_GET["SP"]:NULL;

//print "<br>entEsp1: ".$entEsp1.", salEsp1: ".$salEsp1.", SP:".$Sp;

//print $Sp;


if (isset($_SESSION['user_session_const'])){

    switch ($Sp) {
      case 1:
        $storeP='poner_fichada_completa';
        break;
      case 2:
        $storeP='poner_fichada_codausencia_horas';
        break;
      case 3:
        $storeP='MediaHoraComida';
        break;    
      case 4:
        $storeP='completarAl100';
        break;
      case 5:
        $storeP='SW_GRABA_CAMBIO_DLT_CEDULA';
        break;
      case 6:
        $storeP='SW_BORRA_CAMBIO_DLT_CEDULA';
        break;     
      case 7:
        $storeP='SW_GRABA_ST_CEDULA';
        break;
      case 8:
        $storeP='SW_BORRA_ST_CEDULA';
        break;
      case 10:
        $storeP='SW_GRABA_CAMBIO_ESPERANZA_CEDULA';
        break;   
      case 12:
        $storeP='RECHAZADO';
        break;  
      /*
      default:
        # code...
        break;
     */
    }

    echo ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Horas_ST, $Inicio_DLT1, $Fin_DLT1, $Horas_Dlt, $entEsp1, $salEsp1, $entrada2, $salida2, $motivo_ST, $motivo_DLT, $storeP,$observacion, $entReal2, $salReal2);    
}
else{
    echo 'Debe iniciar la su sesion'; 
} 


function ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Horas_ST, $Inicio_DLT1, $Fin_DLT1, $Horas_Dlt, $entEsp1, $salEsp1, $entrada2, $salida2, $motivo_ST, $motivo_DLT, $SP, $observacion, $entReal2, $salReal2) {

   include("../BD/conexion.php");
   require_once('funciones_var.php'); 
   $mbd=Conectarse_sitt();       
   $inpt="No se ejecuto ninguna operacion";    


   if ($SP=='poner_fichada_completa'){

      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);           
      $stmt->bindParam(3, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      /*
      $inpt= "$(\"#entReal1\").val(\"" . $row['entrada_real1'] . "\");\n" ;
      $inpt.= "$(\"#salReal1\").val(\"" . $row['salida_real1'] . "\");\n" ;
      $inpt.= "$(\"#entReal2\").val(\"" ."" . "\");\n" ;
      $inpt.= "$(\"#salReal2\").val(\"" . "" . "\");\n" ;
      */
      if ($row['cedula']==$trabajador)
        $inpt='1';
      else
        $inpt='Error al colocar la fichada completa';
      

   }
   elseif ($SP=='poner_fichada_codausencia_horas') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $codigo, PDO::PARAM_INT);
      $stmt->bindParam(4, $entrada, PDO::PARAM_STR,5);
      $stmt->bindParam(5, $salida, PDO::PARAM_STR,5);           
      $stmt->bindParam(6, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->bindParam(7, $entReal2, PDO::PARAM_STR,5);
      $stmt->bindParam(8, $salReal2, PDO::PARAM_STR,5);       
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      if ($row['cedula']==$trabajador){
          //2da fichada:
          /*
          if ($entrada2!='' && $salida2!='' && $entrada2!=NULL){
            $stmt = $mbd->prepare("EXEC poner_2da_fichada ?, ?, ?, ?, ?");
            $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
            $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);            
            $stmt->bindParam(3, $entrada2, PDO::PARAM_STR,5);
            $stmt->bindParam(4, $salida2, PDO::PARAM_STR,5);           
            $stmt->bindParam(5, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);            
          }
          */
          $inpt='1';
      }
      else
        $inpt='Error al colocar la fichada parcial con codigo de ausencia';
      
   }
   elseif ($SP=='completarAl100') {
      $stmt = $mbd->prepare(" update sw_hoja_de_tiempo_real set horasnetapresencia=8, horasnetaausencia=0, coderror=100, autorizado1=?, Fecha_autor1=GETDATE() where cedula=? and fecha=? ");
      $stmt->bindParam(1, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $fecha, PDO::PARAM_STR,10);                       
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      $inpt='1';
   }
   elseif ($SP=='MediaHoraComida') {
      $stmt = $mbd->prepare(" update sw_hoja_de_tiempo_real set PagoComida=? where cedula=? and fecha=? ");
      $stmt->bindParam(1, $comida, PDO::PARAM_STR,1);
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $fecha, PDO::PARAM_STR,10);                       
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      $inpt='1';
   }
   elseif ($SP=='SW_GRABA_ST_CEDULA') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?,?,?,?,?,?,?,?,?,?");
      $causalST='08';
      $obsSt=$motivo_ST; //'Horas Extraordinarias';
      $st2="";
      $cod2="";
//print "<br>".$obsSt.', Responsable: '.$_SESSION['cedula_session_const'];
      $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha, PDO::PARAM_STR, 10); 
      $stmt->bindParam(3, $Inicio_ST1, PDO::PARAM_STR, 5); 
      $stmt->bindParam(4, $Fin_St1, PDO::PARAM_STR, 5); 
      $stmt->bindParam(5, $st2, PDO::PARAM_STR, 5); 
      $stmt->bindParam(6, $st2, PDO::PARAM_STR, 5); 
      $stmt->bindParam(7, $causalST, PDO::PARAM_STR, 2); 
      $stmt->bindParam(8, $cod2, PDO::PARAM_STR, 2); 
      $stmt->bindParam(9, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->bindParam(10, $obsSt, PDO::PARAM_STR, 255);                       
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

      if ($row['Error']=='OK'){ 

          /***********************************************************************/
          /*          VERIFICAMOS QUE SI EXISTE O NO EN LA BD LOCAL              */
          /***********************************************************************/
          $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$trabajador." and fecha= '".$fecha."'";
          $link2           = Conex_Contancia_pgsql();
          $result         = ejecutar_query($link2, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
          $contar         = ejecutar_num_rows($result);
          $row            = ejecutar_fetch_array($result);
          $responsable_carga = '';
          $fecha_carga = '';
          if ($contar>0){
              $responsable_carga = "<tr><td>Cargado por</td><td> ".nombre_trabajadores($row['ced_st'])."</td></tr>";
              if ($row['fecha_carga_st_dlt'] != NULL)
                  $fecha_carga="<tr><td>El d&iacute;a</td><td>".$row['fecha_carga_st_dlt']."</td></tr>";

              if ($row['validado_stdlt']==0){
                $query_update2="validado_stdlt='".$_SESSION['cedula_session_const']."', ";
              }else{
                $query_update2="";
              }
              $query_update="UPDATE sw_hoja_de_tiempo_real SET ";
              $query_update.="autorizado1='".$_SESSION['cedula_session_const']."', ";
              $query_update.=$query_update2;
              $query_update.="fecha_autor1='".date("Y-m-d H:i:s")."'";
              $query_update.=" where cedula = '".$trabajador."' ";
              $query_update.="and fecha= '".$fecha."'";
              //print "<br>".$query_update;  
              $link2  = Conex_Contancia_pgsql();      
              $result = ejecutar_query($link2, $query_update) or die("Error en la Consulta SQL: ".$query_update);
              require("enviodecorreoxmodulo.php");
              $asunto="Procesado Horas Extras de ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');
   
              $cuerpo  = "Saludos, Se aprob&oacute; el registro de  ".$Horas_ST." Horas Extras del trabajador: 
              <Table border ='1'>
                <tr>
                  <td>Nombre</td> 
                  <td>".nombre_trabajadores(trim($trabajador))."</td>
                </tr>
                <tr>
                   <td>C&eacute;dula de indentidad</td>
                   <td>".trim($trabajador)."</td>
                </tr>
                <tr>
                    <td>De fecha</td>
                    <td>".formato_fecha($fecha,'-').$responsable_carga.$fecha_carga."</td>
                </tr>
                <tr>
                    <td>Procesado por</td>
                    <td>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
                </tr>
                <tr>
                    <td>A trav&eacute;s del M&oacute;dulo</td>
                    <td>Carga de STDLT</td>
                </tr>                
              </table>";
              $responsable_carga_registro_stdlt= responsable_carga_registro_stdlt($link2,$trabajador,$fecha,'ST');
              ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "APROBAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matblg@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmaa@briqven.com.ve","matmca@briqven.com.ve");
              pg_close($link2);
          }   


         $inpt='1';
      }else{
         $inpt=$row['Error'];
      }  
     
   }
   elseif ($SP=='SW_BORRA_ST_CEDULA') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?,?");
      
          
      $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha, PDO::PARAM_STR, 10);
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

      if ($row['Error']=='OK'){
         $inpt='1';
      }else{
         $inpt=$row['Error'];
      }  
     
   }
   elseif ($SP=='SW_GRABA_CAMBIO_DLT_CEDULA') {      
      //if ($entrada==''){
      //  $inpt='El trabajador no tiene fichada real';
      //}
//print "<br>entEsp1: ".$entEsp1.", salEsp1: ".$salEsp1;
      if ((($entEsp1=='LL:LL') && ($salEsp1=='LL:LL') || (($entEsp1=='FF:FF') && ($salEsp1=='FF:FF')))) {
          $stmt = $mbd->prepare("EXEC ".$SP." ?,?,?,?,?,?,?,?,?,?");
          $causalST='08';
          $obsSt= $motivo_DLT; //'Horas Extraordinarias';
          $dlt2="";
          $cod2="";
             
          $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);
          $stmt->bindParam(2, $fecha, PDO::PARAM_STR, 10); 
          $stmt->bindParam(3, $Inicio_DLT1, PDO::PARAM_STR, 5); 
          $stmt->bindParam(4, $Fin_DLT1, PDO::PARAM_STR, 5); 
          $stmt->bindParam(5, $dlt2, PDO::PARAM_STR, 5); 
          $stmt->bindParam(6, $dlt2, PDO::PARAM_STR, 5); 
          $stmt->bindParam(7, $causalST, PDO::PARAM_STR, 2); 
          $stmt->bindParam(8, $cod2, PDO::PARAM_STR, 2); 
          $stmt->bindParam(9, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
          $stmt->bindParam(10, $obsSt, PDO::PARAM_STR, 255);                       
          
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);          
          

          /***********************************************************************/
          /*          VERIFICAMOS QUE SI EXISTE O NO EN LA BD LOCAL              */
          /***********************************************************************/
          $query_consulta = "select * from sw_hoja_de_tiempo_real where cedula = ".$trabajador." and fecha= '".$fecha."'";
          $link_conn      = Conex_Contancia_pgsql();
          $result         = ejecutar_query($link_conn, $query_consulta) or die("Error en la Consulta SQL: ".$query_consulta);
          $contar         = ejecutar_num_rows($result);
          $row            = ejecutar_fetch_array($result);
          $responsable_carga = '';
          $fecha_carga = '';
          if ($contar>0){
              $responsable_carga = "<tr><td>Cargado por</td><td>".nombre_trabajadores($row['ced_dlt'])."</td></tr>";
              if ($row['fecha_carga_st_dlt'] != NULL)
                  $fecha_carga="<tr><td>El d&iacute;a</td><td>".$row['fecha_carga_st_dlt']."</td></tr>";

              if ($row['validado_stdlt']==0){
                $query_update2="validado_stdlt='".$_SESSION['cedula_session_const']."', ";
              }else{
                $query_update2="";
              }
              $query_update="UPDATE sw_hoja_de_tiempo_real SET ";
              $query_update.="autorizado1='".$_SESSION['cedula_session_const']."', ";
              $query_update.=$query_update2;
              $query_update.="fecha_autor1='".date("Y-m-d H:i:s")."'";
              $query_update.=" where cedula = '".$trabajador."' ";
              $query_update.="and fecha= '".$fecha."'";

              //print "<br>".$query_update;  
              //pg_close($link_conn);  
              $link_conn = Conex_Contancia_pgsql();    
              $resultado = ejecutar_query($link_conn, $query_update);// or die("Error en la Consulta SQL: ".$query_update);
              //print "<br>PASO1";
              require("enviodecorreoxmodulo.php");
              $asunto="Procesado el registro de DLT del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');
  
              $cuerpo  = "Saludos, Se aprob&oacute; el registro de DLT del trabajador
                <table border='1'>
                  <tr>
                    <td>Nombre</td>
                    <td>".nombre_trabajadores(trim($trabajador))."</td>
                  </tr>
                  <tr>
                    <td>C&eacute;dula de indentidad</td>
                    <td>".trim($trabajador)."</td>
                  </tr>
                  <tr>
                    <td>De fecha</td>
                    <td>".formato_fecha($fecha,'-').$responsable_carga.$fecha_carga."</td>
                  </tr>
                  <tr>
                    <td>Procesado por</td>
                    <td>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
                  </tr>
                  <tr>
                    <td>A trav&eacute;s del M&oacute;dulo</td>
                    <td>Carga de STDLT</td>
                  </tr>                   
                </table>";
               
                $responsable_carga_registro_stdlt= responsable_carga_registro_stdlt($link_conn,$trabajador,$fecha,'DLT');
              
              // ENVIAR_CORREO_INDIVIDUAL($cuerpo,$asunto,"",$_SESSION['user_session_const'], "APROBAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve","");
              ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "APROBAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matblg@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matmaa@briqven.com.ve","matmca@briqven.com.ve");
              pg_close($link_conn);
          }    
          $inpt='1';
      
      }elseif((($entEsp1!='LL:LL') && ($salEsp1!='LL:LL')) || (($entEsp1!='FF:FF') && ($salEsp1!='FF:FF'))){
          $inpt='El trabajador no esta libre en este dia';
      }elseif($entrada==''){
          $inpt='El trabajador no esta fichada en este dia';
      }else{
          $inpt='Error en parametros';
      }             
     
     
   }elseif ($SP=='SW_BORRA_CAMBIO_DLT_CEDULA') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?,?,?");
      
          
      $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha, PDO::PARAM_STR, 10);
      $stmt->bindParam(3, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

      if ($row['Error']=='OK'){
         $inpt='1';
      }else{
         $inpt=$row['Error'];
      }  
     
   }
   elseif($SP=='SW_GRABA_CAMBIO_ESPERANZA_CEDULA'){
      if ($entrada2==''){
         $entrada2='00:00';
         $salida2='00:00';
      }
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?, ?");
      $stmt->bindParam(1, $trabajador,  PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha,  PDO::PARAM_STR,10);
      $stmt->bindParam(3, $entEsp1,  PDO::PARAM_STR,5);
      $stmt->bindParam(4, $salEsp1,  PDO::PARAM_STR,5);
      $stmt->bindParam(5, $entrada2,  PDO::PARAM_STR,5);
      $stmt->bindParam(6, $salida2,  PDO::PARAM_STR,5); 
      $stmt->bindParam(7, $_SESSION['cedula_session_const'],  PDO::PARAM_INT,10); 
      $stmt->execute();
      
      $inpt='1';
   }elseif ($SP=='RECHAZADO'){
      if ($observacion ==''){
          $inpt = 'Debe ingresar el motivo por el cual se rechaza el registro';
      }else {
          $inpt = RECHAZAR_STDLT($trabajador, $fecha, $_SESSION['cedula_session_const'], $observacion,'Aprobacion');
          if ($inpt=='1'){
              require("enviodecorreos.php");
              $asunto="Rechazo de registro de ".$tipoSTDLT." del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');

              $cuerpo  = "Saludos, el registro de ".$stdlt." del trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>De fecha: ".formato_fecha($fecha,'-')."<br>Presenta el siguiente problema: ".$observacion;
              ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","informatico12@gmail.com","matzem@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve",$quien_hizo_registro."@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matrot@briqven.com.ve","matzem@briqven.com.ve");
              //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matagm@briqven.com.ve");
              $inpt = '1';
          }
      } 
  }  
  return $inpt;

}         
?>