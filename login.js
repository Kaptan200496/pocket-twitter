// Получаем данные о кнопке по ID
var loginButton = document.getElementById("logInButton");

// Функция определяющая нажатие кнопки полученой выше
loginButton.onclick = function() {
	// Получаем значения которые вводятся в поле login
	var loginValue = document.getElementById("login").value;
	// Получаем значения которые вводятся в поле password
	var passwordValue = document.getElementById("password").value;
	// Проверяем нажата ли галочка в checkBox
	var checkBoxValue = document.getElementById("checkbox").checked;
	// Создаем объект JSON который имеет поля со значениями введенных данных
	var JSONobject = {
		function: session/login,
		parameters: {
				login: loginValue,
				password: passwordValue,
				remember_me: checkBoxValue
			}
		}
	// Создаем переменную которая будет содержать этот объект в формате JSON
 	var requestJSON = JSON.stringify(JSONobject);
 	// Вызываем функцию которой передаем значение выше созданого объекта JSON
 	serverRequest(requestJSON);
}

var serverRequest = function(requestJSON) {

	var request = new XMLHttpRequest();
	// открываем запрос и передаем ему параметры
	request.open("POST", "endpoint.php", true);
	// проверяем готовность обработки запроса
	request.onreadystatechange = function() {
		if(request.readyState == XMLHttpRequest.DONE) {
			// Переменная с ответом от сервера
			responseText = request.responseText;
			console.log(request.responseText);
			// Превращение из JSON в js строку
			responseVariable = JSON.parse(responseText);
			// Переменная с строкой js
			requestResult = responseVariable;
			// Проверка ответа от сервера на схожесть с вводимыми данными
			var checkFunction = function(requestResult) {
				if(responseVariable == true) {
					alert("Пользователь успешно вошел");
				}
				else {
					alert("Неправильный логин или пароль");
				}
			}
			// Вызов функции с проверкой 
			checkFunction(requestResult);
		}
	}
	request.send(requestJSON);
}
