/*  -----------------------------------------
	C.ZUCCANTE AS 2004-2005 5 ISE
	Seno Damiano, Doria Luca, Marchese Andrea
	TimeoutChecker.java
	La classe permette di creare un timer
	per la gestione del timeout dei srv
	POP3 & SMTP
    ----------------------------------------
*/

public class TimeoutChecker extends Thread
{
	private int limit;			/* tempo limite */
	private int step;			/* millisecondi da aspettare */
	private ClientHandler cH;	/*  il server POP3 o SMTP che ha instanziato l'oggetto*/
	private boolean alive;		/* indica se il timeout ha finito di operare */

	/*
		TimeoutChecker()
		----------------------
		NB: per impostare che il timeout aspetti 30 s
			new TimeoutChecker(ClientHandler,30,1000);
			in questo modo vengono aspettati 30000 ms

	*/
	public TimeoutChecker(ClientHandler cH,int limit,int step) {
		this.limit = limit;
		this.step = step;
		this.cH = cH;
		alive = true; /* il timeout appena attivato è vivo*/
	}

	/*
		void die()
		-----------------------------
		Uccide il timeout. Il timeout
		può essere ucciso quando:
			1 - il tempo è scaduto
			2 - il Clienthandler padre è in fase di aggiornamento

	*/
	public void die() {
		alive = false;  /* settando alive = false il metodo run viene interrotto */
	}


	/*
		void run()
		-------------------------
		Funzione del thread
			1 - Controlla se il tempo è finito
				Se si uccide il thread e notifica al ClientHandler che il tempo è scaduto
			2 - In caso contrario aspetta un tempo step e incrementa il tempo aspettato


	*/
	public void run()
	{
		int passed = 0;
		while (alive)
		{
			if (passed == limit) {
				die();
				cH.timeExceeded();
			}
			else
			{
				try
				{
					Thread.sleep(step);
				}
				catch (Exception e){
					die();
				}
				passed++;
			}
		}
	}
}