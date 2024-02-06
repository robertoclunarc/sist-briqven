<?php
session_start();
if(isset($_SESSION['user_session'])!=""){
	header("Location: inicio.php");
}
else{
    header("Location: login/index.php");
}
?>