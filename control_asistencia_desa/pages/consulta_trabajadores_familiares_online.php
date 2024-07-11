<?php
 session_start();
 error_reporting(E_ERROR | E_WARNING | E_PARSE);
 include("../BD/conexion.php");
require_once('funciones_var.php');
if (isset($_SESSION['user_session_const']) && isset($_SESSION['nivel_const'])){
    $trabajador  = isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    $trabajador_filtro='';
    if ($trabajador!='NULL')
    {
      $trabajador_filtro=" and t.TRABAJADOR=".$trabajador."";
    } 
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
              EXTRACT(month FROM sysdate) - EXTRACT(day FROM p.fecha_nacimiento) as dia,
              i.DATO_04,
              i.DATO_07
              from inf_soc_trabajador i 
              INNER JOIN trabajadores_grales g ON i.trabajador=g.trabajador
              INNER JOIN trabajadores t ON t.trabajador=g.trabajador 
              INNER JOIN personas_relacionada p ON p.trabajador=t.trabajador
              LEFT JOIN inf_complementaria inf ON t.TRABAJADOR = inf.TRABAJADOR
              where indice_inf_soc in ('CARGFAM   ')
              and p.persona_relacionada=i.persona_relacionada
              AND g.SIT_TRABAJADOR=1 ".$trabajador_filtro." 
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
              NULL,
              NULL,
              NULL
              FROM TRABAJADORES t
              INNER JOIN trabajadores_grales g ON t.TRABAJADOR =g.TRABAJADOR
              WHERE g.SIT_TRABAJADOR=1  AND t.TRABAJADOR NOT IN (SELECT TRABAJADOR FROM inf_soc_trabajador i WHERE indice_inf_soc in ('CARGFAM '))  ".$trabajador_filtro." ";

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
              $inpt = '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" border="1">';
              $inpt = $inpt.'<thead>
                    <tr>
                    <th class = "info">#.</th>
                    <th class = "info" style="text-align:center;">C&eacute;dula </th>
                    <th class = "info">Trabajador</th>
                    <th class = "info" style="text-align:center;">C&eacute;dula </th>
                    <th class = "info">Nombre familiar</th>
                    <th class = "info" style="text-align:center;">Sexo</th>
                    <th class = "info" style="text-align:center;">Fecha de Nacimiento</th>
                    <th class = "info" style="text-align:center;">Edad</th>
                    <th class = "info">Parentesco</th>
                    <th class = "info">Condición Especial</th>
                    <th class = "info">Tipo Condición Especial</th>
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

                      list($dia,$mes,$ano) = explode("/",$row['FECHA_NAC']);
                      $ano_diferencia  = date("Y") - $ano;
                      $mes_diferencia  = date("m") - $mes;
                      $dia_diferencia  = date("d") - $dia;
                      if ($mes_diferencia < 0)
                          $edad--;
                      elseif ($mes_diferencia == '' && $dia_diferencia < 0 )
                          $edad--;
                      
                      if ($row['DATO_04']=='SI'){
                         $condicion_especial=$row['DATO_07'];
                         $con_condicion_especial='SI';
                      }else{
                        $condicion_especial='';
                        $con_condicion_especial='';
                      } 
                  }
                  $inpt .='<tr>';
                  $inpt .='<td style="text-align:right; vertical-align: middle;">'.$contar.'</td>';                    
                  $inpt .='<td style="text-align:center; vertical-align: middle;">'.$row['TRABAJADOR'].'</td>';
                  $inpt .='<td>'.$row['NOMBRES'].'</td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;">'.$row['CEDULA_FAMILIAR'].'</td>';                  
                  $inpt .='<td>'.$row['NOMBRE_FAMILIAR'].'</td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;">'.$row['SEXO'].'</td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;">'.$row['FECHA_NAC'].'</td>';
                  $inpt .='<td style="text-align:center; vertical-align: middle;">'.$edad.'</td>';
                  $inpt .='<td>'.$row['CARGA'].'</td>';
                  $inpt .='<td>'.$con_condicion_especial.'</td>';
                  $inpt .='<td>'.$condicion_especial.'</td>';
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