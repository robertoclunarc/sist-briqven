<?php
$desde= isset($_POST["txtdesde"])?$_POST["txtdesde"]:date("Y-m-d",time()-3600);
$hasta= isset($_POST["txthasta"])?$_POST["txthasta"]:date("Y-m-d",time()-3600);
$estatus= isset($_POST["cboestatus_venta"])?$_POST["cboestatus_venta"]:"";
$vendedor= isset($_POST["cbofkvendedor"])?$_POST["cbofkvendedor"]:"null";
$cliente= isset($_POST["hddfkcliente"])?$_POST["hddfkcliente"]:"";

if ($estatus!=""){
  $estatus=" and estatus='".$estatus."' ";
}
if ($vendedor!="null"){
  $vendedor=" and fkvendedor=".$vendedor." ";
}else
   $vendedor="";
if ($cliente!=""){
  $cliente=" and fkcliente=".$cliente." ";
}
buscar($desde, $hasta, $estatus, $vendedor, $cliente);

function buscar($desde, $hasta, $estatus, $vendedor, $cliente) {
     include("../BD/conexion.php");
     $link=crearConexion();
     $sql = "SELECT * FROM vw_cuentas_cobrar WHERE fecha between '".$desde."' and '".$hasta."'";
     $sql.=$estatus;
     $sql.=$vendedor;
     $sql.=$cliente;
     $sql.=" ORDER BY fecha DESC;";
     $result = $link->query($sql);
     if ($result->num_rows > 0){ 
          $tabla='<table width="100%" class="table table-striped table-bordered table-hover" >';
          $tabla.='<thead>';
          $tabla.='<tr>'; 
          $tabla.='<th>Fecha</th>'; 
          $tabla.='<th>ID Venta</th>';
          $tabla.='<th>Cliente</th>'; 
          $tabla.='<th>Vendedor</th>';
          $tabla.='<th>Monto Total</th>';
          $tabla.='<th>Monto Debe</th>';
          $tabla.='<th>Monto Haber</th>';
          $tabla.='<th>Estatus</th>'; 
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='<tbody>';

          $i=1;
          $total=0;
          $debe=0;
          $haber=0;
            while($reg=$result->fetch_assoc()){                  

                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td>'.$reg['fecha'].'</td>';
                  $tabla.='<td>'.$reg['fkventa'].'</td>';
                  $tabla.='<td>'.$reg['cliente'].'</td>';
                  $tabla.='<td>'.$reg['vendedor'].'</td>';
                  $tabla.='<td>'.$reg['monto_total'].'</td>';
                  $tabla.='<td>'.$reg['monto_debe'].'</td>';
                  $tabla.='<td>'.$reg['monto_haber'].'</td>';
                  $tabla.='<td>'.$reg['estatus'].'</td>';
                  $tabla.='</tr>';                  
                  $i++;
                  $total+=$reg['monto_total'];
                  $debe+=$reg['monto_debe'];
                  $haber+=$reg['monto_haber'];
            }

            $tabla.='</tbody>';
            $tabla.='<thead>';
            $tabla.='<tr>';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='<th>&nbsp;</th> ';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='<th>TOTALES:</th>';
            $tabla.='<th class="warning">'.number_format($total,2,',','.').'</th>';
            $tabla.='<th class="warning">'.number_format($debe,2,',','.').'</th>';
            $tabla.='<th class="warning">'.number_format($haber,2,',','.').'</th>';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='</tr>';
            $tabla.='</thead>';
            $tabla.='</table>';
            $result->free();
            $link->close();
            echo $tabla;
      }else{
         echo "No tiene Ventas Pendientes por Pagar";
      }
}
?>