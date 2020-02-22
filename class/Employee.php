<?php

class Employee
{
	private $mysqli;
	
	public function __construct($MySQL)
	{
		$this->mysqli = $MySQL;
	}
	
	public function get_names()
	{
		$required_data = [
			'from' => 'names',
			'columns' => 'id, name, client_id',
			'conditions' => 'status = 1'
		];
		
		$names = $this->mysqli->get($required_data);
		
		return $names;
	}
}

?>