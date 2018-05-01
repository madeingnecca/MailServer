/*  -----------------------------------------
	C.ZUCCANTE AS 2004-2005 5 ISE
	Seno Damiano, Doria Luca, Marchese Andrea
	POP3Constraints.java
	Costanti del
	SIMPLE MAIL TRANSFER PROTOCOL
	piu' alcune definite dagli
	sviluppatori.
    ----------------------------------------
*/

public class SMTPConstraints
{
	/* Porta TCP Standard per il servizio SMTP*/
	public static final int PORT = 					25;

	public static final String CRLF = 				"\r\n";

	/* Stati del servizio secondo RFC 821*/
	public static final byte AUTHORIZATION =		1;
	public static final byte TRANSACTION	=		2;
	public static final byte UPDATE = 				3;
	public static final String[] STATES = 			{"Autenticazione","Transazione","Aggiornamento"};
	public static final int 	 TIMEOUT = 			120;
	/*  */

	/* Messaggi positivi e negativi secondo RFC 821*/
	public static final String POSITIVE = 			"250";
	public static final String NEGATIVE = 			"550";

	/*  Caratteristiche del server (modificabile dall'utente)*/
	/*  Tutte le strighe (tranne l'ip) devono cominciare con uno spazio )*/

	public static final String DOMAIN_NAME =		"coolmail.com";
	//public static final String SRV_ADDRESS = 		"127.0.0.1";
	public static final String SRV_WELCOME =		"220 SMTP CoolMail Server vi da il benvenuto";
	public static final String NO_COMMAND =			NEGATIVE+" Comando sconosciuto o non valido nello stato di: ";
	public static final String PARAM_RQ = 			" Parametro richiesto";
	public static final String CMD_RQ = 			" Comando richiesto";
	public static final String NO_SUCH_USER = 		"550 Utente inesistente nel dominio "+DOMAIN_NAME;
	public static final String FOREIGN_USER = 		NO_SUCH_USER+" inoltra a ";
	public static final String USER_OK = 			"250 Utente presente";
	public static final String AUTH_METH_NOT_SUP =  "503 Metodo di autentificazione non valido";
	public static final String NO_MORE_AUTH =  		"504 Autenticazione già eseguita";
	public static final String START_MAIL = 		"354 Scrivi la mail e termina con CRLF.CRLF";
	public static final String MAIL_READ = 			"250 Mail Letta";
	public static final String BYEBYE	 =  		"221 CoolMail SMTP Server spera di rivederti presto";
	public static final String CMD_NOT_FOR_STATE = 	"";
	public static final String INTER_REPLY = 		"354 ";
	public static final String LOGFILES_DIR	=		"SMTP_Logs";
	public static final String[] CAPABILITIES = 	{"RCPT TO:","MAIL FROM:","HELO","EHLO","DATA","NOOP","VRFY","RSET","QUIT"};
}
