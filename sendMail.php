<?php
	include("dbConstraints.php");
	include('Mail_Mime-1.2.1/mime.php');
	include('Mail_Mime-1.2.1/mimeDecode.php');
	include("costantiServer.php");
	include("utility.php");
	include("SMTPClient.php");
	include("EMail.php");
	
	session_start();
	
	$to = trim($_POST["to"]);
	$user = $_SESSION[USERNAME_SESSION_INDEX];
	$from = $user."@coolmail.com";
	$cc = trim($_POST["cc"]);
	$bcc = trim($_POST["bcc"]);
	$allDests = explode(";",$to.";".$cc.";".$bcc);
	$date = date("Y-m-d H:i:s");
	$subject = trim($_POST["subject"]);
	$priority = isset($HTTP_POST_VARS['priority']) ? 1 : 0;
	$rteHtml = "<html><head><link href='../../../css/mail.css' rel='stylesheet'></head><body>".$HTTP_POST_VARS['mailEditor']."</body></html>";
	
	$trueInline = $HTTP_POST_VARS['trueInlineImages'];
	/*
	echo($trueInline."<br>");
	echo($HTTP_POST_VARS['inlineImages']."<br>");
	die();
	*/
	if (trim($HTTP_POST_VARS['inlineImages'])!=="")
		$inline = explode(":",$HTTP_POST_VARS['inlineImages']);
	else
		$inline = array();
		
	if (trim($HTTP_POST_VARS['types'])!=="") {
		$types = $HTTP_POST_VARS['types'];
		$types = explode(":",$types); 
	}
	else
		$types = array();
		
	if (trim($HTTP_POST_VARS['attachNames'])!=="") {
		$names = $HTTP_POST_VARS['attachNames'];
		$names = explode(":",$names); 
	}
	else
		$names = array();		
		
	$hdrs = array(
				  'From'    => $from,
				  'To' => $to,
				  'Subject' => $subject,
				  'Date' => $date,
				  'X-Mailer' => 'CoolMail',
				  'Cc' => $cc,
				  'Bcc' => $bcc,
				  'x-priority' => $priority
				  );
	
	$mime = new Mail_mime(EOL);
	$text = strip_tags($rteHtml);

	$mime->setTXTBody($text);
	$mime->setHTMLBody($rteHtml);

	$tmpImgDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$_SESSION[USERNAME_SESSION_INDEX]."/".TMP_IMGS_DIR."/";
	//echo("Inline images = ".$HTTP_POST_VARS['inlineImages']);
	//echo("<br> attach count = ".$HTTP_POST_VARS['attachments']);
	//echo("<br> attach names = ".$HTTP_POST_VARS['attachNames']);
	//echo("<br> attach count by name = ".sizeof($names));
	
	$imgNames = array();
	$imgBodies = array();
	$attNames = array();
	$attBodies = array();
					
	for ($i=0;$i<sizeof($inline);$i++) {
		$theFile = str_replace("%20"," ",$inline[$i]);
		if (strpos($trueInline,$inline[$i])!==FALSE) {
			$extension = explode(".",$theFile);
			$extension = $extension[sizeof($extension)-1];
			$mime->addHTMLImage($tmpImgDir.$theFile,"image/".$extension.";","",TRUE);
			array_push($imgNames,$theFile);
			$theFileBody = readMyFile($tmpImgDir.$theFile);
			array_push($imgBodies,$theFileBody);
		}
		unlink($tmpImgDir.$theFile);
	}
	$tmpFileDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$_SESSION[USERNAME_SESSION_INDEX]."/".TMP_FILES_DIR."/";
	$realAttachments = 0;
	for ($i=0;$i<sizeof($names);$i++) {
		if (isset($HTTP_POST_VARS["attach".$i])) {
			//echo("entrato<br>");
			$theFile = str_replace("%20"," ",$HTTP_POST_VARS["attach".$i]);
			$mime->addAttachment($tmpFileDir.$theFile,$types[$i]);
			$realAttachments++;
			array_push($attNames,$theFile);
			$theFileBody = readMyFile($tmpFileDir.$theFile);
			array_push($attBodies,$theFileBody);
		}
		else
			$theFile = str_replace("%20"," ",$names[$i]);
		
		unlink($tmpFileDir.$theFile);
		//echo("<br>Ho eliminato il file: ".$tmpFileDir.$theFile."<br>");
	}
	$hasAttach = $realAttachments > 0 ? 1 : 0;
	$mimeMessage = $mime->get();
	$hdrs = $mime->headers($hdrs);
	$strHeaders = $mime->txtHeaders();

	$mimeMessage = $strHeaders.EOL.$mimeMessage;
	$messageSize = strlen($mimeMessage);
	$totalSize = $_SESSION[MEMORY_SESSION_INDEX];

		if ($_POST['mode'] != "") {
			if ($messageSize > TOTAL_MEMORY - $totalSize ) 
				echo("Memoria insufficiente per memorizzare la mail nella cartella: ".DRAFTS_FOLDER);
			else {
				$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".DRAFTS_FOLDER."' AND username = '".$user."';";
				$rs = mysql_db_query(DB_NAME,$folderIdQuest);
				$folderId = mysql_result($rs,0,"folderID");
				if ($_POST['draft'] != "") {
					$theOldID = $_POST['draft'];
					delDir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$theOldID);
					mysql_db_query(DB_NAME,"DELETE FROM ".MAIL_TABLE_NAME." WHERE mailID = '".$theOldID."' AND folderID = ".$folderId.";");
				}
				ob_start(); 
				$theMail = new EMail($rteHtml,$mimeMessage,$user,microtime(),$folderId,$to,$from,$cc,$bcc,$subject,$hasAttach,0,$date,$messageSize,$priority,$imgNames,$imgBodies,$attNames,$attBodies);
				$theMail->save();
				ob_end_flush();
				echo("<br>Messaggio inviato correttamente!");
				header("Location: viewFolder.php?".FOLDER_GETVARS_INDEX."=".DRAFTS_FOLDER."&".ACTION_GETVARS_INDEX."=".GET_MAIL);
			}
		}
		else {
			$smtpClient = new SMTPClient();
			$smtpClient->connect();
			$smtpClient->executeCommand("HELO");
			$smtpClient->executeCommand("MAIL FROM:",$from);
			$errors = 0;
			for ($i=0;$i<sizeof($allDests);$i++) {
				$correctDest = $smtpClient->executeCommand("RCPT TO:",$allDests[$i]);
				$errors = !$correctDest ? $errors+1 : $errors;
			}
			if ($errors != sizeof($allDests)) {
				/* Invio della Mail al server SMTP */
				ob_start(); // inizio controllo del flusso
				$smtpClient->executeCommand("Data",$mimeMessage);
				$smtpClient->disconnect();
				ob_end_flush(); // fine controllo del flusso
				if ($messageSize > TOTAL_MEMORY - $totalSize )
					echo("Memoria insufficiente per memorizzare la mail nella cartella: ".SENT_MAIL_FOLDER);
				else {
					$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".SENT_MAIL_FOLDER."' AND username = '".$user."';";
					$rs = mysql_db_query(DB_NAME,$folderIdQuest);
					$folderId = mysql_result($rs,0,"folderID");
					$theMail = new EMail($rteHtml,$mimeMessage,$user,microtime(),$folderId,$to,$from,$cc,$bcc,$subject,$hasAttach,0,$date,$messageSize,$priority,$imgNames,$imgBodies,$attNames,$attBodies);
					$theMail->save();
					echo("<br>Messaggio inviato correttamente!");
					header("Location: viewFolder.php?".FOLDER_GETVARS_INDEX."=".NEW_MAIL_FOLDER."&".ACTION_GETVARS_INDEX."=".GET_MAIL);
				}
			}
			else {
				echo("<br>Ci dispiace ma non si è stati in grado di spedire il messaggio ai destinatari specificati! :-(");
				$smtpClient->disconnect();
			}
		}
?>
