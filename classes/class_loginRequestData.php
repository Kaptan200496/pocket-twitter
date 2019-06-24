<?php

// Класс для данных из полей ввода при входе
class LoginRequestData {
	public $login;
	public $password;
	public $remember_me;

	// Декодируем данные полученные из ввода пользователей
	function __construct($requestText) {
		$requestVariable = json_decode($requestText);
		
		if (
			(isset($requestVariable->login))
			&&
			(is_string($requestVariable->login)) 
		) {
			$this->login = $requestVariable->login;
		}
		else {
			print("Неверно введённый логин \n");
		}

		if (
			isset($requestVariable->password)
			&&
			(is_string($requestVariable->password)) 
		) {
			$this->password = $requestVariable->password;
		}
		else {
			print("Неверно введённый пароль \n");
		}

		$this->remember_me = $requestVariable->remember_me;
	}
}

?>