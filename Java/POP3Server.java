/*
----------------------------------------
C.ZUCCANTE AS 2004-2005 5 ISE
Seno Damiano, Doria Luca,Marchese Andrea

POP3Server.java
Server in ascolto di eventuali
client POP3 da gestire tramite
dei POP3ClientHandler
----------------------------------------
*/

import java.net.*;
import java.io.*;

public class POP3Server
{
	private ServerSocket	connessioneAllaPorta;  	/* socket lato server per l'ascolto in una determinata porta */

	public POP3Server() throws Exception
	{
		connessioneAllaPorta = new ServerSocket(POP3Constraints.PORT);
		int processID = 0;
		while(true)
			new POP3ClientHandler(connessioneAllaPorta.accept(),processID++);	/* lancio dei POP3ClientHandler dopo l'avvenuta */
																			/* connessione di un client POP3 */
	}

	public static void main(String[] args) throws Exception {
		new POP3Server();
	}
}