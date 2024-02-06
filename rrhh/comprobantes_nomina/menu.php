<?php

function menu()
{
    $barra="";
	switch ($_SESSION['nivel']) {
    case 1:
        $barra= "<nav>
            <ul>
                <li><a href='envio_comp.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Impuestos
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='#'>A.R.C.</a></li>
                        <li><a href='#'>A.R.I.</a></li>                         
                    </ul>
                </li>                 
                <li><a href='#'>Envio de Correo<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                       
                        <li><a href='envio_clave.php'>Enviar Clave Comprobante</a></li>
                        <li><a href='envio_archivo.php'>Enviar Archivo</a></li> 
                    </ul>
                </li>
                 <li><a href='#'>".$_SESSION['username']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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
                <li><a href='envio_comp.php' title='Inicio'>Inicio</a></li>                
                 <li><a href='#'>".$_SESSION['username']."<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
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