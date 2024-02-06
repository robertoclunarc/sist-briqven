<?php 
session_start();
if (isset($_SESSION['username'])){
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$listado=  pg_query($cn,"SELECT a.trabajador, replace(a.nombre, '/', ' ') as nombre_trb, case when a.sexo='1' then 'M' else 'F' end sexo, fecha_nacimiento,        
       e_mail, b.descripcion_unidad as gerencia, c.fecha_ingreso, c.relacion_laboral, c.clase_nomina,
        CASE WHEN c.sit_trabajador=1 THEN 'ACTIVO' else 'BAJA' end as situacion
  FROM trabajadores a LEFT JOIN unidades b ON a.fkunidad = b.idunidad 
   inner join  trabajadores_grales c on a.trabajador=c.trabajador
   order by c.sit_trabajador, b.idunidad,a.nombre");

/*$qr="SELECT replace(nombre, '/', ' ') as nombre_trb,trabajador FROM trabajadores  WHERE trabajador='".$_SESSION['username']."'";
$listadop=  pg_query($cn,$qr);
$prd = pg_fetch_array($listadop, null, PGSQL_ASSOC);
*/
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Lista de Trabajadores</title>
         <script type="text/javascript">
            function ventanaAct(cedula){ 
                var posicion_x; 
                var posicion_y;
                var x=530;
                var y=950; 
                posicion_x=(screen.width/2)+(y/2); 
                posicion_y=(screen.height/2)-(x/2);   
                var ventana = window.open('index1.php?cedula='+cedula, "Actualizacion", "width="+y+",height="+x+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");
                if(!ventana.focus()){
                ventana.focus();
                $(document).ready(function(){
                $.blockUI();
                setTimeout($.unblockUI(),9999);
                    }); 
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
            <form method="post" id="frm_usu">
	
                <table class="formulario"><br />
                    <thead>
                        <tr>
                            <th colspan="2"><img src="add.png" /> Registro de Trabajdores</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <tr>
                            <td>Buscar:</td>
                            <td><input name="busqueda" type="text" value="" id="busqueda" size="50"  /></td>
                        </tr>	
			
                    </tbody>
                   
                </table>
            </form>
            <div id="resultado">
            <table id="grilla" class="lista">
              <thead>
                    <tr>   					 
                         <th>Cedula</th>
                         <th>Nombres</th>
                         <th>Sexo</th> 
                         <th>Fec. Nacimiento.</th>
    		             <th>Correo</th>	
    		             <th>Gerencia</th>
                         <th>Fecha Ingreso</th>
                         <th>Rel. laboral</th>
                         <th>Clase Nom.</th>
                         <th>Situacion</th>                         	
                    </tr>
                </thead>
                <tbody>

		  <?php  
                      $cont=1;
                      while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   {   
                  ?> 
                    <tr>
                    <td><?php echo $reg['trabajador']; ?></td>
                    <td><?php echo $reg['nombre_trb']; ?></td>
                    <td><?php echo $reg['sexo']; ?></td>
                    <td><?php echo $reg['fecha_nacimiento']; ?></td>
			        <td><?php echo $reg['e_mail']; ?></td>    
                    <td><?php echo $reg['gerencia']; ?></td>
                    <td><?php echo $reg['fecha_ingreso']; ?></td>
                    <td><?php echo $reg['relacion_laboral']; ?></td>
                    <td><?php echo $reg['clase_nomina']; ?></td>
                    <td><?php echo $reg['situacion']; ?></td>
                     
                    
 <td><a onclick="ventanaAct(<?php echo $reg['trabajador']; ?>)" title="Actualizar" ><img src="note.png" width="30" height="30" /></a></td>
                    </tr>
                    
		  <?php  
                     $cont++; } 
                  ?> 
                </tbody>
                
            </table>
            </div>
             <input type="hidden" name="items" id="items" value="<?php echo $cont - 1; ?>" />
            <hr/>
           
    
        </div>
    </body>
</html>
 <?php 
pg_free_result($listado);


}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>
