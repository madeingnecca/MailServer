<?php
	include("dbConstraints.php");
	session_start();
	$userName = $_SESSION[USERNAME_SESSION_INDEX];
	$action = $HTTP_GET_VARS['action'];
	if ($action == "normal") {
		$type = $HTTP_GET_VARS['type'];
		if ($type == "view")
			$msg = "Visualizza";
		else
			$msg = "Seleziona";
	}
	else {
		die();
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/popup.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>La tua rubrica</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
<link rel="stylesheet" type="text/css" href="css/default.css">
<link rel="stylesheet" type="text/css" href="css/popup.css">
<script language="javascript" src="js/general.js"></script>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="js/contacts.js"></script>
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
				  	::Contatti - <?=$userName?>::
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
				<form action="contacts.php?action=save" method="post" name="book">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="">
							<h3><?=$msg?></h3>
							<script language="javascript">doSeparator()</script>
							<?
								$connTrial = mysql_connect(DB_ADDRESS,USERNAME_DB,PASSWORD_DB) or die(MYSQL_ERROR);
								$contactsQuest = "SELECT name,email FROM ".CONTACTS_TABLE_NAME." WHERE username= '".$userName."'";
								$rs = mysql_db_query(DB_NAME,$contactsQuest);
								$totContacts = intval(mysql_num_rows($rs));
								echo("Hai memorizzato <div id='count' style='display:inline;'>".$totContacts."</div>&nbsp;contatto/i<br>");
								?>
								<div id="contacts" style="overflow:auto;height:100px;">
								<?
								for ($i=0;$i<$totContacts;$i++) {
									echo("<input type='checkbox' name='contact".$i."' value='".mysql_result($rs,$i,"email")."'> ".mysql_result($rs,$i,"name")." ".mysql_result($rs,$i,"email")."<br>");
								}
							?>
								</div>
							</td>
							<td style="background-image:url(images/dotsVert.gif);background-repeat:repeat-y;">
								<h3>Aggiungi</h3>
								<script language="javascript">doSeparator()</script>
								<div style="height:100px;width:150px;">
									Nome:<br>
									<input type="text" name="name" class="textInput">
									<br><br>
									Email:<br>
									<input type="text" name="email" class="textInput">
									<br><br>
									<input type="button" class="mioButton" onClick="doSaveContact()" value="Salva">
								</div>
								<input type="hidden" value="<?=$totContacts?>" name="contactsCount" style="position:absolute;">
								<input type="hidden" value="" name="existingContacts" style="position:absolute;">
							</td>
						</tr>
						<tr>
							<td colspan="2">
							</td>
						</tr>
					</table>
				</form>
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
