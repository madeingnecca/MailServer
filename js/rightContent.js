function viewPrintableVersion(mailID) {
	var windowOptions = 'history=no,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=no,resizable=no,width=550,height=650';
	window.open("printableMail.php?mailID="+mailID,"",windowOptions);
}

function indexInArray(myArray,element) {
	for (var i=0;i<myArray.length;i++) {
		if (myArray[i] == element)
			return i;
	}
	return "notFound";
}

function updateInlineObjects(trueNames,gainedNames,pathOnWebSrv,realPath) {
	alert("Loading..");
	var allImages = document.frames['mailEditor'].document.images;
	var tNames = trueNames.split(":");
	var fNames = gainedNames.split(":");
	for (var i = 0;i<allImages.length;i++){
		var tempImg = allImages[i];
		if (tempImg.src.indexOf("http://"+pathOnWebSrv) != -1) {
			var theSrc = tempImg.src.substring(7,tempImg.src.length);
			var splitted = theSrc.split("/");
			var newFile = splitted[splitted.length-1];
			var theIndex = indexInArray(tNames,newFile);
			if (theIndex != "notFound")
				newFile = fNames[theIndex];
			tempImg.src = "http://"+realPath+newFile;
		}
	}
}

function moveInDrafts() {
	document.editor.mode.value = "draft";
}

function moveMsgs(theFolder) {
	document.msgs.action = "moveMail.php?mode=many&folder="+theFolder;
	document.msgs.submit();
}

function deleteMsgs(){
	document.msgs.action = "erase.php";
	document.msgs.submit();	
}

function submitForm(pathOnWebSrv) {
	if (document.editor.to.value.length == 0) {
		alert("Destinatario mancante!");
		return false;
	}
	var allImages = document.frames['mailEditor'].document.images;
	var sources = "";
	for (var i = 0;i<allImages.length;i++){
		var tempImg = allImages[i];
		if (tempImg.src.indexOf("http://"+pathOnWebSrv) != -1) {
			var theSrc = tempImg.src.substring(7,tempImg.src.length);
			var splitted = theSrc.split("/");
			var newFile = splitted[splitted.length-1];
			tempImg.src = newFile;
			sources+=newFile+(i==allImages.length-1? "" : ":" );
		}
	}
	var allLinks = document.frames['mailEditor'].document.links;
	for (var i=0;i<allLinks.length;i++){
		var tempLink = allLinks[i];
		tempLink.target = "_blank";
	}
	updateRTE('mailEditor');
	document.editor.trueInlineImages.value = sources;
	return true;
}

function doHighlight(theId) {
	if (theId.className == "normal")
		theId.className = "highLight";
	else	
		theId.className = "normal";
}

var currentCheck = false;

function selectUnSelectMsgs(checkCount,checkedOrNot,doSwitch) {
	if (doSwitch)
		currentCheck = !currentCheck;
	else {
		currentCheck = checkedOrNot;
		commander.checked = currentCheck;
	}
	for (var i=0;i<checkCount;i++) {
		var tmpCheck = eval("document.all.check"+i);
		var tmpDiv = eval("document.all.tr"+i);

		if (doSwitch) {
			if (tmpCheck.checked != currentCheck)
				doHighlight(tmpDiv);
			tmpCheck.checked = currentCheck;
		}
		else {
			if (tmpCheck.checked != checkedOrNot) 
				doHighlight(tmpDiv);
			tmpCheck.checked = checkedOrNot;
		}
	}
}

function showLayer(theDiv) {
	var theElement = eval("document.all."+theDiv);
	if (theElement.style.visibility == "visible")
		theElement.style.visibility = "hidden";
	else
		theElement.style.visibility = "visible";
}

	