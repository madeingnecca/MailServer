var secsToWait = 5;
var seconds = 0
var timerID;

function autoRedirect(thePage){
	if (seconds < secsToWait){
		timerID = setTimeout("autoRedirect('"+thePage+"')",1000);
		seconds++;
	}
	else {
		clearTimeout(timerID);
		document.location = thePage;
	}
}