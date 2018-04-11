<?php

/**
* Models
* class User
*/

class User {

	public $data = array();

	function __construct ($id) {
		$this->data = self::getUserByField('id', $id);
	}

	// Регистрация пользователя
	public static function register ($aData) {
		$db = Db::getConnection();
		$sql = 'INSERT INTO users (name, password, email, salt, code) '
			.'VALUES (:name, :password, :email, :salt, :code)';
		$result = $db->prepare($sql);
		$result->bindParam(':name', $aData['name'], PDO::PARAM_STR);
		$result->bindParam(':password', $aData['password'], PDO::PARAM_STR);
		$result->bindParam(':email', $aData['email'], PDO::PARAM_STR);
		$result->bindParam(':salt', $aData['salt'], PDO::PARAM_STR);
		$result->bindParam(':code', $aData['key'], PDO::PARAM_STR);
		$result->execute();

		return array (
			'status' => true,
			'code' => 0,
			'data' => $result
		);
	}

	// Отправка письма для активации
	public static function sendMailActivate ($aData) {
		$url = HOST.'/register/?key='.$aData['key'];
		$title = 'Регистрация на '.SITE;
		$message = '<h4>Привет '.$aData['name'].'!</h4>'
			.'<p>Для активации акаунта пройдите по ссылке '
			.'<a href="'. $url .'">'. $url .'</a></p>';

		ExtraMethods::sendMailMsg(
			$aData['email'], 
			'Регистрация на '.HOST.'/ <'.MAIL.'>', 
			$title, 
			$message
		);
	}

	// Активация пользователя после регистрации
	public static function activation ($code) {
		$db = Db::getConnection();
		$sql = 'SELECT * FROM users WHERE code = :code';

		$result = $db->prepare($sql);
		$result->bindParam(':code', $code, PDO::PARAM_STR);
		$result->execute();

		$user = $result->fetch();

		$res = array (
			'status' => false,
			'code' => 0,
			'data' => $user
		);

		if ($user) {
			if (intval($user['active']) == 0) {
				//Активируем аккаунт пользователя
				$db = Db::getConnection();
				$sql = 'UPDATE users SET active = :active WHERE id = :id';

				$result = $db->prepare($sql);
				$result->bindParam(':active', $l=1);
				$result->bindParam(':id', $user['id']);
				$result->execute();

				$res['status'] = true;

			} else {
				$res['code'] = 2;
			} 
		} else {
			$res['code'] = 1;
		}

		return $res;
	}

	//Проверка на количество символов
	public static function checkCharCnt ($name = '', $min = 0, $max = 999999) {
		$l = strlen($name);
		if ($l > $min && $l < $max) return true;
		return false;
	}

	//Проверка на кирилические символы
	public static function checkCyrilic ($name) {
		return preg_match('/[А-Яа-яЁё]/u', $name);
	}

	//Проверка емейла
	public static function checkEmail ($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
		return false;
	}

	//Проверка на существования имени
	public static function checkNameExists ($name) {
		$db = Db::getConnection();
		$sql = 'SELECT COUNT(*) FROM users WHERE name = :name';

		$result = $db->prepare($sql);
		$result->bindParam(':name', $name, PDO::PARAM_STR);
		$result->execute();

		if ($result->fetchColumn()) return true;
		return false;
	}

	//Проверка существования емейла в базе
	public static function checkEmailExists ($email) {
		$db = Db::getConnection();
		$sql = 'SELECT COUNT(*) FROM users WHERE email = :email';

		$result = $db->prepare($sql);
		$result->bindParam(':email', $email, PDO::PARAM_STR);
		$result->execute();

		if ($result->fetchColumn()) return true;
		return false;
	}

	// Генерация соли
	public static function getSalt () {
		return substr(md5(uniqid()), -7);
	}

	// Генерация ключа
	public static function getKey ($salt) {
		return md5($salt);
	}

	// Генерация крипто-пароля
	public static function getGenerateForPassword ($pwd) {
		$salt = self::getSalt();
		return array (
			'salt' => $salt,
			'key' => self::getKey($salt),
			'pwd' => md5(md5($pwd).$salt)
		);
	}

	// Сравнение паролей
	public static function comparePassword ($pwd, $pwdUser, $salt) {
		return (md5(md5($pwd).$salt) == $pwdUser);
	}

	// Авторизация
	public static function auth ($userId, $loc = false) {
		self::visitUpdate($userId);
		self::setUserById ('isauth', 1, $userId);
		new User($userId);
		$_SESSION['user'] = $userId;
		if ($loc) header("location: $loc");
	}

	// Выход
	public static function logout () {
		if (isset($_SESSION['user'])) {
			self::setUserById ('isauth', '0', $_SESSION['user']);
			unset($_SESSION['user']);
			header("location: /");
		}
	}

	// Обновление времени визита
	public static function visitUpdate ($userId) {
		self::setUserById ('last_activity', date('Y-m-d H:i:s'), $userId);
	}

	// Проверка на авторизацию
	public static function getAuth () {
		if (isset($_SESSION['user'])) {
			$user = new User($_SESSION['user']);
			if ($user->data['group_id']) $user->data['group'] = self::getGroupById($user->data['group_id']);
			return $user->data;
		}
	}

	// Проверка на активацию аккаунта
	public static function checkActive ($userId) {
		$db = Db::getConnection();
		$sql = 'SELECT active FROM users WHERE id = :id';

		$result = $db->prepare($sql);
		$result->bindParam(':id', $userId, PDO::PARAM_STR);
		$result->execute();

		$user = $result->fetch();

		return intval($user['active']);
	}

	// Получение группы пользователя
	public static function getGroupById ($groupId) {
		$db = Db::getConnection();
		$sql = 'SELECT * FROM user_groups WHERE id = :id';

		$result = $db->prepare($sql);
		$result->bindParam(':id', $groupId, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch();
	}

	// Получение запрашиваемых полей
	public static function getUserByField ($field, $value, $sel = '*') {
		if ($field && $value) {
			$field = "`".str_replace("`","``",$field)."`";

			$db = Db::getConnection();
			$sql = "SELECT $sel FROM users WHERE $field = ?";
			$result = $db->prepare($sql);
			$result->setFetchMode(PDO::FETCH_ASSOC);
			$result->execute(array($value));
			
			return $result->fetch();
		}
		return false;
	}

	// Запись в определённые поля по id 
	public static function setUserById ($field, $value, $id) {
		if ($field && $value && $id) {
			$field = "`".str_replace("`","``",$field)."`";

			$db = Db::getConnection();
			$sql = "UPDATE users SET $field = ? WHERE id = ?";
			$result = $db->prepare($sql);
			$result->execute(array($value, $id));

			return $result;
		}
		return false;
	}

	// Получение списка пользователей
	public static function getUserList () {
		$db = Db::getConnection();
		$sql = 'SELECT * FROM users WHERE group_id < 10';
		$result = $db->prepare($sql);
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$result->execute();
		return $result->fetchAll();
	}


// public static function edit ($userId, $name, $password) {
// 	$db = Db::getConnection();
// 	$sql = 'UPDATE users SET name = :name, password = :password WHERE id = :id';

// 	$result = $db->prepare($sql);
// 	$result->bindParam(':id', $userId, PDO::PARAM_STR);
// 	$result->bindParam(':name', $name, PDO::PARAM_STR);
// 	$result->bindParam(':password', $password, PDO::PARAM_STR);

// 	return $result->execute();
// }

}

?>