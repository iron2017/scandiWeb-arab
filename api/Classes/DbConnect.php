<?php 
	
	/**
	* Database Connection
	*/
	class DbConnect
	{
		private $server = 'localhost';
		private $dbname = 'id19537409_id19537409_jdetesttask';
		private $user = 'id19537409_id19537409_nadir';
		private $pass = '8CNAJRseP|DZZ#~i';

		public function connect()
		{
			//try {
				$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			/*} catch (Exception $e) {
				echo "Database Error: " . $e->getMessage();
			}*/
		}
	}
 ?>