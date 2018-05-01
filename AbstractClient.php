<?php
require_once("costantiServer.php");

class AbstractMailClient
{
	var $socketFromClientToServer;
	
	/*
		socketPrintLine($theSocket,$cosaScrivere)
		-------------------------------------------
		Scrive su stream il messaggio $cosaScrivere
		più il carattere \n
	*/
	function socketPrintLine($cosaScrivere){
		$paramCorretto = $cosaScrivere.EOL;
		fputs($this->socketFromClientToServer,$paramCorretto);
	}
	
	/*
		socketReadLine($theSocket)
		--------------------------
		Legge dallo stream e restituisce
		una stringa terminante con CRLF
		NB: CRLF --> \r\n ( WIN )
	*/
	function socketReadLine(){
		return fgets($this->socketFromClientToServer);
	}
	
	/*
		positiveResponse($theMsg)
		--------------------------------------
		Restituisce vero se una stringa
		inizia con +OK, ovvero se il server
		POP3 ha inviato una risposta positiva
		falso in caso contrario.
	*/
	function positiveResponse() {
		return substr(trim($this->socketReadLine()),0,3)=="+OK";
	}

	function connect() {
	
	}

	function executeCommand($command, $params) {

	}
	


	function disconnect() {
		
	}
		
}

?>
