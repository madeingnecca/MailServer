<?php
	include("dbConstraints.php");
	include("utility.php");
	
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	$mailID = $_GET[MAILID_GETVARS_INDEX];
	$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);
	$mailQuest = "SELECT * FROM ".MAIL_TABLE_NAME.",".FOLDER_TABLE_NAME." WHERE ".MAIL_TABLE_NAME.".mailID ='".$mailID."' AND ".MAIL_TABLE_NAME.".folderID = ".FOLDER_TABLE_NAME.".folderID AND ".FOLDER_TABLE_NAME.".username = '".$user."';";
	$rs = mysql_db_query(DB_NAME,$mailQuest);
	$mailPath = "CoolMailUsers"."/".$user.WEB_MAIL_DIR.$mailID."/".MAIL_DOC;
	$subject = mysql_result($rs,0,"subject");
	$from = mysql_result($rs,0,"fromAddress");
	$to = mysql_result($rs,0,"toAddress");
	$cc = mysql_result($rs,0,"ccAddress");
	$date = mysql_result($rs,0,"arriveDate");
	$folder = mysql_result($rs,0,"description");
	/* -  IMPOSTO CHE L'EMAIL � STATA LETTA - */
	$nowRead = "UPDATE ".MAIL_TABLE_NAME." SET hasBeenRead = 1 WHERE mailID ='".$mailID."';";
	mysql_db_query(DB_NAME,$nowRead);
	/* --- */
	mysql_close();
	
	$attachDir = "CoolMailUsers"."/".$user.WEB_MAIL_DIR.$mailID."/attachments";
	$dir = dir($attachDir);
	$attachDiv = "";
	$attachCount = 0;
	while($file = $dir->read()) {
		if($file != '.' && $file != '..') {
			$attachDiv.= "<a target='_blank' href='/MailServer/".$attachDir."/".$file."'>".$file." ".getFileSize(filesize($attachDir."/".$file))."<br>";
			$attachCount++;
		}
	}
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
		<table width="540" height="100%">
			<tr>
			  <td height="20" valign="top">
				  <h3><?=$user?> - visualizza mail</h3>
				  <script>doSeparator()</script>
			  </td>
		  </tr>
		  <tr>
			  <td height="50" valign="top" width="100%"><table width="100%" height="94"  border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="19%">
					<?
						if ($folder == SENT_MAIL_FOLDER)
							echo("A:");
						else
							echo("Da:");
					?>
				  </td>
                  <td width="38%">
				  <?=($folder == SENT_MAIL_FOLDER ? $to : $from)?>
				  </td>
                  <td width="43%" rowspan="4" align="right">
				  	<button class="mioButton" onClick="document.location = 'newMail.php?action=edit&for=fw&<?=MAILID_GETVARS_INDEX?>=<?=$mailID?>'">Inoltra</button>
					&nbsp;
					<button class="mioButton" onClick="document.location = 'newMail.php?action=edit&for=re&<?=MAILID_GETVARS_INDEX?>=<?=$mailID?>'">Rispondi</button>
					<br><br>
					<button class="mioButton" onClick="viewPrintableVersion('<?=$mailID?>');">Stampa</button>
					<? if ($attachCount > 0 ) {?>
						<button class="special" onClick="showLayer('attach')">Visualizza allegati   </button>
					<? }?>
					
					<div id="attach" style="top:108px;left:325px;">
						<h3>Allegati:</h3>
						<script>doSeparator()</script>
						<div id="attachments">
						<?=$attachDiv?>
						</div>
					</div>
				  </td>
                </tr>
                <tr>
                  <td>Data invio: </td>
                  <td><?=$date?></td>
                </tr>
                <tr>
                  <td>Cc:</td>
                  <td><?=$cc?></td>
                </tr>
                <tr>
                  <td>Oggetto:</td>
                  <td><?=$subject?></td>
                </tr>
				<tr>
					<td colspan="3">
						<script>doSeparator()</script>
					</td>
				</tr>
              </table>
			  </td>
		  </tr>
			<tr>
				<td valign="top">
					<iframe src="mail.php?<?=MAILID_GETVARS_INDEX?>=<?=$mailID?>" width="100%" height="100%" frameborder="0" style="border:1px solid #ccc;padding:5px;">Why CoolMail? 'Cause it's Cool =)
					</iframe>
				</td>
			</tr>
		</table>
	<!-- InstanceEndEditable -->	
	</body>
<!-- InstanceEnd --></html>