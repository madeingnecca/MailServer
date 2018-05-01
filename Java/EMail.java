import java.sql.ResultSet;

public class EMail
{
	private int messageID;
	private String username;
	private String message;
	private boolean toDelete;

	public EMail(ResultSet dbMail)
	{
		try
		{
			messageID = dbMail.getInt("mailID");
			message = dbMail.getString("message");
			toDelete = false;
		}
		catch (Exception sqlOrDateExc)
		{
			System.out.println(sqlOrDateExc.getMessage());
		}
	}

	public void setToDelete(boolean choice)
	{
		toDelete = choice;
	}

	public boolean isToDelete()
	{
		return toDelete;
	}

	public String getMessage()
	{
		return message;
	}

	public int getMessageID()
	{
		return messageID;
	}
}