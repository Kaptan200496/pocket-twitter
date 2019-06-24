<?php 
class regRequestData {
	public $login;
	public $password;
	public $email;

	function __construct($requestText) {
		$requestVariable = json_decode($requestText);
		$this->login = $requestVariable->login;
		$this->password = $requestVariable->password;
		$this->email = $requestVariable->email;

	}
}
?>
