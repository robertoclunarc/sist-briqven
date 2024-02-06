<?php
/*session_start();

if(isset($_SESSION['user_session'])!="")
{
	header("Location: index.php");
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Logear para entrar al sistema</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="validation.min.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" media="screen">
<script type="text/javascript" src="script2.js"></script>

</head>

<body>
    
<div class="signin-form">

	<div class="container">
     
        
       <form class="form-signin" method="post" id="login-form">
      
        <h2 class="form-signin-heading">Registro Usuario</h2><hr />
        
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Cedula" name="cedula" id="cedula" />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Fecha Nacimiento Año-Mes-Dia" name="fecha_nac" id="fecha_nac" />
        </div>

         <div class="form-group">
        <input type="text" class="form-control" placeholder="Fecha Ingreso Año-Mes-Dia" name="fecha_ingreso" id="fecha_ingreso" />
        </div>
       
     	<hr />
        
        <div class="form-group">
            <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
    		<span class="glyphicon glyphicon-log-in"></span> &nbsp; Entrar
			</button> 
        </div>  
        <a href="index.php">Ir atras si ya tiene cuenta de correo</a>
      </form>

    </div>
    
</div>
    
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>