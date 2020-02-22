<?php

class Customer
{
	private $mysqli;
	
	public function __construct($MySQL)
	{
		$this->mysqli = $MySQL;
	}
	
	public function get_client_names($client_id)
	{
		$required_data = [
			'from' => 'names',
			'columns' => 'name, status, deny_reason, status_updated_date',
			'conditions' => "client_id = $client_id"
		];
		
		$client_names = $this->mysqli->get($required_data);
		
		return $client_names;
	}
}

?>