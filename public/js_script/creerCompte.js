let passwordInput = null;
let passwordRepetitionInput = null;

document.addEventListener("DOMContentLoaded", function(event) { 
	passwordInput = document.getElementById("password");
	passwordRepetitionInput = document.getElementById("passwordRepetition");

	document.getElementById("captcha").addEventListener("submit",function(evt) {
		var response = grecaptcha.getResponse();

		if(response.length == 0)
			alert("vide");
		else
			alert("pas vide");
	});
});

function togglePasswordVisibility1() {
	if (passwordInput.type === "password") {
		passwordInput.type = "text";
	} else {
		passwordInput.type = "password";
	}
}


function togglePasswordVisibility2() {
	if (passwordRepetitionInput.type === "password") {
		passwordRepetitionInput.type = "text";
	} else {
		passwordRepetitionInput.type = "password";
	}
}