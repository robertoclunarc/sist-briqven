<script type="text/javascript" language="javascript" src="./js/jslistadotrabajadores.js"></script>

<script type="text/javascript">
 
function ventanaAct(idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./modificar_trabajadores/index.php?ced='+idp, "Actualizacion", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	$(document).ready(function(){
	$.blockUI();
	setTimeout($.unblockUI(),9999);
    	}); 
     }
 }

function ingresartrabajador(){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./insert/index.php', "Ingresar Trabajador", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	$(document).ready(function(){
	$.blockUI();
	setTimeout($.unblockUI(),9999);
    	}); 
     }
 }

</script>
<?php require_once('./conexion.php');
$cn=  Conectarse();
$listado=  pg_query($cn,"SELECT a.* FROM trabajadores a order by centro_costo, nombres");
 ?>         
<tool1> 
<table >
<tr>
<td><A title="Inicio" href="./index.php">Pag. principal</A></td>
<td><A title="Agregar Nuevo Trabajador" onclick="ingresartrabajador()" href="#">Agregar Trabajador</A></td>  
</tr>
</table>
</tool1>
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_trabajadores">
                <thead>
                    <tr>
			<th>Oper.</th>   
                        <th>Nac.</th>  
                        <th>Cedula</th>                        
                        <th>Nombre(s)</th>
                        <th>Apellido(s)</th>
                        <th>Fec. Nac.</th>
                        <th>Cargo</th>
                        <th>Fec. Ingreso</th>
                        <th>Email</th>
			<th>Correo Def.</th>
                        <th>Siglado</th>
                        <th>Rel. Laboral</th>
                        <th>Tipo Nomina</th>
                        <th>Centro Costo</th>
                        <th>Sist. Horario</th>
                        <th>Turno</th> 
			<th>Estatus</th> 
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                  <tbody>
                     
                    <?php
                    
                  while($reg = pg_fetch_array($listado, null, PGSQL_ASSOC))    
                   { 
				echo '<tr>';
				echo '<td><A id="act'.$reg['cedula'].'" onclick="ventanaAct('.$reg['cedula'].')" title="Actualizacion del Trabajador '.$reg['cedula'].'" href="#"><IMG SRC="images/act.png" WIDTH="20" HEIGHT="20"></A></td>';
                               echo '<td>'.$reg['tipo_documento'].'</td>';                              
                               echo '<td>'.$reg['cedula'].'</td>';
                               echo '<td>'.$reg['nombres'].'</td>';
                               echo '<td>'.$reg['apellidos'].'</td>';
                               echo '<td>'.$reg['fecha_nacimiento'].'</td>';
                               echo '<td>'.$reg['cargo'].'</td>';
                               echo '<td>'.$reg['fecha_ingreso'].'</td>';
                               echo '<td>'.$reg['email'].'</td>';
                               echo '<td>'.$reg['def_correo'].'</td>';
                               echo '<td>'.$reg['siglado'].'</td>';
                               echo '<td>'.$reg['relacion_laboral'].'</td>';
                               echo '<td>'.$reg['tipo_contrato'].'</td>';
                               echo '<td>'.$reg['centro_costo'].'</td>';
                               echo '<td>'.$reg['sistema_horario'].'</td>';
                               echo '<td>'.$reg['turno'].'</td>';  
			       echo '<td>'.$reg['estatus'].'</td>';                               
                               echo '</tr>';
                               
                    }
                    ?>
                <tbody>
            </table>
 </div> 
<?php 
pg_free_result($listado);
?>
