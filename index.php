<?php

session_start();



if(isset($_GET['logout'])) // Если указан GET-атрибут 'logout', то пользователь хочет выйти из системы
{
	session_destroy();
	header('Location: /');
}




		
if(!isset($_SESSION['user'])) // Если пользователь не авторизован, предлагаем авторизоваться
{
	include 'page/client/auth.php';
}
else
{
	if($_SESSION['user']['employee']) // Если пользователь авторизован и он сотрудник
	{
		include 'page/employee/home.php';
	}
	else // Это клиент
	{
		include 'page/client/home.php';
	}
}

?>