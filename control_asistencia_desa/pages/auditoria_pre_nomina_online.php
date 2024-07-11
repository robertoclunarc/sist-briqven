<?php
 session_start();
 if (isset($_SESSION['user_session_const'])){
    require_once('funciones_var.php');
    require_once("../BD/conexion.php");
    $finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
    $ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
    $trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $tipoauditoria= isset($_POST["tipoauditoria"])?$_POST["tipoauditoria"]:"NULL";
    $link001=Conex_Contancia_pgsql();
    
    $acceso=permiso_usuario($link001, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);
    pg_close($link001);

    //print ($tipoauditoria);
    switch ($tipoauditoria) {
    case 1:
        $titulo="M&aacute;s de 3 dias de descanso por semana";
        $qry= "select a.cedula, c.nombre,c.apellidos ,b.periodo, a.HorasNetSicaSt, count(*) cantidad from sw_hoja_de_tiempo_real a, adam_calendario_nomina b, adam_datos_personales c where a.cedula = c.trabajador and b.tipo_nomina = 'MS' and a.fecha between b.fecha_inicio and b.fecha_termino and fecha between '".$finicio."' AND '".$ffin."' and cedula in (select distinct trabajador from adam_datos_personales where clase_nomina = 'ME' and turno != 9) and entrada_esperada1 = 'LL:LL' GROUP BY a.cedula, c.nombre, c.apellidos ,b.periodo, a.HorasNetSicaSt HAVING count(*)>2 ORDER BY 5 desc";
        break;
    case 2:
        $titulo="Trabajadores disfrutando vacaciones con esperanza diferente a VV:VV";
        $qry= "select a.cedula, b.nombres, a.fecha, b.turno, b.sistema_horario, DESC_PUESTO, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, a.coderror,cod_ausencia,horasnetapresencia, horasnetaausencia, b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral, a.HorasNetSicaSt from sw_hoja_de_tiempo_real a, adam_datos_personales b, adam_programacion_vacaciones d where a.cedula = b.trabajador and b.clase_nomina = 'ME' and fecha between '".$finicio."' AND '".$ffin."' and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') and a.cedula = d.trabajador and a.fecha between d.fecha_ini_per_vac and d.fecha_fin_per_vac order by cedula, fecha";
        break;
    case 3:
        $titulo="solapamiento de permisos";
        $qry = "select a.cedula, b.nombres,b.turno, b.sistema_horario, DESC_PUESTO, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1,a.entrada_esperada2, a.salida_esperada2, a.coderror,cod_ausencia, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral  from sw_hoja_de_tiempo_real a, adam_datos_personales b where a.cedula = b.trabajador and  exists (select 1 from sw_permisos  where cedula = a.cedula and a.fecha between inicio and fin) and entrada_esperada1 in ('LL:LL','FF:FF','VV:VV','RR:RR','SD:SD') order by cedula, fecha";
        break;
    case 4:
        $titulo="C&oacute;digo de errores";
        $qry = "select coderror, count(*) from sw_hoja_de_tiempo_real where fecha between '".$finicio."' AND '".$ffin."' and cedula in (select trabajador from adam_datos_personales where clase_nomina = 'ME' and turno != 9) group by coderror order by coderror";
        break;
    case 5:
        $titulo="Trabajadores con c&oacute;digo error 12";
        $qry = "select a.cedula, b.nombres, b.turno, b.sistema_horario, desc_puesto,centro_costo, desc_ccosto,  a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2,   a.salida_esperada2, cod_ausencia, coderror, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.gergral from sw_hoja_de_tiempo_real a, adam_datos_personales b where  fecha between '".$finicio."' AND '".$ffin."' and b.trabajador=a.cedula and b.clase_nomina = 'ME' and  b.turno != 9 and coderror=12 order by 1,8";
        break;
    case 6:
        $titulo="Trabajadores con entrada o salida NULL";
        $qry = "select a.cedula, b.nombres, b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, DESC_CCOSTO, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_real3, a.salida_real3, a.entrada_esperada1, a.salida_esperada1,a.entrada_esperada2, a.salida_esperada2, a.coderror,cod_ausencia, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral from sw_hoja_de_tiempo_real a, adam_datos_personales b where  fecha between '".$finicio."' AND '".$ffin."' and cedula = trabajador and b.clase_nomina = 'ME' and b.turno != 9  and cod_ausencia != 72 and a.entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') and ((a.salida_real1 is null and a.entrada_real1 is not null) or  (a.salida_real1 is  not null and a.entrada_real1 is null)) order by cedula , fecha";
        break;
    case 7:
        $titulo="Entrada reales diferentes a entradaes esperadas";
        $qry = "select a.cedula,  b.nombres, a.fecha, b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, DESC_CCOSTO, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1,  DATEDIFF(minute,CAST(a.entrada_real1 as DATETIME),CAST(a.entrada_esperada1 as DATETIME)) as tiempo, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, a.coderror,cod_ausencia, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral from sw_hoja_de_tiempo_real a, adam_datos_personales b where  fecha between '".$finicio."' AND '".$ffin."' and cedula = trabajador and b.clase_nomina = 'ME' and b.turno != 9 and coderror !=12 and cod_ausencia != 85 and a.entrada_real1 is not null and a.entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') and cedula not in (select distinct trabajador from adam_datos_personales where suptcia like '%Patrimonial%' and sistema_horario in (1,2,3,4,11,12)) and DATEDIFF(minute,CAST(a.entrada_real1 as DATETIME),CAST(a.entrada_esperada1 as DATETIME))>=60 order by 7 ";
        break;
    case 8:
        $titulo="Trabajadores con CodError=9 y esperanza de trabajo";
        $qry = "select a.cedula, b.nombres, b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, desc_ccosto, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1,a.entrada_esperada2, a.salida_esperada2,horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario, a.cod_ausencia, a.coderror,b.gergral from sw_hoja_de_tiempo_real a, adam_datos_personales b where a.cedula = b.trabajador and b.clase_nomina = 'ME' and fecha between '".$finicio."' AND '".$ffin."' and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') and entrada_real1 is not null and cod_ausencia  in (9) and b.turno != 9 order by cedula, fecha";
        break;
    case 9:
        $titulo="Trabajadores de vacaciones pero con CodError != 65";
        $qry = "select a.cedula, b.nombres,b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, DESC_CCOSTO, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario,cod_ausencia, b.centro_costo, b.desc_ccosto, b.gergral from sw_hoja_de_tiempo_real a, adam_datos_personales b where a.cedula = b.trabajador and b.clase_nomina = 'ME' and fecha between '".$finicio."' AND '".$ffin."' and entrada_esperada1 in ('VV:VV') and b.turno != 9 and cod_ausencia not in (65) order by cedula, fecha";
        break;
    case 10:
        $titulo="Horas presencias !=8, con c&oacute;digo de ausencia de pago";
        $qry = "select cedula, b.nombres, b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, desc_ccosto, fecha, entrada_real1, entrada_real2,  salida_real1, salida_real2, entrada_esperada1,  entrada_esperada2, salida_esperada1, salida_esperada2, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, coderror, cod_ausencia from sw_hoja_de_tiempo_real a, adam_datos_personales b where a.cedula = b.trabajador and fecha between '".$finicio."' AND '".$ffin."' and horasnetapresencia<>8 and cod_ausencia in (10, 20, 30, 40, 50,60,70,80) and entrada_esperada1 not in ('PP:PP') order by cod_ausencia";
        break;
    case 11:
        $titulo="Trabajadores en Comisi&oacute;n de servicio con esperanza2 = FF:FF";
        $qry = "select a.cedula, b.nombres, b.turno, b.sistema_horario, DESC_PUESTO,centro_costo, DESC_CCOSTO, a.fecha, a.entrada_real1, a.salida_real1, a.entrada_esperada1, a.salida_esperada1,a.entrada_esperada2, a.salida_esperada2,horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, b.sistema_horario, a.cod_ausencia, a.coderror from sw_hoja_de_tiempo_real a, adam_datos_personales b where a.cedula = b.trabajador and b.clase_nomina = 'ME' and fecha between '01-Apr-2021' AND '15-Apr-2021' and (entrada_esperada1 in ('CS:CS') and Salida_Esperada1 in ('CS:CS')) and entrada_esperada2 in ('VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF') and b.turno != 9 order by cedula, fecha";
        break;
    case 12:
        $titulo="Posible Sobre tiempo";
        $qry = "select a.cedula, b.nombres, b.turno, b.sistema_horario, 
        desc_puesto,centro_costo, desc_ccosto,  a.fecha, 
        a.entrada_real1, a.salida_real1,a.entrada_real2, 
        a.salida_real2, a.entrada_esperada1, a.salida_esperada1, 
        a.entrada_esperada2, a.salida_esperada2, cod_ausencia, 
        coderror, a.HorasNetSica as horasnetapresencia, horasnetaausencia, 
        b.gergral, a.Horas_ST, a.Inicio_ST1, a.Fin_St1, a.HorasNetSicaSt
        from sw_hoja_de_tiempo_real a, adam_datos_personales b 
        where  fecha between '".$finicio."' AND '".$ffin."' 
        and b.trabajador=a.cedula and b.clase_nomina = 'ME' 
        and  b.turno != 9 and coderror in (13,11) 
        and centro_costo!=61606 
        order by 1,8";
        break;
    case 13:
        $query0="SELECT cedula, TO_CHAR(fecha_desde, 'DD-MM-YYYY') as fecha_desde, fecha_hasta FROM public.personal_bloqueado p inner join v_trabajadores vt on p.cedula = vt.trabajador where (fecha_hasta is null or fecha_hasta>=$ffin) and status ='ACTIVO' order by nombres";

        $titulo="Total de Ausencias";
        $qry = "select  desc_ccosto, sum (horasnetaausencia) as horaausencia, count(*) as cantidadausencia
        from sw_hoja_de_tiempo_real a, adam_datos_personales b
        where a.cedula = b.trabajador
        and b.clase_nomina = 'ME'
        and fecha between '".$finicio."' AND '".$ffin."'
        and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF','SP:SP')
        and horasnetaausencia >= 1
        and cod_ausencia in (85, 88)
        and b.turno != 9
        group by b.desc_ccosto
        order by sum (horasnetaausencia) desc;";
        break;
    case 14:
        $titulo="Resumen de Ausencia";
        $qry = "select a.cedula, b.nombres, sum (horasnetaausencia) as horaausencia, count(*) as cantidadausencia,
        b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral
        from sw_hoja_de_tiempo_real a, adam_datos_personales b
        where a.cedula = b.trabajador
        and b.clase_nomina = 'ME'
        and fecha between '".$finicio."' AND '".$ffin."'
        and entrada_esperada1 not in ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF','SP:SP')
        and horasnetaausencia >=1
        and cod_ausencia in (85, 88) 
        and b.turno != 9
        group by a.cedula, b.nombres,b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral
        order by 4 desc,2 desc;";
        break;                
    case 15:
        $whereTrabajador=" ";
        if ($trabajador!="NULL"){
            
            $whereTrabajador=" AND a.cedula=".$trabajador." ";
        }
        else{
            $os = array(3, 5);
            if (in_array($_SESSION['nivel_const'], $os) || $acceso) { 
                $trabajador=implode(",", $_SESSION['arrayTrab']);
                $whereTrabajador=" AND a.cedula in (".$trabajador.") ";
            }    
        }
        $titulo="Detalle de Ausencia";
        $qry = "SELECT a.cedula, b.nombres, a.fecha, a.turno,a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1, a.entrada_esperada2, a.salida_esperada2, horasnetapresencia, horasnetaausencia, a.HorasNetSicaSt, cod_ausencia, b.sistema_horario, b.centro_costo, b.desc_ccosto, b.gergral
        FROM sw_hoja_de_tiempo_real a INNER JOIN adam_datos_personales b
        ON a.cedula = b.trabajador AND b.clase_nomina = 'ME' WHERE
         fecha BETWEEN '".$finicio."' AND '".$ffin."' ".$whereTrabajador."
        AND entrada_esperada1 NOT IN ('LL:LL','VV:VV','RR:RR','PP:PP','CS:CS','SD:SD','FF:FF','SP:SP')
        AND horasnetaausencia >= 1
        AND cod_ausencia  in (85, 88)
        AND b.turno != 9
        ORDER BY cedula, fecha DESC;";
        break;
    case 16:
        $titulo="Trabajadores con fichada real en dia feriado";
        $qry = "SELECT  a.cedula, b.NOMBRES ,a.fecha, a.entrada_real1, a.salida_real1, a.entrada_real2, a.salida_real2, a.salida_real2, a.entrada_esperada1, a.salida_esperada1, a.HorasNetSicaSt, a.horasnetapresencia, a.horasnetaausencia, b.centro_costo, b.desc_ccosto, b.gergral  from SW_Hoja_de_Tiempo_Real a
            inner join adam_datos_personales b on a.cedula =b.Trabajador 
            where a.fecha BETWEEN '".$finicio."' AND '".$ffin."'  
            and Entrada_Esperada1 = 'FF:FF' 
            and (Entrada_Real1 is not NULL or Salida_Real1 is not NULL) 
            order by a.cedula";
        break;          
    }

    buscar($qry,$tipoauditoria,$titulo);
}else{
    echo '<div class="alert alert-danger">DEBE INICIAR SESION</div>';
}        
       
function buscar($b,$tipoauditoria,$titulo) {
       
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);
        $contar = $stmt1->columnCount(); 
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table border="1" width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
              switch ($tipoauditoria) {

                case 1:
                    $inpt = $inpt.'<thead>
                    <tr>
                               <th colspan="5" style="text-align:center" class="info">'.$titulo.'</th> 
                            </tr>
                            <tr>
                               <th width="5%" class="info">Cedula</th> 
                               <th width="15%" class="info">Nombres</th>             
                               <th width="15%" class="info">Apellidos</th>             
                               <th width="10%" class="info">Periodo</th>  
                               <th width="5%"class="info">Cantidad<br>descanso</th>        
                            </tr>
                         </thead>
                        <tbody>';
                          while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                                $contar++;
                                $nombre="'".$row['nombre']."'";
                                $inpt .='<tr>';                                
                                $inpt .='<td><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';                    
                                $inpt .='<td>'.$row['nombre'].'</td>';
                                $inpt .='<td>'.$row['apellidos'].'</td>';
                                $inpt .='<td>'.$row['periodo'].'</td>';
                                $inpt .='<td>'.$row['cantidad'].'</td>';
                                $inpt .='</tr>';                        
                         } 
        
                break;
                case 13:
                    $TotalHorasAusencias=0;
                    $TotalCantidadAusencia=0;
                    $inpt = $inpt.'<thead>
                    <tr>
                               <th colspan="3" style="text-align:center" class="info">'.$titulo.'</th> 
                            </tr>
                            <tr>
                               <th width="40%" class="info">Centro de Costo</th> 
                               <th width="40%" class="info">Horas de Ausencias</th>             
                               <th width="20%" class="info">Cantidad de Ausencias</th>             
                            </tr>
                         </thead>
                        <tbody>';
                          while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                                $contar++;
                                $inpt .='<tr>';
                                $inpt .='<td>'.$row['desc_ccosto'].'</td>';                    
                                $inpt .='<td>'.number_format($row['horaausencia'],2).'</td>';
                                $inpt .='<td>'.number_format($row['cantidadausencia'],2).'</td>';
                                $inpt .='</tr>';                        
                                $TotalHorasAusencias=$TotalHorasAusencias + $row['horaausencia'];
                                $TotalCantidadAusencia=$TotalCantidadAusencia + $row['cantidadausencia'];                                
                         } 
                         $inpt .='<tr><td>Total:</td><td align="center"><b>'.number_format($TotalHorasAusencias,2).'</b></td><td align="center"><b>'.number_format($TotalCantidadAusencia,2).'</b></td></tr>';

            
                break;
        
                case 14:
                    $inpt = $inpt.'<thead>
                    <tr>
                               <th colspan="6" style="text-align:center" class="info">'.$titulo.'</th> 
                            </tr>
                            <tr>
                                <th width="5%" class="info">Cedula</th>             
                                <th width="15%" class="info">Nombres</th>             
                                <th width="5%" class="info">Total de Horas</th>             
                                <th width="5%" class="info">Cantidad de Ausencia</th>  
                                <th width="35%"class="info">Descripcion Costo</th>            
                                <th width="35%"class="info">Gerencia</th> 
                            </tr>
                         </thead>
                        <tbody>';
                          while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                                $contar++;
                                $inpt .='<tr>';
                                $inpt .='<td>'.$row['cedula'].'</td>';
                                $inpt .='<td>'.$row['nombres'].'</td>';
                                $inpt .='<td>'.$row['horaausencia'].'</td>';
                                $inpt .='<td>'.$row['cantidadausencia'].'</td>';
                                $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                                $inpt .='<td>'.$row['gergral'].'</td>';
                                $inpt .='</tr>';                        
                         } 
            
                break;
                case 16:
                    $contSt=0;
                    $contador=0;
                    $contHorasNetas=0;
                    $contAus=0;
                    $inpt = $inpt.'<thead>
                    <tr>
                               <th colspan="14" style="text-align:center" class="info">'.$titulo.'</th> 
                            </tr>
                            <tr>
                                <th width="5%" class="info">Cedula</th>             
                                <th width="15%" class="info">Nombres</th>    
                                <th width="15%" class="info">Fecha</th>             
                                <th width="35%"class="info">Descripcion Costo</th>            
                                <th width="35%"class="info">Gerencia</th> 
                                <th width="5%" class="info">Ent.Esp1</th>             
                                <th width="5%" class="info">Sal.Esp1</th>                                  
                                <th width="5%" class="info">Ent.R1</th>             
                                <th width="5%" class="info">Sal.R1</th>  
                                <th width="5%" class="info">Ent.R2</th>             
                                <th width="5%" class="info">Sal.R2</th>  
                                <th width="5%" class="info">H. Net Sica St</th>  
                                <th width="5%" class="info">H. netas presencia</th>             
                                <th width="5%" class="info">H. netas ausencia</th>  
                            </tr>
                         </thead>
                        <tbody>';
                          while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                                $contar++;
                                $fecha = substr($row['fecha'],0,10);
                                $inpt .='<tr>';
                                $inpt .='<td>'.$row['cedula'].'</td>';
                                $inpt .='<td>'.$row['NOMBRES'].'</td>';
                                $inpt .='<td>'.formato_fecha($fecha,"-").'</td>';
                                $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                                $inpt .='<td>'.$row['gergral'].'</td>';
                                $inpt .='<td>'.$row['entrada_esperada1'].'</td>'; 
                                $inpt .='<td>'.$row['salida_esperada1'].'</td>'; 
                                $inpt .='<td>'.$row['entrada_real1'].'</td>'; 
                                $inpt .='<td>'.$row['salida_real1'].'</td>';      
                                $inpt .='<td>'.$row['entrada_real2'].'</td>'; 
                                $inpt .='<td>'.$row['salida_real2'].'</td>'; 
                                $inpt .='<td>'.$row['HorasNetSicaSt'].'</td>'; 
                                $inpt .='<td>'.$row['horasnetapresencia'].'</td>'; 
                                $inpt .='<td>'.$row['horasnetaausencia'].'</td>'; 
                                $inpt .='</tr>';    
                                $contSt+=round($row['HorasNetSicaSt'],2);
                                $contHorasNetas+=round($row['horasnetapresencia'],2);
                                $contAus+=$row['horasnetaausencia'];                      
                         } 
            
                break;
                default:
        
                $inpt = $inpt.'<thead>
                        <tr>
                           <th colspan="19" style="text-align:center" class="info" >'.$titulo.'</th> 
                        </tr>
                        <tr>
                           <td width="5%" class="info" align="right">#</td> 
                           <th width="5%" class="info">Cedula</th>             
                           <th class="info">Nombres</th>  
                           <th class="info">Fecha</th>  
                           <th class="info">Tur</th>  
                           <th class="info">SH</th>  
                           <th class="info">Desc. CCosto</th>                           
                           <th class="info">Ent.R1</th>
                           <th class="info">Sal.R1</th> 
                           <th class="info">Ent.R2</th>
                           <th class="info">Sal.R2</th>                                
                           <th class="info">Ent.E1</th>
                           <th class="info">Sal.E2</th>
                           <th class="info">Ent.E2</th>
                           <th class="info">Sal.E2</th>
                           <th class="info">Cod.Aus</th>
                           <th class="info">H.NetPres</th>
                           <th class="info">H.NetAus</th>
                           <th class="info">H.ST</th>
                        </tr>
                     </thead>
                    <tbody>';
                    $contSt=0;
                    $contador=0;
                    $contHorasNetas=0;
                    $contAus=0;
                      while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                            $contar++; $contador++;
                            $fecha = substr($row['fecha'],0,10);
                            
                            $nombre="'".$row['nombres']."'";
                            $inpt .='<tr>';
                            $inpt .='<td align=\'right\'>'.$contador.'</td>';
                            $inpt .='<td><button type="button" class="btn btn-primary" data-toggle="modal" onclick="ver_fichadas('.$row['cedula'].','.$nombre.')" data-target="#exampleModalCenter">'.$row['cedula'].'</button></td>';                    
                            $inpt .='<td>'.$row['nombres'].'</td>';
                            $inpt .='<td>'.formato_fecha($fecha,"-").'</td>';
                            $inpt .='<td>'.$row['turno'].'</td>';
                            $inpt .='<td>'.$row['sistema_horario'].'</td>';
                            $inpt .='<td>'.$row['desc_ccosto'].'</td>';               
                            $inpt .='<td>'.$row['entrada_real1'].'</td>'; 
                            $inpt .='<td>'.$row['salida_real1'].'</td>';      
                            $inpt .='<td>'.$row['entrada_real2'].'</td>'; 
                            $inpt .='<td>'.$row['salida_real2'].'</td>';               
                            $inpt .='<td>'.$row['entrada_esperada1'].'</td>'; 
                            $inpt .='<td>'.$row['salida_esperada1'].'</td>'; 
                            $inpt .='<td>'.$row['entrada_esperada2'].'</td>'; 
                            $inpt .='<td>'.$row['salida_esperada2'].'</td>'; 
                            $inpt .='<td>'.$row['cod_ausencia'].'</td>';               
                            $inpt .='<td>'.round($row['horasnetapresencia'],2).'</td>';         
                            $inpt .='<td>'.$row['horasnetaausencia'].'</td>';
                            $inpt .='<td>'.round($row['HorasNetSicaSt'],2).'</td>';          
                            $inpt .='</tr>';
                            $contSt+=round($row['HorasNetSicaSt'],2);
                            $contHorasNetas+=round($row['horasnetapresencia'],2);
                            $contAus+=$row['horasnetaausencia'];                       
                     } 
        
                break;
        
         }             
       
              $inpt .=' </tbody>';
              
              if (array_search($tipoauditoria, array(1,13,14,16))===false){
                               $inpt .= '<tfoot>
                                  <tr>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-info"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-danger"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success"></span></th>
                                      <th><span class="label label-success">'.$contHorasNetas.'</span></th>
                                      <th><span class="label label-success">'.$contAus.'</span></th>
                                      <th><span class="label label-success">'.$contSt.'</span></th>                                            
                                  </tr>
                                </tfoot>';
                }
                $inpt .='</table>';
        }
echo $inpt;
//print_r(array_search($tipoauditoria, array(1,13,14)));
}         
?>
