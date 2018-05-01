<?php
	include("dbConstraints.php");
	include("EMail.php");
	include("utility.php");
	
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	
	$folder = 
	$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);

	
	if ($connTrial) {
		$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".DELETED_MAIL_FOLDER."' AND username= '".$user."';";
		$rs = mysql_db_query(DB_NAME,$folderIdQuest);
		$folderId = mysql_result($rs,0,"folderID");
		
		$totMsgs = intval($HTTP_POST_VARS['totMsgs']);
		for ($i=0;$i<$totMsgs;$i++) {
			if (isset($HTTP_POST_VARS["check".$i]))
				deteteMail($HTTP_POST_VARS["check".$i],$folderId,$user);
		}
		mysql_close($connTrial);
	}
	echo ("Messaggi cancellati correttamente! :-)");
	
?>