var server = "http://127.0.0.2";

var type=
	[
		"Temperature", 
		"Humidity", 
		"Air quality", 
		"Water leakage", 
		"Motion", 
		"Luminosity"
	];

var	pattern1=/\u0026/g;
var	pattern2=/\u003d/g;
	
var	sensnum=new Array();
var	sensvalue=new Array();
var	senstype=new Array();

function createCORSRequest(method,url) {
	var xhr= new XMLHttpRequest();
	if("withCredentials" in xhr) { 	
		xhr.open(method,url,true); 
	}
	else if(typeof XDomainRequest!="undefined") {
		xhr=new XDomainRequest();
		xhr.open(method,url);
	}
	else { 
		xhr=null; 
	}
	return xhr;
}

	
function AjaxDBDataReq() {
	var http=createCORSRequest("GET",server+"/index.php?request=sensors");
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
	http.onload = function() {
		if (http.readyState==4)	{
			blocktemp.innerHTML="";
			blockhumid.innerHTML="";
			blockairqual.innerHTML="";
			blockwatleak.innerHTML="";
			blockmotion.innerHTML="";
			blocklumin.innerHTML="";
			x=http.responseText;
			c=x.split(pattern1);
			for(i=0;i<c.length-1;i++) {
				temp=c[i].split(pattern2);
				sensnum[i]=Number(temp[0].substring(1,3));
				senstype[i]=Number(temp[0].substring(0,1));
				sensvalue[i]=temp[1];
				switch(senstype[i]) {
					case 0:
						blocktemp.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					case 1:
						blockhumid.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					case 2:
						blockairqual.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					case 3:
						blockwatleak.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					case 4:
						blockmotion.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					case 5:
						blocklumin.innerHTML+="Sensor "+sensnum[i]+" value is "+sensvalue[i]+"<br>";
						break;
					default:
						break;
				}
			}
		}
	}
	http.send();
}
setInterval("AjaxDBDataReq()",10000);
		
	
function AjaxSendard() {
	if (led1.checked)
		state1=1;
	else 
		state1=0;
	if (led2.checked)
		state2=1;
	else 
		state2=0;
	var http=createCORSRequest("GET","http://10.110.1.20?LED1="+state1+"&LED2="+state2);
	http.onload = function() {
		if (http.readyState==4 && http.status == 200) {
		}
	}
http.send();
}



function AjaxWeatherReq() {
	var http=createCORSRequest("GET",server+"/index.php?request=weather");
	http.onload = function() {
		if (http.readyState==4 && http.status == 200) {
			weather.innerHTML=http.response;
		}
	}
http.send();
setTimeout('AjaxWeatherReq()',10000);
}

window.onload=AjaxWeatherReq();



