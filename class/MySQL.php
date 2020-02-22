<?php

 // TODO: Реализовать проверку на дублирование информации для определенных таблиц и подготавливаемые запросы

class MySQL
{
	public $conn;
	
	public function __construct()
	{
		include $_SERVER['DOCUMENT_ROOT'] . '/include/db.php';
		
		$conn = new mysqli($host, $user, $password, $database);
		
		if(!$conn->connect_errno)
		{
			$conn->set_charset('utf8');
			
			$this->conn = $conn;
		}
		else
		{
			exit('Не могу соединиться с MySQL сервером.');
		}
	}
	
	public function insert($insert_data)
	{
		$table = $insert_data['to'];
		$columns = $insert_data['columns'];
		$values = implode("','", $insert_data['values']);
		
		if($this->conn->query("INSERT INTO `$table` ($columns) VALUES ('$values')"))
		{
			$return_data = [
				'last_insert_id' => $this->conn->insert_id
			];
			
			return $return_data;
		}
		
		return false;
	}
	
	public function get($required_data)
	{
		$table = $required_data['from'];
		$columns = $required_data['columns'];
		$conditions = $required_data['conditions'];
		
		if($query = $this->conn->query("SELECT $columns FROM `$table` WHERE $conditions"))
		{
			$selected_data = [];
			
			while($data = $query->fetch_assoc())
			{
				$selected_data[] = $data;
			}
			
			return $selected_data;
		}
		
		return false;
	}
	
	public function update($update_data)
	{
		$table = $update_data['table'];
		$set = $update_data['set'];
		$conditions = $update_data['conditions'];
		
		if($query = $this->conn->query("UPDATE `$table` SET $set WHERE $conditions"))
		{
			return true;
		}
		
		return false;
	}
}

?>