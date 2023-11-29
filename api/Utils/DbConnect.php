<?php

namespace App;

use Exception;
use PDO;

class DbConnect
{
	public function connect()
	{
		try {
			$conn = new PDO('mysql:host=127.0.0.1:3308;dbname=scandi', 'root', '');
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (Exception $e) {
			Validator::handle_exceptions($e);
		}
	}
}
