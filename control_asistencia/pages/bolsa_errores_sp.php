<?php
session_start();

$fecha= isset($_POST["fecha"])?$_POST["fecha"]:NULL;
$trabajador= isset($_POST["cedula"])?$_POST["cedula"]:NULL;
$codigo= isset($_POST["cod_ausencia"])?$_POST["cod_ausencia"]:NULL;
$entrada= isset($_POST["entReal1"])?$_POST["entReal1"]:'';
$salida= isset($_POST["salReal1"])?$_POST["salReal1"]:NULL;
$comida= isset($_POST["optionsComida"])?$_POST["optionsComida"]:NULL;
$Inicio_ST1= isset($_POST["Inicio_ST1"])?$_POST["Inicio_ST1"]:NULL;
$Fin_St1= isset($_POST["Fin_St1"])?$_POST["Fin_St1"]:NULL;
$Inicio_DLT1= isset($_POST["Inicio_DLT1"])?$_POST["Inicio_DLT1"]:NULL;
$Fin_DLT1= isset($_POST["Fin_DLT1"])?$_POST["Fin_DLT1"]:NULL;
$entEsp1= isset($_POST["entEsp1"])?$_POST["entEsp1"]:NULL;
$salEsp1= isset($_POST["salEsp1"])?$_POST["salEsp1"]:NULL;
$entrada2= isset($_POST["entEsp2"])?$_POST["entEsp2"]:NULL;
$salida2= isset($_POST["salEsp2"])?$_POST["salEsp2"]:NULL;
$entReal2= isset($_POST["entReal2"])?$_POST["entReal2"]:NULL;
$salReal2= isset($_POST["salReal2"])?$_POST["salReal2"]:NULL;


$entEsp1Anterior  = isset($_POST["entEsp11"])?$_POST["entEsp11"]:NULL;
$salEsp1Anterior  = isset($_POST["salEsp11"])?$_POST["salEsp11"]:NULL;
$entrada2Anterior = isset($_POST["entEsp21"])?$_POST["entEsp21"]:NULL;
$salida2Anterior  = isset($_POST["salEsp21"])?$_POST["salEsp21"]:NULL;

$Sp= isset($_GET["SP"])?$_GET["SP"]:NULL;

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
      /*
      default:
        # code...
        break;
     */
    }

    //echo ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $storeP); 
    echo ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entEsp1Anterior, $salEsp1Anterior, $entrada2, $salida2, $storeP);     
}
else{
    echo 'Debe iniciar la su sesion'; 
} 

//function ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $SP) {
function ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entEsp1Anterior, $salEsp1Anterior, $entrada2, $salida2, $SP) {
   include("../BD/conexion.php");
   include("funciones_var.php");
   require("enviodecorreos.php");   
   $mbd=Conectarse_sitt();       
   $inpt="No se ejejuto ninguna operacion";    
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
      $stmt = $mbd->prepare("EXEC ".$SP." ?, ?, ?, ?, ?, ?, ?, ?");
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
      $obsSt='Horas Extraordinarias';
      $st2="";
      $cod2="";
          
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

        /*************************************************/
        //SE ENVIA CORREO NOTIFICANDO EL CAMBIO    
        $asunto="Registro de Horas Extras del trabajador: ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
        $cuerpo  = "Saludos, Se Registro las Horas Extras al trabajador 
        <table border='1'>
          <tr>
            <td> Nombre</td> 
            <td>".nombre_trabajadores(trim($trabajador))."
          </tr>
          <tr>
            <td>C&eacute;dula de indentidad</td>
            <td>".trim($trabajador)."</td>
          </tr>
          <tr>  
            <td>en fecha</td>
            <td>".formato_fecha($fecha,'-')."</td>
          <tr>
             <td>Hora Inicio Hora Extra</td>
             <td>".$Inicio_ST1."</td>
          </tr>
          <tr>
            <td>Fin Hora Extras</td>
            <td> ".$Fin_St1."</td>
          </tr>
          <tr>
            <td>Registrado por</td>
            <td> ".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
          </tr>
          <tr>
            <td>A trav&eacute;s del M&oacute;dulo</td>
            <td>Bolsa de Errores</td>
          </tr>
        </table>    ";
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve","");
        /*************************************************/           
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
        /*************************************************/
        //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
        $asunto="Se Elimino las Horas Extras del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');
        $cuerpo  = "Saludos, Se elimino las Horas Extras del trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>en fecha: ".formato_fecha($fecha,'-')."<br>Eliminado por: ".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."<br>A trav&eacute;s del M&oacute;dulo: Bolsa de Errores.";
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve","");  
        /*************************************************/      
         $inpt='1';
      }else{
         $inpt=$row['Error'];
      }  
     
   }
   elseif ($SP=='SW_GRABA_CAMBIO_DLT_CEDULA') {      
      
      //if ($entrada==''){
      //  $inpt='El trabajador no tiene fichada real';
      //}

      if ((($entEsp1=='LL:LL') && ($salEsp1=='LL:LL') || (($entEsp1=='FF:FF') && ($salEsp1=='FF:FF')))) {
          $stmt = $mbd->prepare("EXEC ".$SP." ?,?,?,?,?,?,?,?,?,?");
          $causalST='08';
          $obsSt='Horas Extraordinarias';
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

          /*************************************************/
          //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO         
          $asunto="Registro de DLT del trabajador: ".nombre_trabajadores(trim($trabajador))." el ".formato_fecha($fecha,'-');
          $cuerpo  = "Saludos, Se Registro el DLT al trabajador 
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
              <td>en fecha</td>
              <td>".formato_fecha($fecha,'-')."</td>
            </tr>
            <tr>
              <td>Hora Inicio DLT</td>
              <td>".$Inicio_DLT1."</td>
            </tr>
            <tr>
              <td>Fin DLT</td>
              <td> ".$Fin_DLT1."</td>
            </tr>
            <tr>
              <td>Registrado por</td>
              <td> ".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
            </tr>
            <tr>
              <td>A trav&eacute;s del M&oacute;dulo</td>
              <td> Bolsa de Errores</td>
            <tr>
          </table>  ";
          ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve",""); 
          /*************************************************/
          $inpt='1';
      }
      elseif((($entEsp1!='LL:LL') && ($salEsp1!='LL:LL')) || (($entEsp1!='FF:FF') && ($salEsp1!='FF:FF'))){
          $inpt='El trabajador no esta libre en este dia';
      }elseif($entrada==''){
          $inpt='El trabajador no esta fichada en este dia';
      }else{
          $inpt='Error en parametros';
      }             
     
   }
   elseif ($SP=='SW_BORRA_CAMBIO_DLT_CEDULA') {
      $stmt = $mbd->prepare("EXEC ".$SP." ?,?,?");
      
          
      $stmt->bindParam(1, $trabajador, PDO::PARAM_INT);
      $stmt->bindParam(2, $fecha, PDO::PARAM_STR, 10);
      $stmt->bindParam(3, $_SESSION['cedula_session_const'], PDO::PARAM_INT);
      
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

      if ($row['Error']=='OK'){

        /*************************************************/
        //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
        $asunto="Se Elimino el DLT del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');
        $cuerpo  = "Saludos, Se elimino el DLT al trabajador: <br>Nombre: ".nombre_trabajadores(trim($trabajador)).", <br>C&eacute;dula de indentidad:".trim($trabajador)."<br>en fecha: ".formato_fecha($fecha,'-')."<br>Eliminado por: ".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."<br>A trav&eacute;s del  M&oacute;dulo: Bolsa de Errores.";
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve","");
        /*************************************************/        
         $inpt='1';
      }else{
         $inpt=$row['Error'];
      }  
     
   }
   elseif($SP=='SW_GRABA_CAMBIO_ESPERANZA_CEDULA'){
      if ($entrada2=='' or $entrada2=='00:00'){
         $entrada2='';
         $salida2='';
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

      /*************************************************/
        //SE ENVIA CORREO NOTIFICCANDO EL CAMBIO    
      $asunto="Cambio de Esperanza del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');
      $cuerpo  = "Saludos, Se realizo un cambio de Esperanza al trabajador<br>
      <table border='1'>
        <tr>
           <td colspan='2'>Nombre </td>
           <td colspan='2'><b>".nombre_trabajadores(trim($trabajador))."</b></td>
        </tr>
        <tr>
           <td colspan='2'>C&eacute;dula de indentidad </td>
           <td colspan='2'>".trim($trabajador)."</td>
        </tr>
        <tr>
           <td colspan='2'>En fecha </td>
           <td colspan='2'><b>".formato_fecha($fecha,'-')."</b></td>
        </tr>
        <tr>
           <td rowspan='2' colspan='2'><b>Entrada Esperada</b></td>
           <td style='text-align:center; vertical-align:middle'>Anterior</td>
           <td>".$entEsp1Anterior."</td>
        </tr>
        <tr>
          <td style='text-align:center; vertical-align:middle'><b>Nueva</b></td> 
          <td><b>".$entEsp1."</b></td>
        </tr>           
        <tr>   
           <td rowspan='2' colspan='2'><b>Salida Esperada</b></td>           
           <td style='text-align:center; vertical-align:middle'>Anterior</td>
           <td>".$salEsp1Anterior."</td>           
        </tr>   
        <tr>
           <td style='text-align:center; vertical-align:middle'><b>Nueva</b></td>
           <td><b>".$salEsp1."</b></td>
        </tr>
        <tr>   
           <td colspan='2'>Registrado por: </td>
           <td colspan='2'>".nombre_trabajadores(trim($_SESSION['cedula_session_const']))."</td>
        </tr>   
        <tr>   
           <td colspan='2'>A trav&eacute;s del M&oacute;dulo</td>
           <td colspan='2'> Bolsa de Errores.</td>
        </tr></table>";
      
      if ($_SESSION['cedula_session_const'] != '15908042'){
        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matfih@briqven.com.ve","matlux@briqven.com.ve");
      }  
       /*************************************************/      
      $inpt='1';
   }
    
  return $inpt;

}         
?>