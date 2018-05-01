/*  -----------------------------------------
	C.ZUCCANTE AS 2004-2005 5 ISE
	Seno Damiano, Doria Luca, Marchese Andrea
	POP3Constraints.java
	Costanti del
	POST OFFICE PROTOCOL VERSION 3
	piu' alcune definite dagli
	sviluppatori.
    ----------------------------------------
*/

public class POP3Constraints
{
	/* Porta TCP Standard per il servizio POP3*/
	public static final int PORT = 					110;

	/* Stati del servizio secondo RFC 1939*/
	public static final byte AUTHORIZATION =		1;
	public static final byte TRANSACTION	=		2;
	public static final byte UPDATE = 				3;
	public static final String[] STATES = 			{"Autenticazione","Transazione","Aggiornamento"};
	public static final int TIMEOUT = 				120;
	/*  */

	public static final String CRLF = 				"\r\n";

	/* Messaggi positivi e negativi secondo RFC 821*/
	public static final String POSITIVE = 			"+OK";
	public static final String NEGATIVE = 			"-ERR";

	/*  Caratteristiche del server (modificabile dall'utente)*/
	/*  Tutte le strighe (tranne l'ip) devono cominciare con uno spazio )*/
	public static final String DOMAIN_NAME =		"coolmail.com";
	public static final String SRV_ADDRESS = 		"127.0.0.1";
	public static final String SRV_WELCOME =		" Benvenuti al POP3 Server";
	public static final String NO_COMMAND =			NEGATIVE+" Comando sconosciuto o non valido nello stato di: ";
	public static final String ALREADY_LOCKED =		NEGATIVE+" MailBox gia' aperta!";
	public static final String PARAM_RQ = 			" Parametro richiesto";
	public static final String CMD_RQ = 			" Comando richiesto";
	public static final String ONCLOSE = 			POSITIVE+" "+DOMAIN_NAME+" POP3 Server vi saluta";
	public static final String LOGFILES_DIR	=		"POP3_Logs";
	public static final String NO_SUCH_MESSAGE = 	" Messaggio inesistente!";
	public static final String ALREADY_DELETED = 	" Messaggio gia' cancellato!";
	public static final String WRONG_DIGEST = 		" Digest sbagliato!";
	public static final String RIGHT_DIGEST = 		" Digest corretto!";
	public static final String[] CAPABILITIES = 	{"USER","PASS","APOP","NOOP","RETR","UIDL","STAT","LIST","QUIT"};
}
