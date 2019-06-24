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
		$receivedPasswordHash = sha1($logInObject->password . $userObject->salt);
		$successful = $receivedPasswordHash == $userObject->hash_with_salt;
		$successfulInt = intval($successful);
		$attemptInsertion = "INSERT INTO login_attempts (
			login,
			successful
		)
		VALUES (
			'{$logInObject->login}',
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

		if($successful) {
			self::CreateCookie($userObject);
		}

		return $successful;
	}	

	public static function CreateCookie($userObject) {
		$sessionObject = new Session();
		$sessionObject->user = $userObject;
		$sessionObject->token = self::generate_uuid();
		$sqlExpression = "
			INSERT INTO sessions (
				user,
				token
			)
			VALUES (
				{$sessionObject->user->id},
				'{$sessionObject->token}'
			)
		";
		$secondsInWeek = 3600 * 24 * 7;
		$cookieExpirationTime = time() + $secondsInWeek;
		setcookie("token", $sessionObject->token, $cookieExpirationTime);

	}


	private static function getUserFromDB($login) {
		$selectUser = "
			SELECT * FROM users WHERE login = '{$login}'
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

	public static function generate_uuid() {
	$hexadecimalString = "";

	for($i = 0; $i < 32; $i++) {
		$hexadecimalString .= dechex(random_int(0, 15));
	}

	$splitHexadecimalString = array(
		substr($hexadecimalString, 0, 8),
		substr($hexadecimalString, 8, 4),
		substr($hexadecimalString, 12, 4),
		substr($hexadecimalString, 16, 4),
		substr($hexadecimalString, 20, 12)
	);

	$uuid = implode("-", $splitHexadecimalString);
	return $uuid;
	}

	private static function insertUserIntoDB($userData) {
		$tableName = "users";
		$salt =  self::generate_uuid();
		$password = $userData->password;
		$hash_with_salt = sha1($userData->password . $salt);
		$checkLogin = "
			SELECT login FROM users WHERE login = '{$userData->login}'
		";
		$insertObject = "
			INSERT INTO {$tableName} (
				login,
				password_hash,
				email,
				salt
			)
			VALUES (
				'{$userData->login}',
				'{$hash_with_salt}',
				'{$userData->email}',
				'{$salt}'
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
