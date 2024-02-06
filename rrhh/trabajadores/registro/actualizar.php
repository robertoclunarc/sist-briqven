<?php
$bqd = $_POST['b'];
if(!empty($bqd)) {
            buscar($bqd);
}

function buscar($b) {
	require_once('../libs/conexion.php');
	$cn =  Conectarse_posgres();     
	$query="SELECT a.trabajador, replace(a.nombre, '/', ' ') as nombre_trb, case when a.sexo='1' then 'M' else 'F' end sexo, fecha_nacimiento,  a.e_mail, b.descripcion_unidad as gerencia, c.fecha_ingreso, c.relacion_laboral, c.clase_nomina,
        CASE WHEN c.sit_trabajador=1 THEN 'ACTIVO' else 'BAJA' end as situacion
  FROM trabajadores a LEFT JOIN unidades b ON a.fkunidad = b.idunidad 
   inner join  trabajadores_grales c on a.trabajador=c.trabajador 
   where a.nombre ilike '%".$b."%' 
   or b.descripcion_unidad ilike '%".$b."%' 
   or a.trabajador ilike '%".$b."%'
   or a.e_mail ilike '%".$b."%' 
   order by c.sit_trabajador, b.idunidad,a.nombre";
	
	$result =  pg_query($cn,$query);
	//print_r( pg_last_error() );
	if (pg_num_rows ($result) == 0)
	{
		echo "No se han encontrado resultados para '<b>".$b."</b>'.";
	}else{
		//echo $query;
		$tabla = "<table id='grilla' class='lista'>
          
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
                <tbody>";

              while($reg = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                $tabla = $tabla."<tr>";
                $tabla = $tabla."<td>".$reg['trabajador']."</td>";
                $tabla = $tabla."<td>".$reg['nombre_trb']."</td>";
                $tabla = $tabla."<td>".$reg['sexo']."</td>";
                $tabla = $tabla."<td>".$reg['fecha_nacimiento']."</td>";
                $tabla = $tabla."<td>".$reg['e_mail']."</td>";
                $tabla = $tabla."<td>".$reg['gerencia']."</td>";
                $tabla = $tabla."<td>".$reg['fecha_ingreso']."</td>";
                $tabla = $tabla."<td>".$reg['relacion_laboral']."</td>";
                $tabla = $tabla."<td>".$reg['clase_nomina']."</td>";
                $tabla = $tabla."<td>".$reg['situacion']."</td>";
                $tabla = $tabla."<td><a onclick='ventanaAct(".$reg['trabajador'].")' title='Actualizar'><img src='note.png' width='30' height='30'/></a></td></tr>";
            	}
            	$tabla=$tabla."</tbody>
                
            </table>";
            echo $tabla;
        }
}
?>