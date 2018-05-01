function myIndexOf(theArray,theElement) {
	for (var i=0;i<theArray.length;i++) {
		if (theArray[i] == theElement)
			return true;
	}
	return false;
}

function doSaveContact() {
	var index = parseInt(document.book.contactsCount.value);
	var email = document.book.email.value;
	var name = document.book.name.value;
	var existingContacts = (document.book.existingContacts.value).split(":");
	if (name=="")
		alert("Campo nome mancante!");
	else
	{
		if (myIndexOf(existingContacts,name))
			alert("Contatto gia' presente!");
		else if (email=="")
			alert("Campo email mancante!");
		else if (email.indexOf("@")==-1)
			alert("Formato campo email errato!");
		else {
			contacts.innerHTML += "<input type='checkbox' name='contact"+index+"' value='"+email+"'> "+name+" "+email+"<br>";
			count.innerText = index+1;
			var ex = document.book.existingContacts.value;
			document.book.existingContacts.value+=(ex == "" ? name : ":"+name);
			document.book.contactsCount.value = index+1;
		}
	}
}