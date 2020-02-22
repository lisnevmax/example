<?php

/*
	Этот код и подобные ему можно вывести в отдельные файлы и обращаться к ним через XMLHttpRequest или action, но
	я этого делать не буду. Просто рассказал что так можно и нужно делать...
*/

if(isset($_POST['register'])) // Пользователь нажал на кнопку "Создать учётную запись"
{
	$client_email = $_POST['client_email'];
	$client_password = $_POST['client_password'];
	$client_password_repeat = $_POST['client_password_repeat'];
	
	
	
	$errors = [];
	
	if(!filter_var($client_email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Неверно указан email';
	if(mb_strlen($client_password, 'utf8') < 6) $errors[] = 'Длина пароля не может быть меньше 6 символов';
	if($client_password != $client_password_repeat) $errors[] = 'Указанные пароли не совпадают';
	
	if(sizeof($errors) > 0)
	{
		echo 'Для создания Вашей учётной записи, пожалуйста, исправьте следующие ошибки:<br /><br />';
		
		foreach($errors as $key => $value)
		{
			echo $value . '<br />';
		}
	}
	else
	{
		include $_SERVER['DOCUMENT_ROOT'] . '/class/MySQL.php';
		$MySQL = new MySQL();
		
		$insert_data = [
			'to' => 'clients',
			'columns' => 'email, password',
			'values' => [
				$client_email,
				password_hash($client_password, PASSWORD_DEFAULT)
			]
		];
		
		$creating_client_account_result = $MySQL->insert($insert_data);
		
		if(!empty($creating_client_account_result['last_insert_id'])) // Пользователь создан
		{
			$client_data = [
				'user_id' => $creating_client_account_result['last_insert_id'],
				'employee' => false
			];
			
			$_SESSION['user'] = $client_data; // С этого момента пользователь авторизован
			
			header('Location: /'); // Перезагружаем страницу
		}
		else
		{
			echo 'Что-то пошло не так. Попробуйте попытку позднее.';
		}
	}
}

?>

<?php

if(isset($_GET['badAuth'])) // Если произошла ошибка в момент авторизации
{
	
?>

<b>Такой пользователь не существует или указан неверный пароль.<b/>

<?php

}

?>

<h1>Создание новой учётной записи</h1>

<form method="POST">
	<input type="text" name="client_email" placeholder="Укажите Ваш email" />
	<input type="password" name="client_password" placeholder="Укажите пароль" />
	<input type="password" name="client_password_repeat" placeholder="Повторите пароль" />
	<input type="submit" name="register" value="Создать учётную запись" />
</form>

<h1>Войти в существующую учётную запись</h1>

<form method="POST" action="action/doAuth.php">
	<input type="text" name="login_auth" placeholder="Ваш email" />
	<input type="password" name="password_auth" placeholder="Ваш пароль" />
	<input type="submit" name="doAuth" value="Войти в систему" />
</form>

<h1>Вход для сотрудников</h1>

<form method="POST" action="action/doAuth.php?employee">
	<input type="text" name="login_auth" placeholder="Логин" />
	<input type="password" name="password_auth" placeholder="Пароль" />
	<input type="submit" name="doAuth" value="Войти в систему" />
</form>