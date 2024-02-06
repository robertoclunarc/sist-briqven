<?php 
require_once('../libs/conexion.php');
$cn=  Conectarse_posgres();
$listado =  pg_query($cn,"SELECT a.*, replace(a.nombre, '/', ' ') as nombre_trb, b.descripcion_unidad as gerencia, c.*
  FROM trabajadores a LEFT JOIN unidades b ON a.fkunidad = b.idunidad 
   inner join  trabajadores_grales c on a.trabajador=c.trabajador where a.trabajador='".$_GET['cedula']."'");
$cc = pg_fetch_array($listado, null, PGSQL_ASSOC);

$queryunidad="SELECT idunidad, descripcion_unidad from unidades order by idunidad";
$cunidad=  pg_query($cn,$queryunidad);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Actualizar Datos del Trabajador</title>

        <script language="javascript" type="text/javascript" src="jquery-1.3.2.min.js"></script>	
        <script language="javascript" type="text/javascript" src="jquery.validate.1.5.2.js"></script>	
        <script language="javascript" type="text/javascript" src="script2.js"></script>
        <link href="estilo.css" rel="stylesheet" type="text/css" />

    </head>
    
    <body>
        <div id="contenedor">  
            <form action="javascript: fn_agregar();" method="post" id="frm_usu">
	
                <table class="formulario">
                    <thead>
                        <tr>
                            <th colspan="2"><img src="add.png"/> Actualizar Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>Cedula:</td>
                        <td><input name="cedula" readonly type="text" value="<?php echo $cc['trabajador']; ?>" id="cedula" size="10" class="required" /></td>                        
                        <td>Rif:</td>
                        <td><input name="rif" type="text" readonly value="<?php echo $cc['registro_fiscal']; ?>" id="rif" size="10" class="required" /></td> 
                        </tr>
                        <tr> 
                        <td>Nombres:</td>
                        <td><input name="nombres" type="text" readonly value="<?php echo $cc['nombre_trb']; ?>" id="nombres" size="40" class="required" /></td>
                        <td>Sexo:</td>
                        <td><select name="sexo" type="text" id="sexo" class="required">
                              <option <?php if($cc['sexo'] == '1') echo"selected"; ?> value="1">Masculino</option>
                              <option <?php if($cc['sexo'] == '2') echo"selected"; ?> value="2">Femenino</option>                      
                            </select></td>
                        </tr>
                        <tr>
                        <td>Fecha Nac.:</td>
                        <td><input name="fecha_nac" type="text" value="<?php echo $cc['fecha_nacimiento']; ?>" id="fecha_nac" size="10" /></td>
                        <td>Grupo Sanguineo:</td>                         
                        <td><input name="grupo_sanguinio" type="text" value="<?php echo $cc['grupo_sanguinio']; ?>" id="grupo_sanguinio" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Domicilio:</td>
                        <td><input name="domicilio" type="text" value="<?php echo $cc['domicilio']; ?>" id="domicilio" size="40" /></td>
                        <td>Domicilio 2:</td>
                        <td><input name="domicilio2" type="text" value="<?php echo $cc['domicilio2']; ?>" id="domicilio2" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Poblacion:</td> 
                        <td><input name="poblacion" type="text" value="<?php echo $cc['poblacion']; ?>" id="poblacion" size="40" /></td>
                        <td>Provincia:</td>                        
                        <td><input name="estado_provincia" type="text" value="<?php echo $cc['estado_provincia']; ?>" id="estado_provincia" size="40" /></td> 
                        </tr>
                        <tr>
                        <td>Municipio:</td>
                        <td><input name="domicilio3" type="text" value="<?php echo $cc['domicilio3']; ?>" id="domicilio3" size="40" /></td>
                        <td>Cod. Postal:</td>
                        <td><input name="codigo_postal" type="text" value="<?php echo $cc['codigo_postal']; ?>" id="codigo_postal" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Calles Aledañas:</td>
                        <td><input name="calles_aledanas" type="text" value="<?php echo $cc['calles_aledanas']; ?>" id="calles_aledanas" size="40" /></td>
                        <td>Tlf. particular:</td>
                        <td><input name="telefono_particular" type="text" value="<?php echo $cc['telefono_particular']; ?>" id="telefono_particular" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Nro. Seg. Social:</td>
                        <td><input name="reg_seguro_social" type="text" value="<?php echo $cc['reg_seguro_social']; ?>" id="reg_seguro_social" size="40" /></td>
                        <td>Correo:</td>
                        <td><input name="e_mail" type="text" value="<?php echo $cc['e_mail']; ?>" id="e_mail" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Gerencia:</td>
                        <td><select name="fkunidad" type="text" id="fkunidad" class="required">
                        <?php                     
                            while($ccu = pg_fetch_array($cunidad, null, PGSQL_ASSOC))    
                           {   
                        ?>
                              <option <?php if($ccu['idunidad'] == $cc['fkunidad']) echo"selected"; ?> value="<?php echo $ccu['idunidad']; ?>"><?php echo $ccu['descripcion_unidad']; ?></option>
                        <?php  
                            }
                        ?>  
                            </select></td>
                        <td>Fecha Ingreso:</td>
                        <td><input name="fecha_ingreso" type="text" value="<?php echo $cc['fecha_ingreso']; ?>" id="fecha_ingreso" size="10" class="required" /></td>
                        </tr>
                        <tr>
                        <td>Fecha Antiguedad:</td>
                        <td><input name="fecha_antiguedad" type="text" value="<?php echo $cc['fecha_antiguedad']; ?>" id="fecha_antiguedad" size="10"  /></td>
                        <td>Fecha baja:</td>
                        <td><input name="fecha_baja" type="text" value="<?php echo $cc['fecha_baja']; ?>" id="fecha_baja" size="10"  /></td>
                        </tr>
                        <tr>
                        <td>F. venc. contrato:</td>
                        <td><input name="fecha_vto_contrato" type="text" value="<?php echo $cc['fecha_vto_contrato']; ?>" id="fecha_vto_contrato" size="10" /></td>
                        <td>Rel. Laboral:</td>
                        <td><select name="relacion_laboral" type="text" id="relacion_laboral" class="required">
                              <option <?php if($cc['relacion_laboral'] == 'B') echo"selected"; ?> value="B">Convenio</option>
                              <option <?php if($cc['relacion_laboral'] == 'W') echo"selected"; ?> value="W">Conduccion</option>
                              <option <?php if($cc['relacion_laboral'] == 'E') echo"selected"; ?> value="E">Contratado</option>
                            </select></td>
                        </tr>
                        <tr>
                        <td>Clase Nomina:</td>
                        <td><select name="clase_nomina" type="text" id="clase_nomina" class="required">
                              <option <?php if($cc['clase_nomina'] == 'ME') echo"selected"; ?> value="1">Convenio</option>
                              <option <?php if($cc['clase_nomina'] == 'PA') echo"selected"; ?> value="2">Conduccion</option>
                            </select></td>
                       
                        <td>Sistema Antig.:</td>
                        <td><input name="sistema_antiguedad" type="text" value="<?php echo $cc['sistema_antiguedad']; ?>" id="sistema_antiguedad" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Sist. Horario:</td>
                        <td><input name="sistema_horario" type="text" value="<?php echo $cc['sistema_horario']; ?>" id="sistema_horario" size="40" /></td>                                               
                        <td>Turno:</td>
                        <td><input name="turno" type="text" value="<?php echo $cc['turno']; ?>" id="turno" size="40" /></td>
                        </tr>
                        <tr>
                        <td>Situacion:</td>                         
                        <td><select name="sit_trabajador" type="text" id="sit_trabajador" class="required">
                              <option <?php if($cc['sit_trabajador'] == 1) echo"selected"; ?> value="1">Activo</option>
                              <option <?php if($cc['sit_trabajador'] == 2) echo"selected"; ?> value="2">Baja</option>
                            </select></td>
                        <td>  </td>
                        </tr>
                                               
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><input name="agregar" type="submit" id="agregar" value="Actualizar" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>            
            <hr />
           
    
        </div>
    </body>
</html>
 <?php 
pg_free_result($listado);
pg_free_result($cunidad);
?>
