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
		<script language="javascript" src="js/index.js"></script>
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
				   Benvenuto in CoolMail!
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
				  <table width="100%">
						<tr>
						  <td class="intro">
							  <object class="mioObject" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="263" height="185">
                                 <param name="movie" value="swf/intro.swf">
								 <param name="Loop" value="-1">
								 <param name="quality" value="high">
                                <embed src="swf/intro.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="264" height="212" loop="true"></embed>
						      </object>
							</td>
							<td class="login">
								<form name="login" action="environment.php" method="post" onSubmit="return checkLogin()">
									<h3>&raquo; Utenti Registrati</h3>
									<script>doSeparator();</script>
									Il tuo Username:<br>
									<?php
										include("dbConstraints.php");
									    $username = "";
										$password = "";
										$remStr = "";
										if (isset($HTTP_COOKIE_VARS[USERNAME_COOKIE_INDEX])){
											$username = $HTTP_COOKIE_VARS[USERNAME_COOKIE_INDEX];
											$password = $HTTP_COOKIE_VARS[PASSWORD_COOKIE_INDEX];
											$remStr = "checked";
										}
									?>
									<input class="textInput" type="text" name="userName" value="<?=$username?>"> <a href="index.php"> @ coolmail.com</a>
									<br><br>
									La tua Password:<br>
									<input class="textInput" type="password" name="userPass" value="<?=$password?>">
									<br>
									<br>
									<input type="image" src="images/login.gif" style="cursor:hand;">
									<br><br>
									<input type="checkbox" id="remember" name="remember" <?=$remStr ?>> <label for="remember">Ricordami l'id  e la pass su questo computer!</label>
									<br><br>
									<script>doSeparator();</script>
									Non hai ancora un account?&nbsp;<a href="#" onClick="myPopup('Nuovo account','newAccount.htm');">Iscriviti subito!  :-)</a>
								</form>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="2" >
											<script>doSeparator();</script>
										</td>
									</tr>
									<tr>
										<td class="footerIndex">
											<a href="http://www.apache.org"><img src="images/mini_apache.gif"></a>&nbsp;<a href="http://www.javascript.com"><img src="images/js.gif"></a>&nbsp;<a href="http://www.php.net"><img src="images/button-php.gif"></a>&nbsp;<a href="http://www.mysql.com"><img src="images/mysql.gif"></a>
										</td>
										<td class="footerIndex" style="text-align:right;">
								 			&laquo; <a href="">Crediti</a> :: <a href="">The CoolMail project</a> :: <a href="">Perchè CoolMail?</a> &raquo; 
										</td>
									</tr>
								 </table>
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


