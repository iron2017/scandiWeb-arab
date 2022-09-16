<?php

namespace App;

use Exception;
use PDO;

class DbConnect
{
	public function connect()
	{
		try {
			$conn = new PDO('mysql:host=' . SERVER . ';dbname=' . DB_NAME, USER, PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (Exception $e) {
			Validator::handle_exceptions($e);
		}
	}
}
