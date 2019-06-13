var registerButton = document.getElementById("buttonForRegister");

registerButton.onclick = function() {
	var loginValue = document.getElementById("loginRegister").value;
	var passwordValue = document.getElementById("passwordRegister").value;
	var emailValue = document.getElementById("emailRegister").value;

	var JSONobject = {
			login: loginValue,
			password: passwordValue,
			email: emailValue
		}

	var JSONvalue = JSON.stringify(JSONobject);
	
	var request = new XMLHttpRequest();
	request.open("POST", "registration.php", true);
	
	request.send(JSONvalue);	
}

