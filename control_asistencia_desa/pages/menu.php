<?php
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const']))
    if ($_SESSION['nivel_const']==5)
        header('Location: ../indexCCure.php');
function barra_menu(){
    if ($_SESSION['nivel_const']!=5)
        require_once("../BD/conexion.php");
    else
        require_once("BD/conexion.php");
    require_once('funciones_var.php');       
    $linkMenu=Conex_Contancia_pgsql();
$barra='';
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const']))
switch ($_SESSION['nivel_const']) {
    //case ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const'] ==2):
    case ($_SESSION['nivel_const']==1 ):
        $barra.='<div class="navbar-'.$_SESSION['modeBlack_const'].' sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Inicio</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-credit-card"></i> Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">';
                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="fichadas.php">Cargar ST y DLT</a> 
                                </li>';
                }
                if ((permiso_usuario($linkMenu, 'CONSULTAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {    
                                $barra.='<li>
                                    <a href="consultar_stdlt.php">Consultar ST y DLT</a> 
                                </li>';
                } 
                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])) || ($_SESSION['user_session_const']=='matrot'))
                {
                                $barra.='
                                <li>
                                    <a href="imprimir_planilla_stdlt.php">Imprimir Planilla</a> 
                                </li>';
                }                
                if ((permiso_usuario($linkMenu, 'REALIZAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                {
                                $barra.='<li>
                                    <a href="cargar_movimiento.php">Movimiento Temporales</a> 
                                </li>
                                <li>
                                    <a href="cargar_movimiento_indeterminado.php">Movimiento Permanente</a> 
                                </li>';
                }                 
                                $barra.='<li>
                                    <a href="fichada_ccure.php">Fichada CCURE</a>
                                </li>'; 
                if ((permiso_usuario($linkMenu, 'AUTORIZAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="autorizar_stdlt.php">Autorizar ST y DLT</a> 
                                </li>';
                }
                if ((permiso_usuario($linkMenu, 'VERIFICAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="verificar_stdlt.php">Verificar ST Y DLT</a> 
                                </li>';
                } 
                if ((permiso_usuario($linkMenu, 'PROCESAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="aprobar_stdlt.php">Procesar ST y DLT</a> 
                                </li>';
                }
                if ((permiso_usuario($linkMenu, 'IMPRIMIR', 'planilla_stdlt_inspectoria', $_SESSION['user_session_const'])) )
                {
                                $barra.='<li>
                                    <a href="imprimir_planilla_stdlt_inspectoria.php">STDLT Inspectoria</a> 
                                </li>';
                }                   
                               $barra.='                                
                            </ul>                           
                        </li>'; 
                if (permiso_usuario($linkMenu, 'MENU', 'menu_permiso', $_SESSION['user_session_const']))
                {
                    $barra.='<li>                        
                        <a href="#"><i class="fa fa-shield"></i> Permisos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="consultar_permisos.php">Consultar</a>
                            </li>';                            
                            if (permiso_usuario($linkMenu, 'CARGAR_PERM', 'cargar_permiso.php', $_SESSION['user_session_const'])){
                                $barra.='<li>
                                     <a href="cargar_permiso.php">Cargar</a>
                                </li>';
                            }
                        $barra.='<li>
                                <a href="hist_permisos.php">Historico</a>
                        </li>
                        </ul>                           
                    </li> 
                    ';
                }
                               $barra.='
                        <li>
                            <a href="#"><i class="fa fa-flag"></i> Comision de Servicio<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consulta_trabajadores_comisiones.php">Consultar</a>
                                </li>
                                <li>
                                    <a href="insertcomision.php">Cargar</a>
                                </li>
                                
                            </ul>                           
                        </li>            
                        <li>
                            <a href="#"><i class="fa fa-calendar"></i> Tiempo Trabajado<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            ';
                if (permiso_usuario($linkMenu, 'TODO', 'bolsa_errores.php', $_SESSION['user_session_const']))
                {
                                    $barra.='
                                <li>
                                    <a href="bolsa_errores.php">Bolsa Errores</a> 
                                </li> ';
                }
                            $barra.='                               
                                <li>
                                    <a href="cargar_asistencia_lotes.php">Cargar Fichadas en Lote</a> 
                                </li>
                                <li>
                                    <a href="control_nrt.php">Registrar Fichadas NRT</a> 
                                </li>';
                if (permiso_usuario($linkMenu, 'TODO', 'cargar_sustitucion.php', $_SESSION['user_session_const']))
                {                                                
                            $barra.='<li>
                                    <a href="cargar_sustitucion.php">Cargar Sustituciones</a> 
                                </li>';
                }                
                if (permiso_usuario($linkMenu, 'CERRAR', 'periodos_nom.php', $_SESSION['user_session_const']))
                {
                                        $barra.='<li>
                                            <a href="periodos_nom.php">Periodos</a> 
                                                    </li>';
                }
                            $barra.='</ul>                           
                        </li>                                                
                        <li>
                            <a href="#"><i class="fa fa-plane"></i> Vacaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consulta_trabajadores_vacaciones.php">Consultar</a> 
                                </li>
                                <li>
                                    <a href="insertvacation.php">Cargar</a>
                                </li>
                                <li>
                                    <a href="updatevacation.php">Modificar</a> 
                                </li>                                
                                ';
                if (permiso_usuario($linkMenu, 'TODO', 'excepciones.php', $_SESSION['user_session_const']))
                {
                                        $barra.='<li>
                                            <a href="excepciones.php">Excepciones</a> 
                                                    </li>';
                }
                            $barra.='</ul>                           
                        </li>                                                
                        ';
                if (permiso_usuario($linkMenu, 'TODO', 'suspenciones', $_SESSION['user_session_const']))
                {
                             $barra.='<li>
                                        <a href="#"><i class="fa fa-building"></i> Med. Disciplinaria<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                        <li>
                                             <a href="consultar_SD.php">Consultar</a>
                                        </li>
                                        <li>
                                             <a href="insertSD.php">Cargar</a>
                                        </li>
                                        
                            </ul>                           
                            </li>';
                } 
                
                if (permiso_usuario($linkMenu, 'TODO', 'configuracion', $_SESSION['user_session_const']))
                {
                             $barra.='<li>
                                        <a href="#"><i class="fa fa-wrench"></i> Configuracion<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                        <li>
                                             <a href="trabajadores_supervisados.php">Trabajadores Supervisados</a>
                                        </li>
                                        <li>
                                             <a href="trabajadores_casosexcepcionales.php">Trabajadores situación especial</a>
                                        </li>
                                        
                            </ul>                           
                            </li>';
                }                                
                    $barra.='</ul>
                </div>
            </div>';
        break;
    case 2:
        $barra.='<div class="navbar-'.$_SESSION['modeBlack_const'].' sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
			<li>
                            <a href="#"><i class="fa fa-credit-card"></i> Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">';
                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])) ){
                                $barra.='<li>
                                    <a href="fichadas.php">Cargar ST y DLT</a> 
                                </li>';
                }                            
                if ((permiso_usuario($linkMenu, 'CONSULTAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {    
                                $barra.='<li>
                                    <a href="consultar_stdlt.php">Consultar ST y DLT</a> 
                                </li>';
                } 
                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='
                                <li>
                                    <a href="imprimir_planilla_stdlt.php">Imprimir Planilla</a> 
                                </li>';
                }  
                if ((permiso_usuario($linkMenu, 'REALIZAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                {
                                $barra.='<li>
                                    <a href="cargar_movimiento.php">Movimiento Temporales</a> 
                                </li>
                                <li>
                                    <a href="cargar_movimiento_indeterminado.php">Movimiento Permanente</a> 
                                </li>';
                }                                  
                /*if ((permiso_usuario($linkMenu, 'REALIZAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                {
                                $barra.='<li>
                                    <a href="cambio_turno.php">Cambio de Esperanza</a> 
                                </li>';
                }*/
                if ((permiso_usuario($linkMenu, 'AUTORIZAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="autorizar_stdlt.php">Autorizar ST y DLT</a> 
                                </li>';
                }
                if (permiso_usuario($linkMenu, 'VERIFICAR', 'DLT', $_SESSION['user_session_const'])){
                                $barra.='<li>
                                    <a href="verificar_stdlt.php">Verificar ST Y DLT</a> 
                                </li>';
                }         
                if ((permiso_usuario($linkMenu, 'IMPRIMIR', 'planilla_stdlt_inspectoria', $_SESSION['user_session_const'])) )
                {
                                $barra.='<li>
                                    <a href="imprimir_planilla_stdlt_inspectoria.php">STDLT Inspectoria</a> 
                                </li>';
                }                                           
                               $barra.='                                
                            </ul>                           
                        </li>';
                if (permiso_usuario($linkMenu, 'MENU', 'menu_permiso', $_SESSION['user_session_const']))
                {
                                        $barra.='                                               
                        <li>
                        
                            <a href="#"><i class="fa fa-shield"></i> Permisos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consultar_permisos.php">Consultar</a>
                                </li>
                                ';
                                if (permiso_usuario($linkMenu, 'CARGAR_PERM', 'cargar_permiso.php', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                             <a href="cargar_permiso.php">Cargar</a>
                                        </li>';
                                }
                            $barra.='                                
                                <li>
                                    <a href="hist_permisos.php">Historico</a>
                                </li>
                            </ul>                           
                        </li> 
                        ';
                }
                if (permiso_usuario($linkMenu, 'CARGAR', 'insertvacation.php', $_SESSION['user_session_const'])){    
                        $barra.='<li>
                            <a href="#"><i class="fa fa-plane"></i> Vacaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consulta_trabajadores_vacaciones.php">Consultar</a> 
                                </li>
                                <li>
                                    <a href="insertvacation.php">Cargar</a>
                                </li>
                                <li>
                                    <a href="updatevacation.php">Modificar</a> 
                                </li>                                
                                
                            </ul>                           
                        </li> ';        
                }
                if (permiso_usuario($linkMenu, 'NOUPDATE', 'insertvacation.php', $_SESSION['user_session_const'])){    
                        $barra.='<li>
                            <a href="#"><i class="fa fa-plane"></i> Vacaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consulta_trabajadores_vacaciones.php">Consultar</a> 
                                </li>
                                <li>
                                    <a href="insertvacation.php">Cargar</a>
                                </li>
                            </ul>                           
                        </li> ';        
                }
                if (permiso_usuario($linkMenu, 'TODO', 'suspenciones', $_SESSION['user_session_const'])){
                             $barra.='<li>
                                        <a href="#"><i class="fa fa-building"></i> Med. Disciplinaria<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                        <li>
                                             <a href="consultar_SD.php">Consultar</a>
                                        </li>
                                        <li>
                                             <a href="insertSD.php">Cargar</a>
                                        </li>
                                               
                            </ul>                           
                            </li>';
                } 
                if (permiso_usuario($linkMenu, 'MENU', 'menu_rrhh', $_SESSION['user_session_const']))
                {
                                        $barra.='                                               
                        <li>
                        
                            <a href="#"><i class="fa fa-sitemap"></i> RRHH<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                ';
                                if (permiso_usuario($linkMenu, 'MENU', 'menu_rrhh', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                             <a href="consulta_trabajadores_familiares.php">Listado Familiares</a>
                                        </li>';
                                }
                                if (permiso_usuario($linkMenu, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                            <a href="historico_trabajador.php">Historico de Trabajador</a>
                                        </li>';
                                }
                            $barra.='                                
                            </ul>                           
                        </li> 
                        ';
                }       
                        $barra.='                                               
                    </ul>
                </div>
            </div>';
        break;   

    case 3:
        $barra.='<div class="navbar-'.$_SESSION['modeBlack_const'].' sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
			<li>
                            <a href="#"><i class="fa fa-credit-card"></i> Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">';
                                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const']))){
                                $barra.='<li>
                                    <a href="fichadas.php">Cargar ST y DLT</a> 
                                </li>';
                                }
                                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const']))){
                               $barra.='<li>
                                    <a href="consultar_stdlt.php">Consultar ST y DLT</a> 
                                </li>';
                                }
                                if ((permiso_usuario($linkMenu, 'CARGAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                                {
                                $barra.='
                                <li>
                                    <a href="imprimir_planilla_stdlt.php">Imprimir Planilla</a> 
                                </li>';
                                 }                                 
                                $barra.='<li>
                                    <a href="fichada_ccure.php">Fichada CCURE</a>
                                </li>';
                                if ((permiso_usuario($linkMenu, 'REALIZAR', 'cambio_turno', $_SESSION['user_session_const'])) )
                                {
                                $barra.='<li>
                                    <a href="cargar_movimiento.php">Movimiento Temporales</a> 
                                </li>
                                <li>
                                    <a href="cargar_movimiento_indeterminado.php">Movimiento Permanente</a> 
                                </li>';
                                }                                 
                if ((permiso_usuario($linkMenu, 'AUTORIZAR', 'STDLT', $_SESSION['user_session_const'])) || (permiso_usuario($linkMenu, 'TODO', 'STDLT', $_SESSION['user_session_const'])))
                {
                                $barra.='<li>
                                    <a href="autorizar_stdlt.php">Autorizar ST y DLT</a> 
                                </li>';
                }
                                if (permiso_usuario($linkMenu, 'VERIFICAR', 'DLT', $_SESSION['user_session_const'])){
                                $barra.='<li>
                                    <a href="verificar_stdlt.php">Verificar ST Y DLT</a> 
                                </li>';
                                } 
                                if ((permiso_usuario($linkMenu, 'IMPRIMIR', 'planilla_stdlt_inspectoria', $_SESSION['user_session_const'])) )
                                {
                                $barra.='<li>
                                    <a href="imprimir_planilla_stdlt_inspectoria.php">STDLT Inspectoria</a> 
                                </li>';
                               }                               
                               $barra.='                                
                            </ul>                           
                        </li>';
                                if (permiso_usuario($linkMenu, 'MENU', 'menu_permiso', $_SESSION['user_session_const'])){
                                        $barra.='                                               
                        <li>
                        
                            <a href="#"><i class="fa fa-shield"></i> Permisos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consultar_permisos.php">Consultar</a>
                                </li>
                                ';
                                if (permiso_usuario($linkMenu, 'CARGAR_PERM', 'cargar_permiso.php', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                             <a href="cargar_permiso.php">Cargar</a>
                                        </li>';
                                }
                            $barra.='                                
                                <li>
                                    <a href="hist_permisos.php">Historico</a>
                                </li>
                            </ul>                           
                        </li> 
                        ';
                                }
                                if (permiso_usuario($linkMenu, 'MENU', 'menu_rrhh', $_SESSION['user_session_const']))
                {
                                        $barra.='                                               
                        <li>
                        
                            <a href="#"><i class="fa fa-sitemap"></i> RRHH<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                ';
                                if (permiso_usuario($linkMenu, 'MENU', 'menu_rrhh', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                             <a href="consulta_trabajadores_familiares.php">Listado Familiares</a>
                                        </li>';
                                }
                                if (permiso_usuario($linkMenu, 'TODO', 'historico_trabajador.php', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                            <a href="historico_trabajador.php">Historico de Trabajador</a>
                                        </li>';
                                }
                            $barra.='                                
                            </ul>                           
                        </li> 
                        ';
                } 
                            $barra.='                                               
                    </ul>
                </div>
            </div>';
        break; 
} 
//pg_close($linkMenu);            
return $barra;            
}            
?>
