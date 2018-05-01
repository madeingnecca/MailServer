<?php
	include('Mail_Mime-1.2.1/mime.php');
	include('Mail_Mime-1.2.1/mimeDecode.php');
	include("POP3Client.php");
	
	$pop3client = new POP3Client("damy_belthazor86","venice",FALSE);
	$okConn = $pop3client->connect();
	if ($okConn) {
		$message = $pop3client->executeCommand("RETR",1);
		if ($message === FALSE)
			echo "nessun messaggio";
	
		$pop3client->disconnect();
		
		$params['include_bodies'] = TRUE;
		$params['decode_bodies']  = TRUE;
		$params['include_headers']  = TRUE;
		
		//die($message);
		
		$decoder = new Mail_mimeDecode($message);
		$structure = $decoder->decode($params);
		
		
		
		flow($structure);
		/*
		$list = "";
		foreach ($structure->parts as $part) {
			// is this an image?
			if ($part->ctype_primary=='image') {
				$list .= $part->ctype_parameters['filename'].': '.strlen($part->body)." bytes\n";
			}
		}
	
		die($list);
		*/
	
	}
?>
