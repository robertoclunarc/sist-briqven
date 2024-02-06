<?PHP
session_start();
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    include("../BD/conexion.php");
    include("funciones_var.php");
    //require("enviodecorreos.php");
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
    $observacion = isset($_POST["observacion_validar"])?$_POST["observacion_validar"]:NULL;
    $Sp          = isset($_GET["SP"])?$_GET["SP"]:NULL;


    switch ($Sp) {
      case 11:
        $Sp='VALIDAR_STDLT';
        break;  
      case 12:
        $Sp=='RECHAZAR_STDLT';
        break;     
    }

    echo ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $motivo_ST, $motivo_DLT, $Sp,$observacion); 

}else{
    echo "De Iniciar Sesion";
}

    function ejecutarSp($fecha, $trabajador, $codigo, $entrada, $salida, $comida , $Inicio_ST1, $Fin_St1, $Inicio_DLT1, $Fin_DLT1, $entEsp1, $salEsp1, $entrada2, $salida2, $motivo_ST, $motivo_DLT, $SP, $observacion) {
        if ($motivo_DLT!='' && $motivo_ST!=''){
            $stdlt     = 'Horas Extras y DLT';
            $tipoSTDLT = 'ST';
        }elseif ($motivo_DLT=='' && $motivo_ST!=''){
            $stdlt = 'Horas Extras';
            $tipoSTDLT = 'ST';
        }elseif ($motivo_DLT!='' && $motivo_ST==''){
            $stdlt = 'DLT';  
            $tipoSTDLT = 'DLT'; 
        }

        
        $link  = Conex_Contancia_pgsql();
        //$quien_hizo_registro=              responsable_carga_registro_stdlt($link,$trabajador,$fecha,$tipoSTDLT);
        $responsable_carga_registro_stdlt= responsable_carga_registro_stdlt($link,$trabajador,$fecha,$tipoSTDLT);

        if ($fecha!='NULL' && $trabajador!='NULL'){
            if($SP=='VALIDAR_STDLT'){ 
                       
                $inpt = 'ENTRO11';
                $inpt = VALIDAR_STDLT($trabajador, $fecha, $_SESSION['cedula_session_const'], $observacion);
                if ($inpt=='1'){
                    require("enviodecorreoxmodulo.php");
                    $asunto="Validacion de ".$tipoSTDLT." del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');

                    $cuerpo  = "Saludos, Se valido el registro de STDLT del trabajador<table border='1'>
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
                               <td>".formato_fecha($fecha,'-')."</td>
                            </tr>
                            <tr>
                              <td>Validado por</td>
                               <td>".nombre_trabajadores($_SESSION['cedula_session_const'])."</td>
                            </tr>
                        </table>";
                    ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "VERIFICAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve");
                    //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve",$quien_hizo_registro."@briqven.com.ve");
                    //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matrot@briqven.com.ve","matblg@briqven.com.ve");
                    //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve",""); 
                    $inpt = '1';
                }
            }elseif ($observacion ==''){
                    $inpt = 'Debe ingresar el motivo por el cual se rechaza el registro';
                }else {
                    $inpt = RECHAZAR_STDLT($trabajador, $fecha, $_SESSION['cedula_session_const'], $observacion);
                    if ($inpt=='1'){
                        require("enviodecorreoxmodulo.php");
                        $asunto="Rechazo de registro de ".$tipoSTDLT." del trabajador: ".nombre_trabajadores(trim($trabajador)).", de fecha: ".formato_fecha($fecha,'-');

                        $cuerpo  = "Saludos, el registro de ".$stdlt." del trabajador
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
                               <td> ".formato_fecha($fecha,'-')."</td>
                            </tr>
                            <tr>
                                <td>Presenta el siguiente problema</td>
                                <td>".$observacion."</td>
                            </tr>
                            <tr>
                              <td>Rechazado por</td>
                               <td>".nombre_trabajadores($_SESSION['cedula_session_const'])."</td>
                            </tr>                            
                        </table>";
                        ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "VERIFICAR STDLT",$responsable_carga_registro_stdlt."@briqven.com.ve");
                        //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matvxl@briqven.com.ve",$quien_hizo_registro."@briqven.com.ve");
                        //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matrot@briqven.com.ve","matzem@briqven.com.ve");
                        //ENVIAR_CORREO($cuerpo,$asunto,"",$_SESSION['user_session_const'], "","matzem@briqven.com.ve","matagm@briqven.com.ve");
                        $inpt = '1';
                    }
                } 
            return $inpt;   
        }
    }


?>