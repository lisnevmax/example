<?php

if(isset($_POST['doAuth'])) // Была нажата кнопка "Войти в систему"
{
	$login = $_POST['login_auth'];
	$password = $_POST['password_auth'];
	
	
	
	include $_SERVER['DOCUMENT_ROOT'] . '/class/MySQL.php';
	
	$MySQL = new MySQL();
	
	
	
	if(isset($_GET['employee'])) // Попытка авторизации от имени сотрудника
	{
		$required_data = [
			'from' => 'employees',
			'columns' => 'id, password',
			'conditions' => "login = '$login'"
		];
		
		$employee = true;
	}
	else // GET-атрибут 'employee' не указан, значит смотрим в таблицу клиентов
	{
		$required_data = [
			'from' => 'clients',
			'columns' => 'id, password',
			'conditions' => "email = '$login'"
		];
		
		$employee = false;
	}
	
	
	
	$user = $MySQL->get($required_data);
	
	if(!empty($user[0]['password'])) // Пользователь существует
	{
		$db_client_password = $user[0]['password'];
		$db_client_id = $user[0]['id'];
		
		if(password_verify($password, $db_client_password)) // Сверяем пароли
		{
			session_start();
		
			$client_data = [
				'user_id' => $db_client_id,
				'employee' => $employee
			];
			
			$_SESSION['user'] = $client_data; // Пользователь авторизован
			
			header('Location: /');
		}
		else
		{
			header('Location: /?badAuth');
		}
	}
	else
	{
		header('Location: /?badAuth');
	}
}

?>