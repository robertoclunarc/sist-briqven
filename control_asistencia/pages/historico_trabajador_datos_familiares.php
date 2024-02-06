<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $conFoto     = isset($_POST["conFoto"])?$_POST["conFoto"]:false;

    $qry="select 
    t.trabajador,
    substr(t.nombre,instr(t.nombre,'/',1,2)+1) || ' ' || translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/','. ') as  nombres,
    i.dato_01 as carga, 
    p.nombre as nombre_hijo_a,  
    decode(p.sexo,1, 'M','F') sexo,
    TO_CHAR(p.fecha_nacimiento, 'DD/MM/YYYY') as fecha_nac, 
    EXTRACT(YEAR FROM sysdate) - EXTRACT(YEAR FROM p.fecha_nacimiento) as edad,
    EXTRACT(month FROM sysdate) - EXTRACT(month FROM p.fecha_nacimiento) as mes,
    EXTRACT(month FROM sysdate) - EXTRACT(day FROM p.fecha_nacimiento) as dia
    from inf_soc_trabajador i , 
    trabajadores_grales g, 
    trabajadores t, 
    personas_relacionada p
    where i.trabajador=g.trabajador
    and t.trabajador=g.trabajador
    and indice_inf_soc in ('CARGFAM   ')
    and p.persona_relacionada=i.persona_relacionada
    and p.trabajador=t.trabajador"; 
    
    if ($trabajador!='NULL')
    {
      $qry.=" and i.TRABAJADOR=".$trabajador."";
    }  
 
    //$qry.=" order by t.trabajador, p.fecha_nacimiento"; 
    $qry.=" order by i.dato_01";


    //print $qry;
    buscar($qry);     
}else
      echo "Debe Iniciar Sesion";       

function buscar($b) {
          include("../BD/conexion.php");
          $cn=Conex_oramprd();
          $stid = oci_parse($cn, $b);
          oci_execute($stid);
          $contar=1;        
            if($contar == 0){
                  $inpt = "No se han encontrado resultados!";
                  
            }else{
              $inpt = '<b>Datos Familiares</b><table width="100%" class="table table-striped" id="dataTables-example01" border="0" >';
              
              $inpt = $inpt.'<thead>
            <tr>
            <th class = "info"><h6>#.</h6></th>
            <th class = "info"><h6>Nombre</h6></th>
            <th class = "info"><h6>Vinculo</h6></th>
            <th class = "info"><h6>sexo</h6></th>
            <th class = "info"><h6>Fecha de nacimiento</h6></th>
            <th class = "info"><h6>Edad</h6></th>
            </tr>
        </thead>
        <tbody>';
                  
                $contar=0;
                while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {   
                  $fecha_nacimiento = str_replace('/', '-', $row['FECHA_NAC']);
                  $contar++;
                  $inpt .='<tr>';
                  $inpt .='<td><h6>'.$contar.'</h6></td>';                    
                  $inpt .='<td><h6>'.$row['NOMBRE_HIJO_A'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['CARGA'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['SEXO'].'</h6></td>';
                  $inpt .='<td><h6>'.$fecha_nacimiento.'</h6></td>';
                  $inpt .='<td><h6>'.calcular_edad(substr($row['FECHA_NAC'],0,10)).'</h6></td>';
                  $inpt .='</tr>';    
                  } 
                
                  $inpt .=' </tbody>

                    </table>';

            }
    echo $inpt;
    //print_r($row);
}         


?>
