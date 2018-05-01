<?PHP
	/* POP3 */
	define("POP3_PORT",110);
	define("POP3_SERVER_ADDRESS","localhost");
	
	/* SMTP */
	define("SMTP_PORT",25);
	define("SMTP_SERVER_ADDRESS","localhost");
	
	if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN'))
		define("EOL","\r\n");
	elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC'))
		define("EOL","\r");
	else  /* LINUX */
		define("EOL","\n");
?>
