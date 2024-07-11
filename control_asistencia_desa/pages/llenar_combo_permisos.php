<?php 
        //$permisos=array(10=>'Falla de Transporte',20=>'Asistencia Control de Acceso',30=>'Adiestramiento',40=>'Consulta Medico',50=>'Evento',60=>'Permiso Sindical',70=>'Compensatorio',72=>'Reposo u Otros',80=>'Disfrute de Vacaciones',85=>'No procede');
        $permisos=array(10=>'Examenes Pre Vacacional',20=>'Justificativo',30=>'Permiso',40=>'Reposo',50=>'Suspendido',60=>'Transporte',70=>'Vacaciones');

//      $option= "<select name=\"cbopermiso\" onchange=\"limpiar_hora();\" id=\"cbopermiso\" data-width=\"80%\" data-size=\"5\" class=\"selectpicker\" data-hide-disabled=\"false\" data-live-search=\"true\">";
        $option.= "<option selected value=\"0\">Seleccione el Tipo de Ausencia</option>";
        foreach ($permisos as $fila => $valor) {
                $option.= "<option value='". $fila."'>". $valor. "</option>" ;
//              if ($fila[0] != "")
//                $option.= ">". $valor. "</option>";
u//              else
//                $option.= ">". $valor. "</option>";
//              $option.= ">". $fila[0]. "</option>";
        }
echo $option;


?>
