<?php
	require_once("dbConstraints.php");
	/*
	UTILITY.PHP
	-----------------------------------------
	Funzioni utilizzate nelle pagine del sito
	
	*/
	
	/* 
	getTagAttributeValues($HTMLCode,$daTag,$attribute)
	--------------------------------------------------
	Restituisce tutti i valori che l'attributo ($attribute)
	specificato del tag speficato ($daTag) assume nel
	codice HTML ($HTMLCode).
	
	CODED BY DAX
	** HO PERSO SOLAMENTE TEMPO! **
	----------------------------------------
	*/
	function getTagAttributeValues($HTMLCode,$daTag,$attribute) {
		$tagUpper = strtoupper($daTag); // elimino il problema del case-sensitive
		$tagLower = strtolower($daTag);
		$attributeValues = array(); // preparo l'array da restituire
		$index = 0; // indice dell'array
		$tokensUp = explode($tagUpper,$HTMLCode); // localizzo tutti i token delimitati dal tag
		$tokensLow = explode($tagLower,$HTMLCode);
		$tokens = sizeof($tokensUp) > sizeof($tokensLow) ? $tokensUp : $tokensLow;
		for($i=0;$i<sizeof($tokens);$i++){ // scorro i token
			$tempStr = explode($attribute,$tokens[$i]); // localizzo i token delimitati dall'attributo
			if ($tempStr[0] != $tokens[$i]) { // se il token e' valido
				$attributeValue = "";
				$j=1;
				while(substr($tempStr[1],$j,1) != "\"") { // scorro fino a trovare il carattere "
					$attributeValue.= substr($tempStr[1],$j++,1); // concateno i caratteri
				}
				$attributeValues[$index++] = $attributeValue;  // aggiungo il percorso trovato a quelli da restituire
			}
		}		
		return $attributeValues;
	}
	
	function mkFile($path,$body) {
		$fp = fopen($path, 'w');
		fwrite($fp,$body);
		fclose($fp);
	}
	
	function readMyFile($path) {
		$content = '';
		$fp = fopen($path, 'r');
		while(!feof($fp)) {
			$content .= fread($fp,4096);
		}
		fclose($fp);
		return $content;
	}
	
	function getFileIcon($type) {
		$icons = array("pdf","word","text","image");
		$icon = "default.gif";
		for ($i=0;$i<sizeof($icons);$i++) {
			if (strpos($type,$icons[$i])!==FALSE) {
				$icon = $icons[$i].".gif";
				break;
			}							
		}
		return $icon;
	}
	
	function stripErrors($daString) {
		$errorChars = array("=","?","/","<",">","*","|",":");
		for ($i=0;$i<sizeof($errorChars);$i++) {
			$daString = str_replace($errorChars[$i],"",$daString);
		}
		return $daString;
	}
	
	/*
	doImgStuff(...)  +
	-----------------|
	
	
	*/
	function doImgStuff($part,$messageId,$user,$outlook = FALSE) {
		global $cids,$imgNames,$imgBodies;
		$theImageName = $outlook ? $part->ctype_parameters['name'] : $part->d_parameters['filename'];
		array_push($imgBodies,$part->body);
		$tempCid = explode("<",trim($part->headers['content-id']));
		$tempCid = explode(">",$tempCid[sizeof($tempCid)-1]);
		array_push($cids,$tempCid[0]);
		array_push($imgNames,$theImageName);
	}
	
	function flowMimeParts($structure,$messageId,$user) 
	{
		if (isset($structure->parts)) 
		{
			$html = NULL;
			$plain = NULL;
			foreach ($structure->parts as $part) {
				if (isset($part->disposition)) {
					if ($part->disposition=='attachment') { // allegati?
						//mkfile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$messageId."/attachments/".stripErrors($part->d_parameters['filename']),$part->body);
						global $attNames,$attBodies;
						array_push($attNames,$part->d_parameters['filename']);
						array_push($attBodies,$part->body);
					}
					else if ($part->ctype_primary=='image' || $part->disposition=='inline') { //immagini
						doImgStuff($part,$messageId,$user);
					}
				}
				else
				{
					if ($part->ctype_primary=='image') { //immagini
						doImgStuff($part,$messageId,$user,TRUE);
					}
					else
					{
						if ($part->ctype_secondary == 'plain') {
							$plain = $part->body;
						}
						else if ($part->ctype_secondary == 'html') {
							$html = $part->body;
							global $mailBody;
							$mailBody = $html;
						}
						if ($plain != NULL && $html == NULL )
							$mailBody = $plain;
							//mkFile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$user.WEB_MAIL_DIR.$messageId."/".MAIL_DOC,$plain);
					}
				}
				flowMimeParts($part,$messageId,$user);
			}
		}
	}

	function delDir($dirName) {
	   if(empty($dirName)) {
		   return true;
	   }
	   if(file_exists($dirName)) {
		   $dir = dir($dirName);
		   while($file = $dir->read()) {
			   if($file != '.' && $file != '..') {
				   if(is_dir($dirName.'/'.$file)) {
					   delDir($dirName.'/'.$file);
				   } else {
					   @unlink($dirName.'/'.$file) or die('File '.$dirName.'/'.$file.' couldn\'t be deleted!');
				   }
			   }
		   }
		   $dir->close();
		   @rmdir($dirName) or die('Folder '.$dirName.' couldn\'t be deleted!');
	   } else {
		   return false;
	   }
	   return true;
	}

	function getFileSize($size) {
		$units = array(' B', ' KB', ' MB', ' GB', ' TB');
		for ($i = 0; $size > 1024; $i++) { 
			$size /= 1024; 
		}
		return round($size, 2).$units[$i];
	}

	
	function flow($myList){
		while(list($campi,$valori)=each($myList)){
			echo($campi."<br>");
			echo($valori."<br><br>");
		}
	}
	
	function isAllMadeOf($daStr,$daChar) {
		$i=0;
		for (;$i<strlen($daStr);$i++) {
			if (substr($daStr,$i,1)!=$daChar)
				return FALSE;
		}
		if ($i)
			return TRUE;
	}
	
	function deteteMail($mailID,$folderId,$user) {
		mysql_db_query(DB_NAME,"DELETE FROM ".MAIL_TABLE_NAME." WHERE mailID='".$mailID."' AND folderID = ".$folderId.";");
		$mailPath = "CoolMailUsers"."/".$user.WEB_MAIL_DIR.$mailID;
		delDir($mailPath);
	}
	
	function secureCopy($filePath,$ext,$src,$tmpDir) {
		$filePath=substr($filePath,0,strlen($filePath)-strlen($ext));
		$filePath = str_replace("%20","_",$filePath);
		$filePath = str_replace(" ","_",$filePath);
		$tmpPath = $filePath;
		for($i=0;file_exists($tmpDir.$tmpPath.$ext);$i++) {
			$tmpPath = $filePath.$i;
		}
		$filePath=$tmpPath.$ext;
		copy($src,$tmpDir.$filePath);
		
		return $filePath;
	}
	
	function fixSize($theString) {
		$result = "";
		for ($i=0;$i<16;$i++)
			$result .= substr($theString,$i,1);
		$result .= " ..";
		return $result;
	}
?>
