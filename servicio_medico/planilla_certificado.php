 <?php
function planilla_certificado ($id_consulta)
{
    require("include_conex.php");
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

    $query="SELECT m.fecha, m.nombre_completo, m.ci, m.sexo, m.cargo, m.motivo, c.condicion, c.idmotivo, c.firma_dr FROM v_morbilidad m, v_consulta c WHERE c.uid = m.uid AND m.uid=".$id_consulta;
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL: ".$query);
    $rowmorb = pg_fetch_array($resultado); 

    $apto=$rowmorb['condicion'];
    if ($rowmorb['sexo']=='M')
        $sx='Masculino';
    else
        $sx='Femenino';

    $firma_dr=$rowmorb['firma_dr'];

    $dirser='http://10.50.188.48/servicio_medico/';

    $imgvacia='<img width="40px" height="40px" align="center" src="'.$dirser.'images/check_vacio_1.jpg">';
    $imgv='<img width="40px" height="40px" align="center" src="'.$dirser.'images/check_green_1.jpg">';
    $imgr='<img width="40px" height="40px" align="center" src="'.$dirser.'images/check_red_1.jpg">';

    $idm=$rowmorb['idmotivo'];

    $check1=$imgvacia;
    if ($idm==9)
        if ($apto=='APTO')
            $check1=$imgv;

    $check2=$imgvacia;
    if ($idm==8)
        if ($apto=='APTO')
            $check2=$imgv;            

    $check3=$imgvacia;
    if ($apto=='APTO CON RESTRICCION')
        $check3=$imgv;

    $check4=$imgvacia;
    if ($idm==7)
        if ($apto=='APTO')
            $check4=$imgv;

    $check5=$imgvacia;
    if ($idm==9)
        if ($apto=='NO APTO')
            $check5=$imgr;

    $check6=$imgvacia;
    if ($idm==13)
        if ($apto=='APTO')
            $check6=$imgv;    

    $check7=$imgvacia;
    if ($idm==10)
        if ($apto=='APTO')
            $check7=$imgv;

    $html='';    
     
    $html.='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body><p>&nbsp;</p><TABLE width="100%" border="0" cellspacing="0" cellpadding="0"><TR><TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" width="110px" height="70px" src="'.$dirser.'images/MATESI_logo_1.png"></TD><TD WIDTH="70%"></TD><TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" width="110px" height="70px" src="'.$dirser.'images/logo_1.png"></TD><TD style="vertical-align:middle; text-align:center" WIDTH="10%"><img align="center" src="'.$dirser.'images/logo_serv_med_1.jpg"></TD></TR></TABLE>
     <TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="33%">&nbsp;</td>
        <td width="34%">&nbsp;</td>
        <td width="33%" style="vertical-align:middle; text-align:right;" >FECHA:'.substr($rowmorb['fecha'],0,10).'</td>
     </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="vertical-align:middle; text-align:center"><strong>CERTIFICADO MEDICO</strong></td>
        <td>&nbsp;</td>
      </tr>      
    </TABLE>
    <TABLE width="100%" BORDER="1">
        <tr>
        <td width="100%">          
     <TABLE width="100%" BORDER="0">
        <tr>
        <td NOWRAP>Nombre y Apellidos (del paciente):&nbsp;</td>
        <td colspan="4" NOWRAP>'.$rowmorb['nombre_completo'].'</td>    
        </tr><tr>
        <td width="20%">Cedula:&nbsp;</td>
        <td width="20%">'.$rowmorb['ci'].'</td>
        <td width="20%">&nbsp;</td>
        <td width="20%">Sexo:&nbsp;</td>
        <td width="20%">'.$sx.'</td>
        </tr><tr>
        <td>Cargo:&nbsp;</td>
        <td colspan="4">'.$rowmorb['cargo'].'</td>    
        </tr><tr>
        <td>Empresa:&nbsp;</td>
        <td>BRIQVEN</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr><tr>
        <td>&nbsp;</td>
        <td>EXAMEN:</td>
        <td NOWRAP colspan="2" style="vertical-align:middle; text-align:center;"><strong><u>'.$rowmorb['motivo'].'</u></strong></td>    
        <td>&nbsp;</td>
        </tr><tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>    
        <td>&nbsp;</td>
        </tr><tr>
        <td colspan="5" style="vertical-align:middle; text-align:center">Certifico que en el Examen M&eacute;dico Ocupacional Practicado,';
        if ($apto=='APTO') 
            $html.=' NO'; 
        else 
            $html.=' SI';
        $html.='</td>    
        </tr><tr>
        <td colspan="5" style="vertical-align:middle; text-align:center">Existen hallazgos cl&iacute;nicos que impidan su normal desempe&ntilde;o.</td> 
        </tr><tr>        
        <td colspan="5" style="vertical-align:middle; text-align:center;">&nbsp;</td>           
        </tr><tr>        
        <td colspan="5" style="vertical-align:middle; text-align:center;"><strong>DISPOSICION</strong></td>           
        </tr><tr>        
        <td colspan="5" style="vertical-align:middle; text-align:center;">&nbsp;</td>           
        </tr><tr>
        <td style="vertical-align:middle; text-align:center;" >Apto para el Cargo</td>
        <td style="vertical-align:middle; text-align:center;">'.$check1.'</td>
          
        <td colspan="2" style="vertical-align:middle; text-align:center;">Apto para Disfrute de Vacaciones</td>
        <td style="vertical-align:middle; text-align:center;">'.$check2.'</td>
            
        </tr><tr>
        <td style="vertical-align:middle; text-align:center;">Apto con Restricciones</td>
        <td style="vertical-align:middle; text-align:center;">'.$check3.'</td>
        
        <td colspan="2" style="vertical-align:middle; text-align:center;">Apto para Reintegro de Vacaciones</td>
        <td style="vertical-align:middle; text-align:center;">'.$check4.'</td>       
        </tr><tr>
        <td style="vertical-align:middle; text-align:center;">No Apto para el Cargo</td>
        <td style="vertical-align:middle; text-align:center;">'.$check5.'</td>
         
        <td colspan="2" style="vertical-align:middle; text-align:center;">Apto para el Reintegro de Post Incapacidad</td>
        <td style="vertical-align:middle; text-align:center;">'.$check6.'</td>        
        </tr><tr>
        <td style="vertical-align:middle; text-align:center;">Apto para Egresar</td>
        <td style="vertical-align:middle; text-align:center;">'.$check7.'</td>
        <td colspan="3" style="vertical-align:middle; text-align:center;">&nbsp;</td> 
           
        </tr>
    </TABLE>
        <p>&nbsp;</p>
        <TABLE width="100%" BORDER="0"><tr>
                <td width="30%">&nbsp;</td>
                <td width="40%"><u><img align="center" src="'.$dirser.'images/'.$firma_dr.'"></u></td>
                <td width="30%">&nbsp;</td>
                </tr><tr>
                <td>&nbsp;</td>
                <td style="vertical-align:top; text-align:center;">______________________________</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td style="vertical-align:middle; text-align:center;">Firma del Medico Responsable</td>
                <td>&nbsp;</td>
                </tr>
                
        </TABLE>        
            
    </td>
    </tr>
    </TABLE></body>
</html>';
    pg_free_result($resultado);
    return $html;
}    
?>