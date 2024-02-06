<?php 
session_start();
?>
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript">
function ventanaAct(idp){ 
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(500/2); 
    posicion_y=(screen.height/2)-(400/2);   
    var ventana = window.open('./informacion/index.php?idp='+idp, "Informacion", "width=500,height=300,menubar=NO,toolbar=NO,directories=NO,scrollbars=NO,resizable=NO,left="+posicion_x+",top="+posicion_y+"");
    if(!ventana.focus())
	ventana.focus();
 }
function exportar(ventana,parametros){ 
var periodo=document.getElementById("periodo").value;
if (periodo=='')
	alert('Por Favor Seleccione un Periodo');
else{   
    var me = periodo.substring(5, 7);
    var an = periodo.substring(2, 4);
    ventana +="?";
    nomVec = parametros.split(",");
    ventana += nomVec[0] + "=" + me +"&" + nomVec[1] + "=" + an + "&" + nomVec[2];
   // ventana = ventana.substring(0,ventana.length-1);
    location.href=ventana;
 }   
}

function enviar_email(){
respuesta = confirm("Seguro Desea Enviar Comprobantes de Pago a "+document.getElementById("total_correos").value+" Trabajadores?");
 if (respuesta){
  exportar('./formato.php','me,an,ced=0');
 }
}

</script>
<?php require_once('./conexion.php');
$cn=  Conectarse();
$listado=  pg_query($cn,"SELECT a.* FROM pagos_periodos_trabajadores a  WHERE estatusp='CERRADO' order by a.anhop asc, a.mesp asc;");
$contador=  pg_query($cn,"SELECT count(*) as total_correos FROM pagos_periodos_trabajadores a  WHERE estatusp='CERRADO' and a.def_correo='S';");
$pdo=  pg_query($cn,"SELECT '20' || a.anhop || '-' || a.mesp as periodo FROM periodos a  WHERE a.estatusp='CERRADO' order by a.idperiodo Desc");
$totalcorreos = pg_fetch_array($contador, null, PGSQL_ASSOC);
 ?>         
 <tool1>
    <!-- <A title="Imprimir Tickets de Trabajadores Sin Correo del Periodo:" target="_blank" href="formato.php?ced=1&me=&an=">Imprimir TTSC</A>    
    --> 
  <!--   &nbsp;|&nbsp;<A title="Enviar Comprobantes por E-Mail del Periodo: " target="_blank" href="formato.php?ced=0&me=&an=">Enviar E-Mail</A>
  -->   
<table >
<tr>
<td><A title="Listar Proceso Actual de Pagos" href="./index.php">Pagina Principal</A></td>  
<td><A title="Exportar a Excel Resumen de Pago" onclick="exportar('./expor_excel1.php','me,an,ced=1')"  href="#">Exp. a Excel Resumen</A>
<br><A title="Exportar a Excel Detalle de Pago" onclick="exportar('./expor_excel2.php','me,an,ced=1')"  href="#">Exp. a Excel Detalles</A>
</td>                         
<td><A title="Generar Archivo .TXT del Periodo" onclick="exportar('./generartxt.php','me,an,ced=1')" href="#">Generar .txt</A>

</td>
<td><A title="Imprimir Tickets de Trabajadores Sin Correo del Periodo:" onclick="exportar('./formato.php','me,an,ced=1')" href="#">Imprimir TTSC</A></td>
<td><A title="Enviar Comprobantes por E-Mail del Periodo: " onclick="enviar_email()" href="#">Enviar E-Mail</A></td>
<td><select id="periodo" name="periodo">
 <option value="">Seleccionar Periodo</option>
<?php while($rp = pg_fetch_array($pdo, null, PGSQL_ASSOC)){   
 ?>                  { 
        <option value="<?php echo $rp['periodo']; ?>"><?php echo $rp['periodo']; ?></option>
         
<?php } ?>     
       </select></td>
<td><A title="Salir de la Sesion: <?php echo $_SESSION['username'];  ?>" href="./login/logout.php">Cerrar Sesion</A></td> 
 </tr>
 </table>
 </tool1>
 <p> &nbsp; </p>
 <div id="capa1">
<input type="hidden" name="total_correos" id="total_correos" value="<?php echo $totalcorreos['total_correos']; ?>" />
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
                               echo '<td><A id="act'.$idp.'" onclick=" ventanaAct('.$idp.')" title="Detalles de Pago ID '.$idp.'" href="#"><IMG SRC="images/informacion.png" WIDTH="20" HEIGHT="20"></A>'; 
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
pg_free_result($pdo);
pg_free_result($contador);
?>
