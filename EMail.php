<?php
	
require_once("dbConstraints.php");

class EMail
{
	var $imgNames;
	var $imgBodies;
	var $attNames;
	var $attBodies;	
	var $mimeBody;
	var $realBody;
	var $user;
	var $mailID;
	var $folderID;
	var $toAddress;
	var $fromAddress;
	var $ccAddress;
	var $bccAddress;
	var $subject;
	var $hasAttach;
	var $hasBeenRead;
	var $hasPriority;
	var $sendDate;
	var $mailSize;
		
	function EMail($realBody,$mimeBody,$user,$mailID,$folderID,$toAddress,$fromAddress
						,$ccAddress,$bccAddress,$subject,$hasAttach,
						$hasBeenRead,$sendDate,$mailSize,$hasPriority,$imgNames,$imgBodies,$attNames,$attBodies) {
		$this->imgNames = $imgNames;
		$this->imgBodies = $imgBodies;
		$this->attNames = $attNames;
		$this->attBodies = $attBodies;
		$this->user = $user;
		$this->mimeBody = $mimeBody;
		$this->realBody = $realBody;
		$this->mailID = $mailID;
		$this->folderID = $folderID;
		$this->toAddress = $toAddress;
		$this->fromAddress = $fromAddress;
		$this->ccAddress = $ccAddress;
		$this->bccAddress = $bccAddress;
		$this->subject = $subject;
		$this->hasAttach = $hasAttach;
		$this->hasBeenRead = $hasBeenRead;
		$this->hasPriority = $hasPriority;
		$this->sendDate = $sendDate;
		$this->mailSize = $mailSize;
	}
		
	function save() {
		mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID);
		mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID."/attachments/");
		for ($i=0;$i<sizeof($this->attNames);$i++) {
			mkFile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID."/attachments/".stripErrors($this->attNames[$i]),$this->attBodies[$i]);
		}
		for ($i=0;$i<sizeof($this->imgNames);$i++) {
			mkFile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID."/".$this->imgNames[$i],$this->imgBodies[$i]);
		}
		mkFile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID."/".MIME_DOC,$this->mimeBody);
		mkFile(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->user.WEB_MAIL_DIR.$this->mailID."/".MAIL_DOC,$this->realBody);
		$insertionQuery = "INSERT INTO ".MAIL_TABLE_NAME." VALUES ('".$this->mailID."',".$this->folderID.",'".$this->toAddress."','".$this->fromAddress."','".$this->ccAddress."','".$this->bccAddress."','".$this->subject."',".$this->hasAttach.",".$this->hasBeenRead.",'".$this->sendDate."',".$this->mailSize.",".$this->hasPriority.");";						
		$ok = mysql_db_query(DB_NAME,$insertionQuery);
	}
}





?>
