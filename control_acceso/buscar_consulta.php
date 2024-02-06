<?php
 session_start();
$motivos = isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";    //
$direccion= isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";         //
$ci= isset($_POST["txtCI"])?$_POST["txtCI"]:"NULL";
$desde= isset($_POST["txtfdesde"])?$_POST["txtfdesde"]:"NULL";
$fin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$enviar= isset($_GET["enviar"])?$_GET["enviar"]:"NO";

$qry="SELECT 
  v_acceso_personal_propio.cedula, 
  v_acceso_personal_propio.fecha_acceso, 
  v_acceso_personal_propio.direccion, 
  v_acceso_personal_propio.tipo_personal, 
  v_acceso_personal_propio.nombres, 
  v_acceso_personal_propio.cargo, 
  v_acceso_personal_propio.departamento, 
  v_acceso_personal_propio.jefe_inmediato, 
  v_acceso_personal_propio.usuario, 
  v_acceso_personal_propio.turno, 
  motivos.idmotivo, 
  motivos.descripcion_motivo
FROM 
  v_acceso_personal_propio, 
  motivos
WHERE 
  v_acceso_personal_propio.fkmotivo = motivos.idmotivo and v_acceso_personal_propio.fecha_acceso::date BETWEEN '".$desde."' AND '".$fin."' ";
     
$where = array();
if (($direccion!="NULL") && ($direccion!="null") && ($direccion!=""))        
  array_push($where,"direccion='" . $direccion . "'");

if (($motivos!="NULL") && ($motivos!="null") && ($motivos!=""))
  array_push($where,"idmotivo = " . $motivos);

if (($ci!="NULLL") && ($ci!=""))
  array_push($where,"cedula = '" . $ci  . "'");

$contelem=count($where);
if ($contelem>0)          
  for ($i=0; $i<$contelem; $i++)
    $qry = $qry . " AND " . $where[$i];

$qry.="UNION  ALL
SELECT 
cedula, 
fecha_acceso, 
direccion, 
tipo_personal, 
nombres, 
null,
departamento, 
responsable, 
usuario, 
null,
fkmotivo,
 motivos_visitas.descripcion_motivo
  FROM acceso_personal_foraneo, motivos_visitas
where acceso_personal_foraneo.fkmotivo = motivos_visitas.idmotivo
and acceso_personal_foraneo.fecha_acceso::date BETWEEN '".$desde."' AND '".$fin."'";

$contelem=count($where);
if ($contelem>0)          
  for ($i=0; $i<$contelem; $i++)
    $qry = $qry . " AND " . $where[$i];



    //echo $qry;   

buscar($qry,$enviar,$desde,$fin);     
       
function buscar($b,$enviar, $desde, $fin) {
       require_once('libs/conexion.php');
       $cn=Conectarse();
        
        $res = pg_query($cn,$b) or die("Error en la Consulta SQL: ".$b);
        $contar = pg_num_rows($res);
         
        if($contar == 0){
              $inpt = "No se han encontrado resultados! ";
              
        }else{
              $inpt = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_accesos_perpro">';
              if ($enviar=='SI'){
                $inpt = '<table cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_lista_accesos_perpro">';
              }
              $inpt = $inpt.'<thead>
                <tr>
                    <th>Cedula</th>                        
                    <th>Fecha</th>
                    <th>Direccion</th>
                    <th>Nombre</th>
                    <th>Tipo Personal</th>
                    <th>Cargo</th>
                    <th>Departamento</th> 
                    <th>Jefe</th>                                             
                    <th>Motivo</th>
                    <th>Turno</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tfoot>
                    <tr>
                        <th>Cant. Reg.:</th>
                        <th>'.$contar.'</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                    </tr>
                </tfoot>           
              <tbody>';
              while($row=pg_fetch_array($res)){
                    
                    $c1 = $row['cedula'];
                    $c2 = substr($row['fecha_acceso'],0,16);
                    $c3 = $row['direccion'];                    
                    $c4 = $row['nombres'];
                    $c5 = $row['tipo_personal'];
                    $c6 = $row['cargo'];
                    $c7 = $row['departamento'];
                    $c8 = $row['jefe_inmediato'];                    
                    $c9 = $row['descripcion_motivo'];                    
                    $c10 = $row['turno'];
                    $c11 = $row['usuario'];

                    $inpt .='<tr>';
                    $inpt .='<td>'.$c1.'</td>';                    
                    $inpt .='<td>'.$c2.'</td>';
                    $inpt .='<td>'.$c3.'</td>';
                    $inpt .='<td>'.$c4.'</td>';
                    $inpt .='<td>'.$c5.'</td>';
                    $inpt .='<td>'.$c6.'</td>';
                    $inpt .='<td>'.$c7.'</td>';
                    $inpt .='<td>'.$c8.'</td>';
                    $inpt .='<td>'.$c9.'</td>';
                    $inpt .='<td>'.$c10.'</td>';                
                    $inpt .='<td>'.$c11.'</td>';
                    $inpt .='</tr>';                        
              }
              $inpt .='</tbody></table>';                  
        }
echo $inpt;
if ($enviar=='SI'){
   require("enviodecorreos.php");
    $enc = '<p>&nbsp;</p><table><thead><tr><th>CONTROL DE ACCESO>></th><th>'.'Fecha: '.$desde.' al '.$fin.'</th></tr></thead></table>';
    $resp=ENVIAR_CORREO($enc.$inpt,"Rep. Contro de Acceso ".$_SESSION['user_session_ca']." del ".$desde." al ".$fin,"",'matlux@briqven.com.ve', "");
    echo '<script type="text/javascript">alert("'.$resp.'");</script>';
}
}         
?>