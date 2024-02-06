<?php
session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 17 Apr 2023 23:59:59 GMT"); // Fecha en el pasado
if(isset($_SESSION['user_session_const'])!=""){
    header("Location: indexCCure.php");
}
else{
    header("Location: login/index.php");
}
?>