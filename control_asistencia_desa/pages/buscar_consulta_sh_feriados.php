<?php
session_start();
if (isset($_SESSION['user_session_const'])){
  $finicio= isset($_GET["finicio"])?$_GET["finicio"]:$_POST["txtfinicio"];         //
  $ffin= isset($_GeT["ffin"])?$_GET["ffin"]:$_POST["txtffin"];
  $trabajador= isset($_POST["cbotrabajador"])?$_POST["cbotrabajador"]:"NULL";  

  buscar($finicio, $ffin, $trabajador);
}else{
  echo "Debe Iniciar Sesion para Consultar";
}       
       
function buscar($finicio, $ffin, $trabajador) {
       include("../BD/conexion.php");
       $cn=Conectarse_sitt();
       $qryFeriados="select FECHA from ADAM_FERIADOS where FECHA between '".$finicio."' and '".$ffin."' 
  ORDER BY fecha";        
        $stmt1 = $cn->query($qryFeriados);        
        $contar = $stmt1->columnCount(); 
             
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
             $inpt = '<th>Feriados</th>';              
             $inpt .= '<th>';
             $param="";
             while($row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
                     
                $param.=substr($row['FECHA'], 0, 10)."; ";
                                        
              } 
              $inpt.='<INPUT readonly type="text" name="txtferiados" value="'.$param.'" class="form-control"/>';
              $inpt .=' </th>';

        }
        $qrySH="select a.SISTEMA_HORARIO from ADAM_DATOS_PERSONALES a where TRABAJADOR=".$trabajador;        
        $stmt1 = $cn->query($qrySH);
        $row = $stmt1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
        $inpt .= '<th>Sistema Horario</th>';              
        $inpt .= '<th>'.'<INPUT readonly type="text" name="txtSH" value="'.$row['SISTEMA_HORARIO'].'" class="form-control"/></th>';

$stmt1=null;
$cn=null;        
echo $inpt;

}         
?>
