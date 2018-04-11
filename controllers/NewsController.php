<?php 

/**
* контроллер новостей
*/

class NewsController {

	// Список новостей
	public function actionIndex () {

		$user = User::getAuth();
		$newsList = array();
		$newsList = News::getNewsList();
		
		if (User::getAuth()) 
			require_once(ROOT.'/views/news/index.php');
		else header("location: /");

		return true;
	}
	// Просмотр одной новости
	public function actionView ($id) {

		$user = User::getAuth();
		$newsItem = News::getNewsItemById($id);
		
		if (User::getAuth()) {
			if ($newsItem) require_once(ROOT.'/views/news/view.php');
			else require_once(ROOT.'/views/site/404.php');
		} else header("location: /");

		return true;
	}

}

?>