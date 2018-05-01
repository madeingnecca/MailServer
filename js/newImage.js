
function imgInsertion() {
	if (url.style.visibility == "visible") {
		var theFile = imageForm.url.value;
		if (theFile.indexOf("http://") == 0 && checkExtension(theFile))
			insertImage("mailEditor",theFile,true);
		else
			alert("Link non valido!");
	}
	else {
		if (checkFile())
			imageForm.submit();
	}
}

function insertImage(rte,theImage,fromUrl){
	var theParent = fromUrl ? window.opener : window.parent.window.opener;
	theParent.rteCommand(rte, 'InsertImage', theImage);
	if (!fromUrl) {
		var theSrc = theImage.substring(7,theImage.length);  // 7 = lunghezza in caratteri di HTTP:// 
		var splitted = theSrc.split("/");
		var newFile = splitted[splitted.length-1];
		var allInline = theParent.document.editor.inlineImages.value;
		theParent.document.editor.inlineImages.value += (allInline.length == 0 ? newFile : ":"+newFile );
	}
	window.close();
}

var modes = new Array("hidden","hidden");

function showLayer(layerIndex) {
	modes[layerIndex] = "visible";
	modes[1-layerIndex] = "hidden";
}

function checkExtension(theFile) {
	var splittedFile = theFile.split(".");
	var extension = splittedFile[splittedFile.length-1].toLowerCase();
	return (extension == "jpg" || extension == "jpeg" || extension == "bmp" || extension == "gif" || extension == "png");
}

function checkFile() {
	var theFile = imageForm.theImg.value;
	if (!checkExtension(theFile)) {
		alert("Formato file non valido!");
		return false;
	}
	return true;
}