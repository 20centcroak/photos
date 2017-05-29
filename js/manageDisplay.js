var originalWidth;
var originalHeight;
var imgNames;
var photo;
var left;
var right;

function request(callback, index) {
    var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			callback(xhr.responseText);
		}
	};
	xhr.open("POST", "php/handlingDisplay.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("ind="+index);
}

function displayFirst(arg){
	imgNames = JSON.parse(arg);
	hide();
	init();
	loadNeighborImages();
}

function displayNext(arg){
	imgNames = JSON.parse(arg);
	hide();
	loadNeighborImages();
}

function hide(){
	if(!("left" in imgNames)){
		document.getElementById("left").style.display = "none";
	}
	else {
		document.getElementById("left").style.display = "initial";
	}
	if(!("right" in imgNames)){
		document.getElementById("right").style.display = "none";
	}
	else {
		document.getElementById("right").style.display = "initial";
	}
}

function init(){	
	photo = new Image(); 
	photo.src = imgNames.current;
	originalWidth = imgNames.w;
	originalHeight = imgNames.h;
	resize();
	document.getElementById("image").src = photo.src;
}

function loadNeighborImages(){
	
	if(left==null && ("left" in imgNames)){
		left = new Image(); 
		left.src = imgNames.left;
	}
	 
	if( right==null && ("right" in imgNames)){ 
		right = new Image(); 
		right.src = imgNames.right;
	}
	
}

function clickLeft(){	
	right = photo;
	photo = left;
	left = null;
	if (photo==null) {
		return
	}
	originalWidth = imgNames.w_left;
	originalHeight = imgNames.h_left;
	resize();
	document.getElementById("image").src = photo.src;
	request(displayNext, -1);
}

function clickRight(){
	left = photo;
	photo = right;
	right = null;
	if (photo==null) {
		return
	}
	originalWidth = imgNames.w_right;
	originalHeight = imgNames.h_right;
	resize();
	document.getElementById("image").src = photo.src;
	request(displayNext, 1);
}

function resize(){
	
	var viewportHeight = window.innerHeight;
	var viewportWidth = window.innerWidth;
	var ratioImg = originalHeight/originalWidth;
	var ratioView = viewportHeight/viewportWidth;
	
	if (ratioView>ratioImg){		
		var w = viewportWidth;
		if (w>originalWidth){
			w=originalWidth;
		}
		var h = w*ratioImg;
	}
	else{
		var h = viewportHeight;
		if (h>originalHeight){
			h=originalHeight;
		}
		var w = h/ratioImg;
	}
	w = w+"px";
	h = h+"px";

	document.getElementById("image").style.width=w;
	document.getElementById("image").style.height=h;
}




