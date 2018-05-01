function doSeparator(separatorWidth){
	document.writeln("<div class='separator' style='width:"+separatorWidth+"px'></div>");
}

var welcomeDate = "Oggi e' il";

function checkInvalidChars(theForm) 
{
	var notAllowedChars = new Array(":","@","<",">","\"","*","/","\\");
	for (var i=0;i<notAllowedChars.length;i++) {
		if (theForm.userName.value.indexOf(notAllowedChars[i])!=-1) {
			alert("L'username contiene caratteri non validi!");
			return false;
		}
	}
	return true;
}

function doClock() {
	var oDate = new Date();
	var clockToStr = welcomeDate+" "+oDate.getDate()+"/"+(oDate.getMonth()+1)+"/"+oDate.getYear();	
	var ore = oDate.getHours() < 10 ? ore = "0" + String(oDate.getHours()) : String(oDate.getHours());
	var minuti = oDate.getMinutes() < 10 ? minuti = "0" + String(oDate.getMinutes()) : String(oDate.getMinutes());
	var secondi = oDate.getSeconds() < 10 ? secondi = "0" + String(oDate.getSeconds()) : String(oDate.getSeconds());
	clockToStr+= " - "+ore+":"+minuti+":"+secondi;
	clock.innerText = clockToStr;
	setTimeout("doClock()",1000);
}

function myPopup(titolo,pagina) {
	var windowOptions = 'history=no,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=no,resizable=no,width=400,height=450';
	window.open(pagina,"",windowOptions);
}