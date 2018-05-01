<html><!-- InstanceBegin template="/Templates/base.dwt" codeOutsideHTMLIsLocked="false" -->
	<head>
		<!-- InstanceBeginEditable name="doctitle" -->
		<title>Benvenuto in CoolMail!</title>
		<!-- InstanceEndEditable -->	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
		 <!-- InstanceBeginEditable name="stili" -->
		<link rel="stylesheet" type="text/css" href="css/default.css">
		<!-- InstanceEndEditable -->
		<!-- InstanceBeginEditable name="javascript" -->
		<script language="javascript" src="js/general.js"></script>
		<!-- InstanceEndEditable -->
	    <!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
	</head>
	<body onLoad="doClock()">
		<table cellpadding=0 cellspacing=0 style="width:750px;" class="mainTable">
			<tr>
				<td colspan="3" align="left" style="height:111px;">
					<table cellpadding=0 cellspacing=0 >
						<td class="headerDx">
							<a href="index.php"><img src="images/header.gif" alt="Vai alla Homepage di CoolMail!"></a>
						</td>
						<td class="headerSx">
						<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="474" height="111">
                          <param name="movie" value="swf/snow.swf">
                          <param name="quality" value="high">
                          <embed src="swf/snow.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="474" height="111"></embed>
						  </object>
						  </td>						
					</table>
				</td>
			</tr>
			<tr>
				<td class="hSx">
				</td>
				<td class="hCenter">
				  <div class="welcome">
				   <!-- InstanceBeginEditable name="titoloTabella" -->
				  	::Installazione di CoolMail::
				  <!-- InstanceEndEditable -->
				  </div>
				  <div id="clock">
				  </div> 
				</td>
				<td class="hDx">
				</td>				
			</tr>
			<tr>
				<td class="sxCenter">
				</td>
				<td class="contenuto">
				  <!-- InstanceBeginEditable name="contenutoTabella" -->				  
				  <table>
						<tr>
							<td class="contenuto">
							<?php
								include("dbConstraints.php");
								include("UserCreator.php");
								mysql_query("CREATE DATABASE IF NOT EXISTS ".DB_NAME);
								$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);
								if ($connTrial) {
									$usersTable = "CREATE TABLE IF NOT EXISTS ".USERS_TABLE_NAME." (	
										 username varchar(20) not null primary key,
										 password varchar(20) not null, 
										 isLocked tinyint default 0
									)TYPE = MyISAM;";

									$textMailTable = "CREATE TABLE IF NOT EXISTS ".MAILDROP_TABLE_NAME." (
										mailID int not null PRIMARY KEY auto_increment,						
										username varchar(20) not null,
										message longtext not null,
										FOREIGN KEY (username) REFERENCES ".USERS_TABLE_NAME." (username) ON DELETE CASCADE
									)TYPE = MyISAM;";
									
									$folderTable = "CREATE TABLE IF NOT EXISTS ".FOLDER_TABLE_NAME." (
													folderID int not null PRIMARY KEY auto_increment,
													description varchar(20) not null,
													username varchar(20) not null,
													FOREIGN KEY (username) REFERENCES ".USERS_TABLE_NAME." (username) ON DELETE CASCADE
									)TYPE = MyISAM;";
									
									$mailTable = "CREATE TABLE IF NOT EXISTS ".MAIL_TABLE_NAME." (
													mailID varchar(30) not null,
													folderID int not null,
													toAddress varchar(50) not null,
													fromAddress varchar(50) not null,
													ccAddress varchar(50),
													bccAddress varchar(50),
													subject varchar(255),
													hasAttach tinyint default 0,
													hasBeenRead tinyint default 0,
													arriveDate datetime not null,
													mailSize int not null,
													hasPriority tinyint default 0,
													FOREIGN KEY (folderID) REFERENCES ".FOLDER_TABLE_NAME." (folderID) ON DELETE CASCADE,
													PRIMARY KEY (mailID,folderID)
													
									)TYPE = MyISAM;";
									
									$contactsTable = "CREATE TABLE IF NOT EXISTS ".CONTACTS_TABLE_NAME." (
													contactID int not null PRIMARY KEY auto_increment,
													name varchar(20) not null,
													email varchar(20) not null,
													username varchar(20) not null,
													FOREIGN KEY (username) REFERENCES ".USERS_TABLE_NAME." (username) ON DELETE CASCADE
									)TYPE = MyISAM;";
									
									$tablesToBuild = array($usersTable,$textMailTable,$folderTable,$mailTable,$contactsTable);
									$esitoInstallazione = "";
									
									for ($i=0;$i<sizeof($tablesToBuild);$i++) {
										if (!mysql_db_query(DB_NAME,$tablesToBuild[$i]))
											$esitoInstallazione.="Impossibile creare la tabella : ".$tablesToBuild[$i]."<BR>";
									}
									
									if ($esitoInstallazione == "") {
										$esitoInstallazione = "Installazione completata correttamente! :-)";
										$userObj = new User(ADMIN_USER,ADMIN_PASS);
										$userObj->create();
									}
									echo $esitoInstallazione;
									mysql_close($connTrial);
								}
								else 
									echo (MYSQL_ERROR);
										
							?>
							</td>
						</tr>
				  </table>	
				  <!-- InstanceEndEditable -->		  
				</td>
				<td class="dxCenter">
				</td>				
			</tr>
			<tr>
				<td class="lSx">
				</td>
				<td class="lCenter">
				</td>
				<td class="lDx">
				</td>				
			</tr>
	</table>
	</body>
<!-- InstanceEnd --></html>
