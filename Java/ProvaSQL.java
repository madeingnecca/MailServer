import java.sql.*;

public class ProvaSQL
{
	public static void main(String[] args) throws Exception
	{

		Class.forName("com.mysql.jdbc.Driver").newInstance();
		String url="jdbc:mysql://localhost:3306/dax";
		String user="";
		String password="";
		Connection conn= DriverManager.getConnection(url,user,password);
		Statement stmt = conn.createStatement();
		ResultSet recordSet = stmt.executeQuery("SELECT * FROM tblprima");
		if (recordSet.next())
		{
			System.out.println("Esistono record!");
			System.out.println(recordSet.getInt(1));
		}
		else
			System.out.println("Ta pare!");
		stmt.close();
		conn.close();
	}
}