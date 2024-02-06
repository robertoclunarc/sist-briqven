<?php

function menu()
{
    $barra="";
	switch ($_SESSION['nivel_ca']) {
    case 1:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Movimientos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>                        
                        <li><a href='movi_equipos.php'>Listar Movimientos</a></li>
                    </ul>
                </li>                
                <li><a href='#'>Personal Protec. Patrimonial<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='#'>Listar Personal</a></li>
                    </ul>
                </li>
                <li><a href='#'>Firmas Autorizadas<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                       
                        <li><a href='#'>Agregar Autorizados</a></li>
                        <li><a href='#'>Consultar Autorizados</a></li>
                    </ul>
                </li>
                <li><a href='#'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        
                        <li><a href='#'>Ver Estatus de Acciones</a>
                        
                    </ul>
                </li>
                
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
    case 2:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>

                <li><a href='#'>Movimientos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        
                        <li><a href='movi_equipos.php'>Listar Movimientos</a></li>
                        
                    </ul>
                </li>                
                <li><a href='#'>Firmas Autorizadas<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Consultar Autorizados</a></li>
                    </ul>
                </li>
                <li><a href='#'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Ver Estatus de Acciones</a>                        
                    </ul>
                </li>                
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
    case 3:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>                
                <li><a href='#'>Movimientos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='registro_movimiento.php'>Entrada/Salida</a></li>
                        <li><a href='movi_equipos.php'>Listar Movimientos</a></li>
                    </ul>
                </li>                
                <li><a href='#'>Firmas Autorizadas<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Consultar Autorizados</a></li>
                    </ul>
                </li>";
                if ($_SESSION['permisoadic_ca']=='Acceso Personal'){
                    $barra.="<li><a href='#'>Acceso Personal
                    <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                    </a>
                        <ul>
                            <li><a href='accesopersonalpropio.php'>Propio</a></li> 
                            <li><a href='#'>Foraneo</a></li>
                            <li><a href='acceso_perpro.php'>Consulta Accesos</a></li>
                            <li><a href='acceso_online.php'>Consulta Fichadas</a></li>
                        </ul>
                    </li>";
                }
                $barra.="<li><a href='#'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Ver Estatus de Acciones</a>                        
                    </ul>
                </li>                
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
         case 4:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Movimientos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='movi_equipos.php'>Listar Movimientos</a></li>
                    </ul>
                </li>
                <li><a href='#'>Acceso Personal
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='accesopersonalpropio.php'>Propio</a></li> 
                        <li><a href='accesopersonalforaneo.php'>Foraneo</a></li>
                        <li><a href='acceso_perpro.php'>Consulta Accesos</a></li>
                        <li><a href='acceso_online.php'>Consulta Fichadas</a></li>
                    </ul>
                </li>
                               
                <li><a href='#'>Firmas Autorizadas<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Consultar Autorizados</a></li>
                    </ul>
                </li>
                <li><a href='#'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Ver Estatus de Acciones</a>                        
                    </ul>
                </li>                
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
        case 5:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Movimientos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='registro_movimiento.php'>Entrada/Salida</a></li>
                        <li><a href='movi_equipos.php'>Listar Movimientos</a></li>                        
                    </ul>
                </li>
                <li><a href='#'>Firmas Autorizadas<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Consultar Autorizados</a></li>
                    </ul>
                </li>
                <li><a href='#'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='#'>Ver Estatus de Acciones</a>                        
                    </ul>
                </li>                
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
        case 6:
        $barra= "<nav>
            <ul>
                
                <li><a href='#'>Acceso Personal
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='acceso_perpro.php'>Consulta Accesos</a></li>
                        <li><a href='acceso_online.php'>Consulta Fichadas</a></li>
                    </ul>
                </li>              
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
        case 7:
        $barra= "<nav>
            <ul>
                <li><a href='movi_equipos.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Consultas
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='consulta_movimientos.php'>Busqueda Avanz. Mov</a></li>
                        <li><a href='index.php'>Listar Movimientos</a></li>
                    </ul>
                </li>              
               <li><a href='#'>".$_SESSION['username_ca']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='login/logout.php'>Cerrar Sesion</a>                                                
                    </ul>
                </li>
            </ul>
        </nav>";
        break;
}
return $barra;
    
}

?>