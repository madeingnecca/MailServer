<?PHP
/****************************************
-----------------------------------------
C.ZUCCANTE AS 2004-2005 5 ISE
Seno Damiano, Doria Luca, Marchese Andrea


CODED BY 5ISE
****************************************/

require_once("utility.php");
require_once("AbstractClient.php");

class SMTPClient extends AbstractMailClient
{
	function SMTPClient() {
	
	}
	
	function connect() {
		$this->socketFromClientToServer = fsockopen(SMTP_SERVER_ADDRESS,SMTP_PORT);
		$greetingBanner = $this->socketReadLine();
	}

	function positiveResponse($command) {
		if ($command == "QUIT")
			return substr(trim($this->socketReadLine()),0,3) == "221";
		else if ($command == "RCPT TO:")
			return substr(trim($this->socketReadLine()),0,3) == "250";
	}	
	
	function executeCommand($command, $params=NULL) {
		$cmd = strtoupper($command);
		if ($cmd == "HELO" || $cmd == "EHLO") {
			$this->socketPrintLine($cmd);
			$this->socketReadLine();
		}
		else if ($cmd == "DATA") {
			$this->socketPrintLine($cmd);
			$this->socketReadLine();
			$lines = explode(EOL,$params);
			$params='';
			for ($i=0;$i<sizeof($lines);$i++){
				//$params.=$lines[$i].EOL;
				$singleLine = $lines[$i];
				if (isAllMadeOf($singleLine,"."))
					$singleLine.=".";
				$this->socketPrintLine($singleLine);
			}
			//$this->socketPrintLine($params);
			$this->socketPrintLine(".");
			$this->socketReadLine();
		}
		else if ($cmd == "MAIL FROM:") {
			$this->socketPrintLine($cmd." ".$params);
			$this->socketReadLine();
		}		
		else if ($cmd == "RCPT TO:") {
			$this->socketPrintLine($cmd." ".$params);
			return $this->positiveResponse("RCPT TO:");
		}
	}
	
	
	function disconnect() {
		$this->socketPrintLine("QUIT");
		while (!$this->positiveResponse("QUIT"));
		fclose($this->socketFromClientToServer);
	}
}


?>
