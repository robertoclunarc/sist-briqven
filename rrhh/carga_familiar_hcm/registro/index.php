<?php 
session_start();
if (isset($_SESSION['username'])){
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$listado=  pg_query($cn,"SELECT a.*, (case when a.sexo='1' then 'M' else 'F' end) as sx, date_part('year',age(a.fecha_nacimiento)) as edad FROM carga_familiar_hcm a  WHERE sit_carga=1 AND a.trabajador='".$_SESSION['username']."' order by a.secuencia");
$qr="SELECT replace(nombre, '/', ' ') as nombre_trb,trabajador FROM trabajadores  WHERE trabajador='".$_SESSION['username']."'";
$listadop=  pg_query($cn,$qr);
$prd = pg_fetch_array($listadop, null, PGSQL_ASSOC);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Carga Familiar y HCM</title>
         <script type="text/javascript">
            function validar(edad,p,prt) {
                var chkCar = document.getElementById("maternidad_"+p);

                var ban = true;

                if ((prt=='CONYUGUE') || (prt=='CONYUGE') || (prt=='CONCUBINO(A)') || (prt=='CONCUBINA'))
                    ban=false;
                if (ban)

                     {                      
                        chkCar.checked = false;                      
                        alert("Beneficiario no le corresponde maternidad");
                         return false ;
                    } 
                if (edad >= 45)
                        {                      
                        chkCar.checked = false;                      
                        alert("Beneficiario supera la edad para maternidad");
                        } 
            }
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
                            <th colspan="2"><img src="add.png" /> Registro de Carga Familiar y HCM</th>
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
                            <td colspan="2"><input name="agregar" type="submit" id="agregar" value="Actualizar" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <table id="grilla" class="lista">
              <thead>
                    <tr>
   					 <th style="display: none" >Secuencia</th>  
                      <th style="display: none" >Secuencia</th> 
                        <th>Personas Relacinadas</th>
                        <th>Nombres</th>
                        <th>Sexo</th> 
                        <th>Fec. Nacimiento.</th>
			             <th>Parentezco</th>	
			             <th>HCM</th>
                         <th>Maternidad</th>
                        		
                    </tr>
                </thead>
                <tbody>

		  <?php  
                      $cont=1;
                      while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {   
                  ?> 
                    <tr>
                    <td style="display: none"><?php echo $reg['secuencia']; ?></td>
			            
                         <td style="display: none"><input style="border: none; font-weight: bold; " name="secuencia_<?php echo $cont; ?>" type="text" id="secuencia_<?php echo $cont; ?>" readonly value="<?php echo $reg['secuencia']; ?>" size="1" /> <?php echo $reg['secuencia']; ?></td>
                        <td>
                    <input style="border: none; font-weight: bold; " name="persona_relacionada_<?php echo $cont; ?>" type="text" id="persona_relacionada_<?php echo $cont; ?>" readonly value="<?php echo $reg['persona_relacionada']; ?>" size="10" />

                        </td>			
                        <td><input style="border: none; font-weight: bold; " name="nombre_<?php echo $cont; ?>" type="text" id="nombre_<?php echo $cont; ?>" readonly value="<?php echo $reg['nombre']; ?>" size="50" /></td>
                        <td><?php echo $reg['sx']; ?></td>
                        <td><?php echo $reg['fecha_nacimiento']; ?></td>
                        <td><?php echo $reg['dato_01']; ?></td>
			             <td>
                    
                     <input type="checkbox" name="hcm<_?php echo $cont; ?>" id="hcm_<?php echo $cont; ?>" <?php if ($reg['hcm']==1) echo "checked"; ?> value="<?php echo $reg['hcm']; ?>" />         
                         </td>
			              <td>
                     <input type="checkbox" onclick="validar(<?php echo $reg['edad']; ?>,<?php echo $cont; ?>,'<?php echo $reg['dato_01']; ?>');" name="maternidad_<?php echo $cont; ?>" id="maternidad_<?php echo $cont; ?>" <?php if ($reg['maternidad']==1) echo "checked"; ?> value="<?php echo $reg['maternidad']; ?>" />          
                          </td>
 <td><a class="elimina"><img src="delete.png" /></a></td>
                    </tr>
                    
		  <?php  
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
             <input type="hidden" name="items" id="items" value="<?php echo $cont - 1; ?>" />
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
