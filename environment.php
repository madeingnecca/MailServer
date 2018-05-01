<?php
	include("dbConstraints.php");
	include("POP3Client.php");
	$user = $HTTP_POST_VARS["userName"];
	$pass = $HTTP_POST_VARS["userPass"];
	$rem = isset($HTTP_POST_VARS["remember"]);
	
	$pop3client = new POP3Client($user,$pass,TRUE);
	$okConn = $pop3client->connect();
	$pop3client->disconnect();
	if ($okConn) {
		session_start();
		$_SESSION[USERNAME_SESSION_INDEX] = $user;
		$_SESSION[PASSWORD_SESSION_INDEX] = $pass;
		if ($rem) {
			setcookie(USERNAME_COOKIE_INDEX,$user,time()+5*24*3600);
			setcookie(PASSWORD_COOKIE_INDEX,$pass,time()+5*24*3600);
		}
		else {
			setcookie(USERNAME_COOKIE_INDEX, "", mktime(12,0,0,1, 1, 1990));
			setcookie(PASSWORD_COOKIE_INDEX, "", mktime(12,0,0,1, 1, 1990));
		}
	}
	else 
		header("Location: logError.htm");
	
?>

<html><!-- InstanceBegin template="/Templates/base.dwt" codeOutsideHTMLIsLocked="false" -->
	<head>
		<!-- InstanceBeginEditable name="doctitle" -->
		<title>Benvenuto in CoolMail!</title>
		<!-- InstanceEndEditable -->	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
		 <!-- InstanceBeginEditable name="stili" -->
		<link rel="stylesheet" type="text/css" href="css/default.css">
		<link rel="stylesheet" type="text/css" href="css/accountEnvironment.css">
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
				  	Benvenuto in CoolMail, <?=$user?>
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
				  <div id="altreCartelle"></div>				  
				  <table height="100%" width="100%" cellpadding="0" cellpadding="0">
						<tr>
							<td class="cartelle">
								<iframe src="loading.html" name="cartelle" scrolling="no" height="100%" width="100%" frameborder="0" marginheight="0" marginwidth="0">
									iframe needed!
								</iframe>
							</td>
							<td class="misc">
								<iframe src="viewFolder.php?<? echo(FOLDER_GETVARS_INDEX);?>=<? echo(NEW_MAIL_FOLDER);?>&<? echo(ACTION_GETVARS_INDEX);?>=<? echo(GET_MAIL);?>" name="rightContent" scrolling="no" height="100%" width="100%" frameborder="0" marginheight="0" marginwidth="0">
									Oops, coolmail necessita gli iFrame :-(
								</iframe>
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
