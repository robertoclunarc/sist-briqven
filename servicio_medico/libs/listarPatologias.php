<?php 
session_start();
?>

<script type="text/javascript"> 
function enviar(uid,desc,cod)
{  
    window.opener.document.getElementById('cboPatologias').value = uid;
    window.opener.document.getElementById('busqueda').value =  cod;
    window.opener.document.getElementById('patol').value = desc;
   // window.opener.Ircliente(rutcli);
    window.close();               
} 
</script>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Patologia</title>
        <meta charset="utf-8">
        <!--    ESTILO GENERAL   -->
        <link type="text/css" href="../css/estilo.css" rel="stylesheet" />
        
        <!--    ESTILO GENERAL    -->
        <!--    JQUERY   -->
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../js/funciones6.js"></script> 
    <script language="javascript" type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>       
     
    <script type="text/javascript" language="javascript" src="../js/jslistapatologias.js"></script>      
    
        <!--    JQUERY    -->
        <!--    FORMATO DE TABLAS    -->
        <link type="text/css" href="../css/demo_table.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="../js/jquery.dataTablesPat.js"></script>
        <!--    FORMATO DE TABLAS    --> 
       
    </head>
    <body> 
<?php
require("../include_conex.php");
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
$cn = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
$listado=pg_query($cn,"select * from tbl_patologias order by codigo_etica");
?> 
<article id="contenido">  
 <div id="capa1">

         <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_patologias">
                <thead>
                    <tr> 
                        <th>Oper.</th> 
                        <th>Descripcion</th>                       
                        <th>Tipo</th>
                        <th>Codigo Etica</th>                         
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>
                     
                    <?php
                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {  
                      $idp=$reg['uid'];
                      $desc="'".$reg['descripcion']."'";  
                      $cod="'".$reg['codigo_etica']."'";                    
                       echo '<tr>';                                
                       echo '<td>';                       
                      echo '<A id="cons'.$idp.'" onclick="enviar('.$idp.', '.$desc.', '.$cod.')" href="#" title="Seleccionar"><IMG SRC="../images/flecha.png" WIDTH="20" HEIGHT="20"></A>                        
                       </td>';                    
                       echo '<td>'.$reg['descripcion'].'</td>';
                       echo '<td>'.$reg['tipo'].'</td>';
                       echo '<td>'.$reg['codigo_etica'].'</td>';                               
                       echo '</tr>';                               
                    }
                    ?>
                </tbody>
            </table>
 </div>
 </article>
 </body>
</html> 
<?php 
pg_free_result($listado);
?>
