<?php

/**
* Models
* class News
*/

class News {
	
	public static function getNewsItemById ($id) {
		$id = intval($id);
		if ($id) {
			$db = Db::getConnection();

			$result = $db->query(
				'SELECT id, title, date, text '
				.'FROM news '
				.'WHERE active = 1 AND id ='.$id
			);

			if ($result) {
				$result->setFetchMode(PDO::FETCH_ASSOC); // отображать только названия ключей, без индексов
				$newsItem = $result->fetch();

				return $newsItem;
			}
		}
		return false;
	}

	public static function getNewsList () {
		$db = Db::getConnection();

		$newsList = array();

		$result = $db->query(
			'SELECT id, title, date, preview '
			.'FROM news '
			.'WHERE active = 1 '
			.'ORDER BY date DESC '
			.'LIMIT 10 '
		);

		if ($result) {
			$i = 0;
			while($row = $result->fetch()) {
				$newsList[$i]['id'] = $row['id'];
				$newsList[$i]['title'] = $row['title'];
				$newsList[$i]['date'] = $row['date'];
				$newsList[$i]['preview'] = $row['preview'];
				$i++;
			}
		}

		return $newsList;
	}

}

?>