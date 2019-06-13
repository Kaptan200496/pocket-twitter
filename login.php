<?php
// Подключаем файлы с классами User и LoginRequestData
require_once("class_db.php");
require_once("class_user.php");
require_once("class_loginRequestData.php");

$response = json_encode(User::logIn());
print($response);

?>