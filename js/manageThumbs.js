
function request(callback) {
  var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && (xhr.status === 200 || xhr.status === 0)) {
			callback(xhr.responseText);
		}
	};
	xhr.open("POST", "php/handlingThumbs.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send();
}

function readJson(arg){

	var json = JSON.parse(arg);
	var title = json.title;
	$("#title").html(title);
	
	var colArrays = json.col;

	display(title, colArrays);
}

function display(title, colArrays, screen){
	var colLength= Math.floor(12/colArrays.length);
	var colType = "sm";
	if(screen){
		colType = screen;
	}
	var addHtml = "";
	
	if(colArrays.length===5){
		addHtml+="<div class=\"col-"+colType+"-1\"></div>";
	}
	
	for(let i=0; i<colArrays.length; i++){
		var col = colArrays[i];
		addHtml+='<div class="col-'+colType+'-'+colLength+'">';
		for (let j=0; j< col.length; j++){
			addHtml+='<div class="thumbnail">'+col[j]+'</div>';
		}
		addHtml+='</div>';
	}
	
	if(colArrays.length===5){
		addHtml+="<div class=\"col-"+colType+"-1\"></div>";
	}

	$("#grid").html(addHtml);
}

function preview(title, bck, nbCols){

	$("#preview").css('background-color', bck);
	$("#title").html(title);
  
  var arrayCols = [];
  var imgPerCols = Math.floor(12/nbCols);
  
  var index = 1;
  for( var i=0; i<nbCols; i++){
  	arrayCols[i] = [];
    for (var j=0; j<imgPerCols; j++){
      arrayCols[i].push('<img src="img/anim-'+index+'.jpg" style="width:100%">');
      index++;
    }
  }
  
  for(i=0; i<12%nbCols; i++){
    arrayCols[i].push('<img src="img/anim-'+index+'.jpg" style="width:100%">');
    index++;
  }
  
  display(title, arrayCols, "xs");

}


/*// Open and close sidenav
function w3_open() {
    document.getElementById("mySidenav").style.width = "100%";
    document.getElementById("mySidenav").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidenav").style.display = "none";
}
*/


