/****************************************
-----------------------------------------
C.ZUCCANTE AS 2004-2005 5 ISE
Seno Damiano, Doria Luca, Marchese Andrea

POP3ClientHandler.java
La classe permette di fornire
servizi ad un client POP3
-----------------------------------------
/***************************************/

import java.net.*;		/* package per i socket */
import java.io.*;		/* package per gli stream*/
import java.util.*;		/* package per gli StringTokenizer e le date */
import java.sql.*;		/* package per la comunicazione con il D-BASE*/

/****************************/

public class POP3ClientHandler extends ClientHandler
{
	private String 					pop3Command;
	private String 					userName;
	private String 					userPass;
	private int						messagesCount;
	private int						mailBoxSize;
	private EMail[]					messages;
	private boolean 				mailBoxSeen;
	private boolean 				mailBoxFree;
	private MD5						crypter;

	public POP3ClientHandler(Socket connessioneAlClient,int processID) throws IOException
	{
		statoServizio = POP3Constraints.AUTHORIZATION;
		this.connessioneAlClient = connessioneAlClient;
		this.processID = processID;
		paramFinder = null;
		mailBoxSeen = false;
		userInput = userName = userPass = pop3Command = null;
		try
		{
			outputStream = new PrintWriter(connessioneAlClient.getOutputStream(),true);
			inputStream = new BufferedReader(new InputStreamReader(connessioneAlClient.getInputStream()));
			generateLogFile();
			engine = new Thread(this);
			engine.start();
		}
		catch(IOException streamFailure)
		{
			System.out.println("Problemi nella creazione degli stream con "+connessioneAlClient);
		}
	}

	private void restoreRs() throws Exception
	{
		recordSet.first();
		recordSet.previous();
	}

	private boolean lockMailbox() throws Exception {
		String checkForLocked = "SELECT * FROM "+DBConstraints.USERS_TABLE_NAME+" WHERE username='"+userName+"' AND isLocked = 1;";
		recordSet = theStatement.executeQuery(checkForLocked);
		if (recordSet.next())
			return false;
		else
		{
			String lock = "UPDATE "+DBConstraints.USERS_TABLE_NAME+" SET isLocked = 1 WHERE username='"+userName+"';";
			theStatement.executeUpdate(lock);
			return true;
		}
	}

	private void unlockMailbox() throws Exception {
		String unlock = "UPDATE "+DBConstraints.USERS_TABLE_NAME+" SET isLocked = 0 WHERE username='"+userName+"';";
		theStatement.executeUpdate(unlock);
	}

	private void scanForMails() throws Exception
	{
		createConnectionToDb();
		if (!mailBoxSeen)
		{
			String sql = "SELECT * FROM "+DBConstraints.MAIL_TABLE_NAME;
			sql+=" WHERE username='"+userName+"' ORDER BY mailID ASC;";
			recordSet = theStatement.executeQuery(sql);
			recordSet.last();
			messagesCount = recordSet.getRow();
			restoreRs();
			mailBoxSeen = true;
			messages = new EMail[messagesCount];
			mailBoxSize = 0;
			for (int i=0;recordSet.next();i++)
			{
				messages[i] = new EMail(recordSet);
				mailBoxSize+= messages[i].getMessage().length();
			}
		}
	}

	public void run()
	{
		timeoutChecker = new TimeoutChecker(this,POP3Constraints.TIMEOUT,1000);
		timeoutChecker.start();
		String greetingBanner = POP3Constraints.POSITIVE+POP3Constraints.SRV_WELCOME;
		greetingBanner+=" "+idAndTime;
		outputStream.println(greetingBanner);
		boolean pop3SessionOver = false;
		try
		{
			while (!pop3SessionOver)
			{
				userInput = statoServizio == POP3Constraints.UPDATE? "" : inputStream.readLine();
				logFileWriter.println(connessioneAlClient+" "+userInput);
				paramFinder = new StringTokenizer(userInput," ");
				pop3Command = paramFinder.hasMoreElements()? paramFinder.nextToken() : null;

				byte mask = (pop3Command == null || pop3Command.equals("")) && statoServizio != POP3Constraints.UPDATE ? 0x0 : (byte) 0xFF;
				switch (statoServizio & mask)
				{
					case POP3Constraints.AUTHORIZATION:
					{
						if (pop3Command.equals("USER"))
						{
							userName = paramFinder.hasMoreElements()? paramFinder.nextToken() :null;
							if (userName!=null)
								outputStream.println(POP3Constraints.POSITIVE+" password?");
							else
								outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.PARAM_RQ);
						}
						else if (pop3Command.equals("PASS"))
						{
							userPass = paramFinder.hasMoreElements()? paramFinder.nextToken():null;
							if (userName == null)
								outputStream.println(POP3Constraints.NEGATIVE+" Inserire nome utente prima!");
							else
							{
								createConnectionToDb();
								recordSet = theStatement.executeQuery("SELECT * FROM "+DBConstraints.USERS_TABLE_NAME+" WHERE username='"+userName+"';");
								if (recordSet.next())
								{
									if (recordSet.getString("password").equals(userPass))
									{
										mailBoxFree = lockMailbox();
										if (mailBoxFree) {
											statoServizio = POP3Constraints.TRANSACTION;
											outputStream.println(POP3Constraints.POSITIVE);
										}
										else
											outputStream.println(POP3Constraints.ALREADY_LOCKED);
									}
									else
									{
										outputStream.println(POP3Constraints.NEGATIVE);
									}
								}
								else
								{
									outputStream.println(POP3Constraints.NEGATIVE);
								}
								closeConnectionToDb();
							}
						}
						else if (pop3Command.equals("APOP"))
						{
							userName = paramFinder.hasMoreElements()? paramFinder.nextToken() :null;
							String inputDigest = paramFinder.hasMoreElements()? paramFinder.nextToken() :null;
							if (userName!=null && inputDigest!=null)
							{
								createConnectionToDb();
								recordSet = theStatement.executeQuery("SELECT password FROM "+DBConstraints.USERS_TABLE_NAME+" WHERE username='"+userName+"';");
								if (recordSet.next())
								{
									crypter = new MD5();
									crypter.update(new String(idAndTime+recordSet.getString("password")).getBytes());
									String trueDigest = crypter.digestToString(crypter.digest());
									logFileWriter.println("True Digest "+trueDigest);
									if (inputDigest.equals(trueDigest))
									{
										mailBoxFree = lockMailbox();
										if (mailBoxFree) {
											statoServizio = POP3Constraints.TRANSACTION;
											outputStream.println(POP3Constraints.POSITIVE);
										}
										else
											outputStream.println(POP3Constraints.ALREADY_LOCKED);
									}
									else
									{
										outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.WRONG_DIGEST);
										theStatement.close();
										SQLConnection.close();
									}
								}
								else
									outputStream.println(POP3Constraints.NEGATIVE);
							}
							else
								outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.PARAM_RQ);

						}
						else if (pop3Command.equals("QUIT"))
						{
							outputStream.println(POP3Constraints.ONCLOSE);
							pop3SessionOver = true;
						}
						else
							notifyWrongCommand();
						break;
					}
					case POP3Constraints.TRANSACTION:
					{
						scanForMails();
						if (pop3Command.equals("STAT"))
						{
							outputStream.println(POP3Constraints.POSITIVE+" "+messagesCount+" "+mailBoxSize);
						}
						else if (pop3Command.equals("LIST"))
						{
							outputStream.println(POP3Constraints.POSITIVE+" "+messagesCount+" "+mailBoxSize);
							for (int messageID=0;messageID<messages.length;messageID++)
								outputStream.println((messageID+1)+" "+messages[messageID].getMessage().length());

							outputStream.println(".");
						}
						else if (pop3Command.equals("RETR"))
						{
							String strMessageID = paramFinder.hasMoreElements()? paramFinder.nextToken() : null;
							if (strMessageID == null)
								outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.PARAM_RQ);
							else
							{
								int messageID;
								try
								{
									messageID = Integer.parseInt(strMessageID)-1;
									EMail theEMail = messages[messageID];
									outputStream.println(POP3Constraints.POSITIVE+" "+theEMail.getMessage().length()+" octets ");
									String mimeMsg = theEMail.getMessage();
									outputStream.println(mimeMsg);
									logFileWriter.println(mimeMsg);
									outputStream.println(".");
								}
								catch(ArrayIndexOutOfBoundsException out)
								{
									outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.NO_SUCH_MESSAGE);
								}
								catch(NumberFormatException invalidFormat)
								{
									outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.NO_SUCH_MESSAGE);
								}
							}
						}
						else if (pop3Command.equals("UIDL"))
						{
							outputStream.println(POP3Constraints.POSITIVE);
							for (int messageID=0;messageID<messages.length;messageID++)
								outputStream.println((messageID+1)+" "+((Object)(messages[messageID])).hashCode());
							outputStream.println(".");
						}
						else if (pop3Command.equals("DELE"))
						{
							String strMessageID = paramFinder.hasMoreElements()? paramFinder.nextToken() : null;
							try
							{
								if (strMessageID == null)
									outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.PARAM_RQ);
								else
								{
									int messageID = Integer.parseInt(strMessageID)-1;
									if (messages[messageID].isToDelete())
										outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.ALREADY_DELETED);
									else
									{
										messages[messageID].setToDelete(true);
										outputStream.println(POP3Constraints.POSITIVE);
									}
								}
							}
							catch(ArrayIndexOutOfBoundsException out)
							{
								outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.NO_SUCH_MESSAGE);
							}
						}
						else if (pop3Command.equals("TOP"))
						{
							outputStream.println(POP3Constraints.POSITIVE);
						}
						else if (pop3Command.equals("NOOP"))
						{
							outputStream.println(POP3Constraints.POSITIVE);
						}
						else if (pop3Command.equals("RSET"))
						{
							for (int messageID=0;messageID<messages.length;messageID++)
								messages[messageID].setToDelete(false);
							outputStream.println(POP3Constraints.POSITIVE);

						}
						else if (pop3Command.equals("QUIT"))
						{
							statoServizio = POP3Constraints.UPDATE;
						}
						else
							notifyWrongCommand();
						break;
					}
					case POP3Constraints.UPDATE:
					{
						for (int i=0;i<messages.length;i++)
							if (messages[i].isToDelete())
								theStatement.executeUpdate("DELETE FROM "+DBConstraints.MAIL_TABLE_NAME+" WHERE mailID='"+messages[i].getMessageID()+"'");

						outputStream.println(POP3Constraints.ONCLOSE);
						pop3SessionOver = true;
						break;
					}
					default:
						outputStream.println(POP3Constraints.NEGATIVE+POP3Constraints.CMD_RQ);
				}

			}

			if (SQLConnection != null)
			{
				if (mailBoxFree)
				{
					createConnectionToDb();
					unlockMailbox();
					closeConnectionToDb();
				}
				theStatement.close();
				SQLConnection.close();
			}
			logFileWriter.close();
			inputStream.close();
			System.out.println("Chiusura chiamata con:"+connessioneAlClient);

		}
		catch (Exception failure)
		{
			try {
				if (mailBoxFree)
				{
					createConnectionToDb();
					unlockMailbox();
					closeConnectionToDb();
				}
			}
			catch (Exception exx) {

			}
			if (!timeIsOver)
				failure.printStackTrace();
		}
	}
}