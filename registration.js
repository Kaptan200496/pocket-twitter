var registerButton = document.getElementById("buttonForRegister");

registerButton.onclick = function() {
	var loginValue = document.getElementById("loginRegister").value;
	var passwordValue = document.getElementById("passwordRegister").value;
	var emailValue = document.getElementById("emailRegister").value;

	var JSONobject = {
		function: User/registration,
		parameters: {
			login: loginValue,
			password: passwordValue,
			email: emailValue
		}
	}

	var JSONvalue = JSON.stringify(JSONobject);
	
	var request = new XMLHttpRequest();
	request.open("POST", "endpoint.php", true);
	
	request.send(JSONvalue);	
}

