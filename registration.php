<?php 
require_once("class_db.php");
require_once("class_regRequestData.php");
require_once("class_user.php");

$response = json_encode(User::registration());
?>