<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/popup.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
<link rel="stylesheet" type="text/css" href="css/default.css">
<link rel="stylesheet" type="text/css" href="css/popup.css">
<script language="javascript" src="js/general.js"></script>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="js/attach.js"></script>
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
				  	::Allega file::
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
					<h3>Aggiungi file in allegato</h3>
					<script>doSeparator()</script>
					<?php
						include("dbConstraints.php");
						include("utility.php");
						session_start();
						$userName = $_SESSION[USERNAME_SESSION_INDEX];
						$upload = isset($HTTP_GET_VARS['action']);
						if ($upload) {
							$tmpImgDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$userName."/".TMP_FILES_DIR."/";
							$filePath = $HTTP_POST_FILES['theFile']['name'];
							$type = $HTTP_POST_FILES['theFile']['type'];
							$icon = getFileIcon($type);
							$ext = explode(".",$filePath);
							$ext = ".".$ext[sizeof($ext)-1];
							$filePath = secureCopy($filePath,$ext,$HTTP_POST_FILES['theFile']['tmp_name'],$tmpImgDir);
							?>
								Nome file: <?=$filePath?>
								<br><br>
								Tipo: <?=$type?> <img src="images/<?=$icon?>">
								<br><br>
								Dimensione: <?=getFileSize($HTTP_POST_FILES['theFile']['size'])?>
								<br><br>
								<input class="mioButton" type="button" value="Continua la mail" onClick="updateAttachments('<?=$filePath?>','<?=$icon?>','<?=getFileSize($HTTP_POST_FILES['theFile']['size'])?>','<?=$type?>')">
							<?
							unlink($HTTP_POST_FILES['theFile']['tmp_name']);
						}
						else  {
					?>
					
					<form id="attachForm" method="post" action="attach.php?action=upload" enctype="multipart/form-data">
						Clicca qui per allegare un file:<br>
						<input class="mioButton" type="file" name="theFile">
						<br><br>
						<input class="mioButton" type="submit" value="Allega questo file">
					</form>
					<?
					}
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
