var selectedElement;

function selectMe(whichElement) {
	
}

function getFileSize(size) {
	var units = new Array(' B', ' KB', ' MB', ' GB', ' TB');
	var i=0;
	for (; size > 1024; i++) { 
		size /= 1024; 
	}
	return ((Math.round(size)).toString())+units[i];
}

function findBounds(number) {
	var left = null;
	var right = null;
	var temp = number;
	if (number % 10 == 0 || number % 5 == 0) {
		if (number == 100) {
			left = 95;
			right = 100;
		}
		else {
			left = number;
			right = number + 5;
		}
	}
	else 
	{
		for (var i=0;right==null;i++) {
			if (temp % 10 == 0 || temp % 5 == 0)
				right = temp;
			temp++;
		}
		temp = number;
		for (var i=0;left==null;i++) {
			if (temp % 10 == 0 || temp % 5 == 0)
				left = temp;
			temp--;
		}
	}
	return left + "-" + right;
}

function doMemoryState(freeMemory,totalMemory) {
	var percentFree = freeMemory / totalMemory;
	var free = parseInt(percentFree * 100);
	document.writeln("<br><img src='images/"+findBounds(100-free)+".gif'>");
	document.write("<div class='header'>Memoria Libera: "+getFileSize(freeMemory)+"<br>Memoria Occupata: "+getFileSize(totalMemory-freeMemory)+"</div>");
}

function showOtherFolders(folders) {
	var folderLayerText = "";
	for (i=0;i<folders.length;i++) {
		folderLayerText+=folders[i]+"<br>";
	}
	window.parent.altreCartelle.innerHTML = folderLayerText;
	window.parent.altreCartelle.style.visibility = "visible";
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY)
	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY)
	{
		posx = e.clientX + window.parent.document.body.scrollLeft;
		posy = e.clientY + window.parent.document.body.scrollTop;
	}
	window.parent.altreCartelle.left = posx;
	window.parent.altreCartelle.top = posy;
	
}