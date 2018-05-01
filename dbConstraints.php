<?php
	/* Impostazioni DB */
	
	define("SITE_ADDRESS","localhost/MailServer"); // specificare assolutamente se non gira in locale!!!!
	define("DB_ADDRESS","localhost");
	define("DB_NAME","mailDB");
	define("USERS_TABLE_NAME","utenti");
	define("MAILDROP_TABLE_NAME","mailDrop");
	define("MAIL_TABLE_NAME","mail");
	define("FOLDER_TABLE_NAME","cartelle");
	define("CONTACTS_TABLE_NAME","contatti");
	define("USERNAME_DB","");
	define("PASSWORD_DB","");
	define("MYSQL_ERROR","Errore nella connessione con MySQL: controllare i parametri mysql nel file dbCostraints.php");
	define("DB_ERROR","Errore nella connessione con il database: controllare i parametri mysql nel file dbCostraints.php");
	define("NEW_MAIL_FOLDER","Posta in arrivo");
	define("SENT_MAIL_FOLDER","Posta inviata");
	define("DELETED_MAIL_FOLDER","Posta eliminata");
	define("DRAFTS_FOLDER","Bozze");
	define("ADMIN_USER","admin");
	define("ADMIN_PASS","admin");
	
	/* Impostazioni cartelle su web server */
	
	define("WEB_SRV_PARENT_DIR",$_SERVER['DOCUMENT_ROOT']."/MailServer");
	define("WEB_SRV_USERS_DIR","/CoolMailUsers");
	define("TMP_FILES_DIR","tempFiles");
	define("TMP_IMGS_DIR","tempImgs");
	define("WEB_MAIL_DIR","/webmail ");
	define("MAIL_DOC","mail.html");
	define("MIME_STRUCT","mimeStructure.txt");
	define("MIME_DOC","originalMime.txt");
	define("USERNAME_SESSION_INDEX","coolMailUserName");
	define("PASSWORD_SESSION_INDEX","coolMailUserPass");
	define("MEMORY_SESSION_INDEX","coolMailUserFreeMemory");
	define("USERNAME_COOKIE_INDEX","coolMailUserName");
	define("PASSWORD_COOKIE_INDEX","coolMailUserPass");
	define("MAILID_GETVARS_INDEX","mailID");		
	define("REMEMBER_COOKIE_INDEX","coolMailRem");
	define("FOLDER_GETVARS_INDEX","folder");
	define("GET_MAIL","get");
	define("ACTION_GETVARS_INDEX","action");
	
	define("TOTAL_MEMORY",8388608);
	
?>
