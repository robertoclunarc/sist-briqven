<?php
session_start();
require_once('libs/conexion.php');
$cn=  Conectarse_posgres();
$listado=  pg_query($cn,"SELECT a.*, (case when a.sexo='1' then 'M' else 'F' end) as sx,(case when a.hcm='1' then 'SI' else 'NO' end) as h, (case when a.maternidad='1' then 'SI' else 'NO' end) as mt, date_part('year',age(a.fecha_nacimiento)) as edad FROM carga_familiar_hcm a  WHERE sit_carga=1
 AND a.trabajador='".$_SESSION['username']."' order by a.secuencia");
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
    <td><img src="logobriqven.JPG" width="103" height="61" /></td>
    <td><img src="logo_seguroh.png" width="233" height="63" /></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>La presente  información es para mantener actualizada la base de datos de los titulares y  beneficiarios de la póliza de salud que lleva la empresa. Requerimos de su  valiosa colaboración para indicarnos si aún mantiene la misma carga familiar  incluida en los beneficiarios del seguro H.C.M</td>
  </tr>
</table>
<p>&nbsp;</p>

<table width="700" border="1" align="left" cellpadding="1" cellspacing="0" bordercolor="#000000">
  <tr>
    <th scope="col">C.I.</th>
    <th scope="col">Nombre</th>
    <th scope="col">Sexo</th>
    <th scope="col">Edad</th>
    <th scope="col">Parentesco</th>
    <th scope="col">HCM</th>
    <th scope="col">Maternidad</th>
  </tr>
   <?php  
     while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
    {   
   ?> 
  <tr>
    <td><?php echo $reg['persona_relacionada']; ?></td>
    <td><?php echo $reg['nombre']; ?></td>
    <td><?php echo $reg['sx']; ?></td>
    <td><?php echo $reg['edad']; ?></td>
    <td><?php echo $reg['dato_01']; ?></td>
    <td><?php echo $reg['h']; ?></td>
    <td><?php echo $reg['mt']; ?></td>
  </tr>
   <?php  
     } 
   ?> 
</table>
<p>&nbsp;</p>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>De no haber modificado el estatus de su carga familiar y no  haber sido excluido en los 30 días siguientes de la  exclusión será descontado a su cuenta el pago  adicional que ha realizado Matesi a la empresa de seguro.</td>
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