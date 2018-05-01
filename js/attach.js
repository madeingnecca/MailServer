function updateAttachments(fileName,fileIcon,fileDim,fileType) {
	var theParent = eval("window.parent.window.opener.attachments");
	var numAttaches = parseInt(window.parent.window.opener.document.editor.attachments.value);
	theParent.innerHTML+="<input type='checkbox' checked value='"+fileName+"' name='attach"+numAttaches+"'><img src='images/"+fileIcon+"'> "+fileName+" "+fileDim+"<br>";
	numAttaches++;
	window.parent.window.opener.document.editor.attachments.value = numAttaches;
	var types = eval("window.parent.window.opener.document.editor.types");
	var names = eval("window.parent.window.opener.document.editor.attachNames");
	types.value= types.value.length==0 ? fileType : types.value+":"+fileType;
	names.value=names.value.length==0 ? fileName : names.value+":"+fileName;
	window.close();
}


