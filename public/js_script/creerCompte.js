let passwordInput = null;
let passwordRepetitionInput = null;

document.addEventListener("DOMContentLoaded", function(event) { 
	passwordInput = document.getElementById("password");
	passwordRepetitionInput = document.getElementById("passwordRepetition");
});

function togglePasswordVisibilty() {
	if (passwordInput.type === "password") {
		passwordInput.type = "text";
		passwordRepetitionInput.type = "text";
	} else {
		passwordInput.type = "password";
		passwordRepetitionInput.type = "password";
	}
}