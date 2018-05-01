<?php
	include("dbConstraints.php");
	include("EMail.php");
	include("utility.php");
	
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	
	$folder = 
	$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);

	
	if ($connTrial) {
		$type = $HTTP_GET_VARS['mode'];
		$folder = $HTTP_GET_VARS['folder'];
		$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".$folder."' AND username= '".$user."';";
		$rs = mysql_db_query(DB_NAME,$folderIdQuest);
		$folderId = mysql_result($rs,0,"folderID");
		
		if ($type == "single") {
			mysql_db_query(DB_NAME,"UPDATE ".MAIL_TABLE_NAME." SET folderID = ".$folderId." WHERE mailID='".$HTTP_GET_VARS["mailID"]."';");
		}
		else {
			$totMsgs = intval($HTTP_POST_VARS['totMsgs']);
			//echo("tot msgs = ".$totMsgs);
			for ($i=0;$i<$totMsgs;$i++) {
				if (isset($HTTP_POST_VARS["check".$i])) {
					mysql_db_query(DB_NAME,"UPDATE ".MAIL_TABLE_NAME." SET folderID = ".$folderId." WHERE mailID='".$HTTP_POST_VARS["check".$i]."';");
					//echo ("<br>messaggio spostato!");
				}
			}
			echo ("<br>Messaggi spostati correttamente!! :-)");
		}
		mysql_close($connTrial);
	}
	//header("Location: viewFolder.php?folder=".$folder);
	
?>
