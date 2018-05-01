<?PHP
require_once("dbConstraints.php");
require_once('Mail_Mime-1.2.1/mime.php');
require_once('Mail_Mime-1.2.1/mimeDecode.php');
require_once("utility.php");
require_once("SMTPClient.php");

class User
{
	var $userName;
	var $password;

	function User($username,$password) {
		$this->userName = $username;
		$this->password = $password;
	}
	
	function makeUserDirs() {
		mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->userName);
		mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->userName."/".TMP_FILES_DIR);
		mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->userName."/".TMP_IMGS_DIR);
	}
	
	function create() {
		$userNotExists = mysql_db_query(DB_NAME,"INSERT INTO ".USERS_TABLE_NAME." (username,password) VALUES ('".$this->userName."','".$this->password."');");
		if ($userNotExists) {
			if (file_exists(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR)) {
				if (!file_exists(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR."/".$this->userName))
					$this->makeUserDirs();
			}
			else {
				mkdir(WEB_SRV_PARENT_DIR.WEB_SRV_USERS_DIR);
				$this->makeUserDirs();
			}
			mysql_db_query(DB_NAME,"INSERT INTO ".FOLDER_TABLE_NAME." (description,username) VALUES ('".NEW_MAIL_FOLDER."','".$this->userName."');");		
			mysql_db_query(DB_NAME,"INSERT INTO ".FOLDER_TABLE_NAME." (description,username) VALUES ('".SENT_MAIL_FOLDER."','".$this->userName."');");
			mysql_db_query(DB_NAME,"INSERT INTO ".FOLDER_TABLE_NAME." (description,username) VALUES ('".DELETED_MAIL_FOLDER."','".$this->userName."');");
			mysql_db_query(DB_NAME,"INSERT INTO ".FOLDER_TABLE_NAME." (description,username) VALUES ('".DRAFTS_FOLDER."','".$this->userName."');");
			
			/* INVIO EMAIL DI BENVENUTO */
			if ($this->userName != ADMIN_USER) {
				$hdrs = array(
				  'From'    => ADMIN_USER."@coolmail.com",
				  'To' => $this->userName."@coolmail.com",
				  'Subject' => "Benvenuto in coolmail!",
				  'Date' => date("Y-m-d H:i:s"),
				  'X-Mailer' => 'CoolMail',
				  'x-priority' => 1
				  );
				
				$rteHtml = "<div style='font-family:Verdana;font-size:8pt;'>Ciao <b>".$this->userName."</b>! Grazie per aver scelto CoolMail, non te ne pentirai! :-) ";
				$rteHtml.= "<br><hr><br> Why CoolMail? 'Cause it's cool! <br> <img src='ice.gif'></div>";
				$mime = new Mail_mime(EOL);
				$text = strip_tags($rteHtml);
				
				$mime->setTXTBody($text);
				$mime->setHTMLBody($rteHtml);
				$mime->addHTMLImage("images/ice.gif","image/gif;","",TRUE);
				$mimeMessage = $mime->get();
				$hdrs = $mime->headers($hdrs);
				$strHeaders = $mime->txtHeaders();
			
				$mimeMessage = $strHeaders.EOL.$mimeMessage;
				
				$smtpClient = new SMTPClient();
				$smtpClient->connect();
				$smtpClient->executeCommand("HELO");
				$smtpClient->executeCommand("MAIL FROM:",ADMIN_USER."@coolmail.com");
				$smtpClient->executeCommand("RCPT TO:",$this->userName."@coolmail.com");
				ob_start(); 
				$smtpClient->executeCommand("Data",$mimeMessage);
				$smtpClient->disconnect();
				ob_end_flush();
			}
			return TRUE;
		}
		return FALSE;
	}
}


?>
