<?php

if(!isset($_SESSION['user']))
{
	header('Location: /');
}

$client_id = $_SESSION['user']['user_id'];



include $_SERVER['DOCUMENT_ROOT'] . '/class/MySQL.php';
include $_SERVER['DOCUMENT_ROOT'] . '/class/Customer.php';

$MySQL = new MySQL();
$Customer = new Customer($MySQL);



if(isset($_POST['book_name'])) // Пользователь нажал на кнопку "Занять имя"
{
	$name = $_POST['name'];
	
	
	
	$errors = [];
	
	if(strlen($name) < 3) $errors[] = 'Длина имени не может быть меньше 3 символов';
	if(strlen($name) > 11) $errors[] = 'Длина имени не может превышать 11 символов';
	
	if(sizeof($errors) > 0)
	{
		echo 'Перед резервированием имени, исправьте следующие ошибки:<br /><br />';
		
		foreach($errors as $key => $value)
		{
			echo $value . '<br />';
		}
	}
	else
	{
		$insert_data = [
			'to' => 'names',
			'columns' => 'name, client_id, status',
			'values' => [
				$name,
				$client_id,
				1
			]
		];
		
		$booking_a_name_result = $MySQL->insert($insert_data);
		
		if(!empty($booking_a_name_result['last_insert_id'])) // Имя зарезервировано, ждем ответа сотрудника
		{
			header('Location: /');
		}
		else
		{
			echo 'Что-то пошло не так. Попробуйте попытку позднее.';
		}
	}
}

?>

<a href="/?logout">Выйти из системы</a>

<h1>Заказ имени</h1>

<form method="POST">
	<input type="text" placeholder="Введите имя" name="name" />
	<input type="submit" value="Занять имя" name="book_name" />
</form>

<h1>Имена, ожидающие проверки</h1>

<?php

$client_names = $Customer->get_client_names($client_id);

foreach($client_names as $key => $value)
{
	
?>

<li>
	<?php
	
	switch($client_names[$key]['status'])
	{
		case '1':
			$status = $client_names[$key]['name'] . ' | на проверке';
			break;
			
		case '2':
			$status = $client_names[$key]['name'] . ' | одобренно | ' . $client_names[$key]['status_updated_date'];
			break;
			
		case '0':
			$status = $client_names[$key]['name'] . ' | отказано, причина: ' . $client_names[$key]['deny_reason'] . ' | ' . $client_names[$key]['status_updated_date'];
			break;
	}
	
	echo $status;
	
	?>
</li>

<?php

}

?>