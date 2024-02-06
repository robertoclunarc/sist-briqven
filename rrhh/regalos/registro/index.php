<?php 
session_start();
if (isset($_SESSION['username'])){
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$listado=  pg_query($cn,"SELECT idopcion, descripcion_regalo, grupo_opcion FROM regalos order by grupo_opcion");

//$qr="SELECT replace(nombre, '/', ' ') as nombre_trb,trabajador FROM trabajadores  WHERE trabajador='16395343'";
$qr="SELECT replace(nombre, '/', ' ') as nombre_trb,trabajador FROM trabajadores  WHERE trabajador='".$_SESSION['username']."'";
$listadop=  pg_query($cn,$qr);
$prd = pg_fetch_array($listadop, null, PGSQL_ASSOC);

$rr="SELECT count( grupo_opcion) nro_grupo, grupo_opcion FROM regalos group by grupo_opcion order by grupo_opcion";
$lrr=  pg_query($cn,$rr);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Obsequio para Trabajadores</title>
         <script type="text/javascript">
            
        </script>
        <script language="javascript" type="text/javascript" src="jquery-1.3.2.min.js"></script>	
        <script language="javascript" type="text/javascript" src="jquery.validate.1.5.2.js"></script>	
        <script language="javascript" type="text/javascript" src="script.js"></script>
        <link href="estilo.css" rel="stylesheet" type="text/css" />

    </head>
    
    <body>
        <div id="contenedor">  
            <form action="javascript: fn_agregar();" method="post" id="frm_usu">
	
                <table class="formulario"><br />
                    <thead>
                        <tr>
                            <th colspan="2"><img src="add.png" /> Registro de Obsequios par Trabajadores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>C.I. Trabajador:</td>
                            <td>			
			                 <input name="cedula" type="text" id="cedula" readonly value="<?php echo $prd['trabajador']; ?>" size="10" class="required" /></td>
                        </tr>
                        <tr>
                            <td>Nombre Trabajador:</td>
                            <td><input name="nombres" type="text" readonly value="<?php echo $prd['nombre_trb']; ?>" id="nombres" size="50" class="required" /></td>
                        </tr>	
			
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><input name="agregar" type="submit" id="agregar" value="Seleccionar" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <table id="grilla" class="lista">
              <thead>
                    <tr>
   					 <th style="display: none" >idopcion</th>                       
                        <th>Descripcion</th>
                        <th>Imagen</th>
                        <th>Opcion</th>
                        <th>Seleccion</th> 
                    </tr>
                </thead>
                <tbody>

		  <?php  
                      $cont=1; 
                      $i=0;
                      $band=true;
                      $filas=1;
                      $j=0;
                      while($w = pg_fetch_array($lrr, null, PGSQL_ASSOC)) 
	                     {  $rcont[$j]=$w['nro_grupo'];
	                      	$j++;
                      	 }
                      while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {
                   if ($band)  { 

                   			
            ?> 

                    <tr>
                   		<td style="display: none"><?php echo $reg['idopcion']; ?></td>
			            <td><?php echo $reg['descripcion_regalo']; ?></td>
			            <td><img width="110px" height="90px" src="<?php echo 'regalos/'.$reg['descripcion_regalo'].'.jpg'; ?>"></td>
                        <td style="font-size-adjust: 20px;" align="center" valign="middle" <?php if ($rcont[$i]>1) echo ' rowspan="'.$rcont[$i].'"';  ?> > <?php echo $reg['grupo_opcion']; ?></td>
                        <td align="center" valign="middle" <?php if ($rcont[$i]>1) echo ' rowspan="'.$rcont[$i].'"';  ?> >                  
                     <input type="radio" name="opc" id="opc_<?php echo $cont; ?>" value="<?php echo $reg['grupo_opcion']; ?>" />         
                       </td> 

                    </tr>
                    
		  <?php  
                   $band = false;
                   if ($rcont[$i]==1)
                   		$filas=$rcont[$i];
                   	else 
                   		$filas=1;
                   } else

					{
			?> 			
					<tr>
						<td style="display: none"><?php echo $reg['idopcion']; ?></td>
			            <td><?php echo $reg['descripcion_regalo']; ?></td>
			            <td><img width="110px" height="90px" src="<?php echo 'regalos/'.$reg['descripcion_regalo'].'.jpg'; ?>"></td>
			        </tr>
			<?php 	}
					
					if ($filas==$rcont[$i])
                      { 
                      	$band = true;                      	
                      	$i++;
                      }
                   $filas++;
                   $cont++; } 
             ?> 
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="5"><strong>Cantidad:</strong> <span id="span_cantidad"> <?php echo $cont; ?> </span> Item(s).</td>
                        <td colspan="5">  <a title="Salir" href="../logout.php"><img height="50px" width="47px" src="salir.jpg"> </a> </td>
                    </tr>
                </tfoot>
            </table>
             <input type="hidden" name="items" id="items" value="<?php echo $cont--; ?>" />
            <hr />
           
    
        </div>
    </body>
</html>
 <?php 
pg_free_result($listado);
pg_free_result($listadop);

}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
