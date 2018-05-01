<?php
	include("dbConstraints.php");
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	$mailID = $_GET[MAILID_GETVARS_INDEX];
	$mailPath = str_replace("/","",WEB_SRV_USERS_DIR)."/".$user.WEB_MAIL_DIR.$mailID."/".MAIL_DOC;
	header("Location: ".$mailPath);
?>
