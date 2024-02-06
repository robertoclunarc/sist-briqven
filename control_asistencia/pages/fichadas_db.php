<?php
 //  include("../BD/conexion.php");
 include("funciones_var.php");
session_start();

$fecha       = isset($_POST["fecha"])?$_POST["fecha"]:NULL;
$trabajador  = isset($_POST["cedula"])?$_POST["cedula"]:NULL;
$codigo      = isset($_POST["cod_ausencia"])?$_POST["cod_ausencia"]:NULL;
$entrada     = isset($_POST["entReal1"])?$_POST["entReal1"]:'';
$salida      = isset($_POST["salReal1"])?$_POST["salReal1"]:NULL;
$comida      = isset($_POST["optionsComida"])?$_POST["optionsComida"]:NULL;
$Inicio_ST1  = isset($_POST["Inicio_ST1"])?$_POST["Inicio_ST1"]:NULL;
$Fin_St1     = isset($_POST["Fin_St1"])?$_POST["Fin_St1"]:NULL;
$Inicio_DLT1 = isset($_POST["Inicio_DLT1"])?$_POST["Inicio_DLT1"]:NULL;
$Fin_DLT1    = isset($_POST["Fin_DLT1"])?$_POST["Fin_DLT1"]:NULL;
$entEsp1     = isset($_POST["entEsp1"])?$_POST["entEsp1"]:NULL;
$salEsp1     = isset($_POST["salEsp1"])?$_POST["salEsp1"]:NULL;
$entrada2    = isset($_POST["entEsp2"])?$_POST["entEsp2"]:NULL;
$salida2     = isset($_POST["salEsp2"])?$_POST["salEsp2"]:NULL;
$motivo_ST   = isset($_POST["motivo_ST"])?$_POST["motivo_ST"]:NULL;
$motivo_DLT  = isset($_POST["motivo_DLT"])?$_POST["motivo_DLT"]:NULL;
$razon       = isset($_POST["razon"])?$_POST["razon"]:NULL;
$observacion = isset($_POST["observacion_validar"])?$_POST["observacion_validar"]:NULL;

$Sp= isset($_GET["SP"])?$_GET["SP"]:NULL;
$razon1='';
if (isset($_POST["razon"])){
  foreach($razon as $seleccion) {
    $razon1.=','.$seleccion;
  }
  $razon1=substr($razon1, 1);
}

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
      case 11:
        $storeP='VALIDAR_STDLT';
        break;  
      case 12:
        $storeP=='RECHAZAR_STDLT';
        break;     
      /*
      default:
        # code...
        break;
     */
    }

    echo ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $storeP, $motivo_ST, $motivo_DLT, $razon1);     
}
else{
    echo 'Debe iniciar la su sesion'; 
} 

function ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $SP, $motivo_ST, $motivo_DLT, $razon1) {

   include("../BD/conexion.php");
   $mbd=Conectarse_sitt();       
   $inpt="No se ejecuto ninguna operacion";  
   if ($_SESSION['user_session_const']=='matary'){
       $correo1 = "matbab@briqven.com.ve";
       $responsable_carga="AREVALO YUDISAY";
   }elseif ($_SESSION['user_session_const']=='sirj3v'){
       $correo1 = "matj3v@briqven.com.ve";
       $responsable_carga="JOICE VELASQUEZ";
   }else  {
       $correo1 = $_SESSION['user_session_const']."@briqven.com.ve";
       $responsable_carga=nombre_trabajadores(trim($_SESSION['cedula_session_const']));
   }  
   $correo2 = "matvxl@briqven.com.ve";   //LUIS VERENZUELA     //matvxl@briqven.com.ve
   $correo3 = "matzem@briqven.com.ve";   //MERVIN ZERPA        //matzem@briqven.com.ve
   $correo4 = "brilci@briqven.com.ve";   //CIPRIANO LOPEZ
   //$correo4 = "brilci@briqven.com.ve";   //TAMARA RODRIGUEZ  //matrot@briqven.com.ve
   $correo5 = "mataln@briqven.com.ve";   //NELKRIS ALEXANDER   //
   $correo6 = "matblg@briqven.com.ve";   //BLAS GONZALEZ
   $correo7 = "matqjo@briqven.com.ve";   //JORGE QUINTERO
   $correo8 = "";   //

   if (($salida2=='NULL') || ($salida2==''))
       $salida = $salEsp1;
   else
       $salida = $salida2;

//$SP='';
   if ($SP=='poner_fichada_completa'){
      $inpt = 'ENTRO1'; 
      /*$stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);           
      $stmt->bindParam(3, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      //$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

      if ($row['cedula']==$trabajador)
        $inpt='1';
      else
        $inpt='Error al colocar la fichada completa';
      
*/
   }elseif ($SP=='poner_fichada_codausencia_horas') {
    $inpt = 'ENTRO2'; 
    /*  $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?");
      $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $codigo, PDO::PARAM_INT);
      $stmt->bindParam(4, $entrada, PDO::PARAM_STR,5);
      $stmt->bindParam(5, $salida, PDO::PARAM_STR,5);           
      $stmt->bindParam(6, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      //$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      if ($row['cedula']==$trabajador){
          //2da fichada:
          if ($entrada2!='' && $salida2!='' && $entrada2!=NULL){
            $stmt = $mbd->prepare("EXEC poner_2da_fichada ?, ?, ?, ?, ?");
            $stmt->bindParam(1, $fecha, PDO::PARAM_STR,10);          
            $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);            
            $stmt->bindParam(3, $entrada2, PDO::PARAM_STR,5);
            $stmt->bindParam(4, $salida2, PDO::PARAM_STR,5);           
            $stmt->bindParam(5, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
            //$stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);            
          }
          $inpt='1';
      }
      else
        $inpt='Error al colocar la fichada parcial con codigo de ausencia';
    */  
   }elseif ($SP=='completarAl100') {
    $inpt = 'ENTRO3'; 
    /*  $stmt = $mbd->prepare(" update sw_hoja_de_tiempo_real set horasnetapresencia=8, horasnetaausencia=0, coderror=100, autorizado1=?, Fecha_autor1=GETDATE() where cedula=? and fecha=? ");
      $stmt->bindParam(1, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $fecha, PDO::PARAM_STR,10);                       
      
      //$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      $inpt='1';
    */  
   }elseif ($SP=='MediaHoraComida') {
    $inpt = 'ENTRO4'; 
    /*  $stmt = $mbd->prepare(" update sw_hoja_de_tiempo_real set PagoComida=? where cedula=? and fecha=? ");
      $stmt->bindParam(1, $comida, PDO::PARAM_STR,1);
      $stmt->bindParam(2, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(3, $fecha, PDO::PARAM_STR,10);                       
      
      //$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      
      $inpt='1';
   */   
   }elseif ($SP=='SW_GRABA_ST_CEDULA') {
      $inpt = 'ENTRO7'; 
      $causalST='08';
      //$obsSt='';
      $st2="";
      $cod2="";
      $horas_st = calcular_horas_extras2($fecha,$Inicio_ST1,$Fin_St1);

      /*if ($Inicio_ST1 =='')
          $inpt = 'Debe ingresar la hora de Inicio H. Extras';
      elseif (($Inicio_ST1>$entEsp1) && ($Inicio_ST1<$salida))
        $inpt = 'La hora de Inicio H. Extras se encuentra dentro de la esperanza'; 
      elseif ($Fin_St1=='')  
          $inpt = 'Debe ingresar la hora de Fin H. Extras';
      elseif (($Fin_St1>$entEsp1)  && ($Fin_St1<$salida))
        $inpt = 'La hora de Fin H. Extras se encuentra dentro de la esperanza';
      elseif ($motivo_ST=='')
          $inpt = 'Debe ingresar el motivo de las Horas Extras';  
      else{
        $inpt = SW_GRABA_ST_CEDULA($trabajador, $fecha, $Inicio_ST1, $Fin_St1, $st2, $st2, $causalST, $cod2, $_SESSION['cedula_session_const'], $motivo_ST);*/
        if ($Inicio_ST1 =='')
          $inpt = 'Debe ingresar la hora de Inicio H. Extras';
        elseif ($Fin_St1=='')  
          $inpt = 'Debe ingresar la hora de Fin H. Extras';
        elseif ($motivo_ST=='')
          $inpt = 'Debe ingresar el motivo de las Horas Extras';  
        elseif (!isset($_POST["razon"]))
          $inpt = 'Debe seleccionar la razón o justificación';  
        else{
       
          $inpt = SW_GRABA_ST_CEDULA($trabajador, $fecha, $Inicio_ST1, $Fin_St1, $st2, $st2, $causalST, $cod2, $_SESSION['cedula_session_const'], $motivo_ST, $razon1);

        if (($inpt=='1') || ($inpt=='2')){
            require("enviodecorreos.php");
            if ($inpt=='1'){
                $asunto = "Registro de Horas Extras de: ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
                $accion = "Se Cargaron ".$horas_st." horas extras al trabajador";
            }

            if ($inpt=='2'){
                     $asunto="Modificacion de Horas Extras de ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
                     $accion="Se Modific&oacute; el registro de Horas Extras al trabajador";
            }
            
    
            $cuerpo  = "Saludos, ".$accion."  
            <table border='1'>
              <tr>
                <td>Nombre </td>
                <td>".nombre_trabajadores(trim($trabajador))."
              </tr>
              <tr>
                <td>C&eacute;dula de indentidad</td>
                <td>".trim($trabajador)."</td>
              </tr>
              <tr>
                <td>Fecha: </td>
                <td>".formato_fecha($fecha,'-')."</td>
              </tr>
              <tr>
                <td>Motivo</td>
                <td>".trim(eliminar_acentos($motivo_ST)).".</td>
              </tr>
              <tr>
                <td>Cargado por</td>
                <td>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
              </tr>
            </table>";
            ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "AUTORIZAR STDLT",$correo1,"matzem@briqven.com.ve");
            /*
            ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
            ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
            ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
            */
            $inpt = '1';
        }
      }
   }elseif ($SP=='SW_BORRA_ST_CEDULA') {
      $inpt = SW_BORRA_ST_CEDULA($trabajador, $fecha);
      if ($inpt=='1'){
          require("enviodecorreoxmodulo.php");
          $asunto="Eliminado registro de ST del trabajador ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
          $accion="Modificó";

          $cuerpo  = "Saludos, Se elimino el registro de horas extras del trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>Fecha: ".formato_fecha($fecha,'-');
          //ENVIAR_CORREO_INDIVIDUAL($cuerpo0,$asunto0,"",$_SESSION['user_session_const'], "AUTORIZAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve","");
          ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "AUTORIZAR STDLT",$correo1,"");
          /*
          ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
          ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
          ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
          */
          $inpt = '1';
      }
   }elseif ($SP=='SW_GRABA_CAMBIO_DLT_CEDULA') {  
    $inpt = 'ENTRO8';     
      //if ($entrada==''){
      //  $inpt='El trabajador no tiene fichada real';
      //}
        
      //if ((($entEsp1=='LL:LL') && ($salEsp1=='LL:LL') || (($entEsp1=='FF:FF') && ($salEsp1=='FF:FF')))) {
          
          $causalDLT='08';
          $obsDLT='Horas Extraordinarias';
          $dlt2="";
          $cod2="";

          /*if ($Inicio_DLT1 =='')
              $inpt = 'Debe ingresar la hora de Inicio DLT';
          elseif (($Inicio_DLT1>$entEsp1) && ($Inicio_DLT1<$salida))
            $inpt = 'La hora de Inicio DLT se encuentra dentro de la esperanza'; 
          elseif ($Fin_DLT1=='')  
              $inpt = 'Debe ingresar la hora de Fin DLT';
          elseif (($Fin_DLT1>$entEsp1)  && ($Fin_DLT1<$salida))
            $inpt = 'La hora de Fin DLT se encuentra dentro de la esperanza';
          elseif ($motivo_DLT=='')
              $inpt = 'Debe ingresar el motivo del DLT';  
          else
            $inpt = SW_GRABA_CAMBIO_DLT_CEDULA($trabajador, $fecha, $Inicio_DLT1, $Fin_DLT1, $dlt2, $dlt2, $causalDLT, $cod2, $_SESSION['cedula_session_const'], $motivo_DLT); 
          */

            if ($Inicio_DLT1 =='')
              $inpt = 'Debe ingresar la hora de Inicio DLT';
            elseif ($Fin_DLT1=='')  
              $inpt = 'Debe ingresar la hora de Fin DLT';
            elseif ($motivo_DLT=='')
              $inpt = 'Debe ingresar el motivo del DLT';  
            else
              $inpt = SW_GRABA_CAMBIO_DLT_CEDULA($trabajador, $fecha, $Inicio_DLT1, $Fin_DLT1, $dlt2, $dlt2, $causalDLT, $cod2, $_SESSION['cedula_session_const'], $motivo_DLT, $razon1); 

            if (($inpt=='1') || ($inpt=='2')){
                require("enviodecorreos.php");
                if ($inpt=='1'){
                    $asunto = "Registro de DLT de ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
                    $accion="cargo";
                }

                if ($inpt=='2'){
                    $asunto="Modificacion de DLT de ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
                    $accion="Modifico";
                }

                $cuerpo  = "Saludos, Se ".$accion." el DLT del trabajador
                <table border ='1'>
                  <tr>
                    <td>Nombre</td>
                    <td> ".nombre_trabajadores(trim($trabajador))."</td>
                  </tr>
                  <tr>
                    <td>C&eacute;dula de indentidad</td>
                    <td>".trim($trabajador)."</td>
                  </tr>
                  <tr>
                    <td>Fecha</td>
                    <td>".formato_fecha($fecha,'-')."</td>
                  </tr>
                  <tr>  
                    <td>Motivo</td>
                    <td>".trim(eliminar_acentos($motivo_DLT))."</td>
                  </tr>
                  <tr>
                    <td>Cargado por</td>
                    <td>".$responsable_carga."</td>
                  </tr>
                </table>";
                ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "AUTORIZAR STDLT",$correo1,"");
                /*
                ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
                ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
                ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
                */
                $inpt = '1';
            }
      //}             
   }elseif ($SP=='SW_BORRA_CAMBIO_DLT_CEDULA') {
    $inpt = 'ENTRO9'; 
    $inpt = SW_BORRA_DLT_CEDULA($trabajador, $fecha);
    if ($inpt=='1'){
        require("enviodecorreos.php");
        $asunto="Eliminado registro de DLT del trabajador ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
        $accion="Modificó";

        $cuerpo  = "Saludos, Se elimino el registro de DLT del trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>Fecha: ".formato_fecha($fecha,'-');
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "AUTORIZAR STDLT",$correo1,"");
        /*
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
        */
        $inpt = '1';
    }
  }elseif($SP=='SW_GRABA_CAMBIO_ESPERANZA_CEDULA'){
    $inpt = 'ENTRO10'; 
    /*  if ($entrada2==''){
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
     // $stmt->execute();
      
      $inpt='1';
      */
   }elseif($SP=='VALIDAR_STDLT'){
    $inpt = 'ENTRO11';
    $inpt = VALIDAR_STDLT($trabajador, $fecha, $_SESSION['cedula_session_const'], $observacion);
    if ($inpt=='1'){
        require("enviodecorreos.php");
        $asunto="Validacion de registro de DLT del trabajador ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');

        $cuerpo  = "Saludos, Se valido el registro de STDLT del trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>Fecha: ".formato_fecha($fecha,'-');
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "VALIDAR STDLT",$correo1,"");
        /*ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
        */
        $inpt = '1';
    }

   }elseif($SP=='RECHAZAR_STDLT'){
    $inpt = 'ENTRO12';
    if ($observacion =='')
          $inpt = 'Debe registrar la observacion por la cual es rechazada';
   }else{
    $inpt = RECHAZAR_STDLT($trabajador, $fecha, $_SESSION['cedula_session_const'], $observacion);
    if ($inpt=='1'){
        require("enviodecorreos.php");
        $asunto="Rechazo de registro de DLT del trabajador ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');

        $cuerpo  = "Saludos, El registro de registro de STDLT del trabajador: ".nombre_trabajadores(trim($trabajador)).", C&eacute;dula de indentidad:".trim($trabajador).", de Fecha: ".formato_fecha($fecha,'-').', fue rechazada por: '.$observacion;
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo1,$correo2);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo3,$correo4);
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "",$correo7,$correo8);
        $inpt = '1';
    }    
   } 
 
  return $inpt;

}         
?>