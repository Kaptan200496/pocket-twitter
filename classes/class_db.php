<?php

class DB {
	private static $host = "localhost";
	private static $user = "kaptan200496";
	private static $password = "ronaldorm9";
	private static $dbName = "pocket_twitter";

	public static $connection;

	public static function connect() {
		self::$connection = new mysqli(self::$host, self::$user, self::$password, self::$dbName);
	}
}

DB::connect();