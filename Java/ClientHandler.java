/****************************************
-----------------------------------------
C.ZUCCANTE AS 2004-2005 5 ISE
Seno Damiano, Doria Luca, Marchese Andrea

ClientHandler.java
-------------------
Classe madre del POP3ClientHandler
e del SMTPClientHandler
-----------------------------------------
/***************************************/

import java.net.*;		/* package per i socket */
import java.io.*;		/* package per gli stream*/
import java.util.*;		/* package per gli StringTokenizer e le date */
import java.sql.*;		/* package per la comunicazione con il D-BASE*/

/****************************/


public class ClientHandler implements Runnable
{
	protected Thread					engine;							/* il thread che esegue la runnable */
	protected Socket 					connessioneAlClient;			/* connessione al client */
	protected PrintWriter 				outputStream;					/* stream di out */
	protected PrintWriter				logFileWriter;					/* stream di scrittura sul file di log */
	protected BufferedReader 			inputStream;					/* stream di in*/
	protected StringTokenizer			paramFinder;					/* individua gli eventuali cmd e param*/
	protected String					userInput;						/* input dal client */
	protected Connection				SQLConnection;					/* connessione al DB */
	protected Statement					theStatement;					/* oggetto per l'esecuzione di query */
	protected ResultSet 				recordSet;						/* recordset contentente il risultato di una query*/
	protected int 						statoServizio;					/* stato del servizio */
	protected boolean					timeIsOver = false;				/* indica se il tempo è finito. ( timeout raggiunto )*/
	protected TimeoutChecker			timeoutChecker;					/* controllore del timeout */
	protected String					idAndTime;
	protected int 						processID;

	/*
		void run()
		-------------------------
		il metodo va implementato
		solo dalle classi figlie
	*/
	public void run() {
	}

	/*
		void createConnectionToDb()
		----------------------------
		crea la connessione al DB e inizializza
		un nuovo statement per l'esecuzione di una query
	*/
	protected void createConnectionToDb() throws Exception {
		Class.forName(DBConstraints.SQL_DRIVER).newInstance();
		SQLConnection = DriverManager.getConnection(DBConstraints.DB_PATH+DBConstraints.DB_NAME,DBConstraints.USERNAME_DB,DBConstraints.PASSWORD_DB);
		theStatement = SQLConnection.createStatement();
	}

	/*
		void closeConnectionToDb()
		---------------------------
		chiude la connessione al DB
	*/
	protected void closeConnectionToDb() throws Exception {
		theStatement.close();
		SQLConnection.close();
	}

	/*
		void notifyWrongCommand()
		---------------------------
		nofica al client che il cmd
		inserito non è valido nello
		stato della sessione oppure
		non è supportato
	*/
	protected void notifyWrongCommand() {
		String[] states;
		String error;
		if (this instanceof POP3ClientHandler)
		{
			states = POP3Constraints.STATES;
			error = POP3Constraints.NO_COMMAND;
		}
		else
		{
			error = SMTPConstraints.NO_COMMAND;
			states = SMTPConstraints.STATES;
		}
		outputStream.println(error+states[statoServizio-1]);
	}

	/*
		void timeExceeded()
		----------------------
		Segnala che il tempo e scaduto
		e si disconnette dal client
	*/
	public void timeExceeded() {
		try
		{
			timeIsOver = true;
			outputStream.println("Timeout Raggiunto");
			inputStream.close();
		}
		catch (Exception e){
		}
	}

	/*

	*/

	protected void generateLogFile() throws IOException {
		idAndTime=String.valueOf(processID)+"."+new java.util.Date().getTime();
		idAndTime+="@"+new String(this instanceof POP3ClientHandler? POP3Constraints.DOMAIN_NAME : SMTPConstraints.DOMAIN_NAME).toLowerCase();
		logFileWriter = new PrintWriter(new FileWriter((this instanceof POP3ClientHandler? POP3Constraints.LOGFILES_DIR : SMTPConstraints.LOGFILES_DIR)+System.getProperty("file.separator")+idAndTime+".txt"),true);
		idAndTime = "<" + idAndTime +">";
	}

}