<?php
//print $_SESSION['user_session_const'].'-'.$_SESSION['nivel_const'];
 
function barra_menu(){
    require_once("../BD/conexion.php");
    require_once('funciones_var.php');       
    $link=Conex_Contancia_pgsql();
$barra='';
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const']))
switch ($_SESSION['nivel_const']) {
    //case ($_SESSION['nivel_const']==1 || $_SESSION['nivel_const'] ==2):
    case ($_SESSION['nivel_const']==1 ):
        $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
 <!--                            <li>
                                    <a href="registrar_fichada.php">Agregar fichada</a>
                                </li>

                                <li>
                                    <a href="consultar_fichadas.php">Consultar fichada cargada</a> 
                                </li>
-->
                                <li>
                                    <a href="cargar_horas_extras.php">Cargar Tiem. Extr.</a> 
                                </li>
                                <li>
                                    <a href="consultar_te.php">Consultar Tiem. Extr.</a> 
                                </li>
                                <li>
                                    <a href="cambio_turno.php">Cambio de Turno</a> 
                                </li>
                                <li>
                                    <a href="consultar_ct.php">Consultar cambio turno</a> 
                                </li>
                                <li>
                                    <a href="cambio_permanencia.php">Cambio de Permanencia</a> 
                                </li>
                                <li>
                                    <a href="consultar_cp.php">Consultar cambio permanencia</a> 
                                </li>
                                <li>
                                    <a href="fichada_ccure.php">Fichada CCURE</a>
                                </li>';
			  	if (permiso_usuario($link, 'AUTORIZAR', 'autorizar_ct.php', $_SESSION['user_session_const'])){
				$barra.='<li>
                                    <a href="autorizar_ct.php">Autorizar Cambio de Turno</a> 
                                </li>
				<li>
                                    <a href="autorizar_te.php">Autorizar Horas Extras</a> 
                                </li>';
				}
                               $barra.='                                
                            </ul>                           
                        </li>';
                                if (permiso_usuario($link, 'AUTORIZAR', 'autorizar_ct.php', $_SESSION['user_session_const'])){
                                $barra.='                                                
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Permisos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consultar_permisos.php">Consultar</a>
                                </li>
                                <li>
                                    <a href="cargar_permiso.php">Cargar</a>
                                </li>
                                <li>
                                    <a href="hist_permisos.php">Historico</a>
                                </li>
                            </ul>                           
                        </li>';
                                }
                               $barra.='
                                    
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>N&oacute;mina<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                
                                <li>
                                    <a href="cargar_asistencia_lotes.php">Cargar Fichadas en Lote</a> 
                                </li>
                                <li>
                                    <a href="control_nrt.php">Registrar Fichadas NRT</a> 
                                </li>
                                <li>
                                    <a href="updatevacation.php">Modificar Vacaciones</a> 
                                </li>
                                <li>
                                    <a href="cargar_sustitucion.php">Cargar Sustituciones</a> 
                                </li>
                                ';
                                if (permiso_usuario($link, 'CERRAR', 'periodos_nom.php', $_SESSION['user_session_const'])){
                                        $barra.='<li>
                                            <a href="periodos_nom.php">Periodos</a> 
                                                    </li>';
                                }
                            $barra.='</ul>                           
                        </li>                                                
<!--                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Evaluaci&oacute;n<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="registrar_evaluacion.php">Nueva</a>
                                </li>
                                <li>
                                    <a href="index_evaluaciones.php">Consultar</a> 
                                </li>
                                
                            </ul>                           
                        </li>                                                
-->
                    </ul>
                </div>
            </div>';
        break;
    case 2:
        $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
                                                        
<!--                    </ul>                           
                        </li>
-->
			<li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
 <!--                            <li>
                                    <a href="registrar_fichada.php">Agregar fichada</a>
                                </li>

                                <li>
                                    <a href="consultar_fichadas.php">Consultar fichada cargada</a> 
                                </li>
-->
                                <li>
                                    <a href="cargar_horas_extras.php">Cargar Tiem. Extr.</a> 
                                </li>
                                <li>
                                    <a href="consultar_te.php">Consultar Tiem. Extr.</a> 
                                </li>
                                <li>
                                    <a href="cambio_turno.php">Cambio de Turno</a> 
                                </li>
                                <li>
                                    <a href="consultar_ct.php">Consultar cambio turno</a> 
                                </li>
                                <li>
                                    <a href="cambio_permanencia.php">Cambio de Permanencia</a> 
                                </li>
                                <li>
                                    <a href="consultar_cp.php">Consultar cambio permanencia</a> 
                                </li>';
                                if (permiso_usuario($link, 'AUTORIZAR', 'autorizar_ct.php', $_SESSION['user_session_const'])){
                                $barra.=' <li>
                                    <a href="autorizar_te.php">Autorizar Horas Extras</a> 
                                </li>
				<li>
                                    <a href="autorizar_ct.php">Autorizar Cambio de Turno</a> 
                                </li>';
                                }
                               $barra.='                                
                            </ul>                           
                        </li>';
                                if (permiso_usuario($link, 'MENU', 'menu_permiso', $_SESSION['user_session_const'])){
                                        $barra.='                                               
                        <li>
                        
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Permisos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="consultar_permisos.php">Consultar</a>
                                </li>
                                ';
                                if (permiso_usuario($link, 'CARGAR_PERM', 'cargar_permiso.php', $_SESSION['user_session_const'])){
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
                            $barra.='                                               
                                                                       
<!--                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Evaluaci&oacute;n<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="registrar_evaluacion.php">Nueva</a>
                                </li>

                                <li>
                                    <a href="index_evaluaciones.php">Consultar</a> 
                                </li>
                                
                            </ul>                           
                        </li>                                                
-->
                    </ul>
                </div>
            </div>';
        break;   

    case 3:
        $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Fichada<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="registrar_fichada.php">Nueva</a>
                                </li>
                                <li>
                                    <a href="consultar_fichadas.php">Consultar</a> 
                                </li>
                                <li>
                                    <a href="cargar_horas_extras.php">Cargar Tiem. Extr.</a> 
                                </li>
                                <li>
                                    <a href="fichada_ccure.php">Consultar CCURE</a> 
                                </li>
                                <li>
                                    <a href="consulta_porcentaje_asistencia.php">Reporte Asistencias</a> 
                                </li>
                            </ul>                           
                        </li>                                                
<!--                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Evaluaci&oacute;n<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="registrar_evaluacion.php">Nueva</a>
                                </li>
                                <li>
                                    <a href="index_evaluaciones.php">Consultar</a> 
                                </li>
-->                                
                            </ul>                           
                        </li>                                                
                    </ul>
                </div>
            </div>';
        break;
} 
            
return $barra;            
}            
?>
