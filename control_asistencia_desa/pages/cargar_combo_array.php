<?PHP
$combo=$_GET["combo"];
switch ($combo) {
        case "esperanza":
            $var = ["1"=>"23:00 a 07:00", "2"=>"07:00 a 15:00", "3"=>"15:00 a 23:00", "4"=>"07:00 a 12:00 y 13:00 a 16:00"];
           // echo "<option selected value=\"0\">Seleccione el horario</option>";
            echo "<option value=\"0\">Seleccione el horario</option>";
            break;
        case "permiso":
	    $var = ["10"=>"Examenes Pre Vacacional","20"=>"Justificativo","30"=>"Permiso","40"=>"Reposo","50"=>"Suspendido","60"=>"Transporte","70"=>"Vacaciones"];
            //echo "<option selected value=\"0\">Seleccione el tipo de Permiso</option>";
            echo "<option value=\"0\">Seleccione el tipo de Permiso</option>";
            break;
        case "grupo":
	    $var = ["1"=>"A","2"=>"B","3"=>"C","4"=>"D","13"=>"M","12"=>"L","11"=>"K","19"=>"S"];
            //echo "<option selected value=\"0\">Seleccione el Grupo</option>";
            echo "<option value=\"0\">Seleccione el Grupo</option>";
            break;
        default:
            $var = [];
            echo "<option selected value=\"0\"></option>";
            break;

    }
        foreach ($var as $fila => $valor) {
                echo "<option value='". $fila."'" ;
             if ($fila[0] != "")
//              if ($fila == $variable)
                echo ">". $fila." => ".$valor. "</option>";
              else
                echo ">". $fila." => ".$valor. "</option>";
        }
	echo "</select>";

?>
