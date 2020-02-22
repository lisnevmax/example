<?php

if(!isset($_SESSION['user']))
{
	header('Location: /');
}

$employee_id = $_SESSION['user']['user_id'];



include $_SERVER['DOCUMENT_ROOT'] . '/class/MySQL.php';
include $_SERVER['DOCUMENT_ROOT'] . '/class/Employee.php';

$MySQL = new MySQL();
$Employee = new Employee($MySQL);



if(isset($_POST['change_status'])) // Сотрудник нажал на кнопку "Изменить статус"
{
	$name_id = $_POST['name_id'];
	$new_status = $_POST['status'];
	$deny_reason = $_POST['deny_reason'];
	$status_update_date = date('Y-m-d H:i:s');
	
	
	
	$update_data = [
		'table' => 'names',
		'set' => "status = $new_status, deny_reason = '$deny_reason', status_updated_date = '$status_update_date'",
		'conditions' => "id = $name_id"
	];
	
	if($MySQL->update($update_data))
	{
		// TODO: Отправить клиенту email
		
		header('Location: /');
	}
	else
	{
		echo 'Что-то пошло не так. Попробуйте попытку позднее.';
	}
}

?>

<a href="/?logout">Выйти из системы</a>

<h1>Ожидающие имена клиентов</h1>

<?php

$names = $Employee->get_names();

foreach($names as $key => $value)
{
	
?>

<li>
	
	<?php
	
	echo $names[$key]['name'] . '<br />';
	
	?>
	
	<form method="POST">
		<input type="text" value="<?php echo $names[$key]['id']; ?>" hidden="hidden" name="name_id" />
		<select name="status">
			<option value="2">Одобренно</option>
			<option value="0">Отказ</option>
		</select>
		<textarea placeholder="Причина отказа" name="deny_reason"></textarea>
		<input type="submit" name="change_status" value="Изменить статус">
	</form>
	
</li>

<hr />

<?php

}

?>