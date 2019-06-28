<?php

class Session {
	public $id;
	public $user;
	public $token;
	public $created;
	public $modified;
	public $active;

	function __construct($sessionObject) {
		$decodeObject = json_decode($sessionObject);
		$this->id = $decodeObject->id;
		$this->user = $decodeObject->user;
		$this->token = $decodeObject->token;
		$this->created = $decodeObject->created;
		$this->modified = $decodeObject->modified;
		$this->active = $decodeObject->active;
	}

	public static function CreateCookie($userObject) {
		$sessionObject = new Session();
		$sessionObject->user = $userObject;
		$sessionObject->token = User::generate_uuid();
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
		Database::$connection->query($sqlExpression);
	}

	private static function getUserCookie($cookie) {
		$receivedCookie = json_decode($cookie);
		$cookieFromDB = "
			SELECT * FROM sessions WHERE token = {$receivedCookie}
		";
		$dbResponseByToken = Database::$connection->query($cookieFromDB);
		if($dbResponseByToken->num_rows == 1) {
			$responseRowByToken = $dbResponseByToken->fetch_assoc();
			$responseRowByToken["id"] = intval($responseRowByToken["id"]);
			$responseObjectByToken = new Session(json_encode($responseRowByToken));

		}
		else {
			return NULL;
		}
		$userRow = "
			SELECT * FROM users WHERE id = {$responseObjectByToken->user}
		";
		$dbResponseByUserId = Database::$connection->query($userRow);
		if($dbResponseByUserId->num_rows == 1) {
			$responseRowByUserId = $dbResponseByUserId->fetch_assoc();
			$responseRowByUserId["id"] = intval($responseRowByUserId["id"]);
			$responseObjectByUserId = new User(json_encode($responseRowByUserId));

			User::logIn()
			// что я должен запиисать в виде переданных данных методу logIn если он не принимает аргументы , а принимает phpinput???
			// Если я захожу с помощью logIn  то в этом методе мне выдается куки опять, но я уже имею его, как это предовратить?
		}
		else {
			return NULL;
		}

	}
}
?>
