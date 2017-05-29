$(document).ready(function(){
  $("#inputName").blur(function(){verifName();});
	$("#inputAlias").blur(function(){verifAlias();});
	$("#inputEmail").blur(function(){verifMail();});
	$("#inputTitle").blur(function(){verifTitle();});
	$("#inputPassword").blur(function(){verifPassword();});
	$("#repeatPassword").blur(function(){verifRepeatPassword();});
});

function verifSignUp(f){
	var nameOk = verifName();
	var mailOk = verifMail();
	var passwordOk = verifPassword();
	var repeatOk = verifRepeatPassword();   

   return (nameOk && mailOk && passwordOk && repeatOK);
}

function verifName(){
	var regex = /^[a-zA-Z0-9-_]{3,20}$/;
	pop("#inputName", "name should be 3 to 20-character long without special characters (including accents) except - and _", regex.test($("#inputName").val()));
}

function verifAlias(){
	var regex = /^[\wàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{1}[\w àáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{0,29}$/;
	pop("#inputAlias", "alias should be 1 to 30-character long without special characters and not begining with white space", regex.test($("#inputAlias").val()));
}

function verifTitle(){
	var regex = /^[\wàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{1}[\w àáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{0,29}$/;
	pop("#inputTitle", "title should be 1 to 30-character long without special characters and not begining with white space", regex.test($("#inputTitle").val()));
}

function verifMail(){
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   pop("#inputEmail", "enter a valid e-mail address", regex.test($("#inputEmail").val()));
}

function verifPassword(){
	pop("#inputPassword", "password should be at least 6-character long. It is better to avoid dictionary words and to mix letters, numbers and special characters", $("#inputPassword").val().length>5);
}

function verifRepeatPassword(){
	pop("#repeatPassword", "password repeated with differences", $("#inputPassword").val()===$("#repeatPassword").val());
}

function pop(field, message, test){
	if(!test){
		$(field).popover({content: message, placement: "auto bottom",  animation: true}); 
		$(field).popover('show');
		$("[data-toggle='popover']").popover('toggle');
		$('.popover').delay(4000).fadeOut('slow');
	}
	else {
		$(field).popover('destroy');
	}
}








