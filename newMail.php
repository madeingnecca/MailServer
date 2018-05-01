<?php
	include("dbConstraints.php");
	include("utility.php");
	require_once("costantiServer.php");
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	$to = "";
	$from = "";
	$cc = "";
	$bcc = "";
	$subject = "";
	$body_html = "";
	$attachCount = 0;
	$attachDiv = "";
	$priority = FALSE;
	$attachNames = "";
	$types = "";
	$inlineImages = "";
	$trueNames = "";
	$tempImgsClientSide = SITE_ADDRESS . WEB_SRV_USERS_DIR."/".$user."/".TMP_IMGS_DIR."/";
	$url = "";
	
	if (isset($HTTP_GET_VARS['action'])) {
		$for = isset($HTTP_GET_VARS['for']) ? $HTTP_GET_VARS['for'] : "";
		$mailID = $_GET[MAILID_GETVARS_INDEX];
		$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);
		$mailQuest = "SELECT * FROM ".MAIL_TABLE_NAME.",".FOLDER_TABLE_NAME." WHERE mailID ='".$mailID."'AND ".MAIL_TABLE_NAME.".folderID = ".FOLDER_TABLE_NAME.".folderID AND ".FOLDER_TABLE_NAME.".username = '".$user."';";
		$rs = mysql_db_query(DB_NAME,$mailQuest);
		if (mysql_result($rs,0,"description")==DRAFTS_FOLDER)
			$url = $mailID;
		$from = mysql_result($rs,0,"fromAddress");
		$cc = mysql_result($rs,0,"ccAddress");
		$bcc = mysql_result($rs,0,"bccAddress");
		$date = mysql_result($rs,0,"arriveDate");
		$priority = mysql_result($rs,0,"hasPriority") == 1;
		$to =  mysql_result($rs,0,"toAddress");


		mysql_close();
		$mailPath = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$mailID."/".MAIL_DOC;
		if ($for != "") {
			$body_html = "<br>Messaggio originale: <hr>";
			$body_html.= "<br><b>Da:</b> ".$from;
			$body_html.= "<br><b>A:</b> ".$to;
			$body_html.= "<br><b>Cc:</b> ".$cc;
			$body_html.= "<br><b>Oggetto:</b> ".mysql_result($rs,0,"subject");
			$body_html.= "<hr>".readMyFile($mailPath);
			if ($for == "fw") {
				$subject = "Fw: ".mysql_result($rs,0,"subject");
				$to = "";
				$bcc = "";
				$cc = "";
			}
			else if ($for == "re") {
				$subject = "Re: ".mysql_result($rs,0,"subject");
				$to = $from;
				$bcc = "";
				$cc = "";
			}
		}
		else {
			$body_html = readMyFile($mailPath);
			$subject = mysql_result($rs,0,"subject");
		}
		$attachDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$mailID."/attachments";
		$tmpImgsDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user."/".TMP_IMGS_DIR."/";
		$tmpFilesDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user."/".TMP_FILES_DIR."/";
		$dir = dir($attachDir);
		$attachDiv = "";

		while($file = $dir->read()) {
			if($file != '.' && $file != '..') {
				$fileDim = getFileSize(filesize($attachDir."/".$file));
				$fileType = filetype($attachDir."/".$file);
				$fileIcon = getFileIcon($fileType);
				$ext = explode(".",$file);
				$ext = ".".$ext[sizeof($ext)-1];
				$file = secureCopy($file,$ext,$attachDir."/".$file,$tmpFilesDir);
				$attachDiv.= "<input type='checkbox' checked value='".$file."' name='attach".$attachCount."'><img src='images/".$fileIcon."'> ".$file." ".$fileDim."<br>";
				$attachNames.= $attachNames == "" ? $file : ":".$file;
				$types.= $types == "" ? $fileType : ":".$fileType;
				$attachCount++;
			}
		}
		$attachDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$mailID;		
		$dir = dir($attachDir);
		while($file = $dir->read()) {
			if($file != '.' && $file != '..' && $file != 'attachments' && $file != MAIL_DOC && $file != MIME_DOC) {
				$ext = explode(".",$file);
				$ext = ".".$ext[sizeof($ext)-1];
				$trueNames.= $trueNames == "" ? $file : ":".$file;
				$file = secureCopy($file,$ext,$attachDir."/".$file,$tmpImgsDir);
				$inlineImages.= $inlineImages == "" ? $file : ":".$file;
			}
		}
	}
?>
<html><!-- InstanceBegin template="/Templates/rightContent.dwt" codeOutsideHTMLIsLocked="false" -->
	<head>
		<!-- InstanceBeginEditable name="doctitle" -->
		<title>Scrivi la tua Mail</title>
		<!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="javascript" src="js/general.js"></script>
		<link rel="stylesheet" type="text/css" href="css/default.css">
		<link rel="stylesheet" type="text/css" href="css/rightContent.css">
		<script language="JavaScript" type="text/javascript" src="js/rightContent.js"></script>
	<!-- InstanceBeginEditable name="head" -->
	<script language="JavaScript" type="text/javascript" src="rte/richtext.js"></script>
	<!-- InstanceEndEditable -->
	</head>
	<body>
	<!-- InstanceBeginEditable name="content" -->
		<form name="editor" action="sendMail.php" method="post" onSubmit="return submitForm('<?=SITE_ADDRESS?>');">
			<table style="width:500px" height="100%">
				<tr>
					<td style="width:250px" height="114" valign="top">
						<table>
							<tr>
								<td>
								Da:
								</td>
								<td>
								<?=$user?>@coolmail.com
								</td>
							</tr>
							<tr>
								<td>
								A:
								</td>
								<td>
								<input name="to" type="text" class="textInput" style="width:200px;" value="<?=$to?>">
								</td>
							</tr>
							<tr>
								<td>
								Cc:
								</td>
								<td>
								<input name="cc" type="text" class="textInput" style="width:200px;" value="<?=$cc?>">
								</td>
							</tr>
							<tr>
								<td>
								Bcc:
								</td>
								<td>
								<input name="bcc" type="text" class="textInput" style="width:200px;" value="<?=$bcc?>">
								</td>
							</tr>
							<tr>
								<td>
								Oggetto:
								</td>
								<td>
								<input name="subject" type="text" class="textInput" style="width:200px;" value="<?=$subject?>">
								</td>
							</tr>
						</table>				  
					</td>
					<td valign="top" style="width:250px;text-align:right;">
						<input name="priority" <?=$priority ? "checked" : "" ?> type="checkbox">Alta priorit&agrave;
						<br><br>
						<input type="button" class="special" value="Allegati   " onClick="showLayer('attach')">
						<br><br>
						<input type="submit" class="mioButton" value="Salva come bozza" onClick="moveInDrafts()">
						<div id="attach">
							<h3>Allegati:</h3>
							<script>doSeparator()</script>
							<div id="attachments">
							<?=$attachDiv?>
							</div>
							<button class="mioButton" onClick="myPopup('Allega','attach.php')">Allega nuovo file</button>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="20">
						<script>doSeparator()</script>
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<div id="realBody" style="visibility:hidden;position:absolute;">
							<?=$body_html?>
						</div>
						<script language="javascript">
							initRTE("rte/images/", "rte/", "");
							writeRichText('mailEditor',realBody.innerHTML, 300, "70%", true, false);
							updateInlineObjects('<?=$trueNames?>','<?=$inlineImages?>','<?=SITE_ADDRESS?>','<?=$tempImgsClientSide?>');
						</script>
						  <input type="image" src="images/invia.gif" value="Invia!">
						  <input type="hidden" name="inlineImages" id="inlineImages" value="<?=$inlineImages?>">
						  <input type="hidden" name="trueInlineImages" id="trueInlineImages" value="">
						  <input type="hidden" name="types" value="<?=$types?>">
						  <input type="hidden" name="attachNames" value="<?=$attachNames?>">
						  <input type="hidden" name="attachments" value="<?=$attachCount?>">
						  <input type="hidden" name="mode" value="">
						  <input type="hidden" name="draft" value="<?=$url?>">
					</td>
			</tr>
		</table>
	</form>
	<!-- InstanceEndEditable -->	
	</body>
<!-- InstanceEnd --></html>
