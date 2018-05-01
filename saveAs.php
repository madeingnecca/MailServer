<?php
	include("dbConstraints.php");
	include("EMail.php");
	include("utility.php");
	
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	$folder = DRAFTS_FOLDER;   // temporanea!
	if 	($folder == DRAFTS_FOLDER) {
		$to = trim($_POST["to"]);
		$user = $_SESSION[USERNAME_SESSION_INDEX];
		$from = $user."@coolmail.com";
		$cc = trim($_POST["cc"]);
		$bcc = trim($_POST["bcc"]);
		$date = date("d-m-Y");
		$subject = trim($_POST["subject"]);
		$priority = isset($HTTP_POST_VARS['priority']) ? 1 : 0;
		$rteHtml = "<html><head><link href='../../../css/mail.css' rel='stylesheet'></head><body>".$HTTP_POST_VARS['mailEditor']."</body></html>";
		$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".DRAFTS_FOLDER."' AND username= '".$user."';";
		$rs = mysql_db_query(DB_NAME,$folderIdQuest);
		$folderId = mysql_result($rs,0,"folderID");
		ob_start();
		$theMail = new EMail($rteHtml,"",$user,microtime(),$folderId,$to,$from,$cc,$bcc,$subject,0,0,$date,0,$priority,array(),array(),array(),array());
		$theMail->save();
		ob_end_flush();
		header("Location: viewFolder.php?".FOLDER_GETVARS_INDEX."=".DRAFTS_FOLDER);
	}
?>

