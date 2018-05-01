<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/popup.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Inserisci immagine</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv='page-enter' content='blendtrans(duration=1)'>
<link rel="stylesheet" type="text/css" href="css/default.css">
<link rel="stylesheet" type="text/css" href="css/popup.css">
<script language="javascript" src="js/general.js"></script>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="js/newImage.js"></script>
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
				  	::Carica Immagine::
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
						include("utility.php");
						session_start();
						$userName = $_SESSION[USERNAME_SESSION_INDEX];
						$upload = isset($HTTP_GET_VARS['action']);
						if ($upload) {
							$tmpImgDir = WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$userName."/".TMP_IMGS_DIR."/";
							$filePath = $HTTP_POST_FILES['theImg']['name'];
							$type = $HTTP_POST_FILES['theImg']['type'];
							if ($type == "image/gif")
								$ext = ".gif";
							else if ($type == "image/jpg" || $type == "image/jpeg" || $type == "image/pjpeg")
								$ext = ".jpg";
							else
								$ext = ".bmp";
							ob_start();
							$filePath = secureCopy($filePath,$ext,$HTTP_POST_FILES['theImg']['tmp_name'],$tmpImgDir);
							unlink($HTTP_POST_FILES['theImg']['tmp_name']);
							ob_end_flush();
							die("<script>insertImage('mailEditor','http://".SITE_ADDRESS.WEB_SRV_USERS_DIR."/".$userName."/".TMP_IMGS_DIR."/".$filePath."',false)</script>");
						}
					?>
					Seleziona la sorgente:
					<script>doSeparator()</script>
					<form id="imageForm" method="post" action="addImage.php?action=upload" enctype="multipart/form-data">
						<input type="radio" name="mode" onClick="showLayer(0);" value="hdd">Dal tuo hardisk
						<div id="hdd">
							<input class="mioButton" type="file" name="theImg">
						</div>
						<input type="radio" name="mode" onClick="showLayer(1);" value="url">Da url
						<div id="url">
							<input class="mioButton" type="text" name="url" value="http://">
						</div>
						<br>
						<input class="mioButton" type="button" onClick="imgInsertion()" value="Conferma">
						&nbsp;
						<input class="mioButton" type="button" onClick="window.close()" value="Annulla">
					</form>
					<script>doSeparator()</script>
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
