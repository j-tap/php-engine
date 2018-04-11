<?php 

/**
* SiteController
* контроллер страниц
*/

class SiteController {

	public function actionIndex () {

		$user = User::getAuth();

		if ($user) {
			require_once(ROOT.'/views/site/index.php');
		} else {
			//require_once(ROOT.'/views/user/login.php');
			if (!class_exists('UserController')) require_once(ROOT.'/controllers/UserController.php');
			UserController::actionLogin();
		}
		return true;
	}

	public function actionContact () {

		$user = User::getAuth();

		require_once(ROOT.'/views/site/contact.php');
		return true;
	}

	public function action404 () {

		$user = User::getAuth();
		
		require_once(ROOT.'/views/site/404.php');
		return true;
	}

}

?>