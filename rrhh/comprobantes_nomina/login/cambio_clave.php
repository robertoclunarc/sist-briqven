<?php
session_start();
if(isset($_SESSION['user_session'])){
    $user_session=$_SESSION['user_session'];
    $ced=$_SESSION['ced'];
    $pregunta_secreta_1=$_SESSION['pregunta_secreta_1'];
    $respuesta_secreta_1=$_SESSION['respuesta_secreta_1'];
    $pregunta_secreta_2=$_SESSION['pregunta_secreta_2'];    
    $respuesta_secreta_2=$_SESSION['respuesta_secreta_2'];
    $userid=$_SESSION['userid'];
    $login_userpass=$_SESSION['login_userpass'];
    //$cant_entradas=$_SESSION['cant_entradas'];
} else {
    $user_session="";
    $ced="";
    $pregunta_secreta_1="";
    $respuesta_secreta_1="";
    $pregunta_secreta_2="";    
    $respuesta_secreta_2="";
    $userid="";
    $login_userpass="";
    //$cant_entradas=0;
}    
/*
Author: Pradeep Khodke
URL: http://www.codingcage.com/
*/
$preguntas=array('NOMBRE DE TU MASCOTA', 'LUGAR DONDE TE GUSTARIA VIVIR', 'POSTRE FAVORITO', 'BEBIDA ALCOHOLICA FAVORITA?', 'PERSONAJE FAVORITO DE LOS SIMPSONS?', 'CANTANTE O GRUPO FAVORITO?', 'LO MEJOR ES?', 'PERSONA MAS LOCA QUE CONOCES?', 'QUE FOBIAS TIENES?', 'QUE ODIAS HACER?', 'COMO LLAMAS A TU MAMA?', 'ALGUIEN A QUIEN NO HAYAS VISTO POR MUCHO TIEMPO Y TENGAS GANAS DE VER?', 'DIA FAVORITO!', 'QUE LLEVAS SIEMPRE EN TUS BOLSILLOS?');
$cont=count($preguntas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comprob. Nomina - Cambio de Clave</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 

<script src="../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="validation.min.js"></script>
<link href="../css/style_login.css" rel="stylesheet" type="text/css" media="screen">
<script type="text/javascript">
//(*--------------------------------------------*)
function actualizar_datos()
{
if ($("#login").val()=="")
  {
    alert("Ingrese el Login");
    $("#login").focus();
    return; 
  } 
if ($("#email").val()=="")
  {
    alert("Ingrese el Email");
    $("#email").focus();
    return; 
  }
if ($("#pregunta_secreta_1").val()=="")
  {
    alert("Ingrese la Pregunta Secreta 1");
    $("#pregunta_secreta_1").focus();
    return; 
  }
 if ($("#respuesta_secreta_1").val()=="")
  {
    alert("Ingrese la Respeusta Secreta 1");
    $("#respuesta_secreta_1").focus();
    return; 
  }
  if ($("#pregunta_secreta_2").val()=="")
  {
    alert("Ingrese la Pregunta Secreta 2");
    $("#pregunta_secreta_2").focus();
    return; 
  }
  if ($("#respuesta_secreta_2").val()=="")
  {
    alert("Ingrese la Respuesta Secreta 2");
    $("#respuesta_secreta_2").focus();
    return; 
  }
  if ($("#cedula").val()=="")
  {
    alert("Ingrese la Cedula");
    $("#cedula").focus();
    return; 
  } 
  if ($("#password").val()=="")
  {
    alert("Ingrese el Password");
    $("#password").focus();
    return; 
  }       
dir_url = "actualizar_datos.php";
$.ajax({
   type: "POST",
   url: dir_url,
   data: $("#login-form").serialize(), // Adjuntar los campos del formulario enviado.
   success: function(data)
   {       
       if (data>=1){
          <?php session_destroy(); ?>
          alert ('Sus Datos Han Sido Actualizados');
          location.href = "index.php";          
       } else {
           alert ('Surgio un Error. Por Favor Revise Sus Datos e Intente de Nuevo. '+data); 
       }         
   }
 });
}

//(*--------------------------------------------*)
</script>
</head>

<body>    

	<div class="login-form">
       <form class="form-signin" method="post" id="login-form">
             
        <h2 class="sub-head-w3-agileits">Comprobantes de Nomina</h2><hr />        
        <div id="error">
        <!-- error will be shown here ! -->
        </div>        
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Login" value="<?php echo $user_session; ?>" name="login" id="login" />
        <span id="check-e"></span>
        </div>

        <div class="form-group">
        <input type="text" class="form-control" placeholder="Cedula" value="<?php echo $ced; ?>" name="cedula" id="cedula" />
        <span id="check-e"></span>
        </div>

        <div class="form-group">
        
        <select name="pregunta_secreta_1" id="pregunta_secreta_1" class="form-control" >
            <option <?php if ($pregunta_secreta_1=='') echo 'selected'; ?>  value="">[Pregunta Secreta 1]</option>
            <?php for ($i=0; $i<=$cont-1; $i++){ ?>
                <option <?php if ($pregunta_secreta_1==$preguntas[$i]) echo 'selected'; ?>  value='<?php
echo $preguntas[$i];?>'><?php echo $preguntas[$i];?></option>
            <?php } ?>
        </select>    
        <span id="check-e"></span>
        </div>
        <div class="form-group">
        <input type="text" class="form-control"  placeholder="Respuesta Secreta 1" name="respuesta_secreta_1" id="respuesta_secreta_1" value="<?php echo $respuesta_secreta_1; ?>" />
        <span id="check-e"></span>
        </div>
        <div class="form-group">
        <select name="pregunta_secreta_2" id="pregunta_secreta_2" class="form-control" >
            <option <?php if ($pregunta_secreta_2=='') echo 'selected'; ?>  value="NULL">[Pregunta Secreta 2]</option>
            <?php for ($i=0; $i<=$cont-1; $i++){ ?>
                <option <?php if ($pregunta_secreta_2==$preguntas[$i]) echo 'selected'; ?>  value='<?php
echo $preguntas[$i];?>'><?php echo $preguntas[$i];?></option>
            <?php } ?>
        </select>
        <span id="check-e"></span>
        </div>
        <div class="form-group">
        <input type="text" class="form-control"  placeholder="Respuesta Secreta 2" name="respuesta_secreta_2" id="respuesta_secreta_2" value="<?php echo $respuesta_secreta_2; ?>" />
        <span id="check-e"></span>
        </div>
        <div class="form-group">
        <input type="text" class="form-control"  placeholder="E-Mail" value="<?php echo $userid; ?>" name="email" id="email" value="<?php echo $userid; ?>" />
        <span id="check-e"></span>
        </div>      
        <div class="form-group">
        <input type="password" value="" class="form-control" placeholder="Password" name="password" id="password" />
        </div>       
     	<hr />        
        <div class="form-group">
            
    		<INPUT id="cmdGuardar" type="button"  value="Actualizar"  class="form-control" onclick="actualizar_datos();"/>
			
        </div>
         <a style="text-decoration:none;color:#DAA520;" href="index.php"> << Atras</a>      
      </form>
    </div>    
    
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>