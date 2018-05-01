function checkLogin(){
	var theForm = document.login;
	var returnValue = theForm.userName.value!="" && theForm.userPass.value!="";
	if (!returnValue)
		alert("Username o password mancanti!");
	return returnValue && checkInvalidChars(theForm);
}

