<?php 
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require('menu.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Rpt. Morbilidad</title>
        <meta charset="utf-8">
        <!--    ESTILO GENERAL   -->
        <link type="text/css" href="css/estilo.css" rel="stylesheet" />
        <link type="text/css" href="css/barra.css" rel="stylesheet" />
        <!--    ESTILO GENERAL    -->
        <!--    JQUERY   -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="js/funcionesmorbilidad.js"></script> 
           
           
    
        <!--    JQUERY    -->
        <!--    FORMATO DE TABLAS    -->
        <link type="text/css" href="css/demo_table.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables_morb.js"></script>

        <!--    FORMATO DE TABLAS    --> 
       
    </head>
    <body>
       
    <header id="titulo">
      <!--  <h3>SISTEMA DE PAGO TICKET DE ALIMENTACION MATESI S.A.</h3>  -->
      <IMG SRC="images/BannerPrincipal.PNG" width="100%" height="220px" >
    </header>
    
    <?php echo menu(); ?>
    <article id="contenido"></article>    
</body>
</html>
<?php 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='index.php';
</script>
</body>";
}
?>