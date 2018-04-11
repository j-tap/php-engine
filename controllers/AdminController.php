<?php 

/**
* контроллер админ панели
*/

class AdminController extends AdminBase {

	public function actionIndex () {
		
		$user = User::getAuth();
		self::checkAdmin();

		require_once(ROOT.'/views/admin/index.php');
		return true;
	}

}

?>