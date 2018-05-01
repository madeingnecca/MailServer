<?
	require("dbConstraints.php");
	session_start();
	$user = isset($_SESSION[USERNAME_SESSION_INDEX]) ? $_SESSION[USERNAME_SESSION_INDEX] : "";
	
	$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB) or die(MYSQL_ERROR);
	$folderIdQuest = "SELECT description FROM ".FOLDER_TABLE_NAME." WHERE description <>'".NEW_MAIL_FOLDER."' AND username= '".$user."'";
	$folderIdQuest.= " AND description<>'".SENT_MAIL_FOLDER."' AND description<>'".DELETED_MAIL_FOLDER."';";
	$rs = mysql_db_query(DB_NAME,$folderIdQuest);
?>
<html>
	<head>
		<title>Untitled Document</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="javascript" src="js/general.js"></script>
		<script language="javascript" src="js/folders.js"></script>
		<link rel="stylesheet" href="css/default.css">
		<link rel="stylesheet" href="css/folders.css">
	</head>
	<body>
		<div id="altreCartelle" style="visibility:hidden;position:absolute; ">
			<?
				$newFoldersCount = mysql_num_rows($rs);
				for ($i=0;$i<$newFoldersCount;$i++) {
					$folderDescription = mysql_result($rs,$i,"description");
					?>
					<a href="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo($folderDescription);?>"><? echo($folderDescription); ?></a><br>
					<?
				}
			?>
		</div>
		<div class="contorno">
			<div class="header">
				<img src="images/arrow1.gif">Le tue Cartelle
			</div>
			<a onClick="selectMe(this)" href="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo(NEW_MAIL_FOLDER);?>&<? echo(ACTION_GETVARS_INDEX);?>=<? echo(GET_MAIL);?>" target="rightContent">Posta in arrivo</a><br>
			<a onClick="selectMe(this)" href="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo(SENT_MAIL_FOLDER);?>" target="rightContent">Posta inviata</a><br>
			<a onClick="selectMe(this)" href="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo(DELETED_MAIL_FOLDER);?>" target="rightContent">Posta eliminata</a><br>
			<a onClick="selectMe(this)" href="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo(DRAFTS_FOLDER);?>" target="rightContent">Bozze</a><br>
			<a onClick="selectMe(this)" href="javascript:;">Altre cartelle</a><br>
		</div>
		<script>doSeparator()</script>
		<div class="contorno">
			<div class="header">
				<img src="images/arrow1.gif">Scelta rapida
			</div>
			<a onClick="selectMe(this)" href="newMail.php" target="rightContent">Nuovo messaggio</a><br>
			<a onClick="selectMe(this)" href="javascript:myPopup('contacts','contacts.php?action=normal&type=view')">Vedi rubrica</a><br>
			<a onClick="selectMe(this)" href="#">Svuota cartella</a><br>
			<a onClick="selectMe(this)" href="#">Elimina cartella</a><br>
		</div>
		<script>doSeparator()</script>
		<div class="contorno">
			<div class="header">
				<img src="images/arrow1.gif">Memoria Occupata
			</div>
			<?
				$sumSize = "SELECT SUM(".MAIL_TABLE_NAME.".mailSize) as somma FROM ".MAIL_TABLE_NAME.",".FOLDER_TABLE_NAME;
				$sumSize.= " WHERE ".FOLDER_TABLE_NAME.".folderID = ".MAIL_TABLE_NAME.".folderID";
				$sumSize.= " AND ".FOLDER_TABLE_NAME.".username = '".$user."';";
				$rs = mysql_db_query(DB_NAME,$sumSize);
				if ($rs)
					$totalSize = intval(mysql_result($rs,0,"somma"));
				else
					$totalSize = 0;
				$_SESSION[MEMORY_SESSION_INDEX] = $totalSize;
				echo ("<script>doMemoryState(".(TOTAL_MEMORY - $totalSize).",".TOTAL_MEMORY.")</script>");
			?>
		</div>
	</body>
</html>
