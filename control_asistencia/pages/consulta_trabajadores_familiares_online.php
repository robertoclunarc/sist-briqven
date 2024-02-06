<?php
 session_start();
 error_reporting(E_ERROR | E_WARNING | E_PARSE);
 include("../BD/conexion.php");
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";

    $qry="SELECT 
              t.trabajador,
              translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/',' ')  || ' ' || substr(t.nombre,instr(t.nombre,'/',1,2)+1) as  nombres,
              inf.des_documento_01 as tipo_sangre,
              i.dato_01 as carga, 
              i.dato_02 as cedula_familiar,    
              p.nombre as nombre_familiar,  
              decode(p.sexo,1, 'M','F') sexo,
              TO_CHAR(p.fecha_nacimiento, 'DD/MM/YYYY') as fecha_nac, 
              EXTRACT(YEAR FROM sysdate) - EXTRACT(YEAR FROM p.fecha_nacimiento) as edad,
              EXTRACT(month FROM sysdate) - EXTRACT(month FROM p.fecha_nacimiento) as mes,
              EXTRACT(month FROM sysdate) - EXTRACT(day FROM p.fecha_nacimiento) as dia
              from inf_soc_trabajador i 
              INNER JOIN trabajadores_grales g ON i.trabajador=g.trabajador
              INNER JOIN trabajadores t ON t.trabajador=g.trabajador 
              INNER JOIN personas_relacionada p ON p.trabajador=t.trabajador
              LEFT JOIN inf_complementaria inf ON t.TRABAJADOR = inf.TRABAJADOR
              where indice_inf_soc in ('CARGFAM   ')
              and p.persona_relacionada=i.persona_relacionada
              AND g.SIT_TRABAJADOR=1
          UNION 
              SELECT 
              t.trabajador, 
              translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/',' ')  || ' ' || substr(t.nombre,instr(t.nombre,'/',1,2)+1) as  nombres,
              NULL,
              NULL,
              NULL,
              NULL,
              NULL,
              NULL,
              NULL,
              NULL,
              NULL
              FROM TRABAJADORES t
              INNER JOIN trabajadores_grales g ON t.TRABAJADOR =g.TRABAJADOR
              WHERE g.SIT_TRABAJADOR=1  AND t.TRABAJADOR NOT IN (SELECT TRABAJADOR FROM inf_soc_trabajador i WHERE indice_inf_soc in ('CARGFAM '))";

    /*$qry="select 
    t.trabajador,
    substr(t.nombre,instr(t.nombre,'/',1,2)+1) || ' ' || translate(substr(t.nombre,1,instr(t.nombre,'/',1,2)-1),'/','. ') as  nombres,
    inf.des_documento_01 as tipo_sangre,
    i.dato_01 as carga, 
    i.dato_02 as cedula_familiar,    
    p.nombre as nombre_familiar,  
    decode(p.sexo,1, 'M','F') sexo,
    TO_CHAR(p.fecha_nacimiento, 'DD/MM/YYYY') as fecha_nac, 
    EXTRACT(YEAR FROM sysdate) - EXTRACT(YEAR FROM p.fecha_nacimiento) as edad,
    EXTRACT(month FROM sysdate) - EXTRACT(month FROM p.fecha_nacimiento) as mes,
    EXTRACT(month FROM sysdate) - EXTRACT(day FROM p.fecha_nacimiento) as dia
    from inf_soc_trabajador i 
    INNER JOIN trabajadores_grales g ON i.trabajador=g.trabajador
    INNER JOIN trabajadores t ON t.trabajador=g.trabajador 
    INNER JOIN personas_relacionada p ON p.trabajador=t.trabajador
    LEFT JOIN inf_complementaria inf ON t.TRABAJADOR = inf.TRABAJADOR
    where indice_inf_soc in ('CARGFAM   ')
    and p.persona_relacionada=i.persona_relacionada
    AND g.SIT_TRABAJADOR=1"; 
    */
    if ($trabajador!='NULL')
    {
      $qry.=" and i.TRABAJADOR=".$trabajador."";
    }  
 
    //$qry.=" order by t.trabajador, p.fecha_nacimiento"; 
    $qry.=" order by 1";


    //print $qry;
    buscar($qry);     
}else
      echo "Debe Iniciar Sesion";     
       
function buscar($b) {
        $cn=Conex_oramprd();
        $stid = oci_parse($cn, $b);
        oci_execute($stid);
        $contar=1;  
        //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              $inpt = $inpt.'<thead>
                    <tr>
                    <th class = "info"><h6>#.</h6></th>
                    <th class = "info" style="text-align:center;"><h6>C&eacute;dula </h6></th>
                    <th class = "info"><h6>Trabajador</h6></th>
                    <th class = "info" style="text-align:center;"><h6>C&eacute;dula </h6></th>
                    <th class = "info"><h6>Nombre familiar</h6></th>
                    <th class = "info" style="text-align:center;"><h6>Sexo</h6></th>
                    <th class = "info" style="text-align:center;"><h6>Fecha de Nacimiento</h6></th>
                    <th class = "info" style="text-align:center;"><h6>Edad</h6></th>
                    <th class = "info"><h6>Parentesco</h6></th>
                    </tr>
                </thead>
        <tbody>';
             $contar=0;
             while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) { 
                  $contar++;
                  $fecha_nacimiento = str_replace('/', '-', $row['FECHA_NAC']);
                  $edad='';
                  if ($row['FECHA_NAC']!=NULL){
                    $edad = $row['EDAD'];
                    /*if ($row['MES']<0)
                      $edad = $row['EDAD'] -1;
                    elseif ($row['MES']==0 && $row['MES'])
                      $edad = $row['EDAD'] -1;
*/
                  list($dia,$mes,$ano) = explode("/",$row['FECHA_NAC']);
                  $ano_diferencia  = date("Y") - $ano;
                  $mes_diferencia  = date("m") - $mes;
                  $dia_diferencia  = date("d") - $dia;
                  if ($mes_diferencia < 0)
                      $edad--;
                  elseif ($mes_diferencia == '' && $dia_diferencia < 0 )
                      $edad--;
                  
                  //$edad = $edad . $row['FECHA_NAC']."/".$fecha_nacimiento." (".$ano."*".$mes."*". $dia.")  (".$ano_diferencia."*".$mes_diferencia."*". $dia_diferencia.")";

                   // $edad = calcular_edad(substr($row['FECHA_NAC'],0,10));
                  }
                  $inpt .='<tr>';
                  $inpt .='<td style="text-align:right; vertical-align: middle;"><h6>'.$contar.'</h6></td>';                    
                  $inpt .='<td style="text-align:center; vertical-align: middle;"><h6>'.$row['TRABAJADOR'].'</h6></td>';
                  $inpt .='<td><h6>'.$row['NOMBRES'].'</h6></td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;"><h6>'.$row['CEDULA_FAMILIAR'].'</h6></td>';                  
                  $inpt .='<td><h6>'.$row['NOMBRE_FAMILIAR'].'</h6></td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;"><h6>'.$row['SEXO'].'</h6></td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;"><h6>'.$row['FECHA_NAC'].'</h6></td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;"><h6>'.$edad.'</h6></td>';
                  $inpt .='<td><h6>'.$row['CARGA'].'</h6></td>';
                  $inpt .='</tr>';                           
              } 
             
              $inpt .=' </tbody>
                </table>';
                $inpt .='<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    '; 
        }
echo $inpt;
//print_r($row);
}         
?>