<?php
 session_start();
 if (isset($_SESSION['user_session_const'])){
include("../BD/conexion.php");
include("funciones_var.php");

$finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
$ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$trabajador= isset($_POST["cbotrabajador"])?trim($_POST["cbotrabajador"]):"NULL";
$ccosto= isset($_POST["cboccosto"])?trim($_POST["cboccosto"]):"NULL";
$cbodireccion= isset($_POST["cbodireccion"])?$_POST["cbodireccion"]:"NULL";
$conFoto= isset($_POST["conFoto"])?$_POST["conFoto"]:false;

$link=Conex_Contancia_pgsql();
$acceso=permiso_usuario($link, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);

$qry="SELECT a.Cedula, a.Nombre, a.Cargo, a.TipodePersonal, a.Fecha_Inicidencia, a.Fecha, a.Hora,       
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
      WHEN a.Direccion='InDirection' THEN 'ENTRADA'
      ELSE  'SALIDA'
END as direction, b.TURNO , b.DESC_CCOSTO
FROM ccure_fichadas a, dbo.ADAM_DATOS_PERSONALES b
WHERE a.Cedula=b.Trabajador and CAST(fecha as datetime) BETWEEN '".$finicio."' AND '".$ffin."'"; 

if ($ccosto!='' && $ccosto!='NULL'){
  $qry.=" and b.centro_costo= '".$ccosto."'";
}
/*else{
    $os = array(3,5);
    if (in_array($_SESSION['nivel_const'], $os)  && $acceso==false) {      
      $qry.=" and b.centro_costo= '".$_SESSION['ccosto']."'";
    }
}*/


if ($cbodireccion!='')
  $qry.=" and a.Direccion= '".$cbodireccion."'";

if ($trabajador!='NULL' && $trabajador!=''){
    $qry.=" and a.Cedula=".$trabajador."";
}
else{

  if (isset($_SESSION['nivel_const'])){
    $os = array(3, 5);
    if (in_array($_SESSION['nivel_const'], $os) || $acceso) {    
      $trabajador=implode(",", $_SESSION['arrayTrab']);
      $qry.=" and a.Cedula in (".$trabajador.")";
    }
  }else{
     $qry.=" and a.Cedula = NULL";
  }  
}
$qry.=" order by a.Cedula, a.Fecha_Inicidencia desc";



buscar($qry,$conFoto); 
}
else{
  echo '<div class="alert alert-danger">DEBE INICIAR SESION</div>';
}   
       
function buscar($b, $conFoto) {
  //echo $b;
       $cn=Conectarse_sitt();
        $stmt1 = $cn->query($b);
        //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        //$stmt1->execute();
        $contar = $stmt1->columnCount(); 
       //$contar=1;        
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="100%" border="0" class="table table-striped" id="dataTables-example">';
              
              $inpt = $inpt.'<thead>
                    <tr>';
                        if ($conFoto!=false)
                            $inpt = $inpt.'<th class = "info">Foto</th>';

                        $inpt = $inpt.'<th class = "info">Cedula</th>
                        <th class = "info">Nombre</th>
                        <th class = "info">Cargo</th>
                        <th class = "info">C.Costo</th>
                        <th class = "info">Turno</th>
                        
                        <th class = "info">Fecha</th>
                        <th class = "info">Hora</th>
                        <th class = "info">Dia</th>
                        <th class = "info">Direccion</th>

                    </tr>
                </thead>

        <tbody>';
             $contar=0;
              
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                    
                    $contar++;

                    $inpt .='<tr>';
                    if ($conFoto!=false){
                      $url_img="../../rrhh/fotcarmat_new/".$row['Cedula'].".bmp";
                      if (!is_file( $url_img ))
                        $url_img="images/user.png";

                      $inpt .='<td><img width="50px" height="50px" style="border-radius: 50%;" src="'.$url_img.'" data-toggle="modal" onclick="cargarfoto('.$row['Cedula'].')" data-target="#exampleModalCenterFoto" /></td>'; 
                    }

                    $inpt .='<td>'.$row['Cedula'].'</td>';                    
                    $inpt .='<td>'.$row['Nombre'].'</td>';
                    $inpt .='<td>'.$row['Cargo'].'</td>';
                    $inpt .='<td>'.$row['DESC_CCOSTO'].'</td>';
                    $inpt .='<td>'.$row['TURNO'].'</td>';
                    //$inpt .='<td>'.$row['TipodePersonal'].'</td>';
                    $inpt .='<td>'.$row['Fecha'].'</td>';
                    $inpt .='<td>'.$row['Hora'].'</td>';
                    $inpt .='<td>'.$row['dia_semana'].'</td>';
                    $inpt .='<td>'.$row['direction'].'</td>';
                    $inpt .='</tr>';                        
              } 

              $cn=null;
             
              $inpt .=' </tbody>
                </table>';
        }
echo $inpt;
//print_r($row);
}         
?>
