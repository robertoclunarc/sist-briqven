<?PHP
$sp = isset($_GET["sp"])?$_GET["sp"]:"";
$campo1 = isset($_GET["cmp1"])?$_GET["cmp1"]:"NULL";         
$campo2 = isset($_GET["cmp2"])?$_GET["cmp2"]:"NULL";		 
$campo3 = isset($_GET["cmp3"])?$_GET["cmp3"]:"NULL";
$campo4 = isset($_GET["cmp4"])?$_GET["cmp4"]:"NULL";

include("../BD/conexion.php");
$mbd=Conectarse_sitt();

		$stmt = $mbd->prepare("EXEC ".$sp." ?, ?, ?, ?");
		$stmt->bindParam(1, $campo1, PDO::PARAM_STR,2);
		$stmt->bindParam(2, $campo2, PDO::PARAM_STR,1);
		$stmt->bindParam(3, $campo3, PDO::PARAM_INT,10);
		$stmt->bindParam(4, $campo4, PDO::PARAM_INT,10);
		
		$stmt->execute();
		$ar_cond = '';
		$ar_docum = '';
		$codigo = '';
		$combocodigo='<select name="cbocodigo" id="cbocodigo" onchange="Ubicacion()" data-width="80%" data-size="5" class="form-control" >';
		    $combocodigo.= '<option value="">';
			$combocodigo.= 'Seleccione la Causa</option>';

			  while ($fila = $stmt->fetch()) {			   
	       		$combocodigo.= '<option value="'. $fila['Cod_Adam'] . '">';
				$combocodigo.= $fila['DESC_COD_HORA']. '</option>';
				
				$codigo.='<input  name="hddcodigoadam[]" type="hidden" value="'.$fila['Cod_Adam'].'"/> ';
				$ar_cond.='<input  name="hddcond[]" type="hidden" value="'.$fila['Condiciones'].'"/> ';
				$ar_docum.='<input  name="hdddoc[]" type="hidden" value="'.$fila['Documentos'].'"/> ';
			 } 
	    $combocodigo.='</select>';
	    echo $combocodigo.$codigo.$ar_cond.$ar_docum;
?>