<?php
 session_start();

$direccion = isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";         //
$ci        = isset($_POST["txtCI"])?$_POST["txtCI"]:"NULL";
$desde     = isset($_POST["txtfdesde"])?$_POST["txtfdesde"]:"NULL";
$fin       = isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$enviar    = isset($_GET["enviar"])?$_GET["enviar"]:"NO";
$hdesde    = isset($_POST["txthdesde"])?$_POST["txthdesde"]:"NULL";
$hfin      = isset($_POST["txthfin"])?$_POST["txthfin"]:"NULL";
$nuevafecha= date("d-m-Y",strtotime($desde."+ 1 days"));
$hfin2     = 
//echo $hdesde;
$qry="SELECT Cedula, Nombre, Cargo, TipodePersonal, Fecha_Inicidencia, Fecha, Hora,        
CASE   
      WHEN Dia='Monday' THEN 'Lunes'
      WHEN Dia='Tuesday' THEN 'Martes'
      WHEN Dia='Wednesday' THEN 'Miercoles'
      WHEN Dia='Thursday' THEN 'Jueves'
      WHEN Dia='Friday' THEN 'Viernes'
      WHEN Dia='Saturday' THEN 'Sabado'
      ELSE  'Domingo'
END as dia_semana,
CASE   
      WHEN Direccion='InDirection' THEN 'ENTRADA'
      ELSE  'SALIDA'
END as direction
FROM ccure_fichadas
WHERE CAST(fecha as datetime) BETWEEN '".$desde."' AND '".$fin."'";
/*
if((strtotime($desde)>strtotime($fin)) 
   if ($fdin==$nuevafecha) && ($desde<$hfin))
"CAST(fecha as datetime)";
"CAST(fecha as datetime) BETWEEN '".$desde."' AND '".$fin."'";
     
*/

$where = array();
if (($direccion!="NULL") && ($direccion!="null") && ($direccion!=""))        
  array_push($where,"Direccion='" . $direccion . "'");

if (($ci!="NULLL") && ($ci!=""))
  if (is_numeric($ci))
      array_push($where,"cedula = " . $ci);
//  else
     

if ($hdesde!="NULL")
  array_push($where,"Hora >= '" . $hdesde. "' AND Hora <= '". $hfin. "'");

$contelem=count($where);
if ($contelem>0)          
  for ($i=0; $i<$contelem; $i++)
    $qry = $qry . " AND " . $where[$i];

$qry = $qry . " ORDER BY Fecha_Inicidencia desc";          

//print $qry;

buscar($qry,$enviar,$desde,$fin);     
       
function buscar($b,$enviar, $desde, $fin) {
       require_once('libs/conexion.php');
       $cn=Conectarse_sitt();
        
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_accesos_perpro">';
              
              $inpt = $inpt.'<thead>
                <tr>
                        <th>Cedula</th>                        
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Tipo Personal</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Dia</th> 
                        <th>Direccion</th>
                </tr>
            </thead>
                       
              <tbody>';
              $contar=0;
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;                                     

                    $inpt .='<tr>';
                    $inpt .='<td>'.$row['Cedula'].'</td>';
                    if ($enviar=='SI') 
                      $inpt .='<td>'.$row['Nombre'].'</td>';
                    else                     
                      $inpt .='<td><A href="#" onclick="verpersonal('.$row['Cedula'].')" >'.$row['Nombre'].'</A></td>';
                    $inpt .='<td>'.$row['Cargo'].'</td>';
                    $inpt .='<td>'.$row['TipodePersonal'].'</td>';
                    $inpt .='<td>'.$row['Fecha'].'</td>';
                    $inpt .='<td>'.$row['Hora'].'</td>';
                    $inpt .='<td>'.$row['dia_semana'].'</td>';
                    $inpt .='<td>'.$row['direction'].'</td>';                   
                    $inpt .='</tr>';                        
              } 
             // $row = $stmt1->fetchall();

              $inpt .='</tbody>
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
                    </tr>
                </tfoot>
              </table>';

        }
echo $inpt;
//print_r($row);
}         
?>
