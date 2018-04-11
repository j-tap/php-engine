<?php 

/**
* UserController
* контроллер пользователя
*/

class UserController {

	public function actionRegister () {

		if (User::getAuth()) header("location: /");

		$aData = array(
			'name' => '',
			'email' => '',
			'password' => ''
		);

		if (isset($_POST['submit'])) {
			$aData['name'] = $_POST['name'];
			$aData['email'] = $_POST['email'];
			$aData['password'] = $_POST['password'];
		}

		$err = false;
		$aRes = array(
			'status' => false,
			'msg' => '',
			'data' => ''
		);

		// Проверка заполнености
		if (empty($aData['name']) || empty($aData['email']) || empty($aData['password'])) {
			$err[] = 'Необходимо заполнить все поля';
		} else {

			if (!User::checkEmail($aData['email'])) 
				$err[] = 'Не верный формат e-mail';
			elseif (User::checkEmailExists($aData['email'])) 
				$err[] = 'Пользователь с таким e-mail уже зарегистрирован';

			/*if (!User::checkCharCnt($aData['name'], 3, 20)) 
				$err[] = 'Никнейм должен быть не короче 3 символов и умеренной длины';
			elseif (User::checkNameExists($aData['name'])) 
				$err[] = 'Такой никнейм уже занят';*/

			/* if (!User::checkPassword($aData['password'])) 
				$err[] = 'Пароль должен быть не короче 6 символов и умеренной длины';*/

			if ($err == false) {

				// создание пароля
				$aGenerate = User::getGenerateForPassword($aData['password']);
				$aData['salt'] = $aGenerate['salt'];
				$aData['key'] = $aGenerate['key'];
				$aData['password'] = $aGenerate['pwd'];

				$aRegisterResult = User::register($aData);
				
				if ($aRegisterResult['status']) {

					$user = User::getUserByField('email', $aData['email'], 'id');

					// Отправка письма для активации
					//User::sendMailActivate($aData);

					$aRes['msg'] = 'Регистрация прошла успешно';
					$aRes['status'] = true;

					// Авторизация
					User::auth($user['id'], '/');

				} else $err[] = 'Ошибка при регистрации пользователя';

			}
		}
		require_once(ROOT.'/views/user/register.php');
		return true;
	}

	// Авторизвция пользователя
	public static function actionLogin () {

		if (User::getAuth()) header("location: /");
		
		$aData = array(
			'email' => '',
			'password' => ''
		);

		if (isset($_POST['submit'])) {
			$aData['email'] = $_POST['email'];
			$aData['password'] = $_POST['password'];
		}

		$err = false;
		$aRes = array(
			'status' => false,
			'msg' => '',
			'data' => ''
		);

		// Проверка заполнености
		if (empty($aData['email']) || empty($aData['password'])) {
			$err[] = 'Необходимо заполнить все поля';
		} else {

			if (!User::checkEmail($aData['email'])) 
				$err[] = 'Не верный формат e-mail';

			if ($err == false) {

				$user = User::getUserByField('email', $aData['email']);
				
				if ($user) {
					//if (intval($user['active']) == 1) {

						//проверяем пароль
						if (User::comparePassword($aData['password'], $user['password'], $user['salt'])) {
							//Вход
							User::auth($user['id']);

							$aRes['status'] = true;
							$aRes['msg'] = 'Вы вошли';

							header("location: /");

						} else $err[] = 'Пароль с ошибкой, попробуйте ещё раз внимательнее';

					//} else $err[] = 'Вы не активировали свой аккаунт, проверьте свою почту '.$aData['email'];

				} else $err[] = 'Пользователь '.$aData['email'].' ещё не регистрировался';

			}
		}
		require_once(ROOT.'/views/user/login.php');
		return true;
	}

	public function actionLogout () {
		User::logout();
		return true;
	}

	public function actionLk () {
		$user = User::getAuth();

		if (isset($_FILES['file'])) {

			$aRes = array(
				'status' => true,
				'msg' => '',
				'data' => ''
			);

			$dir = '/template/images/';
			$file = $dir . basename($_FILES['file']['name']);

			if ($_FILES['file']['size'] > 500000) {
				$aRes['msg'] = 'Размер файла должен быть меньше 500кб.';
			} else {
				$imageFileType = pathinfo($file, PATHINFO_EXTENSION);
				$aRes['msg'] = 'Файл сохранён';
			}
			echo json_encode($aRes);

		} elseif (isset($_POST['data'])) {

			$aData = $_POST['data'];
			$aRes = array(
				'status' => false,
				'msg' => '',
				'data' => ''
			);
			$err = false;
			// Изменение пароля почты и т.д.
			if (isset($aData['changesetting'])) {

				foreach ($aData as $field => $val) {
					if (empty($val)) continue;
					switch ($field) {
						case 'changesetting':
							continue;
							break;
						case 'password':
							if (!User::checkPassword($aData['password'])) {
								$err[] = 'Пароль должен быть не короче 6 символов и умеренной длины';
								continue;
							};
							$aGenPwd = User::getGenerateForPassword($val);
							User::setUserById ('salt', $aGenPwd['salt'], $user['id']);
							User::setUserById ('password', $aGenPwd['pwd'], $user['id']);
							break;
						case 'file':
							//$aRes['data'] = $_FILES['file']['size'];
							//var_dump($_FILES);
							//$_FILES['file']['size']
							//$file = $val
							break;
						// default:
						// 	User::setUserById ($field, $val, $user['id']);
					}
				}

				if ($err == false) {
					$aRes['status'] = true;
					$aRes['msg'] = 'Настройки сохранены';
				} else {
					$aRes['msg'] = $err;
				}
				echo json_encode($aRes);

			} else echo json_encode($aRes);

		} else {
			
			require_once(ROOT.'/views/user/lk.php');
		}
		return true;
	}

	public function actionAllusers () {
		$user = User::getAuth();
		
		if ($user && $user['active']) {

			$allUsers = User::getUserList();

			require_once(ROOT.'/views/user/all.php');

		} else header("location: /");

		return true;
	}
}

?>