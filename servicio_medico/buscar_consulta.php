<?php
 session_start();
 require("enviodecorreos.php");
$motivos = isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";    //
$turno= isset($_POST["cboTurno"])?$_POST["cboTurno"]:"NULL";         //
$area= isset($_POST["cboArea"])?$_POST["cboArea"]:"NULL";
$ci= isset($_POST["txtCI"])?$_POST["txtCI"]:"NULL";
$desde= isset($_POST["txtfdesde"])?$_POST["txtfdesde"]:"NULL";
$fin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$enviar= isset($_GET["enviar"])?$_GET["enviar"]:"NO";

switch ($_SESSION['nivel']) {
  case 1:
      $qry="SELECT * FROM v_morbilidad WHERE fecha::date BETWEEN '".$desde."' AND '".$fin."'";
      break;
  case 2:
      $qry="SELECT * FROM v_morbilidad WHERE fecha::date BETWEEN '".$desde."' AND '".$fin."' AND login_atendio like '%".$_SESSION['user_session']."%'";
      break;
  case 3:
      $qry="SELECT * FROM v_morbilidad WHERE fecha::date BETWEEN '".$desde."' AND '".$fin."'";
      break;      
}

$where = array();

if (($turno!="NULL") && ($turno!="null") && ($turno!=""))        
  array_push($where,"turno=" . $turno);

if (($motivos!="NULL") && ($motivos!="null") && ($motivos!=""))
  array_push($where,"id_motivo =" . $motivos);

if (($area!="NULL") && ($area!="null") && ($area!=""))
  array_push($where, "id_area =" . $area);

if (($ci!="NULLL") && ($ci!=""))
  array_push($where,"ci = '" . $ci  . "'");

$contelem=count($where);
if ($contelem>0)          
  for ($i=0; $i<$contelem; $i++)
    $qry = $qry . " AND " . $where[$i];      

buscar($qry,$enviar,$desde,$fin);     
       
function buscar($b,$enviar, $desde, $fin) {
       require("include_conex.php"); 
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $cn = pg_connect($cadenaConexion) or die("Error en la Conexion: " . pg_last_error());
        $res = pg_query($cn,$b) or die("Error en la Consulta SQL: ".$b);
        $contar = pg_num_rows($res);
         
        if($contar == 0){
              $inpt = "No se han encontrado resultados! ";
              
        }else{
              $inpt = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_morbilidad">';
              if ($enviar=='SI'){
                $inpt = '<table cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_morbilidad">';
              }
              $inpt = $inpt.'<thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>turno</th>                                              
                    <th>Nombres</th>  
                    <th>Cedula</th>                        
                    <th>Cargo</th>
                    <th>Supervisor</th>
                    <th>Area</th>
                    <th>Tipo Diagnostico</th>
                    <th>Motivo</th> 
                    <th>P/S</th> 
                    <th>Tipo Afeccion por Sist.</th>
                    <th>Diag.</th>
                    <th>Condicion | Observacion</th>
                    <th>Medicamento</th>                    
                    <th>Med. Ocupante</th> 
                    <th>Direccion Paciente</th>
                    <th>Mano Dominante</th>
                    <th>Sexo</th>
                    <th>Talla</th>
                    <th>Peso</th>
                    <th>IMC</th>
                    <th>Edad</th>
                </tr>
            </thead>
            
              <tbody>';
              while($row=pg_fetch_array($res)){
                    $c1 = substr($row['fecha'],0,16);
                    $c2= $row['turno'];
                    $c3 = $row['nombre_completo'];
                    $c4 = $row['ci'];
                    $c5 = $row['cargo'];
                    $c6 = $row['nombres_jefe'];
                    $c7 = $row['area'];
                    $c8 = $row['descripciondiagnostico'];
                    $c9 = $row['motivo'];
                    $c10 = $row['p_s'];
                    $c11 = $row['resultado_eva'];
                    $c12 = $row['descripcion_afeccion'];                    
                    $c13 = $row['motivo_consulta'];//.' / '.$row['resultado_eva'];
                    $c14 = $row['aplicacion'];                    
                    $c15 = $row['login_atendio'];

                    $c16 = $row['direccion_hab'];
                    $c17 = $row['mano_dominante'];
                    $c18 = $row['sexo'];
                    $c19 = $row['talla'];
                    $c20 = $row['peso'];
                    $c21 = $row['imc'];
                    $c22 = substr($row['edad'], 0,12);

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
                    $inpt .='<td>'.$c12.'</td>';
                    $inpt .='<td>'.$c13.'</td>';
                    $inpt .='<td>'.$c14.'</td>';                
                    $inpt .='<td>'.$c15.'</td>';
                    $inpt .='<td>'.$c16.'</td>';
                    $inpt .='<td>'.$c17.'</td>';
                    $inpt .='<td>'.$c18.'</td>';
                    $inpt .='<td>'.$c19.'</td>';
                    $inpt .='<td>'.$c20.'</td>';
                    $inpt .='<td>'.$c21.'</td>';
                    $inpt .='<td>'.$c22.'</td>';
                    
                    $inpt .='</tr>';                         
                        
              }
              $inpt .='</tbody></table>';                  
        }
echo $inpt;
if ($enviar=='SI'){
    $enc = '<p>&nbsp;</p><table><thead><tr><th>MORBILIDAD>></th><th>'.'Fecha: '.$desde.' al '.$fin.'</th><th>'.'Usuario: '.$_SESSION['username'].' ('.$_SESSION['user_session'].')</th></tr></thead></table>';
    $resp=ENVIAR_CORREO($enc.$inpt,"Morbilidad ".$_SESSION['username']." del ".$desde." al ".$fin,"",$_SESSION['userid'], "MORBILIDAD");
    echo '<script type="text/javascript">alert("'.$resp.'");</script>';
}
}         
?>