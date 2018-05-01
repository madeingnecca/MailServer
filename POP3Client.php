<?PHP
/****************************************
-----------------------------------------
C.ZUCCANTE AS 2004-2005 5 ISE
Seno Damiano, Doria Luca, Marchese Andrea


CODED BY 5ISE
****************************************/

include("utility.php");
include("AbstractClient.php");
define("POP3_ERROR","Errore nella connessione al server POP3");

class POP3Client extends AbstractMailClient
{
	var $username;
	var $password;
	var $safeMode;
	
	function POP3Client($strUsername,$strPassword,$safeMode=FALSE) {
		$this->username = $strUsername;
 		$this->password = $strPassword;
		$this->safeMode = $safeMode;
	}
	
	function connect() {
		$this->socketFromClientToServer = fsockopen(POP3_SERVER_ADDRESS,POP3_PORT);
		$greetingBanner = $this->socketReadLine();
		if ($this->safeMode){
			$tokens = explode("<",trim($greetingBanner,EOL));
			$idAndTime = "<".$tokens[1];
			$md5param = $idAndTime.$this->password;
			$digest = md5($md5param);
			$this->socketPrintLine("APOP ".$this->username." ".$digest);	
		}
		else {
			$this->socketPrintLine("USER ".$this->username);
			$this->socketReadLine();
			$this->socketPrintLine("PASS ".$this->password);
		}

		return $this->positiveResponse();
	}

	function readMail() {
		$message = "";
		$fromServer = $this->socketReadLine();
		while (trim($fromServer,EOL)!==".") {
			if (isAllMadeOf(trim($fromServer,EOL),".")) {
				$tempMsg = trim($fromServer,EOL);
				$message.= substr($tempMsg,0,strlen($tempMsg)-1).EOL;
			}
			else
				$message.=$fromServer;
			$fromServer=$this->socketReadLine();
		}
		return $message;
	}
	
	function executeCommand($command, $params = NULL) {
		if ($command == "RETR") {
			if (is_array($params))
				$params = $params[0];
			$this->socketPrintLine("RETR ".$params);
			return $this->positiveResponse()? $this->readMail() : FALSE;
		}
		else if ($command == "STAT") {
			$this->socketPrintLine("STAT");
			$tokens = explode(" ",$this->socketReadLine());
			$numMessages = $tokens[1];
			return intval($numMessages);
		}
		else if ($command == "DELE") {
			$this->socketPrintLine("DELE ".$params);
			$this->socketReadLine();
		}
	}
	
	function disconnect() {
		$this->socketPrintLine("QUIT");
		fclose($this->socketFromClientToServer);
	}
	
}
?>
