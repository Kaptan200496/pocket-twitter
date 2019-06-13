<?php  
class User {
	public $id;
	public $login;
	public $password;
	public $registrationDate;

	function __construct($jsonstring) {
		$decodeObject = json_decode($jsonstring);
		if(
			(is_int($decodeObject->id) == true)
			&&
			($decodeObject->id != 0)
		){
			$this->id = $decodeObject->id;
		}
		else {
			print("Айди не обнаружено \n");
		}

		if(
			(is_string($decodeObject->login))
			&&
			(isset($decodeObject->login))
		) {
			$this->password = $decodeObject->login;
		}
		else {
			print("Неверно введён логин \n");
		}

		if(
			(is_string($decodeObject->password))
			&&
			(isset($decodeObject->password))
		) {
			$this->password = $decodeObject->password;
		}

		if(
			($decodeObject->registrationDate < PHP_INT_MAX)
			&&
			(is_int($decodeObject->registrationDate))
		) {
			$timeString = date(DATE_ATOM, $decodeObject->registrationDate);
			$this->registrationDate = new DateTime($timeString);
		}
		else {
			print("Неверный формат даты ");
		}
	}

	public static function logIn() {
		$requestJson = file_get_contents("php://input");
		$logInObject = new LoginRequestData($requestJson);
		$userObject = User::getUserFromDB($logInObject->login);
		$signInTime = time();
		$successful = $logInObject->password == $userObject->password;
		$successfulInt = intval($successful);
		$tableName = "login_attemps";
		$attemptInsertion = "INSERT INTO {$tableName} (
			login,
			loginDate,
			active
		)
		VALUES (
			'{$logInObject->login}',
			{$signInTime},
			{$successfulInt}
		)";
		
		DB::$connection->query($attemptInsertion);

		// Если есть код ошибки, то обрабатываем ошибку
		if(DB::$connection->errno) {
			// Формируем ассоциативный массив с полным описанием ошибки
			// Какой запрос подали и что при этом произошло
			$errorInfo = array(
				"expression" => $attemptInsertion,
				"error" => DB::$connection->error
			);
			// Преобразуем ассоциативный массив в JSON и выводим
			print( json_encode($errorInfo, JSON_PRETTY_PRINT) );
		}

		return $successful;
	}


	private static function getUserFromDB($login) {
		$tableName = "users";
		$selectUser = "
			SELECT * FROM {$tableName} WHERE login = '{$login}'
		";
		
		$dbResponse = DB::$connection->query($selectUser);
		if($dbResponse->num_rows == 1) {
			$userRow = $dbResponse->fetch_assoc();
			$userRow["id"] = intval($userRow["id"]);
			$userRow["registrationDate"] = time();
			$userObject = new User(json_encode($userRow));
		}
		else {
			return NULL;
		}
		return $userObject;
	}

	public static function registration () {
		$requestJson = file_get_contents("php://input");
		$registrationObject = new regRequestData($requestJson);
		$userObject = self::insertUserIntoDB($registrationObject);
	}

	private static function insertUserIntoDB($userData) {
		$tableName = "users";
		$timeStamp = time();
		$loginName = $userData->login;
		$checkLogin = "
			SELECT login FROM {$tableName} WHERE login = '{$loginName}'
		";
		$insertObject = "
					INSERT INTO {$tableName} (
					login,
					password,
					email,
					date
					)
					VALUES (
						'{$loginName}',
						'{$userData->password}',
						'{$userData->email}',
						{$timeStamp}
					)
				";
		$dbResponse = DB::$connection->query($checkLogin);
			if($dbResponse->num_rows == 0) {
				DB::$connection->query($insertObject);
			}
			else {
				print("This login is busy");
			}
	}
}


?>