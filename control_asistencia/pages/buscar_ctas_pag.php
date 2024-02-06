<?php
$desde= isset($_POST["txtdesde"])?$_POST["txtdesde"]:date("Y-m-d",time()-3600);
$hasta= isset($_POST["txthasta"])?$_POST["txthasta"]:date("Y-m-d",time()-3600);
$estatus= isset($_POST["cboestatus_ctapag"])?$_POST["cboestatus_ctapag"]:"";
$vendedor= isset($_POST["cbofkvendedor"])?$_POST["cbofkvendedor"]:"null";
$tipo_deuda= isset($_POST["cbotipo_deuda"])?$_POST["cbotipo_deuda"]:"";

if ($estatus!=""){
  $estatus=" and estatus_ctapag='".$estatus."' ";
}
if ($vendedor!="null")
  $vendedor=" and fk=".$vendedor." and tipo_deuda='VENDEDOR' ";
else
   $vendedor="";
if ($tipo_deuda!=""){
  $tipo_deuda=" and tipo_deuda='".$tipo_deuda."' ";
}
buscar($desde, $hasta, $estatus, $vendedor, $tipo_deuda);

function buscar($desde, $hasta, $estatus, $vendedor, $tipo_deuda) {
     include("../BD/conexion.php");
     $link=crearConexion();
     $sql = "SELECT * FROM vw_cuentas_pagar WHERE fecha between '".$desde."' and '".$hasta."'";
     $sql.=$estatus;
     $sql.=$vendedor;
     $sql.=$tipo_deuda;
     $sql.=" ORDER BY fecha DESC;";
     $result = $link->query($sql);
     if ($result->num_rows > 0){ 
          $tabla='<table width="100%" class="table table-striped table-bordered table-hover" >';
          $tabla.='<thead>';
          $tabla.='<tr>'; 
          $tabla.='<th>Sel.</th>';
          $tabla.='<th>Fecha</th>'; 
          $tabla.='<th>ID</th>';
          $tabla.='<th>Deuda</th>'; 
          $tabla.='<th>Acreedor</th>';
          $tabla.='<th>Total</th>';
          $tabla.='<th>Credito</th>';
          $tabla.='<th>Debito</th>';          
          $tabla.='<th>Estatus</th>'; 
          $tabla.='</tr>';
          $tabla.='</thead>';
          $tabla.='<tbody>';

          $i=1;
          $total=0;
          $debe=0;
          $haber=0;
            while($reg=$result->fetch_assoc()){                  
                  $tipo="'".$reg['tipo_deuda']."'";
                  $tabla.='<tr class="gradeA">';
                  $tabla.='<td>';
                  if ($reg['estatus_ctapag']=='POR PAGAR'){
                    $tabla.='<A href="#" onclick="enviar('.$reg['fkdeuda'].', '.$tipo.')" title="Pagar Al Vendedor"><IMG SRC="images/note.png" WIDTH="20px" HEIGHT="20px"></A>';
                  }
                  if ($reg['estatus_ctapag']=='POR PAGAR' && $reg['tipo_deuda']=='PEDIDO'){
                      $tabla.='<A href="#" onclick="enviar('.$reg['fkdeuda'].', '.$tipo.')" title="Pagar Pedido"><IMG SRC="images/abonar.png" WIDTH="20px" HEIGHT="20px"></A>';
                  }
                  $tabla.='</td>';
                  $tabla.='<td>'.$reg['fecha'].'</td>';
                  $tabla.='<td>'.$reg['fk'].'</td>';
                  $tabla.='<td>'.$reg['tipo_deuda'].'</td>';
                  $tabla.='<td>'.$reg['descripcion'].'</td>';
                  $tabla.='<td>'.$reg['monto_total'].'</td>';
                  $tabla.='<td>'.$reg['monto_pagar'].'</td>';
                  $tabla.='<td>'.$reg['monto_debe'].'</td>';
                  $tabla.='<td>'.$reg['estatus_ctapag'].'</td>';
                  $tabla.='</tr>';                  
                  $i++;
                  $total+=$reg['monto_total'];
                  $haber+=$reg['monto_pagar'];
                  $debe+=$reg['monto_debe'];
            }

            $tabla.='</tbody>';
            $tabla.='<thead>';
            $tabla.='<tr>';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='<th>&nbsp;</th> ';
            $tabla.='<th>&nbsp;</th>';
            $tabla.='<th>TOTALES:</th>';
            $tabla.='<th class="warning">'.number_format($total,2,',','.').'</th>';
            $tabla.='<th class="warning">'.number_format($haber,2,',','.').'</th>';
            $tabla.='<th class="warning">'.number_format($debe,2,',','.').'</th>';
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