function playTTS(read,lang) {
   var tts="tts.class.php"; // path to tts.class.php file
   var sec="http://portalas.org/scripts/tts/audio/recs/point1sec.mp3";
   var a = new XMLHttpRequest();
   var b = document.getElementById("tts");
   var url = tts;
   var read = read.replace(/&/g,"and");
   b.src=sec;
   b.play();
   a.open("POST", url, true);
   a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   a.onreadystatechange = function() {
	if(a.readyState == 4 && a.status == 200) {
          b.src="";
	  b.src=a.responseText;
          b.playbackRate = 1.0;
	}
   }
a.send("read="+ read +"&lang="+lang);
}
function stopTTS() {
     var a = document.getElementById("tts");
     a.pause();
     a.currentTime = 0;
}
document.write('<audio id="tts" autoplay><source src="http://portalas.org/scripts/tts/audio/recs/point1sec.mp3" type="audio/mp3" /></audio>');

function pauseTTS() {
     var a = document.getElementById("tts");
     var b = document.getElementById("pause");
     a.pause();
     b.setAttribute("onclick","resumeTTS()");
     b.innerHTML = "Resume reading!";
}
function resumeTTS() {
     var a = document.getElementById("tts");
     var b = document.getElementById("pause");
     a.play();
     b.setAttribute("onclick","pauseTTS()");
     b.innerHTML = "Pause reading!";
}
