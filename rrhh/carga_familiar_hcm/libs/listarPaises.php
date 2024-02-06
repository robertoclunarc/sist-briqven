<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 
function ventanaAct(idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(800/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./update/index.php?idp='+idp, "Actualizacion", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
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
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(600/2);   
    var ventana = window.open('./insert/index.php','Trabajadores Nuevo Ingreso', "width=850,height=600,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	//$(document).ready(function(){
	//$.blockUI();
	//setTimeout($.unblockUI(),9999);
    	//}); 
     }
 }
function ventananota(idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(450/2);   
    var ventana = window.open('./notas/index.php?idp='+idp,'Notas del Trabajador', "width=850,height=450,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	//$(document).ready(function(){
	//$.blockUI();
	//setTimeout($.unblockUI(),9999);
    	//}); 
     }
 }
    
    function ventananotas(periodo){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(850/2); 
    posicion_y=(screen.height/2)-(450/2);   
    var ventana = window.open('./notas/index1.php?per='+periodo,'Notas del Trabajador', "width=850,height=450,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus()){
	ventana.focus();
	//$(document).ready(function(){
	//$.blockUI();
	//setTimeout($.unblockUI(),9999);
    	//}); 
     }
 }
 
</script>
<?php require_once('./conexion.php');
$cn=Conectarse();
$listado=pg_query($cn,"SELECT a.* FROM pagos_periodos_trabajadores a WHERE estatusp='ABIERTO' and estatus='ACTIVO';");
$periodo_actual=pg_query($cn,"SELECT idperiodo,mesp,anhop FROM periodos  WHERE estatusp='ABIERTO';");
$prd = pg_fetch_array($periodo_actual, null, PGSQL_ASSOC);
$notas=  pg_query($cn,"SELECT count(*) as nronotas, a.cedula, a.idpago, b.leida FROM pagos_periodos_trabajadores a, notas b WHERE b.fkpagonota=a.idpago and estatusp='ABIERTO' group by a.cedula, a.idpago, b.leida");
$contnotas=0;
$rows = pg_num_rows($notas);
if ($rows>0){	
	$pila = array("0");	
	 while($row_notas = pg_fetch_array($notas, null, PGSQL_ASSOC)){
               if ($row_notas['leida']=='N')
			             $contnotas=$row_notas['nronotas']+$contnotas;		
		 array_push($pila, $row_notas['cedula']);        	
	}
}



 ?>         
<tool1> 
<table >
<tr>
<td><A title="Historial de Pagos Periodos Cerrados" target="_blank" href="./index1.php">Ir a Periodos Anteriores</A></td>
<td><A title="Agregar Nuevo Trabajador" onclick="ingresartrabajador()" href="#">Agregar Trabajador</A></td>
<td><A title="Consultar Trabajador(es)" href="trabajadores.php">Ver Trabajador(es)</A></td>  
<td <?php if ($contnotas>0){ echo "style='background-image:url(images/aQ_y73.gif)'"; } ?> ><A onclick="ventananotas(<?php echo $prd['idperiodo']; ?>)" title="Ver Notas del Periodo Activo" href="#">Notas <?php if ($rows>0) echo "(".$contnotas.")"; else echo "(0)"; ?> </A></td>
 
<?php if (($_SESSION['nivel']==1) && ($_SESSION['estatususer']=='ACTIVO')){ ?>
<td><A title="Cerrar Periodo: <?php echo $prd['mesp']."20".$prd['anhop'];  ?>" href="./cierre/">Cerrar Periodo</A></td>
<?php } ?>
<?php if (($_SESSION['nivel']==1) && ($_SESSION['estatususer']=='ACTIVO')){ ?>
<td><A title="Ver/Crear Usuarios del Sistema" href="./u5u4r105/cr34u53r.php">Usuarios</A></td>
<?php } ?>
<td><A title="Salir de la Sesion: <?php echo $_SESSION['username'];  ?>" href="./login/logout.php">Cerrar Sesion</A></td>
</tr>
</table>
</tool1>
 <p> &nbsp; </p>
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr>  
                        <th>Oper.</th>  
                        <th>Periodo</th>                        
                        <th>Cedula</th>
                        <th>Trabajador</th>
                        <th>F. Ingreso</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Rel. Lab.</th>
                        <th>Tipo Nom.</th>
                        <th>CC</th>
                        <th>Sist. Hor.</th>
                        <th>Turno</th>
                        <th>Dias Transc.</th>
                        <th>Monto Estimado</th>
                        <th>Desc.</th>
                        <th>Deudas</th>
                        <th>Total Pagar</th>
                        <th>Estatus Trab.</th>
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
                      $idp=$reg['idpago']; 
                               echo '<tr>';                                
                               echo '<td><A id="act'.$idp.'" onclick="ventanaAct('.$idp.')" title="Actualizacion Pago ID '.$idp.'" href="#"><IMG SRC="images/act.png" WIDTH="20" HEIGHT="20"></A>'; 
				if ($rows>0)	
				if (in_array($reg['cedula'], $pila)) { 
 				echo '<A id="not'.$idp.'" onclick="ventananota('.$idp.')" title="Notas Pago ID '.$idp.'" href="#"><IMG SRC="images/note.png" WIDTH="20" HEIGHT="20"></A>'; 
					}                               
                               echo '<A target="_blank" id="pdf'.$idp.'" title="Descargar PDF Pago ID '.$idp.'" href="formato.php?ced='.$reg['cedula'].'&me='.$reg['mesp'].'&an='.$reg['anhop'].'"><IMG SRC="images/pdf-icon.png" WIDTH="20" HEIGHT="20"></A></td>';
                               echo '<td>'.$reg['periodo'].'</td>';                              
                               echo '<td>'.$reg['cedula'].'</td>';
                               echo '<td>'.$reg['trabajador'].'</td>';
                               echo '<td>'.$reg['fecha_ingreso'].'</td>';
                               echo '<td>'.$reg['cargo'].'</td>';
                               echo '<td>'.$reg['email'].'</td>';
                               echo '<td>'.$reg['relacion_laboral'].'</td>';
                               echo '<td>'.$reg['tipo_contrato'].'</td>';
                               echo '<td>'.$reg['centro_costo'].'</td>';
                               echo '<td>'.$reg['sistema_horario'].'</td>';
                               echo '<td>'.$reg['turno'].'</td>';
                               echo '<td>'.$reg['diast'].'</td>';
                               echo '<td>'.$reg['montoactual'].'</td>';
                               echo '<td>'.$reg['descuento'].'</td>';
                               echo '<td>'.$reg['deudas'].'</td>';
                               echo '<td>'.$reg['total_pagar'].'</td>';
                               echo '<td>'.$reg['estatus'].'</td>';
                               echo '</tr>';
                               
                    }
                    ?>
                <tbody>
            </table>
 </div>
 
 <?php 
pg_free_result($listado);
pg_free_result($periodo_actual);
?>
