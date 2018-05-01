import java.sql.*;
/*
	Installa database e tabelle necessari al progetto.
*/

public class DBConstraints
{
	/* impostazioni D-BASE */
	public static final String SQL_DRIVER =	 		"com.mysql.jdbc.Driver";
	public static final String DB_NAME =			"mailDB";
	public static final String DB_PATH =			"jdbc:mysql://localhost:3306/";
	public static final String USERNAME_DB = 		"";
	public static final String PASSWORD_DB = 		"";
	public static final String USERS_TABLE_NAME = 	"utenti";
	public static final String MAIL_TABLE_NAME = 	"mailDrop";
}