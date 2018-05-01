function checkNewUserForm() {
	var theForm = document.nuovoAccount;
	var esito = "";
	if (theForm.userName.value=="")
		esito+="username \n";
	if (theForm.userPass.value=="")
		esito+="password \n";
	if (esito == "") {
		if (theForm.userPass.value.length<6) {
			alert("Attenzione: la password deve essere lunga almeno 6 caratteri!");
			return false;
		}
		else if (theForm.userPass.value.length>20) {
			alert("Attenzione: la password deve essere lunga al massimo 20 caratteri!");
			return false;
		}
		if (theForm.userPass.value != theForm.safePass.value) {
			alert("Le due password inserite non corrispondono!");
			return false;
		}
		if (theForm.userName.value.length>20) {
			alert("Attenzione: l'username deve essere lungo al massimo 20 caratteri!");
			return false;
		}
		return checkInvalidChars(theForm);
	}
	else {
		alert("I seguenti campi vanno riempiti: \n"+esito);
		return false;
	}
	
}

/*
	prepara la pagina di login dopo l'avvenuta iscrizione ( index.php )
*/

function prepareLogin(theUser,thePass) {
	window.opener.document.login.userName.value = theUser;
	window.opener.document.login.userPass.value = thePass;
	window.close();
}