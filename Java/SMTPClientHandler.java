/*  -----------------------------------------
	C.ZUCCANTE AS 2004-2005 5 ISE
	Seno Damiano, Doria Luca, Marchese Andrea
	POP3ClientHandler.java
	SIMPLE MAIL TRANSFER PROTOCOL
	La classe permette di fornire servizi ad
	un client SMTP.
    ----------------------------------------
*/

import java.net.*;		/* package per i socket */
import java.io.*;		/* package per gli stream*/
import java.util.*;		/* package per gli StringTokenizer e le date */
import java.sql.*;		/* package per la comunicazione con il D-BASE*/

public class SMTPClientHandler extends ClientHandler
{
	private int IDClient;
	private String 				SMTPcommand;  					/* comando SMTP */
	private String 				sender;							/* mittente della mail */
	private Vector 				addresses;						/*  indirizzi a cui è destinata la mail */
	private String				username;						/* username del destinatario ( deve essere del DB di coolmail */
	private StringBuffer 		eMailData = null;				/* contenuto del messaggio ( in MIME ) */


	/*
		SMTPClientHandler
		-----------------
		Inizializzazione dei parametri
	*/
	public SMTPClientHandler (Socket socket, int IDClient) {
		System.out.println(socket+" connesso");
		this.connessioneAlClient = socket;
		this.IDClient = IDClient;
		try
		{
			outputStream = new PrintWriter(connessioneAlClient.getOutputStream(),true);
			inputStream = new BufferedReader(new InputStreamReader(connessioneAlClient.getInputStream()));
			addresses = new Vector();
			generateLogFile();
			engine = new Thread(this);
			engine.start();
		}
		catch(IOException streamFailure)
		{
			System.out.println("Problemi nella creazione degli stream con "+connessioneAlClient);
		}
	}

	/*
	int verifyUser(indirizzoUtente)
	*******************************

	Restituisce:
	------------
		1 : utente trovato nel db
		0 : utente non trovato nel db
	   -1 : utente non appartenente al dominio coolmail
	---------------------------------------------------

	*/
	private int verifyUser(String user)
	{
		boolean found = false;
		try
		{

			StringTokenizer userFinder = new StringTokenizer(user,"@");
			String theUser = userFinder.nextToken();
			String theDomain = userFinder.nextToken();
			username = new StringTokenizer(theUser,"<").nextToken();
			theDomain = new StringTokenizer(theDomain,">").nextToken();

			if (!theDomain.equals(SMTPConstraints.DOMAIN_NAME)) {
				outputStream.println(SMTPConstraints.FOREIGN_USER);
				return -1;
			}

			createConnectionToDb();
			recordSet = theStatement.executeQuery("SELECT * FROM "+DBConstraints.USERS_TABLE_NAME+";");
			while (recordSet.next() && !found) {
				found = recordSet.getString("username").equals(username);
			}

			closeConnectionToDb();
			recordSet.close();
		}
		catch (Exception e){
			return 0;
		}

		return found? 1: 0;
	}

	/*
		boolean isCommonCmd(comando)
		******************************

		Restituisce:
		-----------------
			TRUE  : se il comando è HELP o NOOP
					il tal caso esegue anche i
					rispettivi comandi

			FALSE : altrimenti
		---------------------------------------

	*/
	private boolean isCommonCmd(String SMTPcommand)
	{
		if(SMTPcommand.equals("HELP"))
		{
			String[] capabilities = SMTPConstraints.CAPABILITIES;
			outputStream.println("I comandi disponibili sono:");
			for (int i=0;i<capabilities.length;i++) {
				outputStream.println(capabilities[i]);
			}
		}
		else if(SMTPcommand.equals("NOOP"))
			outputStream.println("250");
		else
			return false;

		return true;
	}

	/*
		void run()
		-----------------------------------
		Implementazione del protocollo SMTP
	*/
	public void run()
	{
		timeoutChecker = new TimeoutChecker(this,SMTPConstraints.TIMEOUT,1000);		/* istanzio e lancio nuovo timeout */
		timeoutChecker.start();
		outputStream.println(SMTPConstraints.SRV_WELCOME+" "+idAndTime);			/* mando al client msg di benvenuto */
		statoServizio = SMTPConstraints.AUTHORIZATION;					/* inizializzo il servizio a " Autenticazione "*/
		boolean smtpSessionOver = false;								/* "attivo" la sessione smtp */
		try
		{
			while (!smtpSessionOver)							/* finchè la sessione e' attiva */
			{
				userInput = statoServizio == SMTPConstraints.UPDATE? "" : inputStream.readLine();	/* leggo da tastiera solo se il servizio non è in UPDATE*/
				logFileWriter.println(connessioneAlClient+" "+userInput);
				paramFinder = new StringTokenizer(userInput," ");
				SMTPcommand=paramFinder.hasMoreElements()? paramFinder.nextToken().toUpperCase() : null;
				byte mask = (SMTPcommand == null || SMTPcommand.equals("")) && statoServizio != SMTPConstraints.UPDATE ? 0x0 : (byte) 0xFF;
				switch (statoServizio & mask)
				{
					case SMTPConstraints.AUTHORIZATION:
					{
						if(SMTPcommand.equals("HELO"))
						{
							statoServizio = SMTPConstraints.TRANSACTION;
							outputStream.println("250 OK");
						}
						else if(SMTPcommand.equals("EHLO"))
						{
							statoServizio = SMTPConstraints.TRANSACTION;
							outputStream.println("250 OK");
						}
						else if(SMTPcommand.equals("QUIT"))
						{
							smtpSessionOver = true;
							inputStream.close();
							outputStream.println(SMTPConstraints.BYEBYE);
						}
						else if (!isCommonCmd(SMTPcommand))
							notifyWrongCommand();
						break;
					}
					case SMTPConstraints.TRANSACTION:
					{
						if(SMTPcommand.equals("DATA"))
						{
							outputStream.println(SMTPConstraints.START_MAIL);
							eMailData = new StringBuffer();
							eMailData.append("Return-Path: "+sender+SMTPConstraints.CRLF);
							eMailData.append("Received: from ESMTP."+SMTPConstraints.DOMAIN_NAME+SMTPConstraints.CRLF);
							String fromClient = inputStream.readLine();
							while (!fromClient.equals(".")) {
								eMailData.append(fromClient+SMTPConstraints.CRLF);
								logFileWriter.println(fromClient);
								fromClient = inputStream.readLine();
							}
							outputStream.println(SMTPConstraints.MAIL_READ);
						}
						else if(SMTPcommand.equals("RSET"))
						{
							outputStream.println("250");
							addresses = new Vector();
							eMailData = null;
						}
						else if(SMTPcommand.equals("VRFY"))
						{
							String user =paramFinder.hasMoreElements()? paramFinder.nextToken().trim() : "";
							if(!user.equals(""))
							{//verifico se coincide con un user//
								int userExistence = verifyUser(user);
								if (userExistence==1)
									outputStream.println(SMTPConstraints.USER_OK);
								else if (userExistence==0)
									outputStream.println(SMTPConstraints.NO_SUCH_USER);
							}
						}
						else if (SMTPcommand.equals("MAIL"))
						{
							String secondCmdPart = paramFinder.hasMoreElements()? paramFinder.nextToken().toUpperCase() : "";
							if(secondCmdPart.equals("FROM:"))
							{
								sender = paramFinder.hasMoreElements()? paramFinder.nextToken() : "unknown [" + connessioneAlClient.getInetAddress().getHostName()+"]";
								if(sender!=null)
								{
									outputStream.println("250");
								}
								else
								{
									outputStream.println("Inserire nome mittente!");
								}
							}
							else
								notifyWrongCommand();
						}
						else if(SMTPcommand.equals("RCPT"))
						{
							String secondCmdPart=paramFinder.hasMoreElements()? paramFinder.nextToken().toUpperCase() : "";
							if(secondCmdPart.equals("TO:"))
							{
								username=paramFinder.hasMoreElements()? paramFinder.nextToken() : null;
								if(username!=null)
								{
									int userExistence = verifyUser(username);
									if (userExistence==1) {
										outputStream.println(SMTPConstraints.USER_OK);
										logFileWriter.println(SMTPConstraints.USER_OK);
										addresses.add(username);
									}
									else if (userExistence==0)
									{
										outputStream.println(SMTPConstraints.NO_SUCH_USER);
										username = null;
									}
								}
								else
								{
									outputStream.println("Inserire nome destinatario!");
								}
							}
							else
								notifyWrongCommand();
						}
						else if (SMTPcommand.equals("QUIT"))
						{
							timeoutChecker.die();
							outputStream.println(SMTPConstraints.BYEBYE);
							inputStream.close();
							logFileWriter.close();
							statoServizio = SMTPConstraints.UPDATE;
						}
						else if (!isCommonCmd(SMTPcommand))
							notifyWrongCommand();
						break;
					}
					case SMTPConstraints.UPDATE:
					{
						if (eMailData != null )
						{
							createConnectionToDb();
							for (int i=0;i<addresses.size();i++) {
								String tempUser = (String)(addresses.get(i));
								PreparedStatement pstmt = SQLConnection.prepareStatement("INSERT INTO "+DBConstraints.MAIL_TABLE_NAME+" (username,message) VALUES (?,?);");
								pstmt.setString(1,tempUser);
								pstmt.setString(2,eMailData.toString());
								pstmt.executeUpdate();
							}
							closeConnectionToDb();
						}
						smtpSessionOver = true;
						break;
					}
					default:
						outputStream.println(SMTPConstraints.CMD_RQ);
				}
			}
		}
		catch (Exception e)
		{
			/* notifico di un'avvenuta eccezione solamente se il timeout è attivo */
			/* in quanto se non è attivo (timeout raggiunto ) lo stesso timeoutchecker causa un'eccezione*/
			/* chiudendo la connessione */

			if (!timeIsOver)
				e.printStackTrace();
			if (!(e instanceof IOException))
				logFileWriter.close();
		}
	}
}