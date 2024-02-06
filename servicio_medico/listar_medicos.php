<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
    require('piedepagina.php');
    require('include_conex.php'); 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Listado de M&eacute;dicos</title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet"> 
</head>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
<header id="titulo">      
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
 </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>

<section id='s1'>
<article id="consulta" >

<!-- AQUI EL CONTENIDO
-->

<?PHP

//$cedula= isset($_GET["cedula"])?$_GET["cedula"]:"";            //Condicion 

 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

$query = "set datestyle = 'SQL, DMY'; select * from tbl_medicos";

//echo $query;


$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

$numReg = pg_num_rows($resultado);
echo '<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:11px;padding:1px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yw4l{vertical-align:top}
</style>';

echo "<label>LISTADO DE M&Eacute;DICOS</label>";
$tablamedicos="";
$tablamedicos = $tablamedicos."<table class='tg'>";
$tablamedicos = $tablamedicos."<tr>";
$tablamedicos = $tablamedicos."<td width='20%'><label>ID</label></td>";
$tablamedicos = $tablamedicos."<td width='20%'><label>C&Eacute;DULA</label></a></td>";
$tablamedicos = $tablamedicos."<td width='30%'><label>NOMBRE</label></td>";
$tablamedicos = $tablamedicos."<td width='20%'><label>ID SSOCIAL</label></td>";
$tablamedicos = $tablamedicos."<td width='20%'><label>ESTATUS</label></td>";
$tablamedicos = $tablamedicos."<td width='20%'><label>Usuario</label></td>";
$tablamedicos = $tablamedicos."</tr>";
	
if($numReg>0){

	while ($fila=pg_fetch_array($resultado)) {
		$tablamedicos = $tablamedicos."<tr>";
		$tablamedicos = $tablamedicos."<td>" . $fila["uid"] . "</td>";
        $tablamedicos = $tablamedicos."<td>" . "<a href='medico_nuevo.php?modo=M&cedula=".$fila["ci"]."'>" . $fila["ci"] . "</a>". "</td>";
        $tablamedicos = $tablamedicos."<td>" . $fila["nombre"] .  "</td>";
        $tablamedicos = $tablamedicos."<td>" . $fila["id_ss"] . "</td>";       
		if ($fila["activo"]=='t')		 
		     $tablamedicos = $tablamedicos."<td>Activo</td>";
        else 
            $tablamedicos = $tablamedicos."<td>Inactivo</td>";
		$tablamedicos = $tablamedicos."<td>".$fila["login"]."</td>";
        $tablamedicos = $tablamedicos. "</tr>";
	} // fin del while

} // fin del if
$tablamedicos = $tablamedicos. "</table>";
echo $tablamedicos;	
pg_close($conexion);
?>
</article>

</section>
        
 <?php piedepagina(); ?> 
<body>
</body>
</html>
<?php 
}
else{
    //header('Location: /login/index.php');
echo "<html>
<body>
<script type='text/javascript'>
window.location='index.php';
</script>
</body>
</html>
";
}
?>

