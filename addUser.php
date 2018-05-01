<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/popup.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Esito</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
<link rel="stylesheet" type="text/css" href="css/default.css">
<link rel="stylesheet" type="text/css" href="css/popup.css">
<script language="javascript" src="js/general.js"></script>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="js/newAccount.js"></script>
<!-- InstanceEndEditable -->
</head>

<body >
		<table cellpadding=0 cellspacing=0>
			<tr>
				<td colspan="3" align="left">
					<table width="100%" cellpadding=0 cellspacing=0>
						<td>
							<img src="images/header.gif">
						</td>					
					</table>
				</td>
			</tr>
			<tr>
				<td class="hSx">
				</td>
				<td class="hCenter" >
				  <!-- InstanceBeginEditable name="titoloTabella" -->
				  	::Esito sottoscrizione::
				  <!-- InstanceEndEditable -->
				</td>
				<td class="hDx">
				</td>				
			</tr>
			<tr>
				<td class="sxCenter">
				</td>
				<td class="contenuto">
			    <!-- InstanceBeginEditable name="contenuto" -->
					<?php
						include("dbConstraints.php");
						include("UserCreator.php");
						
						$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB);
						$username = $HTTP_POST_VARS['userName'];
						$password = $HTTP_POST_VARS['userPass'];
						if ($connTrial) {
							$objUser = new User($username,$password);
							$esito = $objUser->create();
							if ($esito) {
								echo("Nuovo account creato correttamente! :-) <br><br>");
								echo("Queste sono le informazioni da te inviate: <br><br>");
								?>
									Username: <b><? echo $username ?></b>
									<br><br>
									Password: <b><? echo $password ?></b>
									<br><br>
									Clicca <a href="javascript:prepareLogin('<? echo $username ?>','<? echo $password ?>');">qui</a> per andare alla Homepage e cominciare ad utilizzare CoolMail! :-)
								<?
							}
							else {
								?>
									Ci dispiace, ma il tuo nick &egrave; gi&agrave; stato scelto! :-(
									<br><br>
									Clicca <a href="newAccount.htm">qui</a> per ritornare alla pagina di registrazione
								<?
							}
						}
						else
							echo (MYSQL_ERROR);
						
					?>
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
