<?php
function menu()
{
    $barra="";
	switch ($_SESSION['nivel_sio']) {
    case 1:
        $barra= "<nav>
            <ul>
                <li><a href='index.php' title='Inicio'>Inicio</a></li>
                <li>
			<a href='#'>Registrar<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='prd_real_plan.php'>Produc. - Invent. - Despacho</a></li>
                        <li><a href='carga_mensual.php'>Programacion mensual</a></li>
                    </ul>
                </li>                
                <li><a href='enviar_archivos.php'>Enviar Datos</a></li>
<!--                <li><a href='enviar_datos.php'>Historial de Accesos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='#'>Ver Estatus de Acciones</a>
                    </ul>
-->
                </li>

               <li><a href='#'>".$_SESSION['username_sio']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
                        
                        <li><a href='movi_equipos.php'>Listar Movimientos x Equipos</a></li>
                        
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
               <li><a href='#'>".$_SESSION['username_sio']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
                        <li><a href='movi_equipos.php'>Listar Movimientos x Equipos</a></li>
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
               <li><a href='#'>".$_SESSION['username_sio']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
                        <li><a href='movi_equipos.php'>Listar Movimientos x Equipos</a></li>
                    </ul>
                </li>
                <li><a href='#'>Acceso Personal
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='accesopersonalpropio.php'>Propio</a></li> 
                        <li><a href='#'>Foraneo</a></li>
                        <li><a href='acceso_perpro.php'>Consulta</a></li>
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
               <li><a href='#'>".$_SESSION['username_sio']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
                        <li><a href='movi_equipos.php'>Listar Movimientos x Equipos</a></li>                        
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
               <li><a href='#'>".$_SESSION['username_sio']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
