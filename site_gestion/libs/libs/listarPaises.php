<?php 
session_start();
?> 
<script type="text/javascript" language="javascript" src="./js/jslistadopaises.js"></script>
<script type="text/javascript"> 

function solicitar(idm,tipomov,estatus){
var r = confirm("Seguro Desea Cambiar a "+estatus+" La "+tipomov+" ID: "+idm+"?");
var control;
if (r == true) {
    dir_url = "confirmar_movimiento_db.php?idm="+idm+"&tipomov="+tipomov+"&sta="+estatus;
    if (estatus=='SOLICITADO')
        {control="pend_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='CONFORMADO')
        {control="sol_"+idm; document.getElementById(control).innerHTML="";}
    if (estatus=='AUTORIZADO')
        {control="aut_"+idm; document.getElementById(control).innerHTML="";}
    {control="null_"+idm; document.getElementById(control).innerHTML="";}
    mostrar(control);
    $.ajax({
       type: "POST",
       url: dir_url,
       data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
       success: function(data)
       {           
           //alert(data); // Mostrar la respuestas del script PHP.
           if (data=="0"){
             if (estatus=='AUTORIZADO')
             {
                ocultar(control);
                alert("El Movimiento de " + tipomov + " Fue "+estatus+" Correctamente!. \n\nSe Envió por E-Mail al Area de Control de Acceso.\n -");
                notificar_vigil(idm,tipomov,estatus);
             }else{
                ocultar(control);
                alert("El Movimiento de " + tipomov + " Fue "+estatus+" Correctamente!. \n\nSe Envió por E-Mail a su Jefe con Firma Autorizada.\n -");
             }
           }else{
                alert("La operación Generó un Error:" + data);
                ocultar(control);
           }            
            //location.reload(); //Recargar la página desde cero.            
       }
     });  
}  
}
/*
   $(document).ready(function() {
        $('#tabla_lista_paises').DataTable({
            responsive: true,
            "order": [[ 1, "desc" ]]
        });

    });
*/



</script>



<?php 
require_once('./conexion.php');
//require_once('../funciones_var.php');
$cn=Conectarse();

 $query="SELECT a.* FROM prd_real_plan a order by a.fecha_reg desc";

$listado=pg_query($cn,$query);
?> 
<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr>
<th> Registros de Produccion Diaria</th>
</tr>
</table>
 <p>&nbsp;</p> 
 <div id="capa1">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_paises">
                <thead>
                    <tr>  
<!--                        <th>Fecha Registrado</th>  -->
                        <th>Fecha Prod.</th>                        
                        <th>Reactor</th>
                        <th>Ton. Reales del Dia</th>
                        <th>Ton. Prog del Dia</th>
                        <th>Dif. Tn. Prog - Tn. Real</th>
                        <th>Tn. Real Acumuladas</th>
                        <th>Tn. Prog. Acumuladas</th> 
                        <th>Dif. Tn. Prog Acum. - Tn. Real Acum.</th>                                             
                        <th>Tn. Ajustadas Programa Original Mens.</th>
                        <th>Tn. Programada Original del Mes</th> 
                        <th>Tn. Planif. Anual. del Mes</th>
                        <th>Variaciones de los Plan.</th>                                                 
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
                      
                   ?>    
                      <tr>                       
                                              
<!--                       <td><?php echo substr ($reg['fecha_reg'],0, 19); ?></td>                       -->
                       <td><?php echo $reg['fecha_produccion']; ?></td>                                             
                       <td><?php echo $reg['cod_linea']; ?></td> 
                       <td><?php echo $reg['tn_real']; ?></td>                               
<!--                       <td><?php echo $reg['tn_real']; ?></td> -->
                       <td><?php echo $reg['tn_prog']; ?></td>
                       <td><?php echo $reg['tn_desvio']; ?></td>
                       <td><?php echo $reg['tn_real_acum']; ?></td>
                       <td><?php echo $reg['tn_prog_acum']; ?></td>
                       <td><?php echo $reg['tn_desvio_acum']; ?></td>
                       <td><?php echo $reg['tn_proy']; ?></td>
                       <td><?php echo $reg['tn_prog_orig']; ?></td>
                       <td><?php echo $reg['tn_plan_mes']; ?></td>
                       <td><?php echo $reg['tn_var_anual']; ?></td>
                      </tr>
                  <?php                                   
                    }
                   ?>
                </tbody>
            </table>
 </div> 
<?php
pg_free_result($listado);
?>
