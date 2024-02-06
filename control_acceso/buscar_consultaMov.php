<?php
 session_start();
$txtequipo = isset($_POST["txtequipo"])?urldecode($_POST["txtequipo"]):"NULL";    //
$cboretorno= isset($_POST["cboretorno"])?$_POST["cboretorno"]:"NULL";         //
$cboUser= isset($_POST["cboUser"])?urldecode($_POST["cboUser"]):"NULL";
$desde= isset($_POST["txtfdesde"])?$_POST["txtfdesde"]:"NULL";
$fin= isset($_POST["txtffin"])?$_POST["txtffin"]:"NULL";
$cboMotivos= isset($_POST["cboMotivos"])?$_POST["cboMotivos"]:"NULL";
$pdf= isset($_GET["pdf"])?$_GET["pdf"]:"NULL";

$qry="SELECT * FROM 
  v_movimientos_equipos_retornos
WHERE 
  fecha_hora::date BETWEEN '".$desde."' AND '".$fin."' AND estatus='VALIDADO' ";
     
$where = array();
if (($cboUser!="NULL") && ($cboUser!="null") && ($cboUser!=""))        
  array_push($where,"nombres_agr[ 1 ] like '%" . $cboUser . "%'");

if (($cboretorno!="NULL") && ($cboretorno!="null") && ($cboretorno!=""))
  if ($cboretorno=='NO')
    array_push($where,"retorna='SI' AND fecha_retornada ISNULL");
  else
    array_push($where,"retorna='SI' AND NOT fecha_retornada ISNULL");

if (($txtequipo!="NULL") && ($txtequipo!=""))
  array_push($where,"descripcion ilike '%" . $txtequipo  . "%'");

if (($cboMotivos!="NULL") && ($cboMotivos!="") && ($cboMotivos!="null"))
  array_push($where,"objetivo_movimiento=" . $cboMotivos  . "");

$contelem=count($where);
if ($contelem>0)          
  for ($i=0; $i<$contelem; $i++)
    $qry = $qry . " AND " . $where[$i];      

if ($pdf!="NULL")
{   
    $html=buscar($qry,$desde,$fin);
    if ($html!="No se han encontrado resultados!")
        echo generar_pdf($html,$pdf);
     else
        echo $html;
}
else    
  echo buscar($qry,$desde,$fin);        

function buscar($b, $desde, $fin) {
       require_once('libs/conexion.php');
       $cn=Conectarse();
        
        $res = pg_query($cn,$b) or die("Error en la Consulta SQL: ".$b);
        $contar = pg_num_rows($res);
         
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_accesos_perpro">';
             /* if ($enviar=='SI'){
                $inpt = '<table cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_lista_accesos_perpro">';
              }*/
              $inpt = $inpt.'<thead>
                <tr style="background-color: #F0EAE8;">
                        <th><B>IDM.</B></th>                        
                        <th><B>Fecha</B></th>
                        <th><B>Tipo Mov.</B></th>
                        <th><B>Retorna</B></th>
                        <th><B>Destino</B></th>
                        <th><B>F. Retorno</B></th>
                        <th><B>estatus</B></th> 
                        <th><B>Orden Compra</B></th>                                             
                        <th><B>Cant.</B></th>
                        <th><B>Serial</B></th>
                        <th><B>Descripcion</B></th>
                        <th><B>Nombre Dest.</B></th>
                        <th><B>Nombre Contacto</B></th>
                        <th><B>C.I. Contacto</B></th>
                        <th><B>Operacion</B></th>
                        <th><B>Fecha Retornada</B></th>
                        <th><B>Cant. Retorno</B></th>
                        <th><B>Cant. Restante</B></th>
                        <th><B>Ciclo</B></th>
                        <th><B>Usuarios</B></th>
                        <th><B>Unidades</B></th>
                </tr>
            </thead><tbody>'; $i=1;
              while($row=pg_fetch_array($res)){

                      $registro=str_replace('{', '', $row['nombres_agr']);
                      $registro=str_replace('}', '', $registro);
                      $registro=str_replace('"', '', $registro);
                      $registro=str_replace(',','<br>',$registro);

                      $unidades_agr=str_replace('{', '', $row['unidades_agr']);
                      $unidades_agr=str_replace('}', '', $unidades_agr);
                      $unidades_agr=str_replace('"', '', $unidades_agr);
                      $unidades_agr=str_replace(',','<br>',$unidades_agr);
                    
                    $c1 = $row['idmovimiento'];
                    $c2 = substr($row['fecha_hora'],0,16);
                    $c3 = $row['tipo_movimiento'];                    
                    $c4 = $row['retorna'];
                    $c5 = $row['destino'];
                    $c6 = $row['fecha_retorno'];
                    $c7 = $row['estatus'];
                    $c8 = $row['orden_compra'];                    
                    $c9 = $row['cantidad'];                    
                    $c10 = $row['serial_nro_almacen'];
                    $c11 = $row['descripcion'];
                    $c12 = $row['nombre_destinatario'];
                    $c13 = $row['nombre_contacto'];
                    $c14 = $row['cedula_contacto'];
                    $c15 = $row['descripcion_operacion'];
                    $c16 = $row['fecha_retornada'];
                    $c17 = $row['cantidad_retorno'];
                    $c18 = $row['cantidad_restante'];
                    $c19 = $row['ciclo'];
                    $c20 = $registro;
                    $c21 = $unidades_agr;

                    $color=$i%2==0?'#F0EAE8':'#FCFBFA';
                    $i++;
                    $inpt .='<tr style="background-color: '.$color.';">';
                    $inpt .='<td>'.$c1.'</td>';                    
                    $inpt .='<td>'.$c2.'</td>';
                    $inpt .='<td>'.$c3.'</td>';
                    $inpt .='<td>'.$c4.'</td>';
                    $inpt .='<td>'.$c5.'</td>';
                    $inpt .='<td>'.$c6.'</td>';
                    $inpt .='<td>'.$c7.'</td>';
                    $inpt .='<td>'.$c8.'</td>';
                    $inpt .='<td>'.$c9.'</td>';
                    $inpt .='<td>'.$c10.'</td>';                
                    $inpt .='<td>'.$c11.'</td>';
                    $inpt .='<td>'.$c12.'</td>';
                    $inpt .='<td>'.$c13.'</td>';
                    $inpt .='<td>'.$c14.'</td>';
                    $inpt .='<td>'.$c15.'</td>';
                    $inpt .='<td>'.$c16.'</td>';
                    $inpt .='<td>'.$c17.'</td>';
                    $inpt .='<td>'.$c18.'</td>';
                    $inpt .='<td>'.$c19.'</td>';
                    $inpt .='<td>'.$c20.'</td>';
                    $inpt .='<td>'.$c21.'</td>';
                    $inpt .='</tr>';                        
              }
              $inpt .='<tfoot>
                    <tr>
                        <th><B>Cant. Reg.:</B></th>
                        <th><B>'.$contar.'</B></th>
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
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                    </tr>
                </tfoot>           
              </tbody></table><input id="hddcontar" name="hddcontar" type="hidden" value="'.$contar.'"';                  
        }
return $inpt;

}
function generar_pdf($pageHTML,$nombrepdf){
  require_once('tcpdf/tcpdf.php');
  class MYPDF extends TCPDF {
        //Page header
        public function Header() {          
            $this->SetFont('courier', 'B', 8);              
        }
        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('courier', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
  $pdf = crear_pdf ();
  $pdf->AddPage('L', 'A4');        
  $txt=utf8_encode($pageHTML);        
  $pdf->writeHTML($txt, false, 0, true, true, '');     
  //$pdf->Output($nombrepdf.'.pdf', 'D');
  $dir=dirname(__FILE__)."/ATTACHMENT/".$nombrepdf;
  $pdf->Output($dir, 'F');
  return  $dir;
  // return  $pdf->Output($nombrepdf.'.pdf', 'D');
  //return "1"; 
}    

function crear_pdf (){
      // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Matesi');
  $pdf->SetTitle('Control de Acceso');
  $pdf->SetSubject('');
  $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
  // set default header data
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
  // set header and footer fonts
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  // set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  // set image scale factor
  $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 255)));

  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  // set some language-dependent strings (optional)
  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
  }
  //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetMargins(8, 8, 8, true);
  // set font
  $pdf->SetFont('courier', 'I', 7);

  return $pdf;
}         
?>