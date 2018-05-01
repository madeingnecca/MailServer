import java.io.*;
import java.net.*;
import java.util.*;
import java.sql.*;

public class SMTPServer
{
	int IDClient=0;
	public void start() throws Exception
	{
		ServerSocket serverSocket = new ServerSocket(SMTPConstraints.PORT);
		while(true)
		{
			Socket socket = serverSocket.accept();
			SMTPClientHandler serverThread = new SMTPClientHandler(socket, IDClient++);
		}
	}

	private static void fixPacketMaxSize() throws Exception {
		Class.forName(Installer.SQL_DRIVER).newInstance();
		Connection mySqlConnection = DriverManager.getConnection(Installer.DB_PATH);
		Statement stmt = mySqlConnection.createStatement();
		stmt.addBatch("SET GLOBAL max_allowed_packet = 10000000;");
		stmt.executeBatch();
		mySqlConnection.close();
	}

	public static void main (String[] args)  throws Exception
	{
		fixPacketMaxSize();
		new SMTPServer().start();
	}
}