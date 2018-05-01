<?
	require("dbConstraints.php");
	include("POP3Client.php");
	include('Mail_Mime-1.2.1/mimeDecode.php');
	include("EMail.php");
	session_start();
	$action = isset($HTTP_GET_VARS[ACTION_GETVARS_INDEX]) ? $HTTP_GET_VARS[ACTION_GETVARS_INDEX] : "";
	$folder = $HTTP_GET_VARS[FOLDER_GETVARS_INDEX];
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	$pass = isset($_SESSION[PASSWORD_SESSION_INDEX]) ? $_SESSION[PASSWORD_SESSION_INDEX] : "";
	
	//echo("utente = ".$user);
	$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB) or die(MYSQL_ERROR);
	
	// ricavo il folderID di una cartella appartenente ad un utente
	$folderIdQuest = "SELECT folderId FROM ".FOLDER_TABLE_NAME." WHERE description ='".$folder."' AND username= '".$user."';";
	$rs = mysql_db_query(DB_NAME,$folderIdQuest);
	$folderId = mysql_result($rs,0,"folderID");
	if ($action == GET_MAIL && $folder == NEW_MAIL_FOLDER) 
	{
		/* POSTA IN ARRIVO -- scarico le email dal server POP3*/
		$pop3client = new POP3Client($user,$pass,TRUE);
		$okConn = $pop3client->connect();
		if ($okConn) {
			$totMessages = $pop3client->executeCommand("STAT");
			//echo("hai ".$totMessages." messaggi da leggere<br>");
			$messages = array();
			for ($i=1;$i<=$totMessages;$i++) {
				$messages[$i-1] = $pop3client->executeCommand("RETR",$i);
				$pop3client->executeCommand("DELE",$i);
			}
			
			$pop3client->disconnect();
			for ($messageId=0;$messageId<sizeof($messages);$messageId++) {
				$params['include_bodies'] = TRUE;
				$params['decode_bodies']  = TRUE;
				$params['include_headers']  = TRUE;
				$decoder = new Mail_mimeDecode($messages[$messageId]);
				$structure = $decoder->decode($params);
				/* PROCEDIMENTO DOWNLOAD MAIL
					1- scarico file inline
					2- scarico attachments
					3- scarico corpo mail
				*/
				
				if (!isset($structure->body)) {
					$mailBody = "";
					$cids = array();
					$imgNames = array();
					$imgBodies = array();
					$attNames = array();
					$attBodies = array();
					$creationDate = microtime();
					flowMimeParts($structure,$creationDate,$user);
					for ($i=0;$i<sizeof($cids);$i++)
						$mailBody = str_replace("cid:".$cids[$i],$imgNames[$i],$mailBody);
					$realBody = $mailBody;
				}
				else
					$realBody = $structure->body;
				$attachCount = sizeof($attNames);
				$to = $structure->headers['to'] or "";
				$from = $structure->headers['from'] or "";
				$date = date("Y-m-d H:i:s",strtotime($structure->headers['date']));
				$subject = $structure->headers['subject'] or "";
				$cc = isset($structure->headers['cc']) ? $structure->headers['cc'] : "";
				$bcc = isset($structure->headers['bcc']) ? $structure->headers['bcc'] : "";
				//echo($date);
				$priority =  isset($structure->headers['x-priority']) ? (strpos($structure->headers['x-priority'],'1')!== FALSE ? 1 : 0) : 0;
				$hasAttach = $attachCount > 0 ? 1 : 0;
				$mailSize = strlen($messages[$messageId]);
				$theMail = new EMail($realBody,$messages[$messageId],$user,$creationDate,$folderId,$to,$from,$cc,$bcc,$subject,$hasAttach,0,$date,$mailSize,$priority,$imgNames,$imgBodies,$attNames,$attBodies);
				$theMail->save();
			}
		}		
	}
	
	/* fasi per tutte le cartelle */
	/*mostra la tabella delle mail appartenenti a un determinato utente controllando solo il folderID*/
	$strSQL = "SELECT ".MAIL_TABLE_NAME.".* FROM ".FOLDER_TABLE_NAME.",".MAIL_TABLE_NAME;
	$strSQL.= " WHERE ".FOLDER_TABLE_NAME.".folderID = ".$folderId." AND ".MAIL_TABLE_NAME.".folderID = ".FOLDER_TABLE_NAME.".folderID";
	$strSQL.= " ORDER BY ".MAIL_TABLE_NAME.".arriveDate DESC;";
	$rs = mysql_db_query(DB_NAME,$strSQL);
	$elementsCount = mysql_num_rows($rs);
	if ($folder == DRAFTS_FOLDER)
		$url = "newMail.php?action=edit&";
	else
		$url = "mailCaller.php?";
	
?>
<html><!-- InstanceBegin template="/Templates/rightContent.dwt" codeOutsideHTMLIsLocked="false" -->
	<head>
		<!-- InstanceBeginEditable name="doctitle" -->
		<title>Untitled Document</title>
		<!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="javascript" src="js/general.js"></script>
		<link rel="stylesheet" type="text/css" href="css/default.css">
		<link rel="stylesheet" type="text/css" href="css/rightContent.css">
		<script language="JavaScript" type="text/javascript" src="js/rightContent.js"></script>
		<!-- InstanceBeginEditable name="head" -->
		<!-- InstanceEndEditable -->
	</head>
	<body>
	<!-- InstanceBeginEditable name="content" -->
		<?
			if ($folder == NEW_MAIL_FOLDER) {
			?>
			<script>
				window.parent.cartelle.location = "folders.php";
			</script>
		<? } ?>
			<table class="viewFolderTable" cellpadding="0" cellspacing="0">
				<tr>
					<td class="panel">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<h3>&raquo;<?=$folder?> - <?=$user?></h3>
								</td>
								<td align="right">
									La cartella contiene <b><?=$elementsCount?></b> element<?=$elementsCount != 1 ? "i" : "o"?>
								</td>
							</tr>
							<tr>
							<tr>
								<td colspan="2">
									<script>doSeparator()</script>
								</td>
							</tr>
							<tr>
								<td>
									<?
										if ($folder == NEW_MAIL_FOLDER) {
											$finalLetter = sizeof($messages) != 1 ? "i" : "o";
											echo("Hai <b>".sizeof($messages)."</b> messaggi".($finalLetter=="i" ? "" : $finalLetter)." nuov".$finalLetter);
										}
									?>
									</td>
									<td align="right">
										<a href="javascript:selectUnSelectMsgs(<?=$elementsCount?>,true,false)">Seleziona tutti i messaggi</a><br>
										<a href="javascript:selectUnSelectMsgs(<?=$elementsCount?>,false,false)">Deseleziona tutti i messaggi</a>
										<br>
										<? 
										if ($folder != DELETED_MAIL_FOLDER) {
										?>
											<a href="javascript:moveMsgs('<?=DELETED_MAIL_FOLDER?>')">Elimina messaggi selezionati</a>
										<? 
										} 
										else { 
										?>
										<a href="javascript:deleteMsgs('<?=DELETED_MAIL_FOLDER?>')">Elimina messaggi selezionati</a>
										<?
										}
										?>										
										
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<script>doSeparator()</script>
								</td>
							</tr>
							</table>
							<table style="width:auto;" cellpadding="0" cellspacing="0">
								<tr>
									<td class="noneHeader">
										<input type="checkbox" onClick="selectUnSelectMsgs(<?=$elementsCount?>,true,true)" id="commander">
									</td>
									<td class="senderHeader">
									<?
										if ($folder == SENT_MAIL_FOLDER || $folder == DRAFTS_FOLDER) {
											echo("Destinatario");
											$addressToShow = "toAddress";
										}
										else {
											echo("Mittente");
											$addressToShow = "fromAddress";
										}
									?>
									</td>
									<td class="priorityHeader">!</td>
									<td class="attachHeader"><img src="images/clip_1.gif"></td>
									<td class="subjectHeader">Oggetto</td>
									<td class="dateHeader">Data arrivo</td>
									<td class="sizeHeader">Dim</td>
									<td class="help"></td>
								</tr>
							</table>						
					</td>
				</tr>
				<tr>
					<td class="messagesContent" valign="top">
						<div style="position:relative;font-family:Verdana;font-size:8pt;overflow:auto;width:100%;height:100%;padding:0px;">
							<form name="msgs" action="" method="post">
								<table cellpadding="0" cellspacing="0" width="100%" height="100%">
									<?
										for ($i=0;$i<$elementsCount;$i++) {
											$hasBeenRead = mysql_result($rs,$i,"hasBeenRead") == 1;
											$style = !$hasBeenRead ? "style='text-decoration:underline;'" : "";
											$theSubject = mysql_result($rs,$i,"subject")=="" ? "[Nessun oggetto]" : mysql_result($rs,$i,"subject");
											$theSubject = strlen($theSubject) > 16 ? fixSize($theSubject) : $theSubject;
												
											?>
											<tr id="tr<?=$i?>" class="normal">
												<td class="noneHeader">
													<input name="check<?=$i?>" id="check<?=$i?>" type="checkbox" value="<?=mysql_result($rs,$i,"mailID")?>" onClick="doHighlight(<?="tr".$i?>)">
												</td>
												<td class="senderHeader">
													<?=mysql_result($rs,$i,$addressToShow)?>
												</td>
												<td class="priorityHeader"><?=(mysql_result($rs,$i,"hasPriority")? "!" : "")?></td>
												<td class="attachHeader"><?=(mysql_result($rs,$i,"hasAttach")? "<img src='images/clip_1.gif'>" : "")?></td>
												<td class="subjectHeader"><a <?=$style?> href="<?=$url?>mailID=<?=mysql_result($rs,$i,"mailID")?>"><?=$theSubject?></a></td>
												<td class="dateHeader"><?=mysql_result($rs,$i,"arriveDate")?></td>
												<td class="sizeHeader"><?=getFileSize(mysql_result($rs,$i,"mailSize"))?></td>
											</tr>
											<?
										}
										mysql_close($connTrial);
									?>
								</table>
								<input type="hidden" value="<?=$elementsCount?>" name="totMsgs">
							</form>
						</div>
					</td>
			</tr>
		</table>
	<!-- InstanceEndEditable -->	
	</body>
<!-- InstanceEnd --></html>
