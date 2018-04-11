<?php

/**
* Front Controller
*/

// Общие настройки
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

define('ROOT', dirname(__FILE__));

// Подключение файлов системы
require_once(ROOT.'/components/Autoload.php');

// Вызов Router
$router = new Router();
$router->run();

if (isset($_SESSION['user'])) {
	$user = new User($_SESSION['user']);
	User::visitUpdate($user->data['id']);
}

?>