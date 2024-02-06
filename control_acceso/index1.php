<?php 
session_start();
if (isset($_SESSION['username_ca']) && isset($_SESSION['userid_ca']) ){      
        require('libs/menu.php');    
?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Sist. Control Acceso</title>
                <meta charset="utf-8">
                <!--    ESTILO GENERAL   -->
                <link type="text/css" href="css/estilo.css" rel="stylesheet" />
                <link href="css/barra.css" rel="stylesheet">
                <!--    ESTILO GENERAL    -->
                <!--    JQUERY   -->
                <script type="text/javascript" src="js/jquery.js"></script>
                <script type="text/javascript" language="javascript" src="js/funciones1.js"></script> 
                <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
                <!--    JQUERY    -->
                <!--    FORMATO DE TABLAS    -->
                <link type="text/css" href="css/demo_table.css" rel="stylesheet" />
                <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
                <!--    FORMATO DE TABLAS    -->               
            </head>
            <body>               
            <header id="titulo">
              <!--  <h3>SISTEMA DE PROTECCION DE PLANTA.</h3>  -->
              <IMG SRC="images/Seguridad-Patrimonial-BigOne.png" HEIGHT="300px" width="100%" >
            </header>
             <?php echo menu();
             /*$_SESSION['st']="";
             if (isset($_GET["st"])){
                if ($_GET["st"]=='PEN')
                    $_SESSION['st']=" and estatus='PENDIENTE'";
                elseif ($_GET["st"]=='AUT') {
                    $_SESSION['st']=" and estatus='AUTORIZADO'";
                }
             }*/
             ?>
            <article id="contenido"></article>    
            </body>
        </html>
<?php 
}
else{
        //header('Location: /login/index.php');
        echo "<body>
        <script type='text/javascript'>
        window.location='login/index.php';
        </script>
        </body>";
}
?>
