<?php
session_start();
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$listado=  pg_query($cn,"select b.* from seleccion_regalos a, regalos b where a.fkopcion=b.idopcion and a.trabajador='".$_SESSION['username']."' and a.periodo='".$_GET['idp']."'");
$qr="SELECT replace(nombre, '/', ' ') as nombre_trb,trabajador, current_date as hoy FROM trabajadores  WHERE trabajador='".$_SESSION['username']."'";
$listadop=  pg_query($cn,$qr);
$prd = pg_fetch_array($listadop, null, PGSQL_ASSOC);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sist. Carga Familiar HCM</title>
        <script type="text/javascript">
            function imprimir() {
                if (window.print) {
                    window.print();
                } else {
                    alert("La función de impresion no esta soportada por su navegador.");
                }
            }
        </script>
</head>
<body onload="imprimir();">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../logobriqven.JPG" width="103" height="61" /></td>
   
  </tr>
</table>

<p>&nbsp;</p>

<table width="700" border="1" align="left" cellpadding="1" cellspacing="0" bordercolor="#000000">
  <tr>
    <th scope="col">Descripcion</th>
    <th scope="col">Combo Seleccionado</th>    
  </tr>
   <?php  
     while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
    {   
   ?> 
  <tr>
    <td><?php echo $reg['descripcion_regalo']; ?></td>
    <td><?php echo $reg['grupo_opcion']; ?></td>
    
  </tr>
   <?php  
     } 
   ?> 
</table>
<p>&nbsp;</p>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>Los sigueintes registros son pertenecen a los combos de obsequios de la empresa a los trabajadores y que son seleccionado por el trabajador descrito debajo</td>
  </tr>
</table>
<p>&nbsp;</p>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>Titular:</td>
    <td><strong><?php echo $prd['nombre_trb']; ?></strong></td>
  </tr>
  <tr>
    <td>Cedula:</td>
    <td><strong><?php echo $prd['trabajador']; ?></strong></td>
  </tr>
  <tr>
    <td align="left" valign="bottom" height="56">Firma:</td>
    <td align="left" valign="bottom">__________________________</td>
  </tr>
  <tr>
    <td>Fecha:</td>
    <td><?php echo $prd['hoy']; ?></td>
  </tr>
</table>
</body>
</html>
 <?php 
pg_free_result($listado);
pg_free_result($listadop);
?>