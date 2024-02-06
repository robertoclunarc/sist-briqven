<?php 
require_once('../libs/conexion.php');
$cn=  Conectarse();
$listado=  pg_query($cn,"SELECT a.*, b.cedula || ' | ' || b.trabajador as ctrabajador FROM notas a, pagos_periodos_trabajadores b WHERE b.idpago=a.fkpagonota and b.idperiodo=".$_GET['per']." order by a.fecha_nota desc");

$qr="SELECT cedula, cedula || ' | ' || trabajador as ctrabajador, cedula || ' | ' || trabajador || ' |' || idpago as cptrabajador FROM pagos_periodos_trabajadores WHERE idperiodo=".$_GET['per']." order by trabajador";
$ctrabajador=  pg_query($cn,$qr);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agregar Notas del Pago del Trabajador</title>
        <script language="javascript" type="text/javascript" src="jquery-1.3.2.min.js"></script>	
        <script language="javascript" type="text/javascript" src="jquery.validate.1.5.2.js"></script>	
        <script language="javascript" type="text/javascript" src="script.js"></script>
        <link href="estilo.css" rel="stylesheet" type="text/css" />

    </head>
    
    <body>
        <div id="contenedor">  
            <form action="javascript: fn_agregar();" method="post" id="frm_usu">
	
                <table class="formulario">
                    <thead>
                        <tr>
                            <th colspan="2"><img src="add.png" /> Ingresar Nueva Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cedula:</td>
                            <td><select name="cedula" type="text" id="cedula" class="required">
 				<?php 				      
				      while($cc = pg_fetch_array($ctrabajador, null, PGSQL_ASSOC))    
				   {   
                  		?>
                                        <option value="<?php echo $cc['cptrabajador']; ?>"><?php echo $cc['ctrabajador']; ?></option>
                                 <?php  
				     }
                  		?>  
                                        </select></td>
                        </tr>
                       
			
			<tr>
                            <td>Fecha Nota:</td>
                            <td><input name="fecha_nota" type="date" value="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" id="fecha_nota" size="10" class="required" /></td>
                        </tr>
			<tr>
                            <td>Nota:</td>
                            <td><textarea name="nota" id="nota" cols="40" rows="5" class="required"></textarea>
			<input type="checkbox" disabled name="leida" id="leida" value="N"> Leida<br></td>
 <input type="hidden" name="fkpagonota" id="fkpagonota" value="<?php echo $_GET['idp']; ?>" />
                        </tr>			
			
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><input name="agregar" type="submit" id="agregar" value="Agregar" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <table id="grilla" class="lista">
              <thead>
                    <tr>
   			<th style="display: none;">ID Nota</th>			
                        <th>Fecha</th>
                        <th>Leida</th>
                        <th>Nota</th>                       
			             <th>Trabajador</th>	
			<th style="display: none;">ID Pago</th>			
                    </tr>
                </thead>
                <tbody>

		  <?php  
                      $cont=0;
                      while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {   
                  ?> 
                    <tr>
			<td  style="display: none;"><?php echo $reg['idnota']; ?></td>			
                        <td><?php echo $reg['fecha_nota']; ?></td>
                        <td><?php echo $reg['leida']; ?></td>
                        <td><?php echo $reg['nota']; ?></td> 
			<td><?php echo $reg['ctrabajador']; ?></td>
			<td style="display: none;"><?php echo $reg['fkpagonota']; ?></td>
			
                    </tr>
                    
		  <?php  
                     $cont++; } 
                  ?> 
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="5"><strong>Cantidad:</strong> <span id="span_cantidad"> <?php echo $cont; ?> </span> Item(s).</td>
                    </tr>
                </tfoot>
            </table>
            <hr />
           
    
        </div>
    </body>
</html>
 <?php 
pg_free_result($listado);
pg_free_result($ctrabajador);
?>
