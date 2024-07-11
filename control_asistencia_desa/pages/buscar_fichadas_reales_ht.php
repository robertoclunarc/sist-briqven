<?php
 session_start();
 if (isset($_SESSION['user_session_const'])){
    include("../BD/conexion.php");
    require_once('funciones_var.php');
    $finicio= isset($_POST["txtfinicio"])?$_POST["txtfinicio"]:"NULL";         //
    $ffin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
    $trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";
    //$nombre= isset($_GET["nombre"])?$_GET["nombre"]:"NULL";
    $ccosto= isset($_POST["cboccosto"])?$_POST["cboccosto"]:"NULL";

    $link2=Conex_Contancia_pgsql();
    $acceso=permiso_usuario($link2, 'TODO', 'ver_todos_los_trabajadores', $_SESSION['user_session_const']);
    pg_close($link2);

    $qry="select cedula, nombres, fecha, entrada_real1, salida_real1,  entrada_real2, salida_real2 ,
     entrada_esperada1, salida_esperada1,  entrada_esperada2, salida_esperada2,
     horasnetapresencia, horasnetaausencia, HorasNetSica,  b.centro_costo, b.desc_ccosto, b.desc_puesto, a.Cod_ausencia, a.CodError from sw_hoja_de_tiempo_real a, adam_datos_personales b
     where a.cedula=b.trabajador and fecha between '".$finicio."' and '".$ffin."' ";

     
     if (!$acceso){   
        $lista_trabajadores=lista_trabajadores('ct');
        $lista_trabajadores = str_replace("'", "", $lista_trabajadores); 
     }

     if ($trabajador!="NULL")
        $qry.="and cedula=".$trabajador." ";
     elseif (!$acceso && $_SESSION['nivel_const']!=1) {
        $qry.=" and b.trabajador in (".$lista_trabajadores.") ";
     } 

     if ($ccosto!="NULL")
       $qry.=" and b.centro_costo=".$ccosto." ";
     elseif ($_SESSION['nivel_const']>1 && !$acceso && $ccosto=="NULL") {
        $qry.=" and b.centro_costo in (select distinct centro_costo from adam_datos_personales where trabajador_sup=".$_SESSION['cedula_session_const'].") ";
     }

     $qry.=" order by cedula,fecha";
    //echo $qry;
     buscar($qry);     
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}   
           
    function buscar($b) {
           //include("../BD/conexion.php");
           $cn=Conectarse_sitt();
            
            $stmt1 = $cn->query($b);
            //$stmt1 = $cn->prepare($b, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            //$stmt1->execute();
            $contar = $stmt1->columnCount(); 
           //$contar=1;        
            if($contar == 0){
                  $inpt = "No se han encontrado resultados!";
                  
            }else{
                  $inpt = '<table border="1" width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example" cellspacing="0">';
                  
                  $inpt = $inpt.'<thead>
                  
                <tr>
                    <th width="5%" class="info">Cedula</th>
                    <th width="5%" class="info">Nombre</th> 
                    <th width="5%" class="info">Puesto</th>
                    <th width="5%" class="info">Desc. CCosto</th>
                    <th width="5%" class="info">Fecha</th> 
                    <th width="5%" class="info">Ent. Real 1</th>              
                    <th width="5%" class="info">Sal. Real 1</th>  
                    <th width="5%" class="info">Ent. Real 2</th>              
                    <th width="5%" class="info">Sal. Real 2</th>
                    <th width="5%" class="info">Ent. Esp. 1</th>              
                    <th width="5%" class="info">Sal. Esp. 1</th>
                    <th width="5%" class="info">Ent. Esp. 2</th>              
                    <th width="5%" class="info">Sal. Esp. 2</th>
                    <th width="5%" class="info">H.P.</th>
                    <th width="5%" class="info">H.A.</th>
                    <th width="5%" class="info">H.Neta</th>
                    <th width="5%" class="info">Cod. Aus.</th>
                    <th width="5%" class="info">CodError</th>               
                </tr>
            </thead>
            <tbody>';
                  $contar=0;
                  $mayor_a=0;
                  $menor_a=0;
                  $contAus=0;
                  $contPres=0;
                  $contNeta=0;             
                  $libres = array('LL:LL', 'VV:VV', 'RR:RR', 'CS:CS', 'PP:PP', 'SD:SD', 'FF:FF');
                 while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                        
                        $contar++;

                        $clase="label label-info";
                        $porc=$row['entrada_real1'];
                        $pord=$row['salida_real1'];
                        $fecha = substr($row['fecha'], 0,10);
                        if ($porc=='' || $porc=='NULL' || is_null($porc))
                        {
                            if (!in_array($row['entrada_esperada1'], $libres))
                            {  
                              $clase="label label-danger";
                              $menor_a++;
                              $porc="--";
                              $pord="--";
                            }  
                        }    
                        else
                        { 
                          $mayor_a++;
                        }

                        $resultado1=mostrar_esperanza($row['entrada_esperada1']);
                        $resultado2=mostrar_esperanza($row['salida_esperada1']);
                        $resultado3=mostrar_esperanza($row['entrada_esperada2']);
                        $resultado4=mostrar_esperanza($row['salida_esperada2']);

                        $inpt .='<tr>';                     
                        $inpt .='<td>'.$row['cedula'].'</td>'; 
                        $inpt .='<td>'.$row['nombres'].'</td>';
                        $inpt .='<td>'.$row['desc_puesto'].'</td>';
                        $inpt .='<td>'.$row['desc_ccosto'].'</td>';
                        $inpt .='<td>'.$fecha.'</td>';
                        $inpt .='<td><span class="'.$clase.'">'.$porc.'</span></td>';
                        $inpt .='<td><span class="'.$clase.'">'.$pord.'</span></td>';
                        $inpt .='<td>'.$row['entrada_real2'].'</td>';
                        $inpt .='<td>'.$row['salida_real2'].'</td>';
                        $inpt .='<td><span class="'.$resultado1[0].'">'.$resultado1[1].'</span></td>';
                        $inpt .='<td><span class="'.$resultado2[0].'">'.$resultado2[1].'</span></td>';
                        $inpt .='<td><span class="'.$resultado3[0].'">'.$resultado3[1].'</span></td>';
                        $inpt .='<td><span class="'.$resultado4[0].'">'.$resultado4[1].'</span></td>';
                        $inpt .='<td>'.round($row['horasnetapresencia'],2).'</td>';
                        $inpt .='<td>'.round($row['horasnetaausencia'],2).'</td>'; 
                        $inpt .='<td>'.round($row['HorasNetSica'],2).'</td>';
                        $inpt .='<td>'.$row['Cod_ausencia'].'</td>';
                        $inpt .='<td>'.$row['CodError'].'</td>';                  
                        $inpt .='</tr>';
                        $contNeta+=$row['HorasNetSica'];
                        $contAus+=$row['horasnetaausencia'];
                        $contPres+=$row['horasnetapresencia'];                        
                  } 
                 
                  $inpt .=' </tbody>
                  <tfoot>
                        <tr>
                            <th><span class="label label-success">Total:</span></th>
                            <th><span class="label label-success">'.$contar.'</span></th>
                            <th><span class="label label-info">Asist.</span></th>
                            <th><span class="label label-info">'.$mayor_a.'</span></th>
                            <th><span class="label label-danger">Resto:</span></th>
                            <th><span class="label label-danger">'.$menor_a.'</span></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <th></th> 
                            <th></th>  
                            <th></th>
                            <th></th>                          
                            <th><span class="label label-success">'.$contPres.'</span></th>
                            <th><span class="label label-success">'.$contAus.'</span></th>
                            <th><span class="label label-success">'.$contNeta.'</span></th>
                            <th></th>
                            <th></th> 
                        </tr>
                    </tfoot>
                    </table>';
            }
    $cn=NULL;
    $stmt1=NULL;        
    echo $inpt;

    }     
?>