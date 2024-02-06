<?php
	session_start();
	//session_destroy();
	unset($_SESSION["user_session_conslab"]);
	unset($_SESSION["username_conslab"]);
	unset($_SESSION["userci_conslab"]);
	unset($_SESSION["userid_conslab"]);
	unset($_SESSION["nivel_conslab"]);
	unset($_SESSION["estatususer_conslab"]);
	header('Location: index.php');
	exit(0);
?>
