;(function(){
"use strict";

var croakAdmin = {};
window.croakAdmin = croakAdmin;

croakAdmin.loadUser = function(element){
  manageActive(element);
  $("#main").load("admin-user.php");
};

croakAdmin.loadGallery = function(title, bck, nbCols, element){
  manageActive(element);
  $("#main").load("admin-gallery.php", function() {
    managePalette();
   setValues(title, bck, nbCols, element);
   preview(title, bck, nbCols);
  });
};

croakAdmin.updateUser = function(){
  
  $("#submitButton").css("animation-name", "animSubmit");
  var name = $("#inputName").val();
	var email = $("#inputEmail").val();
	var alias = $("#inputAlias").val();
	
	$.post( "php/updateUser.php", function( data ) {
    $( "#main" ).html( data );
  });
	
// , { name: name, email: email, alias: alias }
//  $.post( "php/updateUser.php");
  // , function() {
   
  // })
  //   .done(function() {
  //     $('#myModal').modal('show');
  //   })
  //   .fail(function() {
  //     alert( "error" );
  //   })
  //   .always(function() {
  //     $('#myModal').modal('show');
  //   });

};

croakAdmin.update = function(element){
  var col = $(".btn-group").children(".active").data("col");
  if(element!==undefined){
    col = $(element).data("col");
  }
  var title = $("#inputTitle").val();
  var color = ($("#picker").spectrum("get")).toHexString();
  preview(title, color, col);
};

function manageActive(element){
  var lis = $(element).parent().parent().children();
  lis.removeClass("active");
  $(element).parent().addClass("active");
}

function managePalette(){
  $("#picker").spectrum({
    color: "#fff",
    showButtons: false,
    showInitial: true,
    containerClassName: "palette"
  });
  $("#picker").on('move.spectrum', function(e, color) { 
    var hexColor = color.toHexString();
  });
}

function setValues(title, bck, nbCols){
  var colID = "#"+nbCols+"cols";
  $(colID).parent().addClass('active');
  $("#inputTitle").val(title);
  $("#picker").spectrum({
    color: bck,
    showButtons: false,
    move: function(tinycolor) {update()},
  });
}

function endAnimate(){
  $("#submitButton").css("animation-name", "");
}

})();