<?php
session_start();
if(isset($_SESSION['user_session_const'])!=""){
    header("Location: indexCCure.php");
}
else{
    header("Location: ../login/index.php");
}
?>