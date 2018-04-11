<?php

class Db {

	public static function getConnection () {
		try {
			$paramsPath = '/config/db_params.php';
			$params = include($paramsPath);

			$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
			$db =  new PDO($dsn, $params['user'], $params['password']);
			$db->exec("set names utf8");

			return $db;

		} catch(PDOException $e) {
			echo '<p>'. $e->getMessage() .'</p>';
			echo '<p>Проверьте параметры подключения и наличие базы данных, создайте при необходимости.</p>';
			die;
		}
	}

}

?>