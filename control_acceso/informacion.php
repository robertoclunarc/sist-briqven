<?php 
require_once('libs/conexion.php');
$cn=  Conectarse();
$listado=  pg_query($cn,"SELECT a.trabajador, a.cedula, a.monto_base_diario, a.idpago FROM pagos_periodos_trabajadores a  WHERE a.idpago=".$_GET['idp']);
$prd = pg_fetch_array($listado, null, PGSQL_ASSOC);
if ($_POST) {
 $queryInsert="INSERT INTO dias_descuentos_deudas(
            fkpago, descripcion_descuento, fecha, tipo, condicion, monto)
    VALUES (".$_POST['idpago'].", '".$_POST['descripcion']."', '".$_POST['fecha']."', '".$_POST['tipo']."','".$_POST['condicion']."','".$_POST['monto']."');";
$result = pg_query($cn,$queryInsert);

 
  if ($_POST['condicion']=='DESCONTAR')
       $operacion="descuento=descuento+1";
  else
        $operacion="deudas=deudas+1";  
  $queryUpdate="UPDATE pagos set ".$operacion." Where idpago=".$_POST['idpago'];
  $result2 = pg_query($cn,$queryUpdate);
  
  if (!$result || !$result2) {
    echo "Query: Un error ha occurido.\n".$queryInsert." \n ".$queryUpdate;
    exit;
  }
 //echo "<script language='javascript'> window.opener.location.href='index.php#'; window.close(); </script>"; 
 echo "<script language='javascript'>  window.close();  </script>"; 
}
 ?>
<html>
    <head>
        <title>Informacion de Pago: <?php echo $_GET['idp']; ?></title>
        <meta charset="utf-8">
        <!--    ESTILO GENERAL   -->
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <!--    ESTILO GENERAL    -->
       
        <link type="text/css" href="css/demo_table.css" rel="stylesheet" />
        <script src="http://code.jquery.com/jquery-latest.js"></script><script>// <![CDATA[
 $(document).ready(function() {
 	 $("#tabla_lista_paises").load("listaPaises.php");
   var refreshId = setInterval(function() {
      $("#tabla_lista_paises").load('listaPaises.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
});
// ]]></script>
    </head>
    <body onblur='window.focus();'>
        <form method="post" >
     <input type="hidden" name="idpago" value="<?php echo $prd['idpago']; ?>" />
     <input type="hidden" name="monto" value="<?php echo $prd['monto_base_diario']; ?>" />
      <table cellpadding="0" cellspacing="0" border="0" class="display" >
                <thead>
                    <tr>  
                        <th>Trabajador:</th>  
                        <th><?php echo $prd['trabajador']; ?></th>                        
                        <th>Cedula:</th>
                        <th><?php echo $prd['cedula']; ?></th>                         
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>
                     
                               <tr>
                               <td>Fecha:</td> 
                               <td>&nbsp;</td>                            
                               <td><input name="fecha" id="fecha" value="<?php echo date("Y-m-d"); ?>" type="text"></td> 
                               </tr>
                               
                               <tr>
                               <td>Condicion:</td> 
                               <td>&nbsp;</td>                            
                               <td><select size="1" id="condicion" name="condicion">            
            <option value="DESCONTAR" selected="selected">Descontar</option>
             <option value="ADICIONAR">Adicionar</option>
        </select></td> 
                               </tr>
                               
                               <tr>
                               <td>Tipo:</td> 
                               <td>&nbsp;</td>                            
                               <td><select size="1" id="tipo"  name="tipo">
            <option value="Injustificado">Injustificado</option>
            <option value="Justificado" selected="selected">Justificado</option>
            
        </select></td> 
                               </tr>
                               
                               <tr>
                               <td>Motivo/Descripcion:</td> 
                               <td>&nbsp;</td>                            
                               <td><input name="descripcion" id="descripcion" value="" type="text"></td> 
                               </tr>
                               
                               <tr>
                               <td>&nbsp;</td>                                                             
                                <td>&nbsp;</td> 
                                <td><input type="submit" id="Guardar" onclick="" name="Guadar" value="Guardar" /></td>
                               </tr>
                               
                <tbody>
            </table>
    
    </form> 
</body>
</html>