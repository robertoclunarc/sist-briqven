<?php

function menu()
{
    $barra="";
	switch ($_SESSION['nivel']) {
    case 0:
        $barra= "<nav>
            <ul>
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>                 
            </ul>
        </nav>";
        break;
    case 1:
        $barra= "<nav>
            <ul>
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Consultas
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='consulta_nueva.php'>Nueva Consulta</a></li>
                        <li><a href='listar_consultas_20.php'>Listar Consultas Med.</a></li> 
                        <li><a href='listar_pacientes_exam.php'>Registrar Examenes</a></li>
                        <li><a href='preempleos.php'>Listar Pre-Empleados</a></li>
                        <li><a href='examenes.php'>Listar Examenes</a></li>
                        
                    </ul>
                </li>
                <li><a href='#'>Historia Medica
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='historia_nueva.php'>Agregar Historia</a></li>
                        <li><a href='consultar_historias.php'>Consultar Historias</a></li>
                        <li><a href='#'>Tablas Maestras 
                            <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                            </a>
                            <ul>
                                <li><a href='ing_patalogia_fam.php'>Antecedentes Famil. / Patoligia</a></li>
                                <li ><a href='ing_riesgo.php'>Riesgos</a></li>
                                <li ><a href='ing_exam_fisico.php'>Estudios Fisicos</a></li>
                                <li ><a href='enconstruccion.html'>Funcionales</a></li>
                                <li ><a href='enconstruccion.html'>Habitos</a></li>
                                <li ><a href='enconstruccion.html'>Anamnesis Psicologicos</a></li>
                                
                            </ul>
                        </li>
                    
                    </ul>
                </li>
                <li><a href='#'>Pacientes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        
                        
                        <li><a href='listar_pacientes.php'>Listar Pacientes</a></li>
                    </ul>
                </li>
                <li><a href='#'>Personal M&eacute;dico<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                       
                        <li><a href='medico_nuevo.php'>Nuevo M&eacute;dico</a></li>
                        <li><a href='listar_medicos.php'>Listar M&eacute;dicos</a></li>                                               
                        <li><a href='paramedico_nuevo.php'>Nuevo Para-M&eacute;dico</a></li>
                        <li><a href='listar_paramedicos.php'>Listar Para-M&eacute;dicos</a></li>                       
                                               
                    </ul>
                </li>
                <li><a href='#'>Medicamentos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='ing_medicina.php'>Nuevo Medicamento</a>
                        <li><a href='medicamentos.php'>Listar Medicamentos</a>
                        <li><a href='inv_carga.php'>Carga</a>
                        <li><a href='inv_descarga.php'>Descarga</a>
                         <li><a href='movimientos_inv.php'>Movimientos Inv.</a>
                    </ul>
                </li>

                <li><a href='#'>Reportes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='morbilidad.php'>Morbilidad</a>
                        <li><a href='enconstruccion.html'>Consultas por Motivo</a>
                        <li><a href='enconstruccion.html'>Consultas por Area de Incidente</a>
                        <li><a href='enconstruccion.html'>Consulta por Departamento</a>
                        
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
    case 2:
        $barra= "<nav>
            <ul>
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Consultas
                <i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i>
                </a>
                    <ul>
                        <li><a href='consulta_nueva.php'>Nueva Consulta</a></li>
                         
                    </ul>
                </li>
                
                <li><a href='#'>Pacientes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        
                        
                        <li><a href='listar_pacientes.php'>Listar Pacientes</a></li>
                    </ul>
                </li>
                <li><a href='#'>Personal M&eacute;dico<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                       
                        
                        <li><a href='listar_medicos.php'>Listar M&eacute;dicos</a></li>                                               
                        
                        <li><a href='listar_paramedicos.php'>Listar Para-M&eacute;dicos</a></li>                       
                                               
                    </ul>
                </li> 

                <li><a href='#'>Reportes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='morbilidad.php'>Morbilidad</a>                         
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
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>                
                
                <li><a href='#'>Pacientes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='listar_pacientes.php'>Listar Pacientes</a></li>
                    </ul>
                </li>
                <li><a href='#'>Personal M&eacute;dico<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                       
                        <li><a href='medico_nuevo.php'>Nuevo M&eacute;dico</a></li>
                        <li><a href='listar_medicos.php'>Listar M&eacute;dicos</a></li>                                               
                        <li><a href='paramedico_nuevo.php'>Nuevo Para-M&eacute;dico</a></li>
                        <li><a href='listar_paramedicos.php'>Listar Para-M&eacute;dicos</a></li>    
                                               
                    </ul>
                </li>
                <li><a href='#'>Medicamentos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='ing_medicina.php'>Nuevo Medicamento</a>
                        <li><a href='medicamentos.php'>Listar Medicamentos</a>
                        <li><a href='inv_carga.php'>Carga</a>
                        <li><a href='inv_descarga.php'>Descarga</a>
                         <li><a href='movimientos_inv.php'>Movimientos Inv.</a>
                    </ul>
                </li>

                <li><a href='#'>Reportes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='morbilidad.php'>Morbilidad</a>
                        <li><a href='enconstruccion.html'>Consultas por Motivo</a>
                        <li><a href='enconstruccion.html'>Consultas por Area de Incidente</a>
                        <li><a href='enconstruccion.html'>Consulta por Departamento</a>
                                                
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

        case 4:
        $barra= "<nav>
            <ul>
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>                
                
                <li><a href='#'>Pacientes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='listar_pacientes.php'>Listar Pacientes</a></li>
                    </ul>
                </li>
                <li><a href='#'>Personal M&eacute;dico<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                       
                        <li><a href='medico_nuevo.php'>Nuevo M&eacute;dico</a></li>
                        <li><a href='listar_medicos.php'>Listar M&eacute;dicos</a></li>                                               
                        <li><a href='paramedico_nuevo.php'>Nuevo Para-M&eacute;dico</a></li>
                        <li><a href='listar_paramedicos.php'>Listar Para-M&eacute;dicos</a></li>    
                                               
                    </ul>
                </li>
                <li><a href='#'>Medicamentos<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='ing_medicina.php'>Nuevo Medicamento</a>
                        <li><a href='medicamentos.php'>Listar Medicamentos</a>
                        <li><a href='inv_carga.php'>Carga</a>
                        <li><a href='inv_descarga.php'>Descarga</a>
                         <li><a href='movimientos_inv.php'>Movimientos Inv.</a>
                    </ul>
                </li>

                <li><a href='#'>Reportes<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>
                        <li><a href='morbilidad.php'>Morbilidad</a>
                        <li><a href='enconstruccion.html'>Consultas por Motivo</a>
                        <li><a href='enconstruccion.html'>Consultas por Area de Incidente</a>
                        <li><a href='enconstruccion.html'>Consulta por Departamento</a>
                                  
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
        case 5:
        $barra= "<nav>
            <ul>                              
                <li><a href='inicio.php' title='Inicio'>Inicio</a></li>
                <li><a href='#'>Pre-Empleo<i><img src='images/flecha.png' WIDTH='20' HEIGHT='20'></i></a>
                    <ul>                        
                        <li><a href='paciente_nuevo.php'>Ingresar</a></li>
                        <li><a href='preempleos.php'>Listar Empleados</a></li>
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
}
return $barra;
    
}

?>