let passwordInput = null;

document.addEventListener("DOMContentLoaded", function(event) { 
	passwordInput = document.getElementById("password");
});

function togglePasswordVisibilty() {
	if (passwordInput.type === "password") {
		passwordInput.type = "text";
	} else {
		passwordInput.type = "password";
	}
}