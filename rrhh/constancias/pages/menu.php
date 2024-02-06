<?php 
function barra_menu(){
$barra='';
if (isset($_SESSION['user_session_conslab']) && isset($_SESSION['nivel_conslab']))
switch ($_SESSION['nivel_conslab']) {
    case 1:
        $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Constacias<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="nueva_constancia.php">Nueva</a>
                                </li>
                                <li>
                                    <a href="consulta_emisiones.php">Consultar</a>
                                </li>
                                
                            </ul>                           
                        </li>                                                
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
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Constacias<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="nueva_constancia.php">Nueva</a>
                                </li>
                                <li>
                                    <a href="consulta_emisiones.php">Consultar</a>
                                </li>
                                
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