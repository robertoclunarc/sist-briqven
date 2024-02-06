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
<title>Listado de Para-M&eacute;dicos</title>

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

require("include_conex.php");

//$cedula= isset($_GET["cedula"])?$_GET["cedula"]:"";            //Condicion 

 
$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexin: " . pg_last_error());
//echo "<h3>Conexion FUE Exitosa PHP - PostgreSQL</h3><hr><br>";

$query = "set datestyle = 'SQL, DMY'; select * from tbl_paramedicos";

//echo $query;


$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL:" . $query);

$numReg = pg_num_rows($resultado);

echo '<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:11px;padding:1px 1px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yw4l{vertical-align:top}
</style>';

echo "<label>LISTADO DE PARA-M&Eacute;DICOS</label>";
echo "<table class='tg'>";
echo "<tr><td width='20%'><label>ID</label></td><td width='20%'><label>C&Eacute;DULA<label></a></td><td width='30%'><label>NOMBRE</label></td><td width='20%'><label>ESTATUS</label></td></tr>";
	
if($numReg>0){


	while ($fila=pg_fetch_array($resultado)) {
		echo "<tr><td>". "<a href='paramedico_nuevo.php?modo=M&cedula=" .$fila["ci"]. "'>" . $fila["uid"]  . "</a>" . "</td><td>" . "<a href='paramedico_nuevo.php?modo=M&cedula=" .$fila["ci"]. "'>" . $fila["ci"] . "</a>". "</td><td>" . $fila["nombre"] .  "</td><td>";
		
		if ($fila["activo"]=='t')
		{ 
		 echo ("Activo"); }else {echo( "Inactivo");
		}

	} // while

} //if

	echo "</table>";
	
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