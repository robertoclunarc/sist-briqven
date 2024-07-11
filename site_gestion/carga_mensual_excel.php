<?php 
session_start();
if (isset($_SESSION['user_session_sio'])){
    require('libs/menu.php');  
    require_once('funciones_var.php');
    //echo date('Y-m-d',strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));  
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Reg. Produccion</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Bootstrap -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/barra.css" rel="stylesheet">
    <!-- Bootstrap Dialog -->
    <link href="css/estilo.css" rel="stylesheet">
  
    <!-- <link href="css/bootstrap-dialog.css" rel="stylesheet">-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap-dialog.js"></script>
     <!-- Validacion de Fechas -->

<script  language="javascript">
//(*--------------------------------------------*)

function GuardarRegistro()
{
 //Funcion encargada de enviar el archivo via AJAX
  $("#ver").text('Cargando...');        

    var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
    var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
    //Creamos una instancia del Objeto FormDara.
    var archivos = new FormData();
    /* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
    Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como 
    indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
    for(i=0; i<archivo.length; i++)
    {
      archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
    }     
              
  $.ajax({
    url: "registrar_archivos_db.php",        // Url to which the request is send
    type: "POST",             // Type of request to be send, called as method
    data: archivos,         // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false,       // The content type used when sending data to the server.
    cache: false,             // To unable request pages to be cached
    processData:false,        // To send DOMDocument or non processed data file it is set to false
    success: function(data)   // A function to be called if request succeeds
    {
      $("#ver").html(data);     
    }
  });
}
//(*--------------------------------------------*)

</script>
</head>
<body>
  <header id="titulo">      
      <IMG SRC="images/sitegestion.jpg" width="100%" height="220px" >
  </header>
<div id="barramenu">
<?php echo menu(); ?>
</div>
<section id='s1'>
<article id="consulta" title='Registro de Acceso'>
<!-- AQUI EL CONTENIDO -->
<?php
$fecha = date('Y-m-d');
$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
?>
<form id="formulario" method='post'>
  <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session_sio']; ?>'/>
  <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel_sio']; ?>'/>
  <input  name='apertura_archivo' id='apertura_archivo' type='hidden' value='2'/>
  <input  name='accion' id='accion' type='hidden'/>
<table width="80%" border="0">
<h4>Programaci&oacute;n mensual</h4>      
    <tr>      
      <td><label>Archivo:</label></td>
      <td><input type="file" class="form-control" id="archivos" name="archivo" multiple="false"> </td>
      <td>&nbsp;</td>
      
    </tr>    
</table>
<p>&nbsp;</p>    
<table width="80%" border="1">
  <label>Ejemplo de como debe estar estructurado el archivo:</label>
      <tr>      
      <td>Fila/Colunma</td>
      <td align="center"><b>A</b></td>
      <td align="center"><b>B</b></td>
      <td align="center"><b>C</b></td>
      <td align="center"><b>D</b></td>
      <td align="center"><b>E</b></td>            
      <td align="center"><b>F</b></td>
      <td align="center"><b>G</b></td>
      <td align="center"><b>H</b> </td>
      <td align="center"><b>I</b></td>
      <td align="center"><b>J</b></td>
      <td align="center">...</td>
      <!--<td>&nbsp;</td>-->
    </tr> 
    <tr>      
      <td align="center">27</td>
      <td>Día</td>
      <td>Fecha</td>
      <td>Tiempo Disponible</td>
      <td>Parada Programada</td>
      <td>Demoras</td>            
      <td>Tiempo efectivo</td>
      <td>Productividad</td>
      <td>Producción </td>
      <td>finos de HBC</td>
      <td>Produccion de 1°</td>
      <td>...</td>
      <!--<td>&nbsp;</td>-->
    </tr>    
    <tr align="center">  
      <td>28</td>    
      <td>Lunes</td>
      <td>2024-01-01</td>
      <td align="center">0</td>
      <td></td>
      <td align="center">0</td>
      <td align="center">0</td>
      <td align="center">0</td>
      <td align="center">-</td>      
      <td align="center">-</td>
      <td align="center">-</td>
      <td>...</td>
    </tr> 
    <tr align="center">      
      <td align="center">29</td>
      <td>Martes</td>
      <td>2024-01-02</td>
      <td align="center">0</td>
      <td></td>
      <td align="center">0</td>
      <td align="center">0</td>
      <td align="center">0</td>
      <td align="center">-</td>      
      <td align="center">-</td>
      <td align="center">-</td>
      <td>...</td>
    </tr>    
    <tr align="center">  
      <td>30</td>    
      <td>Lunes</td>
      <td>2024-01-03</td>
      <td align="center">24</td>
      <td></td>
      <td align="center">1,68</td>
      <td align="center">22,32</td>
      <td align="center">40</td>
      <td align="center">893</td>      
      <td align="center">80,35</td>
      <td align="center">812</td>
      <td>...</td>
    </tr> 
    <tr align="center">      
      <td align="center">31</td>
      <td>Martes</td>
      <td>2024-01-04</td>
      <td align="center">24</td>
      <td></td>
      <td align="center">1,68</td>
      <td align="center">22,32</td>
      <td align="center">45</td>
      <td align="center">1004</td>      
      <td align="center">90,4</td>
      <td align="center">914</td>
      <td>...</td>
    </tr>    

    <tr>      
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>      
      <td align="center">...</td>
      <td align="center">...</td>
      <td align="center">...</td>
    </tr>   
    <tr align="center">      
      <td align="center">57</td>
      <td>Martes</td>
      <td>2024-01-30</td>
      <td align="center">24</td>
      <td></td>
      <td align="center">1,68</td>
      <td align="center">22,32</td>
      <td align="center">45</td>
      <td align="center">1004</td>      
      <td align="center">90,4</td>
      <td align="center">914</td>
      <td>...</td>
    </tr>    
    <tr align="center">      
      <td align="center">58</td>
      <td>Miercoles</td>
      <td>2024-01-31</td>
      <td align="center">24</td>
      <td></td>
      <td align="center">1,68</td>
      <td align="center">22,32</td>
      <td align="center">45</td>
      <td align="center">1004</td>      
      <td align="center">90,4</td>
      <td align="center">914</td>
      <td>...</td>
    </tr>  
</table>
<p>&nbsp;</p>    
<div id="ver" class="col-lg-12"></div>
<table class="" width="100%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button"  value="Registrar"  class="btn btn-success" onclick="GuardarRegistro();"/></td>
    <td width="30%"><div id='ver'></div></td>
  </tr>
</table>
<p>&nbsp;</p>
</article>
</form>

</article>
</section>
</body>
</html>
 <?php 

}else{
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
