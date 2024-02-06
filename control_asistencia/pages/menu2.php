<?php 

function barra_menu2(){
    $barra='';

if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    //require_once('funciones_var.php');
    if ($_SESSION['nivel_const']==5){
        require_once("BD/conexion.php");
    }else {
          require_once("../BD/conexion.php");
    }      
    $linkMenu2=Conex_Contancia_pgsql();
switch ($_SESSION['nivel_const']) {
    case 1:    
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-th-list fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        
                        <li>
                            <a href="consulta_hoja_tiempo.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Hoja de Tiempo
                                    <span class="pull-right text-muted small">Tiempo Trabajado</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consulta_porcentaje_asistencia.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Asistencias
                                    <span class="pull-right text-muted small">Porcentaje de Tiempo Trabajado</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consulta_trabajadores_vacaciones.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Vacaciones
                                    <span class="pull-right text-muted small">Disfrutes de vacaciones</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="kardex.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Kardex
                                    <span class="pull-right text-muted small">Hoja de Tiempo Completa</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="fichada_ccure.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>CCure
                                    <span class="pull-right text-muted small">Asistencias Reales</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consultar_control_acceso.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Control de Acceso
                                    <span class="pull-right text-muted small">Asist. x Control de Acceso</span>
                                </div>
                            </a>
                        </li>
                        <!-- <li>
                            <a href="consulta_ausencias01.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Rep. Ausencia 01
                                    <span class="pull-right text-muted small">Ausencias x Sist. Horario </span>
                                </div>
                            </a>
                        </li>-->';
                if (permiso_usuario($linkMenu2, 'TODO', 'consultar_acum_stdlt.php', $_SESSION['user_session_const'])){
                $barra.='
                        <li>
                            <a href="consultar_acum_stdlt.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Acum. STDLT
                                    <span class="pull-right text-muted small">Acumulados</span>
                                </div>
                            </a>
                        </li>';
                                }
                //if (permiso_usuario($linkMenu2, 'TODO', 'Reporte_STDLT_Nomima', $_SESSION['user_session_const'])){
                $barra.='
                        <li>
                            <a href="consultar_stdlt_nomina.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Reporte de STDLT
                                    <span class="pull-right text-muted small">Nomina</span>
                                </div>
                            </a>
                        </li>';
                               // }                                
                    $barra.='                      
                        <li>
                            <a href="auditoria_pre_nomina.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Pre-Nomina
                                    <span class="pull-right text-muted small">Auditoria</span>
                                </div>
                            </a>
                        </li>
                        <li>
                        <a href="consulta_trabajadores_domicilio.php">
                            <div>
                                <i class="fa fa-comment fa-fw"></i>Datos Generales Trabajadores
                                <span class="pull-right text-muted small"></span>
                            </div>
                        </a>
                    </li>';
                if (permiso_usuario($linkMenu2, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                $barra.='<li>
                            <a href="historico_trabajador.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Historico de Trabajador
                                    <span class="pull-right text-muted small">Suspenciones, reposos, permisos y ausencias</span>
                                </div>
                            </a>
                        </li>';
                }
                if ($_SESSION['user_session_const']=='matzem' ){                
                $barra.='<li>
                            <a href="consulta_trabajadores_familiares.php">
                                <div>
                                    <i class="fa fa-users" aria-hidden="true"></i>Familiares
                                    <span class="pull-right text-muted small">Trabajador con los familiares registrados</span>
                                </div>
                            </a>
                        </li>';                                 
                }        
                    $barra.='
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>'.$_SESSION['username_const'].'</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../login/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
     break;
    case 2:
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        
                        <li>
                            <a href="consulta_hoja_tiempo.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Hoja de Tiempo
                                    <span class="pull-right text-muted small">Ver los Tiempo del Trabajador</span>
                                </div>
                            </a>
                        </li>';  
                        if (permiso_usuario($linkMenu2, 'rep01', 'ausencias', $_SESSION['user_session_const'])){
                            $barra.='<li>
                            <a href="consulta_porcentaje_asistencia.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Asistencias
                                    <span class="pull-right text-muted small">Porcentaje de Tiempo Trabajado</span>
                                </div>
                            </a>
                        </li> 
                        ';
                        }
                        if (permiso_usuario($linkMenu2, 'rep01', 'ausencias', $_SESSION['user_session_const'])){
                            $barra.='<li>
                            <a href="kardex.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Kardex
                                    <span class="pull-right text-muted small">Hoja de Tiempo Completa</span>
                                </div>
                            </a>
                        </li>
                        ';
                        }                        
                        $barra.='                       
                        <li>
                            <a href="fichada_ccure.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>CCure
                                    <span class="pull-right text-muted small">Asistencias Reales</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consultar_control_acceso.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Control de Acceso
                                    <span class="pull-right text-muted small">Asist. x Control de Acceso</span>
                                </div>
                            </a>
                        </li>
                        <!--<li>
                            <a href="consulta_ausencias01.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Rep. Ausencia 01
                                    <span class="pull-right text-muted small">Ausencias x Sist. Horario </span>
                                </div>
                            </a>
                        </li>-->';
                if (permiso_usuario($linkMenu2, 'TODO', 'consultar_acum_stdlt.php', $_SESSION['user_session_const'])){
                $barra.='
                        <li>
                            <a href="consultar_acum_stdlt.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Acum. STDLT
                                    <span class="pull-right text-muted small">Acumulados</span>
                                </div>
                            </a>
                        </li>';
                                }
                if (permiso_usuario($linkMenu2, 'CONSULTA', 'auditoria_pre_nomina.php', $_SESSION['user_session_const'])){                
                    $barra.='
                        <li>
                            <a href="auditoria_pre_nomina.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Pre-Nomina
                                    <span class="pull-right text-muted small">Auditoria</span>
                                </div>
                            </a>
                        </li>';
                }

                        $barra.='<li>
                        <a href="consulta_trabajadores_domicilio.php">
                            <div>
                                <i class="fa fa-comment fa-fw"></i>Datos Generales Trabajadores
                                <span class="pull-right text-muted small"></span>
                            </div>
                        </a>
                    </li>';                   
                if (permiso_usuario($linkMenu2, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                $barra.='<li>
                            <a href="historico_trabajador.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Historico de Trabajador
                                    <span class="pull-right text-muted small">Suspenciones, reposos, permisos y ausencias</span>
                                </div>
                            </a>
                        </li>';
                                }
                if ($_SESSION['user_session_const']=='mataln'){                
                $barra.='<li>
                            <a href="consulta_trabajadores_familiares.php">
                                <div>
                                    <i class="fa fa-users" aria-hidden="true"></i>Familiares
                                    <span class="pull-right text-muted small">Trabajador con los familiares registrados</span>
                                </div>
                            </a>
                        </li>';                                 
                }                               
                    $barra.='            
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>'.$_SESSION['username_const'].'</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../login/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
     break;
    case 3:    
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i><a href="#">Auditorias
                                    <span class="pull-right text-muted small">Ver Actividades de usuarios</span>
                                </div>
                            </a>
                        </li>';
                if (permiso_usuario($linkMenu2, 'TODO', 'consultar_acum_stdlt.php', $_SESSION['user_session_const'])){
                $barra.='
                        <li>
                            <a href="consultar_acum_stdlt.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Acum. STDLT
                                    <span class="pull-right text-muted small">Acumulados</span>
                                </div>
                            </a>
                        </li>';
                                }                        
                if (permiso_usuario($linkMenu2, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                $barra.='<li>
                            <a href="historico_trabajador.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Historico de Trabajador
                                    <span class="pull-right text-muted small">Suspenciones, reposos, permisos y ausencias</span>
                                </div>
                            </a>
                        </li>';
                                }
                    $barra.='                        
                        
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>'.$_SESSION['username_const'].'</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../login/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
     break;
    case 4:    
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-th-list fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        
                        <li>
                            <a href="consulta_hoja_tiempo.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Hoja de Tiempo
                                    <span class="pull-right text-muted small">Tiempo Trabajado</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consulta_porcentaje_asistencia.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Asistencias
                                    <span class="pull-right text-muted small">Porcentaje de Tiempo Trabajado</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consulta_trabajadores_vacaciones.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Vacaciones
                                    <span class="pull-right text-muted small">Disfrutes de vacaciones</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="kardex.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Kardex
                                    <span class="pull-right text-muted small">Hoja de Tiempo Completa</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="fichada_ccure.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>CCure
                                    <span class="pull-right text-muted small">Asistencias Reales</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="consultar_control_acceso.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Control de Acceso
                                    <span class="pull-right text-muted small">Asist. x Control de Acceso</span>
                                </div>
                            </a>
                        </li>
                         <!--<li>
                            <a href="consulta_ausencias01.php">
                               
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Rep. Ausencia 01
                                    <span class="pull-right text-muted small">Ausencias x Sist. Horario </span>
                                </div>
                            </a>
                        </li>-->';
                if (permiso_usuario($linkMenu2, 'TODO', 'consultar_acum_stdlt.php', $_SESSION['user_session_const'])){
                $barra.='
                        <li>
                            <a href="consultar_acum_stdlt.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Acum. STDLT
                                    <span class="pull-right text-muted small">Acumulados</span>
                                </div>
                            </a>
                        </li>';
                                }
                    $barra.='                      
                        <li>
                            <a href="auditoria_pre_nomina.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Pre-Nomina
                                    <span class="pull-right text-muted small">Auditoria</span>
                                </div>
                            </a>
                        </li>';
                if (permiso_usuario($linkMenu2, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                $barra.='<li>
                            <a href="historico_trabajador.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>Historico de Trabajador
                                    <span class="pull-right text-muted small">Suspenciones, reposos, permisos y ausencias</span>
                                </div>
                            </a>
                        </li>';
                                }
                    $barra.='
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>'.$_SESSION['username_const'].'</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../login/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
     break;
     case 5:    
    $barra.='<ul class="nav navbar-top-links navbar-right">                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>'.$_SESSION['username_const'].'</a>
                        </li>
                        <li><a href="indexCCure.php"><i class="fa fa-comment fa-fw"></i> Asistencias</a>
                        </li>
              ';
    if (permiso_usuario($linkMenu2, 'VER', 'reportAusencias.php', $_SESSION['user_session_const'])){
                        $barra.='<li><a href="reportAusencias.php"><i class="fa fa-comment fa-fw"></i> Ausencias</a>
                        </li>';
    }
                        $barra.='<li class="divider"></li>
                        <li><a href="login/logout.php"><i class="fa fa-sign-out fa-fw"></i> LogOut</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
     break;
}
pg_close($linkMenu2);
}else{
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">                        
                        
                        <li><a href="login/logout.php"><i class="fa fa-sign-out fa-fw"></i> LogOut</a>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';

}

return $barra;            
}            
?>
